<?php $__env->startSection('content'); ?>
<style>
/* Premium Design Tokens */
:root {
    --primary-green: #1b3e86;
    --accent-orange: #1b3e86;
    --deep-slate: #1e293b;
    --border-radius-lg: 18px;
    --input-shadow: 0 8px 20px rgba(57, 181, 74, 0.05);
    --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
}

/* Architectural Layout */
.designation-card {
    background: #ffffff;
    border: 1px solid rgba(238, 242, 246, 0.8);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
}

/* Signature Accent Line */
.designation-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--primary-green) 0%, #abe9b3 100%);
    z-index: 10;
}

.card-header-premium {
    padding: 2.5rem 2.8rem 1rem;
    background: #fff;
    border: none;
}

.page-main-title {
    font-weight: 800;
    font-size: 1.5rem;
    color: var(--deep-slate);
    letter-spacing: -1px;
}

/* Field Group Styling */
.form-label {
    font-weight: 700;
    color: #475569;
    font-size: 0.9rem;
    margin-bottom: 0.8rem;
}

<<<<<<< HEAD .form-control,
.form-select {
    border-radius: 12px;
    padding: 0.9rem 1.2rem;

    =======.form-control,
    .form-select {
        border-radius: 12px;
        padding: 0.9rem 1.2rem;

        background-color: #f8fafc;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
        color: var(--deep-slate);
    }

    >>>>>>>6ece91f46f5b9050b27e04fd4893f79c9f9e0960 background-color: #f8fafc;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    color: var(--deep-slate);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    background-color: #ffffff;
    box-shadow: var(--input-shadow);
    transform: translateY(-1px);
}

/* Action Toolbar */
.btn-create-action {
    background: var(--primary-green);
    border: none;
    padding: 14px 45px;
    border-radius: 12px;
    font-weight: 800;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(57, 181, 74, 0.15);
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-create-action:hover {
    background: #1b3e86;
    box-shadow: 0 12px 25px rgba(57, 181, 74, 0.25);
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

.btn-cancel-action:hover {
    background: #f1f5f9;
}

/* Validation Spacing */
.text-danger {
    font-weight: 600;
    font-size: 0.75rem !important;
    margin-top: 6px !important;
}
</style>

<div class="card designation-card mb-4">
    <div class="card-header-premium">
        <h5 class="page-main-title mb-0">Add Designation</h5>
    </div>

    <div class="card-body p-4 p-md-5 pt-md-4">
        <form method="POST" id="frm_create" action="<?php echo e(route('admin.designations.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4 pt-2">
                <label for="c_designation" class="form-label">Designation Name *</label>
                <input type="text" id="c_designation" name="c_designation" data-message="Please enter a Designation"
                    value="<?php echo e(old('c_designation')); ?>" class="form-control mandatory" placeholder="e.g. Senior Manager">
                <?php $__errorArgs = ['c_designation'];
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
            <div class="mb-4">
                <label for="identifier" class="form-label">
                    Identifier <span class="text-danger">*</span>
                </label>

                <input type="text" id="identifier" name="identifier" value="<?php echo e(old('identifier')); ?>"
                    class="form-control mandatory" data-message="Please enter an Identifier" placeholder="e.g. SM">

                <?php $__errorArgs = ['identifier'];
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
            <div class="mb-3">
                <label class="form-label">
                    Hierarchy Level <span class="text-danger">*</span>
                </label>

                <input type="number" name="hierarchy_level" class="form-control" value="<?php echo e(old('hierarchy_level')); ?>"
                    min="1" placeholder="Example: 1">
            </div>

            <div class="mb-5">
                <label for="c_status" class="form-label">Status *</label>
                <select id="c_status" name="c_status" class="form-select mandatory"
                    data-message="Please select a Status ">
                    <option value="">Select Status</option>
                    <option value="Y" <?php echo e(old('c_status') === 'Y' ? 'selected' : ''); ?>>Active</option>
                    <option value="N" <?php echo e(old('c_status') === 'N' ? 'selected' : ''); ?>>Inactive</option>
                </select>

                <?php $__errorArgs = ['c_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger mt-1" id="status_error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="d-flex gap-3 pt-4 border-top">
                <button type="submit" id="btn_create" class="btn buttonSpc">
                    <i class="ti ti-plus fs-4"></i> Create
                </button>
                <a href="<?php echo e(route('admin.designations.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


<script>
document.getElementById('c_designation').addEventListener('input', function() {
    let error = this.parentElement.querySelector('.text-danger');
    if (error) error.remove();
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusField = document.getElementById('c_status');

    statusField.addEventListener('change', function() {
        let error = document.getElementById('status_error');

        if (this.value !== '') {
            if (error) {
                error.remove(); //  completely removes error
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/designations/create.blade.php ENDPATH**/ ?>