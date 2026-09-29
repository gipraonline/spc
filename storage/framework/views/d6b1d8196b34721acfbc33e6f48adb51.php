
<?php
    $mnavIsHr = request()->is('hr') || request()->is('hr/*') || request()->routeIs('hr.*');
?>
<nav class="spc-mnav" aria-label="Mobile navigation">
    <div class="spc-mnav-inner">
        <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="fa-solid fa-house"></i><span>Home</span>
        </a>
        <a href="<?php echo e(route('admin.leads.index')); ?>" class="<?php echo e(request()->routeIs('admin.leads.*') ? 'active' : ''); ?>">
            <i class="fa-solid fa-bullseye"></i><span>Leads</span>
        </a>
        <a href="<?php echo e(route('admin.salesorders.index')); ?>"
           class="mnav-raise <?php echo e(request()->routeIs('admin.salesorders.*') ? 'active' : ''); ?>">
            <span class="mnav-ico"><i class="fa-solid fa-cart-shopping"></i></span><span>Orders</span>
        </a>
        <a href="<?php echo e(route('admin.franchises.index')); ?>" class="<?php echo e(request()->routeIs('admin.franchises.*') ? 'active' : ''); ?>">
            <i class="fa-solid fa-store"></i><span>Stores</span>
        </a>
        <a href="<?php echo e(url('/hr/modules/attendance')); ?>" class="<?php echo e($mnavIsHr ? 'active' : ''); ?>">
            <i class="fa-solid fa-users"></i><span>HR</span>
        </a>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\laravel\spc_new\resources\views/partials/mobile-bottom-nav.blade.php ENDPATH**/ ?>