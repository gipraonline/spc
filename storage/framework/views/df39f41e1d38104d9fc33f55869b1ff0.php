<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Comms',
        'heroIcon' => 'fa-solid fa-bullhorn',
        'heroSummary' => 'Company-wide and role-targeted announcements, newest first.',
        'heroStats' => ($role === 'hr_admin' || $role === 'super_admin') ? [
            ['label' => 'Published', 'icon' => 'fa-solid fa-paper-plane', 'value' => $totalPublished],
            ['label' => 'Unread', 'icon' => 'fa-regular fa-envelope-open', 'value' => $unreadTotal],
        ] : [
            ['label' => 'Latest', 'icon' => 'fa-regular fa-envelope-open', 'value' => $unreadTotal . ' new'],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">
        <?php if($role === 'hr_admin' || $role === 'super_admin'): ?>
            <div class="card">
                <div class="widget-head">
                    <div class="wh-ico"><i class="fa-solid fa-pen-nib"></i></div>
                    <div>
                        <h3>Publish an announcement</h3>
                        <p>Delivered instantly to the notification bell of the chosen audience.</p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('hr.announcements.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="field-grid">
                        <div class="field full"><label>Title</label><input name="title" placeholder="e.g. Office closed for Onam" required></div>
                        <div class="field full"><label>Message</label><textarea name="body" placeholder="Announcement details" required></textarea></div>
                        <div class="field">
                            <label>Audience</label>
                            <select name="audience_role">
                                <option value="all">Everyone</option>
                                <option value="employee">Employees</option>
                                <option value="manager">Reporting Managers</option>
                                <option value="hr_admin">HR Admins</option>
                                <option value="super_admin">Super Admins</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions"><button type="submit" class="btn-primary">Publish announcement</button></div>
                </form>
            </div>
        <?php endif; ?>

        <div class="section-head" style="margin-top:30px;">
            <h2><i class="fa-solid fa-stream"></i>Latest announcements</h2>
        </div>
        <?php if($announcements->isEmpty()): ?>
            <div class="table-card"><div class="tc-body">
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-bullhorn"></i></div>
                    <b>No announcements yet</b>
                    <span>Company news will appear here as soon as it's published.</span>
                </div>
            </div></div>
        <?php endif; ?>
        <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card" style="margin-bottom:14px;">
                <div class="widget-head" style="margin-bottom:10px;">
                    <div class="wh-ico"><i class="fa-solid fa-comment-dots"></i></div>
                    <div>
                        <h3><?php echo e($a->title); ?>

                            <?php if (! (in_array($a->id, $readIds))): ?>
                                <span class="pill pill-warn" style="margin-left:8px;vertical-align:middle;">New</span>
                            <?php endif; ?>
                        </h3>
                        <p><?php echo e($a->createdBy->name ?? 'HR'); ?> &middot; <?php echo e(\Illuminate\Support\Carbon::parse($a->published_at)->format('d M Y, H:i')); ?>

                            <?php if($a->audience_role !== 'all'): ?> &middot; <i class="fa-solid fa-user-group" style="font-size:9px;"></i> <?php echo e(ucfirst(str_replace('_',' ',$a->audience_role))); ?> only <?php endif; ?>
                        </p>
                    </div>
                </div>
                <p style="font-size:13.5px;line-height:1.65;margin:0;"><?php echo e($a->body); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php echo e($announcements->links()); ?>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/announcements.blade.php ENDPATH**/ ?>