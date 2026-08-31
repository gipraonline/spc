@extends('layouts.app')

@section('content')


<div class="card filter-card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4 class="mb-0">Sales Report</h4>

            <!-- <a href="{{ route('view.report') }}" class="btn btn-outline-secondary custom-btn">
                <span class="me-2">←</span>Back to Report
            </a> -->

        </div>
        <!--  FILTER -->
        <form method="GET" action="{{ route('view.report') }}">

            <div class="row g-3 mb-4">

                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Employee name/Code"
                        value="{{ request('search') }}">
                </div>

                <!-- From Date -->
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

            <div class="mb-4">
                <button type="submit" class="btn btn-filter">
                    <i class="ti ti-search me-1"></i>Filter Report
                </button>

                <button type="submit" name="export" value="excel" class="btn btn-success text-white py-2 px-3">
                    <i class="ti ti-file-export me-1"></i>
                    Export to Excel
                </button>
                <button type="button" onclick="window.location='{{ route('view.report') }}'"
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
                        <th>Store Code</th>
                        <th>Employee</th>
                        <th>Employee Code</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($allSales as $sale)
                    <tr>
                        <td>
                            {{ ($allSales->currentPage() - 1) * $allSales->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                        <td>{{ $sale->store->c_store_code ?? 'N/A' }}</td>
                        <td>{{ $sale->employee->c_employee_name ?? 'N/A' }}</td>
                        <td>{{ $sale->employee->c_employee_code ?? 'N/A' }}</td>
                        <td>{{ $sale->product->c_product_name ?? 'N/A' }}</td>
                        <td>₹{{ number_format($sale->n_quantity * $sale->n_sold_price, 2) }}</td>
                        <td>{{ $sale->status }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No sales records found</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $allSales->appends(request()->query())->links() }}
        </div>

    </div>
</div>

@endsection