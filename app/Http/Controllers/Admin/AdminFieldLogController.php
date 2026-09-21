<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldLog;
use Illuminate\Http\Request;

class AdminFieldLogController extends Controller
{
    public function search(Request $request)
    {
        session([
            'field_log_from_date' => $request->input('from_date'),
            'field_log_to_date' => $request->input('to_date'),
            'field_log_status' => $request->input('status', ''),
        ]);

        return redirect()->route('admin.admin-log.index');
    }

    public function clearSearch()
    {
        session()->forget([
            'field_log_from_date',
            'field_log_to_date',
            'field_log_status',
        ]);

        return redirect()->route('admin.admin-log.index');
    }

    public function index(Request $request)
    {
        $fromDate = session('field_log_from_date');
        $toDate = session('field_log_to_date');
        $status = session('field_log_status', '');

        $query = FieldLog::with(['admin', 'tasks']);

        // Date filter
        if (! empty($fromDate)) {
            $query->whereDate('work_date', '>=', $fromDate);
        }

        if (! empty($toDate)) {
            $query->whereDate('work_date', '<=', $toDate);
        }

        // Status filter ONLY when a status is selected
        if (! empty($status)) {
            $query->where('status', $status);
        }

        $fieldLogs = $query
            ->latest('work_date')
            ->latest('check_in_time')
            ->paginate(15);

        return view('admin.admin-log.index', compact(
            'fieldLogs',
            'fromDate',
            'toDate',
            'status'
        ));
    }

    /**
     * Display a single field log.
     */
    public function show(FieldLog $fieldLog)
    {
        $fieldLog->load(['admin', 'tasks']);

        $tasks = $fieldLog->tasks;

        /*
        |--------------------------------------------------------------------------
        | Task Summary
        |--------------------------------------------------------------------------
        |
        | Status is the single source of truth:
        |
        | Pending     = Pending
        | In Progress = Currently working
        | Checked Out = Completed
        |
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

        $percent = $total > 0
            ? round(($done / $total) * 100)
            : 0;

        return view('admin.admin-log.show', compact(
            'fieldLog',
            'tasks',
            'total',
            'done',
            'pending',
            'inProgress',
            'percent'
        ));
    }
}
