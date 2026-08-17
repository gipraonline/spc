@extends('layouts.app')

@push('styles')
<style>
/* Creative Light Theme & Green Palette */
:root {
    --bg-body: #f4f8f5;
    --card-bg: #ffffff;
    --primary-green: #0f5132;
    --emerald-green: #059669;
    --light-green-bg: #f0fdf4;
    --border-green: #d1e7dd;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --border-slate: #e2e8f0;
}

/* Main Card Container */
.card {
    border-radius: 14px;
    border: 1px solid var(--border-slate);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    background-color: var(--card-bg);
}

.card-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-green);
}

/* Form Section Box Styling */
.form-section,
.form-box,
.border.rounded {
    border: 1px solid var(--border-slate) !important;
    border-radius: 12px !important;
    padding: 24px !important;
    margin-bottom: 24px !important;
    background-color: #ffffff !important;
    box-shadow: 0 2px 10px rgba(15, 81, 50, 0.02);
}

/* Section Titles */
.section-title,
.form-section-header {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary-green);
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Badge Pills */
.badge-new {
    background-color: #059669;
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-updated {
    background-color: #10b981;
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-renamed {
    background-color: #047857;
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Form Controls & Inputs */
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.form-control,
.form-select {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    color: #1e293b;
    background-color: #ffffff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--emerald-green);
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    outline: none;
}

/* Highlighted Readonly Inputs */
.order-number,
.advisor-highlight {
    background-color: #f4f8f5 !important;
    color: var(--primary-green) !important;
    font-weight: 700 !important;
    border-color: var(--border-green) !important;
}

/* Radio Button Cards (Payment & Order Status) */
.payment-option,
.order-status-option {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 16px;
    margin-right: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    background-color: #ffffff;
    transition: all 0.2s ease;
}

.payment-option:hover,
.order-status-option:hover {
    background-color: #f4f8f5;
    border-color: var(--primary-green);
}

.payment-option input[type="radio"],
.order-status-option input[type="radio"] {
    margin: 0;
    accent-color: var(--primary-green);
    width: 16px;
    height: 16px;
}

.payment-option input[type="radio"]:checked+label,
.order-status-option input[type="radio"]:checked+label {
    color: var(--primary-green);
    font-weight: 700;
}

/* Primary & Secondary Buttons */
.buttonSpc,
#addRow,
#btn_create {
    background: linear-gradient(135deg, #0f5132 0%, #059669 100%) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 22px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
    transition: all 0.2s ease;
}

.buttonSpc:hover,
#addRow:hover,
#btn_create:hover {
    background: linear-gradient(135deg, #0b3e26 0%, #047857 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3);
}

.btn-outline-secondary {
    border: 1px solid #cbd5e1 !important;
    color: #475569 !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 600 !important;
}

.btn-outline-secondary:hover {
    background-color: #f8fafc !important;
    color: #1e293b !important;
}

/* Product Table Styling */
#productTable {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--border-slate);
}

#productTable thead th {
    background-color: #f8faf8;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
    padding: 12px 14px;
    border-bottom: 1px solid var(--border-slate);
}

#productTable tbody td {
    padding: 10px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.removeRow {
    background-color: #dc2626 !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
}

.removeRow:hover {
    background-color: #b91c1c !important;
}

/* Product Details Summary Box */
.product-summary-box {
    background-color: #f8faf8;
    border: 1px solid var(--border-slate);
    border-radius: 12px;
    padding: 18px;
}

.summary-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    gap: 12px;
}

.summary-line:last-child {
    margin-bottom: 0;
}

.summary-label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    flex: 1;
}

.summary-input {
    width: 140px;
    text-align: right;
    font-weight: 600;
    background-color: #ffffff !important;
}

.summary-line.highlight-green {
    background-color: #e8f5e9;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #a7f3d0;
    margin-top: 8px;
}

.summary-line.highlight-green .summary-label {
    color: #0f5132;
    font-size: 14px;
}

.summary-line.highlight-green .summary-input {
    color: #0f5132 !important;
    font-size: 16px;
    font-weight: 800;
    border-color: #a7f3d0;
}

/* Modal Backdrop Z-Index */
#approveModal {
    z-index: 1060 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

/* Mobile Responsive Optimizations */
@media (max-width: 768px) {
    .card-body {
        padding: 16px !important;
    }

    .form-section,
    .form-box,
    .border.rounded {
        padding: 16px !important;
        margin-bottom: 16px !important;
    }

    .payment-option,
    .order-status-option {
        width: 100%;
        margin-right: 0;
    }

    .product-summary-box {
        width: 100% !important;
        margin-top: 16px;
    }

    .summary-input {
        width: 110px;
    }
}

.tablescrolll {
    overflow-x: scroll;
}


#productTable tbody td input,
#productTable tbody td select {
    width: stretch;
    min-width: 100%;
}


#productTable thead th {

    white-space: nowrap;
}

@media screen and (max-width:767px) {
    .summary-line {
        flex-wrap: wrap;
    }

    .text-end {
        text-align: left !important;
    }

    .section-title,
    .form-section-header {
        flex-wrap: wrap
    }

    .tablescrolll {
        overflow-x: scroll;
    }
}
</style>
@endpush

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp


<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Add Sales Orders</h5>
    </div>
    <div class="card-body p-4">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form method="POST" id="frm_create" action="{{ route('admin.salesorders.store') }}"
            enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id" class="form-control" value="{{isset($sale) ? $sale->n_sl_no : ''}}">

            <!-- Section 1: Order Information -->
            <div class="form-section mb-4">

                <div class="section-title mb-3">
                    <i class="ti ti-file-invoice fs-5"></i>
                    Order Information
                </div>

                <!-- Row 1: Date & Booklet Serial No -->
                <div class="row g-3">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="d_date" class="form-control mandatory"
                            data-message="Please Select a Date"
                            value="{{ old('d_date', isset($sale) ? $sale->d_date->format('Y-m-d') : date('Y-m-d')) }}"
                            {{isset($viewmode) && $viewmode=='on' ? 'readonly' : '' }}>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Booklet Serial No *
                        </label>
                        <div class="position-relative">
                            <input type="text" name="c_order_no" placeholder="BK-2026-0417"
                                class="form-control order-number fw-bold text-success"
                                value="{{ old('c_order_no', isset($sale->c_order_no) ? $sale->c_order_no : '') }}"
                                {{isset($viewmode) && $viewmode=='on' ? 'readonly' : '' }}>
                        </div>
                        @error('c_order_no')
                        <div class="text-danger mt-1 fs-2">
                            {{ $message }}
                        </div>

                        @enderror
                    </div>

                </div>

                <!-- Row 2: Order No & Farm Care Advisor -->
                <div class="row g-3">


                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Farm Care Advisor *
                        </label>

                        @if($isFarmCareAdvisor)
                        <input type="text" class="form-control advisor-highlight" value="{{ auth()->user()->c_name }}"
                            readonly>
                        @else
                        <select name="farm_care_advisor_id" class="form-control mandatory">
                            <option value="">Select Farm Care Adviser</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->n_employee_id }}"
                                {{isset($sale) && $sale->farm_care_advisor_id == $employee->n_employee_id  ? 'selected' : '' }}>
                                {{ $employee->c_employee_name }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Sales Order Booklet Proof *
                        </label>
                        <input type="file" name="f_booklet_proof" class="form-control">
                    </div>

                </div>

            </div>

            <!-- Section 2: Product Details -->
            <div class="form-section mb-4">

                <div class="section-title d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <i class="ti ti-shopping-cart fs-5"></i>
                        Product Details *
                    </div>

                    @if(!isset($viewmode) || $viewmode=='off')
                    <button type="button" class="btn buttonSpc btn-sm" id="addRow">
                        <i class="ti ti-plus"></i>
                        Add New Product
                    </button>
                    @endif
                </div>
                <div class="tablescrolll">
                    <table class="table table-bordered table-responsive align-middle" id="productTable">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Product</th>
                                <th width="12%">HSN Code</th>
                                <th width="12%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="13%">Unit</th>
                                <th width="12%">Discount</th>
                                <th width="10%">GST %</th>
                                <th width="13%">GST Amount</th>
                                <th width="13%">Discounted Price</th>
                                <th width="10%">MRP</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($sale->orderProducts) && count($sale->orderProducts) > 0)
                            @foreach($sale->orderProducts as $key=>$val)
                            <tr>
                                <td>
                                    <select name="products[{{ $key }}][product_id]"
                                        class="form-control product mandatory">

                                        <option value="">Select Product</option>

                                        @foreach($products as $product)

                                        <option value="{{ $product->n_product_id }}" data-price="{{ $product->n_mrp }}"
                                            data-gst="{{ $product->n_gst_percentage ?? 0 }}"
                                            {{ $val->product_id == $product->n_product_id ? 'selected' : '' }}>
                                            {{ $product->c_product_name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </td>

                                <td>
                                    <input type="text" name="products[{{ $key }}][c_hsn_code]"
                                        class="form-control c_hsn_code" value="{{ $val->c_hsn_code }}" readonly>
                                </td>


                                <td>
                                    <input type="text" name="products[{{ $key }}][product_price]"
                                        class="form-control price" value="{{ $val->product_price }}" readonly>
                                </td>

                                <td>
                                    <input type="number" name="products[{{ $key }}][qty]" class="form-control qty"
                                        value="{{ $val->qty }}" min="1">
                                </td>

                                <td>
                                    <input type="text" name="products[{{ $key }}][c_unit]" class="form-control c_unit"
                                        value="{{ $val->c_unit }}" readonly>
                                </td>

                                <td>
                                    <input type="number" name="products[{{ $key }}][discount]"
                                        class="form-control discount" value="{{ $val->discount ?? '0.00' }}" step="">
                                </td>

                                <!-- Product GST % -->
                                <td>
                                    <input type="number" name="products[{{ $key }}][n_gst_percentage]"
                                        class="form-control gst_percentage" value="{{ $val->n_gst_percentage ?? 0 }}"
                                        step="0.01" readonly>
                                </td>

                                <!-- Product GST Amount -->
                                <td>
                                    <input type="text" name="products[{{ $key }}][gst_amount]"
                                        class="form-control gst_amount" value="{{ $val->gst_amount ?? '0.00' }}"
                                        readonly>
                                </td>

                                <!-- Discounted Price -->
                                <td>
                                    <input type="text" name="products[{{ $key }}][discounted_price]"
                                        class="form-control discounted_price"
                                        value="{{ $val->discounted_price ?? '0.00' }}" readonly>
                                </td>

                                <td>
                                    <input type="text" name="products[{{ $key }}][product_total]"
                                        class="form-control total" value="{{ $val->product_total }}" readonly>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeRow">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Product Details Summary Box (Right Aligned as shown in shared image) -->

                <div class="row justify-content-end mt-4">
                    <div class="col-md-6 col-lg-5">
                        <div class="product-summary-box">

                            <!-- Total Sales Amount -->
                            <div class="summary-line">
                                <span class="summary-label">
                                    Total Sales Amount
                                </span>

                                <input type="text" name="n_total_sales_amount"
                                    class="form-control summary-input text-end" id="summaryTotalSales"
                                    value="{{ old('n_total_sales_amount', $sale->n_total_sales_amount ?? '0.00') }}"
                                    readonly>
                            </div>

                            {{--  <!-- Product Discount Total -->
                            <div class="summary-line">
                                <span class="summary-label">
                                    Product Discount Total
                                </span>

                                <input type="text" name="n_product_discount_total"
                                    class="form-control summary-input text-end" id="summaryProductDiscount"
                                    value="{{ old('n_product_discount_total', $sale->n_product_discount_total ?? '0.00') }}"
                            readonly>
                        </div> --}}

                        <!-- Additional Discount -->
                        <div class="summary-line">
                            <span class="summary-label">
                                Total GST

                            </span>

                            <input type="number" name="n_total_gst" class="form-control summary-input text-end"
                                id="summaryGstAmount" value="{{ old('n_total_gst', $sale->n_total_gst ?? '0.00') }}"
                                step="0.01" min="0">
                        </div>
                        <!-- Total Discount -->
                        <div class="summary-line">
                            <span class="summary-label">
                                Total Discount
                            </span>

                            <input type="text" name="n_product_discount_total" class="form-control summary-input"
                                id="summaryTotalDiscount"
                                value="{{ old('n_total_discount', $sale->n_product_discount_total ?? '0.00') }}">
                        </div>

                        <!-- Net Sales Amount -->
                        <div class="summary-line highlight-green">
                            <span class="summary-label fw-bold">
                                Net Sales Amount
                            </span>

                            <input type="text" name="n_net_sales_amount"
                                class="form-control summary-input text-end fw-bold text-success" id="summaryNetSales"
                                value="{{ old('n_net_sales_amount', $sale->n_net_sales_amount ?? '0.00') }}" readonly>
                        </div>

                    </div>
                </div>
            </div>

    </div>

    <!-- Section 3: Customer Information -->
    <div class="border rounded p-4 mb-4">

        <div class="form-section-header mb-3">
            <i class="ti ti-user fs-5"></i> Customer Information
        </div>

        <input type="hidden" name="c_customer_name" id="c_customer_name" value="">

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="c_customer_name" class="form-label">Customer *</label>

                <select name="n_customer_id" id="n_customer_id" class="form-select mandatory">
                    <option value="">Select Customer</option>
                    @if(isset($customers))
                    @foreach($customers as $customer)
                    <option value="{{ $customer->n_customer_id }}" data-name="{{ $customer->c_customer_name }}"
                        data-email="{{ $customer->c_email }}" data-mobile="{{ $customer->n_mobile }}"
                        data-address="{{ $customer->c_address }}" data-state="{{ $customer->n_state_id }}"
                        data-district="{{ $customer->n_district_id }}"
                        {{isset($sale->n_customer_id) && $sale->n_customer_id==$customer->n_customer_id ? "selected": ''}}>
                        {{ $customer->c_customer_name }}
                    </option>
                    @endforeach
                    @endif
                </select>
                <div class="text-danger mt-1 fs-2"></div>
            </div>

            <div class="col-md-6">
                <label for="c_customer_email" class="form-label">Customer Email *</label>

                <input type="text" id="c_customer_email" name="c_customer_email"
                    value="{{ old('c_customer_email',isset($sale) ? $sale->c_customer_email : '') }}"
                    {{isset($viewmode) && $viewmode=='on' ? 'readonly' : '' }}
                    data-message="Please enter Customer Email" class="form-control mandatory"
                    placeholder="Enter Customer Email">

                <div class="text-danger mt-1 fs-2"></div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="n_customer_mobile" class="form-label">Customer Mobile *</label>

                <input type="text" id="n_customer_mobile" name="n_customer_mobile"
                    value="{{ old('n_customer_mobile',isset($sale) ? $sale->n_customer_mobile : '') }}"
                    {{isset($viewmode) && $viewmode=='on' ? 'readonly' : '' }}
                    data-message="Please enter Customer Mobile" class="form-control mandatory"
                    placeholder="Enter Customer Mobile">

                <div class="text-danger mt-1 fs-2"></div>
            </div>

            <div class="col-md-6">
                <label for="c_customer_address" class="form-label">Customer Address *</label>

                <input type="text" id="c_customer_address" name="c_customer_address"
                    value="{{ old('c_customer_address',isset($sale) ? $sale->c_customer_address : '') }}"
                    {{isset($viewmode) && $viewmode=='on' ? 'readonly' : '' }}
                    data-message="Please add Customer Address" class="form-control mandatory"
                    placeholder="Customer Address">

                <div class="text-danger mt-1 fs-2"></div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">State</label>

                <select class="form-select mandatory" data-message="Please enter State" id="customer_state"
                    name="n_state_id" {{isset($viewmode) && $viewmode=='on' ? 'disabled' : '' }}>

                    <option value="">Select State</option>

                    @if(isset($states))
                    @foreach($states as $State)
                    <option value="{{$State->n_state_id}}"
                        {{ old('n_state_id', $sale->n_state_id ?? '') == $State->n_state_id ? 'selected' : '' }}>
                        {{$State->name}}
                    </option>
                    @endforeach
                    @endif

                </select>
                <div class="text-danger mt-1 fs-2"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label">District</label>

                <select class="form-select mandatory" data-message="Please enter District" id="customer_district"
                    name="n_district_id" {{isset($viewmode) && $viewmode=='on' ? 'disabled' : '' }}>

                    <option value="">Select District</option>

                    @if(isset($sale->n_district_id))
                    @php
                    $districts = \App\Models\District::where('state_id', $sale->n_state_id)->get();
                    @endphp

                    @foreach($districts as $district)
                    <option value="{{$district->id}}"
                        {{ old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : '' }}>
                        {{$district->district_name}}
                    </option>
                    @endforeach
                    @endif

                </select>
                <div class="text-danger mt-1 fs-2"></div>
            </div>
        </div>

    </div>

    <!-- Section 4: Payment Details -->
    <div class="form-box mb-4">

        <div class="form-section-header mb-3">
            <i class="ti ti-credit-card fs-5"></i>
            Payment Details
        </div>

        <div class="row mb-4 align-items-center">
            <label class="col-md-3 col-form-label fw-semibold">
                Mode of Payment *
            </label>

            <div class="col-md-9 d-flex flex-wrap">
                <div class="payment-option">
                    <input class="form-check-input mandatory mode_of_payment" type="radio" name="c_mode_of_payment"
                        id="cod" value="Cash on delivery" checked
                        {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "cash_on_delivery" ? 'checked' : '' }}>

                    <label for="cod" class="mb-0">
                        <i class="ti ti-truck"></i>
                        Cash on Delivery
                    </label>
                </div>

                <div class="payment-option">
                    <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="upi"
                        value="UPI"
                        {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "UPI" ? 'checked' : '' }}>

                    <label for="upi" class="mb-0">
                        <i class="ti ti-brand-google-pay"></i>
                        UPI
                    </label>
                </div>

                <div class="payment-option">
                    <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="bkd"
                        value="Bank Deposit"
                        {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "Bank Deposit" ? 'checked' : '' }}>

                    <label for="bkd" class="mb-0">
                        <i class="ti ti-building-bank"></i>
                        Bank Deposit
                    </label>
                </div>

                <div class="payment-option">
                    <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="pf"
                        value="Paid to Franchise"
                        {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "Paid to Franchise" ? 'checked' : '' }}>

                    <label for="pf" class="mb-0">
                        <i class="ti ti-cash"></i>
                        Paid to Franchise
                    </label>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-md-4">

                <label class="form-label fw-semibold">
                    Payment Status
                </label>

                <select name="payment_status" id="leadStatus" class="form-select">

                    <option value="">Select Status</option>

                    <option value="pending"
                        {{ old('c_lead_status', $sale->payment_status ?? '') == "pending" ? 'selected' : '' }}>Pending
                    </option>
                    <option value="confirmed"
                        {{ old('c_lead_status', $sale->payment_status ?? '') == "confirmed" ? 'selected' : '' }}>
                        Confirmed</option>

                </select>


            </div>
        </div>

        <!-- Payment Details Extra Fields -->
        <div class="row g-4 mt-1">
            <div class="col-md-4">
                <label class="form-label">
                    Amount to Pay *
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-success fw-bold">₹</span>
                    <input type="text" name="n_amount_to_pay" id="n_amount_to_pay"
                        class="form-control fw-bold text-success" value="" readonly>
                </div>
                <small class="text-muted fs-1 mt-1 d-block">Should match product total: ₹4,250.00</small>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    Transaction ID *
                </label>
                <input type="text" name="c_transaction_id" class="form-control"
                    placeholder="Enter Transaction / UTR / Reference No">
            </div>

            <div class="col-md-4" id="payment_image">
                <label class="form-label">
                    Transaction Proof *
                </label>
                <input type="file" name="payment_image" class="form-control">
            </div>
        </div>

    </div>
    {{--
            <!-- Section 5: Order Status Section -->
            <div class="form-box mb-4">

                <div class="form-section-header mb-3">
                    <i class="ti ti-package fs-5"></i>
                    Order Status
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-md-3 col-form-label fw-semibold">
                        Status <span class="text-danger">*</span>
                    </label>

                    <div class="col-md-9 d-flex flex-wrap">

                        <div class="order-status-option">
                            <input
                                class="form-check-input mandatory order-status"
                                type="radio"
                                name="c_order_status"
                                id="order_status_approved"
                                value="Approved"
                                {{ old('c_order_status', $sale->c_order_status ?? '') == 'Approved' ? 'checked' : '' }}
    >
    <label for="order_status_approved" class="mb-0">
        <i class="ti ti-circle-check text-success me-1"></i> Approved
    </label>
</div>

<div class="order-status-option">
    <input class="form-check-input order-status" type="radio" name="c_order_status" id="order_status_dispatched"
        value="Dispatched" {{ old('c_order_status', $sale->c_order_status ?? '') == 'Dispatched' ? 'checked' : '' }}>
    <label for="order_status_dispatched" class="mb-0">
        <i class="ti ti-truck-loading text-info me-1"></i> Dispatched
    </label>
</div>

<div class="order-status-option">
    <input class="form-check-input order-status" type="radio" name="c_order_status" id="order_status_shipped"
        value="Shipped" {{ old('c_order_status', $sale->c_order_status ?? '') == 'Shipped' ? 'checked' : '' }}>
    <label for="order_status_shipped" class="mb-0">
        <i class="ti ti-truck text-primary me-1"></i> Shipped
    </label>
</div>

<div class="order-status-option">
    <input class="form-check-input order-status" type="radio" name="c_order_status" id="order_status_delivered"
        value="Delivered" {{ old('c_order_status', $sale->c_order_status ?? '') == 'Delivered' ? 'checked' : '' }}>
    <label for="order_status_delivered" class="mb-0">
        <i class="ti ti-package-export text-success me-1"></i> Delivered
    </label>
</div>

<div class="order-status-option">
    <input class="form-check-input order-status" type="radio" name="c_order_status" id="order_status_cancelled"
        value="Cancelled" {{ old('c_order_status', $sale->c_order_status ?? '') == 'Cancelled' ? 'checked' : '' }}>
    <label for="order_status_cancelled" class="mb-0">
        <i class="ti ti-circle-x text-danger me-1"></i> Cancelled
    </label>
</div>

</div>
</div>

</div> --}}

<!-- Section 6: Franchise Details Section -->
<div class="form-box mb-4">

    <div class="form-section-header mb-3">
        <i class="ti ti-map-pin fs-5"></i>
        SPC Organic Clinic / Franchise / Stock Point Details
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <label class="form-label">
                State <span class="text-danger">*</span>
            </label>

            <select class="form-select mandatory" id="franchise_state" name="n_state_id"
                data-message="Please Select State">

                <option value="">Select State</option>

                @if(isset($states))
                @foreach($states as $state)
                <option value="{{ $state->n_state_id }}"
                    {{ old('n_state_id', $sale->n_state_id ?? '') == $state->n_state_id ? 'selected' : '' }}>
                    {{ $state->name }}
                </option>
                @endforeach
                @endif

            </select>

            @error('n_state_id')
            <div class="text-danger mt-1 fs-2">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="state" class="form-label">District</label>
            <select class="form-select mandatory" data-message="Please enter District"
                {{isset($viewmode) && $viewmode=='on' ? 'disabled' : '' }} id="franchise_district" name="n_district_id">
                <option value="" selected>Select District</option>
                @if(isset($sale->n_district_id))
                @php $districts = \App\Models\District::where('state_id', $sale->n_state_id)->get(); @endphp

                @if(isset($districts))
                @foreach($districts as $district)
                <option value="{{$district->id}}"
                    {{ old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : '' }}>
                    {{$district->district_name}}</option>
                @endforeach
                @endif
                @endif

            </select>
            <div class="text-danger mt-1 fs-2"></div>
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <label class="form-label">
                Nearest Franchise
            </label>

            <select class="form-select mandatory" id="franchise" name="nearest_franchise_id"
                data-message="Please enter Nearest Franchise">

                <option value="">
                    Select Franchise
                </option>

                @if(isset($franchises))
                @foreach($franchises as $franchise)
                <option value="{{ $franchise->n_store_id }}"
                    {{ old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : '' }}>
                    {{ $franchise->c_store_name }} ({{ $franchise->c_store_code }})
                </option>
                @endforeach
                @endif

            </select>
        </div>

    </div>

</div>

<!-- Action Buttons -->
<div class="mt-4 d-flex gap-2 flex-wrap">
    @if(isset($viewmode) && $viewmode=="on")
    @can('sales-orders.follow-up')
    <!--Follow-up Button-->
    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc" data-bs-toggle="modal"
        data-bs-target="#followUpModal" data-id="{{ isset($sale) ? Crypt::encryptString($sale->n_sl_no) : '' }}"
        id="followup">Update
        Follow-up</button>
    @endcan
    @can('sales-orders.approval')
    <!--Approval Button-->
    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc" data-bs-toggle="modal"
        data-bs-target="#approveModal" data-bs-dismiss="modal" data-id="{{ Crypt::encryptString($sale->n_sl_no) }}">
        Approve
    </button>
    @endcan
    @if(isset($sale) && $sale->n_sl_no)
    {{-- Always available: Preview --}}
    <a href="{{ route('admin.invoice-orders.preview', $sale->n_sl_no) }}" class="btn mt-1 buttonSpc">
        Order Summary Preview
    </a>

    <a href="{{route('admin.invoice.download', $sale->n_sl_no)}}"><button type="button" class="btn buttonSpc"
            style="height:61px;margin-top: 4px;">Download Invoice</button></a>
    @endif
    @else
    <button type="button" class="btn buttonSpc" style="width:150px;position:relative;"
        id="btn_create">{{isset($sale->n_sl_no) ? 'Update' : 'Create'}}</button>
    <a href="{{ route('admin.salesorders.index') }}" class="btn btn-outline-secondary">Cancel</a>
    @endif
</div>

</form>

</div>



</div>
</div>

<!-- Payment Details Extra Fields -->
<div class="row g-4 mt-1">
    <div class="col-md-4">
        <label class="form-label">
            Amount to Pay *
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-success fw-bold">₹</span>
            <input type="text" name="n_amount_to_pay" id="n_amount_to_pay" class="form-control fw-bold text-success"
                value="" readonly>
        </div>
        <small class="text-muted fs-1 mt-1 d-block">Should match product total: ₹4,250.00</small>
    </div>

    <div class="col-md-4">
        <label class="form-label">
            Transaction ID *
        </label>
        <!-- <input type="text" name="c_transaction_id" class="form-control"
                    placeholder="Enter Transaction / UTR / Reference No"> -->
        <input type="text" name="c_transaction_id" id="c_transaction_id" class="form-control"
            value="{{ old('c_transaction_id', $sale->c_transaction_id ?? '') }}"
            placeholder="Enter Transaction / UTR / Reference No">
    </div>

    <div class="col-md-4" id="payment_image">
        <label class="form-label">
            Transaction Proof *
        </label>
        <!-- <input type="file" name="payment_image" class="form-control"> -->
        <input type="file" name="payment_image" id="payment_image_input" class="form-control"
            accept=".jpg,.jpeg,.png,.webp">
    </div>
</div>

</div>

<!-- Section 5: Order Status Section -->
<div class="form-box mb-4">

    <div class="form-section-header mb-3">
        <i class="ti ti-package fs-5"></i>
        Order Status
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-3 col-form-label fw-semibold">
            Status <span class="text-danger">*</span>
        </label>

        <div class="col-md-9 d-flex flex-wrap">

            <div class="order-status-option">
                <input class="form-check-input mandatory order-status" type="radio" name="c_order_status"
                    id="order_status_approved" value="Approved"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Approved' ? 'checked' : '' }}>
                <label for="order_status_approved" class="mb-0">
                    <i class="ti ti-circle-check text-success me-1"></i> Approved
                </label>
            </div>

            <div class="order-status-option">
                <input class="form-check-input order-status" type="radio" name="c_order_status"
                    id="order_status_dispatched" value="Dispatched"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Dispatched' ? 'checked' : '' }}>
                <label for="order_status_dispatched" class="mb-0">
                    <i class="ti ti-truck-loading text-info me-1"></i> Dispatched
                </label>
            </div>

            <div class="order-status-option">
                <input class="form-check-input order-status" type="radio" name="c_order_status"
                    id="order_status_shipped" value="Shipped"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Shipped' ? 'checked' : '' }}>
                <label for="order_status_shipped" class="mb-0">
                    <i class="ti ti-truck text-primary me-1"></i> Shipped
                </label>
            </div>

            <div class="order-status-option">
                <input class="form-check-input order-status" type="radio" name="c_order_status"
                    id="order_status_delivered" value="Delivered"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Delivered' ? 'checked' : '' }}>
                <label for="order_status_delivered" class="mb-0">
                    <i class="ti ti-package-export text-success me-1"></i> Delivered
                </label>
            </div>

            <div class="order-status-option">
                <input class="form-check-input order-status" type="radio" name="c_order_status"
                    id="order_status_cancelled" value="Cancelled"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Cancelled' ? 'checked' : '' }}>
                <label for="order_status_cancelled" class="mb-0">
                    <i class="ti ti-circle-x text-danger me-1"></i> Cancelled
                </label>
            </div>

        </div>
    </div>

</div>

<!-- Section 6: Franchise Details Section -->
<div class="form-box mb-4">

    <div class="form-section-header mb-3">
        <i class="ti ti-map-pin fs-5"></i>
        SPC Organic Clinic / Franchise / Stock Point Details
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <label class="form-label">
                State <span class="text-danger">*</span>
            </label>

            <select class="form-select mandatory" id="franchise_state" name="n_state_id"
                data-message="Please Select State">

                <option value="">Select State</option>

                @if(isset($states))
                @foreach($states as $state)
                <option value="{{ $state->n_state_id }}"
                    {{ old('n_state_id', $sale->n_state_id ?? '') == $state->n_state_id ? 'selected' : '' }}>
                    {{ $state->name }}
                </option>
                @endforeach
                @endif

            </select>

            @error('n_state_id')
            <div class="text-danger mt-1 fs-2">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="state" class="form-label">District</label>
            <select class="form-select mandatory" data-message="Please enter District"
                {{isset($viewmode) && $viewmode=='on' ? 'disabled' : '' }} id="franchise_district" name="n_district_id">
                <option value="" selected>Select District</option>
                @if(isset($sale->n_district_id))
                @php $districts = \App\Models\District::where('state_id', $sale->n_state_id)->get(); @endphp

                @if(isset($districts))
                @foreach($districts as $district)
                <option value="{{$district->id}}"
                    {{ old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : '' }}>
                    {{$district->district_name}}</option>
                @endforeach
                @endif
                @endif

            </select>
            <div class="text-danger mt-1 fs-2"></div>
        </div>

        {{-- Panchayath --}}
        <div class="col-md-6">
            <label class="form-label">
                Panchayath
            </label>

            <select class="form-select" id="franchise_panchayath" name="n_panchayath_id">

                <option value="">Select Panchayath</option>

            </select>
        </div>


        <div class="col-md-6">
            <label class="form-label">
                Nearest Franchise
            </label>

            <select class="form-select mandatory" id="franchise" name="nearest_franchise_id"
                data-message="Please enter Nearest Franchise">

                <option value="">
                    Select Franchise
                </option>

                @if(isset($franchises))
                @foreach($franchises as $franchise)
                <option value="{{ $franchise->n_store_id }}"
                    {{ old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : '' }}>
                    {{ $franchise->c_store_name }} ({{ $franchise->c_store_code }})
                </option>
                @endforeach
                @endif

            </select>
        </div>

    </div>
</div>

<!-- Action Buttons -->
<div class="mt-4 d-flex gap-2 flex-wrap">
    @if(isset($viewmode) && $viewmode=="on")
    @can('sales-orders.follow-up')
    <!--Follow-up Button-->
    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc" data-bs-toggle="modal"
        data-bs-target="#followUpModal" data-id="{{ isset($sale) ? Crypt::encryptString($sale->n_sl_no) : '' }}"
        id="followup">Update
        Follow-up</button>
    @endcan
    @can('sales-orders.approval')
    <!--Approval Button-->

    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc" data-bs-toggle="modal"
        data-bs-target="#approveModal" data-bs-dismiss="modal" data-id="{{ Crypt::encryptString($sale->n_sl_no) }}">
        Approve
    </button>
    @endcan
    <!-- @if(isset($sale) && $sale->n_sl_no)
        <a href="{{route('admin.invoice-orders.preview', $sale->n_sl_no)}}"><button type="button"
                class="btn mt-1 buttonSpc" id="btn_create">Order Summary Preview</button></a>
        <a href="{{route('admin.invoice.download', $sale->n_sl_no)}}"><button type="button" class="btn mt-1 buttonSpc"
                id="btn_create">Download Invoice</button></a>
        @endif -->

    @if(isset($sale) && $sale->n_sl_no)

    {{-- Always available: Preview --}}
    <a href="{{ route('admin.invoice-orders.preview', $sale->n_sl_no) }}" class="btn mt-1 buttonSpc">
        Order Summary Preview
    </a>

    {{-- Available only after approval --}}
    @if($sale->c_order_status === 'Approved')
    <a href="{{ route('admin.invoice.download', $sale->n_sl_no) }}" class="btn mt-1 buttonSpc">
        Download Invoice
    </a>
    @endif

    @endif
    @else
    <button type="submit" class="btn buttonSpc" id="btn_create">{{isset($sale->n_sl_no) ? 'Update' : 'Create'}}</button>
    <a href="{{ route('admin.salesorders.index') }}" class="btn btn-outline-secondary">Cancel</a>
    @endif
</div>

</form>
</div>
</div>

<!-- Follow-up Modal -->
<div class="modal fade" id="followUpModal" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.salesorders.followup.store') }}" method="POST">
                @csrf

                <div class="modal-header" style="background: linear-gradient(135deg, #0f5132, #074E30);">
                    <h5 class="modal-title text-white" id="followUpModalLabel">
                        Order Follow-up Form
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="n_sale_id" value="{{ $sale->n_sl_no ?? '' }}">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" name="d_followup_date" class="form-control" required>
                        </div>

                        @if(isset($user) && $user->identifier != "FCA")
                        {{-- <div class="col-md-6 mb-3">
                            <label class="form-label">Follow-up Type</label>
                            <select name="followup_type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Phone Call">Phone Call</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Site Visit">Site Visit</option>
                            </select>
                        </div> --}}

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Order Status</label>
                            <select name="c_order_status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="dispatched">Dispatched</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                        @endif

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="4"
                                placeholder="Enter follow-up remarks..." required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn buttonSpc">
                        Save Follow-up
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="approveForm" action="{{ route('admin.salesorders.approval.save') }}">

                @csrf
                @method('PUT')


                <div class="modal-content">

                    <div class="modal-header" style="background: linear-gradient(135deg, #0f5132, #074E30);"">

                    <h5 class=" modal-title text-white" id="approveModalLabel">
                        Approval
                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" id="approval_id">

                        <div class="mb-3">

                            <label class="form-label">
                                Remarks <span class="text-danger">*</span>
                            </label>

                            <textarea class="form-control" name="remarks" id="approval_remarks" rows="3"
                                required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Approval Status
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" name="status" id="approval_status" required>

                                <option value="">
                                    Select Status
                                </option>

                                <option value="Approved">
                                    Approve
                                </option>

                                <option value="Rejected">
                                    Reject
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn buttonSpc" id="approvalSubmit">
                            Submit
                        </button>

                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                    </div>

                </div>

            </form>



        </div>
    </div>
</div>


<!-- Approval Modal -->
{{-- <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="approveForm" action="{{ route('admin.salesorders.approval.save') }}">
@csrf
@method('PUT')
<div class="modal-content">
    <div class="modal-header" style="background: linear-gradient(135deg, #0f5132, #074E30);">
        <h5 class="modal-title text-white" id="approveModalLabel">Approval</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="id" id="approval_id">
        <div class="mb-3">
            <label class="form-label">Remarks <span class="text-danger">*</span></label>
            <textarea class="form-control" name="remarks" id="approval_remarks" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Approval Status <span class="text-danger">*</span></label>
            <select class="form-select" name="status" id="approval_status" required>
                <option value="">Select Status</option>
                <option value="Approved">Approve</option>
                <option value="Rejected">Reject</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn buttonSpc" id="approvalSubmit">Submit</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
</div>
</form>
</div>
</div> --}}
@php
$hasPaymentImage = isset($sale) && !empty($sale->payment_image);
@endphp

@endsection

@push('scripts')

<script>
$(document).ready(function() {

    console.log("Sales Order JS loaded");

    /*
    |--------------------------------------------------------------------------
    | Product Rows
    |--------------------------------------------------------------------------
    */

    let rowIndex = $('#productTable tbody tr').length;

    /*
    |--------------------------------------------------------------------------
    | Add New Product
    |--------------------------------------------------------------------------
    */

    $('#addRow').on('click', function() {

        let row = `
                <tr>

                    <!-- Product -->
                    <td>
                        <select
                            name="products[${rowIndex}][product_id]"
                            class="form-control product mandatory"
                            data-message="Please Select Product">

                            <option value="">Select Product</option>

                            @foreach($products as $product)
                                <option
                                    value="{{ $product->n_product_id }}"
                                    data-price="{{ $product->n_mrp }}"
                                    data-gst="{{ $product->n_gst_percentage }}"
                                    data-hsnCode="{{ $product->c_hsn_code }}"
                                    data-unit="{{ $product->c_unit }}">

                                    {{ $product->c_product_name }}
                                    ({{ $product->c_product_code }})
                                    ({{ $product->c_unit }})

                                </option>
                            @endforeach

                        </select>

                        <div class="text-danger mt-1 fs-2"></div>
                    </td>

                    <!-- HSN Code -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][c_hsn_code]"
                            class="form-control c_hsn_code"
                            value="{{$product->c_hsn_code}}"
                            readonly>
                    </td>


                    <!-- Price -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][product_price]"
                            class="form-control price"
                            readonly>
                    </td>


                    <!-- Quantity -->
                    <td>
                        <input
                            type="number"
                            name="products[${rowIndex}][qty]"
                            class="form-control qty"
                            value="1"
                            min="1">
                    </td>


                    <!-- Unit -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][c_unit]"
                            class="form-control c_unit"
                            readonly>
                    </td>


                    <!-- Discount -->
                    <td>
                        <input
                            type="number"
                            name="products[${rowIndex}][discount]"
                            class="form-control discount"
                            value="0.00"
                            step="0.01"
                            min="0">
                    </td>


                    <!-- GST % -->
                    <td>
                        <input
                            type="number"
                            name="products[${rowIndex}][n_gst_percentage]"
                            class="form-control gst_percentage"
                            value="0.00"
                            step="0.01"
                            readonly>
                    </td>


                    <!-- GST Amount -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][gst_amount]"
                            class="form-control gst_amount"
                            value="0.00"
                            readonly>
                    </td>

                    <!-- Discounted Price -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][discounted_price]"
                            class="form-control discounted_price"
                            value="0.00"
                            readonly>
                    </td>

                    <!-- Product Total -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][product_total]"
                            class="form-control total"
                            value="0.00"
                            readonly>
                    </td>


                    <!-- Remove -->
                    <td class="text-center">
                        <button
                            type="button"
                            class="btn btn-danger btn-sm removeRow">

                            <i class="ti ti-trash"></i>

                        </button>
                    </td>

                </tr>
            `;

        $('#productTable tbody').append(row);

        rowIndex++;

        console.log(
            "Product row added. Current rowIndex:",
            rowIndex
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Product Selection - Calculate Price & Total
    |--------------------------------------------------------------------------
    */

    $(document).on('change', '.product', function() {
        let product = $(this);
        productTotal(product);
    });


    /*
    |--------------------------------------------------------------------------
    | Quantity Change & Discount Change - Recalculate Total
    |--------------------------------------------------------------------------
    */

    $(document).on('input change', '.qty, .discount', function() {
        let row = $(this).closest('tr');
        let product = row.find('.product');
        productTotal(product);
    });


    /*
    |--------------------------------------------------------------------------
    | Product Total Function
    |--------------------------------------------------------------------------
    */

    function productTotal(productSelect) {

        let row = productSelect.closest('tr');

        let selectedOption = productSelect.find(':selected');

        // Price from product
        let mrp = parseFloat(
            selectedOption.attr('data-price')
        ) || parseFloat(row.find('.price').val()) || 0;

        let hsnCode = selectedOption.attr('data-hsnCode');
        let unit = selectedOption.attr('data-unit');

        // GST percentage from product
        let gstPercentage = parseFloat(
            selectedOption.attr('data-gst')
        ) || parseFloat(row.find('.gst_percentage').val()) || 0;

        let gstAmount = mrp - (mrp / (1 + gstPercentage / 100));

        let qty = parseFloat(row.find('.qty').val()) || 0;

        let discount = parseFloat(row.find('.discount').val()) || 0;

        // Product-wise GST
        let price = mrp - gstAmount;

        // Gross amount
        let grossAmount = price * qty;



        // Amount after discount
        let taxableAmount = grossAmount - discount;

        if (taxableAmount < 0) {
            taxableAmount = 0;
        }


        // Product total including GST
        let lineTotal = taxableAmount + gstAmount;


        // Set values
        row.find('.c_hsn_code').val(
            hsnCode
        );

        row.find('.price').val(
            price.toFixed(2)
        );

        row.find('.c_unit').val(
            unit
        );

        row.find('.gst_percentage').val(
            gstPercentage.toFixed(2)
        );

        row.find('.gst_amount').val(
            gstAmount.toFixed(2)
        );

        row.find('.discounted_price').val(
            taxableAmount.toFixed(2)
        );

        row.find('.total').val(
            lineTotal.toFixed(2)
        );


        calculateSummary();
    }

    function calculateSummary() {

        let totalSales = 0;
        let productDiscount = 0;
        let totalGst = 0;

        $('#productTable tbody tr').each(function() {

            let row = $(this);

            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;
            let gstPercentage =
                parseFloat(row.find('.gst_percentage').val()) || 0;

            // Gross product amount
            let grossAmount = price * qty;

            // Taxable amount for this product
            let productTaxableAmount = grossAmount - discount;

            if (productTaxableAmount < 0) {
                productTaxableAmount = 0;
            }

            // Product GST
            let gstAmount =
                productTaxableAmount * gstPercentage / 100;

            // Product total including GST
            let productTotal =
                productTaxableAmount + gstAmount;

            // Set product GST
            row.find('.gst_amount').val(
                gstAmount.toFixed(2)
            );

            // Set product total
            row.find('.total').val(
                productTotal.toFixed(2)
            );

            // Summary
            totalSales += grossAmount;
            productDiscount += discount;
            totalGst += gstAmount;
        });


        // Additional discount
        let additionalDiscount =
            parseFloat(
                $('#summaryAdditionalDiscount').val()
            ) || 0;


        // Total discount
        let totalDiscount =
            productDiscount + additionalDiscount;


        // Taxable amount
        let taxableAmount =
            totalSales - totalDiscount;

        if (taxableAmount < 0) {
            taxableAmount = 0;
        }


        // Net Sales Amount
        let netSalesAmount =
            taxableAmount + totalGst;


        // Display summary
        $('#summaryTotalSales').val(
            totalSales.toFixed(2)
        );

        $('#summaryProductDiscount').val(
            productDiscount.toFixed(2)
        );

        $('#summaryTotalDiscount').val(
            productDiscount.toFixed(2)
        );

        $('#summaryTaxableAmount').val(
            taxableAmount.toFixed(2)
        );

        $('#summaryGstAmount').val(
            totalGst.toFixed(2)
        );

        $('#summaryNetSales').val(
            netSalesAmount.toFixed(2)
        );

        $('#n_amount_to_pay').val(
            netSalesAmount.toFixed(2)
        );

    }

    $(document).on(
        'input',
        '#summaryTotalDiscount',
        function() {
            calculateSummary();
        }
    );
    /*
    |--------------------------------------------------------------------------
    | Remove Product Row
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        calculateSummary();
    });


    /*
    |--------------------------------------------------------------------------
    | Customer Selection
    |--------------------------------------------------------------------------
    */

    $('#n_customer_id').on('change', function() {

        let option = $(this).find(':selected');

        let email = option.data('email') || '';
        let mobile = option.data('mobile') || '';
        let address = option.data('address') || '';

        let stateId = option.data('state') || '';
        let districtId = option.data('district') || '';
        let customer_name = option.data('name') || '';

        $('#c_customer_email').val(email);
        $('#n_customer_mobile').val(mobile);
        $('#c_customer_address').val(address);

        $('#customer_state').val(stateId);
        $("#c_customer_name").val(customer_name);

        /*
        |--------------------------------------------------------------------------
        | Load Customer Districts
        |--------------------------------------------------------------------------
        */

        if (!stateId) {
            $('#customer_district').html('<option value="">Select District</option>');
            return;
        }

        $.ajax({
            type: 'GET',
            url: "{{ route('admin.filterDistrict') }}",
            data: {
                state: stateId
            },
            dataType: 'json',
            beforeSend: function() {
                $('#customer_district').html('<option value="">Loading...</option>');
            },
            success: function(data) {
                $('#customer_district').html('<option value="">Select District</option>');
                if (data.districts) {
                    $.each(data.districts, function(index, district) {
                        $('#customer_district').append(
                            '<option value="' + district.id + '">' + district
                            .district_name + '</option>'
                        );
                    });
                }
                $('#customer_district').val(districtId);
            },
            error: function(xhr) {
                console.error('Customer district loading failed:', xhr.responseText);
                $('#customer_district').html(
                    '<option value="">Unable to load districts</option>');
            }
        });
    });



    /*
    |--------------------------------------------------------------------------
    | Approval Modal
    |--------------------------------------------------------------------------
    */

    const approveModalEl = document.getElementById('approveModal');
    if (approveModalEl) {
        approveModalEl.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (!button) return;
            const id = button.getAttribute('data-id');
            console.log('Approval ID:', id);
            document.getElementById('approval_id').value = id;
            document.getElementById('approval_remarks').value = '';
            document.getElementById('approval_status').value = '';
        });
    }

    $(document).on('click', '.approvalSubmit', function() {
        const id = $(this).attr('data-id');
        console.log('Encrypted ID:', id);
        $('#approval_id').val(id);
        $('#approvalForm').attr('action', "{{ route('admin.salesorders.approval.save') }}");
        $('#approval_remarks').val('');
        $('#approval_status').val('');
    });

    /*
    |--------------------------------------------------------------------------
    | Existing Product Rows Calculation
    |--------------------------------------------------------------------------
    */

    $('#productTable tbody .product').each(function() {
        if ($(this).val()) {
            productTotal($(this));
        }
    });

});
</script>

<script>
/*
|--------------------------------------------------------------------------
| FRANCHISE LOCATION: State → District
|--------------------------------------------------------------------------
*/

$('#franchise_state').on('change', function() {

    let stateId = $(this).val();

    $('#franchise_district').html(
        '<option value="">Loading...</option>'
    );

    $('#franchise_panchayath').html(
        '<option value="">Select Panchayath</option>'
    );

    $('#franchise').html(
        '<option value="">Select Franchise</option>'
    );

    if (!stateId) {
        $('#franchise_district').html(
            '<option value="">Select District</option>'
        );
        return;
    }

    $.ajax({
        type: 'GET',
        url: "{{ route('admin.filterDistrict') }}",
        data: {
            state: stateId
        },
        dataType: 'json',

        success: function(response) {

            $('#franchise_district').html(
                '<option value="">Select District</option>'
            );

            if (response.districts) {

                $.each(response.districts, function(index, district) {

                    $('#franchise_district').append(
                        '<option value="' + district.id + '">' +
                        district.district_name +
                        '</option>'
                    );

                });
            }
        },

        error: function(xhr) {
            console.error('District AJAX Error:', xhr.responseText);

            $('#franchise_district').html(
                '<option value="">Unable to load districts</option>'
            );
        }
    });
});


/*
|--------------------------------------------------------------------------
| FRANCHISE LOCATION: District → Panchayath
|--------------------------------------------------------------------------
*/

$('#franchise_district').on('change', function() {

    let districtId = $(this).val();

    $('#franchise_panchayath').html(
        '<option value="">Loading...</option>'
    );

    $('#franchise').html(
        '<option value="">Select Franchise</option>'
    );

    if (!districtId) {

        $('#franchise_panchayath').html(
            '<option value="">Select Panchayath</option>'
        );

        return;
    }

    $.ajax({
        type: 'GET',
        url: "{{ route('admin.filterPanchayath') }}",
        data: {
            district: districtId
        },
        dataType: 'json',

        success: function(response) {

            console.log('Panchayath response:', response);

            $('#franchise_panchayath').html(
                '<option value="">Select Panchayath</option>'
            );

            if (
                response.panchayaths &&
                response.panchayaths.length > 0
            ) {

                $.each(response.panchayaths, function(index, panchayat) {

                    $('#franchise_panchayath').append(
                        '<option value="' + panchayat.id + '">' +
                        panchayat.panchayath_name +
                        '</option>'
                    );

                });

            } else {

                $('#franchise_panchayath').html(
                    '<option value="">No Panchayaths Found</option>'
                );
            }
        },

        error: function(xhr) {

            console.error(
                'Panchayath AJAX Error:',
                xhr.responseText
            );

            $('#franchise_panchayath').html(
                '<option value="">Unable to load Panchayaths</option>'
            );
        }
    });
});


/*
|--------------------------------------------------------------------------
| FRANCHISE LOCATION: Panchayath → Franchise
|--------------------------------------------------------------------------
*/

$('#franchise_panchayath').on('change', function() {

    let stateId = $('#franchise_state').val();
    let districtId = $('#franchise_district').val();
    let panchayathId = $(this).val();

    console.log('Loading franchises:', {
        state: stateId,
        district: districtId,
        panchayath: panchayathId
    });

    $('#franchise').html(
        '<option value="">Loading franchises...</option>'
    );

    if (!stateId || !districtId || !panchayathId) {

        $('#franchise').html(
            '<option value="">Select Franchise</option>'
        );

        return;
    }

    $.ajax({
        type: 'GET',

        url: "{{ url('admin/filter-franchise') }}",

        data: {
            state: stateId,
            district: districtId,
            panchayath: panchayathId
        },

        dataType: 'json',

        success: function(response) {

            console.log('Franchise response:', response);

            $('#franchise').html(
                '<option value="">Select Franchise</option>'
            );

            if (
                response.franchises &&
                response.franchises.length > 0
            ) {

                $.each(response.franchises, function(index, franchise) {

                    $('#franchise').append(
                        '<option value="' +
                        franchise.n_store_id +
                        '">' +
                        franchise.c_store_name +
                        ' (' +
                        franchise.c_store_code +
                        ')' +
                        '</option>'
                    );

                });

            } else {

                $('#franchise').html(
                    '<option value="">No Franchises Found</option>'
                );

                console.log('No franchises found');
            }
        },

        error: function(xhr, status, error) {

            console.error('Franchise AJAX failed');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);

            $('#franchise').html(
                '<option value="">Unable to load franchises</option>'
            );
        }
    });

});

/*
|--------------------------------------------------------------------------
| Payment Status - Transaction ID & Proof
|--------------------------------------------------------------------------
*/
function togglePaymentFields() {
    let status = $('#paymentStatus').val();

    let transactionId = $('#c_transaction_id');
    let paymentImage = $('#payment_image_input');

    if (status === 'confirmed') {
        // Enable fields
        transactionId.prop('disabled', false);
        paymentImage.prop('disabled', false);

        // Make transaction ID required
        transactionId.prop('required', true);

        // Show enabled styling
        transactionId.removeClass('bg-light');
        paymentImage.removeClass('bg-light');

    } else {
        // Disable fields
        transactionId.prop('disabled', true);
        paymentImage.prop('disabled', true);

        // Remove required
        transactionId.prop('required', false);

        // Clear transaction ID when pending
        transactionId.val('');

        // Clear file input
        paymentImage.val('');

        // Disabled styling
        transactionId.addClass('bg-light');
        paymentImage.addClass('bg-light');
    }
}

// When Payment Status changes
$('#paymentStatus').on('change', function() {
    togglePaymentFields();
});

// Run on page load
togglePaymentFields();
</script>

@endpush