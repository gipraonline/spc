
<?php $showWorkflow = ($isCoo ?? false) || ($isMd ?? false) || ($isFinance ?? false); ?>
<?php if($showWorkflow): ?>
<div class="table-card" style="margin-bottom:18px;">
    <div class="tc-head">
        <h3><span class="wh-ico"><i class="fa-solid fa-list-check"></i></span>
            <?php if($isFinance): ?> Payroll payments <?php else: ?> Payroll approvals <?php endif; ?>
        </h3>
        <span class="pill pill-muted">Recent runs</span>
    </div>
    <div class="tc-body">
        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Employees</th>
                    <th>Gross</th>
                    <th>Net pay</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $workflowRuns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $run): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $stage = $run->approval_stage ?: 'draft'; ?>
                <tr>
                    <td><b><?php echo e($run->monthLabel()); ?></b></td>
                    <td><?php echo e($run->employee_count); ?></td>
                    <td>₹<?php echo e(number_format($run->total_gross,0)); ?></td>
                    <td>₹<?php echo e(number_format($run->total_net,0)); ?></td>
                    <td>
                        <span class="pill <?php echo e($stage === 'completed' ? 'pill-ok' : 'pill-muted'); ?>"><?php echo e($run->stageLabel()); ?></span>
                        <?php if($run->coo_at): ?><span class="field-hint" style="display:block;">COO approved <?php echo e($run->coo_at->format('d M Y')); ?></span><?php endif; ?>
                        <?php if($run->md_at): ?><span class="field-hint" style="display:block;">MD approved <?php echo e($run->md_at->format('d M Y')); ?></span><?php endif; ?>
                        <?php if($stage === 'returned' && $run->workflow_remarks): ?><span class="field-hint" style="display:block;">Returned: <?php echo e($run->workflow_remarks); ?></span><?php endif; ?>
                    </td>
                    <td>
                        <div class="run-actions">
                            <a class="rbtn" href="<?php echo e(route('hr.payroll.register', $run)); ?>"><i class="fa-solid fa-file-excel"></i>Excel</a>

                            <?php if($isCoo && $stage === 'pending_coo'): ?>
                            <form method="POST" action="<?php echo e(route('hr.payroll.coo-approve', $run)); ?>"
                                onsubmit="return confirm('Approve <?php echo e($run->monthLabel()); ?> payroll and send it to the MD?');">
                                <?php echo csrf_field(); ?><button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Approve</button></form>
                            <?php endif; ?>
                            <?php if($isMd && $stage === 'pending_md'): ?>
                            <form method="POST" action="<?php echo e(route('hr.payroll.md-approve', $run)); ?>"
                                onsubmit="return confirm('Approve <?php echo e($run->monthLabel()); ?> payroll and send it to Finance?');">
                                <?php echo csrf_field(); ?><button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Approve</button></form>
                            <?php endif; ?>
                            <?php if(($isCoo && $stage === 'pending_coo') || ($isMd && $stage === 'pending_md')): ?>
                            <form method="POST" action="<?php echo e(route('hr.payroll.return', $run)); ?>" style="display:inline-flex;gap:6px;align-items:center;">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="remarks" maxlength="255" required placeholder="Reason for returning" style="max-width:170px;">
                                <button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-rotate-left"></i>Return to HR</button>
                            </form>
                            <?php endif; ?>

                            <?php if($isFinance && in_array($stage, ['pending_finance','completed'], true)): ?>
                            <a class="rbtn" href="<?php echo e(route('hr.payroll.bank-file', $run)); ?>"><i class="fa-solid fa-building-columns"></i>Bank file</a>
                            <?php endif; ?>
                            <?php if($isFinance && $stage === 'pending_finance'): ?>
                            <form method="POST" action="<?php echo e(route('hr.payroll.paid', $run)); ?>"
                                onsubmit="return confirm('Mark <?php echo e($run->monthLabel()); ?> as paid? Do this after the bank transfer.');">
                                <?php echo csrf_field(); ?><button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Mark paid</button></form>
                            <form method="POST" action="<?php echo e(route('hr.payroll.discard', $run)); ?>"
                                onsubmit="return confirm('Discard <?php echo e($run->monthLabel()); ?> payroll and all its payslips? HR will have to run it again.');">
                                <?php echo csrf_field(); ?><button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-trash-can"></i>Discard</button></form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-list-check"></i></div>
                            <b>No payroll runs yet</b><span>Runs appear here once HR processes a month.</span>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if($isFinance ?? false): ?>
<div class="table-card" style="margin-bottom:18px;">
    <div class="tc-head">
        <h3><span class="wh-ico"><i class="fa-solid fa-inbox"></i></span>Payslip requests</h3>
        <span class="pill pill-muted"><?php echo e($pendingRequests->count()); ?> pending</span>
    </div>
    <div class="tc-body">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Payslip</th>
                    <th>Reason</th>
                    <th>Requested</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="cell-emp">
                        <div class="av"><?php echo e(strtoupper(substr($rq->employee->user->name ?? '?',0,1))); ?></div>
                        <div><b><?php echo e($rq->employee->user->name ?? '—'); ?></b><span><?php echo e($rq->employee->employee_code ?? ''); ?></span></div>
                    </td>
                    <td><?php echo e(optional(optional($rq->payslip)->payrollRun)->monthLabel() ?? '—'); ?></td>
                    <td><?php echo e($rq->reason ?: '—'); ?></td>
                    <td><?php echo e(optional($rq->created_at)->format('d M Y')); ?></td>
                    <td>
                        <div class="run-actions">
                            <form method="POST" action="<?php echo e(route('hr.payroll.payslip.decide', $rq)); ?>">
                                <?php echo csrf_field(); ?><input type="hidden" name="decision" value="approve">
                                <button type="submit" class="rbtn rbtn-ok"><i class="fa-solid fa-circle-check"></i>Make available</button></form>
                            <form method="POST" action="<?php echo e(route('hr.payroll.payslip.decide', $rq)); ?>" style="display:inline-flex;gap:6px;align-items:center;">
                                <?php echo csrf_field(); ?><input type="hidden" name="decision" value="reject">
                                <input type="text" name="remarks" maxlength="255" required placeholder="Reason for rejecting" style="max-width:170px;">
                                <button type="submit" class="rbtn rbtn-bad"><i class="fa-solid fa-xmark"></i>Reject</button></form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-inbox"></i></div>
                            <b>No pending requests</b><span>Employee payslip requests show up here.</span>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="table-card" style="margin-bottom:18px;">
    <div class="tc-head">
        <h3><span class="wh-ico"><i class="fa-regular fa-file-lines"></i></span>Payslip history</h3>
        <span class="pill pill-muted"><?php echo e($financeSlips ? $financeSlips->total() : 0); ?> payslips</span>
    </div>
    <div class="tc-body">
        <table>
            <thead>
                <tr>
                    <th>Cycle</th>
                    <th>Employee</th>
                    <th>Gross</th>
                    <th>Net Pay</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $financeSlips ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($p->payrollRun->monthLabel()); ?></td>
                    <td class="cell-emp">
                        <div class="av"><?php echo e(strtoupper(substr($p->employee->user->name ?? '?',0,1))); ?></div>
                        <div><b><?php echo e($p->employee->user->name ?? '—'); ?></b></div>
                    </td>
                    <td>₹<?php echo e(number_format($p->gross_pay,0)); ?></td>
                    <td><b>₹<?php echo e(number_format($p->net_pay,0)); ?></b></td>
                    <td><a href="<?php echo e(route('hr.payroll.payslip', $p)); ?>" target="_blank" class="btn-ghost">View</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-regular fa-file-lines"></i></div>
                            <b>No payslips generated yet</b>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($financeSlips): ?><?php echo e($financeSlips->links()); ?><?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/payroll-workflow.blade.php ENDPATH**/ ?>