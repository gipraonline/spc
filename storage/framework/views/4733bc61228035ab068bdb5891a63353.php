<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Money',
        'heroIcon' => 'fa-solid fa-medal',
        'heroSummary' => 'Configurable commission rules, calculations and payout approval.',
        'heroStats' => [
            ['label' => 'Pending', 'icon' => 'fa-solid fa-hourglass-half', 'value' => $pendingPayouts->count()],
            ['label' => 'My payouts', 'icon' => 'fa-regular fa-user', 'value' => $ownPayouts->count()],
            ['label' => 'Earned', 'icon' => 'fa-solid fa-indian-rupee-sign', 'value' => '₹' . number_format($ownPayouts->whereIn('status', ['paid', 'approved', 'included_in_payroll'])->sum('incentive_amount'), 0)],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        <div class="grid-2">
            <?php if($role === 'super_admin'): ?>
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-scale-balanced"></i></div>
                        <div>
                            <h3>New incentive rule</h3>
                            <p>Applies to a role; calculates from linked sales/performance data.</p>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('hr.incentive.rule.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field-grid">
                            <div class="field full"><label>Rule name</label><input name="name" placeholder="e.g. Sales Executive Quarterly Incentive" required></div>
                            <div class="field">
                                <label>Applies to</label>
                                <select name="applies_to_role" required>
                                    <option value="employee">Employee</option>
                                    <option value="manager">Reporting Manager</option>
                                </select>
                            </div>
                            <div class="field"><label>Target metric</label><input name="target_metric" placeholder="e.g. monthly_sales_revenue"></div>
                            <div class="field"><label>Slab from</label><input type="number" step="0.01" name="slab_from"></div>
                            <div class="field"><label>Slab to</label><input type="number" step="0.01" name="slab_to"></div>
                            <div class="field"><label>Incentive %</label><input type="number" step="0.01" name="incentive_percent"></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Save rule</button></div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if($pendingPayouts->isNotEmpty()): ?>
                <div class="table-card">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-solid fa-hourglass-half"></i></span>Payouts pending approval</h3>
                        <span class="pill pill-warn"><?php echo e($pendingPayouts->count()); ?> waiting</span>
                    </div>
                    <div class="tc-body">
                        <table>
                            <thead><tr><th>Employee</th><th>Achieved</th><th>Incentive</th><th></th></tr></thead>
                            <tbody>
                                <?php $__currentLoopData = $pendingPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="cell-emp">
                                                <div class="av"><?php echo e(strtoupper(substr($p->employee->user->name,0,1))); ?></div>
                                                <div><b><?php echo e($p->employee->user->name); ?></b></div>
                                            </div>
                                        </td>
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
            <?php elseif($ownPayouts->isNotEmpty()): ?>
                <div class="table-card">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-solid fa-medal"></i></span>Your incentive payouts</h3>
                        <span class="pill pill-muted"><?php echo e($ownPayouts->count()); ?> total</span>
                    </div>
                    <div class="tc-body">
                        <table>
                            <thead><tr><th>Period</th><th>Achieved</th><th>Incentive</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php $__currentLoopData = $ownPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><b><?php echo e($p->month); ?>/<?php echo e($p->year); ?></b></td>
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
            <?php endif; ?>
        </div>

        <?php if($pendingPayouts->isNotEmpty() && $ownPayouts->isNotEmpty()): ?>
            <div class="section-head" style="margin-top:28px;">
                <h2><i class="fa-solid fa-medal"></i>Your incentive payouts</h2>
            </div>
            <div class="table-card">
                <div class="tc-body">
                    <table>
                        <thead><tr><th>Period</th><th>Achieved</th><th>Incentive</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $ownPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><b><?php echo e($p->month); ?>/<?php echo e($p->year); ?></b></td>
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
        <?php endif; ?>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/incentive.blade.php ENDPATH**/ ?>