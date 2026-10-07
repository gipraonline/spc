@extends('layouts.app')

@section('topbarTitle', 'Task')

@section('content')
<style>
.tk-card{background:#fff;border:1px solid rgba(18,58,40,.1);border-radius:16px;box-shadow:0 6px 20px -14px rgba(18,58,40,.25);}
.tk-meta{font-size:12px;color:#5F7A6C;}
.tk-bar{height:9px;border-radius:5px;background:#E3EDE3;overflow:hidden;}
.tk-bar i{display:block;height:100%;background:#5E8D3D;}
.tk-tl{border-left:2px solid #DCEBD5;margin-left:6px;padding-left:16px;}
.tk-tl .item{position:relative;padding-bottom:14px;}
.tk-tl .item:before{content:"";position:absolute;left:-23px;top:4px;width:12px;height:12px;border-radius:50%;background:#5E8D3D;border:2px solid #fff;}
.tk-overdue{color:#B3261E;font-weight:600;}
</style>

@php
    $overall = $task->overallStatus();
    [$done, $total] = $task->progress();
    $pct = $total ? round($done / $total * 100) : 0;
@endphp

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="mb-3 d-flex flex-wrap justify-content-between align-items-start gap-2">
    <div>
        <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none" style="color:#1F5C2E"><i class="bi bi-arrow-left"></i> Back to tasks</a>
        <h4 class="fw-semibold mt-1 mb-1" style="color:#1F5C2E">{{ $task->title }}</h4>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge {{ \App\Models\Task::priorityBadge($task->priority) }}">{{ \App\Models\Task::PRIORITIES[$task->priority] }} priority</span>
            <span class="badge {{ \App\Models\Task::statusBadge($overall) }}">{{ $task->overallLabel() }}</span>
            @if($task->isOverdue())<span class="badge bg-danger">Overdue</span>@endif
        </div>
    </div>
    @if($canManage)
        <div class="d-flex gap-2">
            @unless($task->isCancelled())
                @can('tasks.edit')
                    <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-outline-success btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#tkCancel"><i class="bi bi-x-circle"></i> Cancel task</button>
                @endcan
            @endunless
            @can('tasks.delete')
                <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task for everyone?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                </form>
            @endcan
        </div>
    @endif
</div>

@if($task->isCancelled())
    <div class="alert alert-dark">This task was cancelled on {{ $task->cancelled_at?->format('d M Y, h:i A') }}.
        @if($task->cancel_reason) Reason: {{ $task->cancel_reason }} @endif</div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        {{-- Details --}}
        <div class="tk-card p-4 mb-3">
            <div class="row g-3 mb-3">
                <div class="col-sm-4"><div class="tk-meta">Department</div><b>{{ $departmentName }}</b></div>
                <div class="col-sm-4"><div class="tk-meta">Assigned by</div><b>{{ $task->created_by_name ?: '—' }}</b></div>
                <div class="col-sm-4"><div class="tk-meta">Due date</div>
                    <b class="{{ $task->isOverdue() ? 'tk-overdue' : '' }}">{{ $task->due_date ? $task->due_date->format('d M Y') : 'No due date' }}</b></div>
            </div>
            @if($task->description)
                <div class="tk-meta">Details</div>
                <div class="mb-3">{!! nl2br(e($task->description)) !!}</div>
            @endif
            <div class="d-flex justify-content-between"><span class="tk-meta">Overall progress</span><b>{{ $done }} of {{ $total }} completed</b></div>
            <div class="tk-bar mt-1"><i style="width:{{ $pct }}%"></i></div>
        </div>

        {{-- My status --}}
        @if($mine && ! $task->isCancelled())
            <div class="tk-card p-4 mb-3" style="border-color:#BFD9A9">
                <h6 class="fw-semibold" style="color:#1F5C2E"><i class="bi bi-person-check"></i> Update my status</h6>
                <div class="mb-2">Current: <span class="badge {{ \App\Models\Task::statusBadge($mine->status) }}">{{ \App\Models\Task::STATUSES[$mine->status] }}</span></div>
                @can('tasks.update-status')
                <form method="POST" action="{{ route('admin.tasks.status', $task) }}" class="row g-2">
                    @csrf
                    <div class="col-md-4">
                        <select name="status" class="form-select" required>
                            @foreach(\App\Models\Task::STATUSES as $k => $v)
                                <option value="{{ $k }}" @selected(old('status', $mine->status) === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="remark" maxlength="1000" class="form-control" value="{{ old('remark') }}"
                               placeholder="Remark (required if On Hold, optional otherwise)">
                    </div>
                    <div class="col-12"><button class="btn buttonSpc" type="submit">Save status</button>
                        <span class="tk-meta ms-2">Your reporting managers are notified automatically.</span></div>
                </form>
                @endcan
            </div>
        @endif

        {{-- Assignees --}}
        <div class="tk-card mb-3">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0" style="color:#1F5C2E">Assigned employees</h6>
                @if($assignees->count() < $totalAssignees)
                    <span class="tk-meta">Showing {{ $assignees->count() }} of {{ $totalAssignees }} — people in your reporting line</span>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Employee</th><th>Status</th><th>Started</th><th>Completed</th><th>Last remark</th></tr></thead>
                    <tbody>
                    @forelse($assignees as $a)
                        <tr>
                            <td><b>{{ $a->employee?->c_employee_name ?? 'Employee #'.$a->employee_id }}</b>
                                <div class="tk-meta">{{ $a->employee?->c_employee_code }}</div></td>
                            <td><span class="badge {{ \App\Models\Task::statusBadge($a->status) }}">{{ \App\Models\Task::STATUSES[$a->status] }}</span></td>
                            <td class="tk-meta">{{ $a->started_at?->format('d M, h:i A') ?? '—' }}</td>
                            <td class="tk-meta">{{ $a->completed_at?->format('d M, h:i A') ?? '—' }}</td>
                            <td class="tk-meta">{{ $a->remark ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No assignees in your reporting line.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- History --}}
    <div class="col-lg-4">
        <div class="tk-card p-4">
            <h6 class="fw-semibold mb-3" style="color:#1F5C2E">History</h6>
            <div class="tk-tl">
                @forelse($updates as $u)
                    <div class="item">
                        <div>
                            @if($u->type === 'status')
                                <b>{{ $u->actor_name }}</b> changed status
                                {{ \App\Models\Task::STATUSES[$u->from_status] ?? '' }} → <b>{{ \App\Models\Task::STATUSES[$u->to_status] ?? $u->to_status }}</b>
                            @elseif($u->type === 'created')
                                <b>{{ $u->actor_name }}</b> assigned the task
                            @elseif($u->type === 'edited')
                                <b>{{ $u->actor_name }}</b> edited the task
                            @else
                                <b>{{ $u->actor_name }}</b> cancelled the task
                            @endif
                        </div>
                        @if($u->remark)<div class="tk-meta">“{{ $u->remark }}”</div>@endif
                        <div class="tk-meta">{{ $u->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                @empty
                    <div class="tk-meta">No activity yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Cancel modal --}}
@if($canManage && ! $task->isCancelled())
<div class="modal fade" id="tkCancel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><form method="POST" action="{{ route('admin.tasks.cancel', $task) }}" class="modal-content">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Cancel this task?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <p class="mb-2">Assigned employees will be told it is cancelled and can no longer update it.</p>
            <label class="form-label fw-semibold">Reason (optional)</label>
            <textarea name="reason" rows="3" maxlength="500" class="form-control"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Keep task</button>
            <button type="submit" class="btn btn-warning">Cancel task</button>
        </div>
    </form></div>
</div>
@endif
@endsection
