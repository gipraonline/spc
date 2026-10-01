<?php

namespace App\Services\Hr;

use App\Models\Admin;
use App\Models\EmployeeMaster;
use App\Models\Hr\Employee;
use App\Models\Hr\EmployeeExit;
use App\Models\Hr\EmployeeHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Everything that happens when someone leaves (or comes back):
 *
 *  - an employee_exits row (type, reason, notice, rehire eligibility,
 *    clearance checklist, exit interview) — one per separation
 *  - a frozen performance/details snapshot so the file stays complete
 *    even after accounts are disabled and data moves on
 *  - timeline rows in employee_history
 *  - HR user + SPC login disabled, direct reports re-pointed
 *
 * Exits with a future last working day put the person "on_notice";
 * `hr:finalize-exits` (daily) completes them on the day.
 */
class EmployeeExitService
{
    public const PENDING_DETAILS = 'Pending details';

    /* ------------------------------------------------------------------ */
    /*  Record / finalize / reinstate                                      */
    /* ------------------------------------------------------------------ */

    public static function record(Employee $employee, array $data, ?int $byUserId = null): EmployeeExit
    {
        $open = EmployeeExit::where('employee_id', $employee->id)
            ->whereIn('status', ['on_notice', 'completed'])
            ->exists();

        // An employee marked "exited" by the old toggle has no exit row —
        // that is allowed here so HR can backfill the details.
        if ($open) {
            throw ValidationException::withMessages([
                'exit_type' => 'An exit is already recorded for this employee. Reinstate them first to record a new one.',
            ]);
        }

        $lwd = Carbon::parse($data['last_working_day'])->startOfDay();
        $isFuture = $lwd->gt(today());

        $clearance = [];
        foreach (array_keys(EmployeeExit::CLEARANCE_ITEMS) as $key) {
            $clearance[$key] = ! empty($data['clearance'][$key]);
        }

        $exit = DB::connection('spc_hr')->transaction(function () use ($employee, $data, $byUserId, $lwd, $isFuture, $clearance) {
            $exit = EmployeeExit::create([
                'employee_id' => $employee->id,
                'exit_type' => $data['exit_type'],
                'initiated_by' => $data['initiated_by'] ?? (in_array($data['exit_type'], ['termination', 'absconding']) ? 'company' : 'employee'),
                'reason_category' => $data['reason_category'] ?? null,
                'reason' => $data['reason'] ?? null,
                'notice_date' => $data['notice_date'] ?? null,
                'last_working_day' => $lwd->toDateString(),
                'notice_period_days' => $data['notice_period_days'] ?? null,
                'notice_served_days' => $data['notice_served_days'] ?? null,
                'notice_waived' => ! empty($data['notice_waived']),
                'eligible_for_rehire' => $data['eligible_for_rehire'] ?? 'yes',
                'rehire_remarks' => $data['rehire_remarks'] ?? null,
                'exit_interview_done' => ! empty($data['exit_interview_done']),
                'exit_interview_notes' => $data['exit_interview_notes'] ?? null,
                'clearance' => $clearance,
                'final_settlement_notes' => $data['final_settlement_notes'] ?? null,
                'manager_remarks' => $data['manager_remarks'] ?? null,
                'overall_rating' => $data['overall_rating'] ?? null,
                'status' => 'on_notice',
                'recorded_by' => $byUserId,
                'snapshot' => static::buildSnapshot($employee, $lwd),
            ]);

            $label = EmployeeExit::TYPES[$exit->exit_type];

            if ($isFuture) {
                $employee->update([
                    'employment_status' => 'on_notice',
                    'date_of_exit' => $lwd->toDateString(),
                ]);

                EmployeeHistory::log(
                    $employee->id, 'notice', 'Employment status',
                    'Active', 'On notice ('.$label.')', $byUserId,
                    trim(($data['reason_category'] ?? '').' '.($data['reason'] ?? '')) ?: null,
                    $exit->notice_date?->toDateString()
                );
            }

            return $exit;
        });

        // If HR picked a new manager for the team, move them straight away
        // (even during notice). Otherwise finalize() moves them up to the
        // leaver's own manager on the last working day.
        if (! empty($data['reassign_reports_to'])) {
            static::reassignReports($employee, (int) $data['reassign_reports_to'], $byUserId);
        }

        if (! $isFuture) {
            static::finalize($exit, $byUserId);
        }

        return $exit->fresh();
    }

    /** Called on/after the last working day. Idempotent. */
    public static function finalize(EmployeeExit $exit, ?int $byUserId = null): EmployeeExit
    {
        if ($exit->status === 'completed' && $exit->employee?->employment_status === 'exited') {
            return $exit;
        }

        $employee = $exit->employee()->with('user')->first();
        $lwd = $exit->last_working_day;

        DB::connection('spc_hr')->transaction(function () use ($exit, $employee, $lwd, $byUserId) {
            $previousStatus = $employee->employment_status;

            $employee->update([
                'employment_status' => 'exited',
                'date_of_exit' => $lwd->toDateString(),
            ]);
            $employee->user?->update(['is_active' => false]);

            // Re-take the snapshot as of the last working day so it includes
            // the notice period's attendance, appraisals and sales.
            $exit->update([
                'status' => 'completed',
                'snapshot' => static::buildSnapshot($employee, $lwd),
            ]);

            EmployeeHistory::log(
                $employee->id, 'exit', 'Employment status',
                ucfirst(str_replace('_', ' ', $previousStatus)),
                'Exited — '.EmployeeExit::TYPES[$exit->exit_type],
                $byUserId,
                $exit->reason,
                $lwd->toDateString()
            );
        });

        static::pushStatusToSpc($employee, 'N', 'Inactive');

        // Anyone still reporting to them moves up to their own manager.
        static::reassignReports($employee, $employee->reporting_manager_id, $byUserId);

        return $exit->fresh();
    }

    public static function reinstate(Employee $employee, ?int $byUserId = null, ?string $remarks = null, ?string $previousStatus = null): void
    {
        DB::connection('spc_hr')->transaction(function () use ($employee, $byUserId, $remarks, $previousStatus) {
            $previous = $previousStatus ?? $employee->employment_status;

            $employee->update([
                'employment_status' => 'active',
                'date_of_exit' => null,
            ]);
            $employee->user?->update(['is_active' => true]);

            EmployeeExit::where('employee_id', $employee->id)
                ->whereIn('status', ['on_notice', 'completed'])
                ->update(['status' => 'reinstated', 'reinstated_at' => now()]);

            EmployeeHistory::log(
                $employee->id, 'reinstatement', 'Employment status',
                ucfirst(str_replace('_', ' ', $previous)), 'Active',
                $byUserId, $remarks
            );
        });

        static::pushStatusToSpc($employee, 'Y', 'Active');
    }

    /**
     * Someone was deactivated/deleted from the SPC side (c_status N or D)
     * without going through the HR exit form. Make sure a record exists so
     * the history is never silently lost; HR completes the details later.
     */
    public static function ensureExitRecord(Employee $employee, ?int $byUserId = null): ?EmployeeExit
    {
        $exists = EmployeeExit::where('employee_id', $employee->id)
            ->whereIn('status', ['on_notice', 'completed'])->exists();

        if ($exists) {
            return null;
        }

        $lwd = Carbon::parse($employee->date_of_exit ?? today());

        $exit = EmployeeExit::create([
            'employee_id' => $employee->id,
            'exit_type' => 'other',
            'initiated_by' => 'company',
            'reason_category' => self::PENDING_DETAILS,
            'reason' => 'Deactivated from the SPC Employees screen. HR to add the exit type, reason and clearance details.',
            'last_working_day' => $lwd->toDateString(),
            'status' => 'completed',
            'recorded_by' => $byUserId,
            'snapshot' => static::buildSnapshot($employee, $lwd),
        ]);

        $employee->update(['date_of_exit' => $lwd->toDateString()]);
        $employee->user?->update(['is_active' => false]);

        EmployeeHistory::log(
            $employee->id, 'exit', 'Employment status', 'Active',
            'Exited — details pending', $byUserId,
            'Deactivated from the SPC Employees screen', $lwd->toDateString()
        );

        static::pushStatusToSpc($employee, 'N', 'Inactive');

        return $exit;
    }

    /**
     * Everything the history page / printable file shows for one employee:
     * every exit, the latest one, a snapshot (frozen for former employees,
     * live for current ones) and the career timeline (newest first).
     */
    public static function fileData(Employee $employee): array
    {
        $exits = EmployeeExit::where('employee_id', $employee->id)->orderByDesc('id')->get();
        $latestExit = $exits->first(fn ($x) => $x->status !== 'reinstated');

        $snapshot = $latestExit && $latestExit->snapshot
            ? $latestExit->snapshot
            : static::buildSnapshot($employee);

        $history = EmployeeHistory::with('changedBy')
            ->where('employee_id', $employee->id)
            ->orderByDesc('effective_date')->orderByDesc('id')->get();

        return [
            'exits' => $exits,
            'exit' => $latestExit,
            'snapshot' => $snapshot,
            'history' => $history,
            'isFormer' => $employee->employment_status === 'exited',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Snapshot: who they were and how they performed                     */
    /* ------------------------------------------------------------------ */

    public static function buildSnapshot(Employee $employee, ?Carbon $asOf = null): array
    {
        $hr = DB::connection('spc_hr');
        $asOf = ($asOf ?? now())->copy();
        $from = $asOf->copy()->subMonths(12)->startOfDay();

        $employee->loadMissing(['user', 'department', 'designation', 'reportingManager.user']);

        $joined = $employee->date_of_joining ? Carbon::parse($employee->date_of_joining) : null;

        // --- profile ---
        $profile = [
            'name' => $employee->user->name ?? null,
            'employee_code' => $employee->employee_code,
            'work_email' => $employee->user->email ?? null,
            'personal_email' => $employee->personal_email,
            'phone' => $employee->phone,
            'city' => $employee->city,
            'department' => $employee->department->name ?? null,
            'designation' => $employee->designation->title ?? null,
            'reporting_manager' => $employee->reportingManager?->user?->name,
            'portal_role' => $employee->user?->roleLabel(),
            'date_of_joining' => $joined?->toDateString(),
            'last_working_day' => $asOf->toDateString(),
            'tenure_days' => $joined ? (int) $joined->diffInDays($asOf) : null,
            'tenure_text' => $joined ? $joined->diff($asOf)->format('%y yr %m mo %d d') : null,
        ];

        // --- appraisals ---
        $appraisals = $hr->table('appraisals as a')
            ->join('appraisal_cycles as c', 'c.id', '=', 'a.appraisal_cycle_id')
            ->where('a.employee_id', $employee->id)
            ->orderBy('c.start_date')
            ->get(['a.id', 'c.name as cycle', 'c.end_date', 'a.final_rating', 'a.status', 'a.manager_review', 'a.self_assessment'])
            ->map(function ($a) use ($hr) {
                $goals = $hr->table('appraisal_goals')->where('appraisal_id', $a->id)
                    ->get(['goal_text', 'weight_percent', 'self_rating', 'manager_rating']);

                return [
                    'cycle' => $a->cycle,
                    'cycle_end' => $a->end_date,
                    'status' => $a->status,
                    'final_rating' => $a->final_rating !== null ? (float) $a->final_rating : null,
                    'manager_review' => $a->manager_review,
                    'self_assessment' => $a->self_assessment,
                    'goals' => $goals->map(fn ($g) => (array) $g)->all(),
                ];
            })->values();

        $rated = $appraisals->pluck('final_rating')->filter(fn ($r) => $r !== null);
        $performance = [
            'appraisals' => $appraisals->all(),
            'appraisal_count' => $appraisals->count(),
            'average_rating' => $rated->isNotEmpty() ? round($rated->avg(), 2) : null,
            'latest_rating' => $rated->isNotEmpty() ? $rated->last() : null,
            'best_rating' => $rated->max(),
            'lowest_rating' => $rated->min(),
        ];

        // --- attendance (last 12 months) ---
        $att = $hr->table('attendance')
            ->where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$from->toDateString(), $asOf->toDateString()])
            ->selectRaw('status, COUNT(*) as days, COALESCE(SUM(late_minutes),0) as late_min, COALESCE(SUM(early_exit_minutes),0) as early_min')
            ->groupBy('status')->get()->keyBy('status');

        $attendance = [
            'period' => $from->toDateString().' to '.$asOf->toDateString(),
            'present' => (int) ($att['present']->days ?? 0),
            'late' => (int) ($att['late']->days ?? 0),
            'half_day' => (int) ($att['half_day']->days ?? 0),
            'absent' => (int) ($att['absent']->days ?? 0),
            'on_leave' => (int) ($att['on_leave']->days ?? 0),
            'total_late_minutes' => (int) $att->sum('late_min'),
            'total_early_exit_minutes' => (int) $att->sum('early_min'),
        ];
        $attendance['days_recorded'] = $attendance['present'] + $attendance['late'] + $attendance['half_day'] + $attendance['absent'] + $attendance['on_leave'];

        // --- leave (approved, last 12 months) ---
        $leave = $hr->table('leave_requests as lr')
            ->join('leave_types as lt', 'lt.id', '=', 'lr.leave_type_id')
            ->where('lr.employee_id', $employee->id)
            ->where('lr.status', 'approved')
            ->where('lr.start_date', '>=', $from->toDateString())
            ->selectRaw('lt.name, SUM(lr.days) as days')
            ->groupBy('lt.name')->get()
            ->map(fn ($r) => ['type' => $r->name, 'days' => (float) $r->days])->all();

        // --- pay & incentives ---
        $salary = $hr->table('salary_structures')->where('employee_id', $employee->id)
            ->orderByDesc('effective_from')->first();

        $incentives = $hr->table('incentive_payouts')
            ->where('employee_id', $employee->id)
            ->whereIn('status', ['approved', 'included_in_payroll', 'paid'])
            ->whereRaw('STR_TO_DATE(CONCAT(year,"-",month,"-01"), "%Y-%m-%d") >= ?', [$from->copy()->startOfMonth()->toDateString()])
            ->selectRaw('COUNT(*) as months, COALESCE(SUM(achieved_value),0) as achieved, COALESCE(SUM(incentive_amount),0) as amount')
            ->first();

        $pay = [
            'gross_monthly_at_exit' => $salary ? (float) $salary->gross_monthly : null,
            'basic_at_exit' => $salary ? (float) $salary->basic : null,
            'salary_effective_from' => $salary->effective_from ?? null,
            'salary_revisions' => $hr->table('salary_structures')->where('employee_id', $employee->id)->count(),
            'incentive_months_12m' => (int) ($incentives->months ?? 0),
            'incentive_achieved_12m' => (float) ($incentives->achieved ?? 0),
            'incentive_paid_12m' => (float) ($incentives->amount ?? 0),
        ];

        // --- sales activity from the SPC database ---
        $sales = static::spcSalesSummary($employee->employee_master_id, $from, $asOf);

        // --- career timeline & documents ---
        $timeline = EmployeeHistory::where('employee_id', $employee->id)
            ->orderBy('effective_date')->orderBy('id')->get()
            ->map(fn ($h) => [
                'date' => optional($h->effective_date)->toDateString(),
                'event' => $h->event_type,
                'field' => $h->field_changed,
                'from' => $h->old_value,
                'to' => $h->new_value,
                'remarks' => $h->remarks,
            ])->all();

        $documents = $hr->table('employee_documents')->where('employee_id', $employee->id)
            ->get(['document_type', 'status', 'expiry_date'])->map(fn ($d) => (array) $d)->all();

        // --- loose ends HR must close ---
        $reports = Employee::with('user')
            ->where('reporting_manager_id', $employee->id)
            ->where('employment_status', '!=', 'exited')->get()
            ->map(fn ($e) => ($e->user->name ?? '—').' ('.$e->employee_code.')')->all();

        $openTickets = $hr->table('support_tickets')->where('employee_id', $employee->id)
            ->whereIn('status', ['open', 'in_progress'])->count();
        $pendingLeave = $hr->table('leave_requests')->where('employee_id', $employee->id)
            ->where('status', 'pending')->count();

        return [
            'captured_at' => now()->toDateTimeString(),
            'as_of' => $asOf->toDateString(),
            'profile' => $profile,
            'performance' => $performance,
            'attendance' => $attendance,
            'leave_12m' => $leave,
            'pay' => $pay,
            'sales' => $sales,
            'timeline' => $timeline,
            'documents' => $documents,
            'open_items' => [
                'direct_reports_at_exit' => $reports,
                'open_support_tickets' => $openTickets,
                'pending_leave_requests' => $pendingLeave,
            ],
        ];
    }

    /** Orders and leads owned by this person in the SPC (sales) database. */
    protected static function spcSalesSummary(?int $employeeMasterId, Carbon $from, Carbon $to): ?array
    {
        if (! $employeeMasterId) {
            return null;
        }

        try {
            $orders = DB::table('sales_orders')
                ->where('farm_care_advisor_id', $employeeMasterId)
                ->whereNull('deleted_at');

            $lifetime = (clone $orders)->selectRaw('COUNT(*) as n, COALESCE(SUM(n_net_sales_amount),0) as amt')->first();
            $last12 = (clone $orders)->whereBetween('d_date', [$from->toDateString(), $to->toDateString()])
                ->selectRaw('COUNT(*) as n, COALESCE(SUM(n_net_sales_amount),0) as amt')->first();

            $leads = DB::table('leads')->where('n_fca_id', $employeeMasterId)->whereNull('deleted_at');
            $byStatus = (clone $leads)->selectRaw('COALESCE(c_lead_status,"Unknown") as s, COUNT(*) as n')
                ->groupBy('c_lead_status')->pluck('n', 's')->all();

            return [
                'orders_lifetime' => (int) $lifetime->n,
                'net_sales_lifetime' => (float) $lifetime->amt,
                'orders_12m' => (int) $last12->n,
                'net_sales_12m' => (float) $last12->amt,
                'leads_total' => array_sum($byStatus),
                'leads_by_status' => $byStatus,
            ];
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /* ------------------------------------------------------------------ */
    /*  SPC side effects                                                   */
    /* ------------------------------------------------------------------ */

    /** Mirror the status into the SPC database and block/unblock SPC login. */
    protected static function pushStatusToSpc(Employee $employee, string $masterStatus, string $adminStatus): void
    {
        if (! $employee->employee_master_id) {
            return;
        }

        try {
            $master = EmployeeMaster::withTrashed()->where('n_employee_id', $employee->employee_master_id)->first();

            if ($master) {
                if ($master->trashed() && $masterStatus === 'Y') {
                    // Reinstated after being deleted from the SPC list.
                    $master->restore();
                    $master->update(['c_status' => 'Y']);
                } elseif (! $master->trashed()) {
                    $master->update(['c_status' => $masterStatus]);
                }
            }

            Admin::where('n_employee_id', $employee->employee_master_id)
                ->update(['c_status' => $adminStatus]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** Move an employee's direct reports to a new manager (HR + SPC). */
    protected static function reassignReports(Employee $leaver, ?int $newManagerHrId, ?int $byUserId): void
    {
        if ($newManagerHrId === $leaver->id) {
            return;
        }

        $reports = Employee::with('user')->where('reporting_manager_id', $leaver->id)->get();
        if ($reports->isEmpty()) {
            return;
        }

        $newManager = $newManagerHrId ? Employee::with('user')->find($newManagerHrId) : null;

        foreach ($reports as $report) {
            $report->update(['reporting_manager_id' => $newManager?->id]);

            EmployeeHistory::log(
                $report->id, 'change', 'Reporting manager',
                $leaver->user->name ?? '—', $newManager?->user?->name ?? '—',
                $byUserId, 'Previous manager left the company'
            );

            if ($report->employee_master_id) {
                try {
                    EmployeeMaster::where('n_employee_id', $report->employee_master_id)
                        ->update(['reporting_to' => $newManager?->employee_master_id]);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }
    }
}
