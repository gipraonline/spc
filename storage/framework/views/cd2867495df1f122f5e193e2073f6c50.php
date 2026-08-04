<?php $__env->startSection('content'); ?>

<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create Role</h5>

        <a href="<?php echo e(route('admin.roles.index')); ?>" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card-body">

        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            Please fix the errors below.
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.roles.store')); ?>">

            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">Role Name <span class="text-danger">*</span></label>

                <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>"
                    placeholder="Example: HR Department">

                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small class="text-danger"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn btn-primary">
                Save Role
            </button>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/roles/create.blade.php ENDPATH**/ ?>