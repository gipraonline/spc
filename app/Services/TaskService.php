<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\EmployeeMaster;
use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\TaskUpdate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Business rules for task assignment.
 *
 *  - Who sees a task:  the person who created it, every assignee, and everyone
 *    ABOVE an assignee in the reporting line (same rule as leads / field logs,
 *    via HierarchyScope). Super Admin, Gipra Admin and National Sales Head see all.
 *  - Who can be assigned: Super Admin -> anyone; everyone else -> only people
 *    below them in the reporting line (see assignableEmployeeIds()).
 *  - Who updates it:   only the assignee, on their own row.
 *  - Who is told:      when an assignee changes status, the creator and the
 *    reporting managers above the assignee (config tasks.notify_levels) get a
 *    bell notification and can see the new status on their Tasks list.
 */
class TaskService
{
    /** Tasks the admin is allowed to see. */
    public function visibleQuery(Admin $admin): Builder
    {
        $query = Task::query();
        $ids = HierarchyScope::employeeIds($admin);

        if ($ids === null) {
            return $query; // sees everything
        }

        return $query->where(function (Builder $w) use ($admin, $ids) {
            $w->where('created_by', $admin->getKey());

            if ($ids) {
                $w->orWhereHas('assignees', fn ($a) => $a->whereIn('employee_id', $ids));
            }
        });
    }

    public function canView(Task $task, Admin $admin): bool
    {
        return $this->visibleQuery($admin)->whereKey($task->getKey())->exists();
    }

    /** Creator and see-all roles can manage (edit / cancel / delete) a task. */
    public function canManage(Task $task, Admin $admin): bool
    {
        return HierarchyScope::seesAll($admin) || (int) $task->created_by === (int) $admin->getKey();
    }

    /**
     * Assignee rows the admin may see: all of them for the creator and see-all
     * roles, otherwise only the people in the admin's own reporting line.
     */
    public function visibleAssignees(Task $task, Admin $admin): Collection
    {
        $all = $task->assignees()->with('employee')->get();

        if ($this->canManage($task, $admin)) {
            return $all;
        }

        $ids = HierarchyScope::employeeIds($admin) ?? [];

        return $all->filter(fn ($a) => in_array((int) $a->employee_id, $ids, true))->values();
    }

    /** Super Admin (config tasks.unrestricted_roles) may assign to anyone. */
    public function canAssignToAnyone(Admin $admin): bool
    {
        return $admin->hasAnyRole((array) config('tasks.unrestricted_roles', ['Super Admin']));
    }

    /**
     * Employee ids the admin may assign tasks to: everyone below them in the
     * reporting line, at any depth (not themself).
     *
     * @return int[]|null  null = unrestricted, [] = nobody
     */
    public function assignableEmployeeIds(Admin $admin): ?array
    {
        if ($this->canAssignToAnyone($admin)) {
            return null;
        }

        if (! $admin->n_employee_id) {
            return [];
        }

        return HierarchyScope::descendants((int) $admin->n_employee_id);
    }

    /** id => name of the departments the admin may assign to. */
    public function assignableDepartments(Admin $admin, array $allDepartments): array
    {
        $ids = $this->assignableEmployeeIds($admin);

        if ($ids === null) {
            return $allDepartments;
        }
        if (! $ids) {
            return [];
        }

        $deptIds = EmployeeMaster::query()
            ->whereIn('n_employee_id', $ids)
            ->where('c_status', 'Y')
            ->whereNotNull('department_id')
            ->distinct()
            ->pluck('department_id')
            ->map(fn ($d) => (int) $d)
            ->all();

        return array_filter($allDepartments, fn ($name, $id) => in_array((int) $id, $deptIds, true), ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Active employees of one department, limited to the admin's reporting
     * line unless the admin is unrestricted.
     */
    public function departmentEmployees(int $departmentId, ?Admin $admin = null): Collection
    {
        $query = EmployeeMaster::query()
            ->where('department_id', $departmentId)
            ->where('c_status', 'Y');

        if ($admin && ($ids = $this->assignableEmployeeIds($admin)) !== null) {
            $query->whereIn('n_employee_id', $ids ?: [0]);
        }

        return $query
            ->orderBy('c_employee_name')
            ->get(['n_employee_id', 'c_employee_name', 'c_employee_code', 'n_designation_id']);
    }

    /**
     * Create a task and give it to a department (everyone active in it) or to
     * selected people of that department.
     *
     * @param  int[]|null  $employeeIds  null / empty = the whole department
     */
    public function create(array $data, Admin $actor, ?array $employeeIds = null): Task
    {
        $employees = $this->departmentEmployees((int) $data['department_id'], $actor);

        if ($employeeIds) {
            $employees = $employees->whereIn('n_employee_id', array_map('intval', $employeeIds));
        }

        if ($employees->isEmpty()) {
            throw new InvalidArgumentException($this->canAssignToAnyone($actor)
                ? 'There are no active employees to assign this task to.'
                : 'There are no active employees under you in this department to assign this task to.');
        }

        $task = DB::transaction(function () use ($data, $actor, $employees) {
            $task = Task::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'],
                'due_date' => $data['due_date'] ?? null,
                'department_id' => (int) $data['department_id'],
                'created_by' => $actor->getKey(),
                'created_by_employee_id' => $actor->n_employee_id,
                'created_by_name' => $actor->c_name,
                'status' => 'active',
            ]);

            foreach ($employees as $employee) {
                $task->assignees()->create([
                    'employee_id' => $employee->n_employee_id,
                    'status' => 'pending',
                ]);
            }

            $this->log($task, null, $actor, 'created', null, 'pending', 'Task assigned to '.$employees->count().' employee(s).');

            return $task;
        });

        $this->notifyEmployees(
            $employees->pluck('n_employee_id')->all(),
            'New '.strtolower(Task::PRIORITIES[$task->priority]).'-priority task: '.$task->title,
            $task
        );

        return $task;
    }

    /**
     * An assignee changes the status of their own part of a task.
     *
     * @throws InvalidArgumentException when the change is not allowed
     */
    public function updateStatus(TaskAssignee $assignee, string $to, ?string $remark, Admin $actor): TaskAssignee
    {
        $task = $assignee->task;

        if ($task->isCancelled()) {
            throw new InvalidArgumentException('This task has been cancelled and can no longer be updated.');
        }
        if (! array_key_exists($to, Task::STATUSES)) {
            throw new InvalidArgumentException('Please choose a valid status.');
        }
        if ($assignee->status === $to) {
            throw new InvalidArgumentException('The status is already '.Task::STATUSES[$to].'.');
        }

        $remark = trim((string) $remark);
        if ($to === 'on_hold' && $remark === '') {
            throw new InvalidArgumentException('Please write the reason when putting a task On Hold.');
        }

        $from = $assignee->status;

        DB::transaction(function () use ($assignee, $task, $to, $remark, $from, $actor) {
            $assignee->status = $to;
            $assignee->last_status_at = now();
            $assignee->remark = $remark !== '' ? Str::limit($remark, 1000, '') : $assignee->remark;

            if (in_array($to, ['in_progress', 'completed'], true) && ! $assignee->started_at) {
                $assignee->started_at = now();
            }
            $assignee->completed_at = $to === 'completed' ? now() : null;
            $assignee->save();

            $this->log($task, $assignee, $actor, 'status', $from, $to, $remark !== '' ? $remark : null);
        });

        $this->notifyUp($task->fresh('assignees'), $assignee, $from, $to, $actor);

        return $assignee;
    }

    /** Edit title / description / priority / due date; tell assignees if priority or due date moved. */
    public function edit(Task $task, array $data, Admin $actor): Task
    {
        $task->fill([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'priority' => $data['priority'],
            'due_date' => $data['due_date'] ?? null,
        ]);

        $changes = [];
        if ($task->isDirty('priority')) {
            $changes[] = 'priority '.Task::PRIORITIES[$task->getOriginal('priority')].' → '.Task::PRIORITIES[$task->priority];
        }
        if ($task->isDirty('due_date')) {
            $old = $task->getOriginal('due_date');
            $changes[] = 'due date '.($old ? \Illuminate\Support\Carbon::parse($old)->format('d M Y') : 'none')
                .' → '.($task->due_date ? $task->due_date->format('d M Y') : 'none');
        }

        $dirty = $task->isDirty();
        $task->save();

        if ($dirty) {
            $this->log($task, null, $actor, 'edited', null, null, $changes ? implode('; ', $changes) : 'Details edited.');

            if ($changes) {
                $this->notifyEmployees(
                    $task->assignees()->pluck('employee_id')->all(),
                    'Task updated ('.implode(', ', $changes).'): '.$task->title,
                    $task,
                    $actor
                );
            }
        }

        return $task;
    }

    public function cancel(Task $task, ?string $reason, Admin $actor): Task
    {
        $task->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason' => $reason ? Str::limit($reason, 500, '') : null,
        ]);

        $this->log($task, null, $actor, 'cancelled', null, null, $reason);

        $this->notifyEmployees(
            $task->assignees()->pluck('employee_id')->all(),
            'Task cancelled: '.$task->title,
            $task,
            $actor
        );

        return $task;
    }

    /* ------------------------------------------------------------------ */
    /* Notifications                                                      */
    /* ------------------------------------------------------------------ */

    /** Reporting managers above an employee, nearest first (cycle-safe). */
    public function managerChain(int $employeeId, ?int $levels): array
    {
        $chain = [];
        $seen = [$employeeId => true];
        $current = $employeeId;

        while ($levels === null || count($chain) < $levels) {
            $manager = EmployeeMaster::withTrashed()->where('n_employee_id', $current)->value('reporting_to');

            if (! $manager || isset($seen[(int) $manager])) {
                break;
            }

            $chain[] = (int) $manager;
            $seen[(int) $manager] = true;
            $current = (int) $manager;
        }

        return $chain;
    }

    /** Tell the assignee's bosses and the creator that a status changed. */
    private function notifyUp(Task $task, TaskAssignee $assignee, string $from, string $to, Admin $actor): void
    {
        $levels = config('tasks.notify_levels');
        $chain = $this->managerChain((int) $assignee->employee_id, $levels === null ? null : (int) $levels);

        $adminIds = Admin::whereIn('n_employee_id', $chain)->pluck('n_role_id')->all();
        $adminIds[] = (int) $task->created_by;

        $name = $assignee->employee?->c_employee_name ?? $actor->c_name;
        $message = $name.' moved "'.Str::limit($task->title, 80).'" to '.Task::STATUSES[$to];

        if ($to === 'completed') {
            [$done, $total] = $task->progress();
            if ($done === $total) {
                $message .= ' — all '.$total.' assignee(s) have completed it.';
            }
        }

        $link = route('admin.tasks.show', $task);

        foreach (array_unique(array_map('intval', $adminIds)) as $adminId) {
            if ($adminId === (int) $actor->getKey()) {
                continue;
            }
            Notification::send($adminId, 'task', Str::limit($message, 250, ''), $link, 'Task update');
        }
    }

    /** Bell notification to the login accounts of the given employees. */
    private function notifyEmployees(array $employeeIds, string $message, Task $task, ?Admin $except = null): void
    {
        $link = route('admin.tasks.show', $task);

        Admin::whereIn('n_employee_id', $employeeIds)->pluck('n_role_id')->each(function ($adminId) use ($message, $link, $except) {
            if ($except && (int) $adminId === (int) $except->getKey()) {
                return;
            }
            Notification::send((int) $adminId, 'task', Str::limit($message, 250, ''), $link, 'Task');
        });
    }

    private function log(Task $task, ?TaskAssignee $assignee, Admin $actor, string $type, ?string $from, ?string $to, ?string $remark): void
    {
        TaskUpdate::create([
            'task_id' => $task->getKey(),
            'task_assignee_id' => $assignee?->getKey(),
            'type' => $type,
            'from_status' => $from,
            'to_status' => $to,
            'remark' => $remark ? Str::limit($remark, 1000, '') : null,
            'actor_admin_id' => $actor->getKey(),
            'actor_name' => $actor->c_name,
        ]);
    }
}
