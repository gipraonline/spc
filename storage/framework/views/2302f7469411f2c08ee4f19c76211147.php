<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Growth',
        'heroIcon' => 'fa-solid fa-user-plus',
        'heroSummary' => 'Requisitions, candidate pipeline and onboarding checklists.',
        'heroStats' => [
            ['label' => 'Requisitions', 'icon' => 'fa-solid fa-folder-open', 'value' => $requisitions->count()],
            ['label' => 'Candidates', 'icon' => 'fa-solid fa-users', 'value' => $requisitions->sum(fn ($r) => $r->candidates->count())],
            $onboardingCandidate ? ['label' => 'Onboarding', 'icon' => 'fa-solid fa-rocket', 'value' => $checklist->where('is_completed', true)->count() . '/' . $checklist->count()] : null,
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        <?php if($role === 'hr_admin' || $role === 'super_admin'): ?>
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-file-signature"></i></div>
                    <div>
                        <h3>New job requisition</h3>
                        <p>Opens directly for this demo &mdash; in production this would need department-head sign-off.</p>
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
                    <div class="form-actions"><button type="submit" class="btn-primary">Create requisition</button></div>
                </form>
            </div>
        <?php endif; ?>

        <?php if($requisitions->isNotEmpty()): ?>
            <form method="GET" action="<?php echo e(url('/modules/recruitment')); ?>" style="margin:24px 0 0;max-width:440px;" onchange="this.submit()">
                <div class="field">
                    <label><i class="fa-solid fa-filter" style="color:var(--brand);margin-right:6px;font-size:11px;"></i>Candidate pipeline for</label>
                    <select name="requisition">
                        <?php $__currentLoopData = $requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($req->id); ?>" <?php if($selectedRequisition && $selectedRequisition->id === $req->id): echo 'selected'; endif; ?>>
                                <?php echo e($req->title); ?> &mdash; <?php echo e(ucfirst(str_replace('_',' ',$req->status))); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </form>
        <?php endif; ?>

        <?php if($selectedRequisition): ?>
            <div class="section-head" style="margin-top:24px;">
                <h2><i class="fa-solid fa-arrow-down-short-wide"></i>Candidate pipeline &mdash; <?php echo e($selectedRequisition->title); ?></h2>
                <span class="hint">Applied → Shortlisted → Interviewed → Offered → Hired</span>
            </div>
            <div class="pipeline">
                <?php $__currentLoopData = ['applied'=>'Applied','shortlisted'=>'Shortlisted','interviewed'=>'Interviewed','offered'=>'Offered','hired'=>'Hired']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pipe-col">
                        <div class="pipe-head">
                            <span class="ph-dot" style="background:<?php echo e(['applied'=>'#8FA79B','shortlisted'=>'#1FA97A','interviewed'=>'#1D6FA5','offered'=>'#A16207','hired'=>'#0E5239'][$stage]); ?>;"></span>
                            <h4><?php echo e($label); ?></h4>
                            <span><?php echo e(($pipeline[$stage] ?? collect())->count()); ?></span>
                        </div>
                        <?php $__currentLoopData = $pipeline[$stage] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="pipe-card">
                                <div class="pc-av"><?php echo e(strtoupper(substr($c->name,0,1))); ?></div>
                                <div class="name"><?php echo e($c->name); ?></div>
                                <div class="meta"><i class="fa-solid fa-link" style="font-size:9px;margin-right:4px;"></i><?php echo e($c->source ?? 'Direct'); ?></div>
                                <?php if($role === 'hr_admin' || $role === 'super_admin'): ?>
                                    <form method="POST" action="<?php echo e(route('hr.recruitment.candidate.stage', $c)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <select name="stage" onchange="this.form.submit()">
                                            <?php $__currentLoopData = ['applied','shortlisted','interviewed','offered','hired','rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($s); ?>" <?php if($c->stage === $s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(($pipeline[$stage] ?? collect())->isEmpty()): ?>
                            <div style="text-align:center;padding:14px 6px;color:#A7C9B8;font-size:11.5px;"><i class="fa-regular fa-circle" style="opacity:.6;"></i></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="table-card" style="margin-top:24px;"><div class="tc-body">
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-user-plus"></i></div>
                    <b>No requisitions yet</b>
                    <span>Create your first job requisition above to start the pipeline.</span>
                </div>
            </div></div>
        <?php endif; ?>

        <?php if($onboardingCandidate): ?>
            <div class="section-head" style="margin-top:32px;">
                <h2><i class="fa-solid fa-rocket"></i>Onboarding &mdash; <?php echo e($onboardingCandidate->name); ?></h2>
                <span class="hint"><?php echo e($checklist->where('is_completed', true)->count()); ?> of <?php echo e($checklist->count()); ?> steps done</span>
            </div>
            <div class="card">
                <ul class="checklist">
                    <?php $__currentLoopData = $checklist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="<?php echo e($item->is_completed ? 'done' : ''); ?>">
                            <?php if($role === 'hr_admin' || $role === 'super_admin'): ?>
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
            </div>
        <?php endif; ?>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/recruitment.blade.php ENDPATH**/ ?>