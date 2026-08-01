<?php $__env->startSection('content'); ?>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-bars text-primary me-2"></i>
                Create Menu
            </h4>
            <small class="text-muted">
                Add a new menu or submenu to the application.
            </small>
        </div>

        <a href="<?php echo e(route('admin.menus.index')); ?>" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>
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

        <form method="POST" action="<?php echo e(route('admin.menus.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Parent Menu
                    </label>

                    <select name="parent_id" class="form-select">
                        <option value="">-- Main Menu --</option>

                        <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($parent->id); ?>" <?php echo e(old('parent_id')==$parent->id ? 'selected' : ''); ?>>

                            <?php echo e($parent->name); ?>


                        </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Menu Name <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control"
                        placeholder="Enter menu name">

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Route Name
                    </label>

                    <input type="text" name="route_name" value="<?php echo e(old('route_name')); ?>" class="form-control"
                        placeholder="admin.users.index">

                    <small class="text-muted">
                        Leave empty if the menu is only a parent.
                    </small>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Icon
                    </label>

                    <input type="text" name="icon" value="<?php echo e(old('icon')); ?>" class="form-control"
                        placeholder="ti ti-users">

                    <small class="text-muted">
                        Example: ti ti-users
                    </small>

                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="<?php echo e(route('admin.menus.index')); ?>" class="btn btn-light me-2">

                    Cancel

                </a>

                <button class="btn btn-primary px-4">

                    <i class="fas fa-save me-1"></i>

                    Save Menu

                </button>

            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/menus/create.blade.php ENDPATH**/ ?>