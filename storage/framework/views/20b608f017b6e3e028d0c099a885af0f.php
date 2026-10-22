
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
        <?php if (! (auth()->user()?->isAssociate())): ?>
        <a href="<?php echo e(url('/hr/modules/attendance')); ?>" class="<?php echo e($mnavIsHr ? 'active' : ''); ?>">
            <i class="fa-solid fa-users"></i><span>HR</span>
        </a>
        <?php endif; ?>

        
        <button type="button" class="mnav-more" data-spc-sidebar-toggle role="button" aria-expanded="false"
            aria-controls="main-wrapper" aria-label="Open full menu">
            <i class="fa-solid fa-ellipsis"></i><span>More</span>
        </button>
    </div>
</nav>


<div class="spc-sidebar-backdrop" data-spc-sidebar-close hidden></div>

<script>
    (function() {
        var wrapper = document.getElementById('main-wrapper');
        if (!wrapper) return;
        var backdrop = document.querySelector('.spc-sidebar-backdrop');
        var toggles = document.querySelectorAll('[data-spc-sidebar-toggle]');

        function setOpen(open) {
            wrapper.classList.toggle('show-sidebar', open);
            if (backdrop) backdrop.hidden = !open;
            toggles.forEach(function(t) {
                t.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        function isOpen() {
            return wrapper.classList.contains('show-sidebar');
        }

        toggles.forEach(function(t) {
            t.addEventListener('click', function(e) {
                e.preventDefault();
                setOpen(!isOpen());
            });
        });

        if (backdrop) {
            backdrop.addEventListener('click', function() {
                setOpen(false);
            });
        }

        // Close the drawer when a nav link inside it is tapped
        var links = wrapper.querySelectorAll('.sidebar-nav a');
        links.forEach(function(a) {
            a.addEventListener('click', function() {
                setOpen(false);
            });
        });
    })();
</script>
<?php /**PATH D:\works\git_folder\spc\resources\views/partials/mobile-bottom-nav.blade.php ENDPATH**/ ?>