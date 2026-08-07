<?php $__env->startPush('styles'); ?>

<style>
.refine-search-card {
    font-family: 'Inter', sans-serif;
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.refine-search-card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
}

.icon-box {
    background: linear-gradient(135deg, #f0f7ff 0%, #e0eeff 100%);
    border: 1px solid #d0e4ff;
    color: #5d87ff;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon-inner {
    position: absolute;
    left: 16px;
    color: #94a3b8;
    pointer-events: none;
}

.custom-input {
    height: 52px !important;
    padding-left: 48px !important;
    padding-right: 110px !important;
    border-radius: 12px !important;
    border: 1.5px solid #e2e8f0 !important;
    background-color: #f8fafc !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #1e293b !important;
    transition: all 0.2s ease-in-out !important;
    box-shadow: none !important;
}

.custom-input:focus {
    background-color: #ffffff !important;
    border-color: #5d87ff !important;
    box-shadow: 0 0 0 4px rgba(93, 135, 255, 0.1) !important;
}

.custom-input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.search-btn {
    position: absolute;
    right: 8px;
    height: 38px;
    padding: 0 20px;
    background: #5d87ff;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.search-btn:hover {
    background: #4a6ee0;
    transform: translateY(-1px);
}

.search-label {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.search-btn.position-static {
    position: static;
}

.reset-btn {
    height: 38px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}

.search-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #1b3e86;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.search-label svg {
    flex-shrink: 0;
}
</style>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Franchises</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('franchises.create')): ?>
        <a href="<?php echo e(route('admin.franchises.create')); ?>" class="btn buttonSpc">Add Franchise</a>
        <?php endif; ?>
    </div>
    <div class="card-body p-4">
        <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e($message); ?>

        </div>
        <?php endif; ?>


        <!-- Search Store -->

        <form method="POST" action="<?php echo e(route('admin.franchises.search')); ?>">
            <?php echo csrf_field(); ?>
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <!-- Header Section -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="filter-header-sub">
                            <div class="icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                            </div>
                            <span>Refine Search</span>
                        </div>

                    </div>
                    <!-- Search Field Section -->
                    <div class="row">
                        <div class="col-md-6 col-lg-5">

                            <label class="search-label" for="storeSearch">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="me-1">
                                    <path d="M3 9l1-5h16l1 5" />
                                    <path d="M5 9v10h14V9" />
                                    <path d="M9 19v-6h6v6" />
                                    <path d="M3 9h18" />
                                </svg>
                                Franchise Search
                            </label>
                            <div class="search-input-group">
                                <div class="search-icon-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>

                                <input type="text" name="search" value="<?php echo e(session('store_search')); ?>"
                                    class="form-control custom-input" placeholder="Store Code or Name..."
                                    id="storeSearch" autocomplete="off">

                                <div class="position-absolute end-0 me-2 d-flex gap-2">
                                    <button type="submit" class="search-btn buttonSpc position-static">
                                        Search
                                    </button>

                                    <?php if(session('store_search')): ?>
                                    <a href="<?php echo e(route('admin.franchises.clearSearch')); ?>"
                                        class="btn btn-outline-primary reset-btn">
                                        <i class="ti ti-refresh me-1"></i>
                                        Reset
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Code</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th>
                            <h6 class="fw-semibold mb-0">State</h6>
                        </th>

                        <th>
                            <h6 class="fw-semibold mb-0">District</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Email</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Phone</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['franchises.edit', 'franchises.delete'])): ?>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($store->c_store_code); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0"><?php echo e($store->c_store_name); ?></h6>
                        </td>

                        <td>
                            <?php echo e($store->state->name ?? '-'); ?>

                        </td>

                        <td>
                            <?php echo e($store->district->district_name ?? '-'); ?>

                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($store->c_store_email ?? '-'); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal"><?php echo e($store->n_store_phone ?? '-'); ?></span>
                        </td>
                        <td class="border-bottom-0">
                            <span
                                class="badge <?php echo e($store->c_store_status === 'Y' ? 'bg-success' : 'bg-danger'); ?> rounded-3 fw-semibold">
                                <?php echo e(ucfirst($store->c_store_status)); ?>

                            </span>
                        </td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['franchises.edit', 'franchises.delete'])): ?>
                        <td class="border-bottom-0">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('franchises.edit')): ?>
                            <a href="<?php echo e(route('admin.franchises.edit', $store)); ?>"
                                class="btn btn-sm btn-primary">Edit</a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('franchises.delete')): ?>
                            <form method="POST" action="<?php echo e(route('admin.franchises.destroy', $store)); ?>"
                                class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger ms-2"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center">No franchises found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php echo e($stores->links()); ?>

        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
let searchTimer;

document.getElementById('storeSearch').addEventListener('keyup', function() {

    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        this.form.submit();
    }, 1200); // waits 800ms after typing stops
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/stores/index.blade.php ENDPATH**/ ?>