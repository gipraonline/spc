<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('hr.partials.topbar', [
'title' => $module['title'],
'eyebrow' => 'People',
'heroIcon' => 'fa-solid fa-file-circle-check',
'heroSummary' => 'Review the documents employees upload, then verify them or send them back with a reason.',
'heroStats' => [
['label' => 'Pending', 'icon' => 'fa-regular fa-clock', 'value' => $counts['pending']],
['label' => 'Verified', 'icon' => 'fa-solid fa-check', 'value' => $counts['verified']],
['label' => 'Rejected', 'icon' => 'fa-solid fa-xmark', 'value' => $counts['rejected']],
],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
$tabs = [
'pending' => ['Pending', $counts['pending']],
'verified' => ['Verified', $counts['verified']],
'rejected' => ['Rejected', $counts['rejected']],
'all' => ['All', array_sum($counts)],
];
$pillFor = ['verified' => 'pill-ok', 'pending' => 'pill-warn', 'rejected' => 'pill-bad'];
$myUserId = $authUser->id;
?>

<style>
.dv-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.dv-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 99px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    background: #fff;
    border: 1px solid var(--line);
    text-decoration: none;
}

.dv-tab b {
    font-size: 11px;
    background: var(--brand-soft);
    color: var(--brand-ink);
    border-radius: 99px;
    padding: 1px 8px;
}

.dv-tab.active {
    background: var(--brand);
    color: #fff;
    border-color: var(--brand);
}

.dv-tab.active b {
    background: rgba(255, 255, 255, .22);
    color: #fff;
}

.dv-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin: 16px 0;
}

.dv-filters input,
.dv-filters select {
    padding: 9px 12px;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    font: inherit;
    font-size: 13px;
    background: #fff;
    min-width: 170px;
}

.dv-row.focus {
    background: var(--warn-soft);
    outline: 2px solid var(--warn);
    outline-offset: -2px;
}

.dv-emp b {
    display: block;
    color: var(--brand-ink);
}

.dv-emp span {
    font-size: 12px;
    color: var(--text-muted);
}

.dv-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.dv-actions form {
    margin: 0;
}

.dv-note {
    font-size: 12px;
    color: var(--bad);
    margin-top: 4px;
    max-width: 260px;
    white-space: normal;
}

dialog.dv-dialog {
    border: none;
    border-radius: 18px;
    padding: 0;
    width: min(920px, 94vw);
    box-shadow: var(--shadow-lg);
}

dialog.dv-dialog::backdrop {
    background: rgba(8, 46, 29, .55);
    backdrop-filter: blur(3px);
}

.dv-dialog-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid var(--line);
}

.dv-dialog-head h3 {
    margin: 0;
    font-family: var(--font-head);
    font-size: 17px;
    color: var(--brand-ink);
}

.dv-x {
    background: none;
    border: none;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
    color: var(--text-muted);
}

.dv-view {
    height: 72vh;
    background: var(--paper);
    display: flex;
    align-items: center;
    justify-content: center;
}

.dv-view iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

.dv-view img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.dv-reject-body {
    padding: 20px 22px;
}

.dv-reject-body textarea {
    width: 100%;
    min-height: 96px;
    padding: 10px 12px;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    font: inherit;
}

.dv-reject-foot {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 0 22px 20px;
}

dialog.dv-small {
    width: min(480px, 92vw);
}
</style>

<div class="content">
    <div class="dv-tabs">
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$label, $total]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="dv-tab <?php echo e($statusFilter === $key ? 'active' : ''); ?>"
            href="<?php echo e(route('hr.verification.index', array_filter(['status' => $key, 'type' => $typeFilter, 'q' => $search]))); ?>">
            <?php echo e($label); ?> <b><?php echo e($total); ?></b>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="GET" action="<?php echo e(route('hr.verification.index')); ?>" class="dv-filters">
        <input type="hidden" name="status" value="<?php echo e($statusFilter); ?>">
        <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Search employee name or ID">
        <select name="type" onchange="this.form.submit()">
            <option value="">All document types</option>
            <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($t); ?>" <?php if($typeFilter===$t): echo 'selected'; endif; ?>><?php echo e($t); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button type="submit" class="btn-secondary">Search</button>
        <?php if($search !== '' || $typeFilter !== ''): ?>
        <a class="btn-ghost" href="<?php echo e(route('hr.verification.index', ['status' => $statusFilter])); ?>">Clear</a>
        <?php endif; ?>
    </form>

    <div class="table-card">
        <div class="tc-head">
            <h3><span class="wh-ico"><i class="fa-solid fa-file-circle-check"></i></span>Uploaded documents</h3>
            <span class="pill pill-muted"><?php echo e($documents->total()); ?> shown</span>
        </div>
        <div class="tc-body">
            <?php if($documents->isEmpty()): ?>
            <div class="empty-widget">
                <div class="ew-ico"><i class="fa-regular fa-circle-check"></i></div>
                <b><?php echo e($statusFilter === 'pending' ? 'Nothing waiting for verification' : 'No documents match'); ?></b>
                <span><?php echo e($statusFilter === 'pending' ? 'New employee uploads will appear here and in your notifications.' : 'Try a different status tab or clear the filters.'); ?></span>
            </div>
            <?php else: ?>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Document</th>
                            <th>Uploaded</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $emp = $doc->employee;
                        $isMine = $emp && (int) $emp->user_id === (int) $myUserId;
                        $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                        ?>
                        <tr id="doc-<?php echo e($doc->id); ?>"
                            class="dv-row <?php echo e((int) $focusId === (int) $doc->id ? 'focus' : ''); ?>">
                            <td class="dv-emp">
                                <b><?php echo e($emp->user->name ?? 'Unknown employee'); ?></b>
                                <span><?php echo e($emp->employee_code ?? '—'); ?><?php if($emp?->department): ?> ·
                                    <?php echo e($emp->department->name); ?><?php endif; ?></span>
                            </td>
                            <td><?php echo e($doc->document_type); ?></td>
                            <td><?php echo e($doc->uploaded_at ? \Illuminate\Support\Carbon::parse($doc->uploaded_at)->format('d M Y, h:i A') : '—'); ?>

                            </td>
                            <td><?php echo e($doc->expiry_date ? \Illuminate\Support\Carbon::parse($doc->expiry_date)->format('d M Y') : '—'); ?>

                            </td>
                            <td>
                                <span
                                    class="pill <?php echo e($pillFor[$doc->status] ?? 'pill-muted'); ?>"><?php echo e(ucfirst($doc->status)); ?></span>
                                <?php if($doc->status !== 'pending' && $doc->verifiedBy): ?>
                                <div class="card-note" style="margin:4px 0 0;font-size:11.5px;">by
                                    <?php echo e($doc->verifiedBy->name); ?></div>
                                <?php endif; ?>
                                <?php if($hasRemarks && $doc->status === 'rejected' && !empty($doc->remarks)): ?>
                                <div class="dv-note">Reason: <?php echo e($doc->remarks); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dv-actions">
                                    <button type="button" class="btn-secondary" data-preview
                                        data-url="<?php echo e(route('hr.verification.preview', $doc)); ?>" data-ext="<?php echo e($ext); ?>"
                                        data-title="<?php echo e($doc->document_type); ?> — <?php echo e($emp->user->name ?? ''); ?>">
                                        <i class="fa-regular fa-eye" style="margin-right:5px;"></i>View
                                    </button>

                                    <?php if($isMine): ?>
                                    <span class="card-note" style="margin:0;">Your own document</span>
                                    <?php else: ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-verification.verify')): ?>
                                    <?php if($doc->status !== 'verified'): ?>
                                    <form method="POST" action="<?php echo e(route('hr.verification.decide', $doc)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="action" value="verified">
                                        <button type="submit" class="approve">Verify</button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($doc->status !== 'rejected'): ?>
                                    <button type="button" class="reject" data-reject
                                        data-action="<?php echo e(route('hr.verification.decide', $doc)); ?>"
                                        data-title="<?php echo e($doc->document_type); ?> — <?php echo e($emp->user->name ?? ''); ?>">Reject</button>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:14px;"><?php echo e($documents->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>


<dialog id="dvPreview" class="dv-dialog">
    <div class="dv-dialog-head">
        <h3 id="dvPreviewTitle">Document</h3>
        <div style="display:flex;gap:14px;align-items:center;">
            <a id="dvPreviewOpen" href="#" target="_blank" rel="noopener" class="btn-ghost">Open in new tab</a>
            <button type="button" class="dv-x" onclick="dvClosePreview()" aria-label="Close">&times;</button>
        </div>
    </div>
    <div class="dv-view" id="dvPreviewBody"></div>
</dialog>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-verification.verify')): ?>
<dialog id="dvReject" class="dv-dialog dv-small">
    <form method="POST" id="dvRejectForm" action="">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="rejected">
        <div class="dv-dialog-head">
            <h3 id="dvRejectTitle">Reject document</h3>
            <button type="button" class="dv-x" onclick="document.getElementById('dvReject').close()"
                aria-label="Close">&times;</button>
        </div>
        <div class="dv-reject-body">
            <label for="dvRemarks" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Reason (shown
                to the employee)</label>
            <textarea id="dvRemarks" name="remarks" maxlength="200" required
                placeholder="e.g. Image is blurred — please upload a clear copy of both sides."></textarea>
        </div>
        <div class="dv-reject-foot">
            <button type="button" class="btn-secondary"
                onclick="document.getElementById('dvReject').close()">Cancel</button>
            <button type="submit" class="btn-primary" style="background:var(--bad);border-color:var(--bad);">Reject
                document</button>
        </div>
    </form>
</dialog>
<?php endif; ?>

<script>
(function() {
    const dlg = document.getElementById('dvPreview');
    const body = document.getElementById('dvPreviewBody');

    function openPreview(url, ext, title) {
        body.innerHTML = '';
        const el = document.createElement(ext === 'pdf' ? 'iframe' : 'img');
        el.src = url;
        if (ext !== 'pdf') el.alt = title;
        body.appendChild(el);
        document.getElementById('dvPreviewTitle').textContent = title;
        document.getElementById('dvPreviewOpen').href = url;
        dlg.showModal();
    }

    window.dvClosePreview = function() {
        dlg.close();
    };
    dlg.addEventListener('close', () => {
        body.innerHTML = '';
    });

    document.querySelectorAll('[data-preview]').forEach(btn => {
        btn.addEventListener('click', () => openPreview(btn.dataset.url, btn.dataset.ext, btn.dataset
            .title));
    });

    const rejectDlg = document.getElementById('dvReject');
    document.querySelectorAll('[data-reject]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('dvRejectForm').action = btn.dataset.action;
            document.getElementById('dvRejectTitle').textContent = 'Reject — ' + btn.dataset.title;
            document.getElementById('dvRemarks').value = '';
            rejectDlg.showModal();
        });
    });

    // Arrived from a notification: bring the document into view.
    const focused = document.querySelector('.dv-row.focus');
    if (focused) {
        focused.scrollIntoView({
            block: 'center'
        });
        const view = focused.querySelector('[data-preview]');
        if (view) view.click();
    }
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/document-verification.blade.php ENDPATH**/ ?>