<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Money',
        'heroIcon' => 'fa-solid fa-piggy-bank',
        'heroSummary' => 'Provident fund contributions and gratuity eligibility at a glance.',
        'heroStats' => array_filter([
            $pfAccount ? ['label' => 'UAN', 'icon' => 'fa-solid fa-fingerprint', 'value' => \Illuminate\Support\Str::limit($pfAccount->uan_number, 12, '')] : null,
            ['label' => 'Contributions', 'icon' => 'fa-solid fa-layer-group', 'value' => $contributions->count()],
            $gratuity ? ['label' => 'Tenure', 'icon' => 'fa-solid fa-hourglass-end', 'value' => rtrim(rtrim(number_format($gratuity->tenure_years,1),'0'),'.').' yrs'] : null,
        ]),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        <div class="grid-2">
            <div class="table-card">
                <div class="tc-head">
                    <h3><span class="wh-ico"><i class="fa-solid fa-piggy-bank"></i></span>PF contributions</h3>
                    <?php if($pfAccount): ?><span class="pill pill-muted">PF No: <?php echo e($pfAccount->pf_number); ?></span><?php endif; ?>
                </div>
                <div class="tc-body">
                    <?php if($contributions->isEmpty()): ?>
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-piggy-bank"></i></div>
                            <b>No contributions recorded yet</b>
                            <span>Your PF share appears after your first payroll cycle.</span>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead><tr><th>Month</th><th>Employee share</th><th>Employer share</th></tr></thead>
                            <tbody>
                                <?php $__currentLoopData = $contributions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><b><?php echo e($c->payrollRun->monthLabel()); ?></b></td>
                                        <td>₹<?php echo e(number_format($c->employee_share,0)); ?></td>
                                        <td>₹<?php echo e(number_format($c->employer_share,0)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-award"></i></div>
                    <div>
                        <h3>Gratuity eligibility</h3>
                        <p>Based on continuous tenure &mdash; eligible after 5 years.</p>
                    </div>
                </div>
                <?php if($gratuity): ?>
                    <?php $gpct = min(100, round($gratuity->tenure_years / 5 * 100)); ?>
                    <div class="ring-card" style="border:none;box-shadow:none;padding:0 0 14px;">
                        <div class="ring" style="--pct:<?php echo e($gpct); ?>;"><b><?php echo e(rtrim(rtrim(number_format($gratuity->tenure_years,1),'0'),'.')); ?>y</b></div>
                        <h4><?php echo e($gratuity->is_eligible ? 'Eligible for gratuity' : 'Progress to 5 years'); ?></h4>
                        <small><?php echo e($gratuity->is_eligible ? 'Eligible from '.$gratuity->eligible_date : $gpct.'% of the 5-year milestone'); ?></small>
                    </div>
                    <div class="stat-tiles" style="grid-template-columns:1fr 1fr;margin-bottom:6px;">
                        <div class="stat-tile"><div class="st-ico"><i class="fa-regular fa-calendar-check"></i></div><div><b style="font-size:15px;"><?php echo e($gratuity->eligible_date ?? '—'); ?></b><span>Eligible from</span></div></div>
                        <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-indian-rupee-sign"></i></div><div><b style="font-size:15px;">₹<?php echo e(number_format($gratuity->estimated_amount,0)); ?></b><span>Est. amount</span></div></div>
                    </div>
                    <p class="field-hint">*Estimate at current basic pay; recalculated each payroll cycle.</p>
                <?php else: ?>
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-solid fa-hourglass-start"></i></div>
                        <b>No gratuity record yet</b>
                        <span>Typically created once tenure tracking begins.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/pf-gratuity.blade.php ENDPATH**/ ?>