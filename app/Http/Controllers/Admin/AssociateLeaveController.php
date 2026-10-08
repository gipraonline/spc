<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssociateLeaveRequest;
use App\Models\EmployeeMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Leave for associates (un-promoted Farm Care Advisers / Tele Callers).
 * Associates apply here; their reporting manager (or HR / admin) approves.
 * Entirely separate from the HR leave module — no balances, no payroll.
 */
class AssociateLeaveController extends Controller
{
    private const HR_ROLE_IDENTIFIERS = ['HRM', 'HR_TEAM'];

    private function me(): ?EmployeeMaster
    {
        return auth()->user()?->employee;
    }

    private function seesAll(): bool
    {
        $user = auth()->user();

        return $user->hasAnyRole(['Super Admin', 'Gipra Admin'])
            || $user->roles->contains(fn ($r) => in_array($r->identifier, self::HR_ROLE_IDENTIFIERS, true));
    }

    private function isAssociate(): bool
    {
        return (bool) auth()->user()?->isAssociate();
    }

    /** May the signed-in user approve this request? */
    private function canDecide(AssociateLeaveRequest $leave): bool
    {
        if ($this->seesAll()) {
            return true;
        }

        $me = $this->me();

        return $me && $leave->employee && (int) $leave->employee->reporting_to === (int) $me->n_employee_id;
    }

    public function index()
    {
        $me = $this->me();
        $isAssociate = $this->isAssociate();

        $mine = $isAssociate && $me
            ? AssociateLeaveRequest::where('n_employee_id', $me->n_employee_id)->orderByDesc('id')->limit(30)->get()
            : collect();

        $approvals = collect();

        if (! $isAssociate) {
            $query = AssociateLeaveRequest::with('employee.designation')->orderByRaw("status = 'pending' desc")->orderByDesc('id');

            if (! $this->seesAll()) {
                $ids = $me ? EmployeeMaster::where('reporting_to', $me->n_employee_id)->pluck('n_employee_id') : collect();
                $query->whereIn('n_employee_id', $ids);
            }

            $approvals = $query->limit(100)->get();
        }

        return view('admin.associate-leave.index', [
            'isAssociate' => $isAssociate,
            'mine' => $mine,
            'approvals' => $approvals,
            'types' => AssociateLeaveRequest::TYPES,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($this->isAssociate() && $this->me(), 403, 'Only associates can apply here.');

        $data = $request->validate([
            'leave_type' => 'required|in:'.implode(',', AssociateLeaveRequest::TYPES),
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $empId = $this->me()->n_employee_id;

        $overlap = AssociateLeaveRequest::where('n_employee_id', $empId)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '<=', $end)
            ->whereDate('end_date', '>=', $start)
            ->exists();

        if ($overlap) {
            return back()->withInput()->with('error', 'You already have a pending or approved leave in these dates.');
        }

        AssociateLeaveRequest::create([
            'n_employee_id' => $empId,
            'leave_type' => $data['leave_type'],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days' => $start->diffInDays($end) + 1,
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.associate-leave.index')->with('success', 'Leave request sent for approval.');
    }

    public function cancel(AssociateLeaveRequest $leave)
    {
        abort_unless($this->me() && (int) $leave->n_employee_id === (int) $this->me()->n_employee_id, 403);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $leave->update(['status' => 'cancelled']);

        return back()->with('success', 'Leave request cancelled.');
    }

    public function decide(Request $request, AssociateLeaveRequest $leave)
    {
        abort_unless($this->canDecide($leave), 403, 'You cannot decide this request.');

        $data = $request->validate([
            'decision' => 'required|in:approved,rejected',
            'remark' => 'nullable|string|max:500',
        ]);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'This request has already been decided.');
        }

        $me = $this->me();

        $leave->update([
            'status' => $data['decision'],
            'decided_by' => $me?->n_employee_id,
            'decided_by_name' => $me?->c_employee_name ?? auth()->user()->c_name,
            'decided_at' => now(),
            'decision_remark' => $data['remark'] ?? null,
        ]);

        return back()->with('success', 'Leave '.$data['decision'].'.');
    }
}
