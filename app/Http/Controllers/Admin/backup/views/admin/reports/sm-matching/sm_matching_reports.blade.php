@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                <i class="fa fa-chart-bar text-primary me-2"></i>
                SM Matching Reports
            </h4>

            <small class="text-muted">
                View SM Verified Sales, Rejected Sales and Return Reports
            </small>

        </div>

        @can('sm-matching-reports.export')
        <a href="{{ route('admin.reports.sm.matching.export', request()->query()) }}" class="btn btn-success">

            <i class="fa fa-file-excel me-1"></i>

            Export Excel

        </a>
        @endcan

    </div>

    {{-- Filter Card --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET" action="{{ route('admin.sm.matching.reports') }}">

                <div class="row">

                    {{-- Report --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Report

                        </label>

                        <select name="report_type" class="form-select">

                            <option value="">
                                Select Report
                            </option>

                            <option value="verified_sales"
                                {{ request('report_type')=='verified_sales' ? 'selected':'' }}>

                                SM Verified Sales

                            </option>

                            <option value="rejected_sales"
                                {{ request('report_type')=='rejected_sales' ? 'selected':'' }}>

                                SM Rejected Sales

                            </option>

                            <option value="approved_returns"
                                {{ request('report_type')=='approved_returns' ? 'selected':'' }}>

                                Return Approved

                            </option>

                            <option value="pending_returns"
                                {{ request('report_type')=='pending_returns' ? 'selected':'' }}>

                                Return Not Approved

                            </option>

                        </select>

                    </div>

                    {{-- Operation --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Operation

                        </label>
                        <select name="operation" class="form-select" id="operation">

                            <option value="">Select Operation</option>

                            @if(auth()->user()->hasAnyRole(['Super Admin', 'Gipra Admin']))

                            <option value="all" {{ request('operation') == 'all' ? 'selected' : '' }}>
                                All Operations
                            </option>

                            @endif

                            @foreach($operations as $key => $operation)

                            <option value="{{ $key }}" {{ request('operation') == $key ? 'selected' : '' }}>

                                {{ $operation }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- From Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            From Date

                        </label>

                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">

                    </div>

                    {{-- To Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            To Date

                        </label>

                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">

                    </div>

                </div>

                <div class="mt-2">

                    <button class="btn btn-primary">

                        <i class="fa fa-search me-1"></i>

                        Filter

                    </button>

                    <a href="{{ route('admin.sm.matching.reports') }}" class="btn btn-secondary">

                        <i class="fa fa-refresh me-1"></i>

                        Reset

                    </a>

                </div>

            </form>

        </div>

    </div>

    {{-- Report Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fa fa-table me-2"></i>

                Report Details

            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th width="170">
                                Bill Details
                            </th>

                            <th width="220">
                                Employee
                            </th>

                            <th width="220">
                                Store
                            </th>

                            <th>
                                Product
                            </th>

                            <th class="text-center">
                                Qty
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th width="170" class="text-center">

                                @if(request('report_type')=='approved_returns' ||
                                request('report_type')=='pending_returns')

                                Return Status

                                @else

                                Verification Status

                                @endif

                            </th>

                        </tr>

                    </thead>

                    <tbody>


                    <tbody>


                        @forelse($reports as $report)

                        <tr>

                            <td class="text-center">

                                {{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}

                            </td>

                            {{-- Bill Details --}}
                            <td>

                                <strong>

                                    {{ $report->c_bill_no }}

                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ \Carbon\Carbon::parse($report->d_bill_date)->format('d-m-Y') }}

                                </small>

                            </td>

                            {{-- Employee --}}
                            <td>

                                <strong>

                                    {{ $report->c_employee_code }}

                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ $report->c_employee_name }}

                                </small>

                            </td>

                            {{-- Store --}}
                            <td>

                                <strong>

                                    {{ $report->c_store_code }}

                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ $report->c_store_name }}

                                </small>

                            </td>

                            {{-- Product --}}
                            <td>

                                {{ $report->c_product_name }}

                            </td>

                            {{-- Qty --}}
                            <td class="text-center">

                                {{ $report->n_quantity }}

                            </td>

                            {{-- Amount --}}
                            <td class="text-end text-nowrap">
                                ₹{{ number_format($report->n_sold_price, 2) }}
                            </td>

                            {{-- Status --}}
                            <td class="text-center">

                                @switch(request('report_type'))

                                @case('verified_sales')

                                <span class="badge bg-success">

                                    SM Verified

                                </span>

                                @break

                                @case('rejected_sales')

                                <span class="badge bg-danger">

                                    SM Rejected

                                </span>

                                @break

                                @case('approved_returns')

                                <span class="badge bg-primary">

                                    Return Approved

                                </span>

                                @break

                                @case('pending_returns')

                                <span class="badge bg-warning text-dark">

                                    Return Requested

                                </span>

                                @break

                                @default

                                @if($report->status=='Verified')

                                <span class="badge bg-success">

                                    SM Verified

                                </span>

                                @elseif($report->status=='Rejected')

                                <span class="badge bg-danger">

                                    SM Rejected

                                </span>

                                @elseif($report->return_status=='Approved')

                                <span class="badge bg-primary">

                                    Return Approved

                                </span>

                                @else

                                <span class="badge bg-secondary">

                                    -

                                </span>

                                @endif

                                @endswitch

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8" class="text-center py-5">

                                <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>

                                <h6 class="text-muted">

                                    No Records Found

                                </h6>

                                <small class="text-muted">

                                    Try selecting a report and applying filters.

                                </small>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
            <!-- Pagination -->
            <div class="mt-3">
                {{ $reports->links() }}
            </div>
        </div>

    </div>

</div>

@push('styles')

<style>
.table thead th {

    background: #f8f9fa;
    color: #495057;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;

}

.table td {

    vertical-align: middle;

}

.table td strong {

    font-size: 14px;

}

.table td small {

    font-size: 12px;
    color: #6c757d;

}

.badge {

    font-size: 12px;
    padding: 7px 12px;
    border-radius: 20px;

}

.card {

    border-radius: 12px;

}

.card-header {

    background: #fff;
    border-bottom: 1px solid #e9ecef;

}

.form-label {

    font-weight: 600;

}

.btn {

    border-radius: 8px;

}
</style>

@endpush

@endsection