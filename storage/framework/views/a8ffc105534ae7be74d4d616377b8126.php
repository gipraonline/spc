<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('hr.partials.topbar', [
'title' => $module['title'],
'eyebrow' => 'System',
'heroIcon' => 'fa-solid fa-shield-halved',
'heroSummary' => 'A record of who changed what, and when.',
'heroStats' => [
['label' => 'Entries', 'icon' => 'fa-solid fa-timeline', 'value' => $totalAuditEntries],
['label' => 'Today', 'icon' => 'fa-regular fa-calendar', 'value' => $todayCount],
],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="content">
    <style>
    .al-wrap {
        max-width: 900px;
    }

    .al-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .al-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: #fff;
        color: var(--text);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: border-color .15s, background .15s;
    }

    .al-chip small {
        font-size: 11.5px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .al-chip:hover {
        border-color: var(--brand-bright);
        background: var(--brand-softer);
    }

    .al-chip.on {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .al-chip.on small {
        color: rgba(255, 255, 255, .8);
    }

    .al-day {
        margin: 26px 0 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .al-day:first-of-type {
        margin-top: 0;
    }

    .al-list {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .al-item {
        display: grid;
        grid-template-columns: 40px minmax(0, 1fr) auto;
        gap: 14px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--line-soft);
        align-items: start;
    }

    .al-item:last-child {
        border-bottom: none;
    }

    .al-ico {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .al-ico.add {
        background: var(--ok-soft);
        color: var(--ok);
    }

    .al-ico.edit {
        background: var(--warn-soft);
        color: var(--warn);
    }

    .al-ico.remove {
        background: var(--bad-soft);
        color: var(--bad);
    }

    .al-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.4;
    }

    .al-lines {
        margin: 4px 0 0;
        padding: 0;
        list-style: none;
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .al-by {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .al-by .av {
        width: 22px;
        height: 22px;
        font-size: 11px;
    }

    .al-time {
        font-size: 12.5px;
        color: var(--text-muted);
        white-space: nowrap;
        text-align: right;
    }

    .al-raw {
        margin-top: 8px;
    }

    .al-raw summary {
        cursor: pointer;
        font-size: 12px;
        color: var(--brand);
        font-weight: 600;
        list-style: none;
    }

    .al-raw summary::-webkit-details-marker {
        display: none;
    }

    .al-raw pre {
        margin: 8px 0 0;
        padding: 10px 12px;
        background: var(--brand-softer);
        border-radius: 10px;
        font-size: 11.5px;
        overflow-x: auto;
        white-space: pre-wrap;
        word-break: break-word;
        color: var(--text);
    }

    .al-pager {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 16px;
        font-size: 13px;
        color: var(--text-muted);
    }

    .al-pager .btns {
        display: flex;
        gap: 8px;
    }

    .al-pager a,
    .al-pager span.dis {
        padding: 8px 14px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        color: var(--text);
        text-decoration: none;
        font-weight: 500;
    }

    .al-pager a:hover {
        border-color: var(--brand-bright);
        background: var(--brand-softer);
    }

    .al-pager span.dis {
        opacity: .4;
    }

    @media (max-width:640px) {
        .al-item {
            grid-template-columns: 36px minmax(0, 1fr);
        }

        .al-time {
            grid-column: 2;
            text-align: left;
        }
    }
    </style>

    <div class="al-wrap">
        <nav class="al-chips" aria-label="Filter by area">
            <a class="al-chip <?php if(! $area): ?> on <?php endif; ?>" href="<?php echo e(route('hr.system.index')); ?>">All
                <small><?php echo e($areaCounts['all']); ?></small></a>
            <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($areaCounts[$key] > 0 || $area === $key): ?>
            <a class="al-chip <?php if($area === $key): ?> on <?php endif; ?>"
                href="<?php echo e(route('hr.system.index', ['area' => $key])); ?>"><?php echo e($label); ?>

                <small><?php echo e($areaCounts[$key]); ?></small></a>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        <?php if($auditLog->isEmpty()): ?>
        <div class="table-card">
            <div class="tc-body">
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-timeline"></i></div>
                    <b><?php echo e($area ? 'Nothing here yet' : 'No activity recorded yet'); ?></b>
                    <span><?php echo e($area ? 'No changes have been recorded in this area.' : 'Changes to payroll, settings and access will appear here.'); ?></span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <?php
        $days = $auditLog->getCollection()->groupBy(fn ($l) =>
        \Illuminate\Support\Carbon::parse($l->created_at)->toDateString());
        ?>

        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $entries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $d = \Illuminate\Support\Carbon::parse($date);
        $dayLabel = $d->isToday() ? 'Today' : ($d->isYesterday() ? 'Yesterday' : $d->format('l, d M Y'));
        ?>
        <h3 class="al-day"><?php echo e($dayLabel); ?></h3>
        <div class="al-list">
            <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $sm = $log->summary; $who = $log->user->name ?? 'System'; ?>
            <div class="al-item">
                <div class="al-ico <?php echo e($sm['tone']); ?>"><i class="fa-solid <?php echo e($sm['icon']); ?>"></i></div>
                <div>
                    <div class="al-title"><?php echo e($sm['title']); ?></div>
                    <?php if($sm['lines']): ?>
                    <ul class="al-lines">
                        <?php $__currentLoopData = $sm['lines']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($line); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <?php endif; ?>
                    <div class="al-by">
                        <div class="av"><?php echo e(strtoupper(substr($who, 0, 1))); ?></div>
                        <span>by <?php echo e($who); ?><?php if($log->ip_address): ?> &middot; <?php echo e($log->ip_address); ?><?php endif; ?></span>
                    </div>
                    <?php if($log->old_value || $log->new_value): ?>
                    <details class="al-raw">
                        <summary>View technical details</summary>
                        <pre><?php if($log->old_value): ?>Before: <?php echo e($log->old_value); ?>

<?php endif; ?> <?php if($log->new_value): ?>After:  <?php echo e($log->new_value); ?><?php endif; ?></pre>
                    </details>
                    <?php endif; ?>
                </div>
                <div class="al-time"
                    title="<?php echo e(\Illuminate\Support\Carbon::parse($log->created_at)->format('d M Y, H:i:s')); ?>">
                    <?php echo e(\Illuminate\Support\Carbon::parse($log->created_at)->format('h:i A')); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="al-pager">
            <span>Showing <?php echo e($auditLog->firstItem()); ?>&ndash;<?php echo e($auditLog->lastItem()); ?> of
                <?php echo e($auditLog->total()); ?></span>
            <?php if($auditLog->hasPages()): ?>
            <div class="btns">
                <?php if($auditLog->onFirstPage()): ?><span class="dis">Newer</span><?php else: ?><a
                    href="<?php echo e($auditLog->previousPageUrl()); ?>">Newer</a><?php endif; ?>
                <?php if($auditLog->hasMorePages()): ?><a href="<?php echo e($auditLog->nextPageUrl()); ?>">Older</a><?php else: ?><span
                    class="dis">Older</span><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/system.blade.php ENDPATH**/ ?>