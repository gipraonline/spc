@extends('layouts.app')

@push('styles')
<style>
    /* Global Page & Background Reset */
    .content-wrapper {
        background-color: #f4f8f5;
        min-height: 100vh;
        padding: 24px;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top 5 Stat Widget Cards Grid */
    .widgets-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .widget-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .widget-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    }

    .widget-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #ffffff;
    }

    .widget-icon.green-dark { background-color: #166534; }
    .widget-icon.orange { background-color: #ea580c; }
    .widget-icon.blue { background-color: #0284c7; }
    .widget-icon.green-emerald { background-color: #059669; }
    .widget-icon.purple { background-color: #7c3aed; }

    .widget-details {
        display: flex;
        flex-direction: column;
    }

    .widget-count {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .widget-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-top: 2px;
    }

    /* Main Container Card Styling */
    .card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f5132;
    }

    .buttonSpc {
        background: linear-gradient(135deg, #0f5132 0%, #059669 100%) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 10px 20px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
        transition: all 0.2s ease;
    }

    .buttonSpc:hover {
        background: linear-gradient(135deg, #0b3e26 0%, #047857 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3);
    }

    /* Filter Form Styling */
    .refine-search-card {
        background-color: #f8faf8 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control {
        height: 42px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }

    .btn {
        height: 42px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Table & Status Badges Styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: #f8faf8;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table tbody td {
        padding: 14px 16px;
        font-size: 13.5px;
        color: #1e293b;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr:hover td {
        background-color: #f8faf8;
    }

    /* Status Pills matching exact design in image */
    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-status.pending {
        background-color: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-status.confirmed {
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-status.approved {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-status.dispatched {
        background-color: #f3e8ff;
        color: #6b21a8;
        border: 1px solid #e9d5ff;
    }

    .badge-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    }

    /* Approved - Green */
    .badge-status.approved {
        background-color: #d1fae5;
        color: #047857;
    }

    /* Dispatched - Blue */
    .badge-status.dispatched {
        background-color: #dbeafe;
        color: #1d4ed8;
    }

    /* Shipped - Purple */
    .badge-status.shipped {
        background-color: #ede9fe;
        color: #7c3aed;
    }

    /* Delivered - Teal */
    .badge-status.delivered {
        background-color: #ccfbf1;
        color: #0f766e;
    }

    /* Completed - Dark Green */
    .badge-status.completed {
        background-color: #dcfce7;
        color: #06f55ed8;
    }

    /* Pending - Yellow/Orange */
    .badge-status.pending {
        background-color: #fef3c7;
        color: #b45309;
    }

    /* Unknown status */
    .badge-status.unknown {
        background-color: #e5e7eb;
        color: #374151;
    }

    /* Responsive Grid Breakpoints */
    @media (max-width: 1200px) {
        .widgets-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .widgets-grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
@endpush

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

<!-- Top 5 Stat Widget Cards (As shown in reference image) -->
<div class="widgets-grid">

    <!-- 1. Total Sales Orders -->
    <div class="widget-card">
        <div class="widget-icon green-dark">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count">{{ $totalSalesOrders ?? $sales->total() ?? 0 }}</span>
            <span class="widget-label">Total Sales Orders</span>
        </div>
    </div>

    <!-- 2. Pending -->
    <div class="widget-card">
        <div class="widget-icon orange">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count">{{ $pendingOrders ?? 0 }}</span>
            <span class="widget-label">Pending</span>
        </div>
    </div>

    <!-- 4. Order Approved -->
    <div class="widget-card">
        <div class="widget-icon green-emerald">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count">{{ $approvedOrders ?? 0 }}</span>
            <span class="widget-label">Order Approved</span>
        </div>
    </div>


    <!-- 5. Dispatched -->
    <div class="widget-card">
        <div class="widget-icon purple">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count">{{ $dispatchedOrders ?? 0 }}</span>
            <span class="widget-label">Dispatched</span>
        </div>
    </div>

     <!-- 3. Order Confirmed -->
    <div class="widget-card">
        <div class="widget-icon blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count">{{ $completedOrders ?? 0 }}</span>
            <span class="widget-label">Order Completed</span>
        </div>
    </div>



</div>

<!-- Main Table & Filter Card -->
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Sales Orders</h5>
        @can('sales-orders.create')
        <a href="{{ route('admin.salesorders.create') }}" class="btn buttonSpc">
            <i class="ti ti-plus me-1"></i> Add Sales Entry
        </a>
        @endcan
    </div>

    <div class="card-body p-4">

        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form method="GET" action="{{ route('admin.salesorders.index') }}" class="p-0">
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body p-3">

                    <!-- Search By Farm Care Advisor Name or Code-->
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Farm Care Advisor name/Code" value="{{ request('search') }}">
                        </div>

                        <!-- From Date -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="form-control">
                        </div>

                        <!-- To Date -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                        </div>

                        <!-- Payment Status -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">Payment Status</label>
                            <select name="payment_status"
                                    id="leadStatus"
                                    class="form-select">

                                <option value="">Select Status</option>

                                <option value="pending" {{ old('payment_status', $sale->payment_status ?? '') == "pending" ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed"  {{ old('payment_status', $sale->payment_status ?? '') == "confirmed" ? 'selected' : '' }}>Confirmed</option>

                            </select>
                        </div>

                        <!-- Order Status -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">Order Status</label>

                            <select name="order_status" class="form-select">

                                <option value="">Select Status</option>

                                <option value="Pending"
                                    {{ request('order_status') == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="Approved"
                                    {{ request('order_status') == 'Approved' ? 'selected' : '' }}>
                                    Approved
                                </option>

                                <option value="Dispatched"
                                    {{ request('order_status') == 'Dispatched' ? 'selected' : '' }}>
                                    Dispatched
                                </option>

                                <option value="Shipped"
                                    {{ request('order_status') == 'Shipped' ? 'selected' : '' }}>
                                    Shipped
                                </option>

                                <option value="Delivered"
                                    {{ request('order_status') == 'Delivered' ? 'selected' : '' }}>
                                    Delivered
                                </option>

                                <option value="Completed"
                                    {{ request('order_status') == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>


                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-3 col-md-3 d-flex gap-2">
                            <button class="btn buttonSpc w-100">Filter Report</button>
                            @can('sales-orders.export')
                            <button type="submit" name="export" value="excel" class="btn btn-success">
                                <i class="ti ti-file-export me-1"></i>
                                Export
                            </button>
                            @endcan
                            <a href="{{ route('admin.salesorders.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Order Id</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Address</th>
                        @if(isset($isFarmCareAdvisor))
                        <th scope="col">Farm Care Advisor</th>
                        @endif
                        <th scope="col">Franchise</th>
                        <th scope="col">Payment Image</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Order Status</th>
                        @canany(['sales-orders.view-details', 'sales-orders.edit', 'sales-orders.delete'])
                        <th scope="col">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>

                    @forelse($sales as $key=>$sale)
                    <tr>
                        <td class="text-center">
                            <span class="fw-normal">{{ $sales->firstItem() + $key }}</span>
                        </td>
                        <td><strong>{{ $sale?->c_order_no ?? 'N/A' }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($sale->d_date)->format('d M Y') }}</td>
                        <td>{{ $sale?->c_customer_name ?? 'N/A' }}</td>
                        <td>{{ $sale?->c_customer_address ?? 'N/A' }}</td>

                        @if(isset($isFarmCareAdvisor))
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $sale->employee?->c_employee_name ?? 'N/A' }}</h6>
                                    <span class="fs-2 text-muted">{{ $sale->employee?->c_employee_code ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        @endif
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $sale->franchise?->c_store_name ?? 'N/A' }}</h6>
                                    <span class="fs-2 text-muted">{{ $sale->franchise?->c_store_code ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($sale->payment_image)
                                <a href="{{ asset('uploads/payment_images/' . $sale->payment_image) }}" target="_blank">
                                    <img src="{{ asset('uploads/payment_images/' . $sale->payment_image) }}"
                                        width="50"
                                        height="50"
                                        style="object-fit: cover; border-radius: 6px; cursor: pointer; border: 1px solid #e2e8f0;">
                                </a>
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                         <td>
                            @php
                                $status = strtolower($sale->payment_status ?? 'pending');
                            @endphp
                            @if($status == 'confirmed' )
                                <span class="badge-status confirmed">Confirmed</span>
                            @else
                                <span class="badge-status pending">Pending</span>
                            @endif
                        </td>

                        <td>
                            @php
                                $status = strtolower($sale->current_order_status ?? 'pending');
                            @endphp
                            @if($status == 'approved' )
                                <span class="badge-status approved">Order Approved</span>
                            @elseif($status == 'dispatched')
                                <span class="badge-status dispatched">Dispatched</span>
                            @elseif($status == 'shipped')
                                <span class="badge-status shipped">Shipped</span>
                            @elseif($status == 'delivered')
                                <span class="badge-status delivered">Delivered</span>
                            @elseif($status == 'completed')
                                <span class="badge-status completed">Completed</span>
                            @elseif($status == 'pending')
                                <span class="badge-status pending">Pending</span>
                            @endif
                        </td>

                        @canany(['sales-orders.view-details', 'sales-orders.edit', 'sales-orders.delete'])
                        <td>
                            <div class="dropdown dropstart">
                                <a href="#" class="text-muted p-1" id="dropdownMenuButton_{{ $sale->n_sl_no }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $sale->n_sl_no }}">
                                    <li>
                                        @can('sales-orders.view-details')
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="{{ route('admin.salesorders.show', Crypt::encryptString($sale->n_sl_no)) }}">
                                            <i class="fs-4 ti ti-eye text-primary"></i>View Details
                                        </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('sales-orders.edit')
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="{{ route('admin.salesorders.edit', Crypt::encryptString($sale->n_sl_no)) }}">
                                            <i class="fs-4 ti ti-edit text-success"></i>Edit
                                        </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('sales-orders.delete')
                                        <form action="{{ route('admin.salesorders.destroy', Crypt::encryptString($sale->n_sl_no)) }}"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-3 text-danger">
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
                        <td colspan="10" class="text-center py-4 text-muted">No sales records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $sales->links() }}
        </div>

    </div>
</div>
@endsection
