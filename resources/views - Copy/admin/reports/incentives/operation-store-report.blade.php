@extends('layouts.app')

@section('content')

<style>
.page-wrap {
    max-width: 1400px;
    margin: auto;
}

:root {
    --primary-green: #1b3e86;
    --accent-orange: #1b3e86;
    --card-shadow: 0 10px 25px rgba(0, 0, 0, .04);
    --border-radius: 12px;
}

.filter-card {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border-top: 4px solid var(--primary-green);
    margin-bottom: 2rem;
}

.card-title-custom {
    font-weight: 700;
    color: #2c3e50;
    letter-spacing: -.5px;
}

.form-label {
    font-weight: 600;
    font-size: .85rem;
    color: #64748b;
    margin-bottom: .5rem;
}

.form-control,
.form-select {
    border-radius: 8px;
    padding: .7rem 1rem;
    border: 1.5px solid #eef2f6;
    background: #fdfdfe;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    box-shadow: 0 0 0 4px rgba(27, 62, 134, .08);
}

.btn-filter {
    background: #1b3e86;
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 16px 40px;
    font-size: 1.15rem;
    font-weight: 700;
    min-width: 230px;
    transition: .3s;
}

.btn-filter:hover {
    background: #16336e;
    color: #fff;
}

.btn-export {
    background: #fff;
    color: #1b3e86;
    border: 2px solid #1b3e86;
    border-radius: 12px;
    padding: 14px 32px;
    font-size: 1.05rem;
    font-weight: 700;
    transition: .3s;
}

.btn-export:hover {
    background: #1b3e86;
    color: #fff;
}

.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
    transition: .3s;
}

.stat-card:hover {
    transform: translateY(-4px);
}

.stat-card::after {
    content: '';
    position: absolute;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .15);
    right: -30px;
    top: -30px;
}

.stat-card .card-body {
    position: relative;
    z-index: 2;
}

.stat-label {
    font-size: .82rem;
    text-transform: uppercase;
    opacity: .9;
    letter-spacing: .5px;
}

.stat-value {
    font-size: 1.9rem;
    font-weight: 800;
}

.bg-indigo {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
}

.bg-emerald {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
}

.bg-sky {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #fff;
}

.bg-amber {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
}

.report-card {
    background: #fff;
    border-radius: 12px;
    border-top: 4px solid var(--primary-green);
    box-shadow: var(--card-shadow);
}

.store-code {
    background: #eef2ff;
    color: #4f46e5;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
}

.amount-sale {
    color: #0284c7;
    font-weight: 700;
}

.amount-incentive {
    color: #059669;
    font-weight: 700;
}
</style>

<div class="page-wrap">

    <div class="card filter-card">

        <div class="card-body p-4">

            <h4 class="card-title-custom mb-4">

                Store Incentive Report

            </h4>

            <form method="GET" action="{{ route('admin.incentives.operation-store-report') }}">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">

                            Operation Manager

                        </label>

                        <select name="operation_manager" class="form-select">

                            <option value="">Select Operation</option>


                            @if(auth()->user()->hasAnyRole(['Super Admin', 'Gipra Admin']))
                            <option value="all">All Operation Managers</option>
                            @endif

                            @foreach($employees as $employee)

                            <option value="{{ $employee->n_employee_id }}"
                                {{ request('operation_manager')==$employee->n_employee_id ? 'selected':'' }}>

                                {{ $employee->c_employee_name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">

                            Date From

                        </label>

                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">

                            Date To

                        </label>

                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">

                    </div>
                    <div class="mt-4 pt-3 border-top">

                        <div class="d-flex justify-content-between align-items-center">

                            <button type="submit" class="btn btn-filter">
                                <i class="fa fa-search me-2"></i>
                                Filter Report
                            </button>

                            @can('store-incentive-report.export')

                            <a href="{{ route('admin.incentives.operation-store-report.export', request()->query()) }}"
                                class="btn btn-export">
                                <i class="fa fa-file-excel-o me-2"></i>
                                Export to Excel
                            </a>

                            @endcan


                        </div>

                    </div>

            </form>

        </div>

    </div>

    {{-- Summary Cards --}}

    <div class="row mb-4">

        <div class="col-lg-4 col-md-4 mb-3">
            <div class="card stat-card bg-indigo">
                <div class="card-body">
                    <div class="stat-label">TOTAL STORES</div>
                    <div class="stat-value">{{ number_format($totalStores) }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 mb-3">
            <div class="card stat-card bg-emerald">
                <div class="card-body">
                    <div class="stat-label">TOTAL SALES</div>
                    <div class="stat-value">
                        ₹ {{ number_format($totalSales,2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 mb-3">
            <div class="card stat-card bg-sky">
                <div class="card-body">
                    <div class="stat-label">TOTAL INCENTIVE</div>
                    <div class="stat-value">
                        ₹ {{ number_format($totalIncentive,2) }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== REPORT TABLE ===================== --}}

    <div class="card report-card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="card-title-custom mb-0">
                    Store Incentive Details
                </h5>

                <span class="text-muted">

                    @if(request('start_date') && request('end_date'))

                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d-M-Y') }}
                    -
                    {{ \Carbon\Carbon::parse(request('end_date'))->format('d-M-Y') }}

                    @else

                    All Dates

                    @endif

                </span>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="70">S.No</th>

                            <th>Date</th>

                            <th>Store Code</th>

                            <th>Store Name</th>

                            <th class="text-end">Sales Amount</th>

                            <th class="text-end">Incentive Amount</th>

                        </tr>

                    </thead>

                    <tbody>

                        @php

                        $sl = ($report->currentPage()-1) * $report->perPage() + 1;

                        @endphp

                        @forelse($report as $row)

                        <tr>

                            <td>

                                {{ $sl++ }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($row->d_date)->format('d-M-Y') }}

                            </td>

                            <td>

                                <span class="store-code">

                                    {{ $row->c_store_code }}

                                </span>

                            </td>

                            <td>

                                {{ $row->c_store_name }}

                            </td>

                            <td class="text-end amount-sale">

                                ₹ {{ number_format($row->sale_amount,2) }}

                            </td>

                            <td class="text-end amount-incentive">

                                ₹ {{ number_format($row->incentive_amount,2) }}

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="6" class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fa fa-database fa-3x mb-3"></i>

                                    <br>

                                    No records found.

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                    @if($report->count())

                    <tfoot>

                        <tr>

                            <th colspan="4" class="text-end">

                                Grand Total

                            </th>

                            <th class="text-end text-primary">

                                ₹ {{ number_format($totalSales,2) }}

                            </th>

                            <th class="text-end text-success">

                                ₹ {{ number_format($totalIncentive,2) }}

                            </th>

                        </tr>

                    </tfoot>

                    @endif

                </table>

            </div>

            @if($report->hasPages())

            <div class="mt-4">

                {{ $report->links() }}

            </div>

            @endif

        </div>

    </div>

</div>

@endsection
