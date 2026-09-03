

<?php $__env->startSection('content'); ?>

<style>
.field-log-page {
    background: #f8f9fc;
    min-height: 100%;
}

.field-log-header {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 18px 22px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.field-log-title {
    font-size: 20px;
    font-weight: 700;
    color: #212529;
    margin: 0;
}

.field-log-subtitle {
    font-size: 13px;
    color: #8a94a6;
    margin-top: 4px;
}

.status-badge {
    padding: 8px 13px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.section-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    overflow: hidden;
}

.section-card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #edf0f3;
    background: #fff;
}

.section-card-header h5 {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    color: #212529;
}

.section-card-body {
    padding: 22px;
}

.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 7px;
}

.form-control,
.form-select {
    border-radius: 8px;
    border-color: #dee2e6;
    min-height: 43px;
    font-size: 14px;
}

textarea.form-control {
    min-height: auto;
}

.form-control:focus,
.form-select:focus {
    border-color: #5A8D3A;
    box-shadow: 0 0 0 3px rgba(90, 141, 58, 0.10);
}

.readonly-field {
    background: #f8f9fa !important;
    color: #6c757d;
    font-weight: 500;
}

.buttonSpc {
    border-radius: 8px;
    padding: 9px 17px;
    font-size: 13px;
    font-weight: 600;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #5A8D3A, #074E30);
    border: none;
}

.btn-primary:hover,
.btn-primary:focus {
    background: linear-gradient(135deg, #4d7d31, #063d26);
}

.task-input-row {
    background: #f8f9fc;
    border: 1px solid #edf0f3;
    border-radius: 9px;
    padding: 10px;
}

.task-input-row .form-control {
    background: #fff;
}

.removeTask {
    min-height: 43px;
    width: 100%;
    border-radius: 8px;
    font-size: 20px;
    line-height: 1;
}

.info-box {
    border: 1px solid #edf0f3;
    border-radius: 10px;
    padding: 17px;
    background: #fff;
    height: 100%;
}

.info-box-label {
    color: #8a94a6;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.info-box-value {
    margin-top: 7px;
    color: #212529;
    font-size: 15px;
    font-weight: 700;
}

.task-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
}

.task-summary .badge {
    padding: 7px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.task-table {
    margin: 0;
}

.task-table thead th {
    background: linear-gradient(135deg, #5A8D3A, #074E30);
    color: #fff;
    border-bottom: 1px solid #e9ecef;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .4px;
    font-weight: 700;
    padding: 13px 14px;
    white-space: nowrap;
}

.task-table tbody td {
    padding: 15px 14px;
    vertical-align: middle;
    font-size: 13px;
    color: #495057;
    border-color: #edf0f3;
}

.task-table tbody tr:hover {
    background: #f8fbf6;
}

.task-number {
    width: 30px;
    height: 30px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background: #f1f3f5;
    color: #495057;
    font-size: 12px;
    font-weight: 700;
}

.task-name {
    color: #212529;
    font-weight: 600;
}

.task-status {
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.editTaskBtn {
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
}

.progress-card {
    margin-top: 18px;
}

.progress {
    background: #edf0f3;
    border-radius: 20px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: linear-gradient(135deg, #5A8D3A, #074E30);
}

.checkout-card {
    margin-top: 18px;
}

.checkout-content {
    padding: 22px;
}

.checkout-alert {
    border: 0;
    border-radius: 9px;
    font-size: 13px;
    line-height: 1.7;
}

.checkout-alert strong {
    font-weight: 700;
}

.checkout-action-box {
    background: #f8fbf6;
    border: 1px solid #e4eedf;
    border-radius: 10px;
    padding: 15px 18px;
}

.modal-content {
    border: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .12);
}

.modal-header {
    padding: 18px 20px;
    border-bottom: 1px solid #edf0f3;
}

.modal-title {
    font-size: 16px;
    font-weight: 700;
}

.modal-body {
    padding: 22px;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #edf0f3;
}

.empty-state {
    padding: 45px 20px !important;
    color: #8a94a6;
}

.empty-state-icon {
    width: 46px;
    height: 46px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background: #f1f3f5;
    font-size: 20px;
    margin-bottom: 10px;
}

.divider {
    height: 1px;
    background: #edf0f3;
    margin: 24px 0;
}

.checkout-summary {
    border: 1px solid #edf0f3;
    border-radius: 10px;
    overflow: hidden;
}

.checkout-summary-item {
    padding: 13px 10px;
    text-align: center;
    border-right: 1px solid #edf0f3;
}

.checkout-summary-item:last-child {
    border-right: 0;
}

.checkout-summary-label {
    display: block;
    color: #8a94a6;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.checkout-summary-value {
    display: block;
    margin-top: 4px;
    color: #212529;
    font-size: 16px;
    font-weight: 700;
}

@media (max-width: 767px) {

    .field-log-header {
        padding: 15px;
    }

    .field-log-header {
        align-items: flex-start !important;
        gap: 10px;
    }

    .section-card-body,
    .checkout-content {
        padding: 16px;
    }

    .task-summary {
        margin-top: 12px;
    }

    .removeTask {
        margin-top: 8px;
    }

    .field-log-title {
        font-size: 18px;
    }

    .section-card-header {
        padding: 15px;
    }

    .checkout-action-box {
        flex-direction: column;
        align-items: stretch !important;
    }

    .checkout-action-box .btn {
        width: 100%;
    }

}
</style>


<div class="field-log-page">

    
    
    

    <div class="field-log-header d-flex justify-content-between align-items-center">

        <div>

            <h5 class="field-log-title">
                Field Log
            </h5>

            <div class="field-log-subtitle">
                Manage your daily field work and tasks
            </div>

        </div>


        <div>

            <?php if(!$fieldLog): ?>

            <span class="badge bg-warning text-dark status-badge">
                Not Checked In
            </span>

            <?php elseif($fieldLog->status == 'Checked Out'): ?>

            <span class="badge bg-secondary status-badge">
                Checked Out
            </span>

            <?php else: ?>

            <span class="badge bg-success status-badge">
                Working
            </span>

            <?php endif; ?>

        </div>

    </div>


    
    
    

    <?php if(session('success')): ?>

    <div class="alert alert-success border-0 rounded-3 mb-3">

        <strong>Success!</strong>

        <?php echo e(session('success')); ?>


    </div>

    <?php endif; ?>


    <?php if($errors->any()): ?>

    <div class="alert alert-danger border-0 rounded-3 mb-3">

        <strong>Please check the following:</strong>

        <ul class="mb-0 mt-2">

            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <li><?php echo e($error); ?></li>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </ul>

    </div>

    <?php endif; ?>


    
    
    

    <?php if(!$fieldLog): ?>

    <div class="section-card">

        <div class="section-card-header">

            <h5>
                Start Your Workday
            </h5>

        </div>


        <div class="section-card-body">

            <form action="<?php echo e(route('admin.field-log.checkin')); ?>" method="POST">

                <?php echo csrf_field(); ?>


                <div class="row">

                    

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Date
                        </label>

                        <input type="text" class="form-control readonly-field" value="<?php echo e(now()->format('d-m-Y')); ?>"
                            readonly>

                    </div>


                    

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Time
                        </label>

                        <input type="text" class="form-control readonly-field" value="<?php echo e(now()->format('h:i A')); ?>"
                            readonly>

                    </div>

                </div>


                

                <div class="mb-4">

                    <label class="form-label">
                        Check In Remarks
                    </label>

                    <textarea class="form-control" rows="3" name="check_in_remark"
                        placeholder="Add any notes about today's work..."></textarea>

                </div>


                <div class="divider"></div>


                

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="mb-1" style="font-size:16px;font-weight:700;">
                            Today's Tasks
                        </h5>

                        <small class="text-muted">
                            Add the tasks you plan to work on today.
                        </small>

                    </div>


                    <button type="button" id="addTask" class="btn buttonSpc btn-primary">
                        + Add Task
                    </button>

                </div>


                

                <div id="taskArea">

                    <div class="row task-row task-input-row mb-2">

                        <div class="col-md-10">

                            <input type="text" name="tasks[]" class="form-control" placeholder="Enter task">

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

                    <button type="submit" class="btn buttonSpc btn-primary px-4">
                        Check In
                    </button>

                    <?php endif; ?>

                </div>

            </form>

        </div>

    </div>


    <?php else: ?>


    
    
    

    <div class="section-card">

        <div class="section-card-header">

            <h5>
                Today's Work Summary
            </h5>

        </div>


        <div class="section-card-body">

            <div class="row">

                

                <div class="col-md-3 mb-3">

                    <div class="info-box">

                        <div class="info-box-label">
                            Date
                        </div>

                        <div class="info-box-value">
                            <?php echo e($fieldLog->work_date->format('d-m-Y')); ?>

                        </div>

                    </div>

                </div>


                

                <div class="col-md-3 mb-3">

                    <div class="info-box">

                        <div class="info-box-label">
                            Check In
                        </div>

                        <div class="info-box-value">
                            <?php echo e($fieldLog->check_in_time->format('h:i A')); ?>

                        </div>

                    </div>

                </div>


                

                <div class="col-md-3 mb-3">

                    <div class="info-box">

                        <div class="info-box-label">
                            Check Out
                        </div>

                        <div class="info-box-value">

                            <?php echo e(optional($fieldLog->check_out_time)->format('h:i A') ?? '--'); ?>


                        </div>

                    </div>

                </div>


                

                <div class="col-md-3 mb-3">

                    <div class="info-box">

                        <div class="info-box-label">
                            Status
                        </div>

                        <div class="info-box-value">

                            <?php if($fieldLog->status == 'Checked Out'): ?>

                            <span class="badge bg-secondary status-badge">
                                Checked Out
                            </span>

                            <?php else: ?>

                            <span class="badge bg-success status-badge">
                                Working
                            </span>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


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
    * In Progress = Allowed
    * Done = Allowed
    * Pending = Not Allowed
    */

    $canCheckout = !$isCheckedOut && $pendingTasks === 0;

    $percent = $total > 0
    ? round(($done / $total) * 100)
    : 0;

    ?>


    
    
    

    <div class="section-card mt-4">

        <div class="section-card-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>

                    <h5>
                        Today's Tasks
                    </h5>

                    <small class="text-muted">
                        Track and update your work progress.
                    </small>

                </div>


                <div class="task-summary">

                    <span class="badge bg-success">
                        Done: <?php echo e($done); ?>

                    </span>

                    <span class="badge bg-primary">
                        In Progress: <?php echo e($inProgressTasks); ?>

                    </span>

                    <span class="badge bg-warning text-dark">
                        Pending: <?php echo e($pendingTasks); ?>

                    </span>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover task-table">

                    <thead>

                        <tr>

                            <th width="6%">
                                #
                            </th>

                            <th>
                                Task
                            </th>

                            <th width="15%">
                                Status
                            </th>

                            <th width="28%">
                                Pending Remark
                            </th>

                            <th width="12%">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $fieldLog->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            

                            <td>

                                <span class="task-number">
                                    <?php echo e($key + 1); ?>

                                </span>

                            </td>


                            

                            <td>

                                <span class="task-name">
                                    <?php echo e($task->task); ?>

                                </span>

                            </td>


                            

                            <td>

                                <?php if($task->status == 'Done'): ?>

                                <span class="badge bg-success task-status">
                                    Done
                                </span>

                                <?php elseif($task->status == 'In Progress'): ?>

                                <span class="badge bg-primary task-status">
                                    In Progress
                                </span>

                                <?php else: ?>

                                <span class="badge bg-warning text-dark task-status">
                                    Pending
                                </span>

                                <?php endif; ?>

                            </td>


                            

                            <td>

                                <?php if($task->pending_remark): ?>

                                <?php echo e($task->pending_remark); ?>


                                <?php else: ?>

                                <span class="text-muted">
                                    --
                                </span>

                                <?php endif; ?>

                            </td>


                            

                            <td>

                                <button type="button" class="btn btn-sm btn-outline-primary editTaskBtn"
                                    data-id="<?php echo e($task->id); ?>" data-task="<?php echo e($task->task); ?>"
                                    data-status="<?php echo e($task->status); ?>" data-remark="<?php echo e($task->pending_remark); ?>"
                                    data-bs-toggle="<?php echo e($isCheckedOut ? '' : 'modal'); ?>"
                                    data-bs-target="<?php echo e($isCheckedOut ? '' : '#taskModal'); ?>"
                                    <?php echo e($isCheckedOut ? 'disabled' : ''); ?>>
                                    Update
                                </button>

                            </td>

                        </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="5" class="text-center empty-state">

                                <div class="empty-state-icon">
                                    ✓
                                </div>

                                <div>
                                    No Tasks Found
                                </div>

                                <small>
                                    There are no tasks recorded for today.
                                </small>

                            </td>

                        </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    
    
    

    <div class="section-card progress-card">

        <div class="section-card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong style="font-size:14px;">
                        Today's Progress
                    </strong>

                    <div class="text-muted mt-1" style="font-size:12px;">
                        Completed tasks
                    </div>

                </div>


                <strong style="font-size:15px;">
                    <?php echo e($done); ?> / <?php echo e($total); ?>

                </strong>

            </div>


            <div class="progress mt-3" style="height:20px;">

                <div class="progress-bar" role="progressbar" style="width: <?php echo e($percent); ?>%;"
                    aria-valuenow="<?php echo e($percent); ?>" aria-valuemin="0" aria-valuemax="100">
                    <?php echo e($percent); ?>%
                </div>

            </div>

        </div>

    </div>


    
    
    

    <div class="section-card checkout-card">

        <div class="section-card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5>
                        Check Out
                    </h5>

                    <small class="text-muted">
                        Complete your workday
                    </small>

                </div>


                <?php if($isCheckedOut): ?>

                <span class="badge bg-secondary status-badge">
                    Completed
                </span>

                <?php elseif($pendingTasks > 0): ?>

                <span class="badge bg-warning text-dark status-badge">
                    Pending Tasks
                </span>

                <?php else: ?>

                <span class="badge bg-success status-badge">
                    Available
                </span>

                <?php endif; ?>

            </div>

        </div>


        <div class="checkout-content">

            

            <?php if($isCheckedOut): ?>

            <div class="alert alert-secondary checkout-alert mb-0">

                <strong>
                    Already Checked Out
                </strong>

                <br>

                You have already checked out for today.

                <?php if($fieldLog->check_out_time): ?>

                <br>

                Check out time:

                <strong>
                    <?php echo e($fieldLog->check_out_time->format('h:i A')); ?>

                </strong>

                <?php endif; ?>

            </div>


            

            <?php elseif($pendingTasks > 0): ?>

            <div class="alert alert-warning checkout-alert mb-0">

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


            

            <?php else: ?>

            <div class="alert alert-info checkout-alert">

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


            <div class="checkout-action-box d-flex justify-content-between align-items-center">

                <div>

                    <strong style="font-size:13px;">
                        Ready to finish?
                    </strong>

                    <br>

                    <small class="text-muted">
                        Review your tasks before checking out.
                    </small>

                </div>


                <button type="button" class="btn btn-danger buttonSpc px-4" data-bs-toggle="modal"
                    data-bs-target="#checkoutModal">
                    Check Out
                </button>

            </div>

            <?php endif; ?>

        </div>

    </div>


    
    
    

    <?php if(!$isCheckedOut && $pendingTasks === 0): ?>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <form action="<?php echo e(route('admin.field-log.checkout')); ?>" method="POST">

                <?php echo csrf_field(); ?>


                <div class="modal-content">

                    

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title" id="checkoutModalLabel">
                                Confirm Check Out
                            </h5>

                            <small class="text-muted">
                                Complete your field log for today
                            </small>

                        </div>


                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>


                    

                    <div class="modal-body">

                        <div class="alert alert-warning checkout-alert">

                            <strong>
                                Are you sure you want to check out?
                            </strong>

                            <br>

                            Once checked out, you will not be able to update today's tasks.

                        </div>


                        

                        <div class="checkout-summary mb-4">

                            <div class="row g-0">

                                <div class="col-4">

                                    <div class="checkout-summary-item">

                                        <span class="checkout-summary-label">
                                            Total
                                        </span>

                                        <span class="checkout-summary-value">
                                            <?php echo e($total); ?>

                                        </span>

                                    </div>

                                </div>


                                <div class="col-4">

                                    <div class="checkout-summary-item">

                                        <span class="checkout-summary-label">
                                            Done
                                        </span>

                                        <span class="checkout-summary-value text-success">
                                            <?php echo e($done); ?>

                                        </span>

                                    </div>

                                </div>


                                <div class="col-4">

                                    <div class="checkout-summary-item" style="border-right:0;">

                                        <span class="checkout-summary-label">
                                            Progress
                                        </span>

                                        <span class="checkout-summary-value">
                                            <?php echo e($percent); ?>%
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>


                        

                        <div class="mb-2">

                            <label class="form-label">
                                Check Out Remark
                            </label>

                            <textarea name="check_out_remark" class="form-control" rows="4"
                                placeholder="Enter check out remarks..."></textarea>

                        </div>

                    </div>


                    

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light buttonSpc" data-bs-dismiss="modal">
                            Cancel
                        </button>


                        <button type="submit" class="btn btn-danger buttonSpc px-4">
                            Confirm Check Out
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <?php endif; ?>


    
    
    

    <?php if(!$isCheckedOut): ?>

    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <form action="<?php echo e(route('admin.field-log.task.update')); ?>" method="POST">

                <?php echo csrf_field(); ?>


                <input type="hidden" name="task_id" id="task_id">


                <div class="modal-content">

                    

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title" id="taskModalLabel">
                                Update Task
                            </h5>

                            <small class="text-muted">
                                Update task status and remarks
                            </small>

                        </div>


                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>


                    

                    <div class="modal-body">

                        

                        <div class="mb-3">

                            <label class="form-label">
                                Task
                            </label>

                            <input type="text" id="task_name" class="form-control readonly-field" readonly>

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

                        <button type="button" class="btn btn-light buttonSpc" data-bs-dismiss="modal">
                            Cancel
                        </button>


                        <button type="submit" class="btn btn-primary buttonSpc">
                            Update Task
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
            <div class="row task-row task-input-row mb-2">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="tasks[]"
                        class="form-control"
                        placeholder="Enter task"
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


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT MODAL
    |--------------------------------------------------------------------------
    |
    | Bootstrap handles the modal through:
    |
    | data-bs-toggle="modal"
    | data-bs-target="#checkoutModal"
    |
    | No additional JavaScript is required.
    |
    |--------------------------------------------------------------------------
    */

});
</script>

<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/field-log/index.blade.php ENDPATH**/ ?>