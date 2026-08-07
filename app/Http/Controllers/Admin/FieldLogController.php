<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldLog;
use App\Models\FieldLogTask;


class FieldLogController extends Controller
{
    
    public function index()
    {
      $fieldLog = FieldLog::with('tasks')
                ->where('user_id', auth()->id())
                ->whereDate('work_date', today())
                ->first();

        return view('admin.field-log.index', compact('fieldLog'));
    }

    public function checkIn(Request $request)
{
    $request->validate([
        'check_in_remark' => 'nullable|string',
        'tasks' => 'required|array',
        'tasks.*' => 'required|string|max:255',
    ]);


    // Create today's field log
    $fieldLog = FieldLog::create([

        'user_id' => auth()->id(),

        'work_date' => today(),

        'check_in_time' => now(),

        'check_in_remark' => $request->check_in_remark,

        'status' => 'Checked In',

    ]);


    // Insert Tasks
    foreach ($request->tasks as $task) {

        FieldLogTask::create([

            'field_log_id' => $fieldLog->id,

            'task' => $task,

            'status' => 'Pending',

        ]);

    }


    return back()->with('success','Checked In Successfully');

}

    public function storeTask(Request $request)
    {

    }

    public function updateTask(Request $request, FieldLogTask $task)
    {
        
    $task = FieldLogTask::findOrFail($request->task_id);

    $task->status = $request->status;

    if ($request->status == 'Done') {

        $task->completed_at = now();
        $task->pending_remark = null;

    } else {

        $task->completed_at = null;
        $task->pending_remark = $request->pending_remark;

    }

    $task->save();

    return back()->with('success', 'Task updated successfully.');

    }

    public function checkOut(Request $request)
{
    $request->validate([
        'check_out_remark' => 'nullable|string',
    ]);

    $fieldLog = FieldLog::with('tasks')
        ->where('user_id', auth()->id())
        ->whereDate('work_date', today())
        ->firstOrFail();

    // Prevent multiple checkouts
    if ($fieldLog->check_out_time) {
        return back()->with('error', 'Already checked out.');
    }

    // Ensure all tasks are completed
    $pendingTasks = $fieldLog->tasks()
        ->where('status', 'Pending')
        ->count();

    if ($pendingTasks > 0) {
        return back()->with('error', 'Complete all tasks before checking out.');
    }

    $fieldLog->update([
        'check_out_time'   => now(),
        'check_out_remark' => $request->check_out_remark,
        'status'           => 'Checked Out',
    ]);

    return back()->with('success', 'Checked Out Successfully.');
}

public function history()
{
    $fieldLogs = FieldLog::withCount('tasks')
        ->where('user_id', auth()->id())
        ->latest('work_date')
        ->paginate(10);

    return view('admin.field-log.history', compact('fieldLogs'));
}
public function show(FieldLog $fieldLog)
{
    abort_if($fieldLog->user_id != auth()->id(), 403);

    $fieldLog->load('tasks');

    return view('admin.field-log.show', compact('fieldLog'));
}

}