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

        
        <div class="card" style="max-width:680px;">

            <div class="widget-head">
                <div class="wh-ico">
                    <i class="fa-solid fa-sliders"></i>
                </div>

                <div>
                    <h3>Policy &amp; payroll configuration</h3>
                    <p>
                        Every change here is written to the audit log.
                    </p>
                </div>
            </div>


            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hr-settings.edit')): ?>

                <form
                    method="POST"
                    action="<?php echo e(route('hr.settings.update')); ?>"
                >
                    <?php echo csrf_field(); ?>

                    <div class="field-grid">

                        <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="field">

                                <label>
                                    <?php echo e($s['label']); ?>

                                </label>

                                <input
                                    type="<?php echo e($s['type']); ?>"
                                    name="<?php echo e($s['key']); ?>"
                                    value="<?php echo e(old($s['key'], $s['value'])); ?>"
                                >

                                <?php $__errorArgs = [$s['key']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger">
                                        <?php echo e($message); ?>

                                    </small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>


                    <div class="form-actions">

                        <button
                            type="submit"
                            class="btn-primary"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save settings
                        </button>

                    </div>

                </form>

            <?php else: ?>

                
                <div class="field-grid">

                    <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="field">

                            <label>
                                <?php echo e($s['label']); ?>

                            </label>

                            <input
                                type="<?php echo e($s['type']); ?>"
                                value="<?php echo e($s['value']); ?>"
                                disabled
                            >

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            <?php endif; ?>

        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc_new\resources\views/hr/modules/settings.blade.php ENDPATH**/ ?>