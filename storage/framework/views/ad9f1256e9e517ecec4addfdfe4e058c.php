

<?php $__env->startSection('content'); ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log Details
        </h5>

        <a href="<?php echo e(route('admin.field-log.history')); ?>" class="btn buttonSpc">
            Back
        </a>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3 mb-3">

                <div class="border rounded p-3">

                    <small>Date</small>

                    <h6 class="mt-2">
                        <?php echo e($fieldLog->work_date->format('d-m-Y')); ?>

                    </h6>

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="border rounded p-3">

                    <small>Check In</small>

                    <h6 class="mt-2">
                        <?php echo e($fieldLog->check_in_time->format('h:i A')); ?>

                    </h6>

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="border rounded p-3">

                    <small>Check Out</small>

                    <h6 class="mt-2">
                        <?php echo e(optional($fieldLog->check_out_time)->format('h:i A') ?? '--'); ?>

                    </h6>

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="border rounded p-3">

                    <small>Status</small>

                    <h6 class="mt-2">

                        <?php if($fieldLog->status=='Checked Out'): ?>
                        <span class="badge bg-success">Checked Out</span>
                        <?php else: ?>
                        <span class="badge bg-warning text-dark">Working</span>
                        <?php endif; ?>

                    </h6>

                </div>

            </div>

        </div>

        <div class="row mt-2">

            <div class="col-md-6">

                <label class="form-label">
                    Check In Remark
                </label>

                <textarea class="form-control" rows="3" readonly><?php echo e($fieldLog->check_in_remark); ?></textarea>

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Check Out Remark
                </label>

                <textarea class="form-control" rows="3" readonly><?php echo e($fieldLog->check_out_remark); ?></textarea>

            </div>

        </div>

        <hr>

        <?php
        $done = $fieldLog->tasks->where('status','Done')->count();
        $total = $fieldLog->tasks->count();
        $percent = $total > 0 ? round(($done / $total) * 100) : 0;
        ?>

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <strong>Progress</strong>

                    <strong><?php echo e($done); ?> / <?php echo e($total); ?></strong>

                </div>

                <div class="progress mt-3" style="height:20px;">

                    <div class="progress-bar bg-success" style="width:<?php echo e($percent); ?>%">

                        <?php echo e($percent); ?>%

                    </div>

                </div>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-bordered">

                <thead class="table-light">

                    <tr>

                        <th>#</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Pending Remark</th>
                        <th>Completed At</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $fieldLog->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td><?php echo e($key + 1); ?></td>

                        <td><?php echo e($task->task); ?></td>

                        <td>

                            <?php if($task->status=='Checked Out'): ?>

                            <span class="badge bg-success">
                                Done
                            </span>

                            <?php else: ?>

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                            <?php endif; ?>

                        </td>

                        <td><?php echo e($task->pending_remark ?: '--'); ?></td>

                        <td>

                            <?php echo e($task->completed_at ? $task->completed_at->format('d-m-Y h:i A') : '--'); ?>


                        </td>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="5" class="text-center">

                            No Tasks Found

                        </td>

                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/field-log/show.blade.php ENDPATH**/ ?>