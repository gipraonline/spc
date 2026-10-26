
<?php
    $hrMnavSeg = request()->segment(2); // /hr/modules/{seg}
?>
<nav class="spc-mnav" aria-label="Mobile navigation">
    <div class="spc-mnav-inner">
        <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="fa-solid fa-house"></i><span>Home</span>
        </a>
        <a href="<?php echo e(url('/hr/modules/attendance')); ?>" class="<?php echo e($hrMnavSeg === 'attendance' ? 'active' : ''); ?>">
            <i class="fa-solid fa-stopwatch"></i><span>Attendance</span>
        </a>
        <a href="<?php echo e(url('/hr/modules/leave')); ?>"
           class="mnav-raise <?php echo e($hrMnavSeg === 'leave' ? 'active' : ''); ?>">
            <span class="mnav-ico"><i class="fa-solid fa-calendar-days"></i></span><span>Leave</span>
        </a>
        <a href="<?php echo e(url('/hr/modules/payroll')); ?>" class="<?php echo e($hrMnavSeg === 'payroll' ? 'active' : ''); ?>">
            <i class="fa-solid fa-wallet"></i><span>Payroll</span>
        </a>
        <a href="<?php echo e(route('hr.profile.index')); ?>" class="<?php echo e(request()->routeIs('hr.profile.*') ? 'active' : ''); ?>">
            <i class="fa-solid fa-user"></i><span>Profile</span>
        </a>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\laravel\spc_new\resources\views/hr/partials/mobile-bottom-nav.blade.php ENDPATH**/ ?>