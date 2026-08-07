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

    }

}