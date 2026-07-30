<?php $__env->startSection('content'); ?>
<style>
/* Filter Card */
.filter-card-wrapper {
    margin-bottom: 1rem;
}

.filter-header-sub {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.25rem;
    color: #2a3547;
}

.filter-header-sub .icon-box {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(93, 135, 255, .1);
    color: #5d87ff;
}

.filter-header-sub span {
    font-size: .9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* Filter Body */
.premium-filter-container {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 0;
}

/* Labels */
.custom-filter-group {
    position: relative;
}

.custom-filter-group label {
    display: block;
    margin-bottom: 12px;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Inputs */
.styled-select,
.styled-textbox {
    height: 54px !important;
    border: 1.5px solid #dfe5ef !important;
    border-radius: 16px !important;
    background: #f8fafc !important;
    padding: 0 18px !important;
    font-size: 14px !important;
}

.styled-select:focus,
.styled-textbox:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, .08) !important;
}

/* Buttons */
.btn-creative-filter {
    height: 54px !important;
    border-radius: 16px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
}

/* Responsive */
@media (max-width:768px) {
    .filter-card-wrapper {
        margin: 1rem;
        padding: 1rem;
    }

    .premium-filter-container {
        padding: 15px;
    }
}
</style>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Products</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.create')): ?>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
            Add Item
        </a>
        <?php endif; ?>
    </div>

    <div class="card-body p-4">
        <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e($message); ?>

        </div>
        <?php endif; ?>


        

        <form method="GET" action="<?php echo e(route('admin.products.index')); ?>">

            <div class="filter-card-wrapper">
                <div class="filter-header-sub">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </div>
                    <span>Refine Search</span>
                </div>
                <div class="premium-filter-container">

                    <div class="row g-3 align-items-end">

                        <!-- Search -->
                        <div class="col-md-5">
                            <div class="custom-filter-group">
                                <label>Product</label>
                                <input type="text" name="search" class="form-control styled-textbox"
                                    placeholder="Search by Product ID or Product Name" value="<?php echo e(request('search')); ?>">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-3">
                            <div class="custom-filter-group">
                                <label>Status</label>

                                <select name="status" class="form-select styled-select">
                                    <option value="">All Status</option>
                                    <option value="Y" <?php echo e(request('status')=='Y' ? 'selected' : ''); ?>>
                                        Active
                                    </option>
                                    <option value="N" <?php echo e(request('status')=='N' ? 'selected' : ''); ?>>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-4">
                            <div class="d-flex gap-2">

                                <button type="submit" class="btn btn-primary btn-creative-filter flex-fill">
                                    <i class="ti ti-search"></i>
                                    Filter
                                </button>

                                <a href="<?php echo e(route('admin.products.index')); ?>"
                                    class="btn btn-secondary btn-creative-filter flex-fill">
                                    <i class="ti ti-refresh"></i>
                                    Reset
                                </a>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.export')): ?>
                                <a href="<?php echo e(route('admin.products.export', request()->query())); ?>"
                                    class="btn btn-success btn-creative-filter flex-fill">
                                    <i class="ti ti-file-export"></i>
                                    Export
                                </a>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </form>

        <div class="table-responsive" id="productTable">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Sl No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Product ID</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">MRP</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Selling Price</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Purchase Price</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['products.edit', 'products.delete'])): ?>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">
                                <?php echo e(($products->currentPage() - 1) * $products->perPage() + $loop->iteration); ?>

                            </h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0"><?php echo e($product->c_product_code); ?></h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0"><?php echo e($product->c_product_name); ?></h6>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">₹<?php echo e(number_format($product->n_mrp, 2)); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">₹<?php echo e(number_format($product->n_selling_price, 2)); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">₹<?php echo e(number_format($product->n_purchase_price, 2)); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span
                                class="badge <?php echo e($product->c_status === 'Y' ? 'bg-success' : 'bg-danger'); ?> rounded-3 fw-semibold">
                                <?php echo e(ucfirst(str_replace('_', ' ', $product->c_status))); ?>

                            </span>
                        </td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['products.edit', 'products.delete'])): ?>
                        <td class="border-bottom-0">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.edit')): ?>
                            <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.delete')): ?>
                            <form method="POST" action="<?php echo e(route('admin.products.destroy', $product)); ?>"
                                class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger ms-2" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center">No products found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($products->links()); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let timer;

document.getElementById('search').addEventListener('keyup', function() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        this.form.submit();
    }, 1500);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/products/index.blade.php ENDPATH**/ ?>