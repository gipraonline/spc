

<?php $__env->startSection('content'); ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Field Log History
        </h5>

    </div>

    <div class="card-body">

        
        <form method="GET">

            <div class="row">

                <div class="col-md-3">
                    <input type="date" name="from_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <input type="date" name="to_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Checked In">Working</option>
                        <option value="Checked Out">Checked Out</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn buttonSpc">
                        Search
                    </button>
                </div>

            </div>

        </form>

        <hr>

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $fieldLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$fieldLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td><?php echo e($key+1); ?></td>

                        <td><?php echo e($fieldLog->work_date->format('d-m-Y')); ?></td>

                        <td><?php echo e($fieldLog->admin->c_name); ?></td>

                        <td><?php echo e($fieldLog->check_in_time->format('h:i A')); ?></td>

                        <td>

                            <?php echo e(optional($fieldLog->check_out_time)->format('h:i A') ?? '--'); ?>


                        </td>

                        <td>

                            <?php if($fieldLog->status=='Checked Out'): ?>

                            <span class="badge bg-success">
                                Checked Out
                            </span>

                            <?php else: ?>

                            <span class="badge bg-warning text-dark">
                                Working
                            </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="<?php echo e(route('admin.admin-log.show',$fieldLog->id)); ?>" class="btn btn-sm buttonSpc">

                                View

                            </a>

                        </td>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="7" class="text-center">

                            No Records Found

                        </td>

                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            <?php echo e($fieldLogs->links()); ?>


        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/admin-log/index.blade.php ENDPATH**/ ?>