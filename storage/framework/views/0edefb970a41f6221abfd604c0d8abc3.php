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

/* Main Record Architecture */
.product-edit-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header-styled {
    padding: 1.5rem 2rem;
    border-bottom: 2px solid var(--bg-light);
    border-top: 4px solid var(--primary-green);
}

.section-title {
    font-size: 0.85rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--primary-green);
    letter-spacing: 1px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f5f9;
}

/* Form Fields */
.form-label {
    font-weight: 700;
    color: #4a5568;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border-radius: 10px;
    padding: 0.75rem 1rem;

    background-color: #fdfdfe;
    transition: all 0.25s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(57, 181, 74, 0.08);
}

/* Incentive Split Styling */
#incentive_percentages {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.incentive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 1rem;
}

.incentive-box {
    background: #fff;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #edf2f7;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.pct-label {
    font-weight: 800;
    color: #64748b;
    font-size: 0.7rem;
    text-transform: uppercase;
}

.pct-input-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}

.pct-symbol {
    font-weight: 800;
    color: #94a3b8;
    font-size: 0.9rem;
}

/* Footer Buttons */
.btn-update-item {
    background: var(--primary-green);
    border: none;
    padding: 12px 35px;
    border-radius: 10px;
    font-weight: 700;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(57, 181, 74, 0.2);
}

.btn-update-item:hover {
    background: #1b3e86;
    transform: translateY(-2px);
}

/* Toggle Switches style */
.toggle-container {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    background: #fff;
    border: 1px solid #edf2f7;
    border-radius: 10px;
    width: fit-content;
}

#active_incentive {
    accent-color: var(--primary-green);
    width: 18px;
    height: 18px;
}
</style>

<div class="card product-edit-card mb-4">
    <div class="card-header-styled">
        <h5 class="fw-bold mb-0" style="color: var(--text-dark);">Edit Item Details</h5>
    </div>
    <div class="card-body p-4 p-md-5">
        <form method="POST" id="frm_create" action="<?php echo e(route('admin.products.update', $product)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="section-title">
                <i class="ti ti-info-circle me-1"></i> Basic Information
            </div>
            <div class="row g-4 mb-4">

                <div class="col-md-6">
                    <label for="n_category_id" class="form-label">
                        Category *
                    </label>

                    <select id="n_category_id" name="n_category_id" class="form-select mandatory"
                        data-message="Please select Category">
                        <option value="">Select Category</option>

                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->n_category_id); ?>"
                            <?php echo e(old('n_category_id', $product->n_category_id) == $category->n_category_id ? 'selected' : ''); ?>>
                            <?php echo e($category->c_category_name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <?php $__errorArgs = ['n_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                <div class="col-md-6">
                    <label for="c_product_name" class="form-label">Product Name *</label>
                    <input type="text" id="c_product_name" data-message="Please enter Product Name"
                        name="c_product_name" value="<?php echo e(old('c_product_name', $product->c_product_name)); ?>"
                        class="form-control mandatory">
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
                        name="c_product_code" value="<?php echo e(old('c_product_code', $product->c_product_code)); ?>"
                        class="form-control mandatory">
                    <?php $__errorArgs = ['c_product_code'];
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
            </div>

            <div class="section-title">
                <i class="ti ti-currency-dollar me-1"></i> Pricing & Status
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="n_mrp" class="form-label">MRP *</label>
                    <input type="number" id="n_mrp" data-message="Please enter Maximum Retail Price" name="n_mrp"
                        value="<?php echo e(old('n_mrp', $product->n_mrp)); ?>" step="0.01" class="form-control mandatory">
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
                    <input type="number" id="n_purchase_price" data-message="Please enter Purchase Price"
                        name="n_purchase_price" value="<?php echo e(old('n_purchase_price', $product->n_purchase_price)); ?>"
                        step="0.01" class="form-control mandatory">
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
                    <input type="number" id="n_selling_price" data-message="Please enter Selling Price"
                        name="n_selling_price" value="<?php echo e(old('n_selling_price', $product->n_selling_price)); ?>"
                        step="0.01" class="form-control mandatory ">
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
                <!-- 
                <div class="col-md-6">
                    <label for="c_status" class="form-label">Operational Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory">
                        <option value="Y" <?php echo e(old('c_status', $product->c_status) === 'Y' ? 'selected' : ''); ?>>
                            Active
                        </option>
                        <option value="N" <?php echo e(old('c_status', $product->c_status) === 'N' ? 'selected' : ''); ?>>Not
                            Inactive</option>
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
                </div> -->

                <div class="col-md-6">
                    <label for="c_unit" class="form-label">Unit *</label>
                    <input type="text" id="c_unit" name="c_unit" value="<?php echo e(old('c_unit', $product->c_unit)); ?>"
                        class="form-control mandatory" data-message="Please enter Unit" placeholder="e.g. 1 ltr">

                    <?php $__errorArgs = ['c_unit'];
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
                    <label for="c_hsn_code" class="form-label">HSN Code *</label>
                    <input type="text" id="c_hsn_code" name="c_hsn_code"
                        value="<?php echo e(old('c_hsn_code', $product->c_hsn_code)); ?>" class="form-control mandatory"
                        data-message="Please enter HSN Code" placeholder="e.g. 31010099">

                    <?php $__errorArgs = ['c_hsn_code'];
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
                    <label for="n_gst_percentage" class="form-label">GST % *</label>
                    <input type="number" id="n_gst_percentage" name="n_gst_percentage"
                        value="<?php echo e(old('n_gst_percentage', $product->n_gst_percentage)); ?>" class="form-control mandatory"
                        data-message="Please enter GST Percentage" placeholder="e.g. 5" min="0" max="100" step="0.01">

                    <?php $__errorArgs = ['n_gst_percentage'];
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
                    <label for="c_status" class="form-label">Operational Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory">
                        <option value="Y" <?php echo e(old('c_status', $product->c_status) === 'Y' ? 'selected' : ''); ?>>
                            Active
                        </option>
                        <option value="N" <?php echo e(old('c_status', $product->c_status) === 'N' ? 'selected' : ''); ?>>
                            Inactive
                        </option>
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
            </div>
            <div class="pt-5 border-top d-flex gap-3">
                <button type="button" id="btn_create" class="btn buttonSpc">
                    <i class="ti ti-device-floppy me-1"></i> Update Item
                </button>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary px-4 fw-bold"
                    style="border-radius: 10px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
const codeInput = document.getElementById('c_product_code');
const sellingInput = document.getElementById('n_selling_price');
const purchaseInput = document.getElementById('n_purchase_price');

const codeError = document.getElementById('code_error');
const sellingError = document.getElementById('selling_error');

let isDuplicate = false;

// Product code validation function
function validateProductCode() {
    let code = codeInput.value.trim();

    if (code === '') {
        codeError.innerText = "Product code cannot be empty";
        isDuplicate = false;
        return;
    }
}

// Trigger validation on input and blur
codeInput.addEventListener('input', validateProductCode);
codeInput.addEventListener('blur', validateProductCode);

// Selling price validation function
function validatePrices() {
    let purchase = parseFloat(purchaseInput.value) || 0;
    let selling = parseFloat(sellingInput.value) || 0;

    if (selling <= purchase && selling !== 0) {
        sellingError.innerText = "Selling price must be greater than purchase price";
    } else {
        sellingError.innerText = "";
    }
}

// Trigger price validation on input
purchaseInput.addEventListener('input', validatePrices);
sellingInput.addEventListener('input', validatePrices);

// Form submission check
document.getElementById('frm_create').addEventListener('submit', function(e) {
    // Trim product code spaces
    codeInput.value = codeInput.value.trim();

    // Run validations
    validateProductCode();
    validatePrices();

    if (codeError.innerText !== "" || sellingError.innerText !== "" || isDuplicate) {
        e.preventDefault(); // block submission
        alert("Please fix errors before submitting");
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>