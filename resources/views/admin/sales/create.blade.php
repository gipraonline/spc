@extends('layouts.app')
@push('styles')
<style>
.order-number {
    background: #f5f5f5;
    font-weight: bold;
    color: #086a0e;
}

.form-section {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    background: #fff;
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    color: #086a0e;
    border-bottom: 1px solid #eee;
    padding-bottom: 12px;
}

.form-box {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    background: #ffffff;
}

#approveModal {
    z-index: 1060 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

.form-section-header {
    font-size: 16px;
    font-weight: 600;
    color: #086a0e;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 20px;
}

.payment-option {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px 18px;
    margin-right: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.payment-option:hover {
    background: #f8f9fa;
}

.payment-option input[type="radio"] {
    margin: 0;
}

.payment-option input[type="radio"]:checked+label {
    color: #087f23;
    font-weight: 600;
}



.order-status-option {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px 17px;
    margin-right: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.order-status-option:hover {
    background: #f8f9fa;
}

.order-status-option input[type="radio"] {
    margin: 0;
}

.order-status-option input[type="radio"]:checked+label {
    color: #087f23;
    font-weight: 600;
}



.advisor-highlight {

    /* Light yellow */
    background: #f5f5f5;
     !important;
    color: #087f23;
     !important;
    font-weight: 700;

}

.advisor-highlight:focus {

    border-color: #087f23;
     !important;

}
</style>
@endpush
@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
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

                <!-- Row 1 -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="d_date" class="form-control mandatory"
                            data-message="Please Select a Date"
                            value="{{ old('d_date', isset($sale) ? $sale->d_date->format('Y-m-d') : '') }}"
                            {{isset($viewmode) && $viewmode=='on' ? 'readonly' : '' }}>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">Booklet Serial No *</label>

                        <input type="text" name="c_order_no" class="form-control mandatory order-number"
                            data-message="Please Enter Order No"
                            value="{{ old('c_order_no', isset($sale) ? $sale->c_order_no : '') }}"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'readonly' : '' }}>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                </div>


                <!-- Row 2: Farm Care Advisor -->
                <div class="row">

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

                </div>

            </div>
            <!-- Row 3 : Product Details -->

            <div class="form-section mb-4">

                <div class="section-title d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <i class="ti ti-shopping-cart fs-5"></i>
                        Product Details *
                    </div>


                    @if(isset($viewmode) && $viewmode=='off')

                    <button type="button" class="btn buttonSpc btn-sm" id="addRow">
                        <i class="ti ti-plus"></i>
                        Add New Product
                    </button>

                    @endif

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered table-responsive align-middle" id="productTable">

                        <thead class="table-light">

                            <tr>
                                <th width="45%">Product</th>
                                <th width="15%">Price</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Total</th>
                                <th width="10%">Action</th>
                            </tr>

                        </thead>


                        <tbody>

                            @if(isset($sale->orderProducts))

                            @foreach($sale->orderProducts as $key=>$val)

                            <tr>

                                <td>
                                    <select name="products[{{ $key }}][product_id]"
                                        class="form-control product mandatory">

                                        <option value="">
                                            Select Product
                                        </option>

                                        @foreach($products as $product)

                                        <option value="{{ $product->n_product_id }}"
                                            {{-- data-price="{{ $product->n_selling_price }}" --}}
                                            data-price="{{ $product->n_mrp }}"
                                            {{ $val->product_id == $product->n_product_id ? 'selected' : '' }}>

                                            {{ $product->c_product_name }}

                                        </option>

                                        @endforeach

                                    </select>
                                </td>


                                <td>
                                    <input type="text" name="products[{{ $key }}][product_price]"
                                        class="form-control price" value="{{ $val->product_price }}" readonly>
                                </td>


                                <td>
                                    <input type="number" name="products[{{ $key }}][qty]" class="form-control qty"
                                        value="{{ $val->qty }}">
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

                        <select class="form-select mandatory" data-message="Please enter District"
                            id="customer_district" name="n_district_id"
                            {{isset($viewmode) && $viewmode=='on' ? 'disabled' : '' }}>

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
            <div class="form-box">

                <div class="form-section-header mb-3">
                    <i class="ti ti-credit-card fs-5"></i>
                    Payment Details
                </div>



                <!-- Payment Details Section -->
                <div class="form-box mb-4">

                    <div class="form-section-header mb-3">
                        <i class="ti ti-wallet fs-5"></i>
                        Payment Details
                    </div>

                    <div class="row mb-3 align-items-center">

                        <label class="col-md-3 col-form-label fw-semibold">
                            Mode of Payment *
                        </label>

                        <div class="col-md-9">

                            <div class="payment-option">
                                <input class="form-check-input mandatory mode_of_payment" type="radio"
                                    name="c_mode_of_payment" id="cod" value="Cash on delivery"
                                    {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "cash_on_delivery" ? 'checked' : '' }}>

                                <label for="cod" class="mb-0">
                                    <i class="ti ti-truck"></i>
                                    Cash on Delivery
                                </label>
                            </div>


                            <div class="payment-option">
                                <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment"
                                    id="upi" value="UPI"
                                    {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "UPI" ? 'checked' : '' }}>

                                <label for="upi" class="mb-0">
                                    <i class="ti ti-brand-google-pay"></i>
                                    UPI
                                </label>
                            </div>

                            <div class="payment-option">
                                <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment"
                                    id="bkd" value="Bank Deposit"
                                    {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "Bank Deposit" ? 'checked' : '' }}>

                                <label for="bkd" class="mb-0">
                                    <i class="ti ti-building-bank"></i>
                                    Bank Deposit
                                </label>
                            </div>
                            <div class="payment-option">
                                <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment"
                                    id="pf" value="Paid to Franchise"
                                    {{ old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "Paid to Franchise" ? 'checked' : '' }}>

                                <label for="pf" class="mb-0">
                                    <i class="ti ti-cash"></i>
                                    Paid to Franchise
                                </label>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- order status section -->
                <div class="form-box mb-4">

    <div class="form-section-header mb-3">
        <i class="ti ti-package fs-5"></i>
        Order Status
    </div>

    <div class="row mb-3 align-items-center">

        <label class="col-md-3 col-form-label fw-semibold">
            Status <span class="text-danger">*</span>
        </label>

        <div class="col-md-9">

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
                    <i class="ti ti-circle-check text-success"></i>
                    Approved
                </label>
            </div>

            <div class="order-status-option">
                <input
                    class="form-check-input order-status"
                    type="radio"
                    name="c_order_status"
                    id="order_status_dispatched"
                    value="Dispatched"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Dispatched' ? 'checked' : '' }}
                >

                <label for="order_status_dispatched" class="mb-0">
                    <i class="ti ti-truck-loading text-info"></i>
                    Dispatched
                </label>
            </div>

            <div class="order-status-option">
                <input
                    class="form-check-input order-status"
                    type="radio"
                    name="c_order_status"
                    id="order_status_shipped"
                    value="Shipped"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Shipped' ? 'checked' : '' }}
                >

                <label for="order_status_shipped" class="mb-0">
                    <i class="ti ti-truck text-primary"></i>
                    Shipped
                </label>
            </div>

            <div class="order-status-option">
                <input
                    class="form-check-input order-status"
                    type="radio"
                    name="c_order_status"
                    id="order_status_delivered"
                    value="Delivered"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Delivered' ? 'checked' : '' }}
                >

                <label for="order_status_delivered" class="mb-0">
                    <i class="ti ti-package-export text-success"></i>
                    Delivered
                </label>
            </div>

            <div class="order-status-option">
                <input
                    class="form-check-input order-status"
                    type="radio"
                    name="c_order_status"
                    id="order_status_cancelled"
                    value="Cancelled"
                    {{ old('c_order_status', $sale->c_order_status ?? '') == 'Cancelled' ? 'checked' : '' }}
                >

                <label for="order_status_cancelled" class="mb-0">
                    <i class="ti ti-circle-x text-danger"></i>
                    Cancelled
                </label>
            </div>

        </div>
    </div>

</div>

                <!-- Franchise Details Section -->
                <div class="form-box">

                    <div class="form-section-header mb-3">
                        <i class="ti ti-map-pin fs-5"></i>
                        Franchise Details
                    </div>


                    <div class="row g-4 mb-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                State <span class="text-danger">*</span>
                            </label>

                            <select class="form-select mandatory" id="franchise_state" name="n_state_id"
                                data-message="Please Select State">

                                <option value="">Select State</option>

                                @foreach($states as $state)

                                <option value="{{ $state->n_state_id }}"
                                    {{ old('n_state_id', $sale->n_state_id ?? '') == $state->n_state_id ? 'selected' : '' }}>

                                    {{ $state->name }}

                                </option>

                                @endforeach

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
                                {{isset($viewmode) && $viewmode=='on' ? 'disabled' : '' }} id="franchise_district"
                                name="n_district_id">
                                <option value="" selected>Select District</option>
                                @if(isset($sale->n_district_id))
                                @php $districts = \App\Models\District::where('state_id', $sale->n_state_id)->get();
                                @endphp
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

                        <div class="col-md-6" style="position:relative;display:none;" id="payment_image">

                            <label class="form-label">
                                Image
                            </label>
                            <input type="file" name="payment_image">
                        </div>

                    </div>





                </div>

                <!-- Buttons -->
                <div class="mt-3 d-flex gap-2">
                    @if(isset($viewmode) && $viewmode=="on")
                    @can('sales-orders.follow-up')
                    <!--Follow-up Button-->
                    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc"
                        data-bs-toggle="modal" data-bs-target="#followUpModal"
                        data-id="{{ isset($sale) ? Crypt::encryptString($sale->n_sl_no) : '' }}" id="followup">Update
                        Follow-up</button>
                    @endcan
                    @can('sales-orders.approval')
                    <!--Approval Button-->
                    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc"
                        data-bs-toggle="modal" data-bs-target="#approveModal" data-bs-dismiss="modal"
                        data-id="{{ Crypt::encryptString($sale->n_sl_no) }}">
                        Approve
                    </button>
                    @endcan
                    @if(isset($sale) && $sale->n_sl_no)
                    <a href="{{route('admin.invoice.download', $sale->n_sl_no)}}"><button type="button"
                            class="btn mt-1 buttonSpc" id="btn_create">Download Invoice</button></a>
                    @endif
                    @else
                    <button type="button" class="btn mt-1 buttonSpc"
                        id="btn_create">{{isset($sale->n_sl_no) ? 'Update' : 'Create'}}</button>
                    <a href="{{ route('admin.salesorders.index') }}" class="btn btn-outline-secondary">Cancel</a>

                    {{-- <a href="{{route('')}}" class="btn buttonSpc">Download Invoice</a> --}}
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

                <div class="modal-header">
                    <h5 class="modal-title text-white" id="followUpModalLabel">
                        Lead Follow-up Form
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="lead_id" value="{{ $lead->id ?? '' }}">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" name="followup_date" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Next Follow-up Date</label>
                            <input type="date" name="next_followup_date" class="form-control">
                        </div>

                        @if(isset($user) && $user->identifier != "FCA")
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Follow-up Type</label>
                            <select name="followup_type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Phone Call">Phone Call</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Site Visit">Site Visit</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lead Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Interested">Interested</option>
                                <option value="Negotiation">Negotiation</option>
                                <option value="Won">Won</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option>Low</option>
                                <option selected>Medium</option>
                                <option>High</option>
                                <option>Urgent</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reminder</label>
                            <input type="datetime-local" name="reminder_at" class="form-control">
                        </div>

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

<!--Approval Form modal-->
{{-- <div class="modal fade"
     id="approveModal"
     tabindex="-1"
     aria-labelledby="approveModalLabel"
     aria-hidden="true">

    <div class="modal-dialog">

        <form method="POST"
              id="approveForm"
              action="{{ route('admin.salesorders.approval.save') }}">

@csrf
@method('PUT')

<div class="modal-content">

    <div class="modal-header" style="background: linear-gradient(135deg, #5A8D3A, #074E30);">

        <h5 class="modal-title text-white" id="approveModalLabel">
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

            <textarea class="form-control" name="remarks" id="approval_remarks" rows="3" required></textarea>

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

        <button type="button" class="btn buttonSpc" id="approvalSubmit">
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
--}}


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

    // IMPORTANT:
    // Do not use Blade here.
    // Count existing product rows directly from the table.
    let rowIndex = $('#productTable tbody tr').length;

    /*
    |--------------------------------------------------------------------------
    | Add New Product
    |--------------------------------------------------------------------------
    */

    $('#addRow').on('click', function() {

        let row = `
            <tr>

                <td>
                    <select
                        name="products[${rowIndex}][product_id]"
                        class="form-control product mandatory"
                        data-message="Please Select Product">

                        <option value="">Select Product</option>

                        @foreach($products as $product)
                            <option
                                value="{{ $product->n_product_id }}"
                                data-price="{{ $product->n_mrp }}">
                                {{ $product->c_product_name }} ({{ $product->c_product_code }})({{$product->c_unit}})
                            </option>
                        @endforeach

                    </select>

                    <div class="text-danger mt-1 fs-2"></div>
                </td>

                <td>
                    <input
                        type="text"
                        name="products[${rowIndex}][product_price]"
                        class="form-control price"
                        readonly>
                </td>

                <td>
                    <input
                        type="number"
                        name="products[${rowIndex}][qty]"
                        class="form-control qty"
                        value="1"
                        min="1">
                </td>

                <td>
                    <input
                        type="text"
                        name="products[${rowIndex}][product_total]"
                        class="form-control total"
                        readonly>
                </td>

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

        console.log("Product row added. Current rowIndex:", rowIndex);
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
    | Quantity Change - Recalculate Total
    |--------------------------------------------------------------------------
    */

    $(document).on('input change', '.qty', function() {

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

        let price = parseFloat(selectedOption.attr('data-price')) || 0;

        let qty = parseFloat(row.find('.qty').val()) || 0;

        let total = price * qty;

        row.find('.price').val(price.toFixed(2));

        row.find('.total').val(total.toFixed(2));
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Product Row
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.removeRow', function() {

        $(this).closest('tr').remove();

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

            $('#customer_district').html(
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

            beforeSend: function() {

                $('#customer_district').html(
                    '<option value="">Loading...</option>'
                );

            },

            success: function(data) {

                $('#customer_district').html(
                    '<option value="">Select District</option>'
                );

                if (data.districts) {

                    $.each(data.districts, function(index, district) {

                        $('#customer_district').append(

                            '<option value="' +
                            district.id +
                            '">' +
                            district.district_name +
                            '</option>'

                        );

                    });
                }

                $('#customer_district').val(districtId);
            },

            error: function(xhr) {

                console.error(
                    'Customer district loading failed:',
                    xhr.responseText
                );

                $('#customer_district').html(
                    '<option value="">Unable to load districts</option>'
                );
            }


        });

    });


    /*
    |--------------------------------------------------------------------------
    | Franchise State Selection
    |--------------------------------------------------------------------------
    */

    $('#franchise_state').on('change', function() {

        let stateId = $(this).val();

        $('#franchise_district').html(
            '<option value="">Loading...</option>'
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

            url: "{{ route('admin.filterDistrict') }}",

            type: 'GET',

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

                            '<option value="' +
                            district.id +
                            '">' +
                            district.district_name +
                            '</option>'

                        );

                    });
                }

            },

            error: function(xhr) {

                console.error(
                    'Franchise district loading failed:',
                    xhr.responseText
                );

                $('#franchise_district').html(
                    '<option value="">Unable to load districts</option>'
                );
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Franchise District Selection
    |--------------------------------------------------------------------------
    */

    $('#franchise_district').on('change', function() {

        let stateId = $('#franchise_state').val();

        let districtId = $(this).val();

        $('#franchise').html(
            '<option value="">Loading...</option>'
        );

        if (!stateId || !districtId) {

            $('#franchise').html(
                '<option value="">Select Franchise</option>'
            );

            return;
        }

        $.ajax({

            url: "{{ url('admin/filter-franchise') }}",

            type: 'GET',

            data: {
                state: stateId,
                district: districtId
            },

            dataType: 'json',

            success: function(response) {

                $('#franchise').html(
                    '<option value="">Select Franchise</option>'
                );

                if (response.franchises) {

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
                }

            },

            error: function(xhr) {

                console.error(
                    'Franchise loading failed:',
                    xhr.responseText
                );

                $('#franchise').html(
                    '<option value="">Unable to load franchise</option>'
                );
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Approval Modal
    |--------------------------------------------------------------------------
    */

    document.getElementById('approveModal').addEventListener('show.bs.modal', function(event) {

        const button = event.relatedTarget;

        if (!button) {
            return;
        }

        const id = button.getAttribute('data-id');

        console.log('Approval ID:', id);

        document.getElementById('approval_id').value = id;

        document.getElementById('approval_remarks').value = '';
        document.getElementById('approval_status').value = '';

    });

    $(document).on('click', '.approvalSubmit', function() {

        const id = $(this).attr('data-id');

        console.log('Encrypted ID:', id);

        $('#approval_id').val(id);

        $('#approvalForm').attr(
            'action',
            "{{ route('admin.salesorders.approval.save') }}"
        );

        $('#approval_remarks').val('');
        $('#approval_status').val('');

    });
    /*
    |--------------------------------------------------------------------------
    | Existing Product Rows
    |--------------------------------------------------------------------------
    |
    | If editing an existing order, calculate totals for
    | products already present in the table.
    |
    */

    $('#productTable tbody .product').each(function() {

        if ($(this).val()) {

            productTotal($(this));

        }

    });


});
</script>



<!--  ======================================================
 Customer Selection
 Auto-populate customer details and load customer district
 based on the selected customer's state.
 ====================================================== -->
<script>
$(document).ready(function() {
    $("#n_customer_id").change(function() {

        let option = $(this).find(":selected");

        $("#c_customer_email").val(option.data("email"));
        $("#n_customer_mobile").val(option.data("mobile"));
        $("#c_customer_address").val(option.data("address"));

        let stateId = option.data("state");
        let districtId = option.data("district");

        $("#customer_state").val(stateId);

        $.ajax({
            type: "GET",
            url: "{{ route('admin.filterDistrict') }}",
            data: {
                state: stateId
            },
            dataType: "json",
            success: function(data) {

                $("#customer_district").html('<option value="">Select District</option>');

                $.each(data.districts, function(i, district) {
                    $("#customer_district").append(
                        '<option value="' + district.id + '">' +
                        district.district_name +
                        '</option>'
                    );
                });

                // Select customer's district after options are loaded
                $("#customer_district").val(districtId);

            }
        });

    });
});
</script>

<!--======================================================
 Franchise State Selection
 Load districts based on the selected franchise state.
 ====================================================== -->

<script>
$(document).ready(function() {

    $('#franchise_state').change(function() {

        let stateId = $(this).val();

        $('#franchise_district').html('<option value="">Loading...</option>');

        $.ajax({

            url: "{{ route('admin.filterDistrict') }}",

            type: "GET",

            data: {
                state: stateId
            },

            success: function(response) {

                $('#franchise_district').html('<option value="">Select District</option>');

                $.each(response.districts, function(index, district) {

                    $('#franchise_district').append(
                        '<option value="' + district.id + '">' +
                        district.district_name +
                        '</option>'
                    );

                });

            }

        });

    });

    $(".mode_of_payment").click(function() {
        var payment_mode = $(this).val();
        if (payment_mode == "UPI" || payment_mode == "Bank Deposit" || payment_mode ==
            "Paid to Franchise") {
            $("#payment_image").show();
        } else {
            $("#payment_image").hide();
        }
    })

})
</script>

<!--======================================================
 Franchise District Selection
 Load nearest franchise list based on the selected
 franchise state and district.
 =========================================================-->

<script>
$(document).ready(function() {
    $('#franchise_district').change(function() {

        let stateId = $('#franchise_state').val();
        let districtId = $(this).val();

        $.ajax({
            url: "{{ url('admin/filter-franchise') }}",
            type: "GET",
            data: {
                state: stateId,
                district: districtId
            },
            dataType: "json",
            success: function(response) {

                $('#franchise').html('<option value="">Select Franchise</option>');

                $.each(response.franchises, function(i, franchise) {

                    $('#franchise').append(
                        '<option value="' + franchise.n_store_id + '">' +
                        franchise.c_store_name + ' (' + franchise.c_store_code +
                        ')' +
                        '</option>'
                    );

                });

            }
        });

    });
})
</script>


@endpush