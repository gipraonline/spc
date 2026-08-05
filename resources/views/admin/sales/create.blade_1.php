@extends('layouts.app')

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

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" id="frm_create" action="{{ route('admin.salesorders.store') }}">
                @csrf

                <input type="hidden" name="id" class="form-control" value="{{ isset($sale) ? $sale->n_sl_no : '' }}">

                <!-- Row 1 -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="d_date" class="form-control mandatory"
                            data-message="Please Select a Date"
                            value="{{ old('d_date', isset($sale) ? $sale->d_date->format('Y-m-d') : '') }}"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'readonly' : '' }}>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bill No *</label>
                        <input type="text" name="c_bill_no" class="form-control mandatory"
                            data-message="Please Enter Bill No"
                            value="{{ old('c_bill_no', isset($sale) ? $sale->c_bill_no : '') }}"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'readonly' : '' }}>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>


                </div>

                <!-- Row 2 -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Farm Care Advisor *</label>
                        <select name="farm_care_advisor_id" data-message="Please Select Farm Care Advisor"
                            class="form-select mandatory" {{ isset($viewmode) && $viewmode == 'on' ? 'disabled' : '' }}>
                            <option value="">Select</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->n_employee_id }}"
                                    {{ old('farm_care_advisor_id', $sale->farm_care_advisor_id ?? '') == $employee->n_employee_id ? 'selected' : '' }}>
                                    {{ $employee->c_employee_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                </div>

                <!-- Row 3 -->
                <div class="row">
                    <label class="form-label">Order Details *</label>
                    @if (isset($viewmode) && $viewmode == 'off')
                        <button type="button" style="width:180px;position:relative;" class="btn mb-1 buttonSpc"
                            id="addRow">Add New Product</button>
                    @endif
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
                            @if (isset($sale->orderProducts))
                                @foreach ($sale->orderProducts as $key => $val)
                                    <tr>
                                        <td>
                                            <select name="products[{{ $key }}][product_id]"
                                                class="form-control product mandatory">
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->n_product_id }}"
                                                        data-price="{{ $product->n_selling_price }}"
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
                                            <input type="number" name="products[{{ $key }}][qty]"
                                                class="form-control qty" value="{{ $val->qty }}">
                                        </td>

                                        <td>
                                            <input type="text" name="products[{{ $key }}][product_total]"
                                                class="form-control total" value="{{ $val->product_total }}" readonly>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-danger removeRow">X</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif



                        </tbody>
                    </table>
                </div>

                <!-- Section 4: Contact & Status -->
                <div class="form-section-header mb-3">
                    <i class="ti ti-mail fs-5"></i> Contact & Status
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="c_customer_name" class="form-label">Customer Name *</label>
                        <input type="text" id="c_customer_name" name="c_customer_name"
                            value="{{ old('c_customer_name', isset($sale) ? $sale->c_customer_name : '') }}"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'readonly' : '' }}
                            data-message="Please add Customer Name" class="form-control mandatory"
                            placeholder="Customer Name">
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="c_customer_email" class="form-label">Customer Email *</label>
                        <input type="text" id="c_customer_email" name="c_customer_email"
                            value="{{ old('c_customer_email', isset($sale) ? $sale->c_customer_email : '') }}"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'readonly' : '' }}
                            data-message="Please enter Customer Email" class="form-control mandatory"
                            placeholder="Enter Customer Email">
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="c_customer_address" class="form-label">Customer Address *</label>
                        <input type="text" id="c_customer_address" name="c_customer_address"
                            value="{{ old('c_customer_address', isset($sale) ? $sale->c_customer_address : '') }}"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'readonly' : '' }}
                            data-message="Please add Customer Address" class="form-control mandatory"
                            placeholder="Customer Address">
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="n_customer_mobile" class="form-label">Customer Mobile *</label>
                        <input type="text" id="n_customer_mobile" name="n_customer_mobile"
                            value="{{ old('n_customer_mobile', isset($sale) ? $sale->n_customer_mobile : '') }}"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'readonly' : '' }}
                            data-message="Please enter Customer Mobile" class="form-control mandatory"
                            placeholder="Enter Customer Mobile">
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select mandatory" data-message="Please enter State" id="state"
                            name="n_state_id" {{ isset($viewmode) && $viewmode == 'on' ? 'disabled' : '' }}>
                            <option value="" selected>Select State</option>
                            @if (isset($states))
                                @foreach ($states as $State)
                                    <option value="{{ $State->n_state_id }}"
                                        {{ old('n_state_id', $sale->n_state_id ?? '') == $State->n_state_id ? 'selected' : '' }}>
                                        {{ $State->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="state" class="form-label">District</label>
                        <select class="form-select mandatory" data-message="Please enter District"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'disabled' : '' }} id="district"
                            name="n_district_id">
                            <option value="" selected>Select District</option>
                            @if (isset($sale->n_district_id))
                                @php $districts = \App\Models\District::where('state_id', $sale->n_state_id)->get(); @endphp
                                @if (isset($districts))
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}"
                                            {{ old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : '' }}>
                                            {{ $district->district_name }}</option>
                                    @endforeach
                                @endif
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
                            <input class="form-check-input mandatory" type="radio" name="c_mode_of_payment"
                                id="cod" data-message="Please enter Mode of Payment" value="cash_on_delivery"
                                {{ old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == 'cash_on_delivery' ? 'checked' : '' }}
                                {{ isset($viewmode) && $viewmode == 'on' ? 'disabled' : '' }}>
                            <label class="form-check-label" for="cod">
                                Cash on Delivery
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="c_mode_of_payment" id="upi"
                                value="UPI"
                                {{ old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == 'UPI' ? 'checked' : '' }}
                                {{ isset($viewmode) && $viewmode == 'on' ? 'disabled' : '' }}>
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
                        <select class="form-select mandatory" data-message="Please enter Nearest Franchise"
                            id="franchise" name="nearest_franchise_id"
                            {{ isset($viewmode) && $viewmode == 'on' ? 'disabled' : '' }}>
                            <option value="" selected>Select Franchise</option>
                            @if (isset($franchises))
                                @foreach ($franchises as $franchise)
                                    <option value="{{ $franchise->n_store_id }}"
                                        {{ old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : '' }}>
                                        {{ $franchise->c_store_name }}({{ $franchise->c_store_code }})</option>
                                @endforeach
                            @endif

                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                </div>


                <!-- Buttons -->
                <div class="mt-3">
                    @if (isset($viewmode) && $viewmode == 'on')
                        @can('sales-orders.approve')
                            <button type="button" style="width:150px;position:relative;" class="btn mb-1 buttonSpc"
                                data-bs-toggle="modal" data-bs-target="#approveModal"
                                data-id="{{ isset($sale) ? Crypt::encryptString($sale->n_sl_no) : '' }}"
                                id="approve">Approve</button>
                        @endcan
                    @else
                        <button type="button" class="btn buttonSpc"
                            id="btn_create">{{ isset($sale->n_sl_no) ? 'Update' : 'Create' }}</button>
                        <a href="{{ route('admin.salesorders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>


    <!--Approval Form modal-->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="approveForm">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" id="approval_id">

                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Approval Status</label>
                            <select class="form-select" name="status">
                                <option value="Approved">Pending</option>
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>

            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowIndex = {{ isset($sale) ? count($sale->orderProducts) : 0 }};

            $("#addRow").click(function() {

                let row = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-control product mandatory" data-message="Please Select Product">
                            <option value="">Select Product</option>

                            @foreach ($products as $product)
                                <option value="{{ $product->n_product_id }}"
                                        data-price="{{ $product->n_selling_price }}">
                                    {{ $product->c_product_name }}({{ $product->c_product_code }})
                                </option>
                            @endforeach

                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </td>

                    <td>
                        <input type="text"
                            name="products[${rowIndex}][product_price]"
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


            $(document).on("change", ".product", function() {

                productTotal($(this));

            });

            $(document).on("change", ".qty", function() {

                productTotal($(this).parent().siblings().find(".product"));

            });



            $(document).on("click", ".removeRow", function() {
                $(this).closest("tr").remove();
            });

            $(document).on("change", "#state", function() {
                var state = $(this).val();
                $.ajax({
                    type: "get",
                    url: "{{ route('admin.filterDistrict') }}",
                    data: {
                        state: state
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $("#district").empty();
                        $("#district").append('<option value="">Select District</option>');

                        $.each(data.districts, function(index, district) {
                            $("#district").append(
                                '<option value="' + district.id + '">' + district
                                .district_name + '</option>'
                            );
                        });
                    }
                });
            });

            function productTotal(id) {
                let row = id.closest("tr");

                let price = id.find(":selected").data("price");

                row.find(".price").val(price);

                let qty = row.find(".qty").val();

                row.find(".total").val(price * qty);
            }

            const approveModal = document.getElementById('approveModal');

            approveModal.addEventListener('show.bs.modal', function(event) {

                let button = event.relatedTarget;
                let id = button.getAttribute('data-id');

                document.getElementById('approval_id').value = id;

                // Set form action dynamically
                document.getElementById('approveForm').action =
                    "{{ route('admin.salesorders.approval.save') }}";
            });
        });
    </script>
@endpush
