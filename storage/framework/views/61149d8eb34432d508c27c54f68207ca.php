<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'System',
        'heroIcon' => 'fa-solid fa-gear',
        'heroSummary' => 'Company profile and policy configuration — every change is audited.',
        'heroStats' => [
            [
                'label' => 'Settings',
                'icon' => 'fa-solid fa-sliders',
                'value' => count($settings),
            ],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">

        
        <?php
            $canEdit = false;
            $S = $settings->keyBy('key');
            $val = fn ($k) => (string) old($k, $S[$k]['value'] ?? '');
            $flag = function ($k) use ($val) { $v = trim($val($k)); return $v === '' ? true : $v === '1'; };
            $offDays = collect(explode(',', $val('weekly_off_days')))->map(fn ($d) => trim($d))->filter(fn ($d) => $d !== '')->all();
            $attMissing = trim($val('missing_attendance_as')) ?: 'present';
            $ptMode = trim($val('pt_deduction_mode')) ?: 'monthly';
            $slabs = json_decode($val('pt_slabs'), true);
            $slabs = is_array($slabs) ? array_values(array_filter($slabs, fn ($r) => is_array($r) && count($r) === 2)) : [];
            if (! $slabs) { $slabs = [[0, 0]]; }
            $monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hr-settings.edit')): ?> <?php $canEdit = true; ?> <?php endif; ?>

        <style>
            .set-wrap{max-width:900px;}
            .set-nav{position:sticky;top:68px;z-index:40;display:flex;gap:8px;margin:0 -34px 18px;padding:10px 34px;
                background:color-mix(in srgb, var(--paper) 90%, transparent);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
                border-bottom:1px solid var(--line-soft);overflow-x:auto;scrollbar-width:none;}
            .set-nav::-webkit-scrollbar{display:none;}
            .set-nav a{flex:none;padding:7px 14px;border:1px solid var(--line);border-radius:999px;background:#fff;color:var(--text);font-size:13px;font-weight:500;text-decoration:none;transition:border-color .15s,background .15s,color .15s;}
            .set-nav a:hover{border-color:var(--brand-bright);background:var(--brand-softer);color:var(--brand-ink);}
            .set-nav a.on{background:var(--brand);border-color:var(--brand);color:#fff;}
            @media (max-width:900px){.set-nav{top:62px;margin:0 -14px 14px;padding:10px 14px;}}
            .set-sec{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);margin-bottom:18px;scroll-margin-top:140px;}
            .set-sec-head{display:flex;align-items:center;gap:14px;padding:18px 22px;border-bottom:1px solid var(--line-soft);}
            .set-sec-head .wh-ico{flex:none;}
            .set-sec-head h3{margin:0;font-size:16px;}
            .set-sec-head p{margin:2px 0 0;font-size:12.5px;color:var(--text-muted);}
            .set-sec-head .switch{margin-left:auto;}
            .set-body{padding:4px 22px 8px;}
            .set-row{display:grid;grid-template-columns:minmax(0,1fr) 260px;gap:8px 28px;align-items:center;padding:16px 0;border-bottom:1px solid var(--line-soft);}
            .set-row:last-child{border-bottom:none;}
            .set-lbl label,.set-lbl b{display:block;font-size:13.5px;font-weight:600;color:var(--text);}
            .set-lbl span{display:block;margin-top:3px;font-size:12.5px;line-height:1.5;color:var(--text-muted);max-width:52ch;}
            .set-ctl{display:flex;flex-direction:column;gap:6px;align-items:flex-end;}
            .set-ctl > *{width:100%;}
            .set-ctl.wide{grid-column:1 / -1;align-items:stretch;}
            .set-row.stack{grid-template-columns:1fr;}
            .set-row.stack .set-ctl{align-items:stretch;}
            .affix{position:relative;}
            .affix em{position:absolute;top:50%;transform:translateY(-50%);font-style:normal;font-size:13px;color:var(--text-muted);pointer-events:none;}
            .affix.suf em{right:14px;} .affix.suf input{padding-right:50px;}
            .affix.pre em{left:14px;} .affix.pre input{padding-left:30px;}
            .set-ctl small{font-size:12px;}
            .dep{transition:opacity .15s;}
            .set-sec:has(.sw-master:not(:checked)) .dep{opacity:.42;}

            .switch{display:inline-flex;align-items:center;gap:10px;cursor:pointer;width:auto !important;}
            .switch input{position:absolute;opacity:0;width:0;height:0;padding:0;}
            .switch i{position:relative;flex:none;width:42px;height:24px;border-radius:999px;background:#C9D5CE;transition:background .15s;}
            .switch i::after{content:"";position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.25);transition:transform .15s;}
            .switch input:checked + i{background:var(--brand);}
            .switch input:checked + i::after{transform:translateX(18px);}
            .switch input:focus-visible + i{outline:2px solid var(--brand-bright);outline-offset:2px;}
            .switch b{font-size:13px;font-weight:600;min-width:24px;color:var(--text-muted);}
            .switch b::after{content:"Off";}
            .switch input:checked ~ b{color:var(--brand-ink);}
            .switch input:checked ~ b::after{content:"On";}
            .set-ctl .switch{justify-content:flex-end;}

            .seg{display:flex;gap:0;border:1px solid var(--line);border-radius:12px;overflow:hidden;background:#fff;}
            .seg label{flex:1;position:relative;cursor:pointer;margin:0;}
            .seg input{position:absolute;opacity:0;width:0;height:0;padding:0;}
            .seg span{display:block;text-align:center;padding:10px 12px;font-size:13px;font-weight:500;color:var(--text);border-right:1px solid var(--line);}
            .seg label:last-child span{border-right:none;}
            .seg input:checked + span{background:var(--brand);color:#fff;}
            .seg input:focus-visible + span{outline:2px solid var(--brand-bright);outline-offset:-2px;}
            .days{display:flex;flex-wrap:wrap;gap:8px;}
            .days label{position:relative;cursor:pointer;margin:0;}
            .days input{position:absolute;opacity:0;width:0;height:0;padding:0;}
            .days span{display:flex;align-items:center;justify-content:center;width:52px;height:40px;border:1px solid var(--line);border-radius:11px;background:#fff;font-size:13px;font-weight:500;transition:all .15s;}
            .days input:checked + span{background:var(--brand);border-color:var(--brand);color:#fff;}
            .days input:focus-visible + span{outline:2px solid var(--brand-bright);outline-offset:2px;}

            .slabs{border:1px solid var(--line);border-radius:12px;overflow:hidden;}
            .slab{display:grid;grid-template-columns:1fr 1fr 120px 40px;gap:10px;align-items:center;padding:10px 12px;border-bottom:1px solid var(--line-soft);}
            .slab:last-child{border-bottom:none;}
            .slab.head{background:var(--brand-softer);font-size:12px;font-weight:600;color:var(--text-muted);}
            .slab .per{font-size:12.5px;color:var(--text-muted);}
            .slab .rm{width:34px;height:34px;border:1px solid var(--line);border-radius:10px;background:#fff;color:var(--bad);cursor:pointer;}
            .slab .rm:hover{background:var(--bad-soft);border-color:var(--bad);}
            .slab .rm:disabled{opacity:.35;cursor:not-allowed;}
            .rbtn{display:inline-flex;align-items:center;gap:7px;padding:8px 14px;border-radius:10px;border:1px solid var(--line);background:#fff;color:var(--text);font-family:var(--font-body);font-size:12.5px;font-weight:500;cursor:pointer;white-space:nowrap;transition:border-color .15s,background .15s;}
            .rbtn:hover{border-color:var(--brand-bright);background:var(--brand-softer);}
            .slab-add{margin-top:10px;align-self:flex-start;width:auto !important;}

            .set-save{position:sticky;bottom:14px;z-index:5;display:flex;align-items:center;gap:14px;padding:12px 16px;background:#fff;border:1px solid var(--line);border-radius:14px;box-shadow:var(--shadow-md);}
            .set-save .note{font-size:12.5px;color:var(--text-muted);flex:1;}
            .set-save .note.dirty{color:var(--warn);font-weight:600;}
            fieldset.set-fs{border:none;margin:0;padding:0;min-width:0;}
            @media (max-width:720px){
                .set-row{grid-template-columns:1fr;}
                .set-ctl{align-items:stretch;}
                .set-ctl .switch{justify-content:flex-start;}
                .slab{grid-template-columns:1fr 1fr 40px;}
                .slab .per,.slab.head span:nth-child(3){display:none;}
            }
        </style>

        <nav class="set-nav" id="set-nav" aria-label="Settings sections">
            <a href="#set-general">General</a>
            <a href="#set-attendance">Attendance</a>
            <a href="#set-pf">Provident fund</a>
            <a href="#set-esi">ESI</a>
            <a href="#set-pt">Professional tax</a>
            <a href="#set-tds">Income tax (TDS)</a>
            <a href="#set-leave">Leave</a>
        </nav>

        <div class="set-wrap">
        <form method="POST" action="<?php echo e(route('hr.settings.update')); ?>" id="settings-form">
            <?php echo csrf_field(); ?>
            <fieldset class="set-fs" <?php if(! $canEdit): echo 'disabled'; endif; ?>>

            
            <section class="set-sec" id="set-general">
                <div class="set-sec-head">
                    <div class="wh-ico"><i class="fa-regular fa-building"></i></div>
                    <div><h3>General</h3><p>Company details and the payroll calendar. Every change is saved to the audit log.</p></div>
                </div>
                <div class="set-body">
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-company_name">Company name</label><span>Your registered company name.</span></div>
                        <div class="set-ctl"><input id="s-company_name" type="text" name="company_name" value="<?php echo e($val('company_name')); ?>"><?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-leave_year_start_month">Leave year starts in</label><span>The month when every employee's leave balance starts a new year.</span></div>
                        <div class="set-ctl">
                            <select id="s-leave_year_start_month" name="leave_year_start_month">
                                <?php $__currentLoopData = $monthNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($i + 1); ?>" <?php if((int) $val('leave_year_start_month') === $i + 1): echo 'selected'; endif; ?>><?php echo e($m); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['leave_year_start_month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-payroll_cutoff_day">Payroll cutoff day</label><span>The day of the month your payroll cycle closes.</span></div>
                        <div class="set-ctl"><div class="affix suf"><input id="s-payroll_cutoff_day" type="number" step="any" min="1" max="31" name="payroll_cutoff_day" value="<?php echo e($val('payroll_cutoff_day')); ?>"><em>of month</em></div><?php $__errorArgs = ['payroll_cutoff_day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                </div>
            </section>

            
            <section class="set-sec" id="set-attendance">
                <div class="set-sec-head">
                    <div class="wh-ico"><i class="fa-regular fa-clock"></i></div>
                    <div><h3>Attendance and working days</h3><p>How attendance turns into paid days.</p></div>
                </div>
                <div class="set-body">
                    <div class="set-row stack">
                        <div class="set-lbl"><b>Weekly off days</b><span>Selected days are not counted as working days in payroll. If none are selected, Sunday is used.</span></div>
                        <div class="set-ctl">
                            <div class="days" role="group" aria-label="Weekly off days">
                                <?php $__currentLoopData = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label><input type="checkbox" data-day value="<?php echo e($i); ?>" <?php if(in_array((string) $i, $offDays, true)): echo 'checked'; endif; ?>><span><?php echo e($d); ?></span></label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <input type="hidden" name="weekly_off_days" value="<?php echo e($val('weekly_off_days')); ?>">
                            <?php $__errorArgs = ['weekly_off_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><b>Working day with no attendance record</b><span>When someone has no check-in on a working day, payroll treats them as present or absent.</span></div>
                        <div class="set-ctl">
                            <div class="seg" role="radiogroup">
                                <label><input type="radio" name="missing_attendance_as" value="present" <?php if($attMissing === 'present'): echo 'checked'; endif; ?>><span>Present</span></label>
                                <label><input type="radio" name="missing_attendance_as" value="absent" <?php if($attMissing === 'absent'): echo 'checked'; endif; ?>><span>Absent</span></label>
                            </div>
                            <?php $__errorArgs = ['missing_attendance_as'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-attendance_grace_minutes">Late grace period</label><span>Minutes of lateness allowed before a check-in is marked late.</span></div>
                        <div class="set-ctl"><div class="affix suf"><input id="s-attendance_grace_minutes" type="number" step="any" min="0" name="attendance_grace_minutes" value="<?php echo e($val('attendance_grace_minutes')); ?>"><em>min</em></div><?php $__errorArgs = ['attendance_grace_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-wfh_max_days_per_month">Work from home limit</label><span>Most work-from-home days one employee can take in a month.</span></div>
                        <div class="set-ctl"><div class="affix suf"><input id="s-wfh_max_days_per_month" type="number" step="any" min="0" name="wfh_max_days_per_month" value="<?php echo e($val('wfh_max_days_per_month')); ?>"><em>days</em></div><?php $__errorArgs = ['wfh_max_days_per_month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                </div>
            </section>

            
            <section class="set-sec" id="set-pf">
                <div class="set-sec-head">
                    <div class="wh-ico"><i class="fa-solid fa-piggy-bank"></i></div>
                    <div><h3>Provident fund (PF)</h3><p>Switch PF on or off per employee in their salary structure.</p></div>
                </div>
                <div class="set-body">
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-pf_contribution_rate">Employee contribution</label><span>Deducted from salary, as a percentage of Basic pay.</span></div>
                        <div class="set-ctl"><div class="affix suf"><input id="s-pf_contribution_rate" type="number" step="any" min="0" name="pf_contribution_rate" value="<?php echo e($val('pf_contribution_rate')); ?>"><em>%</em></div><?php $__errorArgs = ['pf_contribution_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><b>Limit PF to the wage ceiling</b><span>On: PF is worked out on Basic pay up to the ceiling below. Off: PF is worked out on full Basic pay.</span></div>
                        <div class="set-ctl">
                            <input type="hidden" name="pf_cap_wages" value="0">
                            <label class="switch"><input type="checkbox" name="pf_cap_wages" value="1" <?php if($flag('pf_cap_wages')): echo 'checked'; endif; ?>><i></i><b></b></label>
                            <?php $__errorArgs = ['pf_cap_wages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-pf_wage_ceiling">PF wage ceiling</label><span>The monthly pay amount PF is calculated up to.</span></div>
                        <div class="set-ctl"><div class="affix pre"><em>₹</em><input id="s-pf_wage_ceiling" type="number" step="any" min="0" name="pf_wage_ceiling" value="<?php echo e($val('pf_wage_ceiling')); ?>"></div><?php $__errorArgs = ['pf_wage_ceiling'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-pf_admin_edli_rate">Employer admin and EDLI charges</label><span>Paid by the company on top of salary. Not deducted from employees.</span></div>
                        <div class="set-ctl"><div class="affix suf"><input id="s-pf_admin_edli_rate" type="number" step="any" min="0" name="pf_admin_edli_rate" value="<?php echo e($val('pf_admin_edli_rate')); ?>"><em>%</em></div><?php $__errorArgs = ['pf_admin_edli_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                </div>
            </section>

            
            <section class="set-sec" id="set-esi">
                <div class="set-sec-head">
                    <div class="wh-ico"><i class="fa-solid fa-heart-pulse"></i></div>
                    <div><h3>Employee State Insurance (ESI)</h3><p>Applies only to employees whose monthly gross is within the limit.</p></div>
                </div>
                <div class="set-body">
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-esi_threshold">Monthly gross limit</label><span>Employees earning more than this are not covered by ESI.</span></div>
                        <div class="set-ctl"><div class="affix pre"><em>₹</em><input id="s-esi_threshold" type="number" step="any" min="0" name="esi_threshold" value="<?php echo e($val('esi_threshold')); ?>"></div><?php $__errorArgs = ['esi_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-esi_employee_rate">Employee contribution</label><span>Deducted from salary, as a percentage of gross pay.</span></div>
                        <div class="set-ctl"><div class="affix suf"><input id="s-esi_employee_rate" type="number" step="any" min="0" name="esi_employee_rate" value="<?php echo e($val('esi_employee_rate')); ?>"><em>%</em></div><?php $__errorArgs = ['esi_employee_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-esi_employer_rate">Employer contribution</label><span>Paid by the company on top of salary. Not deducted from employees.</span></div>
                        <div class="set-ctl"><div class="affix suf"><input id="s-esi_employer_rate" type="number" step="any" min="0" name="esi_employer_rate" value="<?php echo e($val('esi_employer_rate')); ?>"><em>%</em></div><?php $__errorArgs = ['esi_employer_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                </div>
            </section>

            
            <section class="set-sec" id="set-pt">
                <div class="set-sec-head">
                    <div class="wh-ico"><i class="fa-solid fa-landmark"></i></div>
                    <div><h3>Professional tax</h3><p>State tax charged per half-year, based on gross pay.</p></div>
                    <div>
                        <input type="hidden" name="pt_enabled" value="0">
                        <label class="switch"><input type="checkbox" class="sw-master" name="pt_enabled" value="1" <?php if($flag('pt_enabled')): echo 'checked'; endif; ?>><i></i><b></b></label>
                    </div>
                </div>
                <div class="set-body dep">
                    <?php $__errorArgs = ['pt_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="set-row">
                        <div class="set-lbl"><b>How it is deducted</b><span>Spread the half-year tax across six payslips, or take it all at once in September and March.</span></div>
                        <div class="set-ctl">
                            <div class="seg" role="radiogroup">
                                <label><input type="radio" name="pt_deduction_mode" value="monthly" <?php if($ptMode === 'monthly'): echo 'checked'; endif; ?>><span>Every month</span></label>
                                <label><input type="radio" name="pt_deduction_mode" value="half_yearly" <?php if($ptMode === 'half_yearly'): echo 'checked'; endif; ?>><span>Twice a year</span></label>
                            </div>
                            <?php $__errorArgs = ['pt_deduction_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="set-row stack">
                        <div class="set-lbl"><b>Tax slabs</b><span>Find the highest slab whose "at least" amount is within the employee's half-year gross. That slab's tax is charged for the half-year.</span></div>
                        <div class="set-ctl">
                            <div class="slabs" id="slabs">
                                <div class="slab head"><span>Half-year gross is at least</span><span>Tax for the half-year</span><span>If spread monthly</span><span></span></div>
                                <?php $__currentLoopData = $slabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slab" data-slab>
                                        <div class="affix pre"><em>₹</em><input type="number" step="any" min="0" value="<?php echo e($row[0]); ?>" aria-label="Half-year gross is at least"></div>
                                        <div class="affix pre"><em>₹</em><input type="number" step="any" min="0" value="<?php echo e($row[1]); ?>" aria-label="Tax for the half-year"></div>
                                        <span class="per"></span>
                                        <button type="button" class="rm" aria-label="Remove slab"><i class="fa-regular fa-trash-can"></i></button>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <button type="button" class="rbtn slab-add" id="slab-add"><i class="fa-solid fa-plus"></i>Add slab</button>
                            <input type="hidden" name="pt_slabs" value="<?php echo e($val('pt_slabs')); ?>">
                            <?php $__errorArgs = ['pt_slabs'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </section>

            
            <section class="set-sec" id="set-tds">
                <div class="set-sec-head">
                    <div class="wh-ico"><i class="fa-solid fa-receipt"></i></div>
                    <div><h3>Income tax (TDS)</h3><p>Tax deducted from salary. You can set a fixed amount per employee in their salary structure.</p></div>
                    <div>
                        <input type="hidden" name="tds_enabled" value="0">
                        <label class="switch"><input type="checkbox" class="sw-master" name="tds_enabled" value="1" <?php if($flag('tds_enabled')): echo 'checked'; endif; ?>><i></i><b></b></label>
                    </div>
                </div>
                <div class="set-body dep">
                    <?php $__errorArgs = ['tds_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="set-row">
                        <div class="set-lbl"><label for="s-tds_standard_deduction">Standard deduction</label><span>The yearly amount subtracted from income before TDS is calculated.</span></div>
                        <div class="set-ctl"><div class="affix pre"><em>₹</em><input id="s-tds_standard_deduction" type="number" step="any" min="0" name="tds_standard_deduction" value="<?php echo e($val('tds_standard_deduction')); ?>"></div><?php $__errorArgs = ['tds_standard_deduction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    </div>
                </div>
            </section>

            </fieldset>

            <?php if($canEdit): ?>
                <div class="set-save">
                    <span class="note" id="set-note">No changes yet.</span>
                    <button type="submit" class="btn-primary">Save settings</button>
                </div>
            <?php else: ?>
                <p class="field-hint">You can view these settings but not change them.</p>
            <?php endif; ?>
        </form>

        <script>
        (function () {
            var form = document.getElementById('settings-form');
            if (!form) return;
            var note = document.getElementById('set-note');
            var dayBoxes = form.querySelectorAll('[data-day]');
            var daysOut = form.querySelector('input[name=weekly_off_days]');
            var slabBox = document.getElementById('slabs');
            var slabOut = form.querySelector('input[name=pt_slabs]');

            function syncDays() {
                daysOut.value = Array.prototype.filter.call(dayBoxes, function (d) { return d.checked; })
                    .map(function (d) { return d.value; }).join(',');
            }
            function rows() { return slabBox.querySelectorAll('[data-slab]'); }
            function syncSlabs() {
                var out = [];
                rows().forEach(function (r) {
                    var i = r.querySelectorAll('input');
                    var from = parseFloat(i[0].value), tax = parseFloat(i[1].value);
                    r.querySelector('.per').textContent = isFinite(tax) ? '\u2248 \u20B9' + Math.round(tax / 6).toLocaleString('en-IN') + ' / month' : '';
                    if (isFinite(from) && isFinite(tax)) out.push([from, tax]);
                });
                out.sort(function (a, b) { return a[0] - b[0]; });
                slabOut.value = out.length ? JSON.stringify(out) : '';
                var only = rows().length <= 1;
                rows().forEach(function (r) { r.querySelector('.rm').disabled = only; });
            }
            function sync() { syncDays(); syncSlabs(); }

            document.getElementById('slab-add').addEventListener('click', function () {
                var first = rows()[0];
                var row = first.cloneNode(true);
                row.querySelectorAll('input').forEach(function (i) { i.value = ''; });
                slabBox.appendChild(row);
                sync();
                row.querySelector('input').focus();
            });
            slabBox.addEventListener('click', function (e) {
                var btn = e.target.closest('.rm');
                if (btn && rows().length > 1) { btn.closest('[data-slab]').remove(); sync(); markDirty(); }
            });

            function markDirty() {
                if (!note) return;
                note.textContent = 'You have unsaved changes.';
                note.classList.add('dirty');
            }
            form.addEventListener('input', function () { sync(); markDirty(); });
            form.addEventListener('change', function () { sync(); markDirty(); });
            form.addEventListener('submit', sync);
            sync();
        })();
        </script>
        </div>


        <script>
        (function () {
            var links = document.querySelectorAll('#set-nav a');
            var map = {};
            links.forEach(function (a) { map[a.getAttribute('href').slice(1)] = a; });
            var ids = Object.keys(map);
            function update() {
                var current = ids[0];
                ids.forEach(function (id) {
                    var el = document.getElementById(id);
                    if (el && el.getBoundingClientRect().top <= 160) current = id;
                });
                if (window.innerHeight + window.scrollY >= document.body.scrollHeight - 4) current = ids[ids.length - 1];
                links.forEach(function (a) { a.classList.toggle('on', a === map[current]); });
                var on = map[current];
                if (on) { var nav = document.getElementById('set-nav'); nav.scrollLeft = on.offsetLeft - 40; }
            }
            window.addEventListener('scroll', update, { passive: true });
            window.addEventListener('resize', update);
            update();
        })();
        </script>

        <div id="set-leave" style="scroll-margin-top:140px;"></div>

        
        <?php $fmtDays = fn($n) => rtrim(rtrim(number_format((float) $n, 1), '0'), '.') ?: '0'; ?>
        <div class="card" style="max-width:980px;margin-top:6px;">

            <div class="widget-head">
                <div class="wh-ico"><i class="fa-regular fa-calendar-check"></i></div>
                <div>
                    <h3>Leave entitlements</h3>
                    <p>Set how many days each leave type gives per year. Unpaid leave is unlimited — employees can take as much as they need.</p>
                </div>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hr-settings.edit')): ?>
            <form method="POST" action="<?php echo e(route('hr.settings.leave-types.update')); ?>"
                onsubmit="var r=this.querySelector('input[name=apply_to_existing]:checked'); return !(r && r.value==='1') || confirm('Update the current-year leave balances of ALL existing employees?');">
                <?php echo csrf_field(); ?>
                <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Leave type</th>
                            <th>Pay</th>
                            <th>Days per year</th>
                            <th>Carry forward</th>
                            <th>Max days carried</th>
                            <th>Applies to</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $unpaidCount = $leaveTypes->where('is_paid', false)->count(); ?>
                        <?php $__currentLoopData = $leaveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $usedBy = (int) ($leaveTypeUsage[$lt->id] ?? 0);
                            $isLastUnpaid = ! $lt->is_paid && $unpaidCount <= 1;
                            $canDelete = $usedBy === 0 && ! $isLastUnpaid;
                            $whyNot = $isLastUnpaid
                                ? 'Unpaid Leave must stay - it is the unlimited fallback.'
                                : 'Has '.$usedBy.' leave request'.($usedBy === 1 ? '' : 's').' - deleting would erase that history.';
                        ?>
                        <tr>
                            <td><b><?php echo e($lt->name); ?></b></td>
                            <?php if($lt->is_paid): ?>
                                <td><span class="pill pill-ok">Paid</span></td>
                                <td>
                                    <input type="number" step="0.5" min="0" max="365" style="width:100px;"
                                        name="types[<?php echo e($lt->id); ?>][days]"
                                        value="<?php echo e(old('types.'.$lt->id.'.days', $fmtDays($lt->default_annual_days))); ?>">
                                    <?php $__errorArgs = ['types.'.$lt->id.'.days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </td>
                                <td>
                                    <input type="hidden" name="types[<?php echo e($lt->id); ?>][carry_forward]" value="0">
                                    <label style="display:inline-flex;align-items:center;gap:6px;">
                                        <input type="checkbox" name="types[<?php echo e($lt->id); ?>][carry_forward]" value="1"
                                            <?php if(old('types.'.$lt->id.'.carry_forward', $lt->carry_forward)): echo 'checked'; endif; ?>>
                                        Yes
                                    </label>
                                </td>
                                <td>
                                    <input type="number" step="0.5" min="0" max="365" style="width:100px;"
                                        name="types[<?php echo e($lt->id); ?>][max_carry_forward]"
                                        value="<?php echo e(old('types.'.$lt->id.'.max_carry_forward', $fmtDays($lt->max_carry_forward))); ?>">
                                </td>
                            <?php else: ?>
                                <td><span class="pill pill-muted">Unpaid</span></td>
                                <td colspan="3"><span class="card-note" style="margin:0;">Unlimited &mdash; no cap, no balance to maintain.</span></td>
                            <?php endif; ?>
                            <td><?php echo e(match($lt->applicable_to ?? 'all') { 'female' => 'Female employees', 'male' => 'Male employees', default => 'Everyone' }); ?></td>
                            <td style="text-align:right;">
                                <button type="submit" form="leaveTypeDeleteForm"
                                    formaction="<?php echo e(route('hr.settings.leave-types.destroy', $lt)); ?>"
                                    class="btn-ghost" data-name="<?php echo e($lt->name); ?>"
                                    style="color:var(--bad);<?php echo e($canDelete ? '' : 'opacity:.35;cursor:not-allowed;'); ?>"
                                    <?php if($canDelete): ?> onclick="return confirm('Delete ' + this.dataset.name + '? This cannot be undone.')"
                                    <?php else: ?> disabled title="<?php echo e($whyNot); ?>" <?php endif; ?>>
                                    <i class="fa-regular fa-trash-can"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                </div>

                <style>
                    .scope-title{margin:22px 0 4px;font-family:var(--font-head);font-size:14px;color:var(--brand-ink);}
                    .scope-sub{margin:0 0 10px;font-size:12.5px;color:var(--text-muted);}
                    .scope-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:12px;}
                    .scope-opt{display:flex;gap:12px;align-items:flex-start;padding:14px 16px;border:1.5px solid var(--line);border-radius:var(--radius);
                        background:#fff;cursor:pointer;transition:border-color .15s,background .15s,box-shadow .15s;}
                    .scope-opt:hover{border-color:var(--brand-bright);}
                    .scope-opt input{width:auto;flex:0 0 auto;margin:3px 0 0;padding:0;accent-color:var(--brand);box-shadow:none;}
                    .scope-opt:has(input:checked){border-color:var(--brand);background:var(--brand-softer);box-shadow:0 0 0 3px rgba(94,141,61,.14);}
                    .scope-opt b{display:block;font-size:13.5px;color:var(--brand-ink);margin-bottom:3px;}
                    .scope-opt span{display:block;font-size:12.5px;color:var(--text-muted);line-height:1.5;}
                    .scope-opt em{display:block;margin-top:6px;font-style:normal;font-size:12px;color:var(--brand-strong);background:var(--brand-soft);padding:4px 8px;border-radius:8px;}
                    .scope-opt.warn:has(input:checked){border-color:var(--warn);background:var(--warn-soft);box-shadow:0 0 0 3px rgba(180,83,9,.12);}
                    .scope-opt.warn em{color:var(--warn);background:#fff;}
                </style>

                <h4 class="scope-title">Who should get these changes?</h4>
                <p class="scope-sub">Choose how far your changes reach when you save.</p>
                <div class="scope-grid">
                    <label class="scope-opt">
                        <input type="radio" name="apply_to_existing" value="0" checked>
                        <div>
                            <b><i class="fa-solid fa-user-plus" style="margin-right:6px;"></i>New employees &amp; next year only</b>
                            <span>Nobody's current balance changes. The new numbers are used for people who join later and when a new year starts.</span>
                        </div>
                    </label>
                    <label class="scope-opt warn">
                        <input type="radio" name="apply_to_existing" value="1">
                        <div>
                            <b><i class="fa-solid fa-users" style="margin-right:6px;"></i>Everyone, right now</b>
                            <span>Also resets this year's entitlement for all current employees. Days they've already used are kept.</span>
                            <em>Example: set to 15, someone used 3 &rarr; they now have 12 left.</em>
                        </div>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save leave entitlements</button>
                </div>
            </form>

            
            <form id="leaveTypeDeleteForm" method="POST" action="" style="display:none;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
            </form>

            <hr style="border:none;border-top:1px solid var(--line);margin:24px 0 18px;">

            <h4 style="margin:0 0 10px;font-family:var(--font-head);color:var(--brand-ink);">Add a leave type</h4>
            <form method="POST" action="<?php echo e(route('hr.settings.leave-types.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="field-grid cols-3">
                    <div class="field"><label>Name</label><input name="name" maxlength="60" placeholder="e.g. Compensatory Off" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                    <div class="field"><label>Pay</label>
                        <select name="is_paid"><option value="1">Paid (limited days)</option><option value="0">Unpaid (unlimited)</option></select></div>
                    <div class="field"><label>Days per year (paid only)</label><input type="number" name="default_annual_days" step="0.5" min="0" max="365" value="0"></div>
                    <div class="field"><label>Applies to</label>
                        <select name="applicable_to"><option value="all">Everyone</option><option value="female">Female employees</option><option value="male">Male employees</option></select></div>
                </div>
                <div class="form-actions"><button type="submit" class="btn-secondary">Add leave type</button></div>
            </form>

            <?php else: ?>
            <table>
                <thead><tr><th>Leave type</th><th>Pay</th><th>Days per year</th><th>Carry forward</th></tr></thead>
                <tbody>
                <?php $__currentLoopData = $leaveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><b><?php echo e($lt->name); ?></b></td>
                        <td><?php echo e($lt->is_paid ? 'Paid' : 'Unpaid'); ?></td>
                        <td><?php echo e($lt->is_paid ? $fmtDays($lt->default_annual_days) : 'Unlimited'); ?></td>
                        <td><?php echo e($lt->is_paid && $lt->carry_forward ? 'Up to '.$fmtDays($lt->max_carry_forward).' days' : '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/settings.blade.php ENDPATH**/ ?>