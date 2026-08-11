

<?php $__env->startSection('content'); ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log Details
        </h5>

        <a href="<?php echo e(route('admin.admin-log.index')); ?>" class="btn btn-secondary">
            Back
        </a>

    </div>

    <div class="card-body">

        
        <div class="row">

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Employee</label>
                <p><?php echo e($fieldLog->admin->c_name ?? '-'); ?></p>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Date</label>
                <p><?php echo e($fieldLog->work_date->format('d-m-Y')); ?></p>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Check In</label>
                <p><?php echo e($fieldLog->check_in_time->format('h:i A')); ?></p>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Check Out</label>
                <p>
                    <?php echo e($fieldLog->check_out_time ? $fieldLog->check_out_time->format('h:i A') : '--'); ?>

                </p>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <label class="form-label fw-bold">
                    Check In Remark
                </label>

                <textarea class="form-control" rows="3" readonly><?php echo e($fieldLog->check_in_remark); ?></textarea>

            </div>

            <div class="col-md-6">

                <label class="form-label fw-bold">
                    Check Out Remark
                </label>

                <textarea class="form-control" rows="3" readonly><?php echo e($fieldLog->check_out_remark); ?></textarea>

            </div>

        </div>

        <hr>

        <!-- <?php
        $total = $fieldLog->tasks->count();
        $done = $fieldLog->tasks->where('status','Done')->count();
        $pending = $total - $done;
        $percent = $total > 0 ? round(($done/$total)*100) : 0;
        ?> -->

        <?php
        $total = $fieldLog->tasks->count();

        $done = $fieldLog->tasks
        ->where('status', 'Done')
        ->count();

        $inProgress = $fieldLog->tasks
        ->where('status', 'In Progress')
        ->count();

        $pending = $fieldLog->tasks
        ->where('status', 'Pending')
        ->count();

        $percent = $total > 0
        ? round(($done / $total) * 100)
        : 0;
        ?>



        
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4><?php echo e($total); ?></h4>

                        <small>Total Tasks</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4 class="text-success"><?php echo e($done); ?></h4>

                        <small>Completed</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4 class="text-warning"><?php echo e($pending); ?></h4>

                        <small>Pending</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border">

                    <div class="card-body text-center">

                        <h4><?php echo e($percent); ?>%</h4>

                        <small>Progress</small>

                    </div>

                </div>

            </div>

        </div>

        <div class="progress mb-4" style="height:20px;">

            <div class="progress-bar bg-success" style="width:<?php echo e($percent); ?>%">

                <?php echo e($percent); ?>%

            </div>

        </div>

        
        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Pending Remark</th>
                        <th>Completed At</th>
                        <th>Time Taken</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $fieldLog->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td><?php echo e($key+1); ?></td>

                        <td><?php echo e($task->task); ?></td>

                        <td>

                            <?php if($task->status=='Checked Out'): ?>

                            <span class="badge bg-success">
                                Done
                            </span>

                            <?php else: ?>

                            <span class="badge bg-warning text-dark">
                                In Progress
                            </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php echo e($task->pending_remark ?: '--'); ?>


                        </td>

                        <td>

                            <?php echo e($task->completed_at ? $task->completed_at->format('d-m-Y h:i A') : '--'); ?>


                        </td>

                        <td>

                            <?php if($task->completed_at): ?>

                            <?php echo e($fieldLog->check_in_time->diffForHumans($task->completed_at, [
                                    'parts' => 2,
                                    'short' => true,
                                    'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE
                                ])); ?>


                            <?php else: ?>

                            --

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="6" class="text-center">
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/admin-log/show.blade.php ENDPATH**/ ?>