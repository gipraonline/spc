@extends('layouts.app')

@section('content')

<style>
:root {
    --primary-green: #1b3e86;
    --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
}

.filter-card {
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border-top: 4px solid var(--primary-green);
    margin-bottom: 2rem;
}

.card-title-custom {
    font-weight: 700;
    color: #2c3e50;
}

.btn-calculate {
    background: var(--primary-green);
    border: none;
    padding: 10px 30px;
    border-radius: 8px;
    color: #fff;
    font-weight: 700;
}

.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: var(--bs-border-width) solid #dfe5ef;
    border-radius: 7px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 38px;
}

.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 38px;
    user-select: none;
    -webkit-user-select: none;
}

.select2-results__options {
    max-height: 300px;
    overflow-y: auto;
    scrollbar-width: thick;
}
</style>

<!-- ✅ SINGLE CARD -->
<div class="card filter-card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="card-title-custom mb-0">Verified Sales</h5>
            <a href="{{ route('admin.incentives.batch') }}" class="btn btn-primary d-flex align-items-center">
                <i class="ti ti-plus me-1 fs-5"></i>
                Start New Batch
            </a>
        </div>



        <!-- FILTER SECTION -->
        <div class="filter-card-wrapper mb-4">

            <div class="filter-header-sub">
                <div class="icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>

                    </svg>
                </div>

                <span>Refine Search</span>
            </div>
            <form method="POST" action="{{ route('sales.report') }}">
                @csrf

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
                    <div class="col-md-3">
                        <label class="form-label">Store Name</label>

                        <select id="n_store_id" name="n_store_id" class="form-select">
                            <option value="">Select Store</option>
                            @foreach ($storeDropdown as $store)
                            <option value="{{ $store->n_store_id }}">
                                {{ $store->c_store_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Store Code -->
                    <div class="col-md-3">
                        <label class="form-label">Store Code</label>

                        <input type="text" id="store_code" name="store_code" class="form-control"
                            placeholder="Search Store Code" value="{{ request('store_code') }}">
                    </div>

                    <!-- Product Name -->
                    <div class="col-md-3">
                        <label class="form-label">Product Name</label>

                        <input type="text" id="product_name" name="product_name" class="form-control"
                            value="{{ request('product_name') }}" placeholder="Product Name">
                    </div>

                    <!-- Bill No -->
                    <div class="col-md-3">
                        <label class="form-label">Bill No</label>

                        <input type="text" id="bill_no" name="bill_no" class="form-control"
                            value="{{ request('bill_no') }}" placeholder="Bill No">
                    </div>


                    <!-- Incentive Status -->
                    <div class="col-md-3">
                        <label class="form-label">Incentive Status</label>
                        <select name="incentive_status" class="form-select">
                            <option value="">All Records</option>
                            <option value="1" {{ request('incentive_status') == '1' ? 'selected' : '' }}>Done</option>
                            <option value="0" {{ request('incentive_status') == '0' ? 'selected' : '' }}>Pending
                            </option>
                        </select>
                    </div>

                    <!-- Dates -->
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                </div>

                <div class="mb-4 d-flex gap-2">
                    <button type="submit" class="btn btn-calculate">
                        <i class="ti ti-search me-1"></i> Filter Report
                    </button>
                    @can('verified-sales.export')
                    <a href="{{ route('export.sales.report', [
                                'employee_id' => request('employee_id'),
                                'store_id' => request('store_id'),
                                'incentive_status' => request('incentive_status'),
                                'start_date' => request('start_date'),
                                'end_date' => request('end_date'),
                            ]) }}" class="btn btn-calculate bg-success text-white">
                        <i class="ti ti-file-export me-1"></i> Export to Excel
                    </a>
                    @endcan
                </div>


            </form>
        </div>
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
                        <th>Buying Rate</th>
                        <th>Sold Price</th>
                        <th>Margin (20%)</th>
                        <th class="text-center">Incentive Status</th>
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
                        <td>{{ number_format($row->n_buying_rate, 2) }}</td>
                        <td class="fw-bold">{{ number_format($row->n_sold_price, 2) }}</td>
                        <td class="text-success fw-bold">{{ number_format($row->n_total_margin_amount, 2) }}</td>
                        <td class="text-center">
                            @if($row->is_incentive_calculated)
                            <span class="badge bg-light-success text-success fw-semibold">Done</span>
                            @else
                            <span class="badge bg-light-warning text-warning fw-semibold">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-4">
                            <p class="text-muted mb-0">No sales records found match your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $report->links() }}
        </div>

    </div>

</div>
</div>

@push('scripts')

<script>
let timer;

document.getElementById('store_code').addEventListener('keyup', function() {

    clearTimeout(timer);

    timer = setTimeout(() => {

        this.form.submit();

    }, 1800);

});
</script>

<script>
let productTimer;

document.getElementById('product_name').addEventListener('keyup', function() {

    clearTimeout(productTimer);

    productTimer = setTimeout(() => {

        this.form.submit();

    }, 1800);

});
</script>

<script>
let billTimer;

document.getElementById('bill_no').addEventListener('keyup', function() {

    clearTimeout(billTimer);

    billTimer = setTimeout(() => {

        this.form.submit();

    }, 1800);

});
</script>
<!-- select 2 for store name -->
<script>
$(document).ready(function() {

    $('#n_store_id').select2({
        placeholder: "Select Store",

        matcher: function(params, data) {

            // show first 5 initially
            if ($.trim(params.term) === '') {

                let index = $(data.element).index();

                return index <= 5 ? data : null;
            }

            // search filter
            if (typeof data.text === 'undefined') {
                return null;
            }

            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }

            return null;
        }
    });

});
</script>

@endpush
@endsection