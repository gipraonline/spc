<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\AppraisalCycle;
use App\Models\Hr\Employee;
use App\Models\Hr\SalesTarget;
use App\Services\Hr\PerformanceInsightsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Performance page: sales / attendance overview, monthly employee report and targets.
 *
 * Targets are still stored against the review period they belong to, because the
 * incentive calculation divides them across that period. The page never shows
 * "cycles" or appraisals: it just uses the period that covers today.
 */
class AppraisalController extends Controller
{
    public function index(Request $request, PerformanceInsightsService $insights)
    {
        $module = $this->abortUnlessModuleAllowed('appraisal');
        $employee = $this->currentEmployee();
        $role = $this->currentRole();

        $month = $this->reportMonth($request);
        [$from, $to] = $insights->periodForMonth($month);

        // Target period (internal): the period covering today, else the active one, else the latest.
        $today = now()->toDateString();
        $periods = AppraisalCycle::orderByDesc('start_date')->get();
        $targetPeriod = $periods->first(fn ($c) => $c->start_date <= $today && $c->end_date >= $today)
            ?? $periods->firstWhere('status', 'active')
            ?? $periods->first();
        [$targetFrom, $targetTo] = $insights->period($targetPeriod);

        // Own target progress (the only personal figures an employee gets).
        $mine = $employee ? $insights->forEmployee($employee, $targetFrom, $targetTo, $targetPeriod?->id) : null;

        // My own numbers for the picked month (every login with an employee record).
        $mineMonth = $employee ? $insights->forEmployee($employee, $from, $to, null) : null;
        $mineReport = $employee ? ($insights->employeeMonthlyReport($month, null, $employee)['rows'][0] ?? null) : null;

        // Scope is decided here, by role, and nowhere else.
        $team = ($role === 'manager' && $employee) ? $insights->forTeam($employee, $from, $to) : null;
        $org = $this->isHrOrAbove() ? $insights->forOrganisation($from, $to, $role === 'super_admin') : null;

        $report = $this->isManagerOrAbove() && $employee
            ? $insights->employeeMonthlyReport($month, $role === 'manager' ? $employee : null)
            : null;

        $targetEmployees = collect();
        $targetValues = [];
        if ($this->isManagerOrAbove() && $employee && $targetPeriod) {
            $targetEmployees = $this->targetableEmployees($employee)->load('user');
            $targetValues = SalesTarget::where('appraisal_cycle_id', $targetPeriod->id)
                ->whereIn('employee_id', $targetEmployees->pluck('id'))->get()
                ->groupBy('employee_id')->map(fn ($g) => $g->pluck('target_value', 'metric')->map(fn ($v) => (float) $v)->all())->all();
        }

        return view('hr.modules.appraisal', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'appraisal',
            'month' => $month,
            'periodFrom' => $from,
            'periodTo' => $to,
            'targetPeriod' => $targetPeriod,
            'targetFrom' => $targetFrom,
            'targetTo' => $targetTo,
            'targetEmployees' => $targetEmployees,
            'targetValues' => $targetValues,
            'mine' => $mine,
            'mineMonth' => $mineMonth,
            'mineReport' => $mineReport,
            'team' => $team,
            'org' => $org,
            'report' => $report,
        ]));
    }

    private function reportMonth(Request $request): Carbon
    {
        $m = (string) $request->query('month', '');

        $month = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $m)
            ? Carbon::createFromFormat('Y-m-d', $m.'-01')->startOfMonth()
            : now()->startOfMonth();

        return $month->gt(now()->startOfMonth()) ? now()->startOfMonth() : $month;
    }

    public function exportReport(Request $request, PerformanceInsightsService $insights)
    {
        $this->abortUnlessModuleAllowed('appraisal');
        abort_unless($this->isManagerOrAbove(), 403);
        $employee = $this->currentEmployee();
        abort_unless($employee, 403);

        $month = $this->reportMonth($request);
        $data = $insights->employeeMonthlyReport($month, $this->currentRole() === 'manager' ? $employee : null);

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee', 'Code', 'Department', 'Monthly sales value', 'Orders', 'Leave days', 'WFH days', 'Late', 'Salary paid']);
            foreach ($data['rows'] as $r) {
                fputcsv($out, [$r['name'], $r['code'], $r['department'], $r['sales_linked'] ? $r['sales'] : '', $r['orders'], $r['leave_days'], $r['wfh_days'], $r['late'], $r['salary_paid'] ?? '']);
            }
            fclose($out);
        }, 'employee-performance-'.$month->format('Y-m').'.csv', ['Content-Type' => 'text/csv']);
    }

    /** Employees the current manager / HR user is allowed to set targets for. */
    private function targetableEmployees(Employee $me)
    {
        $q = Employee::where('employment_status', '!=', 'exited')->where('id', '!=', $me->id);

        if ($this->currentRole() === 'manager') {
            $q->where('reporting_manager_id', $me->id);
        }

        return $q->orderBy('employee_code')->get();
    }

    public function saveTargets(Request $request)
    {
        $this->abortUnlessModuleAllowed('appraisal');
        abort_unless($this->isManagerOrAbove(), 403);
        $me = $this->currentEmployee();
        abort_unless($me, 403);

        $data = $request->validate([
            'appraisal_cycle_id' => 'required|exists:spc_hr.appraisal_cycles,id',
            'targets' => 'required|array',
            'targets.*' => 'array',
            'targets.*.*' => 'nullable|numeric|min:0|max:100000000',
        ]);

        $allowed = $this->targetableEmployees($me)->pluck('id')->all();
        $saved = 0;

        foreach ($data['targets'] as $employeeId => $metrics) {
            if (! in_array((int) $employeeId, $allowed, true)) {
                continue; // never write targets for someone outside your scope
            }

            foreach ($metrics as $metric => $value) {
                if (! isset(SalesTarget::METRICS[$metric])) {
                    continue;
                }

                $key = ['employee_id' => (int) $employeeId, 'appraisal_cycle_id' => (int) $data['appraisal_cycle_id'], 'metric' => $metric];

                if ($value === null || $value === '' || (float) $value <= 0) {
                    SalesTarget::where($key)->delete();
                } else {
                    SalesTarget::updateOrCreate($key, ['target_value' => $value, 'set_by' => $this->currentUser()->id]);
                    $saved++;
                }
            }
        }

        return redirect()->route('hr.appraisal.index', ['tab' => 'targets'])
            ->with('status', "Targets saved ({$saved} values).");
    }
}
