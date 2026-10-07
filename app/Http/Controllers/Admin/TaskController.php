<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hr\Department;
use App\Models\Task;
use App\Services\HierarchyScope;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class TaskController extends Controller
{
    public function __construct(private TaskService $tasks)
    {
    }

    /** id => name of HR departments (empty if the HR database is unreachable). */
    private function departments(): array
    {
        try {
            return Department::orderBy('name')->pluck('name', 'id')->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /* ------------------------------------------------------------------ */
    /* List                                                               */
    /* ------------------------------------------------------------------ */

    public function index(Request $request)
    {
        $admin = auth()->user();
        $myEmployeeId = $admin->n_employee_id ? (int) $admin->n_employee_id : null;
        $base = $this->tasks->visibleQuery($admin);

        // ---- summary cards (always over everything the user may see)
        $counts = [
            'total' => (clone $base)->count(),
            'open' => $this->statusScope(clone $base, 'open')->count(),
            'on_hold' => $this->statusScope(clone $base, 'on_hold')->count(),
            'overdue' => $this->statusScope(clone $base, 'overdue')->count(),
            'completed' => $this->statusScope(clone $base, 'completed')->count(),
        ];

        // ---- filters
        $query = clone $base;
        $scope = $request->input('scope', 'all');

        if ($scope === 'mine') {
            $query->whereHas('assignees', fn ($a) => $a->where('employee_id', $myEmployeeId ?: 0));
        } elseif ($scope === 'created') {
            $query->where('created_by', $admin->getKey());
        } elseif ($scope === 'team') {
            $below = HierarchyScope::employeeIds($admin);
            if ($below !== null) {
                $below = array_values(array_diff($below, [$myEmployeeId]));
            }
            $query->whereHas('assignees', function ($a) use ($below) {
                if ($below !== null) {
                    $a->whereIn('employee_id', $below ?: [0]);
                }
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', (int) $request->input('department_id'));
        }
        if ($request->filled('priority') && array_key_exists($request->input('priority'), Task::PRIORITIES)) {
            $query->where('priority', $request->input('priority'));
        }
        if ($request->filled('status')) {
            $this->statusScope($query, $request->input('status'));
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.trim($request->input('q')).'%');
        }

        $tasks = $query
            ->with(['assignees:id,task_id,employee_id,status'])
            ->orderByRaw("FIELD(priority,'urgent','high','medium','low')")
            ->orderByRaw('due_date IS NULL, due_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tasks.index', [
            'tasks' => $tasks,
            'counts' => $counts,
            'departments' => $this->departments(),
            'myEmployeeId' => $myEmployeeId,
            'filters' => $request->only(['scope', 'department_id', 'priority', 'status', 'q']),
            'canCreate' => $admin->can('tasks.create'),
        ]);
    }

    /** Status filters evaluated against everyone's own status. */
    private function statusScope(Builder $query, string $status): Builder
    {
        $notDone = fn ($a) => $a->where('status', '!=', 'completed');

        return match ($status) {
            'open' => $query->where('status', 'active')->whereHas('assignees', $notDone),
            'completed' => $query->where('status', 'active')->whereHas('assignees')->whereDoesntHave('assignees', $notDone),
            'on_hold' => $query->where('status', 'active')->whereHas('assignees', fn ($a) => $a->where('status', 'on_hold')),
            'overdue' => $query->where('status', 'active')->whereDate('due_date', '<', today())->whereHas('assignees', $notDone),
            'cancelled' => $query->where('status', 'cancelled'),
            default => $query,
        };
    }

    /* ------------------------------------------------------------------ */
    /* Assign (create)                                                    */
    /* ------------------------------------------------------------------ */

    public function create()
    {
        return view('admin.tasks.form', [
            'task' => new Task(['priority' => config('tasks.default_priority', 'medium')]),
            'departments' => $this->departments(),
            'editing' => false,
        ]);
    }

    /** JSON list of active employees of a department (for the "pick people" box). */
    public function departmentEmployees(int $department)
    {
        return response()->json(
            $this->tasks->departmentEmployees($department)->map(fn ($e) => [
                'id' => $e->n_employee_id,
                'name' => $e->c_employee_name,
                'code' => $e->c_employee_code,
            ])->values()
        );
    }

    public function store(Request $request)
    {
        $departments = $this->departments();

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'priority' => ['required', Rule::in(array_keys(Task::PRIORITIES))],
            'due_date' => 'nullable|date|after_or_equal:today',
            'department_id' => ['required', 'integer', Rule::in(array_keys($departments))],
            'assign_mode' => ['required', Rule::in(['all', 'pick'])],
            'employee_ids' => 'required_if:assign_mode,pick|array',
            'employee_ids.*' => 'integer',
        ], [
            'title.required' => 'Please enter a task title.',
            'priority.required' => 'Please choose a priority.',
            'due_date.after_or_equal' => 'The due date cannot be in the past.',
            'department_id.required' => 'Please choose a department.',
            'department_id.in' => 'Please choose a valid department.',
            'employee_ids.required_if' => 'Please select at least one employee.',
        ]);

        try {
            $task = $this->tasks->create(
                $data,
                auth()->user(),
                $data['assign_mode'] === 'pick' ? ($data['employee_ids'] ?? []) : null
            );
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['department_id' => $e->getMessage()]);
        }

        return redirect()->route('admin.tasks.show', $task)
            ->with('success', 'Task assigned to '.$task->assignees()->count().' employee(s).');
    }

    /* ------------------------------------------------------------------ */
    /* View                                                               */
    /* ------------------------------------------------------------------ */

    public function show(Task $task)
    {
        $admin = auth()->user();
        abort_unless($this->tasks->canView($task, $admin), 403, 'You cannot view this task.');

        $assignees = $this->tasks->visibleAssignees($task, $admin);
        $visibleIds = $assignees->pluck('id')->all();
        $canManage = $this->tasks->canManage($task, $admin);

        $task->load('assignees');

        $updates = $task->updates()
            ->when(! $canManage, fn ($q) => $q->where(function ($w) use ($visibleIds) {
                $w->whereNull('task_assignee_id')->orWhereIn('task_assignee_id', $visibleIds ?: [0]);
            }))
            ->limit(100)
            ->get();

        $mine = $admin->n_employee_id
            ? $task->assignees->firstWhere('employee_id', (int) $admin->n_employee_id)
            : null;

        return view('admin.tasks.show', [
            'task' => $task,
            'departmentName' => $this->departments()[$task->department_id] ?? '—',
            'assignees' => $assignees,
            'totalAssignees' => $task->assignees->count(),
            'updates' => $updates,
            'mine' => $mine,
            'canManage' => $canManage,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /* Assignee updates own status                                        */
    /* ------------------------------------------------------------------ */

    public function updateStatus(Request $request, Task $task)
    {
        $admin = auth()->user();

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Task::STATUSES))],
            'remark' => 'nullable|string|max:1000',
        ]);

        $mine = $admin->n_employee_id
            ? $task->assignees()->where('employee_id', (int) $admin->n_employee_id)->first()
            : null;

        abort_unless($mine, 403, 'This task is not assigned to you.');

        try {
            $this->tasks->updateStatus($mine, $data['status'], $data['remark'] ?? null, $admin);
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', 'Your status is now '.Task::STATUSES[$data['status']].'. Your managers can see it.');
    }

    /* ------------------------------------------------------------------ */
    /* Edit / cancel / delete (creator or see-all roles)                  */
    /* ------------------------------------------------------------------ */

    public function edit(Task $task)
    {
        abort_unless($this->tasks->canManage($task, auth()->user()), 403);

        return view('admin.tasks.form', [
            'task' => $task,
            'departments' => $this->departments(),
            'editing' => true,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        abort_unless($this->tasks->canManage($task, auth()->user()), 403);

        if ($task->isCancelled()) {
            return back()->withErrors(['title' => 'A cancelled task cannot be edited.']);
        }

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'priority' => ['required', Rule::in(array_keys(Task::PRIORITIES))],
            'due_date' => 'nullable|date',
        ]);

        $this->tasks->edit($task, $data, auth()->user());

        return redirect()->route('admin.tasks.show', $task)->with('success', 'Task updated.');
    }

    public function cancel(Request $request, Task $task)
    {
        abort_unless($this->tasks->canManage($task, auth()->user()), 403);

        $data = $request->validate(['reason' => 'nullable|string|max:500']);

        if (! $task->isCancelled()) {
            $this->tasks->cancel($task, $data['reason'] ?? null, auth()->user());
        }

        return redirect()->route('admin.tasks.show', $task)->with('success', 'Task cancelled.');
    }

    public function destroy(Task $task)
    {
        abort_unless($this->tasks->canManage($task, auth()->user()), 403);

        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted.');
    }
}
