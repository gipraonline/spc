<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'tasks';

    protected string $auditEntity = 'Task';

    protected array $auditSubjectColumns = ['title'];

    use SoftDeletes;

    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    /** Status an assignee can set on their own part of a task. */
    public const STATUSES = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
    ];

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function assignees()
    {
        return $this->hasMany(TaskAssignee::class);
    }

    public function updates()
    {
        return $this->hasMany(TaskUpdate::class)->orderByDesc('id');
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Overall status worked out from everyone's own status:
     * cancelled | completed (all done) | on_hold (someone blocked) |
     * in_progress (work started) | pending (nobody started).
     */
    public function overallStatus(): string
    {
        if ($this->isCancelled()) {
            return 'cancelled';
        }

        $list = $this->relationLoaded('assignees') ? $this->assignees : $this->assignees()->get();
        $statuses = $list->pluck('status');

        if ($statuses->isEmpty()) {
            return 'pending';
        }
        if ($statuses->every(fn ($s) => $s === 'completed')) {
            return 'completed';
        }
        if ($statuses->contains('on_hold')) {
            return 'on_hold';
        }
        if ($statuses->contains('in_progress') || $statuses->contains('completed')) {
            return 'in_progress';
        }

        return 'pending';
    }

    public function overallLabel(): string
    {
        $s = $this->overallStatus();

        return $s === 'cancelled' ? 'Cancelled' : (self::STATUSES[$s] ?? ucfirst($s));
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->lt(today())
            && ! in_array($this->overallStatus(), ['completed', 'cancelled'], true);
    }

    /** [done, total] across all assignees. */
    public function progress(): array
    {
        $list = $this->relationLoaded('assignees') ? $this->assignees : $this->assignees()->get();

        return [$list->where('status', 'completed')->count(), $list->count()];
    }

    public static function priorityBadge(string $priority): string
    {
        return match ($priority) {
            'urgent' => 'bg-danger',
            'high' => 'bg-warning text-dark',
            'medium' => 'bg-info text-dark',
            default => 'bg-secondary',
        };
    }

    public static function statusBadge(string $status): string
    {
        return match ($status) {
            'completed' => 'bg-success',
            'in_progress' => 'bg-primary',
            'on_hold' => 'bg-warning text-dark',
            'cancelled' => 'bg-dark',
            default => 'bg-secondary',
        };
    }
}
