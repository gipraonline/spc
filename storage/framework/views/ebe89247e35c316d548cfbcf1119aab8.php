<?php $__env->startSection('topbarTitle', 'Task'); ?>

<?php $__env->startSection('content'); ?>
<style>
.tk-card{background:#fff;border:1px solid rgba(18,58,40,.1);border-radius:16px;box-shadow:0 6px 20px -14px rgba(18,58,40,.25);}
.tk-meta{font-size:12px;color:#5F7A6C;}
.tk-bar{height:9px;border-radius:5px;background:#E3EDE3;overflow:hidden;}
.tk-bar i{display:block;height:100%;background:#5E8D3D;}
.tk-tl{border-left:2px solid #DCEBD5;margin-left:6px;padding-left:16px;}
.tk-tl .item{position:relative;padding-bottom:14px;}
.tk-tl .item:before{content:"";position:absolute;left:-23px;top:4px;width:12px;height:12px;border-radius:50%;background:#5E8D3D;border:2px solid #fff;}
.tk-overdue{color:#B3261E;font-weight:600;}
</style>

<?php
    $overall = $task->overallStatus();
    [$done, $total] = $task->progress();
    $pct = $total ? round($done / $total * 100) : 0;
?>

<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<?php if($errors->any()): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>

<div class="mb-3 d-flex flex-wrap justify-content-between align-items-start gap-2">
    <div>
        <a href="<?php echo e(route('admin.tasks.index')); ?>" class="text-decoration-none" style="color:#1F5C2E"><i class="bi bi-arrow-left"></i> Back to tasks</a>
        <h4 class="fw-semibold mt-1 mb-1" style="color:#1F5C2E"><?php echo e($task->title); ?></h4>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge <?php echo e(\App\Models\Task::priorityBadge($task->priority)); ?>"><?php echo e(\App\Models\Task::PRIORITIES[$task->priority]); ?> priority</span>
            <span class="badge <?php echo e(\App\Models\Task::statusBadge($overall)); ?>"><?php echo e($task->overallLabel()); ?></span>
            <?php if($task->isOverdue()): ?><span class="badge bg-danger">Overdue</span><?php endif; ?>
        </div>
    </div>
    <?php if($canManage): ?>
        <div class="d-flex gap-2">
            <?php if (! ($task->isCancelled())): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks.edit')): ?>
                    <a href="<?php echo e(route('admin.tasks.edit', $task)); ?>" class="btn btn-outline-success btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#tkCancel"><i class="bi bi-x-circle"></i> Cancel task</button>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks.delete')): ?>
                <form method="POST" action="<?php echo e(route('admin.tasks.destroy', $task)); ?>" onsubmit="return confirm('Delete this task for everyone?');">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php if($task->isCancelled()): ?>
    <div class="alert alert-dark">This task was cancelled on <?php echo e($task->cancelled_at?->format('d M Y, h:i A')); ?>.
        <?php if($task->cancel_reason): ?> Reason: <?php echo e($task->cancel_reason); ?> <?php endif; ?></div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-lg-8">
        
        <div class="tk-card p-4 mb-3">
            <div class="row g-3 mb-3">
                <div class="col-sm-4"><div class="tk-meta">Department</div><b><?php echo e($departmentName); ?></b></div>
                <div class="col-sm-4"><div class="tk-meta">Assigned by</div><b><?php echo e($task->created_by_name ?: '—'); ?></b></div>
                <div class="col-sm-4"><div class="tk-meta">Due date</div>
                    <b class="<?php echo e($task->isOverdue() ? 'tk-overdue' : ''); ?>"><?php echo e($task->due_date ? $task->due_date->format('d M Y') : 'No due date'); ?></b></div>
            </div>
            <?php if($task->description): ?>
                <div class="tk-meta">Details</div>
                <div class="mb-3"><?php echo nl2br(e($task->description)); ?></div>
            <?php endif; ?>
            <div class="d-flex justify-content-between"><span class="tk-meta">Overall progress</span><b><?php echo e($done); ?> of <?php echo e($total); ?> completed</b></div>
            <div class="tk-bar mt-1"><i style="width:<?php echo e($pct); ?>%"></i></div>
        </div>

        
        <?php if($mine && ! $task->isCancelled()): ?>
            <div class="tk-card p-4 mb-3" style="border-color:#BFD9A9">
                <h6 class="fw-semibold" style="color:#1F5C2E"><i class="bi bi-person-check"></i> Update my status</h6>
                <div class="mb-2">Current: <span class="badge <?php echo e(\App\Models\Task::statusBadge($mine->status)); ?>"><?php echo e(\App\Models\Task::STATUSES[$mine->status]); ?></span></div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tasks.update-status')): ?>
                <form method="POST" action="<?php echo e(route('admin.tasks.status', $task)); ?>" class="row g-2">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-4">
                        <select name="status" class="form-select" required>
                            <?php $__currentLoopData = \App\Models\Task::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k); ?>" <?php if(old('status', $mine->status) === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="remark" maxlength="1000" class="form-control" value="<?php echo e(old('remark')); ?>"
                               placeholder="Remark (required if On Hold, optional otherwise)">
                    </div>
                    <div class="col-12"><button class="btn buttonSpc" type="submit">Save status</button>
                        <span class="tk-meta ms-2">Your reporting managers are notified automatically.</span></div>
                </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <div class="tk-card mb-3">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0" style="color:#1F5C2E">Assigned employees</h6>
                <?php if($assignees->count() < $totalAssignees): ?>
                    <span class="tk-meta">Showing <?php echo e($assignees->count()); ?> of <?php echo e($totalAssignees); ?> — people in your reporting line</span>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Employee</th><th>Status</th><th>Started</th><th>Completed</th><th>Last remark</th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $assignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><b><?php echo e($a->employee?->c_employee_name ?? 'Employee #'.$a->employee_id); ?></b>
                                <div class="tk-meta"><?php echo e($a->employee?->c_employee_code); ?></div></td>
                            <td><span class="badge <?php echo e(\App\Models\Task::statusBadge($a->status)); ?>"><?php echo e(\App\Models\Task::STATUSES[$a->status]); ?></span></td>
                            <td class="tk-meta"><?php echo e($a->started_at?->format('d M, h:i A') ?? '—'); ?></td>
                            <td class="tk-meta"><?php echo e($a->completed_at?->format('d M, h:i A') ?? '—'); ?></td>
                            <td class="tk-meta"><?php echo e($a->remark ?: '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No assignees in your reporting line.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="tk-card p-4">
            <h6 class="fw-semibold mb-3" style="color:#1F5C2E">History</h6>
            <div class="tk-tl">
                <?php $__empty_1 = true; $__currentLoopData = $updates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="item">
                        <div>
                            <?php if($u->type === 'status'): ?>
                                <b><?php echo e($u->actor_name); ?></b> changed status
                                <?php echo e(\App\Models\Task::STATUSES[$u->from_status] ?? ''); ?> → <b><?php echo e(\App\Models\Task::STATUSES[$u->to_status] ?? $u->to_status); ?></b>
                            <?php elseif($u->type === 'created'): ?>
                                <b><?php echo e($u->actor_name); ?></b> assigned the task
                            <?php elseif($u->type === 'edited'): ?>
                                <b><?php echo e($u->actor_name); ?></b> edited the task
                            <?php else: ?>
                                <b><?php echo e($u->actor_name); ?></b> cancelled the task
                            <?php endif; ?>
                        </div>
                        <?php if($u->remark): ?><div class="tk-meta">“<?php echo e($u->remark); ?>”</div><?php endif; ?>
                        <div class="tk-meta"><?php echo e($u->created_at->format('d M Y, h:i A')); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="tk-meta">No activity yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php if($canManage && ! $task->isCancelled()): ?>
<div class="modal fade" id="tkCancel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><form method="POST" action="<?php echo e(route('admin.tasks.cancel', $task)); ?>" class="modal-content">
        <?php echo csrf_field(); ?>
        <div class="modal-header"><h5 class="modal-title">Cancel this task?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <p class="mb-2">Assigned employees will be told it is cancelled and can no longer update it.</p>
            <label class="form-label fw-semibold">Reason (optional)</label>
            <textarea name="reason" rows="3" maxlength="500" class="form-control"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Keep task</button>
            <button type="submit" class="btn btn-warning">Cancel task</button>
        </div>
    </form></div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/tasks/show.blade.php ENDPATH**/ ?>