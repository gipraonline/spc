<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php $currentCycleMonth = $currentCycleMonth ?? now()->format('F'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Money',
        'heroIcon' => 'fa-solid fa-indian-rupee-sign',
        'heroSummary' => 'Salary structures, monthly payroll runs and payslip access.',
        'heroStats' => $role === 'super_admin' ? [
            ['label' => 'Employees', 'icon' => 'fa-solid fa-users', 'value' => $activeEmployeeCount],
            ['label' => 'Last net pay', 'icon' => 'fa-solid fa-money-check-dollar', 'value' => '₹' . number_format($lastCycleNetPay, 0)],
            ['label' => 'Cycles done', 'icon' => 'fa-solid fa-circle-check', 'value' => $cyclesFinalized],
            ['label' => 'Cycle', 'icon' => 'fa-regular fa-calendar', 'value' => $currentCycleMonth],
        ] : [
            ['label' => 'Payslips', 'icon' => 'fa-regular fa-file-lines', 'value' => $payslips->count()],
            ['label' => 'Cycle', 'icon' => 'fa-regular fa-calendar', 'value' => $currentCycleMonth],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
    <?php if($role === 'super_admin'): ?>
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-top"><div class="kpi-ico"><i class="fa-solid fa-users"></i></div></div>
                <div>
                    <div class="kpi-label">Active employees</div>
                    <div class="kpi-val"><?php echo e($activeEmployeeCount); ?></div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><div class="kpi-ico"><i class="fa-solid fa-money-check-dollar"></i></div></div>
                <div>
                    <div class="kpi-label">Last cycle net pay</div>
                    <div class="kpi-val">₹<?php echo e(number_format($lastCycleNetPay, 0)); ?></div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><div class="kpi-ico"><i class="fa-solid fa-circle-check"></i></div></div>
                <div>
                    <div class="kpi-label">Cycles finalized</div>
                    <div class="kpi-val"><?php echo e($cyclesFinalized); ?></div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-top"><div class="kpi-ico"><i class="fa-regular fa-calendar"></i></div></div>
                <div>
                    <div class="kpi-label">Current cycle</div>
                    <div class="kpi-val" style="font-size:19px;"><?php echo e($currentCycleMonth); ?></div>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button type="button" class="tab active" data-tab="salary" onclick="payrollTab(this,'salary')">Salary Structure</button>
            <button type="button" class="tab" data-tab="run" onclick="payrollTab(this,'run')">Run Payroll</button>
            <button type="button" class="tab" data-tab="history" onclick="payrollTab(this,'history')">Payslip History</button>
        </div>

        <div class="tabpanel active" data-tabpanel="salary">
            <div class="table-card">
                <div class="tc-head">
                    <h3><span class="wh-ico"><i class="fa-solid fa-file-invoice-dollar"></i></span>Salary structures</h3>
                    <span class="pill pill-muted"><?php echo e($activeEmployees->count()); ?> employees</span>
                </div>
                <div class="tc-body">
                    <table>
                        <thead><tr><th>Employee</th><th>Gross</th><th>Basic</th><th>HRA</th><th>Allowances</th><th>PF</th><th></th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $activeEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $s = $e->currentSalaryStructure; ?>
                            <tr>
                                <td class="cell-emp"><div class="av"><?php echo e(strtoupper(substr($e->user->name,0,1))); ?></div><div><b><?php echo e($e->user->name); ?></b><span><?php echo e($e->employee_code); ?></span></div></td>
                                <td><?php echo e($s ? '₹'.number_format($s->gross_monthly,0) : '—'); ?></td>
                                <td><?php echo e($s ? '₹'.number_format($s->basic,0) : '—'); ?></td>
                                <td><?php echo e($s ? '₹'.number_format($s->hra,0) : '—'); ?></td>
                                <td><?php echo e($s ? '₹'.number_format($s->other_allowances,0) : '—'); ?></td>
                                <td><?php echo e($s ? '₹'.number_format($s->basic * 0.12,0) : '—'); ?></td>
                                <td><button type="button" class="btn-ghost" onclick="document.getElementById('salary-dialog-<?php echo e($e->id); ?>').showModal()">Edit</button></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7">
                                <div class="empty-widget">
                                    <div class="ew-ico"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                                    <b>No active employees</b>
                                    <span>Add employees to define salary structures.</span>
                                </div>
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php $__currentLoopData = $activeEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $s = $e->currentSalaryStructure; ?>
                <dialog id="salary-dialog-<?php echo e($e->id); ?>" class="app-dialog">
                    <form method="POST" action="<?php echo e(route('hr.payroll.salary.update', $e)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="dialog-head">
                            <div><h3 style="margin:0;">Edit salary structure</h3><p class="card-note" style="margin:2px 0 0;"><?php echo e($e->user->name); ?></p></div>
                            <button type="button" class="btn-ghost" onclick="this.closest('dialog').close()">&times;</button>
                        </div>
                        <div class="status-block" style="margin-bottom:16px;">Changes take effect from a new effective date; historical payslips remain unchanged.</div>
                        <div class="field-grid">
                            <div class="field"><label>Gross monthly</label><input type="number" step="0.01" value="<?php echo e($s->gross_monthly ?? 0); ?>" disabled></div>
                            <div class="field"><label>Basic</label><input type="number" step="0.01" name="basic" value="<?php echo e($s->basic ?? 0); ?>" required></div>
                            <div class="field"><label>HRA</label><input type="number" step="0.01" name="hra" value="<?php echo e($s->hra ?? 0); ?>" required></div>
                            <div class="field"><label>Allowances</label><input type="number" step="0.01" name="other_allowances" value="<?php echo e($s->other_allowances ?? 0); ?>" required></div>
                            <div class="field"><label>Variable pay</label><input type="number" step="0.01" name="variable_pay" value="<?php echo e($s->variable_pay ?? 0); ?>" required></div>
                            <div class="field"><label>Effective from</label><input type="date" name="effective_from" value="<?php echo e(now()->toDateString()); ?>"></div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn-secondary" onclick="this.closest('dialog').close()">Cancel</button>
                            <button type="submit" class="btn-primary">Save structure</button>
                        </div>
                    </form>
                </dialog>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="tabpanel" data-tabpanel="run">
            <div class="card" style="max-width:540px;">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-play"></i></div>
                    <div>
                        <h3>Run payroll</h3>
                        <p>Generates payslips for all <?php echo e($activeEmployeeCount); ?> active employees.</p>
                    </div>
                </div>
                <div class="status-block" style="margin-bottom:20px;">Running payroll locks attendance &amp; leave inputs for the period and generates payslips for all active employees. This action is logged.</div>
                <form method="POST" action="<?php echo e(route('hr.payroll.run')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="field-grid">
                        <div class="field">
                            <label>Month</label>
                            <select name="month">
                                <?php $__currentLoopData = ['January','February','March','April','May','June','July','August','September','October','November','December']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($i+1); ?>" <?php if(($i+1) == now()->month): echo 'selected'; endif; ?>><?php echo e($m); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="field"><label>Year</label><input type="number" name="year" value="<?php echo e(now()->year); ?>"></div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Run payroll for <?php echo e($activeEmployeeCount); ?> employees</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="tabpanel" data-tabpanel="history">
            <div class="table-card">
                <div class="tc-head">
                    <h3><span class="wh-ico"><i class="fa-regular fa-file-lines"></i></span>Payslip history</h3>
                    <span class="pill pill-muted"><?php echo e($totalPayslips); ?> payslips</span>
                </div>
                <div class="tc-body">
                    <table>
                        <thead><tr><th>Cycle</th><th>Employee</th><th>Gross</th><th>Deductions</th><th>Net Pay</th><th></th></tr></thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $allPayslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($p->payrollRun->monthLabel()); ?></td>
                                <td class="cell-emp"><div class="av"><?php echo e(strtoupper(substr($p->employee->user->name ?? '?',0,1))); ?></div><div><b><?php echo e($p->employee->user->name ?? '—'); ?></b></div></td>
                                <td>₹<?php echo e(number_format($p->gross_pay,0)); ?></td>
                                <td>₹<?php echo e(number_format($p->pf_deduction + $p->esi_deduction + $p->professional_tax + $p->tds_deduction + $p->other_deductions,0)); ?></td>
                                <td><b>₹<?php echo e(number_format($p->net_pay,0)); ?></b></td>
                                <td><a href="<?php echo e(route('hr.payroll.payslip', $p)); ?>" target="_blank" class="btn-ghost">View</a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6">
                                <div class="empty-widget">
                                    <div class="ew-ico"><i class="fa-regular fa-file-lines"></i></div>
                                    <b>No payslips generated yet</b>
                                    <span>Run your first payroll cycle to generate payslips.</span>
                                </div>
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <?php echo e($allPayslips->links()); ?>

                </div>
            </div>
        </div>

        <script>
        function payrollTab(btn, name){
            const scope = document.querySelector('.content');
            scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
            scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            scope.querySelectorAll('[data-tabpanel="'+name+'"]').forEach(p => p.classList.add('active'));
        }
        </script>
    <?php else: ?>

        <div class="grid-2">
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    <div>
                        <h3>Salary structure</h3>
                        <p>Fixed, variable pay &mdash; per employee.</p>
                    </div>
                </div>
                <?php if($viewedEmployee && $salaryStructure): ?>
                    <form method="POST" action="<?php echo e(route('hr.payroll.salary.update', $viewedEmployee)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php $canEditSalary = $role === 'super_admin'; ?>
                        <div class="field-grid">
                            <div class="field"><label>Basic</label><input type="number" step="0.01" name="basic" value="<?php echo e($salaryStructure->basic); ?>" <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                            <div class="field"><label>HRA</label><input type="number" step="0.01" name="hra" value="<?php echo e($salaryStructure->hra); ?>" <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                            <div class="field"><label>Other allowances</label><input type="number" step="0.01" name="other_allowances" value="<?php echo e($salaryStructure->other_allowances); ?>" <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                            <div class="field"><label>Variable pay</label><input type="number" step="0.01" name="variable_pay" value="<?php echo e($salaryStructure->variable_pay); ?>" <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                        </div>
                        <div class="stat-tiles" style="grid-template-columns:1fr;margin-top:18px;margin-bottom:0;">
                            <div class="stat-tile"><div class="st-ico"><i class="fa-solid fa-sack-dollar"></i></div><div><b>₹<?php echo e(number_format($salaryStructure->gross_monthly,0)); ?></b><span>Gross monthly</span></div></div>
                        </div>
                        <?php if($canEditSalary): ?>
                            <div class="form-actions"><button type="submit" class="btn-primary">Save structure</button></div>
                        <?php else: ?>
                            <p class="field-hint" style="margin-top:14px;">Read-only &mdash; salary structure changes are Super Admin only.</p>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <div class="empty-widget">
                        <div class="ew-ico"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        <b>No salary structure on file yet</b>
                        <span>HR will set up your structure after onboarding.</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="stack">
                <div class="table-card">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-regular fa-file-lines"></i></span>Payslips</h3>
                        <span class="pill pill-muted"><?php echo e($payslips->count()); ?> total</span>
                    </div>
                    <div class="tc-body">
                        <?php if($payslips->isEmpty()): ?>
                            <div class="empty-widget">
                                <div class="ew-ico"><i class="fa-regular fa-file-lines"></i></div>
                                <b>No payslips yet</b>
                                <span>Your payslips appear after the first payroll run.</span>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead><tr><th>Month</th><th>Net pay</th><th></th></tr></thead>
                                <tbody>
                                    <?php $__currentLoopData = $payslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><b><?php echo e($p->payrollRun->monthLabel()); ?></b></td>
                                            <td>₹<?php echo e(number_format($p->net_pay,0)); ?></td>
                                            <td><a href="<?php echo e(route('hr.payroll.payslip', $p)); ?>" target="_blank" class="btn-ghost">View / print</a></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if(($role === 'hr_admin' || $role === 'super_admin') && $runsByDepartment->isNotEmpty()): ?>
                    <div class="table-card">
                        <div class="tc-head">
                            <h3><span class="wh-ico"><i class="fa-solid fa-sitemap"></i></span><?php echo e($latestRun->monthLabel()); ?> payroll run</h3>
                            <span class="pill pill-ok"><?php echo e(ucfirst($latestRun->status)); ?></span>
                        </div>
                        <div class="tc-body">
                            <table>
                                <thead><tr><th>Department</th><th>Employees</th><th>Gross</th></tr></thead>
                                <tbody>
                                    <?php $__currentLoopData = $runsByDepartment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($d->department); ?></td>
                                            <td><?php echo e($d->headcount); ?></td>
                                            <td>₹<?php echo e(number_format($d->gross,0)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>


    <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/payroll.blade.php ENDPATH**/ ?>