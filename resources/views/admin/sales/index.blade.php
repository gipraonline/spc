@extends('layouts.app')

@section('content')
<style>
.card form {
    background: #fff;
    border-bottom: 1px solid #eee;
}

.form-label {
    margin-bottom: 8px;
}

.form-control {
    height: 45px;
}

.btn {
    height: 45px;
}
</style>
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Sales Records</h5>
        @can('sales-orders.create')
        <a href="{{ route('admin.salesorders.create') }}" class="btn btn-primary btn-sm">Add Sales Entry</a>
        @endcan
    </div>
    <form method="GET" action="{{ route('admin.salesorders.index') }}" class="p-4">
        <div class="row g-4 align-items-end">

            <!-- Search By Employee Name or Code-->
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Employee name/Code"
                    value="{{ request('search') }}">
            </div>

            <!-- From Date -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>

            <!-- To Date -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <!-- Buttons -->
        </div>
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary">Filter Report</button>
            @can('incentives.export')
            <button type="submit" name="export" value="excel" class="btn btn-success">
                <i class="ti ti-file-export me-1"></i>
                Export to Excel
            </button>
            @endcan
            <a href="{{ route('admin.salesorders.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Employee</th>
                        <th scope="col">Store</th>
                        <!-- <th scope="col">Store Code</th> -->
                        <th scope="col">Item</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Purchase</th>
                        <th scope="col">Total Sales</th>
                        <th scope="col">Margin</th>
                        @canany(['incentives.view-details', 'incentives.edit', 'incentives.delete'])
                        <th scope="col">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sale->d_date)->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex align-products-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $sale->employee?->c_employee_name ?? 'N/A' }}</h6>
                                    <span class="fs-2 text-muted">{{ $sale->employee?->c_employee_code ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-products-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $sale->store?->c_store_name ?? 'N/A' }}</h6>
                                    <span class="fs-2 text-muted">{{ $sale->store?->c_store_code ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $sale->product?->c_product_name ?? 'N/A' }}</td>
                        <td>{{ $sale->n_quantity }}</td>
                        <td>{{ number_format($sale->product?->n_selling_price ?? 0, 2) }}</td>
                        <td>{{ number_format($sale->product?->n_purchase_price ?? 0, 2) }}</td>
                        <td>{{ number_format($sale->total_sales_amount, 2) }}</td>
                        <td class="fw-bold text-success">{{ number_format($sale->total_margin_amount, 2) }}</td>
                        @canany(['incentives.view-details', 'incentives.edit', 'incentives.delete'])
                        <td>
                            <div class="dropdown dropstart">
                                <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        @can('incentives.view-details')
                                        <a class="dropdown-item d-flex align-products-center gap-3"
                                            href="{{ route('admin.sales.show', $sale->n_slno) }}">
                                            <i class="fs-4 ti ti-eye"></i>View Details
                                        </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('incentives.edit')
                                        <a class="dropdown-item d-flex align-products-center gap-3"
                                            href="{{ route('admin.sales.edit', $sale->n_slno) }}">
                                            <i class="fs-4 ti ti-edit"></i>Edit
                                        </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('incentives.delete')
                                        <form action="{{ route('admin.sales.destroy', $sale->n_slno) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="dropdown-item d-flex align-products-center gap-3 text-danger">
                                                <i class="fs-4 ti ti-trash"></i>Delete
                                            </button>
                                        </form>
                                        @endcan
                                    </li>
                                </ul>
                            </div>
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No sales records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
