
<div class="topbar">
    <div class="topbar-left">
        <button type="button" class="hamburger" onclick="document.getElementById('appShell').classList.toggle('sidebar-open')" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div>
            <div class="eyebrow"><?php echo e($eyebrow ?? 'HR Management Module'); ?></div>
            <h1><?php echo e($title); ?></h1>
        </div>
    </div>
    <div class="topbar-actions">
        <?php echo $__env->make('hr.partials.nav-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>


<?php if(isset($heroIcon) && $heroIcon): ?>
<div class="hero-band">
<div class="page-hero">
    <div class="page-hero-ico"><i class="<?php echo e($heroIcon); ?>"></i></div>
    <div class="page-hero-text">
        <?php if(!empty($heroSummary)): ?><p><?php echo e($heroSummary); ?></p><?php endif; ?>
    </div>
    <?php if(!empty($heroStats)): ?>
    <div class="page-hero-stats">
        <?php $__currentLoopData = array_filter($heroStats); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!is_array($s)) continue; ?>
            <div class="ph-stat">
                <b><?php if(!empty($s['icon'])): ?><i class="<?php echo e($s['icon']); ?>"></i><?php endif; ?><?php echo e($s['value']); ?></b>
                <span><?php echo e($s['label']); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>
</div>
<?php endif; ?>

<?php if(session('status')): ?>
    <div class="flash" style="margin:22px 34px 0;"><?php echo e(session('status')); ?></div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="flash-errors" style="margin:22px 34px 0;">
        <strong>Please check the form:</strong>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/topbar.blade.php ENDPATH**/ ?>