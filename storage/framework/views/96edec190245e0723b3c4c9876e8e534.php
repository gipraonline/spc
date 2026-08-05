<?php $__env->startSection('content'); ?>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <?php echo e(isset($selectedMenu) ? 'Add Permission' : 'Create Permission'); ?>

        </h4>

        <a href="<?php echo e(route('admin.permissions.index')); ?>" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card-body">

        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.permissions.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            
            <div class="mb-3">

                <label class="form-label">Menu</label>

                <?php if(!empty($selectedModule)): ?>

                <input type="text" class="form-control" value="<?php echo e(ucfirst($selectedModule)); ?>" readonly>

                <input type="hidden" name="module" value="<?php echo e(strtolower($selectedModule)); ?>">

                <?php else: ?>

                <select name="module" class="form-select" required>

                    <option value="">Select Menu</option>

                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option value="<?php echo e(strtolower($parent->name)); ?>">
                        <?php echo e($parent->name); ?>

                    </option>

                    <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option value="<?php echo e(strtolower($child->name)); ?>">
                        └── <?php echo e($child->name); ?>

                    </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

                <?php endif; ?>

            </div>

            <?php
            $actions = [
            // CRUD
            'view',
            'create',
            'edit',
            'delete',

            // Reports
            'export',
            'upload',
            'calculate',
            'approve',
            'reject',
            'view-details',
            'confirm',
            'cancel',
            'process-batch',
            'add-sale',
            'follow-up',

            // Dashboard Cards
            'employees-card',
            'stores-card',
            'products-card',
            'sales-card',
            'incentives-card',
            'centreal-sales-card',
            'centreal-incentives-card',
            'vanitham-sales-card',
            'vanitham-incentives-card',

            // Dashboard Data Cards
            'recent-sales-card',
            'top-stores-card',
            'pending-sales-card',
            'top-centreal-performers-card',
            'top-vanitham-performers-card',
            ];
            ?>
            <div class="mb-3">

                <label class="form-label">Actions</label>

                <div class="row">

                    <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if(!isset($existingActions) || !in_array($action,$existingActions)): ?>

                    <div class="col-md-3 mb-2">

                        <div class="form-check">

                            <input class="form-check-input" type="checkbox" name="actions[]" value="<?php echo e($action); ?>"
                                id="<?php echo e($action); ?>">

                            <label class="form-check-label" for="<?php echo e($action); ?>">
                                <?php echo e(ucfirst($action)); ?>

                            </label>

                        </div>

                    </div>

                    <?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            </div>

            <?php if(isset($existingActions) && count($existingActions)): ?>

            <div class="alert alert-info">

                <strong>Already Exists :</strong>

                <?php $__currentLoopData = $existingActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge bg-success">
                    <?php echo e(ucfirst($action)); ?>

                </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>

            <?php endif; ?>

            <button class="btn buttonSpc">
                Save
            </button>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/permissions/create.blade.php ENDPATH**/ ?>