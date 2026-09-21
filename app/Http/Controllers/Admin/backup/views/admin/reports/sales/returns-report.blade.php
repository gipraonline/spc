@extends('layouts.app')

@section('content')

<style>
:root {
    --primary-blue: #1b3e86;
    --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
}

.filter-card {
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border-top: 4px solid var(--primary-blue);
    margin-bottom: 2rem;
}

.card-title-custom {
    font-weight: 700;
    color: #2c3e50;
}

.btn-filter {
    background: var(--primary-blue);
    border: none;
    padding: 10px 30px;
    border-radius: 8px;
    color: #fff;
    font-weight: 700;
}
</style>

<div class="card filter-card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="card-title-custom mb-0">Sale Returns Report</h5>
        </div>

        <!-- 🔍 FILTER -->
        <form method="GET" action="{{ route('admin.sales.returns-report') }}">
            <div class="row g-3 mb-4">

                <!-- Employee -->
                <div class="col-md-3">
                    <label class="form-label">Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">All Employees</option>
                        @foreach ($employees as $emp)
                        <option value="{{ $emp->n_employee_id }}"
                            {{ request('employee_id') == $emp->n_employee_id ? 'selected' : '' }}>
                            {{ $emp->c_employee_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Store -->
                <div class="col-md-2">
                    <label class="form-label">Store</label>
                    <select name="store_id" class="form-select">
                        <option value="">All Stores</option>
                        @foreach ($stores as $store)
                        <option value="{{ $store->n_store_id }}"
                            {{ request('store_id') == $store->n_store_id ? 'selected' : '' }}>
                            {{ $store->c_store_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Return Status -->
                <div class="col-md-2">
                    <label class="form-label">Return Status</label>
                    <select name="return_status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="requested" {{ request('return_status') == 'requested' ? 'selected' : '' }}>
                            Requested</option>
                        <option value="approved" {{ request('return_status') == 'approved' ? 'selected' : '' }}>Approved
                        </option>


                    </select>
                </div>

                <!-- Dates -->
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

            </div>

            <div class="mb-4 d-flex gap-2">
                <button type="submit" class="btn btn-filter text-white">
                    <i class="ti ti-search me-1"></i> Filter Report
                </button>
                @can('sale-returns.export')
                <a href="{{ route('admin.export.sale-returns.report', request()->all()) }}"
                    class="btn btn-filter bg-success text-white">
                    <i class="ti ti-file-export me-1"></i> Export to Excel
                </a>
                @endcan
                <a href="{{ route('admin.sales.returns-report') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-refresh me-1"></i> Reset
                </a>
            </div>
        </form>

        <!-- 📋 TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">Sl No.</th>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Store</th>
                        <th>Store Code</th>
                        <th>Product</th>
                        <th>Bill No</th>
                        <th>Amount</th>
                        <th class="text-center">Return Status</th>
                        <th class="text-center">Incentive Calculated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $index => $row)
                    <tr>
                        <td class="ps-3">{{ $report->firstItem() + $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->d_date)->format('M d, Y') }}</td>
                        <td>{{ $row->c_employee_name }}</td>
                        <td>{{ $row->c_store_name }}</td>
                        <td>{{ $row->c_store_code }}</td>
                        <td>{{ $row->c_product_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $row->c_bill_no }}</span></td>
                        <td class="fw-bold">{{ number_format($row->total_price, 2) }}</td>
                        <td class="text-center">
                            @if($row->return_status == 'approved')
                            <span class="badge bg-light-success text-success fw-semibold">Approved</span>
                            @elseif($row->return_status == 'requested')
                            <span class="badge bg-light-warning text-warning fw-semibold">Requested</span>
                            @else
                            <span
                                class="badge bg-light-secondary text-secondary fw-semibold">{{ ucfirst($row->return_status) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row->is_incentive_calculated)
                            <span class="badge bg-light-success text-success fw-semibold">Yes</span>
                            @else
                            <span class="badge bg-light-warning text-warning fw-semibold">No</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <p class="text-muted mb-0">No return records found matching your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $report->links() }}
        </div>

    </div>
</div>

@endsection