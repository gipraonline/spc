<?php

namespace App\Services\Hr;

use App\Services\Hr\AssociateScope;
use App\Models\Hr\Attendance;
use App\Models\Hr\Employee;
use App\Models\Hr\Holiday;
use App\Models\Hr\IncentivePayout;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\PayrollRun;
use App\Models\Hr\Payslip;
use App\Models\Hr\PfContribution;
use App\Models\Hr\SalaryStructure;
use App\Models\Hr\SystemSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Monthly payroll engine for a private limited company in India.
 *
 *  1. Pay days   = days in month - days before joining / after exit - loss-of-pay days.
 *                  LOP comes from unpaid leave, absences and half days; weekly offs and
 *                  declared holidays are paid.
 *  2. Earnings   = salary structure x pay days / days in month, plus approved incentives.
 *  3. Deductions = PF (employee), ESI (employee), professional tax, TDS (Sec 192 projection,
 *                  new regime), recurring deduction (loan / advance).
 *  4. Employer   = PF, ESI, PF admin + EDLI  -> total cost to company.
 *
 * calculate() never writes anything (used for the preview); process() persists a run.
 */
class PayrollCalculator
{
    private array $settings;

    private const DEFAULTS = [
        'weekly_off_days' => '0',
        'missing_attendance_as' => 'present',
        'pf_contribution_rate' => '12',
        'pf_wage_ceiling' => '15000',
        'pf_cap_wages' => '1',
        'pf_admin_edli_rate' => '1',
        'esi_threshold' => '21000',
        'esi_employee_rate' => '0.75',
        'esi_employer_rate' => '3.25',
        'pt_enabled' => '1',
        'pt_deduction_mode' => 'monthly',
        'pt_slabs' => '[[0,0],[12000,320],[18000,450],[30000,600],[45000,750],[60000,1000],[125000,1250]]',
        'tds_enabled' => '1',
        'tds_standard_deduction' => '75000',
    ];

    public function __construct()
    {
        $this->settings = array_merge(
            self::DEFAULTS,
            SystemSetting::pluck('setting_value', 'setting_key')->filter(fn ($v) => $v !== null && $v !== '')->all()
        );
    }

    /* ------------------------------------------------------------------ */
    /*  Public API                                                        */
    /* ------------------------------------------------------------------ */

    /**
     * Compute payslips for every employee in scope for the month. Nothing is saved.
     *
     * @return array{rows: array<int,array>, totals: array, skipped: array<int,array>, month: int, year: int}
     */
    public function calculate(int $month, int $year): array
    {
        $monthStart = Carbon::create($year, $month, 1)->startOfDay();
        $monthEnd = $monthStart->copy()->endOfMonth()->startOfDay();

        $employees = Employee::with(['user', 'department', 'designation'])
            ->where('date_of_joining', '<=', $monthEnd->toDateString())
            ->where(function ($q) use ($monthStart) {
                $q->whereNull('date_of_exit')->orWhere('date_of_exit', '>=', $monthStart->toDateString());
            })
            ->where(function ($q) {
                // an "exited" employee with no exit date is data we cannot place in a month
                $q->where('employment_status', '!=', 'exited')->orWhereNotNull('date_of_exit');
            })
            // associates (Farm Care Advisers / Tele Callers) earn commission, not salary
            ->where(fn ($q) => AssociateScope::exclude($q))
            ->orderBy('employee_code')
            ->get();

        $ctx = $this->loadContext($employees, $monthStart, $monthEnd, $month, $year);

        $rows = [];
        $skipped = [];

        foreach ($employees as $employee) {
            $structure = $this->structureFor($employee->id, $monthStart, $monthEnd);

            if (! $structure) {
                $skipped[] = ['employee' => $employee, 'reason' => 'No salary structure on file'];
                continue;
            }

            $rows[] = $this->calculateEmployee($employee, $structure, $ctx, $monthStart, $monthEnd, $month, $year);
        }

        return [
            'month' => $month,
            'year' => $year,
            'rows' => $rows,
            'skipped' => $skipped,
            'totals' => $this->totals($rows),
        ];
    }

    /** Calculate and persist a payroll run (status "processed"). */
    public function process(int $month, int $year, ?int $userId, ?string $notes = null): PayrollRun
    {
        return DB::connection('spc_hr')->transaction(function () use ($month, $year, $userId, $notes) {
            if (PayrollRun::where('month', $month)->where('year', $year)->lockForUpdate()->exists()) {
                throw new \RuntimeException('Payroll for that month already exists.');
            }

            $result = $this->calculate($month, $year);

            if (empty($result['rows'])) {
                throw new \RuntimeException('No employees with a salary structure were found for that month.');
            }

            $run = PayrollRun::create([
                'month' => $month,
                'year' => $year,
                'status' => 'processed',
                'employee_count' => count($result['rows']),
                'total_gross' => $result['totals']['gross'],
                'total_deductions' => $result['totals']['deductions'],
                'total_net' => $result['totals']['net'],
                'total_employer_cost' => $result['totals']['employer_cost'],
                'processed_by' => $userId,
                'processed_at' => now(),
                'notes' => $notes,
            ]);

            foreach ($result['rows'] as $row) {
                Payslip::create([
                    'payroll_run_id' => $run->id,
                    'employee_id' => $row['employee']->id,
                    'days_in_month' => $row['days_in_month'],
                    'paid_days' => $row['paid_days'],
                    'lop_days' => $row['lop_days'],
                    'basic_earned' => $row['basic'],
                    'hra_earned' => $row['hra'],
                    'allowances_earned' => $row['allowances'],
                    'variable_earned' => $row['variable'],
                    'incentive_pay' => $row['incentive'],
                    'gross_pay' => $row['gross'],
                    'pf_deduction' => $row['pf'],
                    'esi_deduction' => $row['esi'],
                    'professional_tax' => $row['pt'],
                    'tds_deduction' => $row['tds'],
                    'other_deductions' => $row['other'],
                    'net_pay' => $row['net'],
                    'employer_pf' => $row['employer_pf'],
                    'employer_esi' => $row['employer_esi'],
                    'employer_admin_charges' => $row['employer_admin'],
                    'calc_detail' => $row['detail'],
                    'generated_at' => now(),
                ]);

                PfContribution::create([
                    'employee_id' => $row['employee']->id,
                    'payroll_run_id' => $run->id,
                    'employee_share' => $row['pf'],
                    'employer_share' => $row['employer_pf'],
                ]);

                if (! empty($row['incentive_ids'])) {
                    IncentivePayout::whereIn('id', $row['incentive_ids'])->update([
                        'status' => 'included_in_payroll',
                        'payroll_run_id' => $run->id,
                    ]);
                }
            }

            return $run;
        });
    }

    /** Throw away a processed (not yet paid) run so it can be run again after fixing inputs. */
    public function discard(PayrollRun $run): void
    {
        if ($run->status === 'paid') {
            throw new \RuntimeException('A run that is marked paid cannot be discarded.');
        }

        DB::connection('spc_hr')->transaction(function () use ($run) {
            IncentivePayout::where('payroll_run_id', $run->id)->update([
                'status' => 'approved',
                'payroll_run_id' => null,
            ]);
            PfContribution::where('payroll_run_id', $run->id)->delete();
            Payslip::where('payroll_run_id', $run->id)->delete();
            $run->delete();
        });
    }

    public function markPaid(PayrollRun $run): void
    {
        if ($run->status !== 'processed') {
            throw new \RuntimeException('Only a processed run can be marked paid.');
        }

        DB::connection('spc_hr')->transaction(function () use ($run) {
            $run->update(['status' => 'paid', 'paid_at' => now()]);
            IncentivePayout::where('payroll_run_id', $run->id)->update(['status' => 'paid']);
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Per-employee calculation                                          */
    /* ------------------------------------------------------------------ */

    private function calculateEmployee(
        Employee $employee,
        SalaryStructure $st,
        array $ctx,
        Carbon $monthStart,
        Carbon $monthEnd,
        int $month,
        int $year
    ): array {
        $daysInMonth = $monthStart->daysInMonth;
        $warnings = [];

        /* ---- 1. pay days ---------------------------------------------- */
        $empStart = Carbon::parse($employee->date_of_joining)->startOfDay();
        $empEnd = $employee->date_of_exit ? Carbon::parse($employee->date_of_exit)->startOfDay() : null;

        $notEmployed = 0;
        $workingDays = 0;
        $weeklyOffs = 0;
        $holidays = 0;
        $lop = 0.0;
        $paidLeave = 0.0;
        $unpaidLeave = 0.0;
        $unrecorded = 0;
        $treatMissingAsAbsent = ($this->settings['missing_attendance_as'] === 'absent');

        for ($d = $monthStart->copy(); $d->lte($monthEnd); $d->addDay()) {
            $key = $d->toDateString();

            if ($d->lt($empStart) || ($empEnd && $d->gt($empEnd))) {
                $notEmployed++;
                continue;
            }

            if (in_array($d->dayOfWeek, $ctx['weeklyOff'], true)) {
                $weeklyOffs++;
                continue;
            }

            if (isset($ctx['holidays'][$key])) {
                $holidays++;
                continue;
            }

            $workingDays++;

            $leave = $ctx['leave'][$employee->id][$key] ?? ['paid' => 0.0, 'unpaid' => 0.0];
            $unpaidPart = min(1.0, $leave['unpaid']);
            $paidPart = min(1.0 - $unpaidPart, $leave['paid']);
            $needsAttendance = 1.0 - $unpaidPart - $paidPart;

            $lop += $unpaidPart;
            $unpaidLeave += $unpaidPart;
            $paidLeave += $paidPart;

            if ($needsAttendance <= 0) {
                continue;
            }

            $att = $ctx['attendance'][$employee->id][$key] ?? null;

            if (! $att) {
                if ($treatMissingAsAbsent) {
                    $lop += $needsAttendance;
                } else {
                    $unrecorded++;
                }
                continue;
            }

            switch ($att) {
                case 'absent':
                    $lop += $needsAttendance;
                    break;
                case 'half_day':
                    $lop += min($needsAttendance, 0.5);
                    break;
                default:
                    // present / late / on_leave (leave without a request on file is treated as paid)
            }
        }

        $lop = min($lop, (float) $workingDays);
        $paidDays = max(0.0, round($daysInMonth - $notEmployed - $lop, 2));
        $factor = $daysInMonth > 0 ? $paidDays / $daysInMonth : 0.0;

        if ($unrecorded > 0) {
            $warnings[] = "{$unrecorded} working day(s) have no attendance record and were treated as present.";
        }
        if ($paidDays <= 0) {
            $warnings[] = 'No payable days this month.';
        }

        /* ---- 2. earnings ---------------------------------------------- */
        $basic = round((float) $st->basic * $factor, 2);
        $hra = round((float) $st->hra * $factor, 2);
        $allowances = round((float) $st->other_allowances * $factor, 2);
        $variable = round((float) $st->variable_pay * $factor, 2);

        $incentives = $ctx['incentives'][$employee->id] ?? collect();
        $incentive = round((float) $incentives->sum('incentive_amount'), 2);
        $incentiveIds = $incentives->pluck('id')->all();

        $gross = round($basic + $hra + $allowances + $variable + $incentive, 2);
        $baseGross = (float) $st->gross_monthly;

        /* ---- 3. statutory deductions ---------------------------------- */
        // PF
        $pf = 0.0;
        $employerPf = 0.0;
        $employerAdmin = 0.0;
        $pfWages = 0.0;
        $ceiling = (float) $this->settings['pf_wage_ceiling'];

        if ($st->pf_applicable && $basic > 0) {
            $pfWages = ($this->settings['pf_cap_wages'] === '1') ? min($basic, $ceiling) : $basic;
            $rate = (float) $this->settings['pf_contribution_rate'] / 100;
            $pf = round($pfWages * $rate, 0);
            $employerPf = $pf;
            $employerAdmin = round(min($basic, $ceiling) * (float) $this->settings['pf_admin_edli_rate'] / 100, 0);
        }

        // ESI: eligibility is decided on the contractual monthly gross, contribution on what is earned
        $esi = 0.0;
        $employerEsi = 0.0;
        $esiEligible = $st->esi_applicable && $baseGross <= (float) $this->settings['esi_threshold'] && $gross > 0;

        if ($esiEligible) {
            $esi = ceil($gross * (float) $this->settings['esi_employee_rate'] / 100);
            $employerEsi = ceil($gross * (float) $this->settings['esi_employer_rate'] / 100);
        }

        // Professional tax
        $pt = 0.0;
        $ptHalfYearly = 0.0;
        if ($this->settings['pt_enabled'] === '1' && $st->pt_applicable && $paidDays > 0) {
            $ptHalfYearly = $this->professionalTaxHalfYear($baseGross * 6);
            $pt = $this->professionalTaxForMonth($ptHalfYearly, $month);
        }

        // TDS
        $tdsInfo = ['method' => 'off'];
        $tds = 0.0;
        if ($this->settings['tds_enabled'] === '1' && $gross > 0) {
            if ($st->tds_monthly_override !== null) {
                $tds = round((float) $st->tds_monthly_override, 0);
                $tdsInfo = ['method' => 'manual override'];
            } else {
                [$tds, $tdsInfo] = $this->tdsForMonth($employee, $empEnd, $baseGross, $gross, $month, $year, $ctx);
            }
        }

        $other = round((float) $st->other_deduction, 2);

        /* ---- 4. totals -------------------------------------------------- */
        $deductions = round($pf + $esi + $pt + $tds + $other, 2);
        $net = round($gross - $deductions, 2);

        if ($net < 0) {
            $warnings[] = 'Deductions exceed earnings; net pay held at zero. Review the recurring deduction / TDS.';
            $deductions = $gross;
            $net = 0.0;
        }

        $employerCost = round($gross + $employerPf + $employerEsi + $employerAdmin, 2);

        return [
            'employee' => $employee,
            'structure' => $st,
            'days_in_month' => $daysInMonth,
            'paid_days' => $paidDays,
            'lop_days' => round($lop, 2),
            'basic' => $basic,
            'hra' => $hra,
            'allowances' => $allowances,
            'variable' => $variable,
            'incentive' => $incentive,
            'incentive_ids' => $incentiveIds,
            'gross' => $gross,
            'pf' => $pf,
            'esi' => $esi,
            'pt' => $pt,
            'tds' => $tds,
            'other' => $other,
            'deductions' => $deductions,
            'net' => $net,
            'employer_pf' => $employerPf,
            'employer_esi' => $employerEsi,
            'employer_admin' => $employerAdmin,
            'employer_cost' => $employerCost,
            'warnings' => $warnings,
            'detail' => [
                'working_days' => $workingDays,
                'weekly_offs' => $weeklyOffs,
                'holidays' => $holidays,
                'not_employed_days' => $notEmployed,
                'paid_leave_days' => round($paidLeave, 2),
                'unpaid_leave_days' => round($unpaidLeave, 2),
                'unrecorded_days' => $unrecorded,
                'pf_wages' => $pfWages,
                'esi_eligible' => $esiEligible,
                'pt_half_yearly_slab' => $ptHalfYearly,
                'tds' => $tdsInfo,
                'structure_gross' => $baseGross,
            ],
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Statutory helpers                                                 */
    /* ------------------------------------------------------------------ */

    /** Half-yearly professional tax for a half-yearly gross, from the configured slabs. */
    private function professionalTaxHalfYear(float $halfYearGross): float
    {
        $slabs = json_decode($this->settings['pt_slabs'], true);
        if (! is_array($slabs)) {
            return 0.0;
        }

        usort($slabs, fn ($a, $b) => $a[0] <=> $b[0]);

        $tax = 0.0;
        foreach ($slabs as [$from, $amount]) {
            if ($halfYearGross >= $from) {
                $tax = (float) $amount;
            }
        }

        return $tax;
    }

    /**
     * Kerala assesses PT per half-year (Apr-Sep, Oct-Mar). "monthly" spreads it over six payslips
     * (last month of the half absorbs rounding); "half_yearly" deducts it all in Sep and Mar.
     */
    private function professionalTaxForMonth(float $halfYearTax, int $month): float
    {
        if ($halfYearTax <= 0) {
            return 0.0;
        }

        $position = (($month - 4 + 12) % 12) % 6; // 0..5 within the half-year
        $isLast = $position === 5;

        if ($this->settings['pt_deduction_mode'] === 'half_yearly') {
            return $isLast ? $halfYearTax : 0.0;
        }

        $share = round($halfYearTax / 6, 2);

        return $isLast ? round($halfYearTax - 5 * $share, 2) : $share;
    }

    /**
     * Section 192 style TDS: project the full financial year, compute the tax,
     * subtract what has already been deducted and spread the balance over the remaining months.
     *
     * @return array{0: float, 1: array}
     */
    private function tdsForMonth(Employee $employee, ?Carbon $empEnd, float $baseGross, float $gross, int $month, int $year, array $ctx): array
    {
        $index = ($month - 4 + 12) % 12;       // 0 = April
        $monthsLeft = 12 - $index;             // including this month

        // leaving during the year: this is the last salary, settle the tax in full
        if ($empEnd) {
            $fyEnd = Carbon::create($month >= 4 ? $year + 1 : $year, 3, 31);
            if ($empEnd->lte($fyEnd)) {
                $exitIndex = (($empEnd->month - 4 + 12) % 12);
                $monthsLeft = max(1, min($monthsLeft, $exitIndex - $index + 1));
            }
        }

        $ytd = $ctx['ytd'][$employee->id] ?? ['gross' => 0.0, 'tds' => 0.0, 'months' => 0];

        // Months of this financial year before the current one in which the employee was on the payroll.
        // Where this system holds no payslip for such a month (payroll started mid-year, or the month was
        // paid elsewhere) assume it was paid at the contractual gross with no tax deducted, so the tax for
        // the whole year is still caught up over the remaining months.
        $fyStart = Carbon::create($month >= 4 ? $year : $year - 1, 4, 1)->startOfDay();
        $current = Carbon::create($year, $month, 1)->startOfDay();
        $joinMonth = Carbon::parse($employee->date_of_joining)->startOfMonth();
        $firstMonth = $joinMonth->gt($fyStart) ? $joinMonth : $fyStart;
        $monthsBefore = $firstMonth->lt($current) ? (int) $firstMonth->diffInMonths($current) : 0;
        $missingMonths = max(0, $monthsBefore - (int) $ytd['months']);

        $priorGross = $ytd['gross'] + $missingMonths * $baseGross;
        $projected = $priorGross + $gross + max(0, $monthsLeft - 1) * $baseGross;
        $taxable = max(0.0, $projected - (float) $this->settings['tds_standard_deduction']);
        $annualTax = $this->incomeTaxNewRegime($taxable);
        $tds = max(0.0, round(($annualTax - $ytd['tds']) / $monthsLeft, 0));

        return [$tds, [
            'method' => 'projected annual tax (new regime)',
            'projected_income' => round($projected, 2),
            'taxable_income' => round($taxable, 2),
            'annual_tax' => round($annualTax, 2),
            'tds_so_far' => round($ytd['tds'], 2),
            'months_assumed' => $missingMonths,
            'months_left' => $monthsLeft,
        ]];
    }

    private function incomeTaxNewRegime(float $taxable): float
    {
        $cfg = config('payroll.tds');

        $tax = 0.0;
        $lower = 0.0;
        foreach ($cfg['new_regime_slabs'] as [$upper, $rate]) {
            if ($taxable <= $lower) {
                break;
            }
            $slice = ($upper === null ? $taxable : min($taxable, $upper)) - $lower;
            $tax += $slice * $rate;
            $lower = $upper ?? $taxable;
        }

        if ($taxable <= $cfg['rebate_limit']) {
            $tax = max(0.0, $tax - min($tax, $cfg['rebate_max']));
        } else {
            // marginal relief just above the rebate limit
            $tax = min($tax, $taxable - $cfg['rebate_limit']);
        }

        return round($tax * (1 + $cfg['cess']), 2);
    }

    /* ------------------------------------------------------------------ */
    /*  Data loading                                                      */
    /* ------------------------------------------------------------------ */

    private function structureFor(int $employeeId, Carbon $monthStart, Carbon $monthEnd): ?SalaryStructure
    {
        // the structure in force at the end of the month (a mid-month revision applies for the whole month)
        return SalaryStructure::where('employee_id', $employeeId)
            ->where('effective_from', '<=', $monthEnd->toDateString())
            ->where(function ($q) use ($monthStart) {
                $q->whereNull('effective_to')->orWhere('effective_to', '>=', $monthStart->toDateString());
            })
            ->orderByDesc('effective_from')
            ->orderByDesc('id')
            ->first();
    }

    private function loadContext($employees, Carbon $monthStart, Carbon $monthEnd, int $month, int $year): array
    {
        $ids = $employees->pluck('id')->all();

        $weeklyOff = collect(explode(',', (string) $this->settings['weekly_off_days']))
            ->map(fn ($v) => trim($v))
            ->filter(fn ($v) => $v !== '' && is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->values()
            ->all();

        $holidays = Holiday::whereBetween('holiday_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->where('is_optional', 0)
            ->pluck('name', 'holiday_date')
            ->mapWithKeys(fn ($name, $date) => [Carbon::parse($date)->toDateString() => $name])
            ->all();

        $attendance = [];
        Attendance::whereIn('employee_id', $ids)
            ->whereBetween('attendance_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get(['employee_id', 'attendance_date', 'status'])
            ->each(function ($row) use (&$attendance) {
                $attendance[$row->employee_id][Carbon::parse($row->attendance_date)->toDateString()] = $row->status;
            });

        $leave = [];
        LeaveRequest::query()
            ->join('leave_types', 'leave_types.id', '=', 'leave_requests.leave_type_id')
            ->whereIn('leave_requests.employee_id', $ids)
            ->where('leave_requests.status', 'approved')
            ->where('leave_requests.start_date', '<=', $monthEnd->toDateString())
            ->where('leave_requests.end_date', '>=', $monthStart->toDateString())
            ->get(['leave_requests.employee_id', 'leave_requests.start_date', 'leave_requests.end_date', 'leave_requests.days', 'leave_types.is_paid'])
            ->each(function ($req) use (&$leave, $monthStart, $monthEnd) {
                $start = Carbon::parse($req->start_date)->startOfDay();
                $end = Carbon::parse($req->end_date)->startOfDay();
                $weight = ($start->equalTo($end) && (float) $req->days < 1) ? (float) $req->days : 1.0;
                $bucket = $req->is_paid ? 'paid' : 'unpaid';

                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    if ($d->lt($monthStart) || $d->gt($monthEnd)) {
                        continue;
                    }
                    $key = $d->toDateString();
                    $leave[$req->employee_id][$key] ??= ['paid' => 0.0, 'unpaid' => 0.0];
                    $leave[$req->employee_id][$key][$bucket] += $weight;
                }
            });

        // approved incentives not yet paid out through a payroll run, for this month or earlier
        $periodKey = $year * 100 + $month;
        $incentives = IncentivePayout::whereIn('employee_id', $ids)
            ->where('status', 'approved')
            ->whereNull('payroll_run_id')
            ->whereRaw('(year * 100 + month) <= ?', [$periodKey])
            ->get()
            ->groupBy('employee_id')
            ->all();

        // year-to-date gross and TDS from earlier runs in this financial year
        $fyStartYear = $month >= 4 ? $year : $year - 1;
        $fyStartKey = $fyStartYear * 100 + 4;

        $ytd = [];
        Payslip::query()
            ->join('payroll_runs', 'payroll_runs.id', '=', 'payslips.payroll_run_id')
            ->whereIn('payslips.employee_id', $ids)
            ->whereRaw('(payroll_runs.year * 100 + payroll_runs.month) >= ?', [$fyStartKey])
            ->whereRaw('(payroll_runs.year * 100 + payroll_runs.month) < ?', [$periodKey])
            ->selectRaw('payslips.employee_id, COUNT(*) as months, SUM(payslips.gross_pay) as gross, SUM(payslips.tds_deduction) as tds')
            ->groupBy('payslips.employee_id')
            ->get()
            ->each(function ($row) use (&$ytd) {
                $ytd[$row->employee_id] = ['gross' => (float) $row->gross, 'tds' => (float) $row->tds, 'months' => (int) $row->months];
            });

        return compact('weeklyOff', 'holidays', 'attendance', 'leave', 'incentives', 'ytd');
    }

    private function totals(array $rows): array
    {
        $sum = fn (string $key) => round(array_sum(array_column($rows, $key)), 2);

        return [
            'count' => count($rows),
            'gross' => $sum('gross'),
            'pf' => $sum('pf'),
            'esi' => $sum('esi'),
            'pt' => $sum('pt'),
            'tds' => $sum('tds'),
            'other' => $sum('other'),
            'deductions' => $sum('deductions'),
            'net' => $sum('net'),
            'incentive' => $sum('incentive'),
            'employer_pf' => $sum('employer_pf'),
            'employer_esi' => $sum('employer_esi'),
            'employer_admin' => $sum('employer_admin'),
            'employer_cost' => $sum('employer_cost'),
            'warnings' => array_sum(array_map(fn ($r) => count($r['warnings']), $rows)),
        ];
    }
}
