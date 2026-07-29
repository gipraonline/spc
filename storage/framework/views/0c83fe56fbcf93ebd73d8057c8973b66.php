<?php $__env->startSection('content'); ?>
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
        <form method="POST" id="frm_create" action="<?php echo e(route('admin.employees.update', $employee)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <!-- Identification Section -->
            <div class="section-label">
                <i class="ti ti-id fs-5"></i> Identification
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_employee_code" class="form-label">Employee Code *</label>
                    <input type="text" id="c_employee_code" name="c_employee_code"
                        value="<?php echo e(old('c_employee_code', $employee->c_employee_code)); ?>"
                        data-message="Please add Employee Code" class="form-control mandatory" disabled>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="c_employee_name" class="form-label">Employee Name *</label>
                    <input type="text" id="c_employee_name" name="c_employee_name"
                        value="<?php echo e(old('c_employee_name', $employee->c_employee_name)); ?>"
                        data-message="Please enter Employee Name" class="form-control mandatory">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>


            <div id="password_section" style="<?php echo e($errors->has('password') ? 'display:block;' : 'display:none;'); ?>"
                class="mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

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
                    <input type="hidden" name="pre_designation_id" value="<?php echo e($employee->n_designation_id); ?>">
                    <label for="n_designation_id" class="form-label">Designation *</label>
                    <select id="n_designation_id" name="n_designation_id" class="form-select mandatory "
                        data-message="Please select a Designation">
                        <option value="">Select Designation</option>
                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $desigName = strtoupper(trim($designation->c_designation));
                        $storeRequired = in_array($desigName, ['CSA', 'C&A', 'SM']) ? 1 : 0;
                        ?>
                        <option value="<?php echo e($designation->n_designation_id); ?>" data-store="<?php echo e($storeRequired); ?>"
                            <?php echo e(old('n_designation_id', $employee->n_designation_id) == $designation->n_designation_id ? 'selected' : ''); ?>>
                            <?php echo e($designation->c_designation); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
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
                        value="<?php echo e(old('account_number', $kyc ? $kyc->account_number : '')); ?>"
                        data-message="Please add Account Number" class="form-control mandatory" placeholder="ACC-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">IFSC Code *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code"
                        value="<?php echo e(old('ifsc_code', $kyc ? $kyc->ifsc_code : '')); ?>"
                        data-message="Please enter IFSC Code" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">Bank Name *</label>
                    <input type="text" id="bank_name" name="bank_name"
                        value="<?php echo e(old('bank_name', $kyc ? $kyc->bank_name : '')); ?>"
                        data-message="Please enter Bank name" class="form-control mandatory"
                        placeholder="Enter Bank Name">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">Bank Name *</label>
                    <input type="text" id="branch_name" name="branch_name"
                        value="<?php echo e(old('bank_branch', $kyc ? $kyc->bank_branch : '')); ?>"
                        data-message="Please enter branch name" class="form-control mandatory"
                        placeholder="Enter Branch Name">
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
                        value="<?php echo e(old('c_employee_email', $employee->c_employee_email)); ?>"
                        data-message="Please enter an Email Address" class="form-control mandatory" readonly>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-4">
                    <label for="c_status" class="form-label">Account Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory"
                        data-message="Please select Status">
                        <option value="">Select Status</option>
                        <option value="Y" <?php echo e(old('c_status', $employee->c_status) === 'Y' ? 'selected' : ''); ?>>Active
                        </option>
                        <option value="N" <?php echo e(old('c_status', $employee->c_status) === 'N' ? 'selected' : ''); ?>>Inactive
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
                <a href="<?php echo e(route('admin.employees.index')); ?>" class="btn btn-outline-secondary btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/employees/edit.blade.php ENDPATH**/ ?>