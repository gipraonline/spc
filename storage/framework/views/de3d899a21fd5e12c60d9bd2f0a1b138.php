

<?php $__env->startSection('content'); ?>

<div class="card w-100 position-relative overflow-hidden">

    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">

        <h5 class="card-title fw-semibold mb-0">
            Field Log
        </h5>

        <?php if(!$fieldLog): ?>

        <span class="badge bg-warning">
            Not Checked In
        </span>

        <?php else: ?>

        <span class="badge bg-success">
            Working
        </span>

        <?php endif; ?>

    </div>

    <div class="card-body p-4">

        <?php if(session('success')): ?>

        <div class="alert alert-success">

            <?php echo e(session('success')); ?>


        </div>

        <?php endif; ?>

        <?php if($errors->any()): ?>

        <div class="alert alert-danger">

            <ul class="mb-0">

                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <li><?php echo e($error); ?></li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>

        </div>

        <?php endif; ?>


        <?php if(!$fieldLog): ?>

        

        <form action="">

            <?php echo csrf_field(); ?>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Date

                    </label>

                    <input type="text" class="form-control" value="<?php echo e(now()->format('d-m-Y')); ?>" readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Time

                    </label>

                    <input type="text" class="form-control" value="<?php echo e(now()->format('h:i A')); ?>" readonly>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Check In Remarks

                </label>

                <textarea class="form-control" rows="3" name="check_in_remark"
                    placeholder="Enter remarks..."></textarea>

            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">

                    Today's Tasks

                </h5>

                <button type="button" id="addTask" class="btn buttonSpc">

                    + Add Task

                </button>

            </div>

            <div id="taskArea">

                <div class="row task-row mb-3">

                    <div class="col-md-10">

                        <input type="text" name="tasks[]" class="form-control" placeholder="Enter Task">

                    </div>

                    <div class="col-md-2">

                        <button type="button" class="btn btn-danger removeTask">
                            &times;
                        </button>

                    </div>

                </div>

            </div>

            <div class="text-end mt-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('field-log.check-in')): ?>
                <button type="submit" class="btn buttonSpc">
                    Check In
                </button>
                <?php endif; ?>

            </div>

        </form>

        <?php else: ?>

        

        <div class="row">

            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small>Date</small>

                    <h6 class="mt-2">

                        <?php echo e($fieldLog->work_date->format('d-m-Y')); ?>


                    </h6>

                </div>

            </div>

            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small>Check In</small>

                    <h6 class="mt-2">

                        <?php echo e($fieldLog->check_in_time->format('h:i A')); ?>


                    </h6>

                </div>

            </div>

            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small>Check Out</small>

                    <h6 class="mt-2">

                        <?php echo e(optional($fieldLog->check_out_time)->format('h:i A') ?? '--'); ?>


                    </h6>

                </div>

            </div>

            <div class="col-md-3">

                <div class="border rounded p-3">

                    <small>Status</small>

                    <h6 class="mt-2">

                        <span class="badge bg-success">

                            <?php echo e($fieldLog->status); ?>


                        </span>

                    </h6>

                </div>

            </div>

        </div>

        <hr>

        

        <div class="card mt-4 shadow-sm">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Today's Tasks
                </h5>

            </div>

            <div class="card-body p-0">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="5%">#</th>

                            <th>Task</th>

                            <th width="15%">Status</th>

                            <th width="30%">Pending Remark</th>
                            <th width="15%">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $done = 0;
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $fieldLog->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <?php
                        if($task->status=='Done'){
                        $done++;
                        }
                        ?>

                        <tr>

                            <td><?php echo e($key+1); ?></td>

                            <td><?php echo e($task->task); ?></td>

                            <td>

                                <?php if($task->status=='Done'): ?>

                                <span class="badge bg-success">
                                    Done
                                </span>

                                <?php else: ?>

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php echo e($task->pending_remark); ?>


                            </td>

                            <td>

                                <button class="btn btn-sm buttonSpc editTaskBtn" data-id="<?php echo e($task->id); ?>"
                                    data-task="<?php echo e($task->task); ?>" data-status="<?php echo e($task->status); ?>"
                                    data-remark="<?php echo e($task->pending_remark); ?>" data-bs-toggle="modal"
                                    data-bs-target="#taskModal">

                                    Update

                                </button>

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

        <?php

        $total=count($fieldLog->tasks);

        $percent=$total>0 ? round(($done/$total)*100) : 0;

        ?>

        <div class="card mt-3">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <strong>Today's Progress</strong>

                    <strong>

                        <?php echo e($done); ?> / <?php echo e($total); ?>


                    </strong>

                </div>

                <div class="progress mt-3" style="height:20px;">

                    <div class="progress-bar bg-success" style="width:<?php echo e($percent); ?>%">

                        <?php echo e($percent); ?>%

                    </div>

                </div>

            </div>

        </div>

        

        <div class="card mt-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Check Out

                </h5>

            </div>

            <div class="card-body">

                <?php if($done!=$total): ?>

                <div class="alert alert-warning">

                    Complete all tasks before Check Out.

                </div>

                <?php endif; ?>

                <form action="<?php echo e(route('admin.field-log.checkout')); ?>" method="POST">

                    <?php echo csrf_field(); ?>

                    <div class="mb-3">

                        <label>

                            Check Out Remark

                        </label>

                        <textarea name="check_out_remark" class="form-control" rows="3"></textarea>

                    </div>

                    <div class="text-end">

                        <button class="btn btn-danger" <?php echo e($done!=$total ? 'disabled' : ''); ?>>

                            Check Out

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <?php endif; ?>

    </div>

</div>
<!-- Task Update Modal -->
<div class="modal fade" id="taskModal" tabindex="-1">

    <div class="modal-dialog">

        <form action="" method="POST">

            <?php echo csrf_field(); ?>

            <input type="hidden" name="task_id" id="task_id">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Update Task

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">

                            Task

                        </label>

                        <input type="text" id="task_name" class="form-control" readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Status

                        </label>

                        <select name="status" id="task_status" class="form-select">

                            <option value="Pending">Pending</option>
                            <option value="Done">Done</option>

                        </select>

                    </div>

                    <div class="mb-3" id="remarkDiv">

                        <label class="form-label">

                            Pending Remark

                        </label>

                        <textarea name="pending_remark" id="pending_remark" rows="3" class="form-control"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn buttonSpc">

                        Update

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>



<?php $__env->startPush('scripts'); ?>

<!-- <script>
$(function() {

    $('.editTaskBtn').click(function() {

        $('#task_id').val($(this).data('id'));

        $('#task_name').val($(this).data('task'));

        $('#task_status').val($(this).data('status'));

        $('#pending_remark').val($(this).data('remark'));

        toggleRemark();

    });

    $('#task_status').change(function() {

        toggleRemark();

    });

    function toggleRemark() {

        if ($('#task_status').val() == 'Done') {

            $('#remarkDiv').hide();

        } else {

            $('#remarkDiv').show();

        }

    }

});
</script> -->


<script>
$(function() {

    // Add Task
    $('#addTask').click(function() {

        let html = `
        <div class="row task-row mb-3">

            <div class="col-md-10">

                <input type="text" 
                       name="tasks[]" 
                       class="form-control" 
                       placeholder="Enter Task">

            </div>

            <div class="col-md-2">

                <button type="button" class="btn btn-danger removeTask ">
            &times;
        </button>

            </div>

        </div>`;

        $('#taskArea').append(html);

    });


    // Remove Task
    $(document).on('click', '.removeTask', function() {

        $(this).closest('.task-row').remove();

    });


    // Edit Task Modal
    $('.editTaskBtn').click(function() {

        $('#task_id').val($(this).data('id'));

        $('#task_name').val($(this).data('task'));

        $('#task_status').val($(this).data('status'));

        $('#pending_remark').val($(this).data('remark'));

        toggleRemark();

    });


    $('#task_status').change(function() {

        toggleRemark();

    });


    function toggleRemark() {

        if ($('#task_status').val() == 'Done') {

            $('#remarkDiv').hide();

        } else {

            $('#remarkDiv').show();

        }

    }


});
</script>



<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/field-log/index.blade.php ENDPATH**/ ?>