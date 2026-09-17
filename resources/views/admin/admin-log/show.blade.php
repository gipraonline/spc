@extends('layouts.app')

@section('content')

<style>
/* =========================================================
       Field Log Dashboard
    ========================================================= */

.field-log-page {
    --card-radius: 16px;
    --soft-bg: #f8fafc;
    --border-color: #e9edf3;
}

.field-log-page .card {
    border-radius: var(--card-radius);
    border: 1px solid var(--border-color);
}

/* Header */
.page-header-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.page-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
}

.page-subtitle {
    font-size: .82rem;
    color: #94a3b8;
    margin-top: 3px;
}

/* =========================================================
       Information Cards
    ========================================================= */

.info-card {
    position: relative;
    overflow: hidden;
    background: #fff;
    transition: all .25s ease;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(15, 23, 42, .08) !important;
}

.info-card::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 70px;
    height: 70px;
    border-radius: 0 0 0 70px;
    opacity: .06;
    background: currentColor;
}

.info-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.info-label {
    font-size: .72rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 5px;
}

.info-value {
    font-size: .95rem;
    font-weight: 700;
    color: #1e293b;
}

/* =========================================================
       Remark Cards
    ========================================================= */

.remark-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 18px;
    height: 100%;
}

.remark-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .85rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 10px;
}

.remark-title i {
    font-size: 1rem;
}

.remark-text {
    min-height: 90px;
    background: #f8fafc;
    border: 1px solid #eef2f7;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: .86rem;
    color: #475569;
    line-height: 1.6;
}

/* =========================================================
       Summary Cards
    ========================================================= */

.summary-card {
    position: relative;
    overflow: hidden;
    border: 0 !important;
    background: #fff;
    transition: all .25s ease;
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(15, 23, 42, .09) !important;
}

.summary-card .summary-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.summary-label {
    font-size: .78rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 6px;
}

.summary-number {
    font-size: 1.85rem;
    line-height: 1;
    font-weight: 800;
    color: #1e293b;
}

.summary-description {
    font-size: .74rem;
    color: #94a3b8;
    margin-top: 7px;
}

.summary-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
}

/* =========================================================
       Progress
    ========================================================= */

.progress-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.progress {
    background-color: #e9eef5;
}

.progress-bar {
    transition: width .6s ease;
}

.progress-percentage {
    font-size: 1.5rem;
    font-weight: 800;
}

/* =========================================================
       Task Table
    ========================================================= */

.task-card {
    overflow: hidden;
}

.task-card-header {
    padding: 20px 22px;
    border-bottom: 1px solid #eef2f7;
    background: #fff;
}

.task-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 3px;
}

.task-subtitle {
    font-size: .76rem;
    color: #94a3b8;
}

.task-table {
    margin-bottom: 0;
}

.task-table thead th {
    linear-gradient(135deg, #5A8D3A, #074E30);
    color: #64748b;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 13px 15px;
    border-bottom: 1px solid #e5eaf0;
    white-space: nowrap;
}

.task-table tbody td {
    padding: 14px 15px;
    color: #475569;
    font-size: .84rem;
    border-color: #eef2f7;
    vertical-align: middle;
}

.task-table tbody tr {
    transition: background .2s ease;
}

.task-table tbody tr:hover {
    background: #f8fafc;
}

.task-number {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #f1f5f9;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    font-weight: 700;
}

.task-name {
    font-weight: 600;
    color: #334155;
    min-width: 180px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 50px;
    font-size: .7rem;
    font-weight: 700;
}

.status-badge i {
    font-size: .55rem;
}

.status-done {
    color: #15803d;
    background: #dcfce7;
}

.status-progress {
    color: #a16207;
    background: #fef3c7;
}

.status-pending {
    color: #dc2626;
    background: #fee2e2;
}

.status-default {
    color: #475569;
    background: #f1f5f9;
}

.empty-state {
    padding: 50px 20px !important;
    color: #94a3b8 !important;
}

.empty-state i {
    font-size: 2rem;
    display: block;
    margin-bottom: 8px;
}

/* =========================================================
       Responsive
    ========================================================= */

@media (max-width: 767px) {
    .page-title {
        font-size: 1rem;
    }

    .summary-number {
        font-size: 1.55rem;
    }

    .info-icon,
    .summary-card .summary-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
    }

    .task-card-header {
        padding: 16px;
    }
}
</style>


<div class="container-fluid field-log-page py-2">

    {{-- =========================================================
         Page Header
    ========================================================= --}}

    <div class="card page-header-card shadow-sm mb-4">

        <div class="card-body px-4 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>
                    <div class="d-flex align-items-center gap-2">

                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width:42px;height:42px;">
                            <i class="bi bi-clipboard2-data fs-5"></i>
                        </div>

                        <div>
                            <div class="page-title">
                                Field Log Details
                            </div>

                            <div class="page-subtitle">
                                Employee attendance and task activity summary
                            </div>
                        </div>

                    </div>
                </div>

                <a href="{{ route('admin.admin-log.index') }}" class="btn btn-outline-secondary px-3">

                    <i class="bi bi-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Field Log Information
    ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Employee --}}
        <div class="col-xl-3 col-md-6">

            <div class="card info-card shadow-sm h-100 text-primary">

                <div class="card-body p-3">

                    <div class="d-flex align-items-center">

                        <div class="info-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="info-label">
                                Employee
                            </div>

                            <div class="info-value text-truncate">
                                {{ $fieldLog->admin->c_name ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Work Date --}}
        <div class="col-xl-3 col-md-6">

            <div class="card info-card shadow-sm h-100 text-info">

                <div class="card-body p-3">

                    <div class="d-flex align-items-center">

                        <div class="info-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="info-label">
                                Work Date
                            </div>

                            <div class="info-value">
                                {{ $fieldLog->work_date
                                    ? $fieldLog->work_date->format('d M Y')
                                    : '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Check In --}}
        <div class="col-xl-3 col-md-6">

            <div class="card info-card shadow-sm h-100 text-success">

                <div class="card-body p-3">

                    <div class="d-flex align-items-center">

                        <div class="info-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="info-label">
                                Check In
                            </div>

                            <div class="info-value text-success">
                                {{ $fieldLog->check_in_time
                                    ? $fieldLog->check_in_time->format('h:i A')
                                    : '--' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Check Out --}}
        <div class="col-xl-3 col-md-6">

            <div class="card info-card shadow-sm h-100 text-danger">

                <div class="card-body p-3">

                    <div class="d-flex align-items-center">

                        <div class="info-icon bg-danger bg-opacity-10 text-danger me-3">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="info-label">
                                Check Out
                            </div>

                            <div class="info-value text-danger">
                                {{ $fieldLog->check_out_time
                                    ? $fieldLog->check_out_time->format('h:i A')
                                    : '--' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Remarks
    ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Check In Remark --}}
        <div class="col-lg-6">

            <div class="remark-card shadow-sm">

                <div class="remark-title">
                    <i class="bi bi-box-arrow-in-right text-success"></i>
                    Check In Remark
                </div>

                <div class="remark-text">
                    {{ $fieldLog->check_in_remark ?: 'No check-in remark provided.' }}
                </div>

            </div>

        </div>


        {{-- Check Out Remark --}}
        <div class="col-lg-6">

            <div class="remark-card shadow-sm">

                <div class="remark-title">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                    Check Out Remark
                </div>

                <div class="remark-text">
                    {{ $fieldLog->check_out_remark ?: 'No check-out remark provided.' }}
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Task Summary
    ========================================================= --}}

    <div class="d-flex align-items-center mb-3">

        <div>
            <h5 class="fw-bold mb-1">
                Task Overview
            </h5>

            <small class="text-muted">
                Summary of today's assigned work
            </small>
        </div>

    </div>


    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card summary-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="summary-label">
                                Total Tasks
                            </div>

                            <div class="summary-number">
                                {{ $total }}
                            </div>

                            <div class="summary-description">
                                All assigned tasks
                            </div>

                        </div>

                        <div class="summary-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-list-task"></i>
                        </div>

                    </div>

                </div>

                <div class="summary-bottom bg-primary"></div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-xl-3 col-md-6">

            <div class="card summary-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="summary-label">
                                Completed
                            </div>

                            <div class="summary-number text-success">
                                {{ $done }}
                            </div>

                            <div class="summary-description">
                                Tasks completed
                            </div>

                        </div>

                        <div class="summary-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

                <div class="summary-bottom bg-success"></div>

            </div>

        </div>


        {{-- In Progress --}}
        <div class="col-xl-3 col-md-6">

            <div class="card summary-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="summary-label">
                                In Progress
                            </div>

                            <div class="summary-number text-warning">
                                {{ $inProgress }}
                            </div>

                            <div class="summary-description">
                                Currently working
                            </div>

                        </div>

                        <div class="summary-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                    </div>

                </div>

                <div class="summary-bottom bg-warning"></div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">

            <div class="card summary-card shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="summary-label">
                                Pending
                            </div>

                            <div class="summary-number text-danger">
                                {{ $pending }}
                            </div>

                            <div class="summary-description">
                                Awaiting completion
                            </div>

                        </div>

                        <div class="summary-icon bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-clock-history"></i>
                        </div>

                    </div>

                </div>

                <div class="summary-bottom bg-danger"></div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Task Progress
    ========================================================= --}}

    <div class="card progress-card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <div class="d-flex align-items-center gap-2 mb-1">

                        <div class="rounded-2 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                            style="width:34px;height:34px;">

                            <i class="bi bi-graph-up-arrow"></i>

                        </div>

                        <h6 class="fw-bold mb-0">
                            Task Progress
                        </h6>

                    </div>

                    <small class="text-muted ms-1">
                        {{ $done }} of {{ $total }} tasks completed
                    </small>

                </div>

                <div class="text-end">

                    <div class="progress-percentage text-success">
                        {{ $percent }}%
                    </div>

                    <small class="text-muted">
                        Completion
                    </small>

                </div>

            </div>


            <div class="progress rounded-pill" style="height: 12px;">

                <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $percent }}%;"
                    aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                </div>

            </div>


            <div class="d-flex justify-content-between mt-2">

                <small class="text-muted">
                    <i class="bi bi-play-circle me-1"></i>
                    Started
                </small>

                <small class="text-muted">

                    @if($percent == 100)

                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    All tasks completed

                    @else

                    <i class="bi bi-arrow-repeat me-1"></i>
                    Work in progress

                    @endif

                </small>

            </div>

        </div>

    </div>


    {{-- =========================================================
         Task List
    ========================================================= --}}

    <div class="card task-card border-0 shadow-sm mb-4">

        <div class="task-card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="task-title">
                        Task Details
                    </div>

                    <div class="task-subtitle">
                        Detailed breakdown of assigned tasks
                    </div>

                </div>

                <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                    style="width:40px;height:40px;">

                    <i class="bi bi-table"></i>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table task-table align-middle">

                <thead>

                    <tr>

                        <th class="ps-4">#</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Pending Remark</th>
                        <th>Completed At</th>
                        <th>Time Taken</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($tasks as $key => $task)

                    <tr>

                        {{-- Number --}}
                        <td class="ps-4">

                            <span class="task-number">
                                {{ $key + 1 }}
                            </span>

                        </td>


                        {{-- Task --}}
                        <td>

                            <div class="task-name">
                                {{ $task->task ?? '-' }}
                            </div>

                        </td>


                        {{-- Status --}}
                        <td>

                            @if($task->status === 'Checked Out')

                            <span class="status-badge status-done">
                                <i class="bi bi-circle-fill"></i>
                                Done
                            </span>

                            @elseif($task->status === 'In Progress')

                            <span class="status-badge status-progress">
                                <i class="bi bi-circle-fill"></i>
                                In Progress
                            </span>

                            @elseif($task->status === 'Pending')

                            <span class="status-badge status-pending">
                                <i class="bi bi-circle-fill"></i>
                                Pending
                            </span>

                            @else

                            <span class="status-badge status-default">
                                <i class="bi bi-circle-fill"></i>
                                {{ $task->status ?? 'Unknown' }}
                            </span>

                            @endif

                        </td>


                        {{-- Pending Remark --}}
                        <td>

                            @if($task->pending_remark)

                            <span class="text-secondary">
                                {{ $task->pending_remark }}
                            </span>

                            @else

                            <span class="text-muted">
                                —
                            </span>

                            @endif

                        </td>


                        {{-- Completed At --}}
                        <td>

                            @if($task->completed_at)

                            <div class="fw-semibold text-dark">
                                {{ $task->completed_at->format('d M Y') }}
                            </div>

                            <small class="text-muted">
                                {{ $task->completed_at->format('h:i A') }}
                            </small>

                            @else

                            <span class="text-muted">
                                —
                            </span>

                            @endif

                        </td>


                        {{-- Time Taken --}}
                        <td>

                            @if($task->completed_at && $fieldLog->check_in_time)

                            <span class="fw-semibold text-primary">

                                <i class="bi bi-stopwatch me-1"></i>

                                {{ $fieldLog->check_in_time->diffForHumans(
                                            $task->completed_at,
                                            [
                                                'parts' => 2,
                                                'short' => true,
                                                'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE
                                            ]
                                        ) }}

                            </span>

                            @else

                            <span class="text-muted">
                                —
                            </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6" class="text-center empty-state">

                            <i class="bi bi-clipboard-x"></i>

                            <div class="fw-semibold">
                                No Tasks Found
                            </div>

                            <small>
                                There are no tasks assigned to this field log.
                            </small>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection