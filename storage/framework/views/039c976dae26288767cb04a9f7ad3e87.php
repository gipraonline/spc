<?php $__env->startSection('content'); ?>

<style>
/* =========================================================
       Create User Page
    ========================================================= */
.create-user-page {
    --card-radius: 16px;
    --soft-bg: #f8fafc;
    --border-color: #e9edf3;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
}

.create-user-page .card {
    border-radius: var(--card-radius);
    border: 1px solid var(--border-color);
}

/* =========================================================
       Page Header
    ========================================================= */
.page-header-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.page-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text-primary);
}

.page-subtitle {
    font-size: .82rem;
    color: var(--text-muted);
    margin-top: 3px;
}

.page-header-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--bs-primary-rgb), .10);
    color: var(--bs-primary);
    font-size: 1.15rem;
}

/* =========================================================
       Form Card
    ========================================================= */
.form-card {
    overflow: hidden;
    background: #fff;
}

.form-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #eef2f7;
    background: #fff;
}

.section-title {
    font-size: .95rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 3px;
}

.section-subtitle {
    font-size: .76rem;
    color: var(--text-muted);
}

.section-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--bs-primary-rgb), .10);
    color: var(--bs-primary);
}

/* =========================================================
       Form Fields
    ========================================================= */
.form-section {
    padding: 24px;
}

.form-label {
    font-size: .78rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 7px;
}

.required-mark {
    color: #dc2626;
}

.input-group-text {
    min-width: 44px;
    justify-content: center;
    background: #f8fafc;
    border-color: #dfe5ec;
    color: #94a3b8;
}

.form-control,
.form-select {
    min-height: 43px;
    border-color: #dfe5ec;
    font-size: .84rem;
    color: #334155;
    box-shadow: none !important;
    transition: all .2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: rgba(var(--bs-primary-rgb), .55);
    box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .08) !important;
}

.form-control[readonly] {
    background-color: #f8fafc;
    color: #64748b;
    cursor: not-allowed;
}

.form-select {
    cursor: pointer;
}

.field-help {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 7px;
    font-size: .72rem;
    color: var(--text-muted);
}

.field-help i {
    font-size: .72rem;
}

/* =========================================================
       Employee Preview
    ========================================================= */
.employee-preview {
    display: none;
    margin-top: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #eef2f7;
}

.employee-preview.active {
    display: flex;
    align-items: center;
    gap: 10px;
}

.employee-preview-icon {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--bs-primary-rgb), .10);
    color: var(--bs-primary);
    font-size: .8rem;
}

.employee-preview-label {
    font-size: .66rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .4px;
}

.employee-preview-value {
    font-size: .78rem;
    font-weight: 600;
    color: #475569;
}

/* =========================================================
       Role Information
    ========================================================= */
.role-info {
    margin-top: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #eef2f7;
}

.role-info-title {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .75rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 4px;
}

.role-info-text {
    font-size: .72rem;
    color: #94a3b8;
    line-height: 1.5;
}

/* =========================================================
       Validation Alert
    ========================================================= */
.validation-alert {
    border: 0;
    border-radius: 12px;
    background: #fff1f2;
    color: #9f1239;
    padding: 13px 15px;
    font-size: .8rem;
}

.validation-alert-title {
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 700;
    margin-bottom: 6px;
}

.validation-alert ul {
    padding-left: 25px;
    margin-bottom: 0;
}

.validation-alert li {
    margin-bottom: 2px;
}

/* =========================================================
       Form Footer
    ========================================================= */
.form-footer {
    padding: 18px 24px;
    border-top: 1px solid #eef2f7;
    background: #fafbfc;
}

.btn {
    font-size: .8rem;
    font-weight: 600;
}

.btn-create {
    min-height: 40px;
    border-radius: 9px;
    padding: 0 20px;
}

.btn-cancel {
    min-height: 40px;
    border-radius: 9px;
    padding: 0 18px;
}

/* =========================================================
       Responsive
    ========================================================= */
@media (max-width: 767px) {
    .page-title {
        font-size: 1rem;
    }

    .page-header-card .card-body {
        padding: 16px !important;
    }

    .form-card-header,
    .form-section {
        padding: 18px;
    }

    .form-footer {
        padding: 15px 18px;
    }

    .form-footer .d-flex {
        width: 100%;
    }

    .btn-cancel,
    .btn-create {
        flex: 1;
    }
}
</style>


<div class="container-fluid create-user-page py-2">

    
    <div class="card page-header-card shadow-sm mb-4">
        <div class="card-body px-4 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="page-header-icon">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>

                    <div>
                        <div class="page-title">
                            Create User
                        </div>

                        <div class="page-subtitle">
                            Create a new system user and assign the appropriate role.
                        </div>
                    </div>

                </div>

                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary px-3">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>

            </div>

        </div>
    </div>


    
    <?php if($errors->any()): ?>
    <div class="alert validation-alert shadow-sm mb-4">

        <div class="validation-alert-title">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Please correct the following errors
        </div>

        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

    </div>
    <?php endif; ?>


    
    <div class="card form-card border-0 shadow-sm mb-4">

        
        <div class="form-card-header">

            <div class="d-flex align-items-center gap-3">

                <div class="section-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div>
                    <div class="section-title">
                        User Information
                    </div>

                    <div class="section-subtitle">
                        Select an employee and assign their system access role.
                    </div>
                </div>

            </div>

        </div>


        <form action="<?php echo e(route('admin.users.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-section">

                <div class="row g-4">

                    
                    <div class="col-lg-6">

                        <label for="employee" class="form-label">
                            Employee
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <select class="form-select" id="employee" name="employee_id" required>

                                <option value="">
                                    Select Employee
                                </option>

                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($employee->n_employee_id); ?>"
                                    data-email="<?php echo e($employee->c_employee_email); ?>"
                                    data-designation="<?php echo e($employee->designation?->c_designation); ?>"
                                    data-designation-identifier="<?php echo e($employee->designation?->identifier); ?>"
                                    <?php echo e(old('employee_id') == $employee->n_employee_id ? 'selected' : ''); ?>>

                                    <?php echo e($employee->c_employee_code); ?>

                                    -
                                    <?php echo e($employee->c_employee_name); ?>


                                </option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>

                        </div>

                        <div class="field-help">
                            <i class="bi bi-info-circle"></i>
                            Select the employee who will use this account.
                        </div>

                        
                        <div class="employee-preview" id="employeePreview">

                            <div class="employee-preview-icon">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>

                            <div>
                                <div class="employee-preview-label">
                                    Designation
                                </div>

                                <div class="employee-preview-value" id="designationPreview">
                                    —
                                </div>
                            </div>

                        </div>

                    </div>


                    
                    <div class="col-lg-6">

                        <label for="username" class="form-label">
                            Email Address
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input type="email" id="username" class="form-control" readonly
                                placeholder="Employee email will appear here">

                        </div>

                        <div class="field-help">
                            <i class="bi bi-lock"></i>
                            Email is automatically retrieved from the employee record.
                        </div>

                    </div>


                    
                    <div class="col-12">

                        <label for="role" class="form-label">
                            Assigned Role
                            <span class="required-mark">*</span>
                        </label>

                        <select class="form-select" id="role" name="role" required>

                            <option value="">
                                Select Role
                            </option>

                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($role->name); ?>" data-identifier="<?php echo e($role->identifier); ?>"
                                <?php echo e(old('role') == $role->name ? 'selected' : ''); ?>>

                                <?php echo e($role->name); ?>


                            </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <div class="role-info">

                            <div class="role-info-title">
                                <i class="bi bi-shield-check text-primary"></i>
                                Role & Permissions
                            </div>

                            <div class="role-info-text">
                                The assigned role determines the user's menu access,
                                permissions, and available system features.
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            
            <div class="form-footer">

                <div class="d-flex justify-content-end align-items-center gap-2">

                    <!-- <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-light border btn-cancel">

                        <i class="bi bi-x-lg me-1"></i>
                        Cancel

                    </a> -->

                    <button type="submit" class="btn btn-primary btn-create">

                        <i class="bi bi-person-plus-fill me-1"></i>
                        Create User

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>



<script>
document.addEventListener('DOMContentLoaded', function() {

    const employee = document.getElementById('employee');
    const email = document.getElementById('username');
    const role = document.getElementById('role');

    const employeePreview = document.getElementById('employeePreview');
    const designationPreview = document.getElementById('designationPreview');


    function fillEmployeeDetails() {

        const option = employee.options[employee.selectedIndex];

        if (!option || !option.value) {

            email.value = '';

            employeePreview.classList.remove('active');
            designationPreview.textContent = '—';

            return;
        }


        /* =========================================================
           Fill Employee Email
        ========================================================= */

        email.value = option.dataset.email || '';


        /* =========================================================
           Fill Employee Designation
        ========================================================= */

        const designation =
            option.dataset.designation || '';

        designationPreview.textContent =
            designation || 'Not specified';

        employeePreview.classList.add('active');


        /* =========================================================
           Get Designation Identifier
        ========================================================= */

        const designationIdentifier =
            option.dataset.designationIdentifier || '';


        /* =========================================================
           Reset Role
        ========================================================= */

        role.value = '';


        /* =========================================================
           Match Designation → Role
        ========================================================= */

        Array.from(role.options).forEach(function(roleOption) {

            const roleIdentifier =
                roleOption.dataset.identifier || '';

            if (
                roleIdentifier &&
                designationIdentifier &&
                roleIdentifier.toLowerCase() ===
                designationIdentifier.toLowerCase()
            ) {

                roleOption.selected = true;
            }

        });

    }


    /* =========================================================
       Employee Change
    ========================================================= */

    employee.addEventListener(
        'change',
        fillEmployeeDetails
    );


    /* =========================================================
       Run On Page Load
    ========================================================= */

    fillEmployeeDetails();

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/users/create.blade.php ENDPATH**/ ?>