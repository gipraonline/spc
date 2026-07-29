@extends('layouts.app')

@section('content')
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Add Sales Orders</h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" id="frm_create" action="{{ route('admin.salesorders.store') }}">
            @csrf

            <!-- Row 1 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date *</label>
                    <input type="date" name="d_date" class="form-control mandatory" data-message="Please Select a Date" value="">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Bill No *</label>
                    <input type="text" name="c_bill_no" class="form-control mandatory" data-message="Please Enter Bill No" value="{{ old('c_bill_no') }}">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>


            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Farm Care Advisor *</label>
                    <select name="farm_care_advisor_id" data-message="Please Select Farm Care Advisor" class="form-select mandatory">
                        <option value="">Select</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->n_employee_id }}">
                            {{ $employee->c_employee_name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

            </div>

            <!-- Row 3 -->
            <div class="row">
                <label class="form-label">Products *</label>
                <button type="button" class="btn btn-success" id="addRow">Add Product</button>
                <table class="table table-responsive " id="productTable">
                    <thead>
                        <tr>
                            <th width="45%">Product</th>
                            <th width="20%">Price</th>
                            <th width="20%">Quantity</th>
                            <th width="10%">Total</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>

            <!-- Section 4: Contact & Status -->
            <div class="form-section-header" >
                <i class="ti ti-mail fs-5"></i> Contact & Status
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_customer_name" class="form-label">Customer Name *</label>
                    <input type="text" id="c_customer_name" name="c_customer_name" value="{{ old('c_customer_name') }}"
                        data-message="Please add Customer Name" class="form-control mandatory" placeholder="Customer Name">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="c_customer_email" class="form-label">Customer Email *</label>
                    <input type="text" id="c_customer_email" name="c_customer_email" value="{{ old('c_customer_email') }}"
                        data-message="Please enter Customer Email" class="form-control mandatory"
                        placeholder="Enter Customer Email">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="account_number" class="form-label">Customer Address *</label>
                    <input type="text" id="account_number" name="account_number" value="{{ old('c_customer_address') }}"
                        data-message="Please add Customer Address" class="form-control mandatory" placeholder="ACC-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">Customer Mobile *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" value="{{ old('n_customer_mobile') }}"
                        data-message="Please enter Customer Mobile" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">District</label>
                    <select class="form-select mandatory" data-message="Please enter District" id="district" name="district">
                        <option value="" selected>Select District</option>
                        @if(isset($districts))
                            @foreach($districts as $district)
                                <option value="1">{{$district->name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select mandatory" data-message="Please enter State"  id="state" name="state">
                        <option value="" selected>Select State</option>
                         @if(isset($states))
                            @foreach($states as $state)
                                <option value="1">{{$state->name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>


           <div class="row mb-3 align-items-center">
                <label class="col-md-2 col-form-label">
                    Mode of Payment
                </label>

                <div class="col-md-9">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input mandatory" type="radio" name="mode_of_payment" id="cod" data-message="Please enter Mode of Payment" value="cash_on_delivery" >
                        <label class="form-check-label" for="cod">
                            Cash on Delivery
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="mode_of_payment" id="upi" value="UPI">
                        <label class="form-check-label" for="upi">
                            UPI
                        </label>
                    </div>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>


             <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">Nearest Franchise</label>
                    <select class="form-select mandatory" data-message="Please enter Nearest Franchise" id="state" name="nearest_franchise_id">
                        <option value="" selected>Select Franchise</option>
                         @if(isset($shops))
                            @foreach($shops as $shop)
                                <option value="1">{{$shop->name}}</option>
                            @endforeach
                        @endif

                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">Payment Status</label>
                    <select class="form-select mandatory" data-message="Please enter Payment Status" id="state" name="payment_status">
                        <option value="" selected>Select Status</option>
                        <option value="1">Ordered</option>
                        <option value="2">Paid</option>
                        <option value="3">Approved</option>
                        <option value="4">Cancelled</option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">Delivery Status</label>
                    <select class="form-select mandatory" data-message="Please enter Delivory Status" id="state" name="delivery_status">
                        <option value="" selected>Select Delivery Status</option>
                        <option value="1">Ordered</option>
                        <option value="2">Shipped</option>
                        <option value="3">Delivered</option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-3">
                <button type="button" class="btn btn-primary" id="btn_create">Create</button>
                <a href="{{ route('admin.salesorders.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            let rowIndex = 0;

            $("#addRow").click(function () {

                let row = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-control product mandatory" data-message="Please Select Product">
                            <option value="">Select Product</option>

                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                        data-price="{{ $product->price }}">
                                    {{ $product->product_name }}
                                </option>
                            @endforeach

                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </td>

                    <td>
                        <input type="text"
                            name="products[${rowIndex}][price]"
                            class="form-control price"
                            readonly>
                    </td>

                    <td>
                        <input type="number"
                            min="1"
                            value="1"
                            name="products[${rowIndex}][qty]"
                            class="form-control qty">
                    </td>

                    <td>
                        <input type="text"
                            class="form-control total"
                            name="products[${rowIndex}][product_total]"
                            readonly>
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-danger removeRow">
                            X
                        </button>
                    </td>
                </tr>`;

                $("#productTable tbody").append(row);

                rowIndex++;
            });


            $(document).on("change",".product",function(){

                let row = $(this).closest("tr");

                let price = $(this).find(":selected").data("price");

                row.find(".price").val(price);

                let qty = row.find(".qty").val();

                row.find(".total").val(price * qty);

            });



            $(document).on("click",".removeRow",function(){
                $(this).closest("tr").remove();
            });
        });
    </script>
@endpush
