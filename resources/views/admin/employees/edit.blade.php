@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #1b3e86;
    --text-dark: #1e293b;
    --border-radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

/* Main Record Card */
.edit-employee-card {
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header-styled {
    padding: 1.5rem 2rem;
    border-bottom: 2px solid #f8fafc;
    border-top: 4px solid var(--accent-orange);
    /* Orange for edit mode */
    background: #fff;
}

.card-title-custom {
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.5px;
}

/* Form Sectioning */
.section-label {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--accent-orange);
    letter-spacing: 1.2px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f5f9;
}

/* Inputs & Labels */
.form-label {
    font-weight: 700;
    color: #475569;
    font-size: 0.85rem;
    margin-bottom: 0.6rem;
}

.form-control,
.form-select {
    border-radius: 10px;
    padding: 0.8rem 1rem;

    background-color: #fdfdfe;
    transition: all 0.2s ease;
    font-weight: 500;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--accent-orange);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(247, 148, 30, 0.1);
}

/* Multiple Select Box */
#cluster_stores {
    background-image: none;
    padding: 10px;
    border-radius: 10px;
    border: 1.5px solid #edf2f7;
}

/* Submit Button */
.btn-update {
    background: var(--accent-orange);
    border: none;
    padding: 12px 35px;
    border-radius: 10px;
    font-weight: 700;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(247, 148, 30, 0.2);
}

.btn-update:hover {
    background: #1b3e86;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(247, 148, 30, 0.3);
}

.btn-cancel {
    border-radius: 10px;
    padding: 12px 25px;
    font-weight: 600;
}
</style>

<div class="card edit-employee-card mb-4">
    <div class="card-header-styled d-flex justify-content-between align-items-center">
        <h5 class="card-title-custom mb-0">Edit Employee Details</h5>
    </div>

    <div class="card-body p-4 p-md-5">
        <form method="POST" id="frm_create" action="{{ route('admin.employees.update', $employee) }}">
            @csrf @method('PUT')

            <!-- Identification Section -->
            <div class="section-label">
                <i class="ti ti-id fs-5"></i> Identification
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_employee_code" class="form-label">Employee Code *</label>
                    <input type="text" id="c_employee_code" name="c_employee_code"
                        value="{{ old('c_employee_code', $employee->c_employee_code) }}"
                        data-message="Please add Employee Code" class="form-control mandatory" disabled>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="c_employee_name" class="form-label">Employee Name *</label>
                    <input type="text" id="c_employee_name" name="c_employee_name"
                        value="{{ old('c_employee_name', $employee->c_employee_name) }}"
                        data-message="Please enter Employee Name" class="form-control mandatory">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>


            <div id="password_section" style="{{ $errors->has('password') ? 'display:block;' : 'display:none;' }}"
                class="mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                        @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            autocomplete="new-password">

                    </div>

                </div>
            </div>

            <!-- Role Section -->
            <div class="section-label">
                <i class="ti ti-briefcase fs-5"></i> Role & Assigment
            </div>


            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <input type="hidden" name="pre_designation_id" value="{{ $employee->n_designation_id}}">
                    <label for="n_designation_id" class="form-label">Designation *</label>
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
                    <div class="text-danger mt-1 fs-2"></div>
                </div>



                <div class="col-md-6" id="store_div">
                    <label for="n_store_id" class="form-label">Primary Store Assignment</label>
                    <!-- Search input -->
                    <input type="text" id="store_search" autocomplete="off"
                        value="{{ old('store_name', $employee->store->c_store_name ?? '') }}" class="form-control"
                        placeholder="Search Store...">

                    <!-- Hidden value -->
                    <input type="hidden" name="n_store_id" id="n_store_id"
                        value="{{ old('n_store_id', $employee->n_store_id ?? '') }}">

                    <!-- Results -->
                    <ul id="store_results" class="list-group mt-1"></ul>

                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6" id="operations_pool_div" style="display: none;">
                    <label for="n_pool_id" class="form-label">Operations Pool *</label>
                    <select id="n_pool_id" name="n_pool_id" class="form-select">
                        <option value="">Select Pool</option>
                        @foreach($pools as $pool)
                        @if(stripos($pool->c_pool_name, 'Operations') !== false)
                        <option value="{{ $pool->n_pool_id }}"
                            {{ old('n_pool_id', $employee->n_pool_id) == $pool->n_pool_id ? 'selected' : '' }}>
                            {{ $pool->c_pool_name }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-12" id="operation_manager_div" style="display: none;">
                    <div class="p-3 bg-light rounded-3 border">
                        <label for="n_operation_manager_id" class="form-label">Operations Manager *</label>
                        <select id="n_operation_manager_id" name="n_operation_manager_id" class="form-select">
                            <option value="">Select Operations Manager</option>
                            @foreach($operationsUsers as $opUser)
                            <option value="{{ $opUser->n_employee_id }}"
                                {{ old('n_operation_manager_id', $operationManager->n_employee_id ?? '') == $opUser->n_employee_id ? 'selected' : '' }}>
                                {{ $opUser->c_employee_name }} ({{ $opUser->c_employee_code }})
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2" style="font-size: 0.75rem;"><i class="ti ti-info-circle"></i> Each
                            cluster manager must be linked to an operations manager.</div>
                        @error('n_operation_manager_id')
                        <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12" id="cluster_stores_container" style="display: none;">
                    <div class="p-3 bg-light rounded-3 border">
                        <label class="form-label">Linked Stores (For Cluster Manager)</label>

                        <!-- Search -->
                        <input type="text" id="cluster_store_search" class="form-control mb-2"
                            placeholder="Search stores..." autocomplete="off">

                        <!-- Suggestions -->
                        <ul id="cluster_store_results" class="list-group mb-2"></ul>

                        <!-- Selected stores -->
                        <div id="selected_cluster_stores" class="d-flex flex-wrap gap-2"></div>

                        <!-- Hidden inputs -->
                        <div id="cluster_hidden_inputs"></div>
                    </div>
                </div>
            </div>
            <!-- Section 3: Account Details -->
            <div class="section-label">
                <i class="ti ti-building-bank fs-5"></i> Account Details
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="account_number" class="form-label">Account Number *</label>
                    <input type="text" id="account_number" name="account_number"
                        value="{{ old('account_number', $kyc ? $kyc->account_number : '') }}"
                        data-message="Please add Account Number" class="form-control mandatory" placeholder="ACC-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">IFSC Code *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code"
                        value="{{ old('ifsc_code', $kyc ? $kyc->ifsc_code : '') }}"
                        data-message="Please enter IFSC Code" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>



            <!-- Contact Section -->
            <div class="section-label">
                <i class="ti ti-mail fs-5"></i> Communication & Status
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-8">
                    <label for="c_employee_email" class="form-label">Work Email Address *</label>
                    <input type="email" id="c_employee_email" name="c_employee_email"
                        value="{{ old('c_employee_email', $employee->c_employee_email) }}"
                        data-message="Please enter an Email Address" class="form-control mandatory" disabled>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-4">
                    <label for="c_status" class="form-label">Account Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory"
                        data-message="Please select Status">
                        <option value="">Select Status</option>
                        <option value="Y" {{ old('c_status', $employee->c_status) === 'Y' ? 'selected' : '' }}>Active
                        </option>
                        <option value="N" {{ old('c_status', $employee->c_status) === 'N' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="d-flex gap-3 pt-4 border-top">
                <button type="submit" id="btn_create" class="btn btn-update">
                    <i class="ti ti-device-floppy me-1"></i> Update Record
                </button>

                <button type="button" id="btn_edit_password" class="btn btn-update">
                    <i class="ti ti-lock me-1"></i> Change Password
                </button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>


@push('scripts')

<script>
document.getElementById('btn_edit_password').addEventListener('click', function() {
    let section = document.getElementById('password_section');
    let inputs = section.querySelectorAll('input');

    if (section.style.display === 'none') {
        section.style.display = 'block';

        //  Force clear autofill
        inputs.forEach(input => {
            input.value = '';
            input.setAttribute('value', '');
        });

    } else {
        section.style.display = 'none';

        inputs.forEach(input => {
            input.value = '';
            input.setAttribute('value', '');
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let designation = document.getElementById('n_designation_id');
    let storeDiv = document.getElementById('store_div');
    let storeSelect = document.getElementById('n_store_id');
    let poolDiv = document.getElementById('operations_pool_div');
    let poolSelect = document.getElementById('n_pool_id');

    function toggleStore() {
        let selectedOption = designation.options[designation.selectedIndex];
        let isRequired = selectedOption ? selectedOption.getAttribute('data-store') : null;
        let designName = selectedOption ? selectedOption.text.trim().toUpperCase() : '';

        if (isRequired === "1") {
            storeDiv.style.opacity = '1';
            storeDiv.style.pointerEvents = 'auto';
            storeSelect.setAttribute('required', 'required');
            storeSelect.classList.add('mandatory');
        } else {
            storeDiv.style.opacity = '0.5';
            storeDiv.style.pointerEvents = 'none';
            storeSelect.removeAttribute('required');
            storeSelect.classList.remove('mandatory');
            storeSelect.value = '';
            if (storeSelect.nextElementSibling) {
                storeSelect.nextElementSibling.innerText = '';
            }
        }

        if (designName === 'OPERATIONS') {
            poolDiv.style.display = 'block';
            poolSelect.classList.add('mandatory');
        } else {
            poolDiv.style.display = 'none';
            poolSelect.classList.remove('mandatory');
            poolSelect.value = '';
        }
    }
    designation.addEventListener('change', toggleStore);
    toggleStore();
});
</script>

{{--
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const designationSelect = document.getElementById('n_designation_id');
                const clusterContainer = document.getElementById('cluster_stores_container');

                function toggleClusterStores() {
                    if (!designationSelect || !clusterContainer) return;
                    const selectedOption = designationSelect.options[designationSelect.selectedIndex];
                    if (selectedOption && selectedOption.text.trim().toUpperCase() === 'CLUSTER') {
                        clusterContainer.style.display = 'block';
                    } else {
                        clusterContainer.style.display = 'none';
                    }
                }

                designationSelect.addEventListener('change', toggleClusterStores);
                toggleClusterStores();
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const designationSelect = document.getElementById('n_designation_id');
                const clusterContainer = document.getElementById('cluster_stores_container');
                const clusterManagerDiv = document.getElementById('cluster_manager_div');
                const clusterManagerSelect = document.getElementById('n_cluster_manager_id');
                const operationManagerDiv = document.getElementById('operation_manager_div');
                const operationManagerSelect = document.getElementById('n_operation_manager_id');

                function toggleClusterManager() {
                    if (!designationSelect || !clusterContainer || !clusterManagerDiv || !operationManagerDiv) return;
                    const selectedOption = designationSelect.options[designationSelect.selectedIndex];
                    const designName = selectedOption ? selectedOption.text.trim().toUpperCase() : '';

                    if (designName === 'CLUSTER') {
                        clusterContainer.style.display = 'block';
                        clusterManagerDiv.style.display = 'none';
                        clusterManagerSelect.classList.remove('mandatory');

                        operationManagerDiv.style.display = 'block';
                        operationManagerSelect.classList.add('mandatory');
                    } else if (designName === 'OPERATIONS') {
                        clusterContainer.style.display = 'none';
                        clusterManagerDiv.style.display = 'none'; // Previously 'block', now 'none' as requested
                        clusterManagerSelect.classList.remove('mandatory');

                        operationManagerDiv.style.display = 'none';
                        operationManagerSelect.classList.remove('mandatory');
                    } else {
                        clusterContainer.style.display = 'none';
                        clusterManagerDiv.style.display = 'none';
                        clusterManagerSelect.classList.remove('mandatory');

                        operationManagerDiv.style.display = 'none';
                        operationManagerSelect.classList.remove('mandatory');
                    }
                }

                designationSelect.addEventListener('change', toggleClusterManager);
                toggleClusterManager();
            });


        </script>
        --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    const designationSelect = document.getElementById('n_designation_id');
    const clusterContainer = document.getElementById('cluster_stores_container');
    const clusterManagerDiv = document.getElementById('cluster_manager_div');
    const clusterManagerSelect = document.getElementById('n_employee_id');
    const operationManagerDiv = document.getElementById('operation_manager_div');
    const operationManagerSelect = document.getElementById('n_operation_manager_id');

    function handleDesignationChange() {

        if (!designationSelect) return;

        const selectedOption = designationSelect.options[designationSelect.selectedIndex];
        const designName = selectedOption ? selectedOption.text.trim().toUpperCase() : '';

        // Reset everything first (clean approach)
        clusterContainer && (clusterContainer.style.display = 'none');
        clusterManagerDiv && (clusterManagerDiv.style.display = 'none');
        operationManagerDiv && (operationManagerDiv.style.display = 'none');

        clusterManagerSelect && clusterManagerSelect.classList.remove('mandatory');
        operationManagerSelect && operationManagerSelect.classList.remove('mandatory');

        // Apply conditions
        if (designName === 'CLUSTER') {
            clusterContainer && (clusterContainer.style.display = 'block');

            operationManagerDiv && (operationManagerDiv.style.display = 'block');
            operationManagerSelect && operationManagerSelect.classList.add('mandatory');

        } else if (designName === 'OPERATIONS') {
            // Everything already hidden (based on your requirement)
        }
    }

    designationSelect.addEventListener('change', handleDesignationChange);

    // Run on page load
    handleDesignationChange();
});

//Auto Suggest Store List
window.stores = @json($stores);

// Cluster Linked Stores


window.clusterStores = @json($clusterStoresData);
window.preselectedClusterStores = @json($clusterIds);
</script>
<script src="{{asset('dist/js/custom.js?1')}}"></script>
@endpush

@endsection