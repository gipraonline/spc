@extends('layouts.app')

@section('content')

<div class="card filter-card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="card-title-custom mb-0">Sales Report</h5>

        </div>
        <!--  FILTER -->
        <form method="GET" action="{{ route('admin.sales.uploads.report') }}">

            <div class="row g-3 mb-4">

                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Bill No / Product Code"
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
                        <th>Bill No</th>
                        <th>Product Code</th>
                        <th>Selling Rate</th>
                        <th>Buying Rate</th>
                        <th>Quantity</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $key=>$sale)
                    <tr>
                        <td>{{ $sales->firstItem() + $key }}</td>
                        <td>{{ $sale->d_date }}</td>
                        <td>{{ $sale->c_store_name }}</td>
                        <td>{{ $sale->c_store_code }}</td>
                        <td>{{ $sale->c_bill_no }}</td>
                        <td>{{ $sale->c_product_code ?? $sale->n_product_id }}</td>
                        <td>{{ $sale->n_sold_price }}</td>
                        <td>{{ $sale->n_buying_rate }}</td>
                        <td>{{ $sale->n_quantity }}</td>
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
            {{ $sales->appends(request()->query())->links() }}
        </div>

    </div>
</div>

@endsection