<?php $__env->startSection('content'); ?>
<style>
/* Premium Design Tokens */
:root {
    --primary-green: #5E8D3D;
    --accent-orange: #5E8D3D;
    --deep-slate: #1e293b;
    --border-radius-lg: 18px;
    --input-shadow: 0 8px 20px rgba(94, 141, 61, 0.05);
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

.form-control,
.form-select {
    border-radius: 12px;
    padding: 0.9rem 1.2rem;
    background-color: #f8fafc;
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
    box-shadow: 0 10px 20px rgba(94, 141, 61, 0.15);
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-create-action:hover {
    background: #1F5C2E;
    box-shadow: 0 12px 25px rgba(94, 141, 61, 0.25);
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

/* Dashboard cards block */
.dash-cards-box {
    border: 1px solid #e6eee0;
    border-radius: 14px;
    background: #fbfdf9;
    padding: 16px 18px;
    margin-bottom: 14px;
}
.dash-cards-box .grp-title { font-weight: 700; color: #1F5C2E; }
.dash-cards-box a.dash-card-toggle { font-weight: 600; font-size: 0.8rem; color: var(--primary-green); text-decoration: none; }
.dash-cards-box a.dash-card-toggle:hover { text-decoration: underline; }
.dash-cards-box .form-check-input:checked { background-color: var(--primary-green); border-color: var(--primary-green); }
</style>

<div class="card designation-card mb-4">
    <div class="card-header-premium">
        <h5 class="page-main-title mb-0">Edit Designation</h5>
    </div>

    <div class="card-body p-4 p-md-5 pt-md-4">
        <form method="POST" id="frm_create" action="<?php echo e(route('admin.designations.update', $designation)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-4 pt-2">
                <label for="c_designation" class="form-label">Designation Name *</label>
                <input type="text" id="c_designation" name="c_designation" data-message="Please enter a Designation"
                    value="<?php echo e(old('c_designation', $designation->c_designation)); ?>" class="form-control mandatory">
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
                <label for="hierarchy_level" class="form-label">Hierarchy Level *</label>
                <input type="number" id="hierarchy_level" name="hierarchy_level" min="1"
                    value="<?php echo e(old('hierarchy_level', $designation->hierarchy_level)); ?>" class="form-control mandatory"
                    data-message="Please enter a Hierarchy Level">
                <?php $__errorArgs = ['hierarchy_level'];
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
                    <option value="">— None (top of the hierarchy) —</option>
                    <?php $__currentLoopData = $parentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($option->n_designation_id); ?>"
                            <?php echo e((string) old('parent_designation_id', $designation->parent_designation_id) === (string) $option->n_designation_id ? 'selected' : ''); ?>>
                            <?php echo e($option->c_designation); ?> (Level <?php echo e($option->hierarchy_level); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
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
                <label for="c_status" class="form-label">Status *</label>
                <select id="c_status" name="c_status" class="form-select mandatory" data-message="Please select a Status">
                    <option value="Y" <?php echo e(old('c_status', $designation->c_status) === 'Y' ? 'selected' : ''); ?>>Active</option>
                    <option value="N" <?php echo e(old('c_status', $designation->c_status) === 'N' ? 'selected' : ''); ?>>Inactive</option>
                </select>
                <?php $__errorArgs = ['c_status'];
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
                <input type="hidden" name="dashboard_cards_present" value="1">
                <label class="form-label">Dashboard Cards</label>
                <p class="text-muted small mb-3">
                    Tick the cards employees with this designation should see on their dashboard.
                    Unticked cards are hidden. This only hides cards &mdash; data access is unchanged,
                    and Super Admin always sees everything.
                </p>

                <?php $checkedCards = old('dashboard_cards', $selectedCards); ?>

                <?php $__currentLoopData = $cardGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="dash-cards-box">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="grp-title"><?php echo e($groupLabel); ?></span>
                            <span>
                                <a href="#" class="dash-card-toggle" data-group="<?php echo e($groupKey); ?>" data-state="1">Select all</a>
                                &middot;
                                <a href="#" class="dash-card-toggle" data-group="<?php echo e($groupKey); ?>" data-state="0">Clear</a>
                            </span>
                        </div>
                        <div class="row">
                            <?php $__currentLoopData = $cardCatalog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cardKey => $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(($card['group'] ?? '') === $groupKey): ?>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input dash-card-cb" type="checkbox"
                                                name="dashboard_cards[]" value="<?php echo e($cardKey); ?>"
                                                id="dc_<?php echo e($cardKey); ?>" data-group="<?php echo e($groupKey); ?>"
                                                <?php echo e(in_array($cardKey, $checkedCards, true) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="dc_<?php echo e($cardKey); ?>"><?php echo e($card['label']); ?></label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__errorArgs = ['dashboard_cards.*'];
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

            <div class="d-flex gap-3 pt-4 border-top">
                <button type="submit" id="btn_create" class="btn buttonSpc">Update</button>
                <a href="<?php echo e(route('admin.designations.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.dash-card-toggle').forEach(function (a) {
    a.addEventListener('click', function (e) {
        e.preventDefault();
        var on = this.dataset.state === '1';
        document.querySelectorAll('.dash-card-cb[data-group="' + this.dataset.group + '"]')
            .forEach(function (cb) { cb.checked = on; });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/designations/edit.blade.php ENDPATH**/ ?>