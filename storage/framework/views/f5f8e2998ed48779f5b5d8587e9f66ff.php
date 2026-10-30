<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <h4 class="mb-1">Notifications</h4>
        <p class="text-muted small mb-4">Sales, order and other alerts. HR notifications have their own list under the HR module.</p>

        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="d-flex justify-content-between align-items-start border-bottom py-3">
                <div>
                    <span class="badge <?php echo e(match($n->category) {
                        'order' => 'bg-warning text-dark',
                        'sales' => 'bg-success',
                        default => 'bg-secondary',
                    }); ?> me-2"><?php echo e(ucfirst($n->category)); ?></span>
                    <?php if($n->title): ?><strong><?php echo e($n->title); ?></strong> &middot; <?php endif; ?>
                    <?php echo e($n->message); ?>

                    <div class="text-muted small mt-1"><?php echo e($n->created_at->diffForHumans()); ?></div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <?php if($n->link): ?>
                        <a href="<?php echo e($n->link); ?>" class="btn btn-sm btn-outline-primary">Open</a>
                    <?php endif; ?>
                    <?php if(!$n->read_at): ?>
                        <form method="POST" action="<?php echo e(route('notifications.read', $n)); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-sm btn-outline-secondary">Mark read</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted">No notifications yet.</p>
        <?php endif; ?>

        <div class="mt-3"><?php echo e($notifications->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/notifications/index.blade.php ENDPATH**/ ?>