@extends('layouts.app')

@section('content')
<style>
/* Premium Filter Section Styling */
.filter-card-wrapper {
    background: #ffffff;
    border-radius: 12px;
    margin: 1.5rem 2rem;
    padding: 1.5rem;
    border: 1px solid #eef2f6;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
}

.filter-card-wrapper:hover {
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    border-color: #dce3eb;
}

.filter-header-sub {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.25rem;
    color: #2a3547;
}

.filter-header-sub .icon-box {
    width: 32px;
    height: 32px;
    background: rgba(93, 135, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5d87ff;
}

.filter-header-sub span {
    font-weight: 700;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.custom-filter-group {
    position: relative;
}

.custom-filter-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #5a6a85;
    padding-left: 2px;
}

.styled-select {
    height: 54px !important;
    border-radius: 16px !important;
    border: 1.5px solid #dfe5ef !important;
    padding: 0 15px !important;
    font-size: 0.9rem !important;
    background-color: #fbfcfe !important;
    color: #2a3547 !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer !important;
}

.styled-select:focus {
    border-color: #5d87ff;
    box-shadow: 0 0 0 4px rgba(93, 135, 255, 0.1);
    background-color: #ffffff;
    outline: none;
}

.filter-action-container {
    display: flex;
    align-items: flex-end;
}

.btn-creative-filter {
    height: 54px;
    width: 100%;
    border-radius: 10px;
    font-weight: 600;
    background: linear-gradient(135deg, #5d87ff 0%, #4669e0 100%);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(93, 135, 255, 0.2);
}

.btn-creative-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(93, 135, 255, 0.35);
    background: linear-gradient(135deg, #4669e0 0%, #5d87ff 100%);
}

.btn-creative-filter:active {
    transform: translateY(0);
}

/* Animation for the filter section appear */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-card-wrapper {
    animation: slideIn 0.4s ease-out forwards;
}

@media (max-width: 767px) {
    .filter-action-container {
        margin-top: 1rem;
    }
}

.select2-results__options {
    max-height: 180px !important;
    overflow-y: auto !important;
}

.select2-container--default .select2-selection--single {
    border: none !important;
}

select#n_store_id,
.select2-container--default .select2-selection--single .select2-selection__rendered {
    height: 54px !important;
    border-radius: 10px !important;
    border: 1.5px solid #dfe5ef !important;
    padding: 0 15px !important;
    font-size: 0.9rem !important;
    background-color: #fbfcfe !important;
    color: #2a3547 !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 54px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 35px;
}


.premium-filter-container {
    background: #ffffff;
    border-radius: 24px;
    padding: 30px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04);
    margin-bottom: 2rem;
}

/* Label Styling */
.custom-filter-group label {
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 12px;
    display: block;
}

/* Input & Select Customization */
.styled-select,
.styled-textbox {
    height: 54px !important;
    background-color: #f8fafc !important;
    border-radius: 16px !important;
    color: #1e293b !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    padding-left: 20px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: none !important;
}

.styled-select:focus,
.styled-textbox:focus {
    background-color: #ffffff !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.08) !important;
    outline: none !important;
}

/* Column Spacing Adjustments */
.row.g-3 {
    --bs-gutter-x: 1.5rem;
}

/* Suggestions Menu */
#employee_suggestions {
    margin-top: 10px;
    border: none;
    border-radius: 18px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
    overflow: hidden;
    background: white;
    z-index: 1050;
}

#employee_suggestions .list-group-item {
    border: none;
    padding: 14px 20px;
    font-size: 14px;
    font-weight: 500;
    color: #475569;
    transition: all 0.2s;
}

#employee_suggestions .list-group-item:hover {
    background-color: #eff6ff;
    color: #2563eb;
}

/* Premium Filter Button */
.btn-creative-filter {
    height: 54px;
    width: 100%;
    border-radius: 16px;
    background: #1e293b;
    /* Deep slate for high contrast */
    border: none;
    color: #ffffff;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.1);
}

.btn-creative-filter:hover {
    background: #0f172a;
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(30, 41, 59, 0.2);
}

.btn-creative-filter:active {
    transform: translateY(0);
}

/* Subtle hover effect on the whole container */
.premium-filter-container:hover {
    border-color: #e2e8f0;
}

.select2-container {
    width: 100%;
}

#store_div {
    position: relative;
    margin: 0;
}

select#n_store_id,
.select2-container--default .select2-selection--single .select2-selection__rendered {
    border-radius: 16px !important;
    margin-top: -10px !important;
}

@media screen and (max-width:767px) {
    .premium-filter-container {
        padding: 0;
        border: none !important;
    }

    #store_div {
        position: relative;
        margin: 10px 0px;
    }
}
</style>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Employees</h5>
        @can('employees.create')
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            Add Employee
        </a>
        @endcan
    </div>

    <!-- Redesigned Creative Filter Section -->
    <div class="filter-card-wrapper">
        <div class="filter-header-sub">
            <div class="icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
            </div>
            <span>Refine Search</span>
        </div>

        <div class="premium-filter-container">
            <form action="{{route('admin.employees.index')}}" method="get">
                <div class="row g-3 align-items-center">

                    <!-- Designation Section -->
                    <div class="col-md-3">
                        <div class="custom-filter-group">
                            <label for="n_designation_id">Designation</label>
                            <select id="n_designation_id" name="n_designation_id" class="form-select styled-select">
                                <option value="">Select Designation</option>
                                @foreach($designations as $designation)
                                @php
                                $desigName = strtoupper(trim($designation->c_designation));
                                $storeRequired = in_array($desigName, ['CSA','C&A','SM']) ? 1 : 0;
                                @endphp
                                <option value="{{ $designation->n_designation_id }}" data-store="{{ $storeRequired }}"
                                    {{ request()->n_designation_id == $designation->n_designation_id ? 'selected' : '' }}>
                                    {{ $designation->c_designation }}
                                </option>
                                @endforeach
                            </select>
                            <div class="text-danger  fs-2"></div>
                        </div>
                    </div>
                    <!-- Stores Section -->
                    {{-- <div class="col-md-3" id="store_div">
                        <div class="custom-filter-group">
                            <label for="n_store_id">Stores</label>
                            <select id="n_store_id" name="n_store_id" class="form-select styled-select">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                <option value="{{ $store->n_store_id }}"
                                    {{ request()->n_store_id == $store->n_store_id ? 'selected' : '' }}>
                                    {{ $store->c_store_name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="text-danger  fs-2"></div>
                        </div>
                    </div> --}}
                    <!-- Employee Search Section -->
                    <div class="col-md-4 position-relative">
                        <div class="custom-filter-group">
                            <label for="employee_search">Employee</label>
                            <input type="text" id="employee_search" name="employee_search" autocomplete="off"
                                placeholder="Search by Name or Code" class="form-control styled-textbox">
                            <input type="hidden" id="employee_id" name="employee_id">
                            <ul id="employee_suggestions" class="list-group position-absolute w-100"></ul>
                        </div>
                    </div>
                    <!-- Action Section -->
                    <div class="col-md-2 filter-action-container">
                        <button style="margin-top: 11%;
    height: 54px;" type="submit" class="btn btn-primary btn-creative-filter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card-body p-4">
        @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
        @endif
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Slno</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Code</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Designation</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Store</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Operation Head</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Email</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        @canany(['employees.edit','employees.delete'])
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $key=>$employee)
                    <tr>
                        <td class="border-bottom-0 text-center">
                            <span class="fw-normal">{{ $employees->firstItem() + $key }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">{{ $employee->c_employee_code }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $employee->c_employee_name }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">{{ $employee->designation?->c_designation ?? '-' }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">{{ $employee->store?->c_store_name ?? '-' }}</span>
                        </td>


                        <td class="border-bottom-0">
                            <span class="fw-normal">{{  $employee->operation ?? $employee->poolUpline}}</span>
                        </td>

                        <td class="border-bottom-0">
                            <span class="fw-normal">{{ $employee->c_employee_email ?? '-' }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <span
                                class="badge {{ $employee->c_status === 'Y' ? 'bg-success' : 'bg-danger' }} rounded-3 fw-semibold">
                                {{ ucfirst($employee->c_status) }}
                            </span>
                        </td>
                        @canany(['employees.edit','employees.delete'])
                        <td class="border-bottom-0">

                            @can('employees.edit')
                            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            @endcan

                            @can('employees.delete')
                            <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-danger ms-2"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                            @endcan

                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No employees found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {

    $('#n_store_id').select2({
        placeholder: "Select Store",

        matcher: function(params, data) {

            // show first 5 initially
            if ($.trim(params.term) === '') {

                let index = $(data.element).index();

                return index <= 5 ? data : null;
            }

            // search filter
            if (typeof data.text === 'undefined') {
                return null;
            }

            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }

            return null;
        }
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let designation = document.getElementById('n_designation_id');
    let storeDiv = document.getElementById('store_div');
    let storeSelect = document.getElementById('n_store_id');

    function toggleStore() {
        let selectedOption = designation.options[designation.selectedIndex];
        let isRequired = selectedOption ? selectedOption.getAttribute('data-store') : null;

        if (isRequired === "1") {
            storeDiv.style.opacity = '1';
            storeDiv.style.pointerEvents = 'auto';
        } else {
            storeDiv.style.opacity = '0.5';
            storeDiv.style.pointerEvents = 'none';
            storeSelect.value = '';
        }
    }
    designation.addEventListener('change', toggleStore);
    toggleStore();
    // Employee search suggestions
    function toggleFiltersByEmployee() {

        let hasValue = employeeInput && employeeInput.value.trim().length > 0;

        if (hasValue) {
            // disable designation & store
            designation.disabled = true;
            storeSelect.disabled = true;
            storeDiv.style.opacity = '0.5';
            storeDiv.style.pointerEvents = 'none';

        } else {
            // enable back
            designation.disabled = false;
            storeSelect.disabled = false;
            toggleStore();
        }
    }
    // listen typing
    if (employeeInput) {
        employeeInput.addEventListener('input', toggleFiltersByEmployee);
    }
    // also trigger on page load (for back button case)
    toggleFiltersByEmployee();

    function toggleEmployeeState() {

        let designationVal = designation.value ? designation.value.trim() : '';
        let storeVal = storeSelect.value ? storeSelect.value.trim() : '';
        let shouldDisableEmployee = (designationVal !== '' || storeVal !== '');

        if (employeeInput) {

            if (shouldDisableEmployee) {

                employeeInput.disabled = true;
                employeeInput.value = '';
                let empId = document.getElementById('employee_id');
                if (empId) empId.value = '';
                // also clear suggestions if open
                let sug = document.getElementById('employee_suggestions');
                if (sug) sug.innerHTML = '';

            } else {

                employeeInput.disabled = false;
            }
        }
    }
    // trigger on changes
    designation.addEventListener('change', toggleEmployeeState);
    storeSelect.addEventListener('change', toggleEmployeeState);
    if (employeeInput) employeeInput.addEventListener('input', toggleEmployeeState);
    // initial run
    toggleEmployeeState();
});

// Pass employee data to JS for search suggestions
window.employees = @json($employeesForSearch);
</script>
@endpush
@endsection
