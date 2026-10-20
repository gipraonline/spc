
<?php
    $moved = $fieldLog->distanceMovedKm();
    $maxAcc = (int) config('spc.field_log_max_accuracy_m', 200);
    $row = function ($label, $has, $lat, $lng, $acc, $url) use ($maxAcc) {
        return compact('label', 'has', 'lat', 'lng', 'acc', 'url', 'maxAcc');
    };
    $rows = [
        $row('Check in', $fieldLog->hasCheckInLocation(), $fieldLog->check_in_latitude, $fieldLog->check_in_longitude, $fieldLog->check_in_accuracy_m, $fieldLog->checkInMapUrl()),
        $row('Check out', $fieldLog->hasCheckOutLocation(), $fieldLog->check_out_latitude, $fieldLog->check_out_longitude, $fieldLog->check_out_accuracy_m, $fieldLog->checkOutMapUrl()),
    ];
?>

<div class="border rounded p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0"><i class="ti ti-map-pin"></i> Location (GPS)</h6>
        <?php if($moved !== null): ?>
            <small class="text-muted"><?php echo e(number_format($moved, 2)); ?> km between check-in and check-out</small>
        <?php endif; ?>
    </div>

    <div class="row g-3">
        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6">
                <small class="text-muted d-block"><?php echo e($r['label']); ?></small>
                <?php if($r['has']): ?>
                    <a href="<?php echo e($r['url']); ?>" target="_blank" rel="noopener">
                        <?php echo e(number_format((float) $r['lat'], 5)); ?>, <?php echo e(number_format((float) $r['lng'], 5)); ?>

                        <i class="ti ti-external-link"></i>
                    </a>
                    <?php if($r['acc'] !== null): ?>
                        <span class="badge <?php echo e($r['acc'] > $r['maxAcc'] ? 'bg-warning text-dark' : 'bg-light text-dark border'); ?>">&plusmn;<?php echo e($r['acc']); ?> m</span>
                        <?php if($r['acc'] > $r['maxAcc']): ?><small class="text-warning">low accuracy</small><?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-muted">Not captured</span>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/field-log/_location-card.blade.php ENDPATH**/ ?>