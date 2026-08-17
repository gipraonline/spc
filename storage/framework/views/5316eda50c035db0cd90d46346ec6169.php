<?php $__env->startSection('content'); ?>

<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Management</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-management.create')): ?>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn buttonSpc">
            + Create User
        </a>
        <?php endif; ?>
    </div>

    <div class="card-body">

        <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>


            <?php if(session('password')): ?>
            <hr class="my-2">
            <strong>Temporary Password:</strong>
            <span class="text-danger"><?php echo e(session('password')); ?></span>
            <br>
            <small class="text-muted">
                Please share this password securely with the user.
            </small>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th width="80">#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-management.edit', 'user-management.delete'])): ?>
                    <th width="180">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <tr>

                    <td><?php echo e($users->firstItem() + $loop->index); ?></td>

                    <td><?php echo e($user->c_name); ?></td>

                    <td><?php echo e($user->c_username); ?></td>

                    <td><?php echo e($user->roles->pluck('name')->implode(', ')); ?></td>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-management.edit', 'user-management.delete'])): ?>
                    <td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-management.edit')): ?>
                        <a href="<?php echo e(route('admin.users.edit', $user->n_role_id)); ?>"
                            class="btn btn-primary btn-sm action-btn">
                            Edit
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-management.delete')): ?>
                        <form action="<?php echo e(route('admin.users.destroy', $user->n_role_id)); ?>" method="POST"
                            class="d-inline">

                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>

                            <button type="submit" class="btn btn-danger btn-sm action-btn"
                                onclick="return confirm('Delete this user?')">
                                Delete
                            </button>

                        </form>
                        <?php endif; ?>

                    </td>
                    <?php endif; ?>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <tr>
                    <td colspan="5" class="text-center">
                        No Users Found.
                    </td>
                </tr>

                <?php endif; ?>

            </tbody>

        </table>
        <div class="d-flex justify-content-center mt-3">
            <?php echo e($users->links()); ?>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<style>
.action-btn {
    width: 60px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}
</style>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/users/index.blade.php ENDPATH**/ ?>