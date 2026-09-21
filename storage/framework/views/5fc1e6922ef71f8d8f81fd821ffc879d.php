<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('hr.partials.topbar', [
    'title' => $module['title'],
    'eyebrow' => 'Workforce',
    'heroIcon' => 'fa-solid fa-house-laptop',
    'heroSummary' => 'Request work from home and manage your team\'s requests.',
    'heroStats' => $role !== 'super_admin' ? [
        ['label' => 'My requests', 'icon' => 'fa-regular fa-paper-plane', 'value' => $ownRequests->count()],
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
    ] : [
        ['label' => 'To approve', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingApprovals->count()],
        ['label' => 'All requests', 'icon' => 'fa-solid fa-layer-group', 'value' => $wfhCounts['total'] ?? 0],
    ],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="content">
    <?php if($role !== 'super_admin'): ?>
    <div class="grid-2">
        <div class="card">
            <div class="widget-head">
                <div class="wh-ico"><i class="fa-solid fa-house-laptop"></i></div>
                <div>
                    <h3>Request work from home</h3>
                    <p>Routed to your reporting manager for approval.</p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('hr.wfh.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="field-grid">
                    <div class="field"><label>From</label><input type="date" name="start_date" required></div>
                    <div class="field"><label>To</label><input type="date" name="end_date" required></div>
                    <div class="field"><label>Location</label><input name="location" placeholder="e.g. Home — Kochi"></div>
                    <div class="field"><label>Contact number</label><input name="contact_number" placeholder="Reachable number"></div>
                    <div class="field full"><label>Reason</label><textarea name="reason" placeholder="Brief reason"></textarea></div>
                </div>
                <div class="form-actions"><button type="submit" class="btn-primary">Submit WFH request</button></div>
            </form>
        </div>

        <div class="table-card">
            <div class="tc-head">
                <h3><span class="wh-ico"><i class="fa-regular fa-clock"></i></span>Your recent WFH requests</h3>
                <span class="pill pill-muted"><?php echo e($ownRequests->count()); ?> total</span>
            </div>
            <div class="tc-body">
                <?php if($ownRequests->isEmpty()): ?>
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-regular fa-folder-open"></i></div>
                    <b>No WFH requests yet</b>
                    <span>Submit your first request from the form.</span>
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Dates</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $ownRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
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
        <h2><i class="fa-solid fa-clipboard-check"></i>WFH requests to approve</h2>
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
                <span>No WFH requests waiting for your approval.</span>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Dates</th>
                        <th>Location</th>
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
                        <td><?php echo e(\Illuminate\Support\Carbon::parse($r->start_date)->format('M j, Y')); ?>&ndash;<?php echo e(\Illuminate\Support\Carbon::parse($r->end_date)->format('M j, Y')); ?></td>
                        <td><?php echo e($r->location ?: '—'); ?></td>
                        <td><?php echo e(\Illuminate\Support\Str::limit($r->reason ?: '—', 30)); ?></td>
                        <td>
                            <div class="row-actions">
                                <form method="POST" action="<?php echo e(route('hr.wfh.decide', $r)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="action" value="approve"><button class="approve" type="submit">Approve</button></form>
                                <form method="POST" action="<?php echo e(route('hr.wfh.decide', $r)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="action" value="reject"><button class="reject" type="submit">Reject</button></form>
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
        <h2><i class="fa-solid fa-table-list"></i>All WFH requests</h2>
        <span class="hint">Full register — filter by department, status, employee or date</span>
    </div>
    <div class="stat-tiles">
        <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-layer-group"></i></div><div><b><?php echo e($wfhCounts['total']); ?></b><span>Total</span></div></div>
        <div class="stat-tile alt"><div class="st-ico"><i class="fa-solid fa-hourglass-half"></i></div><div><b><?php echo e($wfhCounts['pending']); ?></b><span>Pending</span></div></div>
        <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-circle-check"></i></div><div><b><?php echo e($wfhCounts['approved']); ?></b><span>Approved</span></div></div>
        <div class="stat-tile warn"><div class="st-ico"><i class="fa-solid fa-circle-xmark"></i></div><div><b><?php echo e($wfhCounts['rejected']); ?></b><span>Rejected</span></div></div>
    </div>

    <form method="GET" action="<?php echo e(route('hr.wfh.index')); ?>" class="filters" style="display:flex;align-items:end;gap:10px;flex-wrap:wrap;margin:0 0 16px;">
        <div class="field" style="min-width:190px;">
            <label for="wfhDepartmentFilter">Department</label>
            <select id="wfhDepartmentFilter" name="dept">
                <option value="">All Departments</option>
                <?php $__currentLoopData = $wfhDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($department->id); ?>" <?php if((string) $wfhDeptFilter===(string) $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="field" style="min-width:160px;">
            <label for="wfhStatusFilter">Status</label>
            <select id="wfhStatusFilter" name="status">
                <option value="all" <?php if($wfhStatusFilter==='all'): echo 'selected'; endif; ?>>All Statuses</option>
                <option value="pending" <?php if($wfhStatusFilter==='pending'): echo 'selected'; endif; ?>>Pending</option>
                <option value="approved" <?php if($wfhStatusFilter==='approved'): echo 'selected'; endif; ?>>Approved</option>
                <option value="rejected" <?php if($wfhStatusFilter==='rejected'): echo 'selected'; endif; ?>>Rejected</option>
            </select>
        </div>
        <div class="field" style="min-width:220px;">
            <label for="wfhEmployeeFilter">Employee</label>
            <input id="wfhEmployeeFilter" type="search" name="employee" placeholder="Search employee..." value="<?php echo e($wfhEmployeeFilter); ?>">
        </div>
        <div class="field" style="min-width:150px;">
            <label for="wfhDateFrom">From</label>
            <input id="wfhDateFrom" name="date_from" type="date" value="<?php echo e($wfhDateFrom); ?>">
        </div>
        <div class="field" style="min-width:150px;">
            <label for="wfhDateTo">To</label>
            <input id="wfhDateTo" name="date_to" type="date" value="<?php echo e($wfhDateTo); ?>">
        </div>
        <div class="field" style="min-width:150px;">
            <label for="wfhMonthFilter">Or pick a month</label>
            <input id="wfhMonthFilter" type="month" onchange="fillWfhMonthRange(this.value)">
        </div>
        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn-primary">Apply</button>
            <button type="button" class="btn-secondary" onclick="exportWfhRequests()" style="display:inline-flex;align-items:center;gap:7px;"><i class="fa-solid fa-file-csv"></i>Export</button>
        </div>
    </form>

    <script>
    function fillWfhMonthRange(value) {
        if (!value) return;
        const [year, month] = value.split('-').map(Number);
        const first = new Date(year, month - 1, 1);
        const last = new Date(year, month, 0);
        const fmt = d => d.toISOString().slice(0, 10);
        document.getElementById('wfhDateFrom').value = fmt(first);
        document.getElementById('wfhDateTo').value = fmt(last);
    }
    </script>

    <div class="table-card">
        <div class="tc-body" style="padding-top:6px;">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Dates</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $wfhRequestsPage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="cell-emp">
                                <div class="av"><?php echo e(strtoupper(substr($r->employee->user->name,0,1))); ?></div>
                                <div><b><?php echo e($r->employee->user->name); ?></b></div>
                            </div>
                        </td>
                        <td><?php echo e($r->employee->department->name ?? '—'); ?></td>
                        <td><?php echo e(\Illuminate\Support\Carbon::parse($r->start_date)->format('d M')); ?> &ndash; <?php echo e(\Illuminate\Support\Carbon::parse($r->end_date)->format('d M Y')); ?></td>
                        <td><?php echo e($r->location ?: '—'); ?></td>
                        <td>
                            <?php $p = ['approved'=>'pill-ok','pending'=>'pill-warn','rejected'=>'pill-bad','cancelled'=>'pill-muted'][$r->status] ?? 'pill-muted'; ?>
                            <span class="pill <?php echo e($p); ?>"><?php echo e($r->status === 'pending' ? 'Awaiting approval' : ucfirst($r->status)); ?></span>
                        </td>
                        <td><?php echo e(\Illuminate\Support\Str::limit($r->reason ?: '—', 30)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-house-laptop"></i></div>
                            <b>No WFH requests match these filters</b>
                            <span>Try adjusting the filters above.</span>
                        </div>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if($wfhRequestsPage): ?>
                <?php echo e($wfhRequestsPage->links()); ?>

            <?php endif; ?>
        </div>
    </div>

    <script>
    function exportWfhRequests() {
        const params = new URLSearchParams(window.location.search);
        params.set('dept', document.getElementById('wfhDepartmentFilter').value);
        params.set('status', document.getElementById('wfhStatusFilter').value);
        params.set('employee', document.getElementById('wfhEmployeeFilter').value);
        params.set('date_from', document.getElementById('wfhDateFrom').value);
        params.set('date_to', document.getElementById('wfhDateTo').value);
        params.set('export', 'csv');
        window.location.href = "<?php echo e(route('hr.wfh.index')); ?>?" + params.toString();
    }
    </script>
    <?php endif; ?>

    <p class="access-note">
        Visible to:
        <?php $__currentLoopData = $module['roles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo e($roles[$r]['label']); ?><?php echo e(!$loop->last ? ', ' : ''); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/wfh.blade.php ENDPATH**/ ?>