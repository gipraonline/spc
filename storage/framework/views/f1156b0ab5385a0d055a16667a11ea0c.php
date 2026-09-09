<?php $__env->startSection('content'); ?>

<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Menu Management
        </h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-management.create')): ?>
        <a href="<?php echo e(route('admin.menus.create')); ?>" class="btn btn-primary">
            + Create Menu
        </a>
        <?php endif; ?>
    </div>

    <div class="card-body">

        <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        <table class="table table-bordered align-middle">

            <thead>
                <tr>
                    <th width="80">#</th>
                    <th>Menu Name</th>
                    <th>Route</th>
                    <th>Icon</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['menu-management.edit', 'menu-management.delete'])): ?>
                    <th width="180">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                
                <tr class="table-primary">
                    <td><?php echo e($loop->iteration); ?></td>

                    <td>
                        <i class="ti ti-folder me-2"></i>
                        <?php echo e($parent->name); ?>

                    </td>

                    <td><?php echo e($parent->route_name ?? '-'); ?></td>

                    <td><?php echo e($parent->icon ?? '-'); ?></td>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['menu-management.edit', 'menu-management.delete'])): ?>
                    <td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-management.edit')): ?>
                        <a href="<?php echo e(route('admin.menus.edit',$parent->id)); ?>" class="btn btn-sm btn-primary action-btn">
                            Edit
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-management.delete')): ?>
                        <form action="<?php echo e(route('admin.menus.destroy',$parent->id)); ?>" method="POST" class="d-inline">

                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>

                            <button class="btn btn-sm btn-danger action-btn action-btn"
                                onclick="return confirm('Delete this menu?')">
                                Delete
                            </button>

                        </form>
                        <?php endif; ?>

                    </td>
                    <?php endif; ?>
                </tr>

                
                <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr>

                    <td></td>

                    <td style="padding-left:60px;">
                        ├──
                        <i class="ti ti-chevron-right me-1"></i>
                        <?php echo e($child->name); ?>

                    </td>

                    <td><?php echo e($child->route_name); ?></td>

                    <td><?php echo e($child->icon); ?></td>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['menu-management.edit', 'menu-management.delete'])): ?>
                    <td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-management.edit')): ?>
                        <a href="<?php echo e(route('admin.menus.edit',$child->id)); ?>" class="btn btn-sm btn-primary action-btn">
                            Edit
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-management.delete')): ?>
                        <form action="<?php echo e(route('admin.menus.destroy',$child->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>

                            <button type="submit" class="btn btn-sm btn-danger action-btn"
                                onclick="return confirm('Delete this menu?')">
                                Delete
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>

                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <tr>
                    <td colspan="5" class="text-center">
                        No Menus Found.
                    </td>
                </tr>

                <?php endif; ?>

            </tbody>

        </table>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($menus->links('pagination::bootstrap-5')); ?>

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/menus/index.blade.php ENDPATH**/ ?>