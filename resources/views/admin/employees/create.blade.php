@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #F7941E;
    --text-muted: #64748b;
    --border-radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

/* Main Card Styling */
.employee-card {
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
    letter-spacing: -0.5px;
}

/* Section Headers */
.form-section-header {
    font-size: 0.8rem;
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

/* Form Controls */
.form-label {
    font-weight: 700;
    color: #4a5568;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border-radius: 10px;
    padding: 0.75rem 1rem;

    background-color: #fdfdfe;
    transition: all 0.25s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(57, 181, 74, 0.08);
}

/* Special Multiple Select */
#cluster_stores {
    scrollbar-width: thin;
    scrollbar-color: var(--primary-green) #f1f5f9;
}

/* Footer Buttons */
.btn-create-item {
    background: var(--primary-green);
    border: none;
    padding: 10px 30px;
    border-radius: 10px;
    font-weight: 700;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(57, 181, 74, 0.2);
}

.btn-create-item:hover {
    background: #1b3e86;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(57, 181, 74, 0.3);
}

.btn-cancel-custom {
    border-radius: 10px;
    padding: 10px 25px;
    font-weight: 600;
}
</style>

<div class="card employee-card mb-4">
    <div class="card-header-styled d-flex justify-content-between align-items-center">
        <h5 class="card-title-custom mb-0">Add Employee</h5>
    </div>

    <div class="card-body p-4 p-md-5">
        <form method="POST" id="frm_create" action="{{ route('admin.employees.store') }}">
            @csrf

            <!-- Section 1: Identification -->
            <div class="form-section-header">
                <i class="ti ti-user-circle fs-5"></i> Identification
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_employee_code" class="form-label">Employee Code *</label>
                    <input type="text" id="c_employee_code" name="c_employee_code" value="{{ old('c_employee_code') }}"
                        data-message="Please add Employee Code" class="form-control mandatory" placeholder="EMP-001">
                    @error('c_employee_code')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="c_employee_name" class="form-label">Employee Name *</label>
                    <input type="text" id="c_employee_name" name="c_employee_name" value="{{ old('c_employee_name') }}"
                        data-message="Please enter Employee Name" class="form-control mandatory"
                        placeholder="Enter full name">
                    @error('c_employee_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Role & Designation -->
            <div class="form-section-header">
                <i class="ti ti-briefcase fs-5"></i> Role & Assignment
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="n_designation_id" class="form-label">Designation *</label>
                    <select id="n_designation_id" name="n_designation_id" data-message="Please select a Designation"
                        class="form-select mandatory">
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                        @php
                        $desigName = strtoupper(trim($designation->c_designation));
                        $storeRequired = in_array($desigName, ['CSA', 'C&A', 'SM']) ? 1 : 0;
                        @endphp
                        <option value="{{ $designation->n_designation_id }}" data-store="{{ $storeRequired }}"
                            {{ old('n_designation_id') == $designation->n_designation_id ? 'selected' : '' }}>
                            {{ $designation->c_designation }}
                        </option>
                        @endforeach
                    </select>
                    @error('n_designation_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="reporting_to" class="form-label">
                        Reporting Manager
                    </label>
                    <select name="reporting_to" id="reporting_to" class="form-select">
                        <option value="">Select Reporting Manager</option>
                    </select>

                    @error('reporting_to')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
            <!-- Section 3: Account Details -->
            <div class="form-section-header">
                <i class="ti ti-building-bank fs-5"></i>Account Details
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="account_number" class="form-label">Account Number *</label>
                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}"
                        data-message="Please add Account Number" class="form-control mandatory" placeholder="ACC-001">

                    @error('account_number')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">IFSC Code *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}"
                        data-message="Please enter IFSC Code" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    @error('ifsc_code')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="bank_name" class="form-label">Bank Name *</label>
                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                        data-message="Please add Bank name" class="form-control mandatory" placeholder="SBI">

                    @error('bank_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="branch_name" class="form-label">Branch Name*</label>
                    <input type="text" id="branch_name" name="branch_name" value="{{ old('branch_name') }}"
                        data-message="Please add Branch name" class="form-control mandatory" placeholder="KOCHI">
                    @error('branch_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <!-- Section 4: Contact & Status -->
            <div class="form-section-header">
                <i class="ti ti-mail fs-5"></i> Contact & Status
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-8">
                    <label for="c_employee_email" class="form-label">Email Address *</label>
                    <input type="email" id="c_employee_email" name="c_employee_email"
                        value="{{ old('c_employee_email') }}" data-message="Please enter an Email Address"
                        class="form-control mandatory" placeholder="example@company.com">

                    @error('c_employee_email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="c_status" class="form-label">Employment Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory"
                        data-message="Please select Status">
                        <option value="">Select Status</option>
                        <option value="Y" {{ old('c_status') === 'Y' ? 'selected' : '' }}>Active</option>
                        <option value="N" {{ old('c_status') === 'N' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    @error('c_status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block mt-1">
                        Default employee password is <strong>Password@123</strong>
                    </small>
                </div>
            </div>

            <div class="d-flex gap-3 pt-4 border-top">
                <button type="submit" id="btn_create" class="btn btn-create-item">
                    <i class="ti ti-plus me-1"></i> Create Employee
                </button>
                <a href="{{ route('admin.employees.index') }}"
                    class="btn btn-outline-secondary btn-cancel-custom">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{asset('dist/js/custom.js?1')}}"></script>
<script>
$(document).ready(function() {

    $('#n_designation_id').change(function() {

        let designation = $(this).val();

        console.log(designation);

        $.ajax({
            url: '/admin/employees/reporting-managers/' + designation,
            type: 'GET',
            success: function(data) {

                let options = '<option value="">Select Reporting Manager</option>';

                $.each(data, function(index, emp) {

                    options += `
            <option value="${emp.n_employee_id}">
                ${emp.c_designation}
            </option>
        `;

                });

                $('#reporting_to').html(options);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });

    });

});
</script>
@endpush
@endsection