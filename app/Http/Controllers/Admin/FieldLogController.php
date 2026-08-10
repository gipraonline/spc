<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldLog;
use App\Models\FieldLogTask;

class FieldLogController extends Controller
{
    /**
     * Field Log page
     */
    public function index()
    {
        $fieldLog = FieldLog::with('tasks')
            ->where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        return view('admin.field-log.index', compact('fieldLog'));
    }


    /**
     * Check In
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'check_in_remark' => 'nullable|string',
            'tasks' => 'required|array|min:1',
            'tasks.*' => 'required|string|max:255',
        ]);


        // Prevent duplicate check-in for today
        $existingLog = FieldLog::where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        if ($existingLog) {
            return back()->withErrors([
                'checkin' => 'You have already checked in for today.'
            ]);
        }


        // Create today's field log
        $fieldLog = FieldLog::create([
            'user_id' => auth()->id(),
            'work_date' => today(),
            'check_in_time' => now(),
            'check_in_remark' => $request->check_in_remark,
            'status' => 'Checked In',
        ]);


        // Insert tasks
        foreach ($request->tasks as $task) {

            FieldLogTask::create([
                'field_log_id' => $fieldLog->id,
                'task' => $task,
                'status' => 'Pending',
            ]);

        }


        return back()->with(
            'success',
            'Checked In Successfully'
        );
    }


    /**
     * Store Task
     */
    public function storeTask(Request $request)
    {
        // Currently not required because tasks
        // are created during check-in.
    }


    /**
     * Update Task
     */
    public function updateTask(Request $request, FieldLogTask $task)
    {
        $request->validate([
            'task_id' => 'required|exists:field_log_tasks,id',
            'status' => 'required|in:Pending,In Progress,Done',
            'pending_remark' => 'nullable|string',
        ]);


        $task = FieldLogTask::findOrFail($request->task_id);


        // Make sure task belongs to current user's field log
        $fieldLog = FieldLog::where('id', $task->field_log_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();


        // Do not allow task update after checkout
        if ($fieldLog->status === 'Checked Out') {

            return back()->withErrors([
                'task' => 'You cannot update tasks after checking out.'
            ]);

        }


        // Update status
        $task->status = $request->status;


        if ($request->status === 'Done') {

            $task->completed_at = now();

            $task->pending_remark = null;

        } else {

            $task->completed_at = null;

            $task->pending_remark = $request->pending_remark;

        }


        $task->save();


        return back()->with(
            'success',
            'Task updated successfully.'
        );
    }


    /**
     * Check Out
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'check_out_remark' => 'nullable|string',
        ]);


        $fieldLog = FieldLog::with('tasks')
            ->where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Already Checked Out
        |--------------------------------------------------------------------------
        */

        if (
            $fieldLog->status === 'Checked Out' ||
            $fieldLog->check_out_time
        ) {

            return back()->withErrors([
                'checkout' => 'You have already checked out for today.'
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Pending Tasks
        |--------------------------------------------------------------------------
        |
        | Pending tasks BLOCK checkout.
        |
        | In Progress  -> Allowed
        | Done         -> Allowed
        | Pending      -> Not Allowed
        |
        */

        $pendingTasks = $fieldLog->tasks()
            ->where('status', 'Pending')
            ->count();


        if ($pendingTasks > 0) {

            return back()->withErrors([
                'checkout' =>
                    'Please move all Pending tasks to In Progress or Done before checking out.'
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Checkout
        |--------------------------------------------------------------------------
        */

        $fieldLog->update([
            'check_out_time' => now(),
            'check_out_remark' => $request->check_out_remark,
            'status' => 'Checked Out',
        ]);


        return back()->with(
            'success',
            'Checked Out Successfully.'
        );
    }


    /**
     * Field Log History
     */
    public function history()
    {
        $fieldLogs = FieldLog::withCount('tasks')
            ->where('user_id', auth()->id())
            ->latest('work_date')
            ->paginate(10);


        return view(
            'admin.field-log.history',
            compact('fieldLogs')
        );
    }


    /**
     * Show Field Log
     */
    public function show(FieldLog $fieldLog)
    {
       
        abort_if(
            $fieldLog->user_id != auth()->id(),
            403
        );


        $fieldLog->load('tasks');


        return view(
            'admin.field-log.show',
            compact('fieldLog')
        );
    }
}