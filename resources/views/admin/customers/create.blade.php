@extends('layouts.app')

@section('content')

<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #F7941E;
    --text-muted: #64748b;
    --border-radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, .05);
}

.customer-card {
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header-styled {
    padding: 1.5rem 2rem;
    border-bottom: 2px solid #f8fafc;
    border-top: 4px solid var(--primary-green);
    background: #fff;
}

.card-title-custom {
    font-weight: 800;
    color: #1a202c;
}

.form-section-header {
    font-size: .8rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--primary-green);
    letter-spacing: 1px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section-header::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f5f9;
}

.form-label {
    font-weight: 700;
    color: #4a5568;
    font-size: .85rem;
}

.form-control,
.form-select {

    border-radius: 10px;
    padding: .75rem 1rem;
    background: #fdfdfe;

}

.form-control:focus,
.form-select:focus {

    border-color: var(--primary-green);
    box-shadow: 0 0 0 4px rgba(57, 181, 74, .08);

}

.btn-cancel-custom {

    border-radius: 10px;
    padding: 10px 25px;

}
</style>

<div class="card customer-card mb-4">

    <div class="card-header-styled d-flex justify-content-between align-items-center">

        <h5 class="card-title-custom mb-0">
            Add Customer
        </h5>

    </div>

    <div class="card-body p-4 p-md-5">

        <form method="POST" action="{{ route('admin.customers.store') }}">

            @csrf

            <!-- Customer Information -->

            <div class="form-section-header">

                <i class="ti ti-user"></i>

                Customer Information

            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-6">

                    <label class="form-label">
                        Customer Code *
                    </label>

                    <input type="text" name="c_customer_code" value="{{ old('c_customer_code') }}"
                        class="form-control mandatory" placeholder="CUS-001">

                    @error('c_customer_code')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Customer Name *
                    </label>

                    <input type="text" name="c_customer_name" value="{{ old('c_customer_name') }}"
                        class="form-control mandatory" placeholder="Customer Name">

                    @error('c_customer_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Mobile Number *
                    </label>

                    <input type="text" maxlength="10" name="n_mobile" value="{{ old('n_mobile') }}"
                        class="form-control mandatory">

                    @error('n_mobile')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        WhatsApp Number
                    </label>

                    <input type="text" maxlength="10" name="n_whatsapp" value="{{ old('n_whatsapp') }}"
                        class="form-control">

                    @error('n_whatsapp')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        Email
                    </label>

                    <input type="email" name="c_email" value="{{ old('c_email') }}" class="form-control">

                    @error('c_email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

            </div>
            <!-- Address Details -->

            <div class="form-section-header">

                <i class="ti ti-map-pin"></i>

                Address Details

            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-12">

                    <label for="c_address" class="form-label">
                        Address
                    </label>

                    <textarea id="c_address" name="c_address" rows="3" class="form-control"
                        placeholder="Enter Customer Address">{{ old('c_address') }}</textarea>

                    @error('c_address')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>
                <div class="col-md-4">

                    <label for="c_state" class="form-label">
                        State
                    </label>

                    <select name="c_state" id="c_state" class="form-select">

                        <option value="">Select State</option>

                        @foreach($states as $state)

                        <option value="{{ $state->name }}" data-id="{{ $state->n_state_id }}"
                            {{ old('c_state') == $state->name ? 'selected' : '' }}>

                            {{ $state->name }}

                        </option>

                        @endforeach

                    </select>

                    @error('c_state')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-4">

                    <label for="c_district" class="form-label">
                        District
                    </label>

                    <select name="c_district" id="c_district" class="form-select">

                        <option value="">Select District</option>

                    </select>

                    @error('c_district')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label for="c_pincode" class="form-label">
                        Pincode
                    </label>

                    <input type="text" id="c_pincode" name="c_pincode" maxlength="6" value="{{ old('c_pincode') }}"
                        class="form-control" placeholder="Pincode">

                    @error('c_pincode')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

            </div>
            <!-- Customer Status -->

            <div class="form-section-header">
                <i class="ti ti-checkup-list"></i>
                Customer Status
            </div>

            <div class="row g-4 mb-5">

                <div class="col-md-4">

                    <label for="c_status" class="form-label">
                        Status <span class="text-danger">*</span>
                    </label>

                    <select id="c_status" name="c_status" class="form-select mandatory">

                        <option value="">Select Status</option>

                        <option value="Y" {{ old('c_status','Y')=='Y' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="N" {{ old('c_status')=='N' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('c_status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            <!-- Footer -->

            <div class="d-flex gap-3 pt-4 border-top">

                <button type="submit" class="btn buttonSpc">

                    <i class="ti ti-plus me-1"></i>

                    Create Customer

                </button>

                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-cancel-custom">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@push('scripts')

<script src="{{ asset('dist/js/custom.js') }}"></script>

<script>
$(document).ready(function() {

    // Allow only numbers for Mobile, WhatsApp & Pincode
    $('#n_mobile, #n_whatsapp, #c_pincode').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // Convert Customer Code to uppercase
    $('#c_customer_code').on('keyup', function() {
        $(this).val($(this).val().toUpperCase());
    });

});
</script>
<script>
$('#c_state').on('change', function() {

    let stateId = $(this).find(':selected').data('id');

    $('#c_district').html('<option>Loading...</option>');

    $.get('/admin/districts/' + stateId, function(response) {

        $('#c_district').html('<option value="">Select District</option>');

        $.each(response, function(index, district) {

            $('#c_district').append(
                '<option value="' + district.district_name + '">' +
                district.district_name +
                '</option>'
            );

        });

    });

});
</script>

@endpush

@endsection