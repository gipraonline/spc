<?php $__env->startSection('content'); ?>
<style>
/* Filter Card */
.filter-card-wrapper {
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 12px;
    margin: 1.5rem 2rem;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .02);
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
    background: rgba(93, 135, 255, .1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5d87ff;
}

.filter-header-sub span {
    font-size: .9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* Filter Container */
.premium-filter-container {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 30px;
}

/* Labels */
.custom-filter-group {
    position: relative;
}

.custom-filter-group label {
    display: block;
    margin-bottom: 12px;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Designation & Employee */
.styled-select,
.styled-textbox {
    height: 44px !important;
    border-radius: 16px !important;
}

/* Filter & Reset Buttons */
.btn-creative-filter {
    height: 44px !important;
    min-height: 44px;
    padding: 0 24px !important;
    border-radius: 16px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
}

.btn-secondary.btn-creative-filter {
    height: 44px !important;
}

/* Employee Suggestion */
#employee_suggestions {
    margin-top: 8px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
    z-index: 1000;
}

#employee_suggestions .list-group-item {
    border: 0;
    padding: 12px 16px;
    cursor: pointer;
}

#employee_suggestions .list-group-item:hover {
    background: #eff6ff;
}



/* Responsive */
@media (max-width:768px) {
    .premium-filter-container {
        padding: 15px;
    }

    .filter-card-wrapper {
        margin: 1rem;
        padding: 1rem;
    }
}
</style>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Employees</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.create')): ?>
        <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn btn-primary">
            Add Employee
        </a>
        <?php endif; ?>
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
            <form action="<?php echo e(route('admin.employees.index')); ?>" method="GET">

                <div class="row g-3 align-items-end">

                    <!-- Designation -->
                    <div class="col-md-3">
                        <div class="custom-filter-group">
                            <label for="n_designation_id">Designation</label>

                            <select id="n_designation_id" name="n_designation_id" class="form-select styled-select">

                                <option value="">Select Designation</option>

                                <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($designation->n_designation_id); ?>"
                                    <?php echo e(request('n_designation_id') == $designation->n_designation_id ? 'selected' : ''); ?>>
                                    <?php echo e($designation->c_designation); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>
                        </div>
                    </div>

                    <!-- Employee -->
                    <div class="col-md-5 position-relative">
                        <div class="custom-filter-group">
                            <label for="employee_search">Employee</label>

                            <input type="text" id="employee_search" name="employee_search"
                                value="<?php echo e(request('employee_search')); ?>" autocomplete="off"
                                placeholder="Search by Name or Code" class="form-control styled-textbox">

                            <input type="hidden" id="employee_id" name="employee_id">

                            <ul id="employee_suggestions" class="list-group position-absolute w-100"></ul>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-4">
                        <label class="invisible">Action</label>

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary btn-creative-filter flex-fill">
                                <i class="ti ti-search"></i>
                                Filter
                            </button>

                            <a href="<?php echo e(route('admin.employees.index')); ?>"
                                class="btn btn-secondary btn-creative-filter flex-fill">
                                <i class="ti ti-refresh"></i>
                                Reset
                            </a>

                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <div class="card-body p-4">
        <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e($message); ?>

        </div>
        <?php endif; ?>
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
                            <h6 class="fw-semibold mb-0">Account Number</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">IFSC</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Bank Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Branch Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Email</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['employees.edit','employees.delete'])): ?>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="border-bottom-0 text-center">
                            <span class="fw-normal"><?php echo e($employees->firstItem() + $key); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($employee->c_employee_code); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0"><?php echo e($employee->c_employee_name); ?></h6>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($employee->designation?->c_designation ?? '-'); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($employee->kycSubmission?->account_number ?? '-'); ?></span>
                        </td>


                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($employee->kycSubmission?->ifsc_code ?? '-'); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($employee->kycSubmission?->bank_name ?? '-'); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($employee->kycSubmission?->bank_branch ?? '-'); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($employee->c_employee_email ?? '-'); ?></span>
                        </td>


                        <td class="border-bottom-0">
                            <span
                                class="badge <?php echo e($employee->c_status === 'Y' ? 'bg-success' : 'bg-danger'); ?> rounded-3 fw-semibold">
                                <?php echo e(ucfirst($employee->c_status)); ?>

                            </span>
                        </td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['employees.edit','employees.delete'])): ?>
                        <td class="border-bottom-0">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.edit')): ?>
                            <a href="<?php echo e(route('admin.employees.edit', $employee)); ?>" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.delete')): ?>
                            <form method="POST" action="<?php echo e(route('admin.employees.destroy', $employee)); ?>"
                                class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <button type="submit" class="btn btn-sm btn-danger ms-2"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                            <?php endif; ?>

                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11" class="text-center">No employees found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($employees->links()); ?>

        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>

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
window.employees = <?php echo json_encode($employeesForSearch, 15, 512) ?>;
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>