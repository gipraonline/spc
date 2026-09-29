<?php

namespace App\Http\Controllers\Hr;


use App\Models\Hr\Department;
use App\Models\Hr\Notification;
use App\Models\Hr\WfhRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WfhController extends Controller
{
    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('wfh');
        $employee = $this->currentEmployee();
        $role = $this->currentRole();

        $ownRequests = ($employee && $role !== 'super_admin')
            ? $employee->wfhRequests()->orderByDesc('id')->limit(10)->get()
            : collect();

        $pendingApprovals = collect();
        if ($this->isManagerOrAbove()) {
            $query = WfhRequest::with('employee.user')->where('status', 'pending');
            if ($role === 'manager' && $employee) {
                $reportIds = $employee->directReports()->pluck('id');
                $query->whereIn('employee_id', $reportIds);
            } elseif ($employee) {
                $query->where('employee_id', '!=', $employee->id);
            }
            $pendingApprovals = $query->orderBy('start_date')->orderByDesc('id')->get();
        }

        // Org-wide WFH register (All / Pending / Approved / Rejected) — HR Admin
        // and Super Admin only. Same dataset either way; filters below narrow it.
        $allWfhRequests = collect();
        $wfhRequestsPage = null;
        $wfhDepartments = collect();
        $wfhCounts = ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'total' => 0];
        if (in_array($role, ['hr_admin', 'super_admin'], true)) {
            $query = WfhRequest::with(['employee.user', 'employee.department'])
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

            $allWfhRequests = (clone $query)->get();

            if ($request->string('export')->toString() === 'csv') {
                return response()->streamDownload(function () use ($allWfhRequests) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['Employee', 'Department', 'Start', 'End', 'Location', 'Contact', 'Status', 'Reason', 'Approved At']);
                    foreach ($allWfhRequests as $r) {
                        fputcsv($out, [
                            $r->employee->user->name ?? '',
                            $r->employee->department->name ?? '',
                            $r->start_date,
                            $r->end_date,
                            $r->location ?? '',
                            $r->contact_number ?? '',
                            ucfirst($r->status),
                            $r->reason ?? '',
                            $r->approved_at ?? '',
                        ]);
                    }
                    fclose($out);
                }, 'wfh-requests.csv', ['Content-Type' => 'text/csv']);
            }

            $wfhCounts = [
                'pending' => $allWfhRequests->where('status', 'pending')->count(),
                'approved' => $allWfhRequests->where('status', 'approved')->count(),
                'rejected' => $allWfhRequests->where('status', 'rejected')->count(),
                'total' => $allWfhRequests->count(),
            ];

            $wfhRequestsPage = $query->paginate(15)->withQueryString();

            $wfhDepartments = Department::orderBy('name')->get();
        }

        return view('hr.modules.wfh', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'wfh',
            'ownRequests' => $ownRequests,
            'pendingApprovals' => $pendingApprovals,
            'allWfhRequests' => $allWfhRequests,
            'wfhRequestsPage' => $wfhRequestsPage,
            'wfhDepartments' => $wfhDepartments,
            'wfhCounts' => $wfhCounts,
            'wfhDeptFilter' => $request->integer('dept') ?: '',
            'wfhStatusFilter' => $request->string('status')->toString() ?: 'all',
            'wfhEmployeeFilter' => $request->string('employee')->toString(),
            'wfhDateFrom' => $request->string('date_from')->toString(),
            'wfhDateTo' => $request->string('date_to')->toString(),
        ]));
    }

    public function store(Request $request)
    {
        $this->abortUnlessModuleAllowed('wfh');
        $employee = $this->currentEmployee();
        abort_unless($employee && $this->currentRole() !== 'super_admin', 403);

        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:150',
            'contact_number' => 'nullable|string|max:20',
        ]);

        WfhRequest::create(array_merge($data, [
            'employee_id' => $employee->id,
            'status' => 'pending',
            'created_at' => now(),
        ]));

        if ($employee->reportingManager && $employee->reportingManager->user) {
            Notification::notify(
                $employee->reportingManager->user->id,
                'wfh_request',
                $employee->user->name.' requested WFH from '.Carbon::parse($data['start_date'])->format('d M').'.',
                '/modules/wfh'
            );
        }

        return back()->with('status', 'WFH request submitted for approval.');
    }

    public function decide(Request $request, WfhRequest $wfhRequest)
    {
        $this->abortUnlessModuleAllowed('wfh');
        abort_unless($this->isManagerOrAbove(), 403);

        $data = $request->validate(['action' => 'required|in:approve,reject']);

        $wfhRequest->update([
            'status' => $data['action'] === 'approve' ? 'approved' : 'rejected',
            'approved_by' => $this->currentUser()->id,
            'approved_at' => now(),
        ]);

        $wfhRequest->loadMissing('employee.user');
        if ($wfhRequest->employee && $wfhRequest->employee->user) {
            Notification::notify(
                $wfhRequest->employee->user->id,
                'wfh_decision',
                'Your WFH request was '.($data['action'] === 'approve' ? 'approved' : 'rejected').'.',
                '/modules/wfh'
            );
        }

        return back()->with('status', 'WFH request '.($data['action'] === 'approve' ? 'approved.' : 'rejected.'));
    }
}
