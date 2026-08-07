@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #1b3e86;
    --deep-slate: #1e293b;
    --glass-bg: #fdfdfe;
    --border-radius-lg: 18px;
    --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.04), 0 5px 15px rgba(0, 0, 0, 0.02);
}

/* Architectural Layout */
.premium-edit-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
}

/* Signature Accent Line */
.premium-edit-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--accent-orange) 0%, #ffae42 100%);
    z-index: 10;
}

.card-header-premium {
    padding: 2.2rem 2.5rem 1.2rem;
    background: #fff;
}

.page-main-title {
    font-weight: 800;
    font-size: 1.4rem;
    color: var(--deep-slate);
    letter-spacing: -0.8px;
}

/* Sectional Typography */
.field-group-title {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--accent-orange);
    letter-spacing: 1.5px;
    margin-bottom: 1.4rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.field-group-title::after {
    content: '';
    height: 1px;
    flex: 1;
    background: linear-gradient(90deg, #f1f5f9 0%, transparent 100%);
}

/* Modern Inputs */
.form-label {
    font-weight: 700;
    color: #475569;
    font-size: 0.85rem;
    margin-bottom: 0.6rem;
}

.form-control,
.form-select {
    border-radius: 12px;
    padding: 0.85rem 1.1rem;

    background-color: #f8fafc;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    color: var(--deep-slate);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--accent-orange);
    background-color: #ffffff;
    box-shadow: 0 8px 20px rgba(247, 148, 30, 0.08);
    transform: translateY(-1px);
}

/* Action Buttons */
.btn-update-action {
    background: var(--accent-orange);
    border: none;
    padding: 14px 40px;
    border-radius: 12px;
    font-weight: 800;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(247, 148, 30, 0.15);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-update-action:hover {
    background: #1b3e86;
    box-shadow: 0 12px 25px rgba(247, 148, 30, 0.25);
    transform: translateY(-2px);
}

.btn-cancel-action {
    border-radius: 12px;
    padding: 14px 30px;
    font-weight: 700;
    border: 2px solid #f1f5f9;
    color: #64748b;
    transition: all 0.2s ease;
}
</style>

<div class="card premium-edit-card mb-4">
    <div class="card-header-premium">
        <h5 class="page-main-title mb-0">Edit Store</h5>
    </div>

    <div class="card-body p-4 p-md-5 pt-md-4">
        <form id="frm_create" method="POST" action="{{ route('admin.franchises.update', $franchise) }}">
            @csrf @method('PUT')

            <!-- Section 1: Store Configuration -->
            <div class="field-group-title">
                <i class="ti ti-settings-automation fs-5"></i> Store Configuration
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <label for="c_store_code" class="form-label">Store Code *</label>
                    <input type="text" id="c_store_code" name="c_store_code" data-message="Enter valid Store Code"
                        max-length="20" value="{{ old('c_store_code', $franchise->c_store_code) }}" required
                        class="form-control mandatory">
                    <div class="text-danger mt-1 fs-2"></div>
                    @error('c_store_code')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-7">
                    <label for="c_store_name" class="form-label">Store Name *</label>
                    <input type="text" id="c_store_name" name="c_store_name" data-message="Please enter Name"
                        maxlength="100" value="{{ old('c_store_name', $franchise->c_store_name) }}" required
                        class="form-control mandatory">
                    @error('c_store_name')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="c_store_address" class="form-label">Address</label>
                    <input type="text" id="c_store_address" name="c_store_address" maxlength="255"
                        value="{{ old('c_store_address', $franchise->c_store_address) }}" class="form-control">
                    @error('c_store_address')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <label for="n_state_id" class="form-label">State *</label>
                        <select id="n_state_id" name="n_state_id" class="form-select">
                            <option value="">Select State</option>

                            @foreach($states as $state)
                            <option value="{{ $state->n_state_id }}"
                                {{ old('n_state_id', $franchise->n_state_id) == $state->n_state_id ? 'selected' : '' }}>
                                {{ $state->name }}
                            </option>
                            @endforeach
                        </select>

                        @error('n_state_id')
                        <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="n_district_id" class="form-label">District *</label>
                        <select id="n_district_id" name="n_district_id" class="form-select">
                            <option value="">Select District</option>

                            @foreach($districts as $district)
                            <option value="{{ $district->id }}"
                                {{ old('n_district_id', $franchise->n_district_id) == $district->id ? 'selected' : '' }}>
                                {{ $district->district_name }}
                            </option>
                            @endforeach
                        </select>

                        @error('n_district_id')
                        <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Section 2: Contact & Operational Details -->
            <div class="field-group-title mt-5">
                <i class="ti ti-address-book fs-5"></i> Contact & Status
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label for="c_store_email" class="form-label">Email</label>
                    <input type="email" id="c_store_email" name="c_store_email"
                        value="{{ old('c_store_email', $franchise->c_store_email) }}" class="form-control">
                    @error('c_store_email')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="n_store_phone" class="form-label">Phone</label>
                    <input type="text" id="n_store_phone" name="n_store_phone" max-length="10"
                        value="{{ old('n_store_phone', $franchise->n_store_phone) }}" class="form-control">
                    @error('n_store_phone')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="c_store_status" class="form-label">Status *</label>
                    <select id="c_store_status" name="c_store_status" data-message="Please select Status" required
                        class="form-select mandatory">
                        <option value="Y"
                            {{ old('c_store_status', $franchise->c_store_status) === 'Y' ? 'selected' : '' }}>
                            Active</option>
                        <option value="N"
                            {{ old('c_store_status', $franchise->c_store_status) === 'N' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                    @error('c_store_status')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Footer Action Bar -->
            <div class="pt-4 border-top d-flex gap-3">
                <button type="button" id="btn_create" class="btn buttonSpc">
                    <i class="ti ti-refresh fs-4"></i> Update Record
                </button>
                <a href="{{ route('admin.franchises.index') }}" class="btn btn-cancel-action">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$('#n_state_id').change(function() {

    let stateId = $(this).val();

    $.get('/admin/districts/' + stateId, function(response) {

        let options = '<option value="">Select District</option>';

        $.each(response, function(i, district) {
            options += `<option value="${district.id}">
                            ${district.district_name}
                        </option>`;
        });

        $('#n_district_id').html(options);
    });

});
</script>
@endpush
@endsection