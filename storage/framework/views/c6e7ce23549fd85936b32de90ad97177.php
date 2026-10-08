<?php $__env->startSection('content'); ?>
<style>
.al-page { padding: 8px 4px 40px; font-family: 'Outfit', sans-serif; color: #22352C; }
.al-page h2 { font-size: 22px; font-weight: 600; margin: 0 0 4px; color: #1F3D14; }
.al-sub { color: #61756B; font-size: 13px; margin-bottom: 18px; }
.al-card { background: #fff; border: 1px solid rgba(18,58,40,.13); border-radius: 14px; padding: 18px; margin-bottom: 18px; box-shadow: 0 1px 2px rgba(10,61,44,.05); }
.al-card h3 { font-size: 15px; font-weight: 600; margin: 0 0 12px; }
.al-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 12px; }
.al-grid label { display: block; font-size: 12px; font-weight: 500; margin-bottom: 4px; color: #61756B; }
.al-grid input, .al-grid select, .al-grid textarea { width: 100%; border: 1px solid rgba(18,58,40,.2); border-radius: 9px; padding: 8px 10px; font-size: 13px; }
.al-btn { border: 1px solid #1F5C2E; background: linear-gradient(135deg, #5E8D3D, #1F5C2E); color: #fff; border-radius: 9px; padding: 8px 16px; font-size: 12.5px; font-weight: 500; cursor: pointer; }
.al-btn.ghost { background: #fff; color: #1F5C2E; }
.al-btn.bad { background: #C0392B; border-color: #C0392B; }
.al-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.al-table th { text-align: left; font-size: 11.5px; text-transform: uppercase; letter-spacing: .04em; color: #61756B; padding: 8px 10px; border-bottom: 1px solid rgba(18,58,40,.13); }
.al-table td { padding: 10px; border-bottom: 1px solid rgba(18,58,40,.07); vertical-align: top; }
.al-pill { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 600; }
.al-pill.pending { background: #FCF0D8; color: #7A5B00; }
.al-pill.approved { background: #DCF3E4; color: #1F5C2E; }
.al-pill.rejected { background: #FBE7E4; color: #A12B1F; }
.al-pill.cancelled { background: #ECEFEE; color: #61756B; }
.al-alert { border-radius: 10px; padding: 10px 14px; font-size: 13px; margin-bottom: 14px; }
.al-alert.ok { background: #DCF3E4; color: #1F5C2E; }
.al-alert.err { background: #FBE7E4; color: #A12B1F; }
.al-muted { color: #61756B; }
.al-row-actions { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
.al-row-actions input[type=text] { border: 1px solid rgba(18,58,40,.2); border-radius: 8px; padding: 5px 8px; font-size: 12px; width: 130px; }
</style>

<div class="al-page">
    <h2>Leave Requests</h2>
    <div class="al-sub">
        <?php if($isAssociate): ?> Apply for leave and track approval. <?php else: ?> Approve or reject leave requests from associates (Farm Care Advisers / Tele Callers). <?php endif; ?>
    </div>

    <?php if(session('success')): ?> <div class="al-alert ok"><?php echo e(session('success')); ?></div> <?php endif; ?>
    <?php if(session('error')): ?> <div class="al-alert err"><?php echo e(session('error')); ?></div> <?php endif; ?>
    <?php if($errors->any()): ?> <div class="al-alert err"><?php echo e($errors->first()); ?></div> <?php endif; ?>

    <?php if($isAssociate): ?>
    <div class="al-card">
        <h3>Apply for leave</h3>
        <form method="POST" action="<?php echo e(route('admin.associate-leave.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="al-grid">
                <div>
                    <label>Leave type</label>
                    <select name="leave_type" required>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('leave_type') === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label>From</label>
                    <input type="date" name="start_date" value="<?php echo e(old('start_date')); ?>" min="<?php echo e(now()->toDateString()); ?>" required>
                </div>
                <div>
                    <label>To</label>
                    <input type="date" name="end_date" value="<?php echo e(old('end_date')); ?>" min="<?php echo e(now()->toDateString()); ?>" required>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label>Reason (optional)</label>
                    <textarea name="reason" rows="2" maxlength="500"><?php echo e(old('reason')); ?></textarea>
                </div>
            </div>
            <div style="margin-top:12px;"><button type="submit" class="al-btn">Send request</button></div>
        </form>
    </div>

    <div class="al-card">
        <h3>My requests</h3>
        <?php if($mine->isEmpty()): ?>
            <div class="al-muted">No leave requests yet.</div>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table class="al-table">
            <thead><tr><th>Type</th><th>Dates</th><th>Days</th><th>Status</th><th>Decision</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $mine; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($r->leave_type); ?></td>
                    <td><?php echo e($r->start_date->format('d M Y')); ?> – <?php echo e($r->end_date->format('d M Y')); ?></td>
                    <td><?php echo e($r->days); ?></td>
                    <td><span class="al-pill <?php echo e($r->status); ?>"><?php echo e(ucfirst($r->status)); ?></span></td>
                    <td class="al-muted">
                        <?php if($r->decided_by_name): ?> <?php echo e($r->decided_by_name); ?>, <?php echo e($r->decided_at?->format('d M')); ?> <?php endif; ?>
                        <?php if($r->decision_remark): ?><br><?php echo e($r->decision_remark); ?><?php endif; ?>
                    </td>
                    <td>
                        <?php if($r->status === 'pending'): ?>
                        <form method="POST" action="<?php echo e(route('admin.associate-leave.cancel', $r->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="al-btn ghost" onclick="return confirm('Cancel this request?')">Cancel</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="al-card">
        <h3>Requests to review</h3>
        <?php if($approvals->isEmpty()): ?>
            <div class="al-muted">No leave requests from associates reporting to you.</div>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table class="al-table">
            <thead><tr><th>Associate</th><th>Type</th><th>Dates</th><th>Days</th><th>Reason</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $approvals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <b><?php echo e($r->employee?->c_employee_name ?? '—'); ?></b><br>
                        <span class="al-muted"><?php echo e($r->employee?->c_employee_code); ?> · <?php echo e($r->employee?->designation?->c_designation); ?></span>
                    </td>
                    <td><?php echo e($r->leave_type); ?></td>
                    <td><?php echo e($r->start_date->format('d M Y')); ?> – <?php echo e($r->end_date->format('d M Y')); ?></td>
                    <td><?php echo e($r->days); ?></td>
                    <td class="al-muted"><?php echo e($r->reason ?: '—'); ?></td>
                    <td>
                        <span class="al-pill <?php echo e($r->status); ?>"><?php echo e(ucfirst($r->status)); ?></span>
                        <?php if($r->decided_by_name): ?><div class="al-muted" style="font-size:11.5px;margin-top:4px;"><?php echo e($r->decided_by_name); ?></div><?php endif; ?>
                    </td>
                    <td>
                        <?php if($r->status === 'pending'): ?>
                        <form method="POST" action="<?php echo e(route('admin.associate-leave.decide', $r->id)); ?>" class="al-row-actions">
                            <?php echo csrf_field(); ?>
                            <input type="text" name="remark" placeholder="Remark (optional)" maxlength="500">
                            <button type="submit" name="decision" value="approved" class="al-btn">Approve</button>
                            <button type="submit" name="decision" value="rejected" class="al-btn bad">Reject</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/associate-leave/index.blade.php ENDPATH**/ ?>