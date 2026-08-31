<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldLog;

class AdminFieldLogController extends Controller
{
    /**
     * Display all field logs with filters.
     */
    public function index(Request $request)
    {
        $fieldLogs = FieldLog::with(['admin', 'tasks'])
            ->latest('work_date')
            ->latest('check_in_time')
            ->paginate(15);

        return view('admin.admin-log.index', compact('fieldLogs'));
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
