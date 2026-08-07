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
        <form method="GET">

            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Store Name / Code"
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label>Store Type</label>
                    <select name="store_type" class="form-control">
                        <option value="">All</option>
                        <option value="centreal" {{ request('store_type') == 'centreal' ? 'selected' : '' }}>
                            Centreal Operations
                        </option>
                        <option value="vanitham" {{ request('store_type') == 'vanitham' ? 'selected' : '' }}>
                            Vanitham Operations
                        </option>
                    </select>
                </div>
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <label>From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-filter">
                            <i class="ti ti-search me-1"></i>Filter Report
                        </button>

                        <button type="submit" name="export" value="excel" class="btn btn-success text-white py-2 px-3">
                            <i class="ti ti-file-export me-1"></i>
                            Export to Excel
                        </button>
                        <button type="button" onclick="window.location='{{ route ('view.store.report') }}'"
                            class="btn btn-secondary text-white py-2 px-4">
                            <i class="ti ti-refresh me-1"></i>
                            Reset
                        </button>
                    </div>

                </div>
            </div>

        </form>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Date</th>
                        <th>Store Code</th>
                        <th>Store Name</th>
                        <th>Total Sales</th>
                        <th>Total Purchase</th>
                        <th>Total Profit</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($stores as $key => $store)
                    <tr>
                        <td>
                            {{ ($stores->currentPage()-1) * $stores->perPage() + $loop->iteration }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($store->d_date)->format('d-m-Y') }}</td>
                        <td>{{ $store->c_store_code }}</td>
                        <td>{{ $store->c_store_name }}</td>
                        <td>{{ number_format($store->sale_amount,2) }}</td>
                        <td>{{ number_format($store->purchase_amount,2) }}</td>
                        <td>{{ number_format($store->profit_amount,2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>


        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $stores->links() }}
        </div>

    </div>
</div>

@endsection