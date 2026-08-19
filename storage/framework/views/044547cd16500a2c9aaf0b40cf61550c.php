<?php $__env->startSection('content'); ?>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-lock text-primary me-2"></i>
                Edit Permission
            </h4>

            <small class="text-muted">
                Update the permission name.
            </small>
        </div>

        <a href="<?php echo e(route('admin.permissions.index')); ?>" class="btn btn-outline-secondary rounded-pill">

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

        <form action="<?php echo e(route('admin.permissions.update',$permission->id)); ?>" method="POST">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Permission Name <span class="text-danger">*</span>

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="fas fa-lock"></i>

                    </span>

                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name',$permission->name)); ?>"
                        placeholder="Example: users.create" required>

                </div>

                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                <small class="text-danger">

                    <?php echo e($message); ?>


                </small>

                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <small class="text-muted mt-2 d-block">

                    Permission format: <strong>module.action</strong>
                    (Example: <code>users.create</code>)

                </small>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="<?php echo e(route('admin.permissions.index')); ?>" class="btn btn-light me-2">

                    Cancel

                </a>

                <button class="btn buttonSpc px-4">

                    <i class="fas fa-save me-1"></i>

                    Update Permission

                </button>

            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>

<style>
.card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 .125rem .5rem rgba(0, 0, 0, .05);
}

.card-header {
    padding: 20px 24px;
}

.card-body {
    padding: 28px;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control {
    height: 46px;
    border-radius: 8px;
    border: 1px solid #d9dee3;
}

.form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 .15rem rgba(105, 108, 255, .15);
}

.input-group-text {
    background: #f8f9fa;
    border: 1px solid #d9dee3;
    border-right: 0;
    border-radius: 8px 0 0 8px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.btn-primary {
    min-width: 180px;
}

.btn-light {
    min-width: 120px;
    border: 1px solid #dee2e6;
}

.alert {
    border-radius: 8px;
}

hr {
    opacity: .15;
    margin: 30px 0;
}
</style>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\neenu\OneDrive\Documents\gipraLaravel\SPC\spc\resources\views/admin/permissions/edit.blade.php ENDPATH**/ ?>