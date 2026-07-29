<?php $__env->startSection('content'); ?>
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
        <form method="POST" id="frm_create" action="<?php echo e(route('admin.employees.store')); ?>">
            <?php echo csrf_field(); ?>

            <!-- Section 1: Identification -->
            <div class="form-section-header">
                <i class="ti ti-user-circle fs-5"></i> Identification
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_employee_code" class="form-label">Employee Code *</label>
                    <input type="text" id="c_employee_code" name="c_employee_code" value="<?php echo e(old('c_employee_code')); ?>"
                        data-message="Please add Employee Code" class="form-control mandatory" placeholder="EMP-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="c_employee_name" class="form-label">Employee Name *</label>
                    <input type="text" id="c_employee_name" name="c_employee_name" value="<?php echo e(old('c_employee_name')); ?>"
                        data-message="Please enter Employee Name" class="form-control mandatory"
                        placeholder="Enter full name">
                    <div class="text-danger mt-1 fs-2"></div>
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
                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $desigName = strtoupper(trim($designation->c_designation));
                        $storeRequired = in_array($desigName, ['CSA', 'C&A', 'SM']) ? 1 : 0;
                        ?>
                        <option value="<?php echo e($designation->n_designation_id); ?>" data-store="<?php echo e($storeRequired); ?>"
                            <?php echo e(old('n_designation_id') == $designation->n_designation_id ? 'selected' : ''); ?>>
                            <?php echo e($designation->c_designation); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

            

                <div class="col-12" id="operation_manager_div" style="display: none;">
                    <div class="p-3 bg-light rounded-3 border">
                        <label for="n_operation_manager_id" class="form-label">Operations Manager *</label>
                        <select id="n_operation_manager_id" name="n_operation_manager_id" class="form-select">
                            <option value="">Select Operations Manager</option>
                            <?php $__currentLoopData = $operationsUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($opUser->n_employee_id); ?>"
                                <?php echo e(old('n_operation_manager_id') == $opUser->n_employee_id ? 'selected' : ''); ?>>
                                <?php echo e($opUser->c_employee_name); ?> (<?php echo e($opUser->c_employee_code); ?>)
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text mt-2" style="font-size: 0.75rem;"><i class="ti ti-info-circle"></i> Each
                            cluster manager must be linked to an operations manager.</div>
                        <?php $__errorArgs = ['n_operation_manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

               

            </div>
            <!-- Section 3: Account Details -->
            <div class="form-section-header">
                <i class="ti ti-building-bank fs-5"></i>Account Details
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="account_number" class="form-label">Account Number *</label>
                    <input type="text" id="account_number" name="account_number" value="<?php echo e(old('account_number')); ?>"
                        data-message="Please add Account Number" class="form-control mandatory" placeholder="ACC-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">IFSC Code *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" value="<?php echo e(old('ifsc_code')); ?>"
                        data-message="Please enter IFSC Code" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    <div class="text-danger mt-1 fs-2"></div>
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
                        value="<?php echo e(old('c_employee_email')); ?>" data-message="Please enter an Email Address"
                        class="form-control mandatory" placeholder="example@company.com">
                    <div class="text-danger mt-1 fs-2">
                        <?php $__errorArgs = ['c_employee_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php echo e($message); ?>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="c_status" class="form-label">Employment Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory"
                        data-message="Please select Status">
                        <option value="">Select Status</option>
                        <option value="Y" <?php echo e(old('c_status') === 'Y' ? 'selected' : ''); ?>>Active</option>
                        <option value="N" <?php echo e(old('c_status') === 'N' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block mt-1">
                        Default employee password is <strong>Password@123</strong>
                    </small>
                </div>
            </div>

            <div class="d-flex gap-3 pt-4 border-top">
                <button type="button" id="btn_create" class="btn btn-create-item">
                    <i class="ti ti-plus me-1"></i> Create Employee
                </button>
                <a href="<?php echo e(route('admin.employees.index')); ?>"
                    class="btn btn-outline-secondary btn-cancel-custom">Cancel</a>
            </div>
        </form>
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

        // Operations pool input removed as per requirements
        // Cluster stores will be shown via the separate toggle logic below
    }
    designation.addEventListener('change', toggleStore);
    toggleStore();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
<script src="<?php echo e(asset('dist/js/custom.js?1')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/employees/create.blade.php ENDPATH**/ ?>