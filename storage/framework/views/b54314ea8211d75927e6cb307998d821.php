

<?php $__env->startSection('content'); ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log History
        </h5>

        <a href="<?php echo e(route('admin.field-log.index')); ?>" class="btn buttonSpc">
            Today's Log
        </a>

    </div>

    <div class="card-body p-0">

        <table class="table table-bordered table-hover mb-0">

            <thead>

                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Tasks</th>
                    <th>Status</th>
                    <th width="120">Action</th>
                </tr>

            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $fieldLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <tr>

                    <td><?php echo e($log->work_date->format('d-m-Y')); ?></td>

                    <td><?php echo e($log->check_in_time->format('h:i A')); ?></td>

                    <td>
                        <?php echo e(optional($log->check_out_time)->format('h:i A') ?? '--'); ?>

                    </td>

                    <td><?php echo e($log->tasks_count); ?></td>

                    <td>

                        <?php if($log->status=='Checked Out'): ?>

                        <span class="badge bg-success">
                            Checked Out
                        </span>

                        <?php else: ?>

                        <span class="badge bg-warning">
                            Working
                        </span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <a href="<?php echo e(route('admin.field-log.show',$log->id)); ?>" class="btn btn-sm buttonSpc">

                            View

                        </a>

                    </td>

                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <tr>

                    <td colspan="6" class="text-center">

                        No Records Found

                    </td>

                </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <div class="card-footer">

        <?php echo e($fieldLogs->links()); ?>


    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/field-log/history.blade.php ENDPATH**/ ?>