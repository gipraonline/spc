<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $isHr = $role === 'hr_admin' || $role === 'super_admin';
        $pillFor = fn ($st) => ['open' => 'pill-ok', 'pending_approval' => 'pill-warn', 'rejected' => 'pill-bad', 'closed' => 'pill-muted'][$st] ?? 'pill-muted';
    ?>

    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Growth',
        'heroIcon' => 'fa-solid fa-user-plus',
        'heroSummary' => 'Requisitions, candidate pipeline and onboarding checklists.',
        'heroStats' => [
            ['label' => 'Requisitions', 'icon' => 'fa-solid fa-folder-open', 'value' => $requisitions->count()],
            ['label' => 'Candidates', 'icon' => 'fa-solid fa-users', 'value' => $requisitions->sum(fn ($r) => $r->candidates->count())],
            ['label' => 'Onboarding', 'icon' => 'fa-solid fa-rocket', 'value' => $onboarding->count()],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        <div class="tabs" style="margin-top:32px;">
            <button type="button" class="tab <?php echo e($activeTab === 'requisitions' ? 'active' : ''); ?>" data-tab="requisitions" onclick="recruitmentTab(this,'requisitions')">Requisitions <?php if($pendingCount): ?>(<?php echo e($pendingCount); ?> pending)<?php endif; ?></button>
            <button type="button" class="tab <?php echo e($activeTab === 'candidates' ? 'active' : ''); ?>" data-tab="candidates" onclick="recruitmentTab(this,'candidates')">Candidates</button>
            <button type="button" class="tab <?php echo e($activeTab === 'onboarding' ? 'active' : ''); ?>" data-tab="onboarding" onclick="recruitmentTab(this,'onboarding')">Onboarding (<?php echo e($onboarding->count()); ?>)</button>
            <button type="button" class="tab <?php echo e($activeTab === 'history' ? 'active' : ''); ?>" data-tab="history" onclick="recruitmentTab(this,'history')">History (<?php echo e($history->count()); ?>)</button>
        </div>

        
        <div class="tabpanel <?php echo e($activeTab === 'requisitions' ? 'active' : ''); ?>" data-tabpanel="requisitions">
            <?php if($isHr): ?>
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-file-signature"></i></div>
                        <div>
                            <h3>New job requisition</h3>
                            <p><?php if($canApprove): ?>As Super Admin your requisition opens straight away.<?php else: ?> Sent to the Super Admin for approval. Candidates can be added once it is approved.<?php endif; ?></p>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('hr.recruitment.requisition.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field-grid">
                            <div class="field full"><label>Job title</label><input name="title" placeholder="e.g. Senior Sales Executive" required></div>
                            <div class="field">
                                <label>Department</label>
                                <select name="department_id">
                                    <option value="">&mdash;</option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="field">
                                <label>Designation</label>
                                <select name="designation_id">
                                    <option value="">&mdash;</option>
                                    <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>"><?php echo e($d->title); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="field"><label>Openings</label><input type="number" name="openings" value="1" min="1" required></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary"><?php echo e($canApprove ? 'Create requisition' : 'Submit for approval'); ?></button></div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="table-card" style="margin-top:24px;">
                <div class="tc-head"><h3>All requisitions</h3></div>
                <div class="tc-body">
                    <?php if($requisitions->isEmpty()): ?>
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-user-plus"></i></div>
                            <b>No requisitions yet</b>
                            <span>Create the first job requisition above.</span>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Position</th><th>Department</th><th>Openings</th><th>Filled</th><th>Candidates</th>
                                    <th>Status</th><th>Raised by</th><th>Date</th><th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><b><?php echo e($req->title); ?></b><?php if($req->designation): ?><br><small><?php echo e($req->designation->title); ?></small><?php endif; ?></td>
                                        <td><?php echo e($req->department->name ?? '—'); ?></td>
                                        <td><?php echo e($req->openings); ?></td>
                                        <td><?php echo e($req->candidates->where('stage', 'hired')->count()); ?> / <?php echo e($req->openings); ?></td>
                                        <td><?php echo e($req->candidates->count()); ?></td>
                                        <td><span class="pill <?php echo e($pillFor($req->status)); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $req->status))); ?></span>
                                            <?php if($req->status === 'rejected' && $req->decision_remarks): ?><br><small><?php echo e($req->decision_remarks); ?></small><?php endif; ?></td>
                                        <td><?php echo e($req->requestedBy->name ?? '—'); ?></td>
                                        <td><?php echo e($req->created_at ? \Illuminate\Support\Carbon::parse($req->created_at)->format('d M Y') : '—'); ?></td>
                                        <td style="white-space:nowrap;">
                                            <?php if($req->status === 'pending_approval' && $canApprove): ?>
                                                <form method="POST" action="<?php echo e(route('hr.recruitment.requisition.decide', $req)); ?>" style="display:flex;gap:6px;align-items:center;">
                                                    <?php echo csrf_field(); ?>
                                                    <input name="remarks" maxlength="255" placeholder="Remarks" style="width:130px;">
                                                    <button type="submit" name="decision" value="approve" class="btn-primary">Approve</button>
                                                    <button type="submit" name="decision" value="reject" class="btn-secondary" onclick="return confirm('Reject this requisition?')">Reject</button>
                                                </form>
                                            <?php elseif($req->status === 'open'): ?>
                                                <a href="<?php echo e(route('hr.recruitment.index', ['requisition' => $req->id, 'tab' => 'candidates'])); ?>">Candidates &rarr;</a>
                                            <?php elseif($req->status === 'closed'): ?>
                                                <a href="<?php echo e(route('hr.recruitment.index', ['requisition' => $req->id, 'tab' => 'candidates'])); ?>">View</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="tabpanel <?php echo e($activeTab === 'candidates' ? 'active' : ''); ?>" data-tabpanel="candidates">
            <?php if($requisitions->isEmpty()): ?>
                <div class="table-card"><div class="tc-body"><div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-user-plus"></i></div>
                    <b>No requisitions yet</b>
                    <span>Create a requisition in the Requisitions tab to start the pipeline.</span>
                </div></div></div>
            <?php else: ?>
                <form method="GET" action="<?php echo e(route('hr.recruitment.index')); ?>" style="margin:0 0 20px;max-width:480px;" onchange="this.submit()">
                    <input type="hidden" name="tab" value="candidates">
                    <div class="field">
                        <label><i class="fa-solid fa-filter" style="color:var(--brand);margin-right:6px;font-size:11px;"></i>Requisition</label>
                        <select name="requisition">
                            <?php $__currentLoopData = $requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($req->id); ?>" <?php if($selectedRequisition && $selectedRequisition->id === $req->id): echo 'selected'; endif; ?>>
                                    <?php echo e($req->title); ?> &mdash; <?php echo e(ucfirst(str_replace('_', ' ', $req->status))); ?> (<?php echo e($req->candidates->where('stage', 'hired')->count()); ?>/<?php echo e($req->openings); ?> hired)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </form>

                <?php if($selectedRequisition): ?>
                    <?php $rs = $selectedRequisition->status; ?>

                    <?php if($rs === 'open' && $isHr): ?>
                        <div class="card">
                            <div class="widget-head">
                                <div class="wh-ico"><i class="fa-solid fa-user-plus"></i></div>
                                <div><h3>New candidate</h3><p><?php echo e($selectedRequisition->title); ?></p></div>
                            </div>
                            <form method="POST" action="<?php echo e(route('hr.recruitment.candidate.store', $selectedRequisition)); ?>" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="field-grid">
                                    <div class="field"><label>Name</label><input name="name" value="<?php echo e(old('name')); ?>" maxlength="150" required></div>
                                    <div class="field"><label>Phone</label><input name="phone" value="<?php echo e(old('phone')); ?>" maxlength="20" inputmode="tel" required></div>
                                    <div class="field"><label>Email</label><input type="email" name="email" value="<?php echo e(old('email')); ?>" maxlength="150"></div>
                                    <div class="field">
                                        <label>Source</label>
                                        <select name="source" required>
                                            <option value="">&mdash;</option>
                                            <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(old('source') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="field full"><label>Resume (PDF, DOC or DOCX, up to 5 MB)</label><input type="file" name="resume" accept=".pdf,.doc,.docx"></div>
                                </div>
                                <div class="form-actions"><button type="submit" class="btn-primary">Add candidate</button></div>
                            </form>
                        </div>
                    <?php elseif($rs === 'pending_approval'): ?>
                        <div class="flash-errors" style="background:var(--warn-soft);color:#8A5A10;border-color:rgba(180,83,9,.2);">This requisition is waiting for Super Admin approval. Candidates can be added once it is open.</div>
                    <?php elseif($rs === 'rejected'): ?>
                        <div class="flash-errors">This requisition was rejected<?php echo e($selectedRequisition->decision_remarks ? ': '.$selectedRequisition->decision_remarks : '.'); ?></div>
                    <?php elseif($rs === 'closed'): ?>
                        <div class="flash" style="background:#EDF3EF;color:#52645B;border-color:rgba(18,58,40,.1);">Closed &mdash; all <?php echo e($selectedRequisition->openings); ?> <?php echo e($selectedRequisition->openings == 1 ? 'opening is' : 'openings are'); ?> filled.</div>
                    <?php endif; ?>

                    <div class="section-head" style="margin-top:24px;">
                        <h2><i class="fa-solid fa-arrow-down-short-wide"></i>Candidate pipeline &mdash; <?php echo e($selectedRequisition->title); ?></h2>
                        <span class="hint">Applied → Shortlisted → Interviewed → Offered → Hired</span>
                    </div>
                    <div class="pipeline">
                        <?php $__currentLoopData = ['applied'=>'Applied','shortlisted'=>'Shortlisted','interviewed'=>'Interviewed','offered'=>'Offered','hired'=>'Hired']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="pipe-col">
                                <div class="pipe-head">
                                    <span class="ph-dot" style="background:<?php echo e(['applied'=>'#8FA79B','shortlisted'=>'#5E8D3D','interviewed'=>'#1D6FA5','offered'=>'#A16207','hired'=>'#0E5239'][$stage]); ?>;"></span>
                                    <h4><?php echo e($label); ?></h4>
                                    <span><?php echo e(($pipeline[$stage] ?? collect())->count()); ?></span>
                                </div>
                                <?php $__currentLoopData = $pipeline[$stage] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="pipe-card">
                                        <div class="pc-av"><?php echo e(strtoupper(substr($c->name,0,1))); ?></div>
                                        <div class="name"><?php echo e($c->name); ?></div>
                                        <div class="meta"><i class="fa-solid fa-link" style="font-size:9px;margin-right:4px;"></i><?php echo e($sources[$c->source] ?? ucfirst($c->source ?? 'Direct')); ?></div>
                                        <?php if($c->phone): ?><div class="meta"><i class="fa-solid fa-phone" style="font-size:9px;margin-right:4px;"></i><?php echo e($c->phone); ?></div><?php endif; ?>
                                        <?php if($isHr && $c->resume_path): ?>
                                            <div class="meta"><a href="<?php echo e(route('hr.recruitment.candidate.resume', $c)); ?>"><i class="fa-solid fa-file-arrow-down" style="font-size:9px;margin-right:4px;"></i>Resume</a></div>
                                        <?php endif; ?>
                                        <?php if($isHr && $stage !== 'hired' && $rs === 'open'): ?>
                                            <?php $next = $stages[array_search($stage, $stages, true) + 1] ?? null; ?>
                                            <form method="POST" action="<?php echo e(route('hr.recruitment.candidate.stage', $c)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <select name="stage" onchange="this.form.submit()">
                                                    <option value="<?php echo e($stage); ?>" selected><?php echo e(ucfirst($stage)); ?></option>
                                                    <?php if($next): ?><option value="<?php echo e($next); ?>">Move to <?php echo e(ucfirst($next)); ?></option><?php endif; ?>
                                                    <option value="rejected">Reject</option>
                                                </select>
                                            </form>
                                        <?php endif; ?>
                                        <?php if($stage === 'hired' && $isHr): ?>
                                            <?php if($canCreateEmployee): ?>
                                                <a href="<?php echo e(route('admin.employees.create', ['candidate' => $c->id, 'name' => $c->name, 'email' => $c->email])); ?>" class="btn-primary" style="display:block;text-align:center;margin-top:8px;font-size:12px;padding:6px 8px;">Create employee &rarr;</a>
                                            <?php else: ?>
                                                <div class="meta" style="margin-top:6px;">Needs an admin with employee-create access.</div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(($pipeline[$stage] ?? collect())->isEmpty()): ?>
                                    <div style="text-align:center;padding:14px 6px;color:#A7C9B8;font-size:11.5px;"><i class="fa-regular fa-circle" style="opacity:.6;"></i></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        
        <div class="tabpanel <?php echo e($activeTab === 'onboarding' ? 'active' : ''); ?>" data-tabpanel="onboarding">
            <?php $__empty_1 = true; $__currentLoopData = $onboarding; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $items = $oc->checklistItems->sortBy('id')->values(); ?>
                <div class="section-head" style="margin-top:<?php echo e($loop->first ? '0' : '24px'); ?>;">
                    <h2><i class="fa-solid fa-rocket"></i><?php echo e($oc->name); ?> &mdash; <?php echo e($oc->requisition->title ?? 'Requisition'); ?></h2>
                    <span class="hint"><?php echo e($items->where('is_completed', true)->count()); ?> of <?php echo e($items->count()); ?> steps done</span>
                </div>
                <div class="card">
                    <ul class="checklist">
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="<?php echo e($item->is_completed ? 'done' : ''); ?>">
                                <?php if($isHr): ?>
                                    <form method="POST" action="<?php echo e(route('hr.recruitment.checklist.toggle', $item)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="num"><?php echo e($item->is_completed ? '✓' : $loop->iteration); ?></button>
                                    </form>
                                <?php else: ?>
                                    <span class="num"><?php echo e($item->is_completed ? '✓' : $loop->iteration); ?></span>
                                <?php endif; ?>
                                <?php echo e($item->item); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <?php if($isHr): ?>
                        <div class="form-actions" style="margin-top:14px;">
                            <?php if($canCreateEmployee): ?>
                                <a href="<?php echo e(route('admin.employees.create', ['candidate' => $oc->id, 'name' => $oc->name, 'email' => $oc->email])); ?>" class="btn-primary">Create employee &rarr;</a>
                            <?php else: ?>
                                <span class="hint">Needs an admin with employee-create access to create the employee.</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="table-card"><div class="tc-body"><div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-rocket"></i></div>
                    <b>No one is onboarding</b>
                    <span>Hired candidates appear here until their employee profile is created.</span>
                </div></div></div>
            <?php endif; ?>
        </div>

        
        <div class="tabpanel <?php echo e($activeTab === 'history' ? 'active' : ''); ?>" data-tabpanel="history">
            <div class="table-card">
                <div class="tc-head"><h3>Hired and added as employees</h3></div>
                <div class="tc-body">
                    <?php if($history->isEmpty()): ?>
                        <div class="empty-widget">
                            <div class="ew-ico"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <b>Nothing here yet</b>
                            <span>Hired candidates move here once their employee profile is created.</span>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead><tr><th>Candidate</th><th>Position</th><th>Source</th><th>Employee</th><th>Hired on</th></tr></thead>
                            <tbody>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><b><?php echo e($h->name); ?></b><?php if($h->phone): ?><br><small><?php echo e($h->phone); ?></small><?php endif; ?></td>
                                        <td><?php echo e($h->requisition->title ?? '—'); ?></td>
                                        <td><?php echo e($sources[$h->source] ?? ucfirst($h->source ?? 'Direct')); ?></td>
                                        <td><?php echo e($h->convertedEmployee->user->name ?? 'Employee #'.$h->converted_employee_id); ?></td>
                                        <td><?php echo e($h->updated_at ? $h->updated_at->format('d M Y') : '—'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function recruitmentTab(btn, name) {
        const scope = document.querySelector('.content');
        scope.querySelectorAll(':scope > .tabs .tab').forEach(t => t.classList.remove('active'));
        scope.querySelectorAll(':scope > .tabpanel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        scope.querySelectorAll('[data-tabpanel="' + name + '"]').forEach(p => p.classList.add('active'));
        // Keep the tab in the URL so forms that redirect back land on the same tab.
        const url = new URL(window.location);
        url.searchParams.set('tab', name);
        history.replaceState(null, '', url);
    }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/recruitment.blade.php ENDPATH**/ ?>