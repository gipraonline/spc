@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-green: #1b3e86;
        --accent-orange: #1b3e86;
        --deep-blue: #1b3e86;
        --bg-light: #f8f9fa;
        --border-radius: 12px;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    /* Page Header */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .page-title {
        font-weight: 800;
        color: var(--deep-blue);
        letter-spacing: -0.5px;
        margin-bottom: 0;
    }

    .btn-back {
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* Metric Cards */
    .metric-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        position: relative;
        color: #fff;
    }

    .bg-gradient-indigo { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-emerald { background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%); }
    .bg-gradient-sky { background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); }

    .metric-card .card-body {
        padding: 1.5rem;
        text-align: center;
    }

    .metric-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
        opacity: 0.9;
        margin-bottom: 10px;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
    }

    /* Main Breakdown Container */
    .breakdown-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .breakdown-header {
        background: #fff;
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #f8f9fa;
        border-top: 4px solid var(--primary-green);
    }

    .pool-group {
        padding: 2rem;
        background: #fff;
    }

    .pool-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--deep-blue);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
    }

    .pool-title::before {
        content: '';
        width: 4px;
        height: 18px;
        background: var(--accent-orange);
        border-radius: 4px;
    }

    .pool-summary-box {
        background: #fcfcfc;
        border: 1px dashed #e2e8f0;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: inline-block;
    }

    /* Table Styling */
    .table-custom {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        margin-bottom: 0;
    }

    .table-custom thead {
        background: #f8fafc;
    }

    .table-custom th {
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        padding: 12px 20px;
        border-bottom: 2px solid #f1f5f9;
    }

    .table-custom td {
        padding: 14px 20px;
        vertical-align: middle;
        font-weight: 500;
        color: #334155;
    }

    .currency-symbol {
        font-family: inherit;
        font-weight: 600;
        color: #94a3b8;
        margin-right: 2px;
    }

    /* Elegant Alerts */
    .premium-alert {
        background: #fff8eb;
        border: 1px solid #ffe8cc;
        border-left: 5px solid #ffa94d;
        border-radius: 10px;
        padding: 1.25rem;
    }

    .premium-alert-heading {
        color: #d9480f;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .premium-alert-value {
        color: #2b2d42;
        font-size: 1.1rem;
        margin-top: 5px;
    }

    .btn-calculate {
            background: var(--primary-green);
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            color: #fff;
            font-weight: 700;
        }

</style>

<div class="page-header">
    <h4 class="page-title">Incentive Calculation Results</h4>
    <a href="{{ route('admin.incentives.index') }}" class="btn btn-outline-secondary btn-back">
        <i class="ti ti-arrow-left me-1"></i> Back
    </a>
</div>

@if (isset($result['status']) && $result['status'] === 'success')
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card metric-card bg-gradient-indigo">
                <div class="card-body">
                    <div class="metric-label">Total Sales Amount</div>
                    <h4 class="metric-value">₹{{ number_format((float)($result['total_sales'] ?? 0), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card bg-gradient-emerald">
                <div class="card-body">
                    <div class="metric-label">Total Incentive Pool</div>
                    <h4 class="metric-value">₹{{ number_format((float)($result['incentive_pool'] ?? 0), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card bg-gradient-sky">
                <div class="card-body">
                    <div class="metric-label">Sales Count</div>
                    <h4 class="metric-value">{{ $result['sale_count'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card breakdown-card">
        <div style="justify-content: space-between;flex-wrap:wrap;    align-items: center;" class="breakdown-header d-flex align-center">
            <h5 class="fw-bold mb-0" style="color: var(--deep-blue);">Incentive Distribution Breakdown</h5>
         <div  class=" d-flex gap-2 ">
                <a href="{{ route('admin.incentives.export', [
                    'store_id' => request('store_id'),
                    'date_from' => request('date_from'),
                    'date_to' => request('date_to'),
                ]) }}" 
                class="btn btn-calculate bg-success text-white">
                    <i class="ti ti-file-export me-1"></i> Export to Excel
                </a>
        </div>
        </div>

       <!-- <a href="{{ route('admin.incentives.export', [
            'store_id' => request('store_id'),
            'date_from' => request('date_from'),
            'date_to' => request('date_to'),
            ]) }}" 
             class="btn btn-calculate bg-success text-white">
            <i class="ti ti-file-export me-1"></i> Export to Excel
        </a> -->
       
        
    

        <div class="card-body p-0">
            @foreach ($result['distribution'] as $poolName => $distribution)
                <div class="pool-group {{ !$loop->last ? 'border-bottom' : '' }}">
                    <h6 class="pool-title text-uppercase">
                        {{ str_replace('_', ' ', $poolName) }} 
                        @if(isset($distribution['percentage']))
                            <small class="ms-2 text-muted">({{ number_format($distribution['percentage'], 2) }}%)</small>
                        @endif
                    </h6>
                    
                    @if (isset($distribution['total_amount']))
                        <div class="pool-summary-box">
                            <span class="fs-2 text-muted fw-bold text-uppercase d-block mb-1">Pool Total Amount</span>
                            <h5 class="fw-bold mb-0 text-dark">₹{{ number_format($distribution['total_amount'], 2) }}</h5>
                        </div>

                        <!-- @if (isset($distribution['employees']) && count($distribution['employees']) > 0)
                            <div class="table-responsive">
                                <table class="table table-custom align-middle">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            @if (isset($distribution['employees'][0]['designation']))
                                                <th>Designation</th>
                                            @endif
                                            <th class="text-end">Incentive Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distribution['employees'] as $emp)
                                            <tr>
                                                <td class="fw-bold text-muted">{{ $emp['code'] }}</td>
                                                <td class="fw-bold text-dark">{{ $emp['name'] }}</td>
                                                @if (isset($emp['designation']))
                                                    <td>
                                                        <span class="badge bg-light text-dark fw-bold">{{ $emp['designation'] }}</span>
                                                    </td>
                                                @endif
                                                <td class="text-end fw-bold text-primary">₹{{ number_format($emp['incentive'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif -->

@if (!empty($distribution['employees_grouped']))

    @foreach ($distribution['employees_grouped'] as $designation => $employees)

        @if ($employees->count() > 0)

            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th class="text-end">Incentive Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $emp)
                            <tr>
                                <td>{{ $emp['code'] }}</td>
                                <td>{{ $emp['name'] }}</td>
                                <td>{{ $emp['designation'] ?? '-' }}</td>
                                <td class="text-end">
                                    ₹{{ number_format($emp['incentive'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-2 d-flex justify-content-center">
                      {{ $employees->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>

            </div>

        @else
            <p class="text-muted">No employees found</p>
        @endif

    @endforeach

@endif

                    @else
                        <div class="premium-alert">
                            <h6 class="premium-alert-heading mb-1">{{ $distribution['note'] ?? '' }}</h6>
                            <p class="premium-alert-value fw-bold mb-0">₹{{ number_format($distribution['amount'], 2) }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="alert alert-warning shadow-sm border-0 d-flex align-items-center" role="alert" style="border-radius: 12px; padding: 1.5rem;">
        <i class="ti ti-alert-triangle me-3 fs-6"></i>
        <div class="fw-bold">{{ $result['message'] ?? 'Unable to calculate incentives' }}</div>
    </div>
@endif
@endsection
