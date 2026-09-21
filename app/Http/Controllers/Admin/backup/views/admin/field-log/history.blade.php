@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log History
        </h5>

        <a href="{{ route('admin.field-log.index') }}" class="btn buttonSpc">
            Today's Log
        </a>

    </div>

    <div class="card-body p-0">

        <table class="table table-bordered table-hover mb-0">

            <thead>

                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Tasks</th>
                    <th>Status</th>
                    <th width="120">Action</th>
                </tr>

            </thead>

            <tbody>

                @forelse($fieldLogs as $log)

                <tr>

                    <td>{{ $log->work_date->format('d-m-Y') }}</td>

                    <td>{{ $log->check_in_time->format('h:i A') }}</td>

                    <td>
                        {{ optional($log->check_out_time)->format('h:i A') ?? '--' }}
                    </td>

                    <td>{{ $log->tasks_count }}</td>

                    <td>

                        @if($log->status=='Checked Out')

                        <span class="badge bg-success">
                            Checked Out
                        </span>

                        @else

                        <span class="badge bg-warning">
                            Working
                        </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('admin.field-log.show',$log->id) }}" class="btn btn-sm buttonSpc">

                            View

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center">

                        No Records Found

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">

        {{ $fieldLogs->links() }}

    </div>

</div>

@endsection