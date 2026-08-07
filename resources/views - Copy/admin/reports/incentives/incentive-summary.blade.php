@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fa fa-chart-bar text-primary me-2"></i>
                Employee Incentive Summary Report
            </h4>
            <small class="text-muted">
                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </small>
        </div>
        @can('incentive-summary.export')
        <a href="{{ route('admin.incentives.incentive-summary.export', request()->query()) }}" class="btn btn-success">
            <i class="fa fa-file-excel me-1"></i>
            Export Excel
        </a>
        @endcan
    </div>

    {{-- KPI Cards --}}
    <div class="row mb-4">

        {{-- Designations --}}
        <div class="col-md-4 mb-3">
            <div class="card kpi-card kpi-designation shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="kpi-label">DESIGNATIONS</small>
                        <h2 class="kpi-value">
                            {{ count($summary['groups']) }}
                        </h2>
                    </div>

                    <div class="kpi-icon">
                        <i data-lucide="network"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employees --}}
        <div class="col-md-4 mb-3">
            <div class="card kpi-card kpi-employees shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="kpi-label">EMPLOYEES</small>
                        <h2 class="kpi-value">
                            {{ collect($summary['groups'])->sum('employee_count') }}
                        </h2>
                    </div>

                    <div class="kpi-icon">
                        <i data-lucide="users"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Incentive --}}
        <div class="col-md-4 mb-3">
            <div class="card kpi-card kpi-incentive shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="kpi-label">TOTAL INCENTIVE</small>
                        <h2 class="kpi-value">
                            ₹{{ number_format($summary['grand_total'],2) }}
                        </h2>
                    </div>

                    <div class="kpi-icon">
                        <i data-lucide="indian-rupee"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Filter Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Operation Manager</label>

                        <select name="operation_manager" class="form-select" id="operation_manager">

                            <option value="">Select Operation</option>


                            @if(auth()->user()->hasAnyRole(['Super Admin', 'Gipra Admin']))

                            <option value="all">
                                All Operation Managers
                            </option>

                            @endif

                            @foreach($employees as $employee)
                            <option value="{{ $employee->n_employee_id }}"
                                {{ request('operation_manager') == $employee->n_employee_id ? 'selected' : '' }}>
                                {{ $employee->c_employee_name }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cluster Manager</label>

                        <select name="cluster_manager" id="cluster_manager" class="form-select">
                            <option value="">Select Cluster</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">From Date</label>

                        <input type="date" class="form-control" name="start_date"
                            value="{{ request('start_date',$startDate) }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">To Date</label>

                        <input type="date" class="form-control" name="end_date"
                            value="{{ request('end_date',$endDate) }}">
                    </div>

                </div>

                <div class="mt-4">

                    <button class="btn btn-primary">
                        <i class="fa fa-search me-1"></i>
                        Filter
                    </button>

                    <button type="button" onclick="window.location='{{ url()->current() }}'" class="btn btn-secondary">
                        Reset
                    </button>

                </div>

            </form>

        </div>
    </div>

    {{-- Summary Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fa fa-layer-group text-primary me-2"></i>
                Incentive Summary
            </h5>
        </div>

        <div class="card-body p-0">

            @if(empty($summary['groups']) || count($summary['groups'])==0)

            <div class="p-5 text-center text-muted">
                No records found.
            </div>

            @else

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0">

                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Store</th>
                            <th class="text-end">Records</th>
                            <th class="text-end">Days</th>
                            <th class="text-end">Total Incentive</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($summary['groups'] as $group)

                        <tr class="designation-row">
                            <td colspan="7">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <i class="fa fa-users me-2"></i>
                                        {{ $group['designation'] }}
                                    </div>

                                    <div>

                                        <span class="badge bg-primary me-2">
                                            {{ $group['employee_count'] }}
                                            Employees
                                        </span>

                                        <span class="badge bg-success">
                                            ₹{{ number_format($group['total_incentive'],2) }}
                                        </span>

                                    </div>

                                </div>

                            </td>
                        </tr>

                        @foreach($group['employees'] as $employee)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $employee['employee_code'] }}
                            </td>

                            <td>
                                {{ $employee['employee_name'] }}
                            </td>

                            <td>
                                {{ $employee['store_code'] }}
                                -
                                {{ $employee['store_name'] }}
                            </td>

                            <td class="text-end">
                                {{ $employee['record_count'] }}
                            </td>

                            <td class="text-end">
                                {{ $employee['days_count'] }}
                            </td>

                            <td class="text-end fw-bold text-success">
                                ₹{{ number_format($employee['total_incentive'],2) }}
                            </td>

                        </tr>

                        @endforeach

                        <tr class="subtotal-row">

                            <td colspan="4" class="text-end fw-bold">
                                {{ $group['designation'] }}
                                Subtotal
                            </td>

                            <td class="text-end fw-bold">
                                {{ $group['total_records'] }}
                            </td>

                            <td></td>

                            <td class="text-end fw-bold">
                                ₹{{ number_format($group['total_incentive'],2) }}
                            </td>

                        </tr>

                        @endforeach

                        <tr class="grandtotal-row">

                            <td colspan="6" class="text-end">
                                GRAND TOTAL
                            </td>

                            <td class="text-end">
                                ₹{{ number_format($summary['grand_total'],2) }}
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            @endif

        </div>

    </div>

</div>

@push('styles')
<style>
.designation-row td {
    background: #eef2fb !important;
    font-weight: 700;
    color: #2c3344;
    border-top: 2px solid #d6dded;
}

.subtotal-row td {
    background: #fff8e6 !important;
    color: #8a6d3b;
    font-weight: 600;
}

.grandtotal-row td {
    background: linear-gradient(90deg, #1e3c72, #2a5298);
    color: #fff;
    font-weight: 700;
    font-size: 15px;
}

.table thead th {
    background: #f8fafc;
    color: #495057;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: .5px;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.card {
    border-radius: 12px;
}

.badge {
    font-size: 12px;
}

.kpi-card {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    transition: all .3s ease;
}

.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, .12) !important;
}

.kpi-card .card-body {
    min-height: 110px;
    padding: 1.5rem;
}

.kpi-designation {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.kpi-employees {
    background: linear-gradient(135deg, #36d1dc, #5b86e5);
}

.kpi-incentive {
    background: linear-gradient(135deg, #11998e, #38ef7d);
}

.kpi-label {
    color: rgba(255, 255, 255, .8);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
}

.kpi-value {
    color: #fff;
    font-size: 2rem;
    font-weight: 700;
    margin-top: 8px;
    margin-bottom: 0;
}

.kpi-icon {
    width: 55px;
    height: 55px;
    border-radius: 15px;
    background: rgba(255, 255, 255, .20);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #fff;
}
</style>
@endpush

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