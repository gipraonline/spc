@extends('layouts.app')

@section('content')

<div class="card filter-card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="card-title-custom mb-0">Incentives Report</h5>

        </div>
        <!--  FILTER -->
        <form method="GET" action="{{ route('admin.incentives.operation-incentives') }}">
            <div class="row g-3 mb-4">

                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label">Operation Manager</label>
                    <!-- <select name="operation_manager" class="form-select" id="operation_manager">
                        <option value="">Select</option>
                        <option value="all">All Operation Managers</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->n_employee_id }}">
                            {{ $employee->c_employee_name }}
                        </option>
                        @endforeach
                    </select> -->

                    <select name="operation_manager" class="form-select" id="operation_manager">

                        <option value="">Select Operation</option>


                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Gipra Admin']))

                        <option value="all">
                            All Operation Managers
                        </option>

                        @endif

                        @foreach ($employees as $employee)

                        <option value="{{ $employee->n_employee_id }}"
                            {{ request('operation_manager') == $employee->n_employee_id ? 'selected' : '' }}>

                            {{ $employee->c_employee_name }}

                        </option>

                        @endforeach

                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cluster</label>
                    <select name="cluster_manager" id="cluster_manager" class="form-select">
                        <option value="">Select Cluster</option>

                    </select>
                </div>

                <!-- From Date -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-4">
                        <label class="form-label">To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                </div>

            </div>
            <!-- FILTER -->
            <div class="mb-4">
                <button type="submit" class="btn btn-filter">
                    <i class="ti ti-search me-1"></i>Filter Report
                </button>

                <!-- EXCEL EXPORT -->
                @can('operation-incentives.export')
                <a href="{{ route('admin.incentives.operation.incentives.export', request()->query()) }}"
                    class="btn btn-success text-white py-2 px-3">
                    Export to Excel
                </a>
                @endcan
                <button type="button" onclick="window.location='{{ url()->current() }}'"
                    class="btn btn-secondary text-white py-2 px-4">
                    <i class="ti ti-refresh me-1"></i>
                    Reset
                </button>
            </div>

        </form>
        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Sl No.</th>
                        <th>Date</th>
                        <th>Store</th>
                        <th>Store Code</th>
                        <th>Employee</th>
                        <th>Employee Code</th>
                        <th>Designation</th>
                        <th>Incentive</th>

                    </tr>
                </thead>

                <tbody>
                    @forelse($incentives as $key => $row)
                    <tr>
                        <td>{{ ($incentives->currentPage() - 1) * $incentives->perPage() + $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->d_date)->format('d-m-Y') }}</td>
                        <td>{{ $row->c_store_name }}</td>
                        <td>{{ $row->c_store_code }}</td>
                        <td>{{ $row->c_employee_name }}</td>
                        <td>{{ $row->c_employee_code }}</td>
                        <td>{{ $row->c_designation }}</td>
                        <td class="text-end">{{ number_format($row->n_incentive_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No data found</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $incentives->links() }}
        </div>

    </div>
</div>


@push('scripts')
<script>
$(document).ready(function() {

    let selectedCluster = "{{ request('cluster_manager') }}";

    $('#operation_manager').on('change', function() {

        let manager = $(this).val();

        $('#cluster_manager').html(
            '<option value="">Select Cluster</option>'
        );

        if (!manager) return;

        $.ajax({
            url: "{{ route('admin.incentives.clusters', ':id') }}"
                .replace(':id', manager),
            type: 'GET',
            success: function(response) {

                $('#cluster_manager').append(
                    '<option value="all">All Clusters</option>'
                );

                $.each(response, function(i, item) {

                    let selected =
                        selectedCluster == item.n_employee_id ?
                        'selected' :
                        '';

                    $('#cluster_manager').append(
                        `<option value="${item.n_employee_id}" ${selected}>
                            ${item.c_employee_name}
                        </option>`
                    );
                });
            }
        });
    });

    @if(request('operation_manager'))
    $('#operation_manager').trigger('change');
    @endif

});
</script>
@endpush

@endsection