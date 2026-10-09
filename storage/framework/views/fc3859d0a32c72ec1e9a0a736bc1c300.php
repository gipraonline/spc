<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Records',
        'heroIcon' => 'fa-solid fa-chart-column',
        'heroSummary' => 'Workforce, payroll, recruitment and appraisal reporting across the organization.',
        'heroStats' => [
            ['label' => 'Headcount', 'icon' => 'fa-solid fa-users', 'value' => $headcountByDept->sum('employees_count')],
            ['label' => 'Funnel', 'icon' => 'fa-solid fa-filter', 'value' => $funnel->sum()],
            ['label' => 'Appraisals', 'icon' => 'fa-solid fa-clipboard-check', 'value' => $appraisalDone . '/' . $appraisalTotal],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        
        <div class="section-head" style="margin-top:6px;">
            <h2><i class="fa-solid fa-signal"></i>Today's snapshot</h2>
            <span class="hint">Live counts, refreshed on every visit</span>
        </div>
        <div class="stat-tiles cols-6">
            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-user-check"></i></div><div><b><?php echo e($presentToday); ?></b><span>Present</span></div></div>
            <div class="stat-tile alt"><div class="st-ico"><i class="fa-regular fa-clock"></i></div><div><b><?php echo e($lateToday); ?></b><span>Late arrivals</span></div></div>
            <div class="stat-tile info"><div class="st-ico"><i class="fa-solid fa-house-laptop"></i></div><div><b><?php echo e($wfhToday); ?></b><span>WFH today</span></div></div>
            <div class="stat-tile warn"><div class="st-ico"><i class="fa-solid fa-plane-departure"></i></div><div><b><?php echo e($onLeaveToday); ?></b><span>On leave</span></div></div>
            <div class="stat-tile alt"><div class="st-ico"><i class="fa-regular fa-calendar-days"></i></div><div><b><?php echo e($leavePending); ?></b><span>Leave to approve</span></div></div>
            <div class="stat-tile info"><div class="st-ico"><i class="fa-solid fa-user-plus"></i></div><div><b><?php echo e($openRequisitions); ?></b><span>Open roles</span></div></div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <h3>Headcount by department</h3>
                        <p>Active employees only.</p>
                    </div>
                    <span class="pill pill-muted" style="margin-left:auto;"><?php echo e($headcountByDept->sum('employees_count')); ?> total</span>
                </div>
                <?php $__empty_1 = true; $__currentLoopData = $headcountByDept; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bar-row">
                        <span class="bar-label"><?php echo e($d->name); ?></span>
                        <div class="bar-track"><div class="bar-fill" style="width:<?php echo e($maxHeadcount ? round($d->employees_count / $maxHeadcount * 100) : 0); ?>%;"></div></div>
                        <span class="bar-value"><?php echo e($d->employees_count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-widget"><div class="ew-ico"><i class="fa-solid fa-users-slash"></i></div><b>No departments yet</b><span>Add departments to see headcount.</span></div>
                <?php endif; ?>
            </div>

            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-filter"></i></div>
                    <div>
                        <h3>Recruitment funnel</h3>
                        <p>All requisitions, all time.</p>
                    </div>
                    <span class="pill pill-muted" style="margin-left:auto;"><?php echo e($funnel->sum()); ?> candidates</span>
                </div>
                <?php
                    $funnelIcons = ['applied'=>'fa-paper-plane','shortlisted'=>'fa-list-check','interviewed'=>'fa-comments','offered'=>'fa-envelope-open-text','hired'=>'fa-handshake'];
                ?>
                <?php $__currentLoopData = $funnel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bar-row">
                        <span class="bar-label"><i class="fa-solid <?php echo e($funnelIcons[$stage] ?? 'fa-circle'); ?>" style="color:var(--brand);margin-right:7px;font-size:11px;"></i><?php echo e(ucfirst($stage)); ?></span>
                        <div class="bar-track"><div class="bar-fill" style="width:<?php echo e($maxFunnel ? round($count / $maxFunnel * 100) : 0); ?>%;"></div></div>
                        <span class="bar-value"><?php echo e($count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($funnel->sum() > 0): ?>
                    <div class="funnel-rate">
                        <i class="fa-solid fa-bullseye"></i>
                        Hire rate <b><?php echo e($funnel->sum() ? round($funnel['hired'] / $funnel->sum() * 100) : 0); ?>%</b>
                        <span>&middot; <?php echo e($funnel['hired']); ?> hired of <?php echo e($funnel->sum()); ?> candidates</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid-2" style="margin-top:20px;">
            <?php if($latestRun && $payrollByDept->isNotEmpty()): ?>
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-sack-dollar"></i></div>
                        <div>
                            <h3>Payroll cost by department</h3>
                            <p><?php echo e($latestRun->monthLabel()); ?> run.</p>
                        </div>
                        <span class="pill pill-warn" style="margin-left:auto;">₹<?php echo e(number_format($payrollByDept->sum('gross'),0)); ?> total</span>
                    </div>
                    <?php
                        $maxPayroll = max(1, $payrollByDept->max('gross'));
                        $barTones = ['#5E8D3D','#7CA243','#5CCFA6','#8CDfC1','#B8E8D6'];
                    ?>
                    <?php $__currentLoopData = $payrollByDept; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bar-row">
                            <span class="bar-label"><?php echo e($d->department); ?></span>
                            <div class="bar-track"><div class="bar-fill" style="width:<?php echo e(round($d->gross / $maxPayroll * 100)); ?>%;background:linear-gradient(90deg,#1F5C2E,<?php echo e($barTones[$loop->index % 5]); ?>);"></div></div>
                            <span class="bar-value">₹<?php echo e(number_format($d->gross,0)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-clipboard-check"></i></div>
                    <div>
                        <h3>Appraisal completion</h3>
                        <p>Across all cycles on record.</p>
                    </div>
                </div>
                <?php $pct = $appraisalTotal ? round($appraisalDone / $appraisalTotal * 100) : 0; ?>
                <div class="appr-wrap">
                    <div class="donut" style="--pct:<?php echo e($pct); ?>;">
                        <div class="donut-hole">
                            <b><?php echo e($pct); ?>%</b>
                            <span>complete</span>
                        </div>
                    </div>
                    <div class="appr-meta">
                        <div class="appr-line"><i class="fa-solid fa-circle-check" style="color:var(--brand-bright);"></i><b><?php echo e($appraisalDone); ?></b> completed</div>
                        <div class="appr-line"><i class="fa-regular fa-clock" style="color:#C9A227;"></i><b><?php echo e($appraisalTotal - $appraisalDone); ?></b> pending</div>
                        <div class="appr-line"><i class="fa-solid fa-users" style="color:var(--brand);"></i><b><?php echo e($appraisalTotal); ?></b> total appraisals</div>
                        <a href="<?php echo e(url('/hr/modules/appraisal')); ?>" class="appr-link">Open Performance module <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/reports.blade.php ENDPATH**/ ?>