<?php $__env->startSection('content'); ?>
<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Designations</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('designations.create')): ?>
        <a href="<?php echo e(route('admin.designations.create')); ?>" class="btn btn-primary">
             Add Designation
        </a>
        <?php endif; ?>

    </div>
    <div class="card-body p-4">
        <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e($message); ?>

        </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        <!-- <th class="border-bottom-0">
              <h6 class="fw-semibold mb-0">Actions</h6>
            </th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0"><?php echo e($designation->c_designation); ?></h6>
                        </td>
                        <td class="border-bottom-0">
                            <span
                                class="badge <?php echo e($designation->c_status === 'Y' ? 'bg-success' : 'bg-danger'); ?> rounded-3 fw-semibold">
                                <?php echo e(ucfirst($designation->c_status)); ?>

                            </span>
                        </td>
                        
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="text-center">No designations found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/designations/index.blade.php ENDPATH**/ ?>