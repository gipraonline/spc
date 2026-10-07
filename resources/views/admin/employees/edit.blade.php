@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
@endpush

@section('content')
<style>
/* ===== Add / Edit Employee — same visual language as the Employee Records module ===== */
.employee-page.efp {
    --brand: #4E7A33;
    --brand-strong: #0E5239;
    --brand-ink: #1F3D14;
    --brand-bright: #5E8D3D;
    --brand-glow: rgba(94, 141, 61, .35);
    --brand-soft: #E4F3EB;
    --brand-softer: #F2F9F5;
    --line: rgba(18, 58, 40, 0.13);
    --line-soft: rgba(18, 58, 40, 0.07);
    --text: #22352C;
    --text-muted: #61756B;
    --bad: #C23A3A;
    --shadow-sm: 0 1px 2px rgba(10, 61, 44, .05);
    --font-head: 'Kanit', sans-serif;
    --font-body: 'Outfit', sans-serif;
    padding: 24px clamp(16px, 3vw, 32px) 8px;
    font-family: var(--font-body);
    color: var(--text);
}

/* Page heading */
.efp .employee-page-heading { margin-bottom: 18px; }
.efp .employee-page-heading h2 {
    margin: 0;
    font-family: var(--font-head);
    font-weight: 600;
    font-size: 21px;
    color: var(--brand-ink);
    display: flex;
    align-items: center;
    gap: 10px;
}
.efp .employee-page-heading h2 i {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}
.efp .employee-page-heading p { margin: 5px 0 0; color: var(--text-muted); font-size: 13px; }

/* Split card */
.efp-card {
    display: grid;
    grid-template-columns: 300px minmax(0, 1fr);
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

/* Left panel */
.efp-side {
    background: linear-gradient(160deg, #0E5239 0%, #1F5C2E 55%, #4E7A33 125%);
    color: #fff;
    padding: 28px 22px;
}
.efp-side-inner { position: sticky; top: 24px; }
.efp-side-ico {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: rgba(255, 255, 255, .14);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-left: 4px;
}
.efp-side h3 {
    margin: 16px 4px 6px;
    font-family: var(--font-head);
    font-size: 20px;
    font-weight: 600;
    line-height: 1.25;
    color: #fff;
    word-break: break-word;
}
.efp-side p { margin: 0 4px; font-size: 13px; line-height: 1.55; color: rgba(255, 255, 255, .78); }
.efp-steps { margin-top: 22px; display: grid; gap: 2px; }
.efp-step {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 8px 10px;
    border-radius: 10px;
    font-size: 13px;
    color: rgba(255, 255, 255, .9);
    text-decoration: none;
}
.efp-step:hover { background: rgba(255, 255, 255, .09); color: #fff; }
.efp-step .num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .16);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
}

/* Form body */
.efp-body { padding: 26px 30px 0; background: #fff; min-width: 0; margin: 0; }
.efp-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: var(--brand-softer);
    border: 1px solid var(--line-soft);
    color: var(--brand-ink);
    border-radius: 12px;
    padding: 11px 14px;
    font-size: 12.5px;
    line-height: 1.5;
    margin-bottom: 22px;
}
.efp-note i { color: var(--brand); font-size: 16px; margin-top: 1px; }
.efp-alert {
    background: #FBE7E4;
    border: 1px solid rgba(194, 58, 58, .25);
    color: #942B2B;
    border-radius: 12px;
    padding: 11px 14px 11px 18px;
    font-size: 12.5px;
    margin-bottom: 22px;
}
.efp-alert ul { margin: 0; padding-left: 16px; }

.efp-section {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-head);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--brand);
    margin: 4px 0 14px;
    scroll-margin-top: 20px;
}
.efp-section i { font-size: 16px; }
.efp-section::after { content: ''; flex: 1; height: 1px; background: var(--line-soft); }

.efp-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px 18px;
    margin-bottom: 26px;
}
.efp-field { min-width: 0; }
.efp-field.full { grid-column: 1 / -1; }
.efp-field > label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: #3B5246;
    margin-bottom: 6px;
}
.efp-field > label i { color: var(--brand); font-size: 15px; }

.efp-field .form-control,
.efp-field .form-select {
    height: 42px;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 0 12px;
    font-family: var(--font-body);
    font-size: 13.5px;
    font-weight: 400;
    color: var(--text);
    background-color: #FBFDFC;
    box-shadow: none;
    transition: border-color .2s, box-shadow .2s, background-color .2s;
}
.efp-field .form-select { padding-right: 34px; }
.efp-field textarea.form-control { height: auto; min-height: 92px; padding: 10px 12px; resize: vertical; }
.efp-field .form-control::placeholder { color: #98A9A0; }
.efp-field .form-control:focus,
.efp-field .form-select:focus {
    border-color: var(--brand-bright);
    background-color: #fff;
    box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .14);
    outline: 0;
}
.efp-field .form-control:disabled,
.efp-field .form-control[readonly] {
    background-color: #F1F5F2;
    color: var(--text-muted);
    cursor: not-allowed;
}
.efp-field .form-control.is-invalid,
.efp-field .form-select.is-invalid { border-color: var(--bad); }
.efp-hint { display: block; margin-top: 5px; font-size: 11.5px; color: var(--text-muted); }
.efp-err { margin-top: 5px; font-size: 12px; }

/* Footer */
.efp-foot {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    padding: 16px 0 22px;
    border-top: 1px solid var(--line-soft);
}
.efp-hint-secure {
    margin-right: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
}
.efp-btn {
    height: 41px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0 18px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid transparent;
    line-height: 1;
}
.efp-btn-secondary { background: var(--brand-softer); color: var(--brand); border-color: var(--line); }
.efp-btn-secondary:hover { background: var(--brand-soft); color: var(--brand-strong); }
.efp-btn-primary {
    background: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    color: #fff;
    box-shadow: 0 10px 20px -10px var(--brand-glow);
}
.efp-btn-primary:hover { filter: brightness(1.07); color: #fff; }

@media (max-width: 992px) {
    .efp-card { grid-template-columns: 1fr; }
    .efp-side { padding: 22px 20px; }
    .efp-side-inner { position: static; }
    .efp-steps { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); }
}
@media (max-width: 640px) {
    .efp-grid { grid-template-columns: 1fr; }
    .efp-body { padding: 20px 18px 0; }
}
</style>

<div class="content employee-page efp">
    <div class="employee-page-heading">
        <h2><i class="ti ti-user-edit"></i>Edit employee</h2>
        <p>Update the details for this employee.</p>
    </div>

    <div class="efp-card">
        <aside class="efp-side">
            <div class="efp-side-inner">
                <div class="efp-side-ico"><i class="ti ti-user-edit"></i></div>
                <h3>Edit — {{ $employee->c_employee_name }}
                    @if($employee->isAssociate())
                    <span style="margin-left:8px;padding:2px 10px;border-radius:999px;background:#FFF4D6;border:1px solid #E6C55A;color:#7A5B00;font-size:12px;font-weight:600;">Associate - not an employee yet</span>
                    @endif
                </h3>
                <p>{{ $employee->c_employee_code }} · {{ $employee->c_employee_email }}</p>
                <div class="efp-steps">
                    <a href="#sec-identification" class="efp-step"><span class="num">1</span>Identification</a>
                    <a href="#sec-personal" class="efp-step"><span class="num">2</span>Personal & HR details</a>
                    <a href="#sec-role" class="efp-step"><span class="num">3</span>Role & assignment</a>
                    <a href="#sec-account" class="efp-step"><span class="num">4</span>Account details</a>
                    <a href="#sec-contact" class="efp-step"><span class="num">5</span>Communication & status</a>
                </div>
            </div>
        </aside>

        <form method="POST" id="frm_create" action="{{ route('admin.employees.update', $employee) }}" class="efp-body">
            @csrf @method('PUT')

            <div class="efp-note"><i class="ti ti-pencil"></i>Employee code and work email are locked. Reporting managers load from the selected designation.</div>

            @if ($errors->any())
                <div class="efp-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Identification Section -->
            <div class="efp-section" id="sec-identification"><i class="ti ti-id"></i>Identification</div>
            <div class="efp-grid">
                <div class="efp-field">
                    <label for="c_employee_code"><i class="ti ti-id-badge-2"></i>Employee Code *</label>
                    <input type="text" id="c_employee_code" name="c_employee_code"
                        value="{{ old('c_employee_code', $employee->c_employee_code) }}"
                        data-message="Please add Employee Code" class="form-control mandatory" disabled>
                    @error('c_employee_code')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field">
                    <label for="c_employee_name"><i class="ti ti-user"></i>Employee Name *</label>
                    <input type="text" id="c_employee_name" name="c_employee_name"
                        value="{{ old('c_employee_name', $employee->c_employee_name) }}"
                        data-message="Please enter Employee Name" class="form-control mandatory">
                    @error('c_employee_name')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field full">
                    <label for="c_employee_address"><i class="ti ti-map-pin"></i>Address *</label>
                    <textarea id="c_employee_address" name="c_employee_address"
                        data-message="Please enter Employee Address" class="form-control "
                        placeholder="Enter Address">{{ old('c_employee_address') }}</textarea>
                    @error('c_employee_address')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            

            <!-- Personal & HR Details Section -->
            <div class="efp-section" id="sec-personal"><i class="ti ti-id-badge-2"></i>Personal & HR Details</div>
            <div class="efp-grid">
                <div class="efp-field">
                    <label for="date_of_birth"><i class="ti ti-cake"></i>Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        value="{{ old('date_of_birth', optional($employee->date_of_birth)->format('Y-m-d')) }}"
                        class="form-control">
                    @error('date_of_birth')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="gender"><i class="ti ti-gender-bigender"></i>Gender</label>
                    <select id="gender" name="gender" class="form-select">
                        <option value="">Select</option>
                        <option value="male" {{ old('gender', $employee->gender) === 'male' ? 'selected' : '' }}>Male
                        </option>
                        <option value="female" {{ old('gender', $employee->gender) === 'female' ? 'selected' : '' }}>
                            Female</option>
                        <option value="other" {{ old('gender', $employee->gender) === 'other' ? 'selected' : '' }}>
                            Other</option>
                    </select>
                    @error('gender')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="personal_email"><i class="ti ti-mail-opened"></i>Personal Email</label>
                    <input type="email" id="personal_email" name="personal_email"
                        value="{{ old('personal_email', $employee->personal_email) }}" class="form-control">
                    @error('personal_email')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="city"><i class="ti ti-building"></i>City</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $employee->city) }}"
                        class="form-control">
                    @error('city')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="department_id"><i class="ti ti-sitemap"></i>Department</label>
                    <select id="department_id" name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        @foreach($hrDepartments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select>
                    <small class="efp-hint">Used by the HR module.</small>
                    @error('department_id')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="date_of_joining"><i class="ti ti-calendar-plus"></i>Date of Joining</label>
                    <input type="date" id="date_of_joining" name="date_of_joining"
                        value="{{ old('date_of_joining', optional($employee->date_of_joining)->format('Y-m-d')) }}"
                        class="form-control">
                    @error('date_of_joining')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Role Section -->
            <div class="efp-section" id="sec-role"><i class="ti ti-briefcase"></i>Role & Assignment</div>
            <div class="efp-grid">
                <div class="efp-field">
                    <input type="hidden" name="pre_designation_id" value="{{ $employee->n_designation_id}}">
                    <label for="n_designation_id"><i class="ti ti-briefcase"></i>Designation *</label>
                    <select id="n_designation_id" name="n_designation_id" class="form-select mandatory "
                        data-message="Please select a Designation">
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                        @php
                        $desigName = strtoupper(trim($designation->c_designation));
                        $storeRequired = in_array($desigName, ['CSA', 'C&A', 'SM']) ? 1 : 0;
                        @endphp
                        <option value="{{ $designation->n_designation_id }}" data-store="{{ $storeRequired }}"
                            {{ old('n_designation_id', $employee->n_designation_id) == $designation->n_designation_id ? 'selected' : '' }}>
                            {{ $designation->c_designation }}
                        </option>
                        @endforeach
                    </select>

                    @error('account_number')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="reporting_to"><i class="ti ti-user-check"></i>Reporting Manager</label>
                    <select name="reporting_to" id="reporting_to" class="form-select">
                        <option value="">Select Reporting Manager</option>

                        @if(isset($employees))
                        @foreach ($employees as $manager)
                        <option value="{{ $manager->n_employee_id }}"
                            {{ old('reporting_to', $employee->reporting_to) == $manager->n_employee_id ? 'selected' : '' }}>
                            {{ $manager->c_employee_name }}
                        </option>
                        @endforeach
                        @endif
                    </select>

                    @error('reporting_to')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Account Details -->
            <div class="efp-section" id="sec-account"><i class="ti ti-building-bank"></i>Account Details</div>
            <div class="efp-grid">
                <div class="efp-field">
                    <label for="account_number"><i class="ti ti-hash"></i>Account Number</label>
                    <input type="text" id="account_number" name="account_number"
                        value="{{ old('account_number', $employee->bank_account_number) }}"
                        data-message="Please add Account Number" class="form-control " placeholder="ACC-001">
                    @error('account_number')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field">
                    <label for="ifsc_code"><i class="ti ti-barcode"></i>IFSC Code</label>
                    <input type="text" id="ifsc_code" name="ifsc_code"
                        value="{{ old('ifsc_code', $employee->bank_ifsc) }}"
                        data-message="Please enter IFSC Code" class="form-control "
                        placeholder="Enter IFSC code">
                    @error('ifsc_code')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="bank_name"><i class="ti ti-building-bank"></i>Bank Name</label>
                    <input type="text" id="bank_name" name="bank_name"
                        value="{{ old('bank_name', $employee->bank_name) }}"
                        data-message="Please enter Bank name" class="form-control "
                        placeholder="Enter Bank Name">
                    @error('bank_name')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="efp-field">
                    <label for="branch_name"><i class="ti ti-map-2"></i>Branch Name</label>
                    <input type="text" id="branch_name" name="branch_name"
                        value="{{ old('branch_name') }}"
                        data-message="Please enter branch name" class="form-control "
                        placeholder="Enter Branch Name">
                    @error('branch_name')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Contact Section -->
            <div class="efp-section" id="sec-contact"><i class="ti ti-mail"></i>Communication & Status</div>
            <div class="efp-grid">
                <div class="efp-field">
                    <label for="c_employee_email"><i class="ti ti-mail"></i>Work Email Address *</label>
                    <input type="email" id="c_employee_email" name="c_employee_email"
                        value="{{ old('c_employee_email', $employee->c_employee_email) }}"
                        data-message="Please enter an Email Address" class="form-control " readonly>
                    @error('c_employee_email')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field">
                    <label for="c_status"><i class="ti ti-circle-half-2"></i>Account Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory"
                        data-message="Please select Status">
                        <option value="">Select Status</option>
                        <option value="Y" {{ old('c_status', $employee->c_status) === 'Y' ? 'selected' : '' }}>Active
                        </option>
                        <option value="N" {{ old('c_status', $employee->c_status) === 'N' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('c_status')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="efp-foot">
                <span class="efp-hint-secure"><i class="ti ti-asterisk"></i>Fields marked * are required</span>
                <a href="{{ route('admin.employees.index') }}" class="efp-btn efp-btn-secondary">Cancel</a>
                
                <button type="submit" id="btn_create" class="efp-btn efp-btn-primary">
                    <i class="ti ti-device-floppy"></i>Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {

    function loadReportingManagers() {

        let designation = $('#n_designation_id').val();

        console.log('Designation:', designation);

        if (!designation) {
            $('#reporting_to').html(
                '<option value="">Select Reporting Manager</option>'
            );
            return;
        }

        $.ajax({
            url: '/admin/employees/reporting-managers/' + designation,
            type: 'GET',

            success: function(data) {

                let options =
                    '<option value="">Select Reporting Manager</option>';

                $.each(data, function(index, emp) {

                    // Keep existing reporting manager selected
                    let selected = '';

                    if (emp.n_employee_id == "{{ $employee->reporting_to ?? '' }}") {
                        selected = 'selected';
                    }

                    options += `
                        <option value="${emp.n_employee_id}" ${selected}>
                            ${emp.c_employee_name}
                        </option>
                    `;
                });

                $('#reporting_to').html(options);
            },

            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }


    // When designation is changed manually
    $('#n_designation_id').on('change', function() {
        loadReportingManagers();
    });


    // IMPORTANT:
    // Run automatically when edit page loads
    loadReportingManagers();

});
</script>
@endpush