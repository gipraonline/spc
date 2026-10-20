<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ---- Tabs ---- */
.ctabs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin: 0 0 18px;
    border-bottom: 1px solid var(--line);
}

.ctab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    margin-bottom: -1px;
    border: 0;
    border-bottom: 2px solid transparent;
    background: transparent;
    color: var(--text-muted);
    font-family: var(--font-body);
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: color .15s, border-color .15s, background .15s;
}

.ctab i {
    font-size: 13px;
}

.ctab:hover {
    color: var(--brand-ink);
    background: var(--brand-softer);
}

.ctab:focus-visible {
    outline: 2px solid var(--brand-bright);
    outline-offset: -2px;
}

.ctab.is-active {
    color: var(--brand-ink);
    border-bottom-color: var(--brand);
    font-weight: 600;
}

.ctab .ctab-count {
    min-width: 20px;
    padding: 1px 7px;
    border-radius: 999px;
    background: var(--brand-softer);
    color: var(--brand-ink);
    font-size: 11.5px;
    font-weight: 600;
    text-align: center;
}

.cpanel[hidden] {
    display: none;
}

@media (max-width: 640px) {
    .ctabs {
        flex-wrap: nowrap;
        overflow-x: auto;
    }
}
</style>
<?php
    $canSeeAll = $role === 'hr_admin' || $role === 'super_admin';

    // Tabs this person can see: key => [label, icon, count badge]
    $tabs = [];
    if ($role !== 'super_admin') {
        $tabs['request'] = ['My requests', 'fa-solid fa-house-laptop', $ownRequests->count()];
    }
    if ($role !== 'employee') {
        $tabs['approvals'] = ['Approvals', 'fa-solid fa-clipboard-check', $pendingApprovals->count() > 0 ? $pendingApprovals->count() : null];
    }
    if ($canSeeAll) {
        $tabs['all'] = ['All requests', 'fa-solid fa-table-list', null];
    }

    // Open ?tab= if given. Applying a filter, paging or exporting keeps the
    // register open even if the tab param is missing.
    $requestedTab = request()->query('tab');
    $hasRegisterQuery = request()->hasAny(['dept', 'status', 'employee', 'date_from', 'date_to', 'page']);
    if (is_string($requestedTab) && isset($tabs[$requestedTab])) {
        $activeTab = $requestedTab;
    } elseif ($hasRegisterQuery && isset($tabs['all'])) {
        $activeTab = 'all';
    } else {
        $activeTab = array_key_first($tabs);
    }
?>

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
    
    <?php if(count($tabs) > 1): ?>
        <div class="ctabs" role="tablist" aria-label="Work from home sections">
            <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$tabLabel, $tabIcon, $tabCount]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="ctab <?php echo e($key === $activeTab ? 'is-active' : ''); ?>"
                    role="tab" id="ctab-<?php echo e($key); ?>" data-tab="<?php echo e($key); ?>"
                    aria-controls="cpanel-<?php echo e($key); ?>" aria-selected="<?php echo e($key === $activeTab ? 'true' : 'false'); ?>"
                    tabindex="<?php echo e($key === $activeTab ? '0' : '-1'); ?>">
                    <i class="<?php echo e($tabIcon); ?>"></i><?php echo e($tabLabel); ?>

                    <?php if($tabCount !== null): ?><span class="ctab-count"><?php echo e($tabCount); ?></span><?php endif; ?>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <?php if($role !== 'super_admin'): ?>
    <div class="cpanel" id="cpanel-request" data-panel="request" role="tabpanel" aria-labelledby="ctab-request" <?php if($activeTab !== 'request'): ?> hidden <?php endif; ?>>
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
    </div>
    <?php endif; ?>

    
    <?php if($role !== 'employee'): ?>
    <div class="cpanel" id="cpanel-approvals" data-panel="approvals" role="tabpanel" aria-labelledby="ctab-approvals" <?php if($activeTab !== 'approvals'): ?> hidden <?php endif; ?>>
        <p class="field-hint" style="margin:0 0 12px;"><?php echo e($role === 'manager' ? 'From your direct reports' : 'Across the organization'); ?></p>
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
    </div>
    <?php endif; ?>

    
    <?php if($canSeeAll): ?>
    <div class="cpanel" id="cpanel-all" data-panel="all" role="tabpanel" aria-labelledby="ctab-all" <?php if($activeTab !== 'all'): ?> hidden <?php endif; ?>>
        <p class="field-hint" style="margin:0 0 12px;">Full register &mdash; filter by department, status, employee or date</p>
        <div class="stat-tiles">
            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-layer-group"></i></div><div><b><?php echo e($wfhCounts['total']); ?></b><span>Total</span></div></div>
            <div class="stat-tile alt"><div class="st-ico"><i class="fa-solid fa-hourglass-half"></i></div><div><b><?php echo e($wfhCounts['pending']); ?></b><span>Pending</span></div></div>
            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-circle-check"></i></div><div><b><?php echo e($wfhCounts['approved']); ?></b><span>Approved</span></div></div>
            <div class="stat-tile warn"><div class="st-ico"><i class="fa-solid fa-circle-xmark"></i></div><div><b><?php echo e($wfhCounts['rejected']); ?></b><span>Rejected</span></div></div>
        </div>

        <form method="GET" action="<?php echo e(route('hr.wfh.index')); ?>" class="filters" style="display:flex;align-items:end;gap:10px;flex-wrap:wrap;margin:0 0 16px;">
            <input type="hidden" name="tab" value="all">
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
                    <?php echo e($wfhRequestsPage->appends(['tab' => 'all'])->links()); ?>

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
    </div>
    <?php endif; ?>

    <p class="access-note">
        Visible to:
        <?php $__currentLoopData = $module['roles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo e($roles[$r]['label']); ?><?php echo e(!$loop->last ? ', ' : ''); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </p>
</div>

<script>
(function () {
    var tabs = Array.prototype.slice.call(document.querySelectorAll('.ctab'));
    var panels = Array.prototype.slice.call(document.querySelectorAll('.cpanel'));
    if (!tabs.length) return;

    function show(key, updateUrl) {
        tabs.forEach(function (t) {
            var on = t.getAttribute('data-tab') === key;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
            t.tabIndex = on ? 0 : -1;
        });
        panels.forEach(function (p) {
            p.hidden = p.getAttribute('data-panel') !== key;
        });
        if (updateUrl && window.history && window.history.replaceState) {
            var url = new URL(window.location.href);
            url.searchParams.set('tab', key);
            window.history.replaceState(null, '', url);
        }
    }

    tabs.forEach(function (t, i) {
        t.addEventListener('click', function () { show(t.getAttribute('data-tab'), true); });
        t.addEventListener('keydown', function (e) {
            var next = null;
            if (e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
            if (e.key === 'ArrowLeft') next = tabs[(i - 1 + tabs.length) % tabs.length];
            if (next) { e.preventDefault(); next.focus(); show(next.getAttribute('data-tab'), true); }
        });
    });
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/wfh.blade.php ENDPATH**/ ?>