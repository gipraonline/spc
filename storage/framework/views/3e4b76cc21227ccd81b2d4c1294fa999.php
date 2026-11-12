
<?php if($report): ?>
    <?php $inrR = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v); ?>
@endphp
    <div class="section-head">
        <h2><i class="fa-solid fa-table-list"></i>Employee report &mdash; <?php echo e($month->format('F Y')); ?></h2>
        <a href="<?php echo e(route('hr.appraisal.report.export', ['month' => $month->format('Y-m')])); ?>" class="btn-secondary" style="padding:8px 14px;">
            <i class="fa-solid fa-file-arrow-down"></i> Download CSV
        </a>
    </div>
 

    <div class="table-card">
        <div class="tc-body pi-scroll">
            <?php if(empty($report['rows'])): ?>
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-users"></i></div>
                    <b>No employees to show</b>
                    <span><?php echo e($role === 'manager' ? 'No one reports to you yet.' : 'There are no active employees.'); ?></span>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th><th>Department</th><th>Sales</th><th>Leave (days)</th>
                            <th>WFH (days)</th><th>Late</th><th>Salary paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $report['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><b><?php echo e($r['name']); ?></b><br><span class="field-hint"><?php echo e($r['code']); ?></span></td>
                                <td><?php echo e($r['department']); ?></td>
                                <td><b><?php echo e($r['sales_linked'] ? $inrR($r['sales']) : '—'); ?></b><?php if($r['sales_linked']): ?><br><span class="field-hint"><?php echo e($r['orders']); ?> orders</span><?php endif; ?></td>
                                <td><?php echo e($r['leave_days']); ?></td>
                                <td><?php echo e($r['wfh_days']); ?></td>
                                <td><?php echo e($r['late']); ?></td>
                                <td><?php echo e($r['salary_paid'] !== null ? '₹'.number_format($r['salary_paid']) : 'Not paid yet'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><b>Total</b></td>
                            <td><b><?php echo e($inrR($report['totals']['sales'])); ?></b></td>
                            <td><b><?php echo e($report['totals']['leave_days']); ?></b></td>
                            <td><b><?php echo e($report['totals']['wfh_days']); ?></b></td>
                            <td><b><?php echo e($report['totals']['late']); ?></b></td>
                            <td><b>₹<?php echo e(number_format($report['totals']['salary_paid'])); ?></b></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/performance-employee-report.blade.php ENDPATH**/ ?>