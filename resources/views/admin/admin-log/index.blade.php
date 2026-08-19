@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log History
        </h5>

    </div>

    <div class="card-body">

        {{-- Filters (Next Step) --}}
        <form method="POST" action="{{ route('admin.admin-log.search') }}">
            @csrf

            <div class="row">

                <div class="col-md-3">
                    <input type="date" name="from_date" value="{{ $fromDate ?? '' }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <input type="date" name="to_date" value="{{ $toDate ?? '' }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">

                        <option value="">All Status</option>

                        <option value="Checked In" {{ ($status ?? '') === 'Checked In' ? 'selected' : '' }}>
                            Working
                        </option>

                        <option value="Checked Out" {{ ($status ?? '') === 'Checked Out' ? 'selected' : '' }}>
                            Checked Out
                        </option>

                    </select>
                </div>

                <div class="col-md-3">

                    <button type="submit" class="btn buttonSpc">
                        Search
                    </button>

                    <a href="{{ route('admin.admin-log.clearSearch') }}" class="btn btn-secondary">
                        Clear
                    </a>

                </div>

            </div>
        </form>

        <hr>

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($fieldLogs as $key=>$fieldLog)

                    <tr>

                        <td>{{ $key+1 }}</td>

                        <td>{{ $fieldLog->work_date->format('d-m-Y') }}</td>

                        <td>{{ $fieldLog->admin->c_name ?? ''}}</td>

                        <td>{{ $fieldLog->check_in_time->format('h:i A') }}</td>

                        <td>

                            {{ optional($fieldLog->check_out_time)->format('h:i A') ?? '--' }}

                        </td>

                        <td>

                            @if($fieldLog->status=='Checked Out')

                            <span class="badge bg-success">
                                Checked Out
                            </span>

                            @else

                            <span class="badge bg-warning text-dark">
                                Working
                            </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('admin.admin-log.show',$fieldLog->id) }}" class="btn btn-sm buttonSpc">

                                View

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            No Records Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $fieldLogs->links() }}

        </div>

    </div>

</div>

@endsection