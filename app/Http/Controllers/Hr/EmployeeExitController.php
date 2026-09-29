<?php

namespace App\Http\Controllers\Hr;

use App\Models\EmployeeMaster;
use App\Models\Hr\Employee;
use App\Models\Hr\EmployeeExit;
use App\Models\Hr\EmployeeHistory;
use App\Services\Hr\EmployeeExitService;
use App\Services\Hr\EmployeeHrSyncService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeExitController extends Controller
{
    /** HR / Super Admin: record a resignation, termination, etc. */
    public function store(Request $request, Employee $employee)
    {
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'exit_type' => ['required', Rule::in(array_keys(EmployeeExit::TYPES))],
            'initiated_by' => 'nullable|in:employee,company',
            'reason_category' => 'nullable|string|max:60',
            'reason' => 'required|string|max:2000',
            'notice_date' => 'nullable|date',
            'last_working_day' => 'required|date',
            'notice_period_days' => 'nullable|integer|min:0|max:365',
            'notice_served_days' => 'nullable|integer|min:0|max:365',
            'notice_waived' => 'nullable|boolean',
            'eligible_for_rehire' => 'required|in:yes,no,conditional',
            'rehire_remarks' => 'nullable|string|max:1000',
            'exit_interview_done' => 'nullable|boolean',
            'exit_interview_notes' => 'nullable|string|max:3000',
            'clearance' => 'nullable|array',
            'clearance.*' => 'nullable|boolean',
            'final_settlement_notes' => 'nullable|string|max:2000',
            'manager_remarks' => 'nullable|string|max:3000',
            'overall_rating' => 'nullable|integer|between:1,5',
            'reassign_reports_to' => 'nullable|exists:spc_hr.employees,id|different:'.$employee->id,
        ]);

        abort_if(
            $this->currentEmployee() && $this->currentEmployee()->id === $employee->id,
            403,
            'You cannot record your own exit.'
        );

        $exit = EmployeeExitService::record($employee, $data, $this->currentUser()->id);

        return back()->with('status', $exit->status === 'on_notice'
            ? $employee->employee_code.' is on notice until '.$exit->last_working_day->format('d M Y').'.'
            : $employee->employee_code.' has been marked as exited. Their full history is preserved.');
    }

    /** Fill in / correct details of an existing exit (interview, clearance, rating...). */
    public function update(Request $request, EmployeeExit $exit)
    {
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'exit_type' => ['required', Rule::in(array_keys(EmployeeExit::TYPES))],
            'reason_category' => 'nullable|string|max:60',
            'reason' => 'required|string|max:2000',
            'notice_date' => 'nullable|date',
            'last_working_day' => 'required|date',
            'eligible_for_rehire' => 'required|in:yes,no,conditional',
            'rehire_remarks' => 'nullable|string|max:1000',
            'exit_interview_done' => 'nullable|boolean',
            'exit_interview_notes' => 'nullable|string|max:3000',
            'clearance' => 'nullable|array',
            'clearance.*' => 'nullable|boolean',
            'final_settlement_notes' => 'nullable|string|max:2000',
            'manager_remarks' => 'nullable|string|max:3000',
            'overall_rating' => 'nullable|integer|between:1,5',
        ]);

        $clearance = [];
        foreach (array_keys(EmployeeExit::CLEARANCE_ITEMS) as $key) {
            $clearance[$key] = ! empty($data['clearance'][$key]);
        }

        $exit->update(array_merge($data, [
            'clearance' => $clearance,
            'exit_interview_done' => ! empty($data['exit_interview_done']),
            'initiated_by' => in_array($data['exit_type'], ['termination', 'absconding']) ? 'company' : 'employee',
        ]));

        if ($exit->employee && $exit->status !== 'reinstated') {
            $exit->employee->update(['date_of_exit' => $exit->last_working_day->toDateString()]);
        }

        EmployeeHistory::log(
            $exit->employee_id, 'change', 'Exit details', null, 'Updated',
            $this->currentUser()->id, 'Exit record edited'
        );

        return back()->with('status', 'Exit details saved.');
    }

    public function reinstate(Request $request, Employee $employee)
    {
        abort_unless($this->isHrOrAbove(), 403);
        abort_unless($employee->employment_status !== 'active', 422);

        $data = $request->validate(['remarks' => 'nullable|string|max:1000']);

        EmployeeExitService::reinstate($employee, $this->currentUser()->id, $data['remarks'] ?? null);

        return back()->with('status', $employee->employee_code.' has been reinstated. The previous exit stays in their history.');
    }

    /**
     * SPC Employee Records -> "History" button. Resolves the HR-side record
     * for an SPC employee (creating it through the normal sync if it is
     * somehow missing) and shows the full history page. Works for former
     * (soft-deleted) employees too — that is the whole point.
     */
    public function history(EmployeeMaster $employeeMaster)
    {
        abort_unless($this->isHrOrAbove(), 403, 'Employee history is available to HR and Super Admin only.');

        $employee = Employee::where('employee_master_id', $employeeMaster->n_employee_id)->first();

        if (! $employee) {
            try {
                $employee = EmployeeHrSyncService::sync($employeeMaster);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (! $employee) {
            return redirect()->route('admin.employees.index')->with(
                'error',
                'No HR record exists for '.$employeeMaster->c_employee_name.' yet — add a work email to the employee so it can be linked, then try again.'
            );
        }

        $employee->load(['user', 'department', 'designation', 'reportingManager.user', 'directReports.user']);

        $possibleManagers = Employee::with('user')->where('employment_status', 'active')
            ->whereHas('user', fn ($u) => $u->whereIn('role', ['manager', 'hr_admin', 'super_admin']))
            ->orderBy('employee_code')->get();

        $viewingSelf = $this->currentEmployee() && $this->currentEmployee()->id === $employee->id;

        return view('admin.employees.history', EmployeeExitService::fileData($employee) + [
            'master' => $employeeMaster,
            'viewed' => $employee,
            'possibleManagers' => $possibleManagers,
            'viewingSelf' => $viewingSelf,
        ]);
    }

    /**
     * Printable full employee file: profile, performance, attendance,
     * pay, sales, career timeline and every exit. Works for current
     * and former employees.
     */
    public function file(Employee $employee)
    {
        abort_unless($this->isHrOrAbove(), 403);

        $employee->load(['user', 'department', 'designation', 'reportingManager.user', 'documents']);

        $master = $employee->employee_master_id
            ? EmployeeMaster::withTrashed()->find($employee->employee_master_id)
            : null;

        return view('hr.modules.employee-file', EmployeeExitService::fileData($employee) + [
            'viewed' => $employee,
            'backUrl' => $master && request()->routeIs('admin.*')
                ? route('admin.employees.history', $master)
                : route('hr.records.index', ['employee' => $employee->id]),
        ]);
    }

    /** CSV register of everyone who has left (or is leaving), with performance. */
    public function register(Request $request)
    {
        abort_unless($this->isHrOrAbove(), 403);

        $query = EmployeeExit::with(['employee.user', 'employee.department', 'employee.designation'])
            ->where('status', '!=', 'reinstated')
            ->orderByDesc('last_working_day');

        if ($request->filled('type')) {
            $query->where('exit_type', $request->string('type'));
        }
        if ($request->filled('from')) {
            $query->whereDate('last_working_day', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('last_working_day', '<=', $request->date('to'));
        }

        $rows = $query->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'Employee', 'ID', 'Department', 'Designation', 'Joined', 'Last working day', 'Tenure',
                'Exit type', 'Reason category', 'Reason', 'Notice (days served / required)', 'Rehire eligible',
                'Avg appraisal rating', 'Latest rating', 'Closing rating (1-5)', 'Exit interview', 'Clearance complete', 'Status',
            ]);
            foreach ($rows as $x) {
                $s = $x->snapshot ?? [];
                fputcsv($out, [
                    $x->employee->user->name ?? '',
                    $x->employee->employee_code ?? '',
                    $x->employee->department->name ?? '',
                    $s['profile']['designation'] ?? ($x->employee->designation->title ?? ''),
                    $s['profile']['date_of_joining'] ?? '',
                    $x->last_working_day->toDateString(),
                    $s['profile']['tenure_text'] ?? '',
                    $x->typeLabel(),
                    $x->reason_category,
                    $x->reason,
                    ($x->notice_served_days ?? '—').' / '.($x->notice_period_days ?? '—'),
                    $x->eligible_for_rehire,
                    $s['performance']['average_rating'] ?? '',
                    $s['performance']['latest_rating'] ?? '',
                    $x->overall_rating,
                    $x->exit_interview_done ? 'Yes' : 'No',
                    ($x->clearance && count(array_filter($x->clearance)) === count(EmployeeExit::CLEARANCE_ITEMS)) ? 'Yes' : 'No',
                    ucfirst(str_replace('_', ' ', $x->status)),
                ]);
            }
            fclose($out);
        }, 'exit-register-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }
}
