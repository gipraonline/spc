<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Money',
        'heroIcon' => 'fa-solid fa-medal',
        'heroSummary' => 'Configurable incentive rules, calculations and payout approval.',
        'heroStats' => [
            ['label' => 'Pending', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingPayouts->count()],
            ['label' => 'My payouts', 'icon' => 'fa-regular fa-user', 'value' => $ownPayouts->count()],
            ['label' => 'Earned', 'icon' => 'fa-solid fa-indian-rupee-sign', 'value' => '₹' . number_format($ownPayouts->whereIn('status', ['paid', 'approved', 'included_in_payroll'])->sum('incentive_amount'), 0)],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $isAdmin = in_array($role, ['super_admin', 'hr_admin'], true);
        $showPending = in_array($role, ['manager', 'super_admin', 'hr_admin'], true);
        $defaultTab = $isAdmin ? 'calculate' : ($showPending ? 'pending' : 'mine');
        $activeTab = request('tab', $defaultTab);
    ?>

    <div class="content">
        <div class="tabs" id="incTabs">
            <?php if($isAdmin): ?>
                <button type="button" class="tab <?php if($activeTab === 'calculate'): ?> active <?php endif; ?>" data-tab="calculate" onclick="incTab(this,'calculate')">Calculate</button>
                <button type="button" class="tab <?php if($activeTab === 'newrule'): ?> active <?php endif; ?>" data-tab="newrule" onclick="incTab(this,'newrule')">New rule</button>
                <button type="button" class="tab <?php if($activeTab === 'rules'): ?> active <?php endif; ?>" data-tab="rules" onclick="incTab(this,'rules')">Rules (<?php echo e($rules->count()); ?>)</button>
            <?php endif; ?>
            <?php if($showPending): ?>
                <button type="button" class="tab <?php if($activeTab === 'pending'): ?> active <?php endif; ?>" data-tab="pending" onclick="incTab(this,'pending')">Pending approval <?php if($pendingPayouts->isNotEmpty()): ?>(<?php echo e($pendingPayouts->count()); ?>)<?php endif; ?></button>
            <?php endif; ?>
            <button type="button" class="tab <?php if($activeTab === 'mine'): ?> active <?php endif; ?>" data-tab="mine" onclick="incTab(this,'mine')">My payouts</button>
        </div>

        <?php if($isAdmin): ?>
            <div class="tabpanel <?php if($activeTab === 'calculate'): ?> active <?php endif; ?>" data-tabpanel="calculate">
                <div class="card">
                            <div class="widget-head">
                                <div class="wh-ico"><i class="fa-solid fa-calculator"></i></div>
                                <div>
                                    <h3>Calculate incentives</h3>
                                    <p>Counts orders that are <b><?php echo e($eligibility['order_status']); ?></b> and payment <b><?php echo e($eligibility['payment_status']); ?></b>. Approved / paid payouts are never changed.</p>
                                </div>
                            </div>
                            <form method="POST" action="<?php echo e(route('hr.incentive.calculate')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="field-grid">
                                    <div class="field"><label>Month</label><input type="month" name="month" value="<?php echo e($runMonth); ?>" max="<?php echo e(now()->format('Y-m')); ?>" required></div>
                                </div>
                                <div class="form-actions"><button type="submit" class="btn-primary">Calculate payouts</button></div>
                            </form>
                        </div>
            </div>

            <div class="tabpanel <?php if($activeTab === 'newrule'): ?> active <?php endif; ?>" data-tabpanel="newrule">
                <div class="card">
                            <div class="widget-head">
                                <div class="wh-ico"><i class="fa-solid fa-scale-balanced"></i></div>
                                <div>
                                    <h3>New incentive rule</h3>
                                    <p>Marginal slabs on monthly eligible net sales. "Own" = the person's sales, "Team" = override on everyone below them.</p>
                                </div>
                            </div>
                            <form method="POST" action="<?php echo e(route('hr.incentive.rule.store')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="field-grid">
                                    <div class="field full"><label>Rule name</label><input name="name" placeholder="e.g. FCO own sales - slab 4" required></div>
                                    <div class="field">
                                        <label>Designation</label>
                                        <select name="designation_code">
                                            <option value="">Any (use portal role below)</option>
                                            <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($d->identifier); ?>"><?php echo e($d->c_designation); ?> (<?php echo e($d->identifier); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Portal role</label>
                                        <select name="applies_to_role" required>
                                            <option value="employee">Employee</option>
                                            <option value="manager">Reporting Manager</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Basis</label>
                                        <select name="basis" required>
                                            <option value="own">Own sales</option>
                                            <option value="team">Team override</option>
                                        </select>
                                    </div>
                                    <div class="field"><label>Slab from (₹)</label><input type="number" step="0.01" min="0" name="slab_from" value="0"></div>
                                    <div class="field"><label>Slab to (₹, blank = no limit)</label><input type="number" step="0.01" min="0" name="slab_to"></div>
                                    <div class="field"><label>Incentive %</label><input type="number" step="0.01" min="0" max="100" name="incentive_percent"></div>
                                    <div class="field"><label>Flat amount (₹)</label><input type="number" step="0.01" min="0" name="flat_amount"></div>
                                    <div class="field"><label>Min. target achievement %</label><input type="number" step="0.1" min="0" name="min_achievement_pct" placeholder="blank = no gate"></div>
                                </div>
                                <div class="form-actions"><button type="submit" class="btn-primary">Save rule</button></div>
                            </form>
                        </div>
            </div>

            <div class="tabpanel <?php if($activeTab === 'rules'): ?> active <?php endif; ?>" data-tabpanel="rules">
                <?php if($rules->isNotEmpty()): ?>
                    <div class="table-card">
                            <div class="tc-body" style="overflow-x:auto;">
                                <table>
                                    <thead><tr><th>Rule</th><th>Who</th><th>Basis</th><th>Slab (₹)</th><th>Pays</th><th>Min. achievement</th><th>Status</th><th></th></tr></thead>
                                    <tbody>
                                        <?php $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr style="<?php echo e($r->is_active ? '' : 'opacity:.55;'); ?>">
                                                <td><b><?php echo e($r->name); ?></b></td>
                                                <td><?php echo e($r->designation_code ?: ucfirst($r->applies_to_role)); ?></td>
                                                <td><?php echo e(($r->basis ?? 'own') === 'team' ? 'Team override' : 'Own sales'); ?></td>
                                                <td><?php echo e(number_format($r->slab_from ?? 0)); ?> &ndash; <?php echo e($r->slab_to !== null ? number_format($r->slab_to) : 'no limit'); ?></td>
                                                <td><?php echo e($r->incentive_percent !== null ? rtrim(rtrim(number_format($r->incentive_percent, 2), '0'), '.').'%' : ''); ?><?php echo e($r->flat_amount !== null ? ' + ₹'.number_format($r->flat_amount) : ''); ?></td>
                                                <td><?php echo e($r->min_achievement_pct !== null ? rtrim(rtrim(number_format($r->min_achievement_pct, 1), '0'), '.').'%' : '—'); ?></td>
                                                <td><span class="pill <?php echo e($r->is_active ? 'pill-ok' : 'pill-muted'); ?>"><?php echo e($r->is_active ? 'Active' : 'Off'); ?></span></td>
                                                <td>
                                                    <form method="POST" action="<?php echo e(route('hr.incentive.rule.toggle', $r)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <button class="approve" type="submit"><?php echo e($r->is_active ? 'Turn off' : 'Turn on'); ?></button>
                                                    </form>
                                                    <form method="POST" action="<?php echo e(route('hr.incentive.rule.destroy', $r)); ?>" style="margin-top:6px;" onsubmit="return confirm('Delete rule &quot;<?php echo e(addslashes($r->name)); ?>&quot;? Existing payouts keep their amounts. This cannot be undone.');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button class="approve" type="submit" style="background:#fde8e8;color:#B42318;border-color:#f5c2c0;"><i class="fa-solid fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <p class="field-hint" style="margin-top:12px;"><i class="fa-solid fa-circle-info" style="color:var(--brand);margin-right:6px;"></i>Slabs are marginal: each rule pays its % only on the part of monthly sales that falls inside its slab.</p>
                            </div>
                        </div>
                <?php else: ?>
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-solid fa-scale-balanced"></i></div>
                        <b>No incentive rules yet</b>
                        <span>Create one under the New rule tab.</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($showPending): ?>
            <div class="tabpanel <?php if($activeTab === 'pending'): ?> active <?php endif; ?>" data-tabpanel="pending">
                <?php if($pendingPayouts->isNotEmpty()): ?>
                    <div class="table-card">
                                <div class="tc-head">
                                    <h3><span class="wh-ico"><i class="fa-solid fa-hourglass-half"></i></span>Payouts pending approval</h3>
                                    <span class="pill pill-warn"><?php echo e($pendingPayouts->count()); ?> waiting</span>
                                </div>
                                <div class="tc-body">
                                    <table>
                                        <thead><tr><th>Employee</th><th>Period</th><th>Basis</th><th>Base sales</th><th>Incentive</th><th></th></tr></thead>
                                        <tbody>
                                            <?php $__currentLoopData = $pendingPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <div class="cell-emp">
                                                            <div class="av"><?php echo e(strtoupper(substr($p->employee->user->name,0,1))); ?></div>
                                                            <div><b><?php echo e($p->employee->user->name); ?></b></div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo e(str_pad($p->month, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e($p->year); ?></td>
                                                    <td><span class="pill pill-muted"><?php echo e(($p->basis ?? 'own') === 'team' ? 'Team override' : 'Own sales'); ?></span></td>
                                                    <td>₹<?php echo e(number_format($p->achieved_value,0)); ?></td>
                                                    <td><b>₹<?php echo e(number_format($p->incentive_amount,0)); ?></b></td>
                                                    <td>
                                                        <form method="POST" action="<?php echo e(route('hr.incentive.payout.approve', $p)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <button class="approve" type="submit">Approve</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <p class="field-hint" style="margin-top:12px;"><i class="fa-solid fa-circle-info" style="color:var(--brand);margin-right:6px;"></i>Approved payouts are included in the next payroll run.</p>
                                </div>
                            </div>
                <?php else: ?>
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-solid fa-hourglass-half"></i></div>
                        <b>Nothing waiting for approval</b>
                        <span>Payouts appear here after HR calculates incentives.</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="tabpanel <?php if($activeTab === 'mine'): ?> active <?php endif; ?>" data-tabpanel="mine">
            <?php if($ownPayouts->isNotEmpty()): ?>
                <div class="table-card">
                            <div class="tc-head">
                                <h3><span class="wh-ico"><i class="fa-solid fa-medal"></i></span>Your incentive payouts</h3>
                                <span class="pill pill-muted"><?php echo e($ownPayouts->count()); ?> total</span>
                            </div>
                            <div class="tc-body">
                                <table>
                                    <thead><tr><th>Period</th><th>Basis</th><th>Base sales</th><th>Incentive</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php $__currentLoopData = $ownPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><b><?php echo e($p->month); ?>/<?php echo e($p->year); ?></b></td>
                                                <td><?php echo e(($p->basis ?? 'own') === 'team' ? 'Team override' : 'Own sales'); ?></td>
                                                <td>₹<?php echo e(number_format($p->achieved_value,0)); ?></td>
                                                <td>₹<?php echo e(number_format($p->incentive_amount,0)); ?></td>
                                                <td>
                                                    <?php $p2 = ['paid'=>'pill-ok','included_in_payroll'=>'pill-ok','approved'=>'pill-ok','pending'=>'pill-warn'][$p->status]; ?>
                                                    <span class="pill <?php echo e($p2); ?>"><?php echo e(ucfirst(str_replace('_',' ',$p->status))); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
            <?php else: ?>
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-medal"></i></div>
                    <b>No incentive payouts yet</b>
                    <span>Your approved incentives show up here and are paid with payroll.</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function incTab(btn, name) {
        const scope = document.querySelector('.content');
        scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
        scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const panel = scope.querySelector('[data-tabpanel="' + name + '"]');
        if (panel) panel.classList.add('active');
        try { sessionStorage.setItem('incentiveTab', name); } catch (e) {}
    }
    (function () {
        // keep the same tab after an approve / calculate / save that reloads the page
        if (new URLSearchParams(location.search).has('tab')) return;
        let name = '';
        try { name = sessionStorage.getItem('incentiveTab') || ''; } catch (e) {}
        const btn = name && document.querySelector('#incTabs .tab[data-tab="' + name + '"]');
        if (btn) btn.click();
    })();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/incentive.blade.php ENDPATH**/ ?>