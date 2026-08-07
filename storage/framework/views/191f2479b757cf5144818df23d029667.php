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
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Leads</h5>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leads.create')): ?>
        <a href="<?php echo e(route('admin.leads.create')); ?>" class="btn buttonSpc">
            Add Lead Entry
        </a>
        <?php endif; ?>
    </div>

    <div class="card-body p-4">

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Filters -->
        <form method="GET" action="<?php echo e(route('admin.leads.index')); ?>">
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Customer / Mobile / Advisor"
                                   value="<?php echo e(request('search')); ?>">
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date"
                                   name="from_date"
                                   class="form-control"
                                   value="<?php echo e(request('from_date')); ?>">
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date"
                                   name="to_date"
                                   class="form-control"
                                   value="<?php echo e(request('to_date')); ?>">
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Interested">Interested</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Negotiation">Negotiation</option>
                                <option value="Won">Won</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>

                        <div class="col-lg-3 pt-4 d-flex gap-2">
                            <button class="btn buttonSpc">
                                <i class="ti ti-search"></i> Filter
                            </button>

                            <a href="<?php echo e(route('admin.leads.index')); ?>"
                               class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </form>

        <!-- Statistics -->
        <div class="row mb-4">

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-primary-subtle">
                    <div class="card-body text-center">
                        <h3><?php echo e($totalLeads ?? 0); ?></h3>
                        <p class="mb-0">Total Leads</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-warning-subtle">
                    <div class="card-body text-center">
                        <h3><?php echo e($pendingFollowups ?? 0); ?></h3>
                        <p class="mb-0">Follow-ups Pending</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-success-subtle">
                    <div class="card-body text-center">
                        <h3><?php echo e($readyToBuy ?? 0); ?></h3>
                        <p class="mb-0">Ready to Buy</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-info-subtle">
                    <div class="card-body text-center">
                        <h3><?php echo e($newCustomers ?? 0); ?></h3>
                        <p class="mb-0">New Customers</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Crop</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Next Follow-up</th>
                        <th>Priority</th>
                        <th>Advisor</th>
                        <th>Remarks</th>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['leads.view','leads.edit','leads.delete'])): ?>
                        <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                    <?php if(isset($leads)): ?>
                        <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            <td><?php echo e(isset($leads) ?? $leads->firstItem() + $key); ?></td>

                            <td><?php echo e(\Carbon\Carbon::parse($lead->followup_date)->format('d M Y')); ?></td>

                            <td>
                                <strong><?php echo e($lead->customer_name); ?></strong><br>
                                <small><?php echo e($lead->mobile); ?></small>
                            </td>

                            <td><?php echo e($lead->location); ?></td>

                            <td><?php echo e($lead->crop); ?></td>

                            <td><?php echo e($lead->product); ?></td>

                            <td>
                                <span class="badge bg-success">
                                    <?php echo e($lead->status); ?>

                                </span>
                            </td>

                            <td>
                                <?php echo e(optional($lead->next_followup_date)->format('d M Y')); ?>

                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    <?php echo e($lead->priority); ?>

                                </span>
                            </td>

                            <td><?php echo e($lead->advisor); ?></td>

                            <td><?php echo e($lead->remarks); ?></td>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['leads.view','leads.edit','leads.delete'])): ?>
                            <td>

                                <div class="dropdown dropstart">

                                    <a href="#" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical fs-6"></i>
                                    </a>

                                    <ul class="dropdown-menu">

                                        <li>
                                            <a class="dropdown-item"
                                            href="#">
                                                <i class="ti ti-eye me-2"></i>View
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                            href="#">
                                                <i class="ti ti-edit me-2"></i>Edit
                                            </a>
                                        </li>

                                        <li>

                                            <form method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>

                                                <button class="dropdown-item text-danger">
                                                    <i class="ti ti-trash me-2"></i>Delete
                                                </button>

                                            </form>

                                        </li>

                                    </ul>

                                </div>

                            </td>
                            <?php endif; ?>

                        </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="12" class="text-center">
                                No lead records found.
                            </td>
                        </tr>

                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            <?php if(isset($leads)): ?>
                <?php echo e($leads->links()); ?>

            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/leads/index.blade.php ENDPATH**/ ?>