<?php $__env->startSection('content'); ?>
<div class="card w-100 position-relative overflow-hidden mb-4">
  <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
    <h5 class="card-title fw-semibold mb-0 lh-sm">Edit Sales Entry</h5>
  </div>
  <div class="card-body p-4">
    <form method="POST" action="<?php echo e(route('admin.sales.update', $sale->n_slno)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="n_employee_id" class="form-label">Employee *</label>
                <select id="n_employee_id" name="n_employee_id" required class="form-select">
                    <option value="">Select Employee</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->n_employee_id); ?>" <?php echo e(old('n_employee_id', $sale->n_employee_id) == $employee->n_employee_id ? 'selected' : ''); ?>>
                            <?php echo e($employee->c_employee_name); ?> (<?php echo e($employee->c_employee_code); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['n_employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6 mb-3">
                <label for="n_product_id" class="form-label">Product *</label>
                <select id="n_product_id" name="n_product_id" required class="form-select">
                    <option value="">Select Product</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($product->n_product_id); ?>" <?php echo e(old('n_product_id', $sale->n_product_id) == $product->n_product_id ? 'selected' : ''); ?>>
                            <?php echo e($product->c_product_name); ?> (Price: <?php echo e($product->n_selling_price); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['n_product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="n_quantity" class="form-label">Quantity *</label>
                <input type="number" id="n_quantity" name="n_quantity" value="<?php echo e(old('n_quantity', $sale->n_quantity)); ?>" min="1" required 
                    class="form-control">
                <?php $__errorArgs = ['n_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="d_date" class="form-label">Date *</label>
                <input type="date" id="d_date" name="d_date" value="<?php echo e(old('d_date', $sale->d_date)); ?>" required 
                    class="form-control">
                <?php $__errorArgs = ['d_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo e(route('admin.sales.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/edit.blade.php ENDPATH**/ ?>