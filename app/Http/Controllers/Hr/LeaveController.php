<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\Department;
use App\Models\Hr\LeaveBalance;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\LeaveType;
use App\Services\Hr\LeaveBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('leave');
        $employee = $this->currentEmployee();
        $role = $this->currentRole();

        $balanceService = app(LeaveBalanceService::class);

        // Every leave type this employee can use, each with what's left
        // (unpaid leave is unlimited and shows days taken instead).
        $leaveSummary = ($employee && $role !== 'super_admin')
            ? $balanceService->summaryFor($employee, now()->year)
            : collect();

        $leaveTypes = $leaveSummary->pluck('type');

        $ownRequests = ($employee && $role !== 'super_admin')
            ? $employee->leaveRequests()->with('leaveType')->orderByDesc('id')->limit(10)->get()
            : collect();

        $pendingApprovals = collect();
        if ($this->isManagerOrAbove()) {
            $query = LeaveRequest::with(['employee.user', 'leaveType'])->where('status', 'pending');
            if ($role === 'manager' && $employee) {
                $reportIds = $employee->directReports()->pluck('id');
                $query->whereIn('employee_id', $reportIds);
            } elseif ($employee) {
                $query->where('employee_id', '!=', $employee->id);
            }
            $pendingApprovals = $query->orderBy('start_date')->orderByDesc('id')->get();
        }

        // Org-wide leave register (All / Pending / Approved / Rejected) — HR Admin
        // and Super Admin only. Same dataset either way; filters below narrow it.
        $allLeaveRequests = collect();
        $leaveRequestsPage = null;
        $leaveDepartments = collect();
        $leaveCounts = ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'total' => 0];
        if (in_array($role, ['hr_admin', 'super_admin'], true)) {
            $query = LeaveRequest::with(['employee.user', 'employee.department', 'leaveType'])
                ->orderByDesc('start_date')->orderByDesc('id');

            if ($request->filled('dept')) {
                $query->whereHas('employee', fn ($e) => $e->where('department_id', $request->integer('dept')));
            }

            if ($request->filled('status') && $request->string('status')->toString() !== 'all') {
                $query->where('status', $request->string('status'));
            }

            if ($request->filled('employee')) {
                $q = $request->string('employee');
                $query->whereHas('employee.user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            }

            if ($request->filled('date_from')) {
                $query->where('end_date', '>=', $request->date('date_from')->toDateString());
            }

            if ($request->filled('date_to')) {
                $query->where('start_date', '<=', $request->date('date_to')->toDateString());
            }

            $allLeaveRequests = (clone $query)->get();

            $leaveCounts = [
                'pending' => $allLeaveRequests->where('status', 'pending')->count(),
                'approved' => $allLeaveRequests->where('status', 'approved')->count(),
                'rejected' => $allLeaveRequests->where('status', 'rejected')->count(),
                'total' => $allLeaveRequests->count(),
            ];

            if ($request->string('export')->toString() === 'csv') {
                return response()->streamDownload(function () use ($allLeaveRequests) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['Employee', 'Department', 'Type', 'Start', 'End', 'Days', 'Status', 'Reason', 'Approved At']);
                    foreach ($allLeaveRequests as $r) {
                        fputcsv($out, [
                            $r->employee->user->name ?? '',
                            $r->employee->department->name ?? '',
                            $r->leaveType->name ?? '',
                            $r->start_date,
                            $r->end_date,
                            $r->days,
                            ucfirst($r->status),
                            $r->reason ?? '',
                            $r->approved_at ?? '',
                        ]);
                    }
                    fclose($out);
                }, 'leave-requests.csv', ['Content-Type' => 'text/csv']);
            }

            $leaveRequestsPage = $query->paginate(15)->withQueryString();

            $leaveDepartments = Department::orderBy('name')->get();
        }

        return view('hr.modules.leave', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'leave',
            'leaveTypes' => $leaveTypes,
            'leaveSummary' => $leaveSummary,
            'ownRequests' => $ownRequests,
            'pendingApprovals' => $pendingApprovals,
            'allLeaveRequests' => $allLeaveRequests,
            'leaveRequestsPage' => $leaveRequestsPage,
            'leaveDepartments' => $leaveDepartments,
            'leaveCounts' => $leaveCounts,
            'leaveDeptFilter' => $request->integer('dept') ?: '',
            'leaveStatusFilter' => $request->string('status')->toString() ?: 'all',
            'leaveEmployeeFilter' => $request->string('employee')->toString(),
            'leaveDateFrom' => $request->string('date_from')->toString(),
            'leaveDateTo' => $request->string('date_to')->toString(),
        ]));
    }

    public function apply(Request $request)
    {
        $this->abortUnlessModuleAllowed('leave');
        $employee = $this->currentEmployee();
        abort_unless($employee && $this->currentRole() !== 'super_admin', 403);

        $data = $request->validate([
            'leave_type_id' => 'required|exists:spc_hr.leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        $start = Carbon::parse($data['start_date'])->startOfDay();
        $end = Carbon::parse($data['end_date'])->startOfDay();
        $days = $start->diffInDays($end) + 1;

        $type = LeaveType::findOrFail($data['leave_type_id']);

        if (! app(LeaveBalanceService::class)->isEligible($employee, $type)) {
            throw ValidationException::withMessages(['leave_type_id' => $type->name.' is not applicable to you.']);
        }

        if ($problem = $this->leaveProblem($employee->id, $type, $start, $end, $days, null, false)) {
            throw ValidationException::withMessages(['start_date' => $problem]);
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days' => $days,
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return back()->with('status', 'Leave application submitted — routed to your reporting manager.');
    }

    public function decide(Request $request, LeaveRequest $leaveRequest)
    {
        $this->abortUnlessModuleAllowed('leave');
        abort_unless($this->isManagerOrAbove(), 403);

        $data = $request->validate(['action' => 'required|in:approve,reject']);
        $approve = $data['action'] === 'approve';

        $actorUser = $this->currentUser();
        $actorEmployee = $this->currentEmployee();
        $role = $this->currentRole();

        // Everything below runs in one transaction on a row lock, so two
        // simultaneous clicks (or a double submit) cannot both pass the
        // "still pending" check and double-count the balance.
        $problem = DB::connection('spc_hr')->transaction(function () use ($leaveRequest, $approve, $actorUser, $actorEmployee, $role) {
            $req = LeaveRequest::whereKey($leaveRequest->id)->lockForUpdate()->firstOrFail();
            $req->load(['employee', 'leaveType']);

            // Nobody decides their own leave — not managers, HR or super admin.
            abort_if(
                ($actorEmployee && $req->employee_id === $actorEmployee->id)
                    || ($req->employee && $req->employee->user_id === $actorUser->id),
                403,
                'You cannot approve or reject your own leave.'
            );

            // Managers only decide for their direct reports (same scope as the
            // pending-approvals list). HR Admin / Super Admin may decide for anyone else.
            if ($role === 'manager') {
                abort_unless(
                    $actorEmployee && $actorEmployee->directReports()->whereKey($req->employee_id)->exists(),
                    403,
                    'This leave request is not from one of your direct reports.'
                );
            }

            if ($req->status !== 'pending') {
                return 'This leave request has already been '.$req->status.'.';
            }

            if ($approve) {
                $start = Carbon::parse($req->start_date)->startOfDay();
                $end = Carbon::parse($req->end_date)->startOfDay();

                if ($problem = $this->leaveProblem($req->employee_id, $req->leaveType, $start, $end, (float) $req->days, $req->id, true)) {
                    return $problem;
                }

                if ($req->leaveType && $req->leaveType->is_paid) {
                    LeaveBalance::where('employee_id', $req->employee_id)
                        ->where('leave_type_id', $req->leave_type_id)
                        ->where('year', $start->year)
                        ->increment('used', $req->days);
                }
            }

            $req->update([
                'status' => $approve ? 'approved' : 'rejected',
                'approved_by' => $actorUser->id,
                'approved_at' => now(),
            ]);

            return null;
        });

        if ($problem) {
            throw ValidationException::withMessages(['leave' => $problem]);
        }

        return back()->with('status', 'Leave request '.($approve ? 'approved.' : 'rejected.'));
    }

    /**
     * Returns a human-readable reason the leave cannot be taken, or null if OK.
     *
     * - overlap: no clash with another pending/approved request of the same
     *   employee (on approval only already-approved ones count, since the
     *   request itself and other pendings are still open).
     * - balance: paid leave types need a balance row for the leave year and
     *   enough remaining days. On apply, days already promised to other pending
     *   requests of the same type are reserved; on approval only the recorded
     *   usage counts. Unpaid types have no balance to check.
     */
    private function leaveProblem(int $employeeId, ?LeaveType $type, Carbon $start, Carbon $end, float $days, ?int $ignoreId, bool $forApproval): ?string
    {
        if (! $type) {
            return 'Unknown leave type.';
        }

        $overlap = LeaveRequest::where('employee_id', $employeeId)
            ->whereIn('status', $forApproval ? ['approved'] : ['pending', 'approved'])
            ->whereDate('start_date', '<=', $end->toDateString())
            ->whereDate('end_date', '>=', $start->toDateString())
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->orderBy('start_date')
            ->first();

        if ($overlap) {
            return 'These dates overlap an existing '.$overlap->status.' leave ('
                .Carbon::parse($overlap->start_date)->format('d M Y').' – '
                .Carbon::parse($overlap->end_date)->format('d M Y').').';
        }

        if (! $type->is_paid) {
            return null;
        }

        if ($start->year !== $end->year) {
            return 'Leave spanning two calendar years must be applied for separately for each year.';
        }

        // Make sure this employee has their balance row for the year (created
        // from the entitlement set in HR Settings) before checking it.
        if ($employee = \App\Models\Hr\Employee::find($employeeId)) {
            app(LeaveBalanceService::class)->ensureFor($employee, $start->year);
        }

        $balanceQuery = LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $type->id)
            ->where('year', $start->year);

        $balance = $forApproval ? $balanceQuery->lockForUpdate()->first() : $balanceQuery->first();

        if (! $balance) {
            return 'No '.$type->name.' balance is allocated for '.$start->year.'.';
        }

        $available = $balance->remaining;

        if (! $forApproval) {
            $reserved = (float) LeaveRequest::where('employee_id', $employeeId)
                ->where('leave_type_id', $type->id)
                ->where('status', 'pending')
                ->whereYear('start_date', $start->year)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->sum('days');
            $available -= $reserved;
        }

        if ($days > $available + 0.0001) {
            return 'Insufficient '.$type->name.' balance: '.rtrim(rtrim(number_format(max(0, $available), 2), '0'), '.')
                .' day(s) available, '.rtrim(rtrim(number_format($days, 2), '0'), '.').' requested.';
        }

        return null;
    }
}
