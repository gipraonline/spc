

<?php $__env->startSection('content'); ?>

<div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">

    <h5 class="card-title fw-semibold mb-0">
        Field Log
    </h5>

    <?php if(!$fieldLog): ?>

    <span class="badge bg-warning">
        Not Checked In
    </span>

    <?php elseif($fieldLog->status == 'Checked Out'): ?>

    <span class="badge bg-secondary">
        Checked Out
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

    <form action="<?php echo e(route('admin.field-log.checkin')); ?>" method="POST">

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

            <textarea class="form-control" rows="3" name="check_in_remark" placeholder="Enter remarks..."></textarea>

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

        
        <div class="col-md-3 mb-3">

            <div class="border rounded p-3 h-100">

                <small class="text-muted">
                    Date
                </small>

                <h6 class="mt-2 mb-0">

                    <?php echo e($fieldLog->work_date->format('d-m-Y')); ?>


                </h6>

            </div>

        </div>


        
        <div class="col-md-3 mb-3">

            <div class="border rounded p-3 h-100">

                <small class="text-muted">
                    Check In
                </small>

                <h6 class="mt-2 mb-0">

                    <?php echo e($fieldLog->check_in_time->format('h:i A')); ?>


                </h6>

            </div>

        </div>


        
        <div class="col-md-3 mb-3">

            <div class="border rounded p-3 h-100">

                <small class="text-muted">
                    Check Out
                </small>

                <h6 class="mt-2 mb-0">

                    <?php echo e(optional($fieldLog->check_out_time)->format('h:i A') ?? '--'); ?>


                </h6>

            </div>

        </div>


        
        <div class="col-md-3 mb-3">

            <div class="border rounded p-3 h-100">

                <small class="text-muted">
                    Status
                </small>

                <h6 class="mt-2 mb-0">

                    <?php if($fieldLog->status == 'Checked Out'): ?>

                    <span class="badge bg-secondary">
                        Checked Out
                    </span>

                    <?php else: ?>

                    <span class="badge bg-success">
                        Working
                    </span>

                    <?php endif; ?>

                </h6>

            </div>

        </div>

    </div>


    <hr>


    
    
    

    <?php

    $done = $fieldLog->tasks
    ->where('status', 'Done')
    ->count();

    $total = $fieldLog->tasks->count();

    $pendingTasks = $fieldLog->tasks
    ->where('status', 'Pending')
    ->count();

    $inProgressTasks = $fieldLog->tasks
    ->where('status', 'In Progress')
    ->count();

    $isCheckedOut = $fieldLog->status === 'Checked Out';

    /*
    * Checkout is allowed when:
    *
    * 1. There are NO Pending tasks
    * 2. Field Log is NOT already Checked Out
    *
    * Therefore:
    *
    * In Progress = Allowed
    * Done = Allowed
    * Pending = Not Allowed
    */
    $canCheckout = !$isCheckedOut && $pendingTasks === 0;

    $percent = $total > 0
    ? round(($done / $total) * 100)
    : 0;

    ?>


    <div class="card mt-4 shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Today's Tasks
            </h5>

            <div>

                <span class="badge bg-success me-1">
                    Done: <?php echo e($done); ?>

                </span>

                <span class="badge bg-primary me-1">
                    In Progress: <?php echo e($inProgressTasks); ?>

                </span>

                <span class="badge bg-warning text-dark">
                    Pending: <?php echo e($pendingTasks); ?>

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="5%">
                                #
                            </th>

                            <th>
                                Task
                            </th>

                            <th width="15%">
                                Status
                            </th>

                            <th width="30%">
                                Pending Remark
                            </th>

                            <th width="15%">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $fieldLog->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            
                            <td>
                                <?php echo e($key + 1); ?>

                            </td>


                            
                            <td>
                                <?php echo e($task->task); ?>

                            </td>


                            
                            <td>

                                <?php if($task->status == 'Done'): ?>

                                <span class="badge bg-success">
                                    Done
                                </span>

                                <?php elseif($task->status == 'In Progress'): ?>

                                <span class="badge bg-primary">
                                    In Progress
                                </span>

                                <?php else: ?>

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                                <?php endif; ?>

                            </td>


                            
                            <td>

                                <?php echo e($task->pending_remark ?: '--'); ?>


                            </td>


                            
                            <td>

                                <button type="button" class="btn btn-sm buttonSpc editTaskBtn" data-id="<?php echo e($task->id); ?>"
                                    data-task="<?php echo e($task->task); ?>" data-status="<?php echo e($task->status); ?>"
                                    data-remark="<?php echo e($task->pending_remark); ?>"
                                    data-bs-toggle="<?php echo e($isCheckedOut ? '' : 'modal'); ?>"
                                    data-bs-target="<?php echo e($isCheckedOut ? '' : '#taskModal'); ?>"
                                    <?php echo e($isCheckedOut ? 'disabled' : ''); ?>>
                                    Update
                                </button>

                            </td>

                        </tr>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="5" class="text-center py-4">
                                No Tasks Found
                            </td>

                        </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    
    
    

    <div class="card mt-3">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Today's Progress
                </strong>

                <strong>
                    <?php echo e($done); ?> / <?php echo e($total); ?>

                </strong>

            </div>


            <div class="progress mt-3" style="height: 20px;">

                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($percent); ?>%;"
                    aria-valuenow="<?php echo e($percent); ?>" aria-valuemin="0" aria-valuemax="100">
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


            
            <?php if($isCheckedOut): ?>

            <div class="alert alert-secondary mb-0">

                <strong>
                    Already Checked Out
                </strong>

                <br>

                You have already checked out for today.

            </div>


            
            <?php elseif($pendingTasks > 0): ?>

            <div class="alert alert-warning">

                <strong>
                    Checkout Not Available
                </strong>

                <br>

                You have
                <strong><?php echo e($pendingTasks); ?></strong>
                Pending task(s).

                <br>

                Please move all Pending tasks to
                <strong>In Progress</strong>
                or
                <strong>Done</strong>
                before checking out.

            </div>


            <form action="<?php echo e(route('admin.field-log.checkout')); ?>" method="POST">

                <?php echo csrf_field(); ?>


                <div class="mb-3">

                    <label class="form-label">
                        Check Out Remark
                    </label>

                    <textarea name="check_out_remark" class="form-control" rows="3"
                        placeholder="Enter check out remarks..."></textarea>

                </div>


                <div class="text-end">

                    <button type="submit" class="btn btn-danger" disabled>
                        Check Out
                    </button>

                </div>

            </form>


            
            <?php else: ?>

            <div class="alert alert-info">

                <strong>
                    Checkout Available
                </strong>

                <br>

                You can check out now.

                <?php if($inProgressTasks > 0): ?>

                <br>

                <strong><?php echo e($inProgressTasks); ?></strong>
                task(s) are still
                <strong>In Progress</strong>.

                <?php endif; ?>

            </div>


            <form action="<?php echo e(route('admin.field-log.checkout')); ?>" method="POST">

                <?php echo csrf_field(); ?>


                <div class="mb-3">

                    <label class="form-label">
                        Check Out Remark
                    </label>

                    <textarea name="check_out_remark" class="form-control" rows="3"
                        placeholder="Enter check out remarks..."></textarea>

                </div>


                <div class="text-end">

                    <button type="submit" class="btn btn-danger">
                        Check Out
                    </button>

                </div>

            </form>

            <?php endif; ?>

        </div>

    </div>


    
    
    

    <?php if(!$isCheckedOut): ?>

    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <form action="<?php echo e(route('admin.field-log.task.update')); ?>" method="POST">

                <?php echo csrf_field(); ?>

                <input type="hidden" name="task_id" id="task_id">


                <div class="modal-content">


                    
                    <div class="modal-header">

                        <h5 class="modal-title" id="taskModalLabel">
                            Update Task
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

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

                                <option value="Pending">
                                    Pending
                                </option>

                                <option value="In Progress">
                                    In Progress
                                </option>

                                <option value="Done">
                                    Done
                                </option>

                            </select>

                        </div>


                        
                        <div class="mb-3" id="remarkDiv">

                            <label class="form-label">
                                Pending Remark
                            </label>

                            <textarea name="pending_remark" id="pending_remark" rows="3" class="form-control"
                                placeholder="Enter pending/in-progress remark..."></textarea>

                        </div>

                    </div>


                    
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn buttonSpc">
                            Update
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <?php endif; ?>


    <?php endif; ?>

</div>






<?php $__env->startPush('scripts'); ?>

<script>
$(function() {

    /*
    |--------------------------------------------------------------------------
    | ADD TASK
    |--------------------------------------------------------------------------
    */

    $('#addTask').on('click', function() {

        let html = `
            <div class="row task-row mb-3">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="tasks[]"
                        class="form-control"
                        placeholder="Enter Task"
                    >

                </div>

                <div class="col-md-2">

                    <button
                        type="button"
                        class="btn btn-danger removeTask"
                    >
                        &times;
                    </button>

                </div>

            </div>
        `;

        $('#taskArea').append(html);

    });


    /*
    |--------------------------------------------------------------------------
    | REMOVE TASK
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.removeTask', function() {

        $(this)
            .closest('.task-row')
            .remove();

    });


    /*
    |--------------------------------------------------------------------------
    | OPEN TASK UPDATE MODAL
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.editTaskBtn', function() {

        let taskId = $(this).data('id');
        let taskName = $(this).data('task');
        let taskStatus = $(this).data('status');
        let taskRemark = $(this).data('remark');

        $('#task_id').val(taskId);

        $('#task_name').val(taskName);

        $('#task_status').val(taskStatus);

        $('#pending_remark').val(taskRemark || '');

        toggleRemark();

    });


    /*
    |--------------------------------------------------------------------------
    | STATUS CHANGE
    |--------------------------------------------------------------------------
    */

    $('#task_status').on('change', function() {

        toggleRemark();

    });


    /*
    |--------------------------------------------------------------------------
    | SHOW / HIDE REMARK
    |--------------------------------------------------------------------------
    */

    function toggleRemark() {

        if ($('#task_status').val() === 'Done') {

            $('#remarkDiv').hide();

            $('#pending_remark').val('');

        } else {

            $('#remarkDiv').show();

        }

    }


});
</script>

<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/field-log/index.blade.php ENDPATH**/ ?>