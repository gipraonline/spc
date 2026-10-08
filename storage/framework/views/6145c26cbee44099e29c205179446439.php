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
['label' => 'Last net pay', 'icon' => 'fa-solid fa-money-check-dollar', 'value' => '₹' . number_format($lastCycleNetPay,
0)],
['label' => 'Cycles done', 'icon' => 'fa-solid fa-circle-check', 'value' => $cyclesFinalized],
['label' => 'Cycle', 'icon' => 'fa-regular fa-calendar', 'value' => $currentCycleMonth],
] : [
['label' => 'Payslips', 'icon' => 'fa-regular fa-file-lines', 'value' => $payslips->count()],
['label' => 'Cycle', 'icon' => 'fa-regular fa-calendar', 'value' => $currentCycleMonth],
],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
.salary-dialog {
    max-width: 620px;
    width: 94vw;
    padding: 0;
    overflow: hidden;
}

.salary-dialog form {
    display: flex;
    flex-direction: column;
    max-height: 88vh;
}

.sd-head {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--line-soft);
}

.sd-head h3 {
    margin: 0;
    font-size: 17px;
}

.sd-head p {
    margin: 2px 0 0;
    font-size: 12.5px;
    color: var(--text-muted);
}

.sd-x {
    margin-left: auto;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: 1px solid var(--line);
    background: #fff;
    font-size: 20px;
    line-height: 1;
    color: var(--text-muted);
    cursor: pointer;
}

.sd-x:hover {
    border-color: var(--brand-bright);
    color: var(--brand);
}

.sd-body {
    padding: 20px 24px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sd-sec h4 {
    margin: 0 0 12px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
}

.money {
    position: relative;
}

.money span {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 13.5px;
    pointer-events: none;
}

.money input {
    padding-left: 28px;
}

.sd-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    padding: 12px 16px;
    background: var(--brand-softer);
    border: 1px solid color-mix(in srgb, var(--brand) 22%, white);
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--brand-ink);
}

.sd-total small {
    display: block;
    font-size: 11.5px;
    font-weight: 400;
    color: var(--text-muted);
}

.sd-total b {
    font-size: 20px;
}

.sd-toggles {
    display: grid;
    gap: 10px;
}

.sd-toggle {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 12px 14px;
    border: 1px solid var(--line);
    border-radius: 12px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
}

.sd-toggle input[type=checkbox] {
    width: 18px;
    height: 18px;
    padding: 0;
    margin: 2px 0 0;
    flex: none;
    accent-color: var(--brand);
}

.sd-toggle b {
    display: block;
    font-size: 13.5px;
    font-weight: 600;
}

.sd-toggle span {
    font-size: 12px;
    color: var(--text-muted);
}

.sd-toggle:has(input:checked) {
    border-color: var(--brand-bright);
    background: var(--brand-softer);
}

.sd-foot {
    padding: 16px 24px;
    border-top: 1px solid var(--line-soft);
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background: #fff;
}

.run-steps {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 4px 0 18px;
    font-size: 12.5px;
    color: var(--text-muted);
}

.run-step {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.run-step i {
    font-style: normal;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--line);
    background: #fff;
    font-size: 11.5px;
}

.run-step.on {
    color: var(--brand-ink);
}

.run-step.on i,
.run-step.done i {
    background: var(--brand);
    border-color: var(--brand);
    color: #fff;
}

.run-line {
    flex: 0 0 32px;
    height: 1px;
    background: var(--line);
}

.run-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    padding: 14px 16px;
    margin-bottom: 16px;
    background: var(--brand-softer);
    border: 1px solid color-mix(in srgb, var(--brand) 28%, white);
    border-radius: 14px;
}

.run-bar-text {
    flex: 1 1 220px;
    display: flex;
    flex-direction: column;
    font-size: 13.5px;
    color: var(--brand-ink);
}

.run-bar-text span {
    font-size: 12px;
    color: var(--text-muted);
}

.run-bar input[type=text] {
    flex: 1 1 200px;
    max-width: 280px;
}

.run-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.run-actions form {
    margin: 0;
}

.rbtn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 13px;
    border-radius: 10px;
    border: 1px solid var(--line);
    background: #fff;
    color: var(--text);
    font-family: var(--font-body);
    font-size: 12.5px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: border-color .15s, background .15s, color .15s;
}

.rbtn i {
    font-size: 12px;
    color: var(--text-muted);
}

.rbtn:hover {
    border-color: var(--brand-bright);
    background: var(--brand-softer);
    color: var(--brand-ink);
}

.rbtn:hover i {
    color: var(--brand);
}

.rbtn:focus-visible {
    outline: 2px solid var(--brand-bright);
    outline-offset: 2px;
}

.rbtn-ok {
    background: var(--ok);
    border-color: var(--ok);
    color: #fff;
}

.rbtn-ok i,
.rbtn-ok:hover i {
    color: #fff;
}

.rbtn-ok:hover {
    background: #116B32;
    border-color: #116B32;
    color: #fff;
}

.rbtn-bad {
    border-color: color-mix(in srgb, var(--bad) 35%, white);
    color: var(--bad);
}

.rbtn-bad i {
    color: var(--bad);
}

.rbtn-bad:hover {
    background: var(--bad-soft);
    border-color: var(--bad);
    color: var(--bad);
}

.rbtn-bad:hover i {
    color: var(--bad);
}

.run-warn {
    display: flex;
    gap: 12px;
    padding: 14px 16px;
    margin-bottom: 16px;
    background: var(--warn-soft);
    border-radius: 14px;
    color: #7A3B06;
    font-size: 13px;
}

.run-warn i {
    margin-top: 3px;
}

.run-warn b,
.run-warn span {
    display: block;
}
</style>
<div class="content">
    <?php if($role === 'super_admin'): ?>
    <div class="kpi-row">
        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-ico"><i class="fa-solid fa-users"></i></div>
            </div>
            <div>
                <div class="kpi-label">Active employees</div>
                <div class="kpi-val"><?php echo e($activeEmployeeCount); ?></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-ico"><i class="fa-solid fa-money-check-dollar"></i></div>
            </div>
            <div>
                <div class="kpi-label">Last cycle net pay</div>
                <div class="kpi-val">₹<?php echo e(number_format($lastCycleNetPay, 0)); ?></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-ico"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div>
                <div class="kpi-label">Cycles finalized</div>
                <div class="kpi-val"><?php echo e($cyclesFinalized); ?></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-ico"><i class="fa-regular fa-calendar"></i></div>
            </div>
            <div>
                <div class="kpi-label">Current cycle</div>
                <div class="kpi-val" style="font-size:19px;"><?php echo e($currentCycleMonth); ?></div>
            </div>
        </div>
    </div>

    <div class="tabs">
        <button type="button" class="tab <?php if($activeTab === 'salary'): ?> active <?php endif; ?>" data-tab="salary"
            onclick="payrollTab(this,'salary')">Salary Structure</button>
        <button type="button" class="tab <?php if($activeTab === 'run'): ?> active <?php endif; ?>" data-tab="run"
            onclick="payrollTab(this,'run')">Run Payroll</button>
        <button type="button" class="tab <?php if($activeTab === 'history'): ?> active <?php endif; ?>" data-tab="history"
            onclick="payrollTab(this,'history')">Payslip History</button>
    </div>

    <div class="tabpanel <?php if($activeTab === 'salary'): ?> active <?php endif; ?>" data-tabpanel="salary">
        <div class="table-card">
            <div class="tc-head">
                <h3><span class="wh-ico"><i class="fa-solid fa-file-invoice-dollar"></i></span>Salary structures</h3>
                <span class="pill pill-muted"><?php echo e($activeEmployees->count()); ?> employees</span>
            </div>
            <div class="tc-body">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Gross</th>
                            <th>Basic</th>
                            <th>HRA</th>
                            <th>Allowances</th>
                            <th>Variable</th>
                            <th>Statutory</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $activeEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $s = $e->currentSalaryStructure; ?>
                        <tr>
                            <td class="cell-emp">
                                <div class="av"><?php echo e(strtoupper(substr($e->user->name,0,1))); ?></div>
                                <div><b><?php echo e($e->user->name); ?></b><span><?php echo e($e->employee_code); ?></span></div>
                            </td>
                            <td><?php echo e($s ? '₹'.number_format($s->gross_monthly,0) : '—'); ?></td>
                            <td><?php echo e($s ? '₹'.number_format($s->basic,0) : '—'); ?></td>
                            <td><?php echo e($s ? '₹'.number_format($s->hra,0) : '—'); ?></td>
                            <td><?php echo e($s ? '₹'.number_format($s->other_allowances,0) : '—'); ?></td>
                            <td><?php echo e($s ? '₹'.number_format($s->variable_pay,0) : '—'); ?></td>
                            <td><?php if($s): ?><span
                                    class="pill pill-muted"><?php echo e($s->pf_applicable ? 'PF' : ''); ?><?php echo e($s->esi_applicable ? ' ESI' : ''); ?><?php echo e($s->pt_applicable ? ' PT' : ''); ?></span><?php else: ?>
                                — <?php endif; ?></td>
                            <td><button type="button" class="btn-ghost"
                                    onclick="document.getElementById('salary-dialog-<?php echo e($e->id); ?>').showModal()">Edit</button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-widget">
                                    <div class="ew-ico"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                                    <b>No active employees</b>
                                    <span>Add employees to define salary structures.</span>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php $__currentLoopData = $activeEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $s = $e->currentSalaryStructure; ?>
        <dialog id="salary-dialog-<?php echo e($e->id); ?>" class="app-dialog salary-dialog">
            <form method="POST" action="<?php echo e(route('hr.payroll.salary.update', $e)); ?>" data-salary-form>
                <?php echo csrf_field(); ?>
                <div class="sd-head">
                    <div class="av"><?php echo e(strtoupper(substr($e->user->name,0,1))); ?></div>
                    <div>
                        <h3><?php echo e($e->user->name); ?></h3>
                        <p><?php echo e($s ? 'Edit salary structure' : 'Set up salary structure'); ?> for <?php echo e($e->employee_code); ?>

                        </p>
                    </div>
                    <button type="button" class="sd-x" aria-label="Close"
                        onclick="this.closest('dialog').close()">&times;</button>
                </div>

                <div class="sd-body">
                    <section class="sd-sec">
                        <h4>Monthly earnings</h4>
                        <div class="field-grid">
                            <div class="field"><label>Basic</label>
                                <div class="money"><span>₹</span><input type="number" step="0.01" min="0" name="basic"
                                        value="<?php echo e($s->basic ?? 0); ?>" data-earn required></div>
                            </div>
                            <div class="field"><label>HRA</label>
                                <div class="money"><span>₹</span><input type="number" step="0.01" min="0" name="hra"
                                        value="<?php echo e($s->hra ?? 0); ?>" data-earn required></div>
                            </div>
                            <div class="field"><label>Allowances</label>
                                <div class="money"><span>₹</span><input type="number" step="0.01" min="0"
                                        name="other_allowances" value="<?php echo e($s->other_allowances ?? 0); ?>" data-earn
                                        required></div>
                            </div>
                            <div class="field"><label>Variable pay</label>
                                <div class="money"><span>₹</span><input type="number" step="0.01" min="0"
                                        name="variable_pay" value="<?php echo e($s->variable_pay ?? 0); ?>" data-earn required>
                                </div>
                            </div>
                        </div>
                        <div class="sd-total">
                            <span>Gross monthly<small>Adds up as you type</small></span>
                            <b data-gross>₹<?php echo e(number_format($s->gross_monthly ?? 0, 0)); ?></b>
                        </div>
                    </section>

                    <section class="sd-sec">
                        <h4>Other deductions</h4>
                        <div class="field-grid">
                            <div class="field"><label>Loan or advance recovery</label>
                                <div class="money"><span>₹</span><input type="number" step="0.01" min="0"
                                        name="other_deduction" value="<?php echo e($s->other_deduction ?? 0); ?>"></div><span
                                    class="field-hint">Deducted every month until you change it.</span>
                            </div>
                            <div class="field"><label>Fixed monthly TDS</label>
                                <div class="money"><span>₹</span><input type="number" step="0.01" min="0"
                                        name="tds_monthly_override" value="<?php echo e($s->tds_monthly_override ?? ''); ?>"
                                        placeholder="Auto"></div><span class="field-hint">Leave blank to calculate TDS
                                    automatically.</span>
                            </div>
                        </div>
                    </section>

                    <section class="sd-sec">
                        <h4>Statutory deductions</h4>
                        <div class="sd-toggles">
                            <label class="sd-toggle"><input type="checkbox" name="pf_applicable" value="1" <?php if($s ?
                                    $s->pf_applicable : true): echo 'checked'; endif; ?>><div><b>Provident fund (PF)</b><span>Deduct PF from
                                        salary</span></div></label>
                            <label class="sd-toggle"><input type="checkbox" name="esi_applicable" value="1" <?php if($s
                                    ? $s->esi_applicable : true): echo 'checked'; endif; ?>><div><b>ESI</b><span>Only while gross is under the ESI
                                        limit</span></div></label>
                            <label class="sd-toggle"><input type="checkbox" name="pt_applicable" value="1" <?php if($s ?
                                    $s->pt_applicable : true): echo 'checked'; endif; ?>><div><b>Professional tax</b><span>Deduct professional
                                        tax</span></div></label>
                        </div>
                    </section>

                    <section class="sd-sec">
                        <h4>Starts from</h4>
                        <div class="field" style="max-width:240px;">
                            <input type="date" name="effective_from" value="<?php echo e(now()->toDateString()); ?>"
                                aria-label="Effective from">
                            <span class="field-hint">Payslips already generated stay unchanged.</span>
                        </div>
                    </section>
                </div>

                <div class="sd-foot">
                    <button type="button" class="btn-secondary" onclick="this.closest('dialog').close()">Cancel</button>
                    <button type="submit" class="btn-primary">Save structure</button>
                </div>
            </form>
        </dialog>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="tabpanel <?php if($activeTab === 'run'): ?> active <?php endif; ?>" data-tabpanel="run">
        <div class="card" style="max-width:760px;margin-bottom:18px;">
            <div class="widget-head">
                <div class="wh-ico"><i class="fa-solid fa-play"></i></div>
                <div>
                    <h3>Run payroll</h3>
                    <p>Pick a month and review the calculation first. Nothing is saved until you process it.</p>
                </div>
            </div>
            <div class="run-steps">
                <span class="run-step <?php if(! $preview): ?> on <?php else: ?> done <?php endif; ?>"><i>1</i>Review</span>
                <span class="run-line"></span>
                <span class="run-step <?php if($preview): ?> on <?php endif; ?>"><i>2</i>Process</span>
            </div>
            <form method="GET" action="<?php echo e(route('hr.payroll.index')); ?>">
                <div class="field-grid">
                    <div class="field">
                        <label>Month</label>
                        <select name="preview_month">
                            <?php $__currentLoopData = ['January','February','March','April','May','June','July','August','September','October','November','December']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($i+1); ?>" <?php if(($i+1)==(int) $previewMonth): echo 'selected'; endif; ?>><?php echo e($m); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="field"><label>Year</label><input type="number" name="preview_year"
                            value="<?php echo e($previewYear); ?>"></div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Review payroll</button>
                    <span class="field-hint">Takes you to step 2, where you can process it.</span>
                </div>
            </form>
        </div>

        <?php if($preview): ?>
        <?php $t = $preview['totals']; $pLabel = \DateTime::createFromFormat('!m', $preview['month'])->format('F').'
        '.$preview['year']; ?>
        <div class="table-card" style="margin-bottom:18px;">
            <div class="tc-head">
                <h3><span class="wh-ico"><i class="fa-solid fa-calculator"></i></span>Preview &mdash; <?php echo e($pLabel); ?></h3>
                <span class="pill pill-muted"><?php echo e($t['count']); ?> employees &middot; nothing saved yet</span>
            </div>
            <div class="tc-body">
                <?php if(! empty($preview['skipped'])): ?>
                <div class="run-warn">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <b><?php echo e(count($preview['skipped'])); ?> active
                            <?php echo e(\Illuminate\Support\Str::plural('employee', count($preview['skipped']))); ?> will not be
                            paid</b>
                        <span>They have no salary structure yet. Add one under Salary Structure, then review
                            again.</span>
                        <button type="button" class="btn-ghost"
                            onclick="payrollTab(document.querySelector('[data-tab=salary]'),'salary')">Go to Salary
                            Structure</button>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($preview['already_run']): ?>
                <div class="status-block" style="margin-bottom:16px;">Payroll for <?php echo e($pLabel); ?> already exists
                    (<?php echo e(ucfirst($preview['already_run']->status)); ?>). Discard it below if it has to be redone.</div>
                <?php elseif($t['count'] > 0): ?>
                <form method="POST" action="<?php echo e(route('hr.payroll.run')); ?>" class="run-bar"
                    onsubmit="return confirm('Process payroll for <?php echo e($pLabel); ?>? Payslips will be generated for <?php echo e($t['count']); ?> employees.');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="month" value="<?php echo e($preview['month']); ?>">
                    <input type="hidden" name="year" value="<?php echo e($preview['year']); ?>">
                    <div class="run-bar-text"><b>Step 2: process <?php echo e($pLabel); ?></b><span>Looks right? This creates
                            payslips for <?php echo e($t['count']); ?>

                            <?php echo e(\Illuminate\Support\Str::plural('employee', $t['count'])); ?>.</span></div>
                    <input type="text" name="notes" maxlength="255"
                        placeholder="Note (optional), e.g. includes arrears">
                    <button type="submit" class="btn-primary">Process payroll</button>
                </form>
                <?php endif; ?>

                <div class="stat-tiles"
                    style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr));margin-bottom:16px;">
                    <div class="stat-tile">
                        <div><b>₹<?php echo e(number_format($t['gross'],0)); ?></b><span>Gross pay</span></div>
                    </div>
                    <div class="stat-tile">
                        <div><b>₹<?php echo e(number_format($t['deductions'],0)); ?></b><span>Deductions</span></div>
                    </div>
                    <div class="stat-tile">
                        <div><b>₹<?php echo e(number_format($t['net'],0)); ?></b><span>Net payable</span></div>
                    </div>
                    <div class="stat-tile">
                        <div><b>₹<?php echo e(number_format($t['employer_cost'],0)); ?></b><span>Cost to company</span></div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Paid days</th>
                            <th>Gross</th>
                            <th>PF</th>
                            <th>ESI</th>
                            <th>PT</th>
                            <th>TDS</th>
                            <th>Other</th>
                            <th>Net pay</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $preview['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="cell-emp">
                                <div class="av"><?php echo e(strtoupper(substr($r['employee']->user->name ?? '?',0,1))); ?></div>
                                <div>
                                    <b><?php echo e($r['employee']->user->name ?? '—'); ?></b><span><?php echo e($r['employee']->employee_code); ?></span>
                                </div>
                            </td>
                            <td><?php echo e(rtrim(rtrim(number_format($r['paid_days'],2),'0'),'.')); ?> /
                                <?php echo e($r['days_in_month']); ?><?php if($r['lop_days'] > 0): ?> <span class="pill pill-muted">LOP
                                    <?php echo e(rtrim(rtrim(number_format($r['lop_days'],2),'0'),'.')); ?></span><?php endif; ?></td>
                            <td>₹<?php echo e(number_format($r['gross'],0)); ?><?php if($r['incentive'] > 0): ?><span class="field-hint">
                                    incl. ₹<?php echo e(number_format($r['incentive'],0)); ?> incentive</span><?php endif; ?></td>
                            <td>₹<?php echo e(number_format($r['pf'],0)); ?></td>
                            <td>₹<?php echo e(number_format($r['esi'],0)); ?></td>
                            <td>₹<?php echo e(number_format($r['pt'],2)); ?></td>
                            <td>₹<?php echo e(number_format($r['tds'],0)); ?></td>
                            <td>₹<?php echo e(number_format($r['other'],0)); ?></td>
                            <td><b>₹<?php echo e(number_format($r['net'],0)); ?></b></td>
                            <td><?php if($r['warnings']): ?><span title="<?php echo e(implode(' ', $r['warnings'])); ?>"
                                    style="cursor:help;color:#B45309;"><i
                                        class="fa-solid fa-triangle-exclamation"></i></span><?php endif; ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr style="font-weight:600;">
                            <td>Total</td>
                            <td></td>
                            <td>₹<?php echo e(number_format($t['gross'],0)); ?></td>
                            <td>₹<?php echo e(number_format($t['pf'],0)); ?></td>
                            <td>₹<?php echo e(number_format($t['esi'],0)); ?></td>
                            <td>₹<?php echo e(number_format($t['pt'],2)); ?></td>
                            <td>₹<?php echo e(number_format($t['tds'],0)); ?></td>
                            <td>₹<?php echo e(number_format($t['other'],0)); ?></td>
                            <td>₹<?php echo e(number_format($t['net'],0)); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <?php if($t['warnings'] > 0): ?>
                <div class="status-block" style="margin-top:16px;"><?php echo e($t['warnings']); ?> warning(s) &mdash; hover the <i
                        class="fa-solid fa-triangle-exclamation"></i> icon on a row for details. Missing attendance is
                    treated as present unless you change that in Settings.</div>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

        <div class="table-card">
            <div class="tc-head">
                <h3><span class="wh-ico"><i class="fa-solid fa-list-check"></i></span>Payroll runs</h3>
                <span class="pill pill-muted"><?php echo e($runs->count()); ?>

                    <?php echo e(\Illuminate\Support\Str::plural('run', $runs->count())); ?></span>
            </div>
            <div class="tc-body">
                <table>
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Employees</th>
                            <th>Gross</th>
                            <th>Net pay</th>
                            <th>Cost to company</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $runs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $run): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><b><?php echo e($run->monthLabel()); ?></b></td>
                            <td><?php echo e($run->employee_count ?: $run->payslips()->count()); ?></td>
                            <td>₹<?php echo e(number_format($run->total_gross,0)); ?></td>
                            <td>₹<?php echo e(number_format($run->total_net,0)); ?></td>
                            <td>₹<?php echo e(number_format($run->total_employer_cost,0)); ?></td>
                            <td><span
                                    class="pill <?php echo e($run->status === 'paid' ? 'pill-ok' : 'pill-muted'); ?>"><?php echo e(ucfirst($run->status)); ?></span>
                            </td>
                            <td>
                                <div class="run-actions">
                                    <a class="rbtn" href="<?php echo e(route('hr.payroll.index', ['run' => $run->id])); ?>"><i
                                            class="fa-regular fa-file-lines"></i>Payslips</a>
                                    <a class="rbtn" href="<?php echo e(route('hr.payroll.register', $run)); ?>"><i
                                            class="fa-solid fa-file-excel"></i>Excel</a>
                                    <a class="rbtn" href="<?php echo e(route('hr.payroll.bank-file', $run)); ?>"><i
                                            class="fa-solid fa-building-columns"></i>Bank file</a>
                                    <?php if($run->status === 'processed'): ?>
                                    <form method="POST" action="<?php echo e(route('hr.payroll.paid', $run)); ?>"
                                        onsubmit="return confirm('Mark <?php echo e($run->monthLabel()); ?> as paid? Do this after the bank transfer.');">
                                        <?php echo csrf_field(); ?><button type="submit" class="rbtn rbtn-ok"><i
                                                class="fa-solid fa-circle-check"></i>Mark paid</button></form>
                                    <form method="POST" action="<?php echo e(route('hr.payroll.discard', $run)); ?>"
                                        onsubmit="return confirm('Discard <?php echo e($run->monthLabel()); ?> payroll and all its payslips? You can run it again afterwards.');">
                                        <?php echo csrf_field(); ?><button type="submit" class="rbtn rbtn-bad"><i
                                                class="fa-solid fa-trash-can"></i>Discard</button></form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-widget">
                                    <div class="ew-ico"><i class="fa-solid fa-list-check"></i></div><b>No payroll runs
                                        yet</b><span>Review a month above, then process it.</span>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tabpanel <?php if($activeTab === 'history'): ?> active <?php endif; ?>" data-tabpanel="history">
        <div class="table-card">
            <div class="tc-head">
                <h3><span class="wh-ico"><i class="fa-regular fa-file-lines"></i></span>Payslip history</h3>
                <span
                    class="pill pill-muted"><?php echo e($filterRun ? 'Filtered to one run' : $totalPayslips.' payslips'); ?></span><?php if($filterRun): ?>
                <a class="btn-ghost" href="<?php echo e(route('hr.payroll.index', ['tab' => 'history'])); ?>">Show all</a><?php endif; ?>
            </div>
            <div class="tc-body">
                <table>
                    <thead>
                        <tr>
                            <th>Cycle</th>
                            <th>Employee</th>
                            <th>Gross</th>
                            <th>Deductions</th>
                            <th>Net Pay</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $allPayslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($p->payrollRun->monthLabel()); ?></td>
                            <td class="cell-emp">
                                <div class="av"><?php echo e(strtoupper(substr($p->employee->user->name ?? '?',0,1))); ?></div>
                                <div><b><?php echo e($p->employee->user->name ?? '—'); ?></b></div>
                            </td>
                            <td>₹<?php echo e(number_format($p->gross_pay,0)); ?></td>
                            <td>₹<?php echo e(number_format($p->pf_deduction + $p->esi_deduction + $p->professional_tax + $p->tds_deduction + $p->other_deductions,0)); ?>

                            </td>
                            <td><b>₹<?php echo e(number_format($p->net_pay,0)); ?></b></td>
                            <td><a href="<?php echo e(route('hr.payroll.payslip', $p)); ?>" target="_blank"
                                    class="btn-ghost">View</a></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-widget">
                                    <div class="ew-ico"><i class="fa-regular fa-file-lines"></i></div>
                                    <b>No payslips generated yet</b>
                                    <span>Run your first payroll cycle to generate payslips.</span>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php echo e($allPayslips->links()); ?>

            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('[data-salary-form]').forEach(function(f) {
        const out = f.querySelector('[data-gross]');
        const calc = function() {
            let t = 0;
            f.querySelectorAll('[data-earn]').forEach(function(i) {
                t += parseFloat(i.value) || 0;
            });
            out.textContent = '₹' + t.toLocaleString('en-IN', {
                maximumFractionDigits: 0
            });
        };
        f.addEventListener('input', calc);
        calc();
    });

    function payrollTab(btn, name) {
        const scope = document.querySelector('.content');
        scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
        scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        scope.querySelectorAll('[data-tabpanel="' + name + '"]').forEach(p => p.classList.add('active'));
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
                    <div class="field"><label>Basic</label><input type="number" step="0.01" name="basic"
                            value="<?php echo e($salaryStructure->basic); ?>" <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                    <div class="field"><label>HRA</label><input type="number" step="0.01" name="hra"
                            value="<?php echo e($salaryStructure->hra); ?>" <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                    <div class="field"><label>Other allowances</label><input type="number" step="0.01"
                            name="other_allowances" value="<?php echo e($salaryStructure->other_allowances); ?>"
                            <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                    <div class="field"><label>Variable pay</label><input type="number" step="0.01" name="variable_pay"
                            value="<?php echo e($salaryStructure->variable_pay); ?>" <?php if(!$canEditSalary): echo 'disabled'; endif; ?>></div>
                </div>
                <div class="stat-tiles" style="grid-template-columns:1fr;margin-top:18px;margin-bottom:0;">
                    <div class="stat-tile">
                        <div class="st-ico"><i class="fa-solid fa-sack-dollar"></i></div>
                        <div><b>₹<?php echo e(number_format($salaryStructure->gross_monthly,0)); ?></b><span>Gross monthly</span>
                        </div>
                    </div>
                </div>
                <?php if($canEditSalary): ?>
                <div class="form-actions"><button type="submit" class="btn-primary">Save structure</button></div>
                <?php else: ?>
                <p class="field-hint" style="margin-top:14px;">Read-only &mdash; salary structure changes are Super
                    Admin only.</p>
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
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Net pay</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payslips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><b><?php echo e($p->payrollRun->monthLabel()); ?></b></td>
                                <td>₹<?php echo e(number_format($p->net_pay,0)); ?></td>
                                <td><a href="<?php echo e(route('hr.payroll.payslip', $p)); ?>" target="_blank"
                                        class="btn-ghost">View / print</a></td>
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
                    <h3><span class="wh-ico"><i class="fa-solid fa-sitemap"></i></span><?php echo e($latestRun->monthLabel()); ?>

                        payroll run</h3>
                    <span class="pill pill-ok"><?php echo e(ucfirst($latestRun->status)); ?></span>
                </div>
                <div class="tc-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Employees</th>
                                <th>Gross</th>
                            </tr>
                        </thead>
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