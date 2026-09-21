

<?php $__env->startSection('content'); ?>

<style>
/* Filter Card */
.filter-card-wrapper {
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 12px;
    margin: 1.5rem 2rem;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .02);
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
    background: rgba(93, 135, 255, .1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5d87ff;
}

.filter-header-sub span {
    font-size: .9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.premium-filter-container {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 30px;
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
    margin-bottom: 12px;
}

.styled-textbox,
.styled-select {
    height: 54px !important;
    border: 1.5px solid #dfe5ef !important;
    border-radius: 16px !important;
    background: #f8fafc !important;
    padding: 0 18px !important;
}

.styled-textbox:focus,
.styled-select:focus {
    border-color: #7f8ca0 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, .08) !important;
}

.btn-creative-filter,
.btn-reset {
    height: 54px !important;
    min-height: 54px;
    border-radius: 16px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 28px !important;
    font-weight: 600;
}

@media(max-width:768px) {

    .premium-filter-container {
        padding: 15px;
    }

    .filter-card-wrapper {
        margin: 1rem;
        padding: 1rem;
    }

}
</style>

<div class="card w-100 position-relative overflow-hidden">

    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">

        <h5 class="card-title fw-semibold mb-0">
            Customers
        </h5>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.create')): ?>
        <a href="<?php echo e(route('admin.customers.create')); ?>" class="btn buttonSpc">
            Add Customer
        </a>
        <?php endif; ?>

    </div>

    <div class="filter-card-wrapper">

        <div class="filter-header-sub">

            <div class="icon-box">
                <i class="ti ti-filter"></i>
            </div>

            <span>Refine Search</span>

        </div>

        <div class="premium-filter-container">

            <form method="POST" action="<?php echo e(route('admin.customers.search')); ?>">

                <?php echo csrf_field(); ?>

                <div class="row g-3 align-items-end">

                    <div class="col-md-7">

                        <label class="search-label">
                            Customer
                        </label>

                        <input type="text" name="customer_search" class="form-control styled-textbox"
                            placeholder="Search by Customer Code / Name / Mobile"
                            value="<?php echo e(session('customer_search')); ?>">

                    </div>

                    <div class="col-md-2">

                        <label class="search-label">
                            Status
                        </label>

                        <select name="c_status" class="form-select styled-select">

                            <option value="">All</option>

                            <option value="Y" <?php echo e(session('customer_status')=='Y' ? 'selected':''); ?>>
                                Active
                            </option>

                            <option value="N" <?php echo e(session('customer_status')=='N' ? 'selected':''); ?>>
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">
                        <label class="invisible">Action</label>

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn buttonSpc btn-creative-filter flex-fill">
                                <i class="ti ti-search"></i>
                                Filter
                            </button>

                            <a href="<?php echo e(route('admin.customers.clearSearch')); ?>"
                                class="btn btn-outline-primary btn-reset">
                                <i class="ti ti-refresh"></i>
                                Reset
                            </a>

                        </div>
                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card-body p-4">

        <?php if(Session::has('success')): ?>

        <div class="alert alert-success">

            <?php echo e(Session::get('success')); ?>


        </div>

        <?php endif; ?>

        <div class="table-responsive">

            <table class="table text-nowrap mb-0 align-middle">

                <thead class="text-dark fs-4">

                    <tr>

                        <th>Sl No</th>

                        <th>Customer Code</th>

                        <th>Customer Name</th>

                        <th>Mobile</th>

                        <th>WhatsApp</th>

                        <th>District</th>

                        <th>Pincode</th>

                        <th>State</th>

                        <th>Status</th>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['customers.edit','customers.delete'])): ?>
                        <th>Actions</th>
                        <?php endif; ?>

                    </tr>

                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td class="border-bottom-0 text-center">
                            <?php echo e($customers->firstItem() + $key); ?>

                        </td>

                        <td class="border-bottom-0">
                            <?php echo e($customer->c_customer_code); ?>

                        </td>

                        <td class="border-bottom-0">
                            <strong><?php echo e($customer->c_customer_name); ?></strong>
                        </td>

                        <td class="border-bottom-0">
                            <?php echo e($customer->n_mobile); ?>

                        </td>

                        <td class="border-bottom-0">
                            <?php echo e($customer->n_whatsapp ?? '-'); ?>

                        </td>

                        <td class="border-bottom-0">
                            <?php echo e($customer->district?->district_name ?? '-'); ?>

                        </td>
                        <td class="border-bottom-0">
                            <?php echo e($customer->c_pincode ?? '-'); ?>

                        </td>


                        <td class="border-bottom-0">
                            <?php echo e($customer->state?->name ?? '-'); ?>

                        </td>

                        <td class="border-bottom-0">

                            <span class="badge <?php echo e($customer->c_status == 'Y' ? 'bg-success' : 'bg-danger'); ?>">

                                <?php echo e($customer->c_status == 'Y' ? 'Active' : 'Inactive'); ?>


                            </span>

                        </td>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['customers.edit','customers.delete'])): ?>

                        <td class="border-bottom-0">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.edit')): ?>

                            <a href="<?php echo e(route('admin.customers.edit',$customer)); ?>" class="btn btn-sm btn-primary">

                                <i class="ti ti-edit"></i>
                                Edit

                            </a>

                            <?php endif; ?>


                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.delete')): ?>

                            <form action="<?php echo e(route('admin.customers.destroy',$customer)); ?>" method="POST"
                                class="d-inline">

                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this customer?')">

                                    <i class="ti ti-trash"></i>
                                    Delete

                                </button>

                            </form>

                            <?php endif; ?>

                        </td>

                        <?php endif; ?>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="10" class="text-center py-4">

                            <div class="text-muted">

                                <i class="ti ti-users fs-1 d-block mb-2"></i>

                                No customers found.

                            </div>

                        </td>

                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="mt-4">

            <?php echo e($customers->links()); ?>


        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>