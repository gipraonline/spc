@extends('layouts.app')

@section('content')

<div class="card">


    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log Details
        </h5>

        <a href="{{ route('admin.admin-log.index') }}" class="btn btn-secondary">
            Back
        </a>

    </div>


    <div class="card-body">


        {{-- ========================================================= --}}
        {{-- Field Log Information --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">

            {{-- Employee --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center">

                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-person-fill fs-5"></i>
                            </div>

                            <div class="flex-grow-1">

                                <small class="text-muted d-block mb-1">
                                    Employee
                                </small>

                                <h6 class="fw-bold mb-0 text-dark">
                                    {{ $fieldLog->admin->c_name ?? '-' }}
                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Date --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center">

                            <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-calendar3 fs-5"></i>
                            </div>

                            <div class="flex-grow-1">

                                <small class="text-muted d-block mb-1">
                                    Work Date
                                </small>

                                <h6 class="fw-bold mb-0 text-dark">

                                    {{ $fieldLog->work_date
                            ? $fieldLog->work_date->format('d M Y')
                            : '-' }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Check In --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center">

                            <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-box-arrow-in-right fs-5"></i>
                            </div>

                            <div class="flex-grow-1">

                                <small class="text-muted d-block mb-1">
                                    Check In
                                </small>

                                <h6 class="fw-bold text-success mb-0">

                                    {{ $fieldLog->check_in_time
                            ? $fieldLog->check_in_time->format('h:i A')
                            : '--' }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Check Out --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center">

                            <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-box-arrow-right fs-5"></i>
                            </div>

                            <div class="flex-grow-1">

                                <small class="text-muted d-block mb-1">
                                    Check Out
                                </small>

                                <h6 class="fw-bold text-danger mb-0">

                                    {{ $fieldLog->check_out_time
                            ? $fieldLog->check_out_time->format('h:i A')
                            : '--' }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>



        {{-- ========================================================= --}}
        {{-- Remarks --}}
        {{-- ========================================================= --}}

        <div class="row">

            {{-- Check In Remark --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-bold">
                    Check In Remark
                </label>

                <textarea class="form-control" rows="3" readonly>{{ $fieldLog->check_in_remark ?? '' }}</textarea>

            </div>


            {{-- Check Out Remark --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-bold">
                    Check Out Remark
                </label>

                <textarea class="form-control" rows="3" readonly>{{ $fieldLog->check_out_remark ?? '' }}</textarea>

            </div>

        </div>


        <hr>


        {{-- ========================================================= --}}
        {{-- Task Summary --}}
        {{-- ========================================================= --}}

        <div class="row g-4 mb-4">


            {{-- Total Tasks --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100 overflow-hidden">

                    <div class="card-body position-relative p-4">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-2 fw-semibold">
                                    Total Tasks
                                </p>

                                <h2 class="fw-bold mb-1">
                                    {{ $total }}
                                </h2>

                                <small class="text-muted">
                                    All assigned tasks
                                </small>

                            </div>

                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-list-task fs-4"></i>
                            </div>

                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 4px;"></div>

                    </div>

                </div>

            </div>


            {{-- Completed --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100 overflow-hidden">

                    <div class="card-body position-relative p-4">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-2 fw-semibold">
                                    Completed
                                </p>

                                <h2 class="fw-bold text-success mb-1">
                                    {{ $done }}
                                </h2>

                                <small class="text-muted">
                                    Tasks completed
                                </small>

                            </div>

                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>

                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 4px;"></div>

                    </div>

                </div>

            </div>


            {{-- In Progress --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100 overflow-hidden">

                    <div class="card-body position-relative p-4">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-2 fw-semibold">
                                    In Progress
                                </p>

                                <h2 class="fw-bold text-warning mb-1">
                                    {{ $inProgress }}
                                </h2>

                                <small class="text-muted">
                                    Currently working
                                </small>

                            </div>

                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-hourglass-split fs-4"></i>
                            </div>

                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 bg-warning" style="height: 4px;"></div>

                    </div>

                </div>

            </div>


            {{-- Pending --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100 overflow-hidden">

                    <div class="card-body position-relative p-4">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-2 fw-semibold">
                                    Pending
                                </p>

                                <h2 class="fw-bold text-danger mb-1">
                                    {{ $pending }}
                                </h2>

                                <small class="text-muted">
                                    Awaiting completion
                                </small>

                            </div>

                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>

                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 bg-danger" style="height: 4px;"></div>

                    </div>

                </div>



            </div>

            {{-- ========================================================= --}}
            {{-- Task Progress --}}
            {{-- ========================================================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h6 class="fw-bold mb-1">
                                Task Progress
                            </h6>

                            <small class="text-muted">
                                {{ $done }} of {{ $total }} tasks completed
                            </small>

                        </div>

                        <div class="text-end">

                            <h4 class="fw-bold text-success mb-0">
                                {{ $percent }}%
                            </h4>

                        </div>

                    </div>


                    <div class="progress rounded-pill" style="height: 12px;">

                        <div class="progress-bar bg-success rounded-pill" role="progressbar"
                            style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>

                    </div>


                    <div class="d-flex justify-content-between mt-2">

                        <small class="text-muted">
                            Started
                        </small>

                        <small class="text-muted">
                            {{ $percent == 100 ? 'All tasks completed' : 'Work in progress' }}
                        </small>

                    </div>

                </div>


            </div>



            {{-- ========================================================= --}}
            {{-- Task List --}}
            {{-- ========================================================= --}}

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead>

                        <tr>

                            <th>#</th>

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
                            <td>
                                {{ $key + 1 }}
                            </td>


                            {{-- Task --}}
                            <td>
                                {{ $task->task ?? '-' }}
                            </td>


                            {{-- Status --}}
                            <td>

                                @if($task->status === 'Checked Out')

                                <span class="badge bg-success">
                                    Done
                                </span>

                                @elseif($task->status === 'In Progress')

                                <span class="badge bg-warning text-dark">
                                    In Progress
                                </span>

                                @elseif($task->status === 'Pending')

                                <span class="badge bg-danger">
                                    Pending
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    {{ $task->status ?? 'Unknown' }}
                                </span>

                                @endif

                            </td>


                            {{-- Pending Remark --}}
                            <td>

                                {{ $task->pending_remark ?: '--' }}

                            </td>


                            {{-- Completed At --}}
                            <td>

                                @if($task->completed_at)

                                {{ $task->completed_at->format('d-m-Y h:i A') }}

                                @else

                                --

                                @endif

                            </td>


                            {{-- Time Taken --}}
                            <td>

                                @if($task->completed_at && $fieldLog->check_in_time)

                                {{ $fieldLog->check_in_time->diffForHumans(
                                    $task->completed_at,
                                    [
                                        'parts' => 2,
                                        'short' => true,
                                        'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE
                                    ]
                                ) }}

                                @else

                                --

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="6" class="text-center">
                                No Tasks Found
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


    </div>

    @endsection