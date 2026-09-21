
<?php
    $spcUser  = auth()->user();
    $spcMenus = collect();

    if ($spcUser) {
        try {
            $spcMenus = \App\Http\Controllers\Admin\MenuController::getMenus();
        } catch (\Throwable $e) {
            $spcMenus = collect();
        }
    }

    $isHrRoute = fn ($r) => is_string($r) && str_starts_with($r, 'hr.');

    $spcHasHrGroup = $spcMenus->contains(function ($p) use ($isHrRoute) {
        return $isHrRoute($p->route_name)
            || $p->children->contains(fn ($c) => $isHrRoute($c->route_name));
    });

    $spcUrl = function ($r) {
        return ($r && \Illuminate\Support\Facades\Route::has($r)) ? route($r) : '#';
    };

    // Active when the route matches exactly, or (for names like admin.sales.index)
    // when any route in the same section is current, e.g. admin.sales.*
    $spcActive = function ($r) {
        if (! $r) {
            return false;
        }
        if (request()->routeIs($r)) {
            return true;
        }

        return substr_count($r, '.') >= 2
            && request()->routeIs(\Illuminate\Support\Str::beforeLast($r, '.').'.*');
    };

    // Fallback HR links (only used when the DB menu has no HR group)
    $hrLucide = [
        'attendance' => 'clock', 'leave' => 'calendar-days', 'payroll' => 'wallet',
        'recruitment' => 'user-plus', 'employee-records' => 'users', 'appraisal' => 'trophy',
        'pf-gratuity' => 'piggy-bank', 'incentive' => 'medal', 'reports' => 'bar-chart-3',
        'system' => 'shield', 'wfh' => 'home', 'announcements' => 'megaphone',
        'support' => 'help-circle', 'settings' => 'settings', 'organization' => 'building-2',
    ];
    $hrGroupOrder = ['People', 'Workforce', 'Money', 'Growth', 'Records', 'Comms', 'System'];
    $hrGrouped = [];
    foreach (($modules ?? []) as $key => $module) {
        if ($key === 'my-profile') {
            continue;
        }
        $hrGrouped[$module['group'] ?? 'Other'][$key] = $module;
    }
?>

<aside class="sidebar spc-side">
    <div class="spc-side-inner">

        <div class="spc-brand">
            <a href="<?php echo e(\Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/')); ?>">
                <img src="<?php echo e(asset('dist/images/logos/spclogo.png')); ?>" alt="SPC">
            </a>
            <button type="button" class="sidebar-close" onclick="document.getElementById('appShell').classList.remove('sidebar-open')" aria-label="Close menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="spc-nav">

            
            <?php $__currentLoopData = $spcMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($parent->children->isEmpty()): ?>
                    <a class="spc-link <?php echo e($spcActive($parent->route_name) ? 'active' : ''); ?>" href="<?php echo e($spcUrl($parent->route_name)); ?>">
                        <i data-lucide="<?php echo e($parent->icon); ?>"></i>
                        <span><?php echo e($parent->name); ?></span>
                    </a>
                <?php else: ?>
                    <p class="spc-cap"><?php echo e($parent->name); ?></p>
                    <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="spc-link <?php echo e($spcActive($child->route_name) ? 'active' : ''); ?>" href="<?php echo e($spcUrl($child->route_name)); ?>">
                            <i data-lucide="<?php echo e($child->icon); ?>"></i>
                            <span><?php echo e($child->name); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if (! ($spcHasHrGroup)): ?>
                <p class="spc-cap">HR</p>
                <a class="spc-link <?php echo e(request()->routeIs('hr.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('hr.dashboard')); ?>">
                    <i data-lucide="layout-dashboard"></i><span>HR Dashboard</span>
                </a>
                <a class="spc-link <?php echo e(request()->routeIs('hr.profile.index') ? 'active' : ''); ?>" href="<?php echo e(route('hr.profile.index')); ?>">
                    <i data-lucide="user"></i><span>My Profile</span>
                </a>

                <?php $__currentLoopData = $hrGroupOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(empty($hrGrouped[$groupName])) continue; ?>
                    <p class="spc-cap"><?php echo e($groupName); ?></p>
                    <?php $__currentLoopData = $hrGrouped[$groupName]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="spc-link <?php echo e((($moduleKey ?? null) === $key) ? 'active' : ''); ?>" href="<?php echo e(url('/hr/modules/'.$key)); ?>">
                            <i data-lucide="<?php echo e($hrLucide[$key] ?? 'layout-grid'); ?>"></i>
                            <span><?php echo e($module['nav_label'] ?? $module['title']); ?></span>
                            <?php if(!empty($navBadges[$key] ?? null)): ?>
                                <span class="spc-badge"><?php echo e($navBadges[$key]); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </nav>
    </div>
</aside>

<style>
    /* Restyle the HR shell's sidebar to match the SPC main site.
       Change these four values to tune the look. */
    .sidebar.spc-side{
        --spc-primary:#5D87FF; --spc-primary-soft:#ECF2FF; --spc-text:#2A3547; --spc-line:#EBF1F6;
        background:#fff; color:var(--spc-text);
        border-right:1px solid var(--spc-line); box-shadow:none;
    }
    .sidebar.spc-side::after{display:none;}
    .spc-side-inner{display:flex; flex-direction:column; min-height:100%;}

    .spc-brand{position:relative; display:flex; align-items:center; justify-content:center; padding:22px 20px 12px;}
    .spc-brand img{height:54px; max-width:180px; width:auto; object-fit:contain; display:block;}
    .sidebar.spc-side .sidebar-close{
        background:#F5F7FA; border:1px solid var(--spc-line); color:var(--spc-text);
        position:absolute; right:12px; top:50%; transform:translateY(-50%);
    }

    .spc-nav{padding:4px 16px 28px; display:flex; flex-direction:column; gap:2px;}
    .spc-cap{
        margin:22px 8px 8px; font-family:var(--font-body); font-size:12px; font-weight:600;
        letter-spacing:.02em; text-transform:uppercase; color:var(--spc-text);
    }
    .spc-link{
        display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:8px;
        font-family:var(--font-body); font-size:14px; font-weight:500; line-height:1.3;
        color:var(--spc-text); text-decoration:none; transition:background .15s, color .15s;
    }
    .spc-link svg{width:20px; height:20px; flex-shrink:0; stroke-width:1.75;}
    .spc-link span{min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
    .spc-link:hover{background:var(--spc-primary-soft); color:var(--spc-primary);}
    .spc-link.active{background:var(--spc-primary); color:#fff;}
    .spc-badge{
        margin-left:auto; background:#FA896B; color:#fff; font-size:10px; font-weight:700;
        line-height:1; padding:4px 8px; border-radius:99px;
    }
</style><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/sidebar.blade.php ENDPATH**/ ?>