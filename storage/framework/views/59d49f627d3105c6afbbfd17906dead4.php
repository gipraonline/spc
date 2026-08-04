<?php $__env->startSection('content'); ?>
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #F7941E;
    --bg-light: #fbfbfb;
    --text-dark: #2d3436;
    --border-color: #e9ecef;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.form-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header-custom {
    padding: 1.5rem 2rem;
    background: #fff;
    border-bottom: 2px solid var(--bg-light);
    border-top: 4px solid var(--primary-green);
}

.card-title-custom {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.form-body {
    padding: 2rem;
}

.form-section-title {
    font-size: 15px;
    font-weight: 800;
    color: #1b3e86;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control,
.form-select {
    border-radius: 8px;
    border: 1.5px solid #b7bec5;
    padding: 0.75rem 1rem;
    background-color: #fcfcfc;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    box-shadow: 0 0 0 4px rgba(57, 181, 74, 0.1);
    background-color: #fff;
}


#incentive_percentages {
    background-color: #f7fafc;
    border: 1.5px solid #edf2f7;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.incentive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.incentive-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
    background: #fff;
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

/* Percentage input styling */
.input-group-text-custom {
    background: none;
    border: none;
    padding-left: 5px;
    font-weight: 600;
    color: #718096;
}

.btn-create-custom {
    background: var(--primary-green);
    border: none;
    padding: 0.8rem 2.5rem;
    border-radius: 10px;
    font-weight: 700;
    color: #fff;
    transition: all 0.3s ease;
}

.btn-create-custom:hover {
    background: #ce2a2a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(57, 181, 74, 0.3);
}

.btn-cancel-custom {
    border-radius: 10px;
    padding: 0.8rem 2rem;
    font-weight: 600;
}


.checkbox-container {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    background: #fff;
    border: 1px solid #edf2f7;
    border-radius: 10px;
    width: fit-content;
    cursor: pointer;
}

#active_incentive {
    accent-color: var(--primary-green);
    width: 18px;
    height: 18px;
}
</style>

<div class="card form-card mb-4">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="card-title-custom">Add Product</h5>
    </div>

    <div class="form-body">
        <form id="frm_create" method="POST" action="<?php echo e(route('admin.products.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="row g-4">
                <!-- General Information Section -->
                <div class="col-12">
                    <div class="form-section-title">
                        <i class="ti ti-info-circle"></i> Basic Information
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="c_product_name" class="form-label">Product Name *</label>
                    <input type="text" id="c_product_name" data-message="Please enter Product Name"
                        name="c_product_name" value="<?php echo e(old('c_product_name')); ?>" class="form-control mandatory"
                        placeholder="Enter product name">

                    <?php $__errorArgs = ['c_product_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label for="c_product_code" class="form-label">Product Code *</label>
                    <input type="text" id="c_product_code" data-message="Please enter Product Code"
                        name="c_product_code" value="<?php echo e(old('c_product_code')); ?>" class="form-control mandatory"
                        placeholder="Enter product code">
                    <div id="code_error" class="text-danger mt-1 fs-2">
                        <?php $__errorArgs = ['c_product_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="col-12">
                    <div class="form-section-title mt-3">
                        <i class="ti ti-currency-dollar"></i> Financial Details
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="n_mrp" class="form-label">MRP *</label>
                    <input type="text" id="n_mrp" name="n_mrp" data-message="Please enter Maximum Retail Price"
                        value="<?php echo e(old('n_mrp')); ?>" class="form-control mandatory" placeholder="0.00">
                    <?php $__errorArgs = ['n_mrp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label for="n_purchase_price" class="form-label">Purchase Price *</label>
                    <input type="text" id="n_purchase_price" name="n_purchase_price"
                        data-message="Please enter Purchase Price" value="<?php echo e(old('n_purchase_price')); ?>"
                        class="form-control mandatory" placeholder="0.00">
                    <?php $__errorArgs = ['n_purchase_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label for="n_selling_price" class="form-label">Selling Price *</label>
                    <input type="text" id="n_selling_price" name="n_selling_price"
                        data-message="Please enter Selling Price" value="<?php echo e(old('n_selling_price')); ?>"
                        class="form-control mandatory" placeholder="0.00">
                    <div id="selling_error" class="text-danger mt-1 fs-2">
                        <?php $__errorArgs = ['n_selling_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="c_status" class="form-label">Status *</label>
                    <select id="c_status" name="c_status" data-message="Please enter Status"
                        class="form-select mandatory">
                        <option value="">Select Status</option>
                        <option value="Y" <?php echo e(old('c_status') === 'Y' ? 'selected' : ''); ?>>Active</option>
                        <option value="N" <?php echo e(old('c_status') === 'N' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                    <?php $__errorArgs = ['c_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                

<div class="col-12 mt-4 pt-3 border-top d-flex gap-3">
    <button type="submit" id="btn_create" class="btn btn-create-custom incentive_perc">Create
        Item</button>
    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary btn-cancel-custom">Cancel</a>
</div>
</div>
</form>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/products/create.blade.php ENDPATH**/ ?>