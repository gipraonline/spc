@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #F7941E;
    --bg-light: #f8fafc;
    --border-color: #e2e8f0;
    --deep-blue: #1c2e4d;
    --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
}

/* Card Styling */
.details-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}

.card-header-styled {
    padding: 1.25rem 2rem;
    border-bottom: 2px solid var(--bg-light);
    border-top: 4px solid var(--primary-green);
}

.info-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
    display: block;
}

.info-value {
    font-weight: 700;
    color: var(--deep-blue);
    margin-bottom: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Financial Result Blocks */
.metric-section {
    background: #fdfdfe;
    border-top: 1px solid var(--border-color);
    padding-top: 2rem;
}

.metric-box {
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid #f1f5f9;
    height: 100%;
}

/* Sidebar Badge */
.status-card {
    background: linear-gradient(135deg, #1c2e4d 0%, #2c3e50 100%);
    color: #fff;
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
}

/* --- REDESIGNED BREAKDOWN SECTION --- */
.breakdown-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    border-top: 5px solid var(--primary-green);
}

.breakdown-header {
    background: #fff;
    padding: 2rem;
    border-bottom: 1px solid #f1f5f9;
}

.pool-group {
    padding: 2.5rem 2rem;
    background: #fff;
    transition: all 0.3s ease;
}

.pool-group:hover {
    background: #fafbfc;
}

.pool-header-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 15px;
}

.pool-title-pill {
    background: #f1f5f9;
    padding: 8px 20px;
    border-radius: 50px;
    color: var(--deep-blue);
    font-weight: 800;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #e2e8f0;
}

.pool-title-pill i {
    color: var(--accent-orange);
}

.pool-summary-card {
    background: linear-gradient(45deg, #1f376d, #344e88);
    border-left: 4px solid var(--accent-orange);
    padding: 18px 25px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
    min-width: 260px;
    transition: transform 0.2s ease;
}

.pool-summary-card:hover {
    transform: translateY(-3px);
}

.pool-summary-icon-box {
    background: rgba(247, 148, 30, 0.12);
    /* Light accent orange */
    color: var(--accent-orange);
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    box-shadow: inset 0 0 0 1px rgba(247, 148, 30, 0.1);
}

.pool-summary-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 1.2px;
    margin-bottom: 2px;
}

.pool-summary-value {
    font-size: 1.5rem;
    font-weight: 900;
    color: #fff;
    margin: 0;
    letter-spacing: -0.5px;
}

/* Table Styling */
.table-container-modern {
    border-radius: 0px;
    border: 1px solid #f1f5f9;
    background: #fff;
    overflow: hidden;
}

.pool-group.border-bottom {
    border-bottom: 1px solid #ef972e47 !important;
}

.table-custom-modern {
    margin-bottom: 0;
}

.table-custom-modern thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
}

.table-custom-modern tbody td {
    padding: 16px 20px;
    font-weight: 600;
    color: #334155;
    border-bottom: 1px solid #ccc;
}

.table-custom-modern tr:last-child td {
    border-bottom: 1px solid #ccc;
}

.emp-code-badge {
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    color: #475569;
}

.designation-badge {
    background: #e0e7ff;
    color: #4338ca;
    font-size: 0.7rem;
    padding: 5px 12px;
    border-radius: 30px;
    font-weight: 700;
}

.incentive-amount-text {
    font-size: 1rem;
    font-weight: 800;
    color: var(--primary-green);
}

.btn-back {
    border-radius: 8px;
    padding: 6px 15px;
    font-weight: 600;
}


@media screen and (max-width:767px) {
    .table-container-modern {
        overflow-x: scroll;
    }
}
</style>

<div class="row">
    <div class="col-lg-8">
        <div class="card details-card mb-4">
            <div class="card-header-styled d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0" style="color: var(--deep-blue);">Sale Details</h5>
                <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary btn-sm btn-back">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <span class="info-label">Calculation Date</span>
                        <h6 class="info-value">
                            <i class="ti ti-calendar text-info"></i>
                            {{ isset($employeeIncentiveDate) ? $employeeIncentiveDate : 'N/A' }}
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <span class="info-label">Bill No</span>
                        <h6 class="info-value">
                            <i class="ti ti-user text-primary"></i>
                            {{ $sale->c_bill_no ?? 'N/A' }}
                            <span class="text-muted small">({{ $sale->c_bill_no ?? '' }})</span>
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Employee</span>
                        <h6 class="info-value">
                            <i class="ti ti-user text-primary"></i>
                            {{ $sale->employee?->c_employee_name ?? 'N/A' }}
                            <span class="text-muted small">({{ $sale->employee?->c_employee_code ?? '' }})</span>
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Store</span>
                        <h6 class="info-value">
                            <i class="ti ti-building-store text-primary"></i>
                            {{ $sale->store?->c_store_name ?? 'N/A' }}
                            <span class="text-muted small">({{ $sale->store?->c_store_code ?? '' }})</span>
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Sale Date</span>
                        <h6 class="info-value">
                            <i class="ti ti-calendar text-info"></i>
                            {{ \Carbon\Carbon::parse($sale->d_date)->format('d M Y') }}
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Item / Product</span>
                        <h6 class="info-value">
                            <i class="ti ti-package text-warning"></i>
                            {{ $sale->product?->c_product_name ?? 'N/A' }}
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Quantity</span>
                        <h6 class="info-value">
                            <i class="ti ti-hash text-danger"></i>
                            {{ $sale->n_quantity }}
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Unit Price</span>
                        <h6 class="info-value">₹{{ number_format($sale->product?->n_selling_price ?? 0, 2) }}</h6>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Unit Purchase Rate</span>
                        <h6 class="info-value">₹{{ number_format($sale->product?->n_purchase_price ?? 0, 2) }}</h6>
                    </div>
                </div>

                <div class="metric-section">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="metric-box border-start border-4 border-primary">
                                <span class="info-label">Total Sales</span>
                                <h5 class="fw-extrabold text-primary mb-0">
                                    ₹{{ number_format($sale->total_sales_amount, 2) }}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="metric-box border-start border-4 border-danger">
                                <span class="info-label">Total Purchase</span>
                                <h5 class="fw-extrabold text-danger mb-0">
                                    ₹{{ number_format($sale->total_purchase_amount, 2) }}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="metric-box border-start border-4 border-success">
                                <span class="info-label">Total Margin</span>
                                <h5 class="fw-extrabold text-success mb-0">
                                    ₹{{ number_format($sale->total_margin_amount, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card status-card mb-4 overflow-hidden">
            <div class="card-body p-4 position-relative">
                <i class="ti ti-check-double position-absolute top-0 end-0 p-3 opacity-25 fs-9"></i>
                <h4 class="fw-bold mb-1 text-white">Incentive Status</h4>
                <p class="mb-3 text-white-50 small">Per Sale Distribution</p>
                <div class="mt-4 pt-3 border-top border-white-10">
                    <span class="badge bg-primary px-3 py-2 fw-bold" style="border-radius: 30px;">
                        <i class="ti ti-star-filled me-1"></i> Calculation Ready
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CREATIVE REDESIGNED BREAKDOWN CARD -->
<div class="card breakdown-card">
    <div class="breakdown-header">
        <div class="col-md-12 d-flex align-items-center">
            <div class="col-md-4  align-items-center d-flex gap-2">
                <div style="width: 12px; height: 12px; background: var(--accent-orange); border-radius: 50%;"></div>
                <h5 class="fw-bold mb-0" style="color: var(--deep-blue); letter-spacing: -0.5px;">Incentive Distribution
                    Breakdown</h5>
            </div>
            <?php

            ?>
        </div>


    </div>
    <div class="card-body p-0">
        @php
        $groupedIncentives = $sale->incentives()->with('employee.designation')->get()->groupBy('c_pool_name');
        @endphp

        @forelse($groupedIncentives as $poolName => $poolIncentives)
        <div class="pool-group {{ !$loop->last ? 'border-bottom' : '' }}">
            <div class="pool-header-flex">
                <div class="pool-title-pill text-uppercase">
                    <i class="ti ti-grid-dots fs-5"></i>
                    {{ str_replace('_', ' ', $poolName ?: 'OTHER') }}
                    @if($poolIncentives->first()->n_pool_percentage)
                    <span class="ms-1"
                        style="color: #64748b; font-weight: 500;">({{ number_format($poolIncentives->first()->n_pool_percentage, 2) }}%)</span>
                    @endif
                </div>



                <div class="pool-summary-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="pool-summary-label">Pool Total Amount</span>
                            <h5 class="pool-summary-value">
                                ₹{{ number_format($poolIncentives->sum('n_incentive_amount'), 2) }}</h5>
                        </div>
                        <div class="pool-summary-icon-box">
                            <i class="ti ti-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container-modern table-responsive">
                <table class="table table-custom-modern align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th class="text-end">Incentive Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($poolIncentives as $incentive)
                        <tr>
                            <td>
                                <span
                                    class="emp-code-badge">{{ $incentive->employee?->c_employee_code ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold" style="color: var(--deep-blue);">
                                    {{ $incentive->employee?->c_employee_name ?? 'Unknown' }}</div>
                            </td>
                            <td>
                                <span
                                    class="designation-badge">{{ $incentive->employee?->designation->c_designation ?? 'N/A' }}</span>
                            </td>

                            <td class="text-end">
                                <span
                                    class="incentive-amount-text">₹{{ number_format($incentive->n_incentive_amount, 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="ti ti-info-circle text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
            <h6 class="text-muted fw-bold">No incentives calculated for this sale yet.</h6>
        </div>
        @endforelse
    </div>
</div>
@endsection