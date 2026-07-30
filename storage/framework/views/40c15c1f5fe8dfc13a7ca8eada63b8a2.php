<?php $__env->startSection('content'); ?>

<div class="card w-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Role Management</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-management.create')): ?>
        <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn btn-primary">
            + Create Role
        </a>
        <?php endif; ?>
    </div>

    <div class="card-body">

        <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Role Name</th>
                    <th width="120">Menus</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['role-management.edit', 'role-management.delete'])): ?>
                    <th width="180">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <tr>

                    <td><?php echo e($roles->firstItem() + $loop->index); ?></td>

                    <td><?php echo e($role->name); ?></td>

                    <td>
                        <span class="badge bg-info">
                            <?php echo e($role->menus->count()); ?>

                        </span>
                    </td>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['role-management.edit', 'role-management.delete'])): ?>
                    <td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-management.edit')): ?>
                        <a href="<?php echo e(route('admin.roles.edit',$role->id)); ?>" class="btn btn-sm btn-primary action-btn">
                            Edit
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-management.delete')): ?>
                        <form action="<?php echo e(route('admin.roles.destroy',$role->id)); ?>" method="POST" class="d-inline">

                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>

                            <button class="btn btn-sm btn-danger action-btn"
                                onclick="return confirm('Delete this role?')">
                                Delete
                            </button>

                        </form>
                        <?php endif; ?>

                    </td>
                    <?php endif; ?>

                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <tr>
                    <td colspan="4" class="text-center">
                        No Roles Found.
                    </td>
                </tr>

                <?php endif; ?>

            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            <?php echo e($roles->links()); ?>

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>