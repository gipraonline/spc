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
        $hasQueue = $allTicketsPage && $allTicketsPage->isNotEmpty();

        // Tabs this person can see: key => [label, icon, count badge]
        $tabs = [
            'mine' => ['My tickets', 'fa-regular fa-rectangle-list', $ownTickets->count()],
        ];
        if ($hasQueue) {
            $tabs['queue'] = ['Ticket queue', 'fa-solid fa-inbox', $notClosedCount > 0 ? $notClosedCount : null];
        }

        // Open ?tab= if given; paging the queue keeps the queue open.
        $requestedTab = request()->query('tab');
        if (is_string($requestedTab) && isset($tabs[$requestedTab])) {
            $activeTab = $requestedTab;
        } elseif (request()->filled('page') && isset($tabs['queue'])) {
            $activeTab = 'queue';
        } else {
            $activeTab = 'mine';
        }
    ?>

    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Comms',
        'heroIcon' => 'fa-regular fa-circle-question',
        'heroSummary' => 'Raise tickets for HR, IT, payroll or facilities — and track every request in one place.',
        'heroStats' => [
            ['label' => 'My tickets', 'icon' => 'fa-regular fa-rectangle-list', 'value' => $ownTickets->count()],
            ['label' => 'Open org-wide', 'icon' => 'fa-solid fa-inbox', 'value' => $openTicketsCount],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        
        <?php if(count($tabs) > 1): ?>
            <div class="ctabs" role="tablist" aria-label="Support sections">
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

        
        <div class="cpanel" id="cpanel-mine" data-panel="mine" role="tabpanel" aria-labelledby="ctab-mine" <?php if($activeTab !== 'mine'): ?> hidden <?php endif; ?>>
            <div class="grid-2">
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-headset"></i></div>
                        <div>
                            <h3>Raise a ticket</h3>
                            <p>HR, IT, Payroll, Facilities or Documents — routed to HR Admin / Super Admin.</p>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('hr.support.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field-grid">
                            <div class="field">
                                <label>Category</label>
                                <select name="category" required>
                                    <option value="it">IT</option>
                                    <option value="hr">HR</option>
                                    <option value="payroll">Payroll</option>
                                    <option value="facilities">Facilities</option>
                                    <option value="documents">Documents</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Priority</label>
                                <select name="priority" required>
                                    <option value="normal" selected>Normal</option>
                                    <option value="low">Low</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="field full"><label>Subject</label><input name="subject" placeholder="Brief summary" required></div>
                            <div class="field full"><label>Description</label><textarea name="description" placeholder="Details HR needs to help" required></textarea></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Raise ticket</button></div>
                    </form>
                </div>

                <div class="table-card">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-regular fa-rectangle-list"></i></span>Your tickets</h3>
                        <span class="pill pill-muted"><?php echo e($ownTickets->count()); ?> total</span>
                    </div>
                    <div class="tc-body">
                        <?php if($ownTickets->isEmpty()): ?>
                            <div class="empty-widget">
                                <div class="ew-ico"><i class="fa-regular fa-face-smile"></i></div>
                                <b>No tickets yet</b>
                                <span>Anything you raise will show up here with its status.</span>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead><tr><th>Subject</th><th>Category</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php $__currentLoopData = $ownTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(\Illuminate\Support\Str::limit($t->subject, 30)); ?></td>
                                            <td><?php echo e(ucfirst($t->category)); ?></td>
                                            <td>
                                                <?php $p = ['open'=>'pill-warn','in_progress'=>'pill-warn','resolved'=>'pill-ok','closed'=>'pill-muted'][$t->status]; ?>
                                                <span class="pill <?php echo e($p); ?>"><?php echo e(ucfirst(str_replace('_',' ',$t->status))); ?></span>
                                            </td>
                                        </tr>
                                        <?php if($t->resolution_note): ?>
                                            <tr><td colspan="3" style="color:var(--text-muted);font-size:12.5px;padding-top:0;"><i class="fa-solid fa-circle-info" style="color:var(--brand);margin-right:6px;"></i><?php echo e($t->resolution_note); ?></td></tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($hasQueue): ?>
            <div class="cpanel" id="cpanel-queue" data-panel="queue" role="tabpanel" aria-labelledby="ctab-queue" <?php if($activeTab !== 'queue'): ?> hidden <?php endif; ?>>
                <p class="field-hint" style="margin:0 0 12px;">Every ticket raised across the organization, open first</p>
                <div class="table-card">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-solid fa-ticket"></i></span>Ticket queue</h3>
                        <span class="pill pill-warn"><?php echo e($notClosedCount); ?> open</span>
                    </div>
                    <div class="tc-body">
                        <table>
                            <thead><tr><th>Employee</th><th>Category</th><th>Priority</th><th>Subject</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                <?php $__currentLoopData = $allTicketsPage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="cell-emp">
                                                <div class="av"><?php echo e(strtoupper(substr($t->employee->user->name ?? '?',0,1))); ?></div>
                                                <div><b><?php echo e($t->employee->user->name ?? '—'); ?></b></div>
                                            </div>
                                        </td>
                                        <td><?php echo e(ucfirst($t->category)); ?></td>
                                        <td>
                                            <?php $pp = ['low'=>'pill-muted','normal'=>'pill-warn','high'=>'pill-bad'][$t->priority]; ?>
                                            <span class="pill <?php echo e($pp); ?>"><?php echo e(ucfirst($t->priority)); ?></span>
                                        </td>
                                        <td><?php echo e(\Illuminate\Support\Str::limit($t->subject, 28)); ?></td>
                                        <td>
                                            <?php $p = ['open'=>'pill-warn','in_progress'=>'pill-warn','resolved'=>'pill-ok','closed'=>'pill-muted'][$t->status]; ?>
                                            <span class="pill <?php echo e($p); ?>"><?php echo e(ucfirst(str_replace('_',' ',$t->status))); ?></span>
                                        </td>
                                        <td>
                                            <?php if($t->status !== 'closed'): ?>
                                                <form method="POST" action="<?php echo e(route('hr.support.update', $t)); ?>" style="display:flex;gap:6px;align-items:center;">
                                                    <?php echo csrf_field(); ?>
                                                    <select name="status" style="padding:5px 8px;font-size:12px;">
                                                        <option value="open" <?php if($t->status==='open'): echo 'selected'; endif; ?>>Open</option>
                                                        <option value="in_progress" <?php if($t->status==='in_progress'): echo 'selected'; endif; ?>>In progress</option>
                                                        <option value="resolved" <?php if($t->status==='resolved'): echo 'selected'; endif; ?>>Resolved</option>
                                                        <option value="closed" <?php if($t->status==='closed'): echo 'selected'; endif; ?>>Closed</option>
                                                    </select>
                                                    <input name="resolution_note" placeholder="Resolution note" value="<?php echo e($t->resolution_note); ?>" style="padding:5px 8px;font-size:12px;width:140px;">
                                                    <button type="submit" class="btn-ghost" style="padding:0;">Save</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="field-hint">Closed</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php echo e($allTicketsPage->appends(['tab' => 'queue'])->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
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
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/support.blade.php ENDPATH**/ ?>