
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


<div class="pi-period">
    <form method="GET" action="<?php echo e(route('hr.appraisal.index')); ?>" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <label for="pi-cycle" class="hint" style="font-size:12.5px;color:var(--text-muted);">Showing figures for</label>
        <select id="pi-cycle" name="cycle" onchange="this.form.submit()">
            <?php $__currentLoopData = $cycles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>" <?php if($selectedCycle && $selectedCycle->id === $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?> (<?php echo e(ucfirst($c->status)); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($cycles->isEmpty()): ?><option>Last 90 days</option><?php endif; ?>
        </select>
        <span class="hint" style="font-size:12.5px;color:var(--text-muted);"><?php echo e($periodFrom->format('d M Y')); ?> &ndash; <?php echo e($periodTo->format('d M Y')); ?></span>
    </form>
</div>


<?php if($mine): ?>
    <div class="section-head">
        <h2><i class="fa-solid fa-chart-line"></i>My performance</h2>
        <span class="hint">Only you can see this</span>
    </div>

    <div class="kpi-row" style="margin-top:0;">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Net sales</span></div>
            <div class="kpi-val"><?php echo e($mine['sales_linked'] ? $inr($mine['sales']['net_sales']) : '—'); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-receipt"></i><?php echo e($mine['sales']['orders']); ?> orders &middot; <?php echo e($mine['sales']['customers']); ?> customers</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Avg. order value</span></div>
            <div class="kpi-val"><?php echo e($mine['sales_linked'] ? $inr($mine['sales']['avg_order']) : '—'); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-circle-check"></i><?php echo e($mine['sales']['approved']); ?> approved &middot; <?php echo e($mine['sales']['pending']); ?> pending</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Attendance</span></div>
            <div class="kpi-val"><?php echo e($rate($mine['attendance']['rate'])); ?></div>
            <div class="kpi-sub"><i class="fa-regular fa-clock"></i><?php echo e($mine['attendance']['late']); ?> late &middot; <?php echo e($mine['attendance']['absent']); ?> absent</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Incentive earned</span></div>
            <div class="kpi-val"><?php echo e($inr($mine['incentives']['earned'])); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i><?php echo e($inr($mine['incentives']['pending'])); ?> pending approval</div>
        </div>
    </div>

    <?php if (! ($mine['sales_linked'])): ?>
        <p class="pi-note"><i class="fa-solid fa-circle-info"></i> Your HR record isn't linked to an SPC sales profile yet, so sales figures aren't available. Ask HR to link it.</p>
    <?php endif; ?>

    <div class="grid-2" style="margin-top:18px;">
        <div class="card">
            <h3>Sales trend</h3>
            <p class="field-hint">Net sales over the last 6 months</p>
            <?php $trendMax = max(array_column($mine['trend'], 'net_sales') ?: [0]); ?>
            <div class="pi-bars">
                <?php $__currentLoopData = $mine['trend']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pi-bar" title="<?php echo e($m['orders']); ?> orders">
                        <em><?php echo e($m['net_sales'] > 0 ? $inr($m['net_sales']) : ''); ?></em>
                        <i style="height:<?php echo e($pct($m['net_sales'], $trendMax)); ?>%;"></i>
                        <small><?php echo e($m['label']); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="card">
            <h3>Leads &amp; ratings</h3>
            <p class="field-hint"><?php echo e($mine['leads']['total']); ?> leads in this period</p>
            <?php $leadMax = max($mine['leads']['by_status'] ?: [0]); ?>
            <?php $__empty_1 = true; $__currentLoopData = array_slice($mine['leads']['by_status'], 0, 5, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="pi-hrow"><span class="lbl"><?php echo e($status); ?></span><span class="trk"><i style="width:<?php echo e($pct($n, $leadMax)); ?>%"></i></span><span class="val"><?php echo e($n); ?></span></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="pi-note">No leads logged in this period.</p>
            <?php endif; ?>

            <?php if(!empty($mine['ratings'])): ?>
                <p class="field-hint" style="margin-top:16px;">Appraisal rating history</p>
                <?php $__currentLoopData = $mine['ratings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pi-hrow"><span class="lbl"><?php echo e($r['cycle']); ?></span><span class="trk"><i style="width:<?php echo e($pct($r['rating'], 5)); ?>%"></i></span><span class="val"><?php echo e(number_format($r['rating'], 2)); ?></span></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>


<?php if($team): ?>
    <div class="section-head" style="margin-top:36px;">
        <h2><i class="fa-solid fa-people-group"></i>My team</h2>
        <span class="hint"><?php echo e($team['size']); ?> direct <?php echo e(\Illuminate\Support\Str::plural('report', $team['size'])); ?></span>
    </div>

    <?php if($team['size'] === 0): ?>
        <div class="card"><p class="pi-note" style="margin:0;">No one reports to you yet.</p></div>
    <?php else: ?>
        <div class="kpi-row" style="margin-top:0;">
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team net sales</span></div>
                <div class="kpi-val"><?php echo e($inr($team['sales']['net_sales'])); ?></div>
                <div class="kpi-sub"><i class="fa-solid fa-receipt"></i><?php echo e($team['sales']['orders']); ?> orders</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team attendance</span></div>
                <div class="kpi-val"><?php echo e($rate($team['attendance']['rate'])); ?></div>
                <div class="kpi-sub"><i class="fa-regular fa-clock"></i><?php echo e($team['attendance']['late']); ?> late &middot; <?php echo e($team['attendance']['absent']); ?> absent</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team leads</span></div>
                <div class="kpi-val"><?php echo e($team['leads']['total']); ?></div>
                <div class="kpi-sub"><i class="fa-solid fa-bullseye"></i>this period</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><span class="kpi-label">Team incentive</span></div>
                <div class="kpi-val"><?php echo e($inr($team['incentives']['earned'])); ?></div>
                <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i><?php echo e($inr($team['incentives']['pending'])); ?> pending</div>
            </div>
        </div>

        <div class="table-card" style="margin-top:18px;">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th>Employee</th><th>Orders</th><th>Net sales</th><th>Attendance</th><th>Latest rating</th><th>Appraisal</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $team['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><b><?php echo e($row['name']); ?></b><br><span class="field-hint"><?php echo e($row['code']); ?></span></td>
                                <td><?php echo e($row['sales_linked'] ? $row['orders'] : '—'); ?></td>
                                <td><b><?php echo e($row['sales_linked'] ? $inr($row['net_sales']) : '—'); ?></b></td>
                                <td><?php echo e($rate($row['attendance_rate'])); ?></td>
                                <td><?php echo e($row['latest_rating'] ? number_format($row['latest_rating'], 2).' / 5' : '—'); ?></td>
                                <td><span class="pill <?php echo e($row['appraisal_status'] === 'completed' ? 'pill-ok' : 'pill-warn'); ?>"><?php echo e($statusLabel($row['appraisal_status'])); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>


<?php if($org): ?>
    <div class="section-head" style="margin-top:36px;">
        <h2><i class="fa-solid fa-building"></i>Organisation overview</h2>
        <span class="hint"><?php echo e($role === 'super_admin' ? 'Org-wide, including franchises' : 'Org-wide people & performance'); ?></span>
    </div>

    <div class="kpi-row" style="margin-top:0;">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Headcount</span></div>
            <div class="kpi-val"><?php echo e($org['headcount']['active']); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-user-clock"></i><?php echo e($org['headcount']['on_notice']); ?> on notice</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Appraisal completion</span></div>
            <div class="kpi-val"><?php echo e($org['appraisal']['completion_pct'] !== null ? $org['appraisal']['completion_pct'].'%' : '—'); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-user-pen"></i><?php echo e($org['appraisal']['completed']); ?> of <?php echo e($org['appraisal']['total']); ?> &middot; <?php echo e($org['appraisal']['pending_manager']); ?> awaiting review</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Average rating</span></div>
            <div class="kpi-val"><?php echo e($org['appraisal']['avg_rating'] !== null ? number_format($org['appraisal']['avg_rating'], 2) : '—'); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-star"></i>out of 5</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Net sales</span></div>
            <div class="kpi-val"><?php echo e($inr($org['sales']['net_sales'])); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-receipt"></i><?php echo e($org['sales']['orders']); ?> orders &middot; <?php echo e($inr($org['sales']['avg_order'])); ?> avg</div>
        </div>
    </div>

    <div class="kpi-row" style="margin-top:18px;">
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Attendance</span></div>
            <div class="kpi-val"><?php echo e($rate($org['attendance']['rate'])); ?></div>
            <div class="kpi-sub"><i class="fa-regular fa-clock"></i><?php echo e($org['attendance']['late']); ?> late &middot; <?php echo e($org['attendance']['absent']); ?> absent</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Leads</span></div>
            <div class="kpi-val"><?php echo e($org['leads']['total']); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-bullseye"></i>created this period</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">Incentive cost</span></div>
            <div class="kpi-val"><?php echo e($inr($org['incentives']['earned'])); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-hourglass-half"></i><?php echo e($inr($org['incentives']['pending'])); ?> pending</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top"><span class="kpi-label">GST / discounts</span></div>
            <div class="kpi-val"><?php echo e($inr($org['sales']['gst'])); ?></div>
            <div class="kpi-sub"><i class="fa-solid fa-tags"></i><?php echo e($inr($org['sales']['discount'])); ?> discounts</div>
        </div>
    </div>

    <div class="grid-2" style="margin-top:18px;">
        <div class="card">
            <h3>Sales trend</h3>
            <p class="field-hint">Organisation net sales, last 6 months</p>
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

        <div class="card">
            <h3>Rating distribution</h3>
            <p class="field-hint"><?php echo e($selectedCycle?->name ?? 'Selected cycle'); ?> &middot; final ratings</p>
            <?php $distMax = max($org['appraisal']['distribution'] ?: [0]); ?>
            <?php $__currentLoopData = $org['appraisal']['distribution']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="pi-hrow"><span class="lbl"><?php echo e($label); ?></span><span class="trk"><i style="width:<?php echo e($n ? $pct($n, $distMax) : 0); ?>%"></i></span><span class="val"><?php echo e($n); ?></span></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(!empty($org['appraisal']['by_department'])): ?>
                <p class="field-hint" style="margin-top:16px;">Average rating by department</p>
                <?php $__currentLoopData = $org['appraisal']['by_department']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pi-hrow"><span class="lbl"><?php echo e($d['department']); ?></span><span class="trk"><i style="width:<?php echo e($pct($d['avg_rating'], 5)); ?>%"></i></span><span class="val"><?php echo e(number_format($d['avg_rating'], 2)); ?></span></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid-2" style="margin-top:18px;">
        <div class="table-card">
            <div class="tc-body pi-scroll">
                <table>
                    <thead><tr><th colspan="3">Top sellers</th></tr><tr><th>Advisor</th><th>Orders</th><th>Net sales</th></tr></thead>
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

        <?php if($role === 'super_admin'): ?>
            <div class="table-card">
                <div class="tc-body pi-scroll">
                    <table>
                        <thead><tr><th colspan="3">Franchise / store sales</th></tr><tr><th>Store</th><th>Orders</th><th>Net sales</th></tr></thead>
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
        <?php else: ?>
            <div class="card">
                <h3>Appraisal pipeline</h3>
                <p class="field-hint"><?php echo e($selectedCycle?->name ?? 'Selected cycle'); ?></p>
                <?php $pipeMax = max($org['appraisal']['by_status'] ?: [0]); ?>
                <?php $__empty_1 = true; $__currentLoopData = $org['appraisal']['by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="pi-hrow"><span class="lbl"><?php echo e($statusLabel($st)); ?></span><span class="trk"><i style="width:<?php echo e($pct($n, $pipeMax)); ?>%"></i></span><span class="val"><?php echo e($n); ?></span></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="pi-note">No appraisals in this cycle.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/performance-insights.blade.php ENDPATH**/ ?>