<?php $__env->startSection('content'); ?>
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Add Sales Orders</h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.salesorders.store')); ?>">
            <?php echo csrf_field(); ?>

            <!-- Row 1 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Farm Care Advisor *</label>
                    <select name="n_employee_id" class="form-select" required>
                        <option value="">Select</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->n_employee_id); ?>">
                            <?php echo e($employee->c_employee_name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Product *</label>
                    <select name="n_product_id" class="form-select" required>
                        <option value="">Select Product</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($product->n_product_id); ?>">
                            <?php echo e($product->c_product_name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Bill No *</label>
                    <input type="text" name="c_bill_no" class="form-control" value="<?php echo e(old('c_bill_no')); ?>" required>
                    <?php $__errorArgs = ['c_bill_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity *</label>
                    <input type="number" name="n_quantity" class="form-control" value="<?php echo e(old('n_quantity')); ?>" min="1"
                        required>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date *</label>
                    <input type="date" name="d_date" class="form-control" value="<?php echo e(old('d_date', date('Y-m-d'))); ?>"
                        required>
                </div>
            </div>

            <!-- Section 4: Contact & Status -->
            <div class="form-section-header" >
                <i class="ti ti-mail fs-5"></i> Contact & Status
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_customer_name" class="form-label">Customer Name *</label>
                    <input type="text" id="c_customer_name" name="c_customer_name" value="<?php echo e(old('c_customer_name')); ?>"
                        data-message="Please add Account Number" class="form-control mandatory" placeholder="Customer Name">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="c_customer_email" class="form-label">Customer Email *</label>
                    <input type="text" id="c_customer_email" name="c_customer_email" value="<?php echo e(old('c_customer_email')); ?>"
                        data-message="Please enter IFSC Code" class="form-control mandatory"
                        placeholder="Enter Customer Email">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="account_number" class="form-label">Customer Address *</label>
                    <input type="text" id="account_number" name="account_number" value="<?php echo e(old('c_customer_neme')); ?>"
                        data-message="Please add Account Number" class="form-control mandatory" placeholder="ACC-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">Customer Mobile *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" value="<?php echo e(old('c_customer_email')); ?>"
                        data-message="Please enter IFSC Code" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">District</label>
                    <select class="form-select" id="state" name="state">
                        <option selected>Select District</option>
                        <option value="1">Admin</option>
                        <option value="2">Franchise</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" id="state" name="state">
                        <option selected>Select State</option>
                        <option value="1">Admin</option>
                        <option value="2">Franchise</option>
                    </select>
                </div>
            </div>


           <div class="row mb-3 align-items-center">
                <label class="col-md-2 col-form-label">
                    Mode of Payment
                </label>

                <div class="col-md-9">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_status" id="cod" value="cash_on_delivery" checked>
                        <label class="form-check-label" for="cod">
                            Cash on Delivery
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_status" id="upi" value="UPI">
                        <label class="form-check-label" for="upi">
                            UPI
                        </label>
                    </div>
                </div>
            </div>


             <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">Nearest Franchise</label>
                    <select class="form-select" id="state" name="nearest_franchise_id">
                        <option selected>Select Franchise</option>
                        <option value="1">Admin</option>
                        <option value="2">Franchise</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">Payment Status</label>
                    <select class="form-select" id="state" name="payment_status">
                        <option selected>Select Status</option>
                        <option value="1">Ordered</option>
                        <option value="2">Paid</option>
                        <option value="3">Approved</option>
                        <option value="4">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">Delivory Status</label>
                    <select class="form-select" id="state" name="delivery_status">
                        <option selected>Select Delivery Status</option>
                        <option value="1">Ordered</option>
                        <option value="2">Shipped</option>
                        <option value="3">Delivered</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>