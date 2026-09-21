<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Growth',
        'heroIcon' => 'fa-solid fa-trophy',
        'heroSummary' => 'Appraisal cycles, self-assessment and manager review.',
        'heroStats' => [
            ['label' => 'Cycles', 'icon' => 'fa-solid fa-rotate', 'value' => $cycles->count()],
            ['label' => 'To review', 'icon' => 'fa-solid fa-user-pen', 'value' => $toReview->count()],
            ['label' => 'Mine', 'icon' => 'fa-regular fa-id-badge', 'value' => $ownAppraisals->count()],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        <?php if($cycles->isNotEmpty()): ?>
            <div class="stat-tiles" style="grid-template-columns:repeat(<?php echo e(min($cycles->count(), 3)); ?>,1fr);">
                <?php $__currentLoopData = $cycles->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="stat-tile">
                        <div class="st-ico"><i class="fa-solid fa-rotate"></i></div>
                        <div>
                            <b style="font-size:15px;"><?php echo e($cycle->name); ?></b>
                            <span><?php echo e(ucfirst($cycle->status)); ?> · <?php echo e(\Illuminate\Support\Carbon::parse($cycle->start_date)->format('d M')); ?> &ndash; <?php echo e(\Illuminate\Support\Carbon::parse($cycle->end_date)->format('d M Y')); ?></span>
                        </div>
                        <span class="pill <?php echo e($cycle->status === 'active' ? 'pill-ok' : ($cycle->status === 'closed' ? 'pill-muted' : 'pill-warn')); ?>" style="margin-left:auto;"><?php echo e(ucfirst($cycle->status)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php if($currentAppraisal): ?>
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-user-pen"></i></div>
                    <div>
                        <h3>Self-assessment &mdash; <?php echo e($currentAppraisal->cycle->name); ?></h3>
                        <p>Status: <?php echo e(ucfirst(str_replace('_',' ',$currentAppraisal->status))); ?></p>
                    </div>
                    <span class="pill <?php echo e($currentAppraisal->status === 'completed' ? 'pill-ok' : 'pill-warn'); ?> wh-right"><?php echo e(ucfirst(str_replace('_',' ',$currentAppraisal->status))); ?></span>
                </div>

                <?php if($currentAppraisal->goals->isNotEmpty()): ?>
                    <?php $__currentLoopData = $currentAppraisal->goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="goal-row">
                            <div class="goal-title"><i class="fa-solid fa-bullseye" style="color:var(--brand);margin-right:8px;font-size:12px;"></i><?php echo e($goal->goal_text); ?></div>
                            <div class="goal-desc">Weight: <?php echo e($goal->weight_percent); ?>% &middot; Self rating: <?php echo e($goal->self_rating ?? '—'); ?> &middot; Manager rating: <?php echo e($goal->manager_rating ?? '—'); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <?php if($currentAppraisal->status === 'not_started' || $currentAppraisal->status === 'self_review'): ?>
                    <form method="POST" action="<?php echo e(route('hr.appraisal.self', $currentAppraisal)); ?>" style="margin-top:16px;">
                        <?php echo csrf_field(); ?>
                        <div class="field full"><label>Self-assessment</label><textarea name="self_assessment" required><?php echo e($currentAppraisal->self_assessment); ?></textarea></div>
                        <?php $__currentLoopData = $currentAppraisal->goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="field" style="margin-top:10px;max-width:200px;">
                                <label>Self rating &mdash; <?php echo e(\Illuminate\Support\Str::limit($goal->goal_text, 24)); ?></label>
                                <input type="number" step="0.1" min="0" max="5" name="goal_ratings[<?php echo e($goal->id); ?>]" value="<?php echo e($goal->self_rating); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-actions"><button type="submit" class="btn-primary">Submit self-assessment</button></div>
                    </form>
                <?php else: ?>
                    <p class="field-hint" style="margin-top:10px;"><?php echo e($currentAppraisal->self_assessment); ?></p>
                    <?php if($currentAppraisal->manager_review): ?>
                        <p class="field-hint" style="margin-top:10px;"><strong>Manager review:</strong> <?php echo e($currentAppraisal->manager_review); ?></p>
                        <p class="field-hint">Final rating: <?php echo e($currentAppraisal->final_rating); ?> / 5</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($ownAppraisals->isNotEmpty()): ?>
            <div class="section-head" style="margin-top:28px;">
                <h2><i class="fa-solid fa-clock-rotate-left"></i>Your appraisal history</h2>
            </div>
            <div class="table-card">
                <div class="tc-body">
                    <table>
                        <thead><tr><th>Cycle</th><th>Status</th><th>Final rating</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $ownAppraisals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><b><?php echo e($a->cycle->name); ?></b></td>
                                    <td><span class="pill <?php echo e($a->status === 'completed' ? 'pill-ok' : 'pill-warn'); ?>"><?php echo e(ucfirst(str_replace('_',' ',$a->status))); ?></span></td>
                                    <td><b><?php echo e($a->final_rating ? number_format($a->final_rating,2).' / 5' : '—'); ?></b></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <?php if($toReview->isNotEmpty()): ?>
            <div class="section-head" style="margin-top:32px;">
                <h2><i class="fa-solid fa-user-pen"></i>Reviews awaiting you</h2>
                <span class="hint">Self-assessments submitted by <?php echo e($role === 'manager' ? 'your direct reports' : 'employees across the organization'); ?></span>
            </div>
            <?php $__currentLoopData = $toReview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card" style="margin-bottom:16px;">
                    <div class="widget-head">
                        <div class="wh-ico"><?php echo e(strtoupper(substr($a->employee->user->name,0,1))); ?></div>
                        <div>
                            <h3><?php echo e($a->employee->user->name); ?> &middot; <?php echo e($a->cycle->name); ?></h3>
                            <p>Self-assessment: <?php echo e(\Illuminate\Support\Str::limit($a->self_assessment ?? 'Not submitted yet', 80)); ?></p>
                        </div>
                    </div>
                    <?php if($a->self_assessment): ?>
                        <form method="POST" action="<?php echo e(route('hr.appraisal.review', $a)); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="field full"><label>Manager review</label><textarea name="manager_review" required><?php echo e($a->manager_review); ?></textarea></div>
                            <div class="field" style="max-width:160px;margin-top:10px;"><label>Final rating (0&ndash;5)</label><input type="number" step="0.1" min="0" max="5" name="final_rating" value="<?php echo e($a->final_rating); ?>" required></div>
                            <?php $__currentLoopData = $a->goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="field" style="margin-top:10px;max-width:220px;">
                                    <label>Manager rating &mdash; <?php echo e(\Illuminate\Support\Str::limit($goal->goal_text, 24)); ?></label>
                                    <input type="number" step="0.1" min="0" max="5" name="goal_ratings[<?php echo e($goal->id); ?>]" value="<?php echo e($a->manager_rating ?? $goal->self_rating); ?>">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-actions"><button type="submit" class="btn-primary">Complete review</button></div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/appraisal.blade.php ENDPATH**/ ?>