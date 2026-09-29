<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('hr.partials.topbar', [
    'title' => $module['title'],
    'eyebrow' => 'Workforce',
    'heroIcon' => 'fa-regular fa-calendar-days',
    'heroSummary' => 'Apply for leave, track balances, and approve your team\'s requests.',
    'heroStats' => $role !== 'super_admin' ? [
        ['label' => 'My requests', 'icon' => 'fa-regular fa-paper-plane', 'value' => $ownRequests->count()],
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
        ['label' => 'Days left', 'icon' => 'fa-solid fa-scale-balanced', 'value' => rtrim(rtrim(number_format($balances->sum('remaining'),1),'0'),'.').'d'],
    ] : [
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
    ],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="content">
    <?php if($role !== 'super_admin' && $balances->isNotEmpty()): ?>
    <?php $entitled = fn($b) => $b->opening_balance + $b->accrued + $b->carried_forward; ?>
    <div class="stat-tiles" style="grid-template-columns:repeat(<?php echo e(min($balances->count(), 4)); ?>,1fr);">
        <?php $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $pct = $entitled($b) > 0 ? round($b->remaining / $entitled($b) * 100) : 0; ?>
        <div class="ring-card">
            <div class="ring" style="--pct:<?php echo e($pct); ?>;"><b><?php echo e(rtrim(rtrim(number_format($b->remaining,1),'0'),'.')); ?></b></div>
            <h4><?php echo e($b->leaveType->name); ?></h4>
            <small><?php echo e($pct); ?>% of <?php echo e(rtrim(rtrim(number_format($entitled($b),1),'0'),'.')); ?>d left</small>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <?php if($role !== 'super_admin'): ?>
    <div class="grid-2">
        <div class="card">
            <div class="widget-head">
                <div class="wh-ico"><i class="fa-solid fa-plane-departure"></i></div>
                <div>
                    <h3>Apply for leave</h3>
                    <p>Submitted requests are routed to your reporting manager.</p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('hr.leave.apply')); ?>">
                <?php echo csrf_field(); ?>
                <div class="field-grid">
                    <div class="field">
                        <label>Leave type</label>
                        <select name="leave_type_id" required>
                            <?php $__currentLoopData = $leaveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($lt->id); ?>"><?php echo e($lt->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="field"><label>From</label><input type="date" name="start_date" required></div>
                    <div class="field"><label>To</label><input type="date" name="end_date" required></div>
                    <div class="field full"><label>Reason</label><textarea name="reason"
                            placeholder="Brief reason"></textarea></div>
                </div>
                <div class="form-actions"><button type="submit" class="btn-primary">Submit leave request</button></div>
            </form>
        </div>

        <div class="table-card">
            <div class="tc-head">
                <h3><span class="wh-ico"><i class="fa-regular fa-clock"></i></span>Your recent applications</h3>
                <span class="pill pill-muted"><?php echo e($ownRequests->count()); ?> total</span>
            </div>
            <div class="tc-body">
                <?php if($ownRequests->isEmpty()): ?>
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-regular fa-folder-open"></i></div>
                    <b>No applications yet</b>
                    <span>Your leave requests will appear here.</span>
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $ownRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><b><?php echo e($r->leaveType->name); ?></b> &middot; <?php echo e(rtrim(rtrim(number_format($r->days,1),'0'),'.')); ?>d</td>
                            <td><?php echo e(\Illuminate\Support\Carbon::parse($r->start_date)->format('M j, Y')); ?>&ndash;<?php echo e(\Illuminate\Support\Carbon::parse($r->end_date)->format('M j, Y')); ?></td>
                            <td>
                                <?php $p = ['approved'=>'pill-ok','pending'=>'pill-warn','rejected'=>'pill-bad','cancelled'=>'pill-muted'][$r->status] ?? 'pill-muted'; ?>
                                <span class="pill <?php echo e($p); ?>"><?php echo e($r->status === 'pending' ? 'Awaiting approval' : ucfirst($r->status)); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="section-head" style="margin-top:30px;">
        <h2><i class="fa-solid fa-clipboard-check"></i>Leave requests to approve</h2>
        <span class="hint"><?php echo e($role === 'manager' ? 'From your direct reports' : 'Across the organization'); ?></span>
    </div>
    <div class="table-card">
        <div class="tc-head">
            <h3><span class="wh-ico"><i class="fa-solid fa-list-check"></i></span>Pending approvals</h3>
            <span class="pill <?php echo e($pendingApprovals->isNotEmpty() ? 'pill-warn' : 'pill-ok'); ?>"><?php echo e($pendingApprovals->count()); ?> waiting</span>
        </div>
        <div class="tc-body">
            <?php if($pendingApprovals->isEmpty()): ?>
            <div class="empty-widget">
                <div class="ew-ico"><i class="fa-solid fa-circle-check"></i></div>
                <b>All caught up</b>
                <span>No leave requests waiting for your approval.</span>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pendingApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="cell-emp">
                                <div class="av"><?php echo e(strtoupper(substr($r->employee->user->name,0,1).substr(strstr($r->employee->user->name,' ') ?: '',1,1))); ?></div>
                                <div><b><?php echo e($r->employee->user->name); ?></b><span><?php echo e($r->employee->department->name ?? '—'); ?></span></div>
                            </div>
                        </td>
                        <td><?php echo e($r->leaveType->name); ?> &middot; <?php echo e(rtrim(rtrim(number_format($r->days,1),'0'),'.')); ?>d</td>
                        <td><?php echo e(\Illuminate\Support\Carbon::parse($r->start_date)->format('M j, Y')); ?>&ndash;<?php echo e(\Illuminate\Support\Carbon::parse($r->end_date)->format('M j, Y')); ?></td>
                        <td><?php echo e(\Illuminate\Support\Str::limit($r->reason ?: '—', 30)); ?></td>
                        <td>
                            <div class="row-actions">
                                <form method="POST" action="<?php echo e(route('hr.leave.decide', $r)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="action" value="approve"><button class="approve" type="submit">Approve</button></form>
                                <form method="POST" action="<?php echo e(route('hr.leave.decide', $r)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="action" value="reject"><button class="reject" type="submit">Reject</button></form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <?php if($role === 'hr_admin' || $role === 'super_admin'): ?>
    <div class="section-head" style="margin-top:30px;">
        <h2><i class="fa-solid fa-table-list"></i>Organization leave register</h2>
        <span class="hint">All leave requests with department, status &amp; date filters</span>
    </div>
    <div class="stat-tiles">
        <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-layer-group"></i></div><div><b><?php echo e($leaveCounts['total']); ?></b><span>Total requests</span></div></div>
        <div class="stat-tile alt"><div class="st-ico"><i class="fa-solid fa-hourglass-half"></i></div><div><b><?php echo e($leaveCounts['pending']); ?></b><span>Pending</span></div></div>
        <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-circle-check"></i></div><div><b><?php echo e($leaveCounts['approved']); ?></b><span>Approved</span></div></div>
        <div class="stat-tile warn"><div class="st-ico"><i class="fa-solid fa-circle-xmark"></i></div><div><b><?php echo e($leaveCounts['rejected']); ?></b><span>Rejected</span></div></div>
    </div>

    <form method="GET" action="<?php echo e(route('hr.leave.index')); ?>" class="filters" style="display:flex;align-items:end;gap:10px;flex-wrap:wrap;margin:0 0 16px;">
        <div class="field" style="min-width:190px;">
            <label for="leaveDeptFilter">Department</label>
            <select id="leaveDeptFilter" name="dept">
                <option value="">All Departments</option>
                <?php $__currentLoopData = $leaveDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($department->id); ?>" <?php if((string) $leaveDeptFilter===(string) $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="field" style="min-width:160px;">
            <label for="leaveStatusFilter">Status</label>
            <select id="leaveStatusFilter" name="status">
                <option value="all" <?php if($leaveStatusFilter==='all'): echo 'selected'; endif; ?>>All Statuses</option>
                <option value="pending" <?php if($leaveStatusFilter==='pending'): echo 'selected'; endif; ?>>Pending</option>
                <option value="approved" <?php if($leaveStatusFilter==='approved'): echo 'selected'; endif; ?>>Approved</option>
                <option value="rejected" <?php if($leaveStatusFilter==='rejected'): echo 'selected'; endif; ?>>Rejected</option>
            </select>
        </div>
        <div class="field" style="min-width:210px;">
            <label for="leaveEmployeeFilter">Employee</label>
            <input id="leaveEmployeeFilter" name="employee" type="search" placeholder="Search employee..." value="<?php echo e($leaveEmployeeFilter); ?>">
        </div>
        <div class="field" style="min-width:150px;">
            <label for="leaveDateFrom">From</label>
            <input id="leaveDateFrom" name="date_from" type="date" value="<?php echo e($leaveDateFrom); ?>">
        </div>
        <div class="field" style="min-width:150px;">
            <label for="leaveDateTo">To</label>
            <input id="leaveDateTo" name="date_to" type="date" value="<?php echo e($leaveDateTo); ?>">
        </div>
        <div class="field" style="min-width:150px;">
            <label for="leaveMonthFilter">Or pick a month</label>
            <input id="leaveMonthFilter" type="month" onchange="fillLeaveMonthRange(this.value)">
        </div>
        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn-primary">Apply</button>
            <button type="submit" name="export" value="csv" class="btn-secondary" style="display:inline-flex;align-items:center;gap:7px;"><i class="fa-solid fa-file-csv"></i>Export</button>
        </div>
    </form>

    <script>
    function fillLeaveMonthRange(value) {
        if (!value) return;
        const [year, month] = value.split('-').map(Number);
        const first = new Date(year, month - 1, 1);
        const last = new Date(year, month, 0);
        const fmt = d => d.toISOString().slice(0, 10);
        document.getElementById('leaveDateFrom').value = fmt(first);
        document.getElementById('leaveDateTo').value = fmt(last);
    }
    </script>

    <div class="table-card">
        <div class="tc-body" style="padding-top:6px;">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $leaveRequestsPage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="cell-emp">
                                <div class="av"><?php echo e(strtoupper(substr($r->employee->user->name,0,1))); ?></div>
                                <div><b><?php echo e($r->employee->user->name); ?></b></div>
                            </div>
                        </td>
                        <td><?php echo e($r->employee->department->name ?? '—'); ?></td>
                        <td><?php echo e($r->leaveType->name); ?></td>
                        <td><?php echo e(\Illuminate\Support\Carbon::parse($r->start_date)->format('d M')); ?> &ndash; <?php echo e(\Illuminate\Support\Carbon::parse($r->end_date)->format('d M Y')); ?></td>
                        <td><?php echo e(rtrim(rtrim(number_format($r->days,1),'0'),'.')); ?></td>
                        <td>
                            <?php $p = ['approved'=>'pill-ok','pending'=>'pill-warn','rejected'=>'pill-bad','cancelled'=>'pill-muted'][$r->status] ?? 'pill-muted'; ?>
                            <span class="pill <?php echo e($p); ?>"><?php echo e(ucfirst($r->status)); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-regular fa-calendar"></i></div>
                            <b>No leave requests found</b>
                            <span>Try adjusting the filters above.</span>
                        </div>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if($leaveRequestsPage): ?>
                <?php echo e($leaveRequestsPage->links()); ?>

            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/leave.blade.php ENDPATH**/ ?>