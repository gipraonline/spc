<?php

namespace App\Http\Controllers\Hr;

use App\Exports\TableExport;
use App\Models\Hr\AuditLog;
use App\Models\Hr\Employee;
use App\Models\Hr\PayrollRun;
use App\Models\Hr\Payslip;
use App\Models\Hr\SalaryStructure;
use App\Models\Hr\SystemSetting;
use App\Services\Hr\PayrollCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('payroll');
        $employee = $this->currentEmployee();
        $role = $this->currentRole();

        $viewedEmployee = $employee;
        $directory = collect();

        if ($this->isHrOrAbove()) {
            $directory = Employee::with('user')->get();
            if ($request->filled('employee')) {
                $viewedEmployee = Employee::with('user')->find($request->integer('employee')) ?? $employee;
            } elseif (! $viewedEmployee) {
                $viewedEmployee = $directory->first();
            }
        }

        $salaryStructure = $viewedEmployee ? $viewedEmployee->salaryStructures()->orderByDesc('effective_from')->first() : null;
        // $payslips = $viewedEmployee ? $viewedEmployee->payslips()->with('payrollRun')->orderByDesc('id')->get() : collect();
        $payslips = $viewedEmployee
    ? Payslip::query()
        ->where('employee_id', $viewedEmployee->id)
        ->with('payrollRun')
        ->orderByDesc('id')
        ->get()
    : collect();

        $runs = collect();
        $runsByDepartment = collect();
        if ($this->isHrOrAbove()) {
            $runs = PayrollRun::orderByDesc('year')->orderByDesc('month')->get();
            $latestRun = $runs->first();
            if ($latestRun) {
                $runsByDepartment = Payslip::where('payroll_run_id', $latestRun->id)
                    ->join('employees', 'employees.id', '=', 'payslips.employee_id')
                    ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                    ->selectRaw('COALESCE(departments.name, "Unassigned") as department, count(*) as headcount, sum(payslips.gross_pay) as gross, sum(payslips.net_pay) as net')
                    ->groupBy('departments.name')
                    ->orderByDesc('gross')
                    ->get();
            }
        }

        // Super Admin only: Salary Structure / Run Payroll / Payslip History tabs.
        $superAdminData = [];
        if ($role === 'super_admin') {
            $activeEmployees = Employee::with(['user', 'currentSalaryStructure'])
                ->whereIn('employment_status', ['active', 'on_notice'])->orderBy('employee_code')->get();

            $lastRun = $runs->first();

            $slipQuery = Payslip::with(['employee.user', 'payrollRun'])->orderByDesc('id');
            $filterRun = $request->integer('run') ?: null;
            if ($filterRun) {
                $slipQuery->where('payroll_run_id', $filterRun);
            }

            // Preview: nothing is saved, it only shows what a run would produce.
            $preview = null;
            $activeTab = $request->query('tab', 'salary');
            if ($request->filled('preview_month') && $request->filled('preview_year')) {
                $pm = (int) $request->query('preview_month');
                $py = (int) $request->query('preview_year');
                if ($pm >= 1 && $pm <= 12 && $py >= 2020 && $py <= 2100) {
                    $preview = app(PayrollCalculator::class)->calculate($pm, $py);
                    $preview['already_run'] = PayrollRun::where('month', $pm)->where('year', $py)->first();
                    $activeTab = 'run';
                }
            }

            $superAdminData = [
                'activeEmployees' => $activeEmployees,
                'activeEmployeeCount' => $activeEmployees->count(),
                'lastCycleNetPay' => $lastRun ? (float) $lastRun->total_net ?: $lastRun->payslips()->sum('net_pay') : 0,
                'cyclesFinalized' => $runs->count(),
                'currentCycleMonth' => now()->format('F'),
                'totalPayslips' => Payslip::count(),
                'allPayslips' => $slipQuery->paginate(20, ['*'], 'payslipsPage')->withQueryString(),
                'filterRun' => $filterRun,
                'preview' => $preview,
                'activeTab' => $filterRun ? 'history' : $activeTab,
                'previewMonth' => $request->query('preview_month', now()->subMonth()->month),
                'previewYear' => $request->query('preview_year', now()->subMonth()->year),
            ];
        }

        return view('hr.modules.payroll', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'payroll',
            'viewedEmployee' => $viewedEmployee,
            'directory' => $directory,
            'salaryStructure' => $salaryStructure,
            'payslips' => $payslips,
            'runs' => $runs,
            'latestRun' => $runs->first(),
            'runsByDepartment' => $runsByDepartment,
        ], $superAdminData));
    }

    /** Process a payroll cycle (Super Admin only). */
    public function runPayroll(Request $request)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->currentRole() === 'super_admin', 403);

        $data = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'notes' => 'nullable|string|max:255',
        ]);

        // never pay a month that has not started
        if (Carbon::create($data['year'], $data['month'], 1)->gt(now()->endOfMonth())) {
            return back()->with('status', 'You cannot run payroll for a future month.');
        }

        if (PayrollRun::where('month', $data['month'])->where('year', $data['year'])->exists()) {
            return back()->with('status', 'Payroll for that month has already been run. Discard it first if it needs to be redone.');
        }

        try {
            $run = app(PayrollCalculator::class)->process((int) $data['month'], (int) $data['year'], $this->currentUser()->id, $data['notes'] ?? null);
        } catch (\RuntimeException $e) {
            return back()->with('status', $e->getMessage());
        }

        $this->audit('PAYROLL_RUN', $run->id, null, [
            'period' => $run->monthLabel(), 'employees' => $run->employee_count, 'net' => $run->total_net,
        ]);

        return redirect()->route('hr.payroll.index', ['tab' => 'run'])
            ->with('status', 'Payroll for '.$run->monthLabel().' processed for '.$run->employee_count.' employees. Review it, then mark it paid after the bank transfer.');
    }

    public function discard(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->currentRole() === 'super_admin', 403);

        $label = $run->monthLabel();

        try {
            app(PayrollCalculator::class)->discard($run);
        } catch (\RuntimeException $e) {
            return back()->with('status', $e->getMessage());
        }

        $this->audit('PAYROLL_DISCARD', $run->id, ['period' => $label], null);

        return redirect()->route('hr.payroll.index', ['tab' => 'run'])->with('status', 'Payroll for '.$label.' was discarded. You can run it again.');
    }

    public function markPaid(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->currentRole() === 'super_admin', 403);

        try {
            app(PayrollCalculator::class)->markPaid($run);
        } catch (\RuntimeException $e) {
            return back()->with('status', $e->getMessage());
        }

        $this->audit('PAYROLL_PAID', $run->id, ['status' => 'processed'], ['status' => 'paid']);

        return back()->with('status', 'Payroll for '.$run->monthLabel().' marked as paid.');
    }

    /** Excel payroll register for a run (HR Admin and Super Admin). */
    public function register(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->isHrOrAbove(), 403);

        $slips = $run->payslips()->with(['employee.user', 'employee.department', 'employee.designation'])->get()
            ->sortBy(fn ($p) => $p->employee->employee_code);

        $headings = ['Emp Code', 'Name', 'Department', 'Designation', 'Days in Month', 'Paid Days', 'LOP Days',
            'Basic', 'HRA', 'Allowances', 'Variable', 'Incentive', 'Gross', 'PF', 'ESI', 'Professional Tax', 'TDS',
            'Other Deductions', 'Total Deductions', 'Net Pay', 'Employer PF', 'Employer ESI', 'PF Admin + EDLI',
            'Cost to Company', 'Bank', 'Account No', 'IFSC'];

        $rows = $slips->map(function ($p) {
            $e = $p->employee;
            $ded = $p->pf_deduction + $p->esi_deduction + $p->professional_tax + $p->tds_deduction + $p->other_deductions;

            return [
                $e->employee_code, $e->user->name ?? '', $e->department->name ?? '', $e->designation->title ?? '',
                (int) $p->days_in_month, (float) $p->paid_days, (float) $p->lop_days,
                (float) $p->basic_earned, (float) $p->hra_earned, (float) $p->allowances_earned, (float) $p->variable_earned,
                (float) $p->incentive_pay, (float) $p->gross_pay, (float) $p->pf_deduction, (float) $p->esi_deduction,
                (float) $p->professional_tax, (float) $p->tds_deduction, (float) $p->other_deductions, round($ded, 2),
                (float) $p->net_pay, (float) $p->employer_pf, (float) $p->employer_esi, (float) $p->employer_admin_charges,
                round($p->gross_pay + $p->employer_pf + $p->employer_esi + $p->employer_admin_charges, 2),
                $e->bank_name, (string) $e->bank_account_number, $e->bank_ifsc,
            ];
        })->values()->all();

        $money = ['H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X'];

        return Excel::download(
            new TableExport($headings, $rows, ['Z', 'AA'], $money),
            sprintf('payroll-register-%04d-%02d.xlsx', $run->year, $run->month)
        );
    }

    /** Bank transfer sheet (CSV) with net pay per employee. Super Admin only. */
    public function bankFile(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->currentRole() === 'super_admin', 403);

        $slips = $run->payslips()->with('employee.user')->get()->sortBy(fn ($p) => $p->employee->employee_code);
        $label = $run->monthLabel();

        return response()->streamDownload(function () use ($slips, $label) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee Code', 'Name', 'Bank', 'Account Number', 'IFSC', 'Net Pay', 'Narration']);
            foreach ($slips as $p) {
                $e = $p->employee;
                fputcsv($out, [
                    $e->employee_code, $e->user->name ?? '', $e->bank_name, $e->bank_account_number, $e->bank_ifsc,
                    number_format((float) $p->net_pay, 2, '.', ''), 'Salary '.$label,
                ]);
            }
            fclose($out);
        }, sprintf('salary-transfer-%04d-%02d.csv', $run->year, $run->month), ['Content-Type' => 'text/csv']);
    }

    public function updateSalary(Request $request, Employee $employee)
    {
        $this->abortUnlessModuleAllowed('payroll');
        // Compensation changes are Super Admin only.
        abort_unless($this->currentRole() === 'super_admin', 403);

        $data = $request->validate([
            'basic' => 'required|numeric|min:0',
            'hra' => 'required|numeric|min:0',
            'other_allowances' => 'required|numeric|min:0',
            'variable_pay' => 'required|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'tds_monthly_override' => 'nullable|numeric|min:0',
            'effective_from' => 'nullable|date',
        ]);

        $effectiveFrom = Carbon::parse($data['effective_from'] ?? now()->toDateString())->toDateString();
        unset($data['effective_from']);

        $data['other_deduction'] = $data['other_deduction'] ?? 0;
        $data['tds_monthly_override'] = ($data['tds_monthly_override'] ?? '') === '' ? null : $data['tds_monthly_override'];
        $data['pf_applicable'] = $request->boolean('pf_applicable');
        $data['esi_applicable'] = $request->boolean('esi_applicable');
        $data['pt_applicable'] = $request->boolean('pt_applicable');

        $gross = $data['basic'] + $data['hra'] + $data['other_allowances'] + $data['variable_pay'];

        $current = $employee->salaryStructures()->orderByDesc('effective_from')->orderByDesc('id')->first();

        if ($current && Carbon::parse($effectiveFrom)->lt(Carbon::parse($current->effective_from))) {
            return back()->with('status', 'The effective date cannot be earlier than the current structure ('.Carbon::parse($current->effective_from)->format('d M Y').').');
        }

        if ($current && Carbon::parse($current->effective_from)->toDateString() === $effectiveFrom) {
            $current->update(array_merge($data, ['gross_monthly' => $gross]));
        } else {
            if ($current) {
                $current->update(['effective_to' => Carbon::parse($effectiveFrom)->subDay()->toDateString()]);
            }
            SalaryStructure::create(array_merge($data, [
                'employee_id' => $employee->id,
                'effective_from' => $effectiveFrom,
                'gross_monthly' => $gross,
                'created_at' => now(),
            ]));
        }

        $this->audit('SALARY_UPDATE', $employee->id, $current ? $current->only(['basic', 'hra', 'other_allowances', 'variable_pay', 'gross_monthly']) : null, $data + ['gross_monthly' => $gross, 'effective_from' => $effectiveFrom]);

        return back()->with('status', 'Salary structure saved for '.$employee->employee_code.'.');
    }

    public function payslip(Payslip $payslip)
    {
        $this->abortUnlessModuleAllowed('payroll');
        $employee = $this->currentEmployee();

        abort_unless(
            $this->isHrOrAbove() || ($employee && $payslip->employee_id === $employee->id),
            403
        );

        $payslip->load(['employee.user', 'employee.department', 'employee.designation', 'payrollRun']);

        return view('hr.modules.payslip-print', [
            'payslip' => $payslip,
            'companyName' => SystemSetting::where('setting_key', 'company_name')->value('setting_value') ?: 'SPC Enterprises',
        ]);
    }

    private function audit(string $action, ?int $recordId, $old, $new): void
    {
        AuditLog::create([
            'user_id' => $this->currentUser()->id,
            'action' => $action,
            'module' => 'payroll',
            'record_id' => $recordId,
            'old_value' => $old === null ? null : json_encode($old),
            'new_value' => $new === null ? null : json_encode($new),
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
