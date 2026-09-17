<?php $__env->startSection('content'); ?>

<style>
/* =========================================================
       Edit User Page
    ========================================================= */
.edit-user-page {
    --card-radius: 16px;
    --soft-bg: #f8fafc;
    --border-color: #e9edf3;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
}

.edit-user-page .card {
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
       User Preview
    ========================================================= */
.user-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #eef2f7;
}

.user-preview-icon {
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

.user-preview-label {
    font-size: .66rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .4px;
}

.user-preview-value {
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

.btn-update {
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
    .btn-update {
        flex: 1;
    }
}
</style>


<div class="container-fluid edit-user-page py-2">

    
    <div class="card page-header-card shadow-sm mb-4">

        <div class="card-body px-4 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="page-header-icon">
                        <i class="bi bi-person-gear"></i>
                    </div>

                    <div>

                        <div class="page-title">
                            Edit User
                        </div>

                        <div class="page-subtitle">
                            Update user details and manage the assigned system role.
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
                        Update the user's account details and assigned role.
                    </div>

                </div>

            </div>

        </div>


        <form action="<?php echo e(route('admin.users.update', $user->n_role_id)); ?>" method="POST">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>


            <div class="form-section">

                <div class="row g-4">


                    
                    <div class="col-lg-6">

                        <label for="name" class="form-label">

                            Full Name
                            <span class="required-mark">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input type="text" id="name" name="name" class="form-control"
                                value="<?php echo e(old('name', $user->c_name)); ?>" placeholder="Enter full name" required>

                        </div>

                        <div class="field-help">

                            <i class="bi bi-info-circle"></i>

                            Enter the user's display name.

                        </div>

                    </div>


                    
                    <div class="col-lg-6">

                        <label for="username" class="form-label">

                            Email Address
                            <span class="required-mark">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input type="email" id="username" name="username" class="form-control"
                                value="<?php echo e(old('username', $user->c_username)); ?>" placeholder="example@company.com"
                                required>

                        </div>

                        <div class="field-help">

                            <i class="bi bi-envelope"></i>

                            This email address is used as the user's login username.

                        </div>

                    </div>


                    
                    <div class="col-12">

                        <div class="user-preview">

                            <div class="user-preview-icon">
                                <i class="bi bi-person-check-fill"></i>
                            </div>

                            <div>

                                <div class="user-preview-label">
                                    Current Account
                                </div>

                                <div class="user-preview-value">
                                    <?php echo e($user->c_name); ?>

                                </div>

                            </div>

                        </div>

                    </div>


                    
                    <div class="col-12">

                        <label for="role" class="form-label">

                            Assigned Role
                            <span class="required-mark">*</span>

                        </label>

                        <select class="form-select" id="role" name="role" required>

                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($role->name); ?>" <?php echo e($user->hasRole($role->name) ? 'selected' : ''); ?>>

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

                                Changing the assigned role will automatically update
                                the user's menu access, permissions, and available
                                system features.

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


                    <button type="submit" class="btn btn-primary btn-update">

                        <i class="bi bi-check2-circle me-1"></i>

                        Update User

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>