@extends('layouts.app')

@section('topbarTitle', 'Tasks')

@section('content')
<style>
.tk-card{background:#fff;border:1px solid rgba(18,58,40,.1);border-radius:16px;box-shadow:0 6px 20px -14px rgba(18,58,40,.25);}
.tk-stat{padding:16px 18px;border-radius:16px;background:#fff;border:1px solid rgba(18,58,40,.1);height:100%;text-decoration:none;display:block;color:inherit;}
.tk-stat small{color:#5F7A6C;font-weight:600;text-transform:uppercase;letter-spacing:.06em;font-size:10.5px;}
.tk-stat b{display:block;font-size:26px;color:#1F5C2E;line-height:1.2;margin-top:2px;}
.tk-stat.warn b{color:#B26A00;} .tk-stat.bad b{color:#B3261E;}
.tk-tabs .nav-link{color:#1F5C2E;font-weight:600;border-radius:999px;padding:6px 16px;}
.tk-tabs .nav-link.active{background:#1F5C2E;color:#fff;}
.tk-title{font-weight:600;color:#123A28;}
.tk-meta{font-size:12px;color:#5F7A6C;}
.tk-bar{height:7px;border-radius:5px;background:#E3EDE3;overflow:hidden;min-width:90px;}
.tk-bar i{display:block;height:100%;background:#5E8D3D;}
.tk-overdue{color:#B3261E;font-weight:600;}
</style>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h4 class="fw-semibold mb-0" style="color:#1F5C2E">Tasks</h4>
        <div class="tk-meta">Tasks given to departments and employees, with priority and live status.</div>
    </div>
    @if($canCreate)
        <a href="{{ route('admin.tasks.create') }}" class="btn buttonSpc"><i class="bi bi-plus-lg"></i> Assign Task</a>
    @endif
</div>

{{-- Summary --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md"><a class="tk-stat" href="{{ route('admin.tasks.index', array_merge($filters, ['status' => ''])) }}"><small>All tasks</small><b>{{ $counts['total'] }}</b></a></div>
    <div class="col-6 col-md"><a class="tk-stat" href="{{ route('admin.tasks.index', array_merge($filters, ['status' => 'open'])) }}"><small>Open</small><b>{{ $counts['open'] }}</b></a></div>
    <div class="col-6 col-md"><a class="tk-stat warn" href="{{ route('admin.tasks.index', array_merge($filters, ['status' => 'on_hold'])) }}"><small>On hold</small><b>{{ $counts['on_hold'] }}</b></a></div>
    <div class="col-6 col-md"><a class="tk-stat bad" href="{{ route('admin.tasks.index', array_merge($filters, ['status' => 'overdue'])) }}"><small>Overdue</small><b>{{ $counts['overdue'] }}</b></a></div>
    <div class="col-6 col-md"><a class="tk-stat" href="{{ route('admin.tasks.index', array_merge($filters, ['status' => 'completed'])) }}"><small>Completed</small><b>{{ $counts['completed'] }}</b></a></div>
</div>

{{-- Filters --}}
@php
    $scope = $filters['scope'] ?? 'all';
    $tabs = ['all' => 'Everything I can see', 'mine' => 'Assigned to me', 'team' => 'My team'];
    if ($canCreate) { $tabs['created'] = 'Assigned by me'; }
@endphp
<div class="tk-card p-3 mb-3">
    <ul class="nav nav-pills tk-tabs mb-3">
        @foreach($tabs as $key => $label)
            <li class="nav-item">
                <a class="nav-link {{ $scope === $key ? 'active' : '' }}"
                   href="{{ route('admin.tasks.index', array_merge($filters, ['scope' => $key])) }}">{{ $label }}</a>
            </li>
        @endforeach
    </ul>

    <form method="GET" action="{{ route('admin.tasks.index') }}" class="row g-2 align-items-end">
        <input type="hidden" name="scope" value="{{ $scope }}">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Search</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Task title">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Department</label>
            <select name="department_id" class="form-select">
                <option value="">All departments</option>
                @foreach($departments as $id => $name)
                    <option value="{{ $id }}" @selected(($filters['department_id'] ?? '') == $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Priority</label>
            <select name="priority" class="form-select">
                <option value="">All</option>
                @foreach(\App\Models\Task::PRIORITIES as $k => $v)
                    <option value="{{ $k }}" @selected(($filters['priority'] ?? '') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                @foreach(['open' => 'Open', 'on_hold' => 'On hold', 'overdue' => 'Overdue', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $k => $v)
                    <option value="{{ $k }}" @selected(($filters['status'] ?? '') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn buttonSpc flex-fill" type="submit">Filter</button>
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

{{-- List --}}
<div class="tk-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Task</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Due</th>
                    <th style="min-width:150px">Progress</th>
                    <th>Status</th>
                    <th>My status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($tasks as $task)
                @php
                    [$done, $total] = $task->progress();
                    $pct = $total ? round($done / $total * 100) : 0;
                    $overall = $task->overallStatus();
                    $mine = $myEmployeeId ? $task->assignees->firstWhere('employee_id', $myEmployeeId) : null;
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.tasks.show', $task) }}" class="tk-title text-decoration-none">{{ $task->title }}</a>
                        <div class="tk-meta">By {{ $task->created_by_name ?: '—' }} · {{ $task->created_at->format('d M Y') }}</div>
                    </td>
                    <td>{{ $departments[$task->department_id] ?? '—' }}</td>
                    <td><span class="badge {{ \App\Models\Task::priorityBadge($task->priority) }}">{{ \App\Models\Task::PRIORITIES[$task->priority] ?? ucfirst($task->priority) }}</span></td>
                    <td>
                        @if($task->due_date)
                            <span class="{{ $task->isOverdue() ? 'tk-overdue' : '' }}">{{ $task->due_date->format('d M Y') }}</span>
                            @if($task->isOverdue())<div class="tk-meta tk-overdue">Overdue</div>@endif
                        @else — @endif
                    </td>
                    <td>
                        <div class="tk-bar"><i style="width:{{ $pct }}%"></i></div>
                        <div class="tk-meta">{{ $done }} of {{ $total }} done</div>
                    </td>
                    <td><span class="badge {{ \App\Models\Task::statusBadge($overall) }}">{{ $task->overallLabel() }}</span></td>
                    <td>
                        @if($mine)
                            <span class="badge {{ \App\Models\Task::statusBadge($mine->status) }}">{{ \App\Models\Task::STATUSES[$mine->status] }}</span>
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td class="text-end"><a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-success">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-5">No tasks found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $tasks->links() }}</div>
</div>
@endsection
