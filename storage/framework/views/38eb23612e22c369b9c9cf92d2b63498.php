<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('hr.partials.topbar', [
    'title' => $module['title'],
    'eyebrow' => 'Growth',
    'heroIcon' => 'fa-solid fa-trophy',
    'heroSummary' => match ($role) {
        'employee' => 'Your sales, attendance, targets and monthly report.',
        'manager' => 'Your own numbers, plus your team\'s sales, attendance and monthly report.',
        default => 'Your own numbers, plus company sales, attendance and a monthly report for every employee.',
    },
    'heroStats' => [],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    $inr = fn ($v) => \App\Services\Hr\PerformanceInsightsService::inr($v);
    $pct = fn ($v, $max) => $max > 0 ? max(2, (int) round($v / $max * 100)) : 0;
    $rate = fn ($v) => $v === null ? '—' : rtrim(rtrim(number_format($v, 1), '0'), '.').'%';

    $isHrRole = in_array($role, ['hr_admin', 'super_admin'], true);
    $hasOverview = (bool) ($team || $org);
    $hasReport = (bool) $report;
    $hasMine = (bool) $mineMonth;
    $available = array_keys(array_filter(['mine' => $hasMine, 'overview' => $hasOverview, 'report' => $hasReport, 'targets' => true]));
    $activeTab = in_array(request('tab'), $available, true) ? request('tab') : $available[0];
?>

<style>
    .pi-help{display:flex;gap:12px;align-items:flex-start;background:var(--brand-soft);border:1px solid var(--line);border-radius:14px;padding:14px 16px;margin:0 0 22px;font-size:13.5px;line-height:1.55;color:var(--brand-ink);}
    .pi-help i{margin-top:3px;color:var(--brand);}
    .pi-help ul{margin:6px 0 0 18px;padding:0;}
    .pi-month{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:28px 0 0;}
    .pi-month input[type=month]{padding:8px 12px;border:1px solid var(--line);border-radius:10px;background:var(--surface);font:inherit;font-size:13px;}
</style>

<div class="content">

    <?php if($hasMine || $hasOverview || $hasReport): ?>
        <form method="GET" action="<?php echo e(route('hr.appraisal.index')); ?>" class="pi-month">
            <input type="hidden" name="tab" id="pi-tab-field" value="<?php echo e($activeTab); ?>">
            <label for="pi-month" style="font-size:12.5px;color:var(--text-muted);">Month</label>
            <input type="month" id="pi-month" name="month" value="<?php echo e($month->format('Y-m')); ?>" max="<?php echo e(now()->format('Y-m')); ?>" onchange="this.form.submit()">
        </form>
    <?php endif; ?>

    <div class="tabs" style="margin-top:20px;">
        <?php if($hasMine): ?>
            <button type="button" class="tab <?php echo e($activeTab === 'mine' ? 'active' : ''); ?>" onclick="perfTab(this,'mine')">My performance</button>
        <?php endif; ?>
        <?php if($hasOverview): ?>
            <button type="button" class="tab <?php echo e($activeTab === 'overview' ? 'active' : ''); ?>" onclick="perfTab(this,'overview')"><?php echo e($isHrRole ? 'Company' : 'My team'); ?></button>
        <?php endif; ?>
        <?php if($hasReport): ?>
            <button type="button" class="tab <?php echo e($activeTab === 'report' ? 'active' : ''); ?>" onclick="perfTab(this,'report')">Employee report</button>
        <?php endif; ?>
        <button type="button" class="tab <?php echo e($activeTab === 'targets' ? 'active' : ''); ?>" onclick="perfTab(this,'targets')">Targets</button>
    </div>

    
    <?php if($hasMine): ?>
    <div class="tabpanel <?php echo e($activeTab === 'mine' ? 'active' : ''); ?>" data-tabpanel="mine">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                Your own sales, attendance, leave, WFH, late marks and salary paid for the month picked above. Only you can see this page of numbers; your targets are in the Targets tab.
            </div>
        </div>
        <?php echo $__env->make('hr.partials.performance-mine', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <?php endif; ?>

    
    <?php if($hasOverview): ?>
    <div class="tabpanel <?php echo e($activeTab === 'overview' ? 'active' : ''); ?>" data-tabpanel="overview">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                <?php echo e($isHrRole ? 'How the whole company is doing' : 'How your team is doing'); ?> in the month picked above.
                <ul>
                    <li><b>Sales</b> is the value of orders booked. Cancelled and rejected orders are left out.</li>
                    <li><b>Attendance</b> is the share of working days people were present.</li>
                    <li><b>Incentive paid</b> is incentive money already paid out. Money approved but not yet paid is shown under it.</li>
                </ul>
            </div>
        </div>
        <?php echo $__env->make('hr.partials.performance-insights', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <?php endif; ?>

    
    <?php if($hasReport): ?>
    <div class="tabpanel <?php echo e($activeTab === 'report' ? 'active' : ''); ?>" data-tabpanel="report">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                One row per employee for the month picked above: sales, leave days, WFH days, late marks and the salary paid.
                <?php echo e($isHrRole ? 'You see all active employees.' : 'You see the people who report to you.'); ?>

                Use <i>Download CSV</i> to open it in Excel.
            </div>
        </div>
        <?php echo $__env->make('hr.partials.performance-employee-report', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <?php endif; ?>

    
    <div class="tabpanel <?php echo e($activeTab === 'targets' ? 'active' : ''); ?>" data-tabpanel="targets">
        <div class="pi-help">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                <?php if($role === 'employee'): ?>
                    Goals your manager set for you and how much you have achieved so far. Green means reached, amber means close, red means behind.
                <?php elseif($role === 'manager'): ?>
                    See your own targets and set targets for the people who report to you. Leave a box empty to remove a target.
                <?php else: ?>
                    Set targets for any active employee. Leave a box empty to remove a target. Each person sees their own progress.
                <?php endif; ?>
                <?php if($targetPeriod): ?><br>Target period: <b><?php echo e($targetFrom->format('d M Y')); ?> &ndash; <?php echo e($targetTo->format('d M Y')); ?></b><?php endif; ?>
            </div>
        </div>
        <?php echo $__env->make('hr.partials.performance-targets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if(empty($mine['targets'] ?? []) && $targetEmployees->isEmpty()): ?>
            <div class="table-card"><div class="tc-body"><div class="empty-widget">
                <div class="ew-ico"><i class="fa-solid fa-bullseye"></i></div>
                <b>No targets yet</b>
                <span>Nothing has been set for you for this period.</span>
            </div></div></div>
        <?php endif; ?>
    </div>
</div>

<script>
function perfTab(btn, name) {
    const scope = document.querySelector('.content');
    scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
    scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    scope.querySelector('[data-tabpanel="' + name + '"]').classList.add('active');
    const field = document.getElementById('pi-tab-field');
    if (field) { field.value = name; }
    const url = new URL(window.location);
    url.searchParams.set('tab', name);
    history.replaceState(null, '', url);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/appraisal.blade.php ENDPATH**/ ?>