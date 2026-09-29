<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\FieldLog;
use Illuminate\Http\Request;

class AdminFieldLogController extends Controller
{
    /**
     * Search Field Logs
     */
    public function search(Request $request)
    {
        session([
            'field_log_from_date' => $request->input('from_date'),
            'field_log_to_date' => $request->input('to_date'),
            'field_log_status' => $request->input('status', ''),
        ]);

        return redirect()->route('admin.admin-log.index');
    }

    /**
     * Clear Search
     */
    public function clearSearch()
    {
        session()->forget([
            'field_log_from_date',
            'field_log_to_date',
            'field_log_status',
        ]);

        return redirect()->route('admin.admin-log.index');
    }

    /**
     * Field Log List
     *
     * Farm Care Officer:
     *   Only sees activities of Farm Care Advisors
     *   reporting to that Farm Care Officer.
     *
     * Other roles:
     *   Existing behaviour - see all field logs.
     */
    public function index(Request $request)
    {
        $fromDate = session('field_log_from_date');
        $toDate = session('field_log_to_date');
        $status = session('field_log_status', '');

        $user = auth()->user();

        $query = FieldLog::with([
            'admin',
            'tasks',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FCO FILTER
        |--------------------------------------------------------------------------
        |
        | Farm Care Officer can only see FieldLogs belonging to
        | Farm Care Advisors whose EmployeeMaster.reporting_to
        | matches the current FCO's employee ID.
        |
        */

        if ($user->hasRole('Farm Care Officer')) {

            $fcaIds = $this->getFcaIdsUnderCurrentFco();

            $query->whereIn('user_id', $fcaIds);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if (! empty($fromDate)) {
            $query->whereDate(
                'work_date',
                '>=',
                $fromDate
            );
        }

        if (! empty($toDate)) {
            $query->whereDate(
                'work_date',
                '<=',
                $toDate
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (! empty($status)) {
            $query->where(
                'status',
                $status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Logs
        |--------------------------------------------------------------------------
        */

        $fieldLogs = $query
            ->latest('work_date')
            ->latest('check_in_time')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.admin-log.index',
            compact(
                'fieldLogs',
                'fromDate',
                'toDate',
                'status'
            )
        );
    }

    /**
     * Display a single field log.
     *
     * Farm Care Officer can only open logs belonging to
     * Farm Care Advisors reporting to that Farm Care Officer.
     */
    public function show(FieldLog $fieldLog)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | FCO Access Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Farm Care Officer')) {

            $fcaIds = $this->getFcaIdsUnderCurrentFco();

            abort_unless(
                $fcaIds->contains($fieldLog->user_id),
                403
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Load Relations
        |--------------------------------------------------------------------------
        */

        $fieldLog->load([
            'admin',
            'tasks',
        ]);

        $tasks = $fieldLog->tasks;

        /*
        |--------------------------------------------------------------------------
        | Task Summary
        |--------------------------------------------------------------------------
        */

        $total = $tasks->count();

        $done = $tasks
            ->where('status', 'Done')
            ->count();

        $pending = $tasks
            ->where('status', 'Pending')
            ->count();

        $inProgress = $tasks
            ->where('status', 'In Progress')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Progress
        |--------------------------------------------------------------------------
        */

        $percent = $total > 0
            ? round(($done / $total) * 100)
            : 0;

        return view(
            'admin.admin-log.show',
            compact(
                'fieldLog',
                'tasks',
                'total',
                'done',
                'pending',
                'inProgress',
                'percent'
            )
        );
    }

    /**
     * Get Farm Care Advisor Admin IDs under the
     * currently logged-in Farm Care Officer.
     *
     * Relationship:
     *
     * Farm Care Officer Admin
     *          |
     *          | n_employee_id
     *          v
     * Farm Care Officer EmployeeMaster
     *          |
     *          | reporting_to
     *          v
     * Farm Care Advisor EmployeeMaster
     *          |
     *          | n_employee_id
     *          v
     * Farm Care Advisor Admin
     *          |
     *          | n_role_id
     *          v
     * FieldLog.user_id
     */
    private function getFcaIdsUnderCurrentFco()
    {
        $fco = auth()->user();

        return Admin::whereHas(
            'employee',
            function ($query) use ($fco) {
                $query->where(
                    'reporting_to',
                    $fco->n_employee_id
                );
            }
        )
            ->role('Farm Care Advisor')
            ->pluck('n_role_id');
    }
}
