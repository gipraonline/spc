
<?php
    $inr = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $pct = fn ($v, $max) => $max > 0 ? max(2, (int) round($v / $max * 100)) : 0;
    $rate = fn ($v) => $v === null ? '—' : rtrim(rtrim(number_format($v, 1), '0'), '.').'%';
    $statusLabel = fn ($s) => $s ? ucfirst(str_replace('_', ' ', $s)) : '—';
?>

<style>
    .pi-bars{display:flex;align-items:flex-end;gap:12px;height:150px;margin-top:14px;}
    .pi-bar{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;gap:6px;min-width:0;}
    .pi-bar i{display:block;width:100%;max-width:44px;border-radius:8px 8px 3px 3px;background:linear-gradient(180deg,var(--brand-bright),var(--brand-strong));min-height:3px;}
    .pi-bar small{font-size:10.5px;color:var(--text-muted);white-space:nowrap;}
    .pi-bar em{font-style:normal;font-size:10.5px;font-weight:600;color:var(--brand-ink);white-space:nowrap;}
    .pi-hrow{display:flex;align-items:center;gap:10px;margin:10px 0;font-size:12.5px;}
    .pi-hrow .lbl{width:118px;flex-shrink:0;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .pi-hrow .trk{flex:1;height:9px;border-radius:99px;background:var(--brand-soft);overflow:hidden;}
    .pi-hrow .trk i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--brand-bright),var(--brand-strong));}
    .pi-hrow .val{width:64px;text-align:right;font-weight:600;color:var(--brand-ink);font-variant-numeric:tabular-nums;}
    .pi-period{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:6px 0 0;}
    .pi-period select{padding:8px 12px;border:1px solid var(--line);border-radius:10px;background:var(--surface);font:inherit;font-size:13px;}
    .pi-note{font-size:12.5px;color:var(--text-muted);margin:12px 0 0;}
    .pi-scroll{overflow-x:auto;}
    @media (max-width:900px){.grid-2{grid-template-columns:1fr;}.kpi-row{grid-template-columns:repeat(2,1fr);}}
</style>


<?php if($team): ?>
    <div class="section-head">
        <h2><i class="fa-solid fa-people-group"></i>My team</h2>
        <span class="hint"><?php echo e($team['size']); ?> direct <?php echo e(\Illuminate\Support\Str::plural('report', $team['size'])); ?> &middot; <?php echo e($periodFrom->format('d M')); ?> &ndash; <?php echo e($periodTo->format('d M Y')); ?></span>
    </div>

    <?php if($team['size'] === 0): ?>
        <div class="card"><p class="pi-note" style="margin:0;">No one reports to you yet.</p></div>
    <?php else: ?>
        <div class="kpi-row" style="margin-top:0;">
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Sales</span></div>
                <div class="kpi-val"><?php echo e($inr($team['sales']['net_sales'])); ?></div>
                <div class="kpi-sub"><i class="fa-solid fa-receipt"></i><?php echo e($team['sales']['orders']); ?> orders</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Attendance</span></div>
                <div class="kpi-val"><?php echo e($rate($team['attendance']['rate'])); ?></div>
                <div class="kpi-sub"><i class="fa-regular fa-clock"></i><?php echo e($team['attendance']['late']); ?> late &middot; <?php echo e($team['attendance']['absent']); ?> absent</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">New leads</span></div>
                <div class="kpi-val"><?php echo e($team['leads']['total']); ?></div>
                <div class="kpi-sub"><i class="fa-solid fa-bullseye"></i>this month</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Incentive paid</span></div>
                <div class="kpi-val"><?php echo e($inr($team['incentives']['paid'])); ?></div>
                <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i><?php echo e($inr($team['incentives']['awaiting_payment'])); ?> awaiting payment</div>
            </div>
        </div>

        <div class="table-card" style="margin-top:18px;">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th>Employee</th><th>Orders</th><th>Sales</th><th>Attendance</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $team['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><b><?php echo e($row['name']); ?></b><br><span class="field-hint"><?php echo e($row['code']); ?></span></td>
                                <td><?php echo e($row['sales_linked'] ? $row['orders'] : '—'); ?></td>
                                <td><b><?php echo e($row['sales_linked'] ? $inr($row['net_sales']) : '—'); ?></b></td>
                                <td><?php echo e($rate($row['attendance_rate'])); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>


<?php if($org): ?>
    <div class="section-head">
        <h2><i class="fa-solid fa-building"></i>Company</h2>
        <span class="hint"><?php echo e($periodFrom->format('d M')); ?> &ndash; <?php echo e($periodTo->format('d M Y')); ?><?php echo e($role === 'super_admin' ? ' · including franchises' : ''); ?></span>
    </div>

    <div class="kpi-row" style="margin-top:0;">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Employees</span></div>
            <div class="kpi-val"><?php echo e($org['headcount']['active']); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-user-clock"></i><?php echo e($org['headcount']['on_notice']); ?> on notice</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Sales</span></div>
            <div class="kpi-val"><?php echo e($inr($org['sales']['net_sales'])); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-receipt"></i><?php echo e($org['sales']['orders']); ?> orders</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Attendance</span></div>
            <div class="kpi-val"><?php echo e($rate($org['attendance']['rate'])); ?></div>
            <div class="kpi-sub"><i class="fa-regular fa-clock"></i><?php echo e($org['attendance']['late']); ?> late &middot; <?php echo e($org['attendance']['absent']); ?> absent</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Incentive paid</span></div>
            <div class="kpi-val"><?php echo e($inr($org['incentives']['paid'])); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i><?php echo e($inr($org['incentives']['awaiting_payment'])); ?> awaiting payment</div>
        </div>
    </div>

    <div class="grid-2" style="margin-top:18px;">
        <div class="card">
            <h3>Sales, last 6 months</h3>
            <?php $orgMax = max(array_column($org['trend'], 'net_sales') ?: [0]); ?>
            <div class="pi-bars">
                <?php $__currentLoopData = $org['trend']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pi-bar" title="<?php echo e($m['orders']); ?> orders">
                        <em><?php echo e($m['net_sales'] > 0 ? $inr($m['net_sales']) : ''); ?></em>
                        <i style="height:<?php echo e($pct($m['net_sales'], $orgMax)); ?>%;"></i>
                        <small><?php echo e($m['label']); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="table-card">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th colspan="3">Top sellers</th></tr><tr><th>Advisor</th><th>Orders</th><th>Sales</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $org['top_sellers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr><td><b><?php echo e($s['name']); ?></b><br><span class="field-hint"><?php echo e($s['code']); ?></span></td><td><?php echo e($s['orders']); ?></td><td><b><?php echo e($inr($s['net_sales'])); ?></b></td></tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="field-hint">No sales recorded in this period.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($role === 'super_admin'): ?>
        <div class="table-card" style="margin-top:18px;">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th colspan="3">Franchise / store sales</th></tr><tr><th>Store</th><th>Orders</th><th>Sales</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $org['franchises']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr><td><b><?php echo e($f['name']); ?></b><br><span class="field-hint"><?php echo e($f['code']); ?></span></td><td><?php echo e($f['orders']); ?></td><td><b><?php echo e($inr($f['net_sales'])); ?></b></td></tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="field-hint">No franchise-attributed orders in this period.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/performance-insights.blade.php ENDPATH**/ ?>