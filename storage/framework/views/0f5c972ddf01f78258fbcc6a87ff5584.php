<?php $__env->startSection('content'); ?>
<style>
/* Premium Design Tokens */
:root {
    --primary-green: #1b6b49;
    --accent-orange: #1b6b49;
    --deep-slate: #1b241e;
    --border-radius-lg: 18px;
    --input-shadow: 0 8px 20px rgba(14, 61, 38, 0.05);
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
    color: #46514a;
    font-size: 0.9rem;
    margin-bottom: 0.8rem;
}

.form-control,
.form-select {
    border-radius: 12px;
    padding: 0.9rem 1.2rem;
    background-color: #f6f9f6;
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

.form-control[readonly] {
    background-color: #eef2ee;
    color: #6b756d;
    cursor: not-allowed;
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
    box-shadow: 0 10px 20px rgba(14, 61, 38, 0.15);
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-create-action:hover {
    background: #0e3d26;
    box-shadow: 0 12px 25px rgba(14, 61, 38, 0.25);
    transform: translateY(-2px);
}

.btn-cancel-action {
    border-radius: 12px;
    padding: 14px 30px;
    font-weight: 700;
    border: 2px solid #eef2ee;
    color: #6b756d;
    transition: all 0.2s ease;
}

.btn-cancel-action:hover {
    background: #eef2ee;
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

            <div class="mb-4">
                <label for="parent_designation_id" class="form-label">Reports To</label>
                <select id="parent_designation_id" name="parent_designation_id" class="form-select">
                    <option value="" data-level="0">— None (top of the hierarchy) —</option>
                    <?php $__currentLoopData = $parentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($option->n_designation_id); ?>" data-level="<?php echo e($option->hierarchy_level); ?>"
                            <?php echo e((string) old('parent_designation_id') === (string) $option->n_designation_id ? 'selected' : ''); ?>>
                            <?php echo e(str_repeat('— ', $option->depth)); ?><?php echo e($option->c_designation); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <small class="text-muted">Choose who this designation reports to. The level is set automatically.</small>
                <?php $__errorArgs = ['parent_designation_id'];
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
                <label for="level_preview" class="form-label">Hierarchy Level</label>
                <input type="text" id="level_preview" class="form-control" readonly>
            </div>

            <div class="mb-4">
                <label for="department_id" class="form-label">HR Department</label>
                <select id="department_id" name="department_id" class="form-select">
                    <option value="">— None —</option>
                    <?php $__currentLoopData = $hrDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dep->id); ?>"
                            <?php echo e((string) old('department_id') === (string) $dep->id ? 'selected' : ''); ?>>
                            <?php echo e($dep->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['department_id'];
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
                error.remove(); // completely removes error
            }
        }
    });

    // Level is worked out from "Reports To": parent's level + 1.
    const parentSel = document.getElementById('parent_designation_id');
    const levelBox = document.getElementById('level_preview');

    function showLevel() {
        const lvl = parseInt(parentSel.selectedOptions[0].dataset.level || 0, 10);
        levelBox.value = 'Level ' + (lvl + 1);
    }

    parentSel.addEventListener('change', showLevel);
    showLevel();

    // Pre-select the parent's department (only until HR picks one by hand).
    const parentDepts = <?php echo json_encode($parentDepartments, 15, 512) ?>;
    const deptSel = document.getElementById('department_id');
    let deptTouched = deptSel.value !== '';

    deptSel.addEventListener('change', () => deptTouched = true);

    parentSel.addEventListener('change', function () {
        if (deptTouched) return;
        deptSel.value = parentDepts[this.value] || '';
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc_new\resources\views/admin/designations/create.blade.php ENDPATH**/ ?>