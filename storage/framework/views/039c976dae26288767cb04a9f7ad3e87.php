<?php $__env->startSection('content'); ?>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-plus text-primary me-2"></i>
                Create User
            </h4>

            <small class="text-muted">
                Create a new user and assign a role.
            </small>
        </div>

        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary rounded-pill">

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

        <form action="<?php echo e(route('admin.users.store')); ?>" method="POST">

            <?php echo csrf_field(); ?>

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Full Name <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>

                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>"
                            placeholder="Enter full name" required>

                    </div>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Email Address <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>

                        <input type="email" name="username" class="form-control" value="<?php echo e(old('username')); ?>"
                            placeholder="example@company.com" required>

                    </div>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Assign Role <span class="text-danger">*</span>

                </label>

                <select class="form-select" name="role" required>

                    <option value="">Select Role</option>

                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option value="<?php echo e($role->name); ?>" <?php echo e(old('role')==$role->name ? 'selected' : ''); ?>>

                        <?php echo e($role->name); ?>


                    </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

                <small class="text-muted">

                    The selected role determines the user's menu access and permissions.

                </small>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-light me-2">

                    Cancel

                </a>

                <button type="submit" class="btn buttonSpc px-4">

                    <i class="fas fa-save me-1"></i>

                    Create User

                </button>

            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/users/create.blade.php ENDPATH**/ ?>