<?php $__env->startSection('topbarTitle', 'Tasks'); ?>

<?php $__env->startSection('content'); ?>
<style>
.tk-card{background:#fff;border:1px solid rgba(18,58,40,.1);border-radius:16px;box-shadow:0 6px 20px -14px rgba(18,58,40,.25);}
.tk-stat{padding:16px 18px;border-radius:16px;background:#fff;border:1px solid rgba(18,58,40,.1);height:100%;text-decoration:none;display:block;color:inherit;}
.tk-stat small{color:#5F7A6C;font-weight:600;text-transform:uppercase;letter-spacing:.06em;font-size:10.5px;}
.tk-stat b{display:block;font-size:26px;color:#1F5C2E;line-height:1.2;margin-top:2px;}
.tk-stat.warn b{color:#B26A00;} .tk-stat.bad b{color:#B3261E;}
.tk-tabs .nav-link{color:#1F5C2E;font-weight:600;border-radius:999px;padding:6px 16px;}
.tk-tabs .nav-link.active{background:#1F5C2E;color:#fff;}
.tk-title{font-weight:600;color:#123A28;}
.tk-meta{font-size:12px;color:#5F7A6C;}
.tk-bar{height:7px;border-radius:5px;background:#E3EDE3;overflow:hidden;min-width:90px;}
.tk-bar i{display:block;height:100%;background:#5E8D3D;}
.tk-overdue{color:#B3261E;font-weight:600;}
</style>

<?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
<?php if(session('error')): ?><div class="alert alert-danger"><?php echo e(session('error')); ?></div><?php endif; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h4 class="fw-semibold mb-0" style="color:#1F5C2E">Tasks</h4>
        <div class="tk-meta">Tasks given to departments and employees, with priority and live status.</div>
    </div>
    <?php if($canCreate): ?>
        <a href="<?php echo e(route('admin.tasks.create')); ?>" class="btn buttonSpc"><i class="bi bi-plus-lg"></i> Assign Task</a>
    <?php endif; ?>
</div>


<div class="row g-3 mb-3">
    <div class="col-6 col-md"><a class="tk-stat" href="<?php echo e(route('admin.tasks.index', array_merge($filters, ['status' => '']))); ?>"><small>All tasks</small><b><?php echo e($counts['total']); ?></b></a></div>
    <div class="col-6 col-md"><a class="tk-stat" href="<?php echo e(route('admin.tasks.index', array_merge($filters, ['status' => 'open']))); ?>"><small>Open</small><b><?php echo e($counts['open']); ?></b></a></div>
    <div class="col-6 col-md"><a class="tk-stat warn" href="<?php echo e(route('admin.tasks.index', array_merge($filters, ['status' => 'on_hold']))); ?>"><small>On hold</small><b><?php echo e($counts['on_hold']); ?></b></a></div>
    <div class="col-6 col-md"><a class="tk-stat bad" href="<?php echo e(route('admin.tasks.index', array_merge($filters, ['status' => 'overdue']))); ?>"><small>Overdue</small><b><?php echo e($counts['overdue']); ?></b></a></div>
    <div class="col-6 col-md"><a class="tk-stat" href="<?php echo e(route('admin.tasks.index', array_merge($filters, ['status' => 'completed']))); ?>"><small>Completed</small><b><?php echo e($counts['completed']); ?></b></a></div>
</div>


<?php
    $scope = $filters['scope'] ?? 'all';
    $tabs = ['all' => 'Everything I can see', 'mine' => 'Assigned to me', 'team' => 'My team'];
    if ($canCreate) { $tabs['created'] = 'Assigned by me'; }
?>
<div class="tk-card p-3 mb-3">
    <ul class="nav nav-pills tk-tabs mb-3">
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e($scope === $key ? 'active' : ''); ?>"
                   href="<?php echo e(route('admin.tasks.index', array_merge($filters, ['scope' => $key]))); ?>"><?php echo e($label); ?></a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

    <form method="GET" action="<?php echo e(route('admin.tasks.index')); ?>" class="row g-2 align-items-end">
        <input type="hidden" name="scope" value="<?php echo e($scope); ?>">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Search</label>
            <input type="text" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" class="form-control" placeholder="Task title">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Department</label>
            <select name="department_id" class="form-select">
                <option value="">All departments</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($id); ?>" <?php if(($filters['department_id'] ?? '') == $id): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Priority</label>
            <select name="priority" class="form-select">
                <option value="">All</option>
                <?php $__currentLoopData = \App\Models\Task::PRIORITIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php if(($filters['priority'] ?? '') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <?php $__currentLoopData = ['open' => 'Open', 'on_hold' => 'On hold', 'overdue' => 'Overdue', 'completed' => 'Completed', 'cancelled' => 'Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php if(($filters['status'] ?? '') === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn buttonSpc flex-fill" type="submit">Filter</button>
            <a href="<?php echo e(route('admin.tasks.index')); ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>


<div class="tk-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Task</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Due</th>
                    <th style="min-width:150px">Progress</th>
                    <th>Status</th>
                    <th>My status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    [$done, $total] = $task->progress();
                    $pct = $total ? round($done / $total * 100) : 0;
                    $overall = $task->overallStatus();
                    $mine = $myEmployeeId ? $task->assignees->firstWhere('employee_id', $myEmployeeId) : null;
                ?>
                <tr>
                    <td>
                        <a href="<?php echo e(route('admin.tasks.show', $task)); ?>" class="tk-title text-decoration-none"><?php echo e($task->title); ?></a>
                        <div class="tk-meta">By <?php echo e($task->created_by_name ?: '—'); ?> · <?php echo e($task->created_at->format('d M Y')); ?></div>
                    </td>
                    <td><?php echo e($departments[$task->department_id] ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(\App\Models\Task::priorityBadge($task->priority)); ?>"><?php echo e(\App\Models\Task::PRIORITIES[$task->priority] ?? ucfirst($task->priority)); ?></span></td>
                    <td>
                        <?php if($task->due_date): ?>
                            <span class="<?php echo e($task->isOverdue() ? 'tk-overdue' : ''); ?>"><?php echo e($task->due_date->format('d M Y')); ?></span>
                            <?php if($task->isOverdue()): ?><div class="tk-meta tk-overdue">Overdue</div><?php endif; ?>
                        <?php else: ?> — <?php endif; ?>
                    </td>
                    <td>
                        <div class="tk-bar"><i style="width:<?php echo e($pct); ?>%"></i></div>
                        <div class="tk-meta"><?php echo e($done); ?> of <?php echo e($total); ?> done</div>
                    </td>
                    <td><span class="badge <?php echo e(\App\Models\Task::statusBadge($overall)); ?>"><?php echo e($task->overallLabel()); ?></span></td>
                    <td>
                        <?php if($mine): ?>
                            <span class="badge <?php echo e(\App\Models\Task::statusBadge($mine->status)); ?>"><?php echo e(\App\Models\Task::STATUSES[$mine->status]); ?></span>
                        <?php else: ?> <span class="text-muted">—</span> <?php endif; ?>
                    </td>
                    <td class="text-end"><a href="<?php echo e(route('admin.tasks.show', $task)); ?>" class="btn btn-sm btn-outline-success">Open</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center text-muted py-5">No tasks found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3"><?php echo e($tasks->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/tasks/index.blade.php ENDPATH**/ ?>