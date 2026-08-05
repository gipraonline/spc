@extends('layouts.app')

@push('styles')
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
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Crypt;
@endphp
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Sales Orders</h5>
        @can('leads.create')
        <a href="{{ route('admin.leads.create') }}" class="btn buttonSpc">Add Sales Entry</a>
        @endcan
    </div>





    <div class="card-body p-4">

        @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
        @endif

        <form method="GET" action="{{ route('admin.leads.index') }}" class="p-2">
             <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body">

                <!-- Search By Farm Care Advisor Name or Code-->
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Farm Care Advisor name/Code"
                            value="{{ request('search') }}">
                    </div>

                    <!-- From Date -->
                    <div class="col-lg-3 col-md-3">
                        <label class="form-label fw-semibold">From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>

                    <!-- To Date -->
                    <div class="col-lg-3 col-md-3">
                        <label class="form-label fw-semibold">To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>

                    <!-- Buttons -->
                    <div class="col-lg-3 col-md-3 pt-4 d-flex gap-2">
                        <button class="btn buttonSpc">Filter Report</button>
                        @can('leads.export')
                        <button type="submit" name="export" value="excel" class="btn btn-success">
                            <i class="ti ti-file-export me-1"></i>
                            Export to Excel
                        </button>
                        @endcan
                        <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>

            </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Order Id</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Address</th>
                        {{-- <th scope="col">Farm Care Advisor</th> --}}
                        <th scope="col">Franchise</th>
                        @canany(['leads.view-details', 'leads.edit', 'leads.delete'])
                        <th scope="col">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>

                    @forelse($sales as $key=>$sale)
                    <tr>
                        <td class="border-bottom-0 text-center">
                            <span class="fw-normal">{{ $sales->firstItem() + $key }}</span>
                        </td>
                        <td>{{ $sale?->c_bill_no ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale->d_date)->format('d M Y') }}</td>
                        <td>{{ $sale?->c_customer_name ?? 'N/A' }}</td>
                        <td>{{ $sale?->c_customer_address ?? 'N/A' }}</td>
                       {{--  <td>
                            <div class="d-flex align-products-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $sale->employee?->c_employee_name ?? 'N/A' }}</h6>
                                    <span class="fs-2 text-muted">{{ $sale->employee?->c_employee_code ?? '' }}</span>
                                </div>
                            </div>
                        </td> --}}
                        <td>
                            <div class="d-flex align-products-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $sale->franchise?->c_store_name ?? 'N/A' }}</h6>
                                    <span class="fs-2 text-muted">{{ $sale->franchise?->c_store_code ?? '' }}</span>
                                </div>
                            </div>
                        </td>


                        @canany(['leads.view-details', 'leads.edit', 'leads.delete'])
                        <td>
                            <div class="dropdown dropstart">
                                <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        @can('leads.view-details')
                                        <a class="dropdown-item d-flex align-products-center gap-3"
                                            href="{{ route('admin.leads.show', Crypt::encryptString($sale->n_sl_no)) }}">
                                            <i class="fs-4 ti ti-eye"></i>View Details
                                        </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('leads.edit')
                                        <a class="dropdown-item d-flex align-products-center gap-3"
                                            href="{{ route('admin.leads.edit', Crypt::encryptString($sale->n_sl_no)) }}">
                                            <i class="fs-4 ti ti-edit"></i>Edit
                                        </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('leads.delete')
                                        <form action="{{ route('admin.leads.destroy', Crypt::encryptString($sale->n_sl_no)) }}" method="POST"
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
