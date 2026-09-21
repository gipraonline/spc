<details class="bell">
    <summary class="bell-icon">
        <i class="fa-regular fa-bell" style="font-size:15px;"></i>
        <?php if(($navUnreadCount ?? 0) > 0): ?>
            <span class="bell-badge"><?php echo e($navUnreadCount > 9 ? '9+' : $navUnreadCount); ?></span>
        <?php endif; ?>
    </summary>
    <div class="bell-panel">
        <?php $__empty_1 = true; $__currentLoopData = ($navNotifications ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bell-item">
                <form method="POST" action="<?php echo e(route('hr.notifications.read', $n)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit">
                        <?php if(!$n->read_at): ?><strong>&bull;</strong><?php endif; ?>
                        <?php echo e($n->message); ?>

                        <div style="color:var(--text-muted);margin-top:2px;font-size:11.5px;"><?php echo e(\Illuminate\Support\Carbon::parse($n->created_at)->diffForHumans()); ?></div>
                    </button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bell-empty"><i class="fa-regular fa-bell-slash"></i>No notifications yet.</div>
        <?php endif; ?>
        <div class="bell-foot"><a href="<?php echo e(route('hr.notifications.index')); ?>">View all <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i></a></div>
    </div>
</details>
<details class="bell user-menu">
    <summary class="bell-icon user-chip-summary">
        <span class="user-chip">
            <span class="avatar"><?php echo e(strtoupper(substr($authUser->name ?? '?', 0, 1))); ?><?php echo e(strtoupper(substr(strstr($authUser->name ?? '', ' ') ?: '', 1, 1))); ?></span>
            <span class="who">
                <b><?php echo e($authUser->name); ?></b>
                <span><?php echo e($authUser->roleLabel()); ?></span>
            </span>
        </span>
    </summary>
    <div class="bell-panel user-panel">
        <div class="user-panel-head">
            <span class="avatar"><?php echo e(strtoupper(substr($authUser->name ?? '?', 0, 1))); ?><?php echo e(strtoupper(substr(strstr($authUser->name ?? '', ' ') ?: '', 1, 1))); ?></span>
            <div>
                <b><?php echo e($authUser->name); ?></b>
                <div class="card-note" style="margin:0;"><?php echo e($authUser->email); ?></div>
            </div>
        </div>
        <div class="user-panel-detail">
            <span>Role</span><span><?php echo e($authUser->roleLabel()); ?></span>
        </div>
        <?php if($employee ?? null): ?>
            <div class="user-panel-detail">
                <span>Employee code</span><span><?php echo e($employee->employee_code); ?></span>
            </div>
            <div class="user-panel-detail">
                <span>Department</span><span><?php echo e($employee->department->name ?? '—'); ?></span>
            </div>
            <div class="user-panel-detail">
                <span>Designation</span><span><?php echo e($employee->designation->title ?? '—'); ?></span>
            </div>
        <?php endif; ?>
        <a href="<?php echo e(route('hr.profile.index')); ?>" class="bell-item" style="display:block;padding:10px 9px;"><i class="fa-regular fa-user" style="margin-right:8px;color:var(--brand);"></i>View full profile</a>
        <form method="POST" action="<?php echo e(route('hr.logout')); ?>" style="padding:4px 9px 2px;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-secondary" style="width:100%;"><i class="fa-solid fa-arrow-right-from-bracket" style="margin-right:8px;color:var(--bad);"></i>Sign out</button>
        </form>
    </div>
</details>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/partials/nav-actions.blade.php ENDPATH**/ ?>