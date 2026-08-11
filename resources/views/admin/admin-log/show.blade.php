@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log Details
        </h5>

        <a href="{{ route('admin.admin-log.index') }}" class="btn btn-secondary">
            Back
        </a>

    </div>

    <div class="card-body">

        {{-- Employee Details --}}
        <div class="row">

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Employee</label>
                <p>{{ $fieldLog->admin->c_name ?? '-' }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Date</label>
                <p>{{ $fieldLog->work_date->format('d-m-Y') }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Check In</label>
                <p>{{ $fieldLog->check_in_time->format('h:i A') }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Check Out</label>
                <p>
                    {{ $fieldLog->check_out_time ? $fieldLog->check_out_time->format('h:i A') : '--' }}
                </p>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <label class="form-label fw-bold">
                    Check In Remark
                </label>

                <textarea class="form-control" rows="3" readonly>{{ $fieldLog->check_in_remark }}</textarea>

            </div>

            <div class="col-md-6">

                <label class="form-label fw-bold">
                    Check Out Remark
                </label>

                <textarea class="form-control" rows="3" readonly>{{ $fieldLog->check_out_remark }}</textarea>

            </div>

        </div>

        <hr>

        <!-- @php
        $total = $fieldLog->tasks->count();
        $done = $fieldLog->tasks->where('status','Done')->count();
        $pending = $total - $done;
        $percent = $total > 0 ? round(($done/$total)*100) : 0;
        @endphp -->

        @php
        $total = $fieldLog->tasks->count();

        $done = $fieldLog->tasks
        ->where('status', 'Done')
        ->count();

        $inProgress = $fieldLog->tasks
        ->where('status', 'In Progress')
        ->count();

        $pending = $fieldLog->tasks
        ->where('status', 'Pending')
        ->count();

        $percent = $total > 0
        ? round(($done / $total) * 100)
        : 0;
        @endphp



        {{-- Summary --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4>{{ $total }}</h4>

                        <small>Total Tasks</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4 class="text-success">{{ $done }}</h4>

                        <small>Completed</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4 class="text-warning">{{ $pending }}</h4>

                        <small>Pending</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4>{{ $percent }}%</h4>

                        <small>Progress</small>

                    </div>

                </div>

            </div>

        </div>

        <div class="progress mb-4" style="height:20px;">

            <div class="progress-bar bg-success" style="width:{{ $percent }}%">

                {{ $percent }}%

            </div>

        </div>

        {{-- Task List --}}
        <div class="table-responsive">

            <table class="table table-bordered table-striped">

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

                    @forelse($fieldLog->tasks as $key=>$task)

                    <tr>

                        <td>{{ $key+1 }}</td>

                        <td>{{ $task->task }}</td>

                        <td>

                            @if($task->status=='Checked Out')

                            <span class="badge bg-success">
                                Done
                            </span>

                            @else

                            <span class="badge bg-warning text-dark">
                                In Progress
                            </span>

                            @endif

                        </td>

                        <td>

                            {{ $task->pending_remark ?: '--' }}

                        </td>

                        <td>

                            {{ $task->completed_at ? $task->completed_at->format('d-m-Y h:i A') : '--' }}

                        </td>

                        <td>

                            @if($task->completed_at)

                            {{ $fieldLog->check_in_time->diffForHumans($task->completed_at, [
                                    'parts' => 2,
                                    'short' => true,
                                    'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE
                                ]) }}

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