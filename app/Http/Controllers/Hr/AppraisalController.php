<?php

namespace App\Http\Controllers\Hr;


use App\Models\Hr\Appraisal;
use App\Models\Hr\AppraisalCycle;
<<<<<<< HEAD
use App\Models\Hr\Employee;
use App\Models\Hr\SalesTarget;
=======
>>>>>>> ecbf179f112763652c4c004034826b6c8822c12d
use App\Services\Hr\PerformanceInsightsService;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{
    public function index(Request $request, PerformanceInsightsService $insights)
    {
        $module = $this->abortUnlessModuleAllowed('appraisal');
        $employee = $this->currentEmployee();
        $role = $this->currentRole();

        $cycles = AppraisalCycle::orderByDesc('start_date')->get();

        $ownAppraisals = $employee
            ? Appraisal::with(['cycle', 'goals'])->where('employee_id', $employee->id)->orderByDesc('id')->get()
            : collect();

        $currentAppraisal = $ownAppraisals->firstWhere('status', '!=', 'completed') ?? $ownAppraisals->first();

        $toReview = collect();
        if ($this->isManagerOrAbove() && $employee) {
            $query = Appraisal::with(['employee.user', 'cycle', 'goals'])
                ->where('status', '!=', 'not_started')
                ->where('status', '!=', 'completed');

            if ($role === 'manager') {
                $query->where('manager_id', $employee->id);
            }

            $toReview = $query->orderByDesc('id')->get();
        }

        // Selected cycle drives the date range for sales / attendance / incentives.
        // Default: the active cycle, else the most recent one, else the last 90 days.
        $selectedCycle = $cycles->firstWhere('id', (int) $request->query('cycle'))
            ?? $cycles->firstWhere('status', 'active')
            ?? $cycles->first();
        [$from, $to] = $insights->period($selectedCycle);

        // Scope is decided here, by role, and nowhere else: an employee only
        // ever receives their own numbers; team/org data is never loaded for them.
<<<<<<< HEAD
        $mine = $employee ? $insights->forEmployee($employee, $from, $to, $selectedCycle?->id) : null;
=======
        $mine = $employee ? $insights->forEmployee($employee, $from, $to) : null;
>>>>>>> ecbf179f112763652c4c004034826b6c8822c12d
        $team = ($role === 'manager' && $employee)
            ? $insights->forTeam($employee, $from, $to, $selectedCycle?->id)
            : null;
        $org = $this->isHrOrAbove()
            ? $insights->forOrganisation($from, $to, $selectedCycle?->id, $role === 'super_admin')
            : null;

<<<<<<< HEAD
        // Who this person may set targets for: managers -> direct reports, HR -> everyone active.
        $targetEmployees = collect();
        $targetValues = [];
        if ($this->isManagerOrAbove() && $employee && $selectedCycle) {
            $targetEmployees = $this->targetableEmployees($employee)->load('user');
            $targetValues = SalesTarget::where('appraisal_cycle_id', $selectedCycle->id)
                ->whereIn('employee_id', $targetEmployees->pluck('id'))->get()
                ->groupBy('employee_id')->map(fn ($g) => $g->pluck('target_value', 'metric')->map(fn ($v) => (float) $v)->all())->all();
        }

=======
>>>>>>> ecbf179f112763652c4c004034826b6c8822c12d
        return view('hr.modules.appraisal', array_merge($this->baseViewData(), [
            'targetEmployees' => $targetEmployees,
            'targetValues' => $targetValues,
            'module' => $module,
            'moduleKey' => 'appraisal',
            'selectedCycle' => $selectedCycle,
            'periodFrom' => $from,
            'periodTo' => $to,
            'mine' => $mine,
            'team' => $team,
            'org' => $org,
            'cycles' => $cycles,
            'ownAppraisals' => $ownAppraisals,
            'currentAppraisal' => $currentAppraisal,
            'toReview' => $toReview,
        ]));
    }

    public function submitSelfAssessment(Request $request, Appraisal $appraisal)
    {
        $this->abortUnlessModuleAllowed('appraisal');
        $employee = $this->currentEmployee();
        abort_unless($employee && $appraisal->employee_id === $employee->id, 403);
        abort_unless(in_array($appraisal->status, ['not_started', 'self_review'], true), 403, 'This appraisal is already with your manager or completed.');

        $data = $request->validate([
            'self_assessment' => 'required|string|max:2000',
            'goal_ratings' => 'nullable|array',
            'goal_ratings.*' => 'nullable|numeric|min:0|max:5',
        ]);

        foreach ($data['goal_ratings'] ?? [] as $goalId => $rating) {
            if ($rating !== null && $rating !== '') {
                $appraisal->goals()->where('id', $goalId)->update(['self_rating' => $rating]);
            }
        }

        $appraisal->update([
            'self_assessment' => $data['self_assessment'],
            'status' => 'self_review',
        ]);

        return back()->with('status', 'Self-assessment submitted for manager review.');
    }

    public function submitManagerReview(Request $request, Appraisal $appraisal)
    {
        $this->abortUnlessModuleAllowed('appraisal');
        abort_unless($this->isManagerOrAbove(), 403);
        if ($this->currentRole() === 'manager') {
            abort_unless($appraisal->manager_id === $this->currentEmployee()?->id, 403, 'You can only review your own direct reports.');
        }

        $data = $request->validate([
            'manager_review' => 'required|string|max:2000',
            'final_rating' => 'required|numeric|min:0|max:5',
            'goal_ratings' => 'nullable|array',
            'goal_ratings.*' => 'nullable|numeric|min:0|max:5',
        ]);

        foreach ($data['goal_ratings'] ?? [] as $goalId => $rating) {
            if ($rating !== null && $rating !== '') {
                $appraisal->goals()->where('id', $goalId)->update(['manager_rating' => $rating]);
            }
        }

        $appraisal->update([
            'manager_review' => $data['manager_review'],
            'final_rating' => $data['final_rating'],
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('status', 'Appraisal review completed for '.$appraisal->employee->employee_code.'.');
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

        return redirect()->route('hr.appraisal.index', ['cycle' => $data['appraisal_cycle_id']])
            ->with('status', "Targets saved ({$saved} values).");
    }
}
