<?php $__env->startPush('styles'); ?>
<style>
.card form {
    background: #fff;
    border-bottom: 1px solid #eee;
}

.form-label {
    margin-bottom: 8px;
}

.form-control {
    height: 45px;
}

.btn {
    height: 45px;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Crypt;
?>
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Sales Orders</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.create')): ?>
        <a href="<?php echo e(route('admin.salesorders.create')); ?>" class="btn buttonSpc">Add Sales Entry</a>
        <?php endif; ?>
    </div>





    <div class="card-body p-4">

        <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e($message); ?>

        </div>
        <?php endif; ?>

        <form method="GET" action="<?php echo e(route('admin.salesorders.index')); ?>" class="p-2">
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body">

                    <!-- Search By Farm Care Advisor Name or Code-->
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Farm Care Advisor name/Code" value="<?php echo e(request('search')); ?>">
                        </div>

                        <!-- From Date -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>"
                                class="form-control">
                        </div>

                        <!-- To Date -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="form-control">
                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-3 col-md-3 pt-4 d-flex gap-2">
                            <button class="btn buttonSpc">Filter Report</button>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.export')): ?>
                            <button type="submit" name="export" value="excel" class="btn btn-success">
                                <i class="ti ti-file-export me-1"></i>
                                Export to Excel
                            </button>
                            <?php endif; ?>
                            <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Order Id</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Address</th>
                        <?php if(isset($isFarmCareAdvisor)): ?>
                        <th scope="col">Farm Care Advisor</th>
                        <?php endif; ?>
                        <th scope="col">Franchise</th>
                        <th scope="col">Payment Image</th>
                        <th scope="col">Payment status</th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sales-orders.view-details', 'sales-orders.edit', 'sales-orders.delete'])): ?>
                        <th scope="col">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="border-bottom-0 text-center">
                            <span class="fw-normal"><?php echo e($sales->firstItem() + $key); ?></span>
                        </td>
                        <td><?php echo e($sale?->c_order_no ?? 'N/A'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($sale->d_date)->format('d M Y')); ?></td>
                        <td><?php echo e($sale?->c_customer_name ?? 'N/A'); ?></td>
                        <td><?php echo e($sale?->c_customer_address ?? 'N/A'); ?></td>

                        <?php if(isset($isFarmCareAdvisor)): ?>
                         <td>
                            <div class="d-flex align-products-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?php echo e($sale->employee?->c_employee_name ?? 'N/A'); ?></h6>
                                    <span class="fs-2 text-muted"><?php echo e($sale->employee?->c_employee_code ?? ''); ?></span>
                                </div>
                            </div>
                        </td>
                        <?php endif; ?>
                        <td>
                            <div class="d-flex align-products-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?php echo e($sale->franchise?->c_store_name ?? 'N/A'); ?></h6>
                                    <span class="fs-2 text-muted"><?php echo e($sale->franchise?->c_store_code ?? ''); ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                           <?php if($sale->payment_image): ?>
                                <a href="<?php echo e(asset('uploads/payment_images/' . $sale->payment_image)); ?>"
                                target="_blank">

                                    <img src="<?php echo e(asset('uploads/payment_images/' . $sale->payment_image)); ?>"
                                        width="60"
                                        height="60"
                                        style="object-fit: cover; border-radius: 5px; cursor: pointer;">

                                </a>
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                         <td><?php echo e($sale?->payment_image ? "Confirmed" : 'Pending'); ?></td>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sales-orders.view-details', 'sales-orders.edit', 'sales-orders.delete'])): ?>
                        <td>
                            <div class="dropdown dropstart">
                                <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.view-details')): ?>
                                        <a class="dropdown-item d-flex align-products-center gap-3"
                                            href="<?php echo e(route('admin.salesorders.show', Crypt::encryptString($sale->n_sl_no))); ?>">
                                            <i class="fs-4 ti ti-eye"></i>View Details
                                        </a>
                                        <?php endif; ?>
                                    </li>
                                    <li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.edit')): ?>
                                        <a class="dropdown-item d-flex align-products-center gap-3"
                                            href="<?php echo e(route('admin.salesorders.edit', Crypt::encryptString($sale->n_sl_no))); ?>">
                                            <i class="fs-4 ti ti-edit"></i>Edit
                                        </a>
                                        <?php endif; ?>
                                    </li>
                                    <li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.delete')): ?>
                                        <form action="<?php echo e(route('admin.salesorders.destroy', Crypt::encryptString($sale->n_sl_no))); ?>"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="dropdown-item d-flex align-products-center gap-3 text-danger">
                                                <i class="fs-4 ti ti-trash"></i>Delete
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center">No sales records found</td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                </table>
                    </div>
                    <div class="mt-3">
                        <?php echo e($sales->links()); ?>

                    </div>
                </div>
            </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/index.blade.php ENDPATH**/ ?>