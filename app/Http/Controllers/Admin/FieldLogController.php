<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldLog;
use App\Models\FieldLogTask;
use App\Support\Geo;
use Illuminate\Http\Request;

class FieldLogController extends Controller
{
    /**
     * Read the browser-supplied GPS fix from the request.
     * Returns [lat, lng, accuracy_m] (all null when none was shared).
     * When config('spc.field_log_require_gps') is on, a missing fix is a validation error.
     */
    private function gpsFromRequest(Request $request, string $errorKey): array
    {
        $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0|max:100000',
        ]);

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        if (! Geo::valid($lat, $lng)) {
            if (config('spc.field_log_require_gps')) {
                abort(back()->withErrors([
                    $errorKey => 'Location is required. Please allow location access in your browser and try again.',
                ])->withInput());
            }

            return [null, null, null];
        }

        return [
            round((float) $lat, 7),
            round((float) $lng, 7),
            $request->filled('accuracy') ? (int) round((float) $request->input('accuracy')) : null,
        ];
    }

    /**
     * Field Log page
     */
    public function index()
    {
        $fieldLog = FieldLog::with('tasks')
            ->where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        $tasks = $fieldLog?->tasks ?? collect();

        // Task counts
        $total = $tasks->count();

        $done = $tasks->where('status', 'Completed')->count();

        $pendingTasks = $tasks->where('status', 'Pending')->count();

        $inProgressTasks = $tasks->where('status', 'In Progress')->count();

        // Progress percentage
        $percent = $total > 0
            ? round(($done / $total) * 100)
            : 0;

        // Checkout status
        $isCheckedOut = $fieldLog
            && $fieldLog->status === 'Checked Out';

        return view('admin.field-log.index', compact(
            'fieldLog',
            'tasks',
            'total',
            'done',
            'pendingTasks',
            'inProgressTasks',
            'percent',
            'isCheckedOut'
        ));
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
                'checkin' => 'You have already checked in for today.',
            ]);
        }

        [$lat, $lng, $accuracy] = $this->gpsFromRequest($request, 'checkin');

        // Create today's field log
        $fieldLog = FieldLog::create([
            'user_id' => auth()->id(),
            'work_date' => today(),
            'check_in_time' => now(),
            'check_in_remark' => $request->check_in_remark,
            'check_in_latitude' => $lat,
            'check_in_longitude' => $lng,
            'check_in_accuracy_m' => $accuracy,
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
            $lat === null
                ? 'Checked In Successfully (location was not captured).'
                : 'Checked In Successfully'
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
                'task' => 'You cannot update tasks after checking out.',
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
                'checkout' => 'You have already checked out for today.',
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
                'checkout' => 'Please move all Pending tasks to In Progress or Done before checking out.',
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Checkout
        |--------------------------------------------------------------------------
        */

        [$lat, $lng, $accuracy] = $this->gpsFromRequest($request, 'checkout');

        $fieldLog->update([
            'check_out_time' => now(),
            'check_out_remark' => $request->check_out_remark,
            'check_out_latitude' => $lat,
            'check_out_longitude' => $lng,
            'check_out_accuracy_m' => $accuracy,
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
