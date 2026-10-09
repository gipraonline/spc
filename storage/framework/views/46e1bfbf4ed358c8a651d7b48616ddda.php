
<?php
    $inrT = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $metrics = \App\Models\Hr\SalesTarget::METRICS;
    $fmtT = fn ($row) => $row['money'] ? $inrT($row['actual']).' / '.$inrT($row['target']) : number_format($row['actual']).' / '.number_format($row['target']);
    $tone = fn ($pct) => $pct >= 100 ? 'var(--brand-strong)' : ($pct >= 70 ? '#d97706' : '#dc2626');
?>

<?php if($mine && !empty($mine['targets'])): ?>
    <div class="section-head" style="margin-top:28px;">
        <h2><i class="fa-solid fa-bullseye"></i>My targets</h2>
        <span class="hint"><?php echo e($selectedCycle?->name); ?></span>
    </div>
    <div class="card">
        <?php $__currentLoopData = $mine['targets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="pi-hrow">
                <span class="lbl" style="width:110px;"><?php echo e($row['label']); ?></span>
                <span class="trk"><i style="width:<?php echo e(min(100, $row['pct'])); ?>%;background:<?php echo e($tone($row['pct'])); ?>;"></i></span>
                <span class="val" style="width:70px;"><?php echo e($row['pct']); ?>%</span>
            </div>
            <div class="hint" style="font-size:12px;color:var(--text-muted);margin:-4px 0 8px 120px;"><?php echo e($fmtT($row)); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php if($targetEmployees->isNotEmpty() && $selectedCycle): ?>
    <div class="section-head" style="margin-top:28px;">
        <h2><i class="fa-solid fa-flag-checkered"></i>Set targets &middot; <?php echo e($selectedCycle->name); ?></h2>
        <span class="hint">Leave a box empty to remove that target</span>
    </div>
    <div class="table-card">
        <div class="tc-body pi-scroll">
            <form method="POST" action="<?php echo e(route('hr.appraisal.targets.save')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="appraisal_cycle_id" value="<?php echo e($selectedCycle->id); ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e($m['label']); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($team): ?><th>Sales achieved</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $targetEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $te): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $teamRow = $team ? collect($team['rows'])->firstWhere('code', $te->employee_code) : null; ?>
                            <tr>
                                <td><b><?php echo e($te->user->name ?? '—'); ?></b><br><span class="hint" style="font-size:11.5px;color:var(--text-muted);"><?php echo e($te->employee_code); ?></span></td>
                                <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td><input type="number" min="0" step="<?php echo e($m['money'] ? '1000' : '1'); ?>" name="targets[<?php echo e($te->id); ?>][<?php echo e($key); ?>]" value="<?php echo e($targetValues[$te->id][$key] ?? ''); ?>" style="width:110px;padding:7px 9px;border:1px solid var(--line);border-radius:8px;font:inherit;font-size:13px;"></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($team): ?>
                                    <td>
                                        <?php if($teamRow && $teamRow['target_pct'] !== null): ?>
                                            <span class="pill <?php echo e($teamRow['target_pct'] >= 100 ? 'pill-ok' : ($teamRow['target_pct'] >= 70 ? 'pill-warn' : 'pill-muted')); ?>"><?php echo e($teamRow['target_pct']); ?>%</span>
                                        <?php else: ?> — <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="form-actions" style="margin-top:14px;"><button type="submit" class="btn-primary">Save targets</button></div>
            </form>
            <p class="pi-note">Targets are for the whole cycle. Incentive rules with a minimum achievement use the monthly share (cycle target &divide; months in the cycle).</p>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/performance-targets.blade.php ENDPATH**/ ?>