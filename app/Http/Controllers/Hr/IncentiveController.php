<?php

namespace App\Http\Controllers\Hr;

use App\Services\Hr\AssociateScope;
use App\Models\Hr\IncentivePayout;
use App\Models\Hr\IncentiveRule;
use App\Services\Hr\IncentiveEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IncentiveController extends Controller
{
    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('incentive');
        $employee = $this->currentEmployee();
        $role = $this->currentRole();

        $rules = IncentiveRule::orderByDesc('is_active')->orderBy('designation_code')->orderBy('slab_from')->get();

        $pendingPayouts = collect();
        if ($this->isManagerOrAbove()) {
            $query = IncentivePayout::with(['employee.user', 'rule'])->where('status', 'pending')
                ->whereNotIn('employee_id', AssociateScope::hrEmployeeIds());
            if ($role === 'manager' && $employee) {
                $reportIds = $employee->directReports()->pluck('id');
                $query->whereIn('employee_id', $reportIds);
            }
            $pendingPayouts = $query->orderByDesc('year')->orderByDesc('month')->get();
        }

        $ownPayouts = $employee
            ? $employee->incentivePayouts()->with('rule')->orderByDesc('year')->orderByDesc('month')->get()
            : collect();

        $designations = DB::table('designation_masters')
            ->whereIn('identifier', ['FCO', 'TL', 'AGM_OM', 'RSH', 'NSH', 'FR_MGR'])
            ->orderBy('hierarchy_level', 'desc')->get(['identifier', 'c_designation']);

        return view('hr.modules.incentive', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'incentive',
            'rules' => $rules,
            'pendingPayouts' => $pendingPayouts,
            'ownPayouts' => $ownPayouts,
            'designations' => $designations,
            'runMonth' => $request->query('month', now()->format('Y-m')),
            'eligibility' => [
                'order_status' => config('spc.incentive.order_status'),
                'payment_status' => implode(', ', (array) config('spc.incentive.payment_status')),
            ],
        ]));
    }

    public function storeRule(Request $request)
    {
        $this->abortUnlessModuleAllowed('incentive');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'applies_to_role' => 'required|in:employee,manager,hr_admin,super_admin',
            'designation_code' => 'nullable|string|max:30',
            'basis' => 'required|in:own,team',
            'target_metric' => 'nullable|string|max:100',
            'slab_from' => 'nullable|numeric|min:0',
            'slab_to' => 'nullable|numeric|min:0|gt:slab_from',
            'incentive_percent' => 'nullable|numeric|min:0|max:100',
            'flat_amount' => 'nullable|numeric|min:0',
            'min_achievement_pct' => 'nullable|numeric|min:0|max:500',
        ]);

        abort_if(
            blank($data['incentive_percent'] ?? null) && blank($data['flat_amount'] ?? null),
            422, 'Enter an incentive % or a flat amount.'
        );

        IncentiveRule::create(array_merge($data, [
            'slab_from' => $data['slab_from'] ?? 0,
            'target_metric' => $data['target_metric'] ?: 'net_sales',
            'effective_from' => now()->toDateString(),
            'is_active' => true,
        ]));

        return back()->with('status', 'Incentive rule "'.$data['name'].'" saved. Run a calculation to apply it.');
    }

    public function toggleRule(IncentiveRule $rule)
    {
        $this->abortUnlessModuleAllowed('incentive');
        abort_unless($this->isHrOrAbove(), 403);

        $rule->update([
            'is_active' => ! $rule->is_active,
            'effective_to' => $rule->is_active ? now()->toDateString() : null,
        ]);

        return back()->with('status', 'Rule "'.$rule->name.'" '.($rule->is_active ? 'activated' : 'deactivated').'.');
    }

    public function destroyRule(IncentiveRule $rule)
    {
        $this->abortUnlessModuleAllowed('incentive');
        abort_unless($this->isHrOrAbove(), 403);

        $name = $rule->name;
        $used = $rule->payouts()->count();

        // Payouts keep their amounts and calculation breakdown; only the link to the rule is cleared.
        $rule->delete();

        return back()->with('status', 'Rule "'.$name.'" deleted.'.($used
            ? " {$used} existing payout(s) keep their amounts. Pending ones are recalculated on the next run."
            : ''));
    }

    public function calculate(Request $request, IncentiveEngine $engine)
    {
        $this->abortUnlessModuleAllowed('incentive');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate(['month' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/']]);
        $m = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth();
        abort_if($m->gt(now()), 422, 'Cannot calculate incentives for a future month.');

        $r = $engine->run($m->year, $m->month);

        return redirect()->route('hr.incentive.index', ['month' => $data['month']])->with('status', sprintf(
            '%s: %d payouts created, %d updated, %d already approved/paid (unchanged). Pending total ₹%s.',
            $m->format('M Y'), $r['created'], $r['updated'], $r['locked'], number_format($r['total'], 0)
        ));
    }

    public function approvePayout(IncentivePayout $payout)
    {
        $this->abortUnlessModuleAllowed('incentive');
        abort_unless($this->isManagerOrAbove(), 403);
        abort_unless($payout->status === 'pending', 422, 'This payout is no longer pending.');

        $employee = $this->currentEmployee();

        // Nobody approves their own incentive; managers approve only their direct reports.
        abort_if($employee && $payout->employee_id === $employee->id, 403, 'You cannot approve your own incentive.');
        if ($this->currentRole() === 'manager') {
            abort_unless(
                $employee && $employee->directReports()->where('id', $payout->employee_id)->exists(),
                403, 'You can only approve payouts for your direct reports.'
            );
        }

        $payout->update([
            'status' => 'approved',
            'approved_by' => $this->currentUser()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Payout approved — will be included in the next payroll run.');
    }
}