<?php $__env->startPush('styles'); ?>
<style>
    /* Global Page & Background Reset */
    .content-wrapper {
        background-color: #f4f8f5;
        min-height: 100vh;
        padding: 24px;
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Top 5 Stat Widget Cards Grid */
    .widgets-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .widget-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .widget-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    }

    .widget-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #ffffff;
    }

    .widget-icon.green-dark { background-color: #166534; }
    .widget-icon.orange { background-color: #ea580c; }
    .widget-icon.blue { background-color: #0284c7; }
    .widget-icon.green-emerald { background-color: #059669; }
    .widget-icon.purple { background-color: #7c3aed; }

    .widget-details {
        display: flex;
        flex-direction: column;
    }

    .widget-count {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .widget-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-top: 2px;
    }

    /* Main Container Card Styling */
    .card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f5132;
    }

    .buttonSpc {
        background: linear-gradient(135deg, #0f5132 0%, #059669 100%) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 10px 20px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
        transition: all 0.2s ease;
    }

    .buttonSpc:hover {
        background: linear-gradient(135deg, #0b3e26 0%, #047857 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3);
    }

    /* Filter Form Styling */
    .refine-search-card {
        background-color: #f8faf8 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control {
        height: 42px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }

    .btn {
        height: 42px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Table & Status Badges Styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: #f8faf8;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table tbody td {
        padding: 14px 16px;
        font-size: 13.5px;
        color: #1e293b;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr:hover td {
        background-color: #f8faf8;
    }

    /* Status Pills matching exact design in image */
    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-status.pending {
        background-color: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-status.confirmed {
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-status.approved {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-status.dispatched {
        background-color: #f3e8ff;
        color: #6b21a8;
        border: 1px solid #e9d5ff;
    }

    .badge-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    }

    /* Approved - Green */
    .badge-status.approved {
        background-color: #d1fae5;
        color: #047857;
    }

    /* Dispatched - Blue */
    .badge-status.dispatched {
        background-color: #dbeafe;
        color: #1d4ed8;
    }

    /* Shipped - Purple */
    .badge-status.shipped {
        background-color: #ede9fe;
        color: #7c3aed;
    }

    /* Delivered - Teal */
    .badge-status.delivered {
        background-color: #ccfbf1;
        color: #0f766e;
    }

    /* Completed - Dark Green */
    .badge-status.completed {
        background-color: #dcfce7;
        color: #06f55ed8;
    }

    .badge-status.returned {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    /* Pending - Yellow/Orange */
    .badge-status.pending {
        background-color: #fef3c7;
        color: #b45309;
    }

    /* Unknown status */
    .badge-status.unknown {
        background-color: #e5e7eb;
        color: #374151;
    }

    /* Responsive Grid Breakpoints */
    @media (max-width: 1200px) {
        .widgets-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .widgets-grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Crypt;
?>

<!-- Top 5 Stat Widget Cards (As shown in reference image) -->
<div class="widgets-grid">

    <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
    <?php endif; ?>

    <!-- 1. Total Sales Orders -->
    <div class="widget-card">
        <div class="widget-icon green-dark">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($totalSalesOrders ?? $sales->total() ?? 0); ?></span>
            <span class="widget-label">Total Sales Orders</span>
        </div>
    </div>

    <!-- 2. Pending -->
    <div class="widget-card">
        <div class="widget-icon orange">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($pendingOrders ?? 0); ?></span>
            <span class="widget-label">Pending</span>
        </div>
    </div>

    <!-- 4. Order Approved -->
    <div class="widget-card">
        <div class="widget-icon green-emerald">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($approvedOrders ?? 0); ?></span>
            <span class="widget-label">Order Approved</span>
        </div>
    </div>


    <!-- 5. Dispatched -->
    <div class="widget-card">
        <div class="widget-icon purple">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($dispatchedOrders ?? 0); ?></span>
            <span class="widget-label">Dispatched</span>
        </div>
    </div>

     <!-- 3. Order Confirmed -->
    <div class="widget-card">
        <div class="widget-icon blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($completedOrders ?? 0); ?></span>
            <span class="widget-label">Order Completed</span>
        </div>
    </div>



</div>

<!-- Main Table & Filter Card -->
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Sales Orders</h5>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.create')): ?>
            <a href="<?php echo e(route('admin.salesorders.create')); ?>" class="btn buttonSpc">
                <i class="ti ti-plus me-1"></i> Add Sales Entry
            </a>
        <?php endif; ?>
    </div>

    <div class="card-body p-4">

        <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e($message); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="GET" action="<?php echo e(route('admin.salesorders.index')); ?>" class="p-0">
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body p-3">

                    <!-- Search By Farm Care Advisor Name or Code-->
                    <div class="row g-3 align-items-end">
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

                        <!-- Payment Status -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">Payment Status</label>
                            <select name="payment_status"
                                    id="leadStatus"
                                    class="form-select">

                                <option value="">Select Status</option>

                                <option value="pending" <?php echo e(old('payment_status', $sale->payment_status ?? '') == "pending" ? 'selected' : ''); ?>>Pending</option>
                                <option value="confirmed"  <?php echo e(old('payment_status', $sale->payment_status ?? '') == "confirmed" ? 'selected' : ''); ?>>Paid</option>

                            </select>
                        </div>

                        <!-- Order Status -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">Order Status</label>

                            <select name="order_status" class="form-select">

                                <option value="">Select Status</option>

                                <option value="pending"
                                    <?php echo e(request('order_status') == 'pending' ? 'selected' : ''); ?>>
                                    Pending
                                </option>

                                <option value="approved"
                                    <?php echo e(request('order_status') == 'approved' ? 'selected' : ''); ?>>
                                    Approved
                                </option>

                                <option value="rejected"
                                    <?php echo e(request('order_status') == 'rejected' ? 'selected' : ''); ?>>
                                    Rejected
                                </option>

                                <option value="dispatched"
                                    <?php echo e(request('order_status') == 'dispatched' ? 'selected' : ''); ?>>
                                    Dispatched
                                </option>

                                <option value="shipped"
                                    <?php echo e(request('order_status') == 'shipped' ? 'selected' : ''); ?>>
                                    Shipped
                                </option>

                                <option value="delivered"
                                    <?php echo e(request('order_status') == 'delivered' ? 'selected' : ''); ?>>
                                    Delivered
                                </option>

                                <option value="completed"
                                    <?php echo e(request('order_status') == 'completed' ? 'selected' : ''); ?>>
                                    Completed
                                </option>

                                <option value="returned"
                                    <?php echo e(request('order_status') == 'returned' ? 'selected' : ''); ?>>
                                    Returned
                                </option>


                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-3 col-md-3 d-flex gap-2">
                            <button class="btn buttonSpc w-100">Filter Report</button>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.export')): ?>
                            <button type="submit" name="export" value="excel" class="btn btn-success">
                                <i class="ti ti-file-export me-1"></i>
                                Export
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
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Order Id</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Address</th>
                        <?php if(isset($isFarmCareAdvisor)): ?>
                        <th scope="col">Farm Care Advisor</th>
                        <?php endif; ?>
                        <th scope="col">Franchise</th>
                        <th scope="col">Payment Image</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Order Status</th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sales-orders.view-details', 'sales-orders.edit', 'sales-orders.delete'])): ?>
                        <th scope="col">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>
                        <td class="text-center" id="salesnumber" data-orderId="<?php echo e($sale->n_sl_no); ?>">
                            <span class="fw-normal"><?php echo e($sales->firstItem() + $key); ?></span>
                        </td>
                        <td><strong><?php echo e($sale?->c_order_no ?? 'N/A'); ?></strong></td>
                        <td><?php echo e(\Carbon\Carbon::parse($sale->d_date)->format('d M Y')); ?></td>
                        <td><?php echo e($sale?->c_customer_name ?? 'N/A'); ?></td>
                        <td><?php echo e($sale?->c_customer_address ?? 'N/A'); ?></td>

                        <?php if(isset($isFarmCareAdvisor)): ?>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?php echo e($sale->employee?->c_employee_name ?? 'N/A'); ?></h6>
                                    <span class="fs-2 text-muted"><?php echo e($sale->employee?->c_employee_code ?? ''); ?></span>
                                </div>
                            </div>
                        </td>
                        <?php endif; ?>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?php echo e($sale->franchise?->c_store_name ?? 'N/A'); ?></h6>
                                    <span class="fs-2 text-muted"><?php echo e($sale->franchise?->c_store_code ?? ''); ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if($sale->payment_image): ?>
                                <a href="<?php echo e(asset('uploads/payment_images/' . $sale->payment_image)); ?>" target="_blank">
                                    <img src="<?php echo e(asset('uploads/payment_images/' . $sale->payment_image)); ?>"
                                        width="50"
                                        height="50"
                                        style="object-fit: cover; border-radius: 6px; cursor: pointer; border: 1px solid #e2e8f0;">
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                         <td>
                            <?php
                                $status = strtolower($sale->payment_status ?? 'pending');
                            ?>
                            <?php if($status == 'paid' ): ?>
                                <span class="badge-status confirmed">Paid</span>
                            <?php else: ?>
                                <span class="badge-status pending">Pending</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php
                                $status = strtolower($sale->current_order_status ?? 'pending');
                            ?>
                            <?php if($status == 'approved' ): ?>
                                <span class="badge-status approved">Order Approved</span>
                            <?php elseif($status == 'rejected'): ?>
                                <span class="badge-status rejected">Rejected</span>
                            <?php elseif($status == 'dispatched'): ?>
                                <span class="badge-status dispatched">Dispatched</span>
                            <?php elseif($status == 'shipped'): ?>
                                <span class="badge-status shipped">Shipped</span>
                            <?php elseif($status == 'delivered'): ?>
                                <span class="badge-status delivered">Delivered</span>
                            <?php elseif($status == 'completed'): ?>
                                <span class="badge-status completed">Completed</span>
                            <?php elseif($status == 'returned'): ?>
                                <span class="badge-status returned">Returned</span>

                            <?php elseif($status == 'pending'): ?>
                                <span class="badge-status pending">Pending</span>
                            <?php endif; ?>
                        </td>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sales-orders.view-details', 'sales-orders.edit', 'sales-orders.delete'])): ?>
                        <td>
                            <div class="dropdown dropstart">
                                <a href="#" class="text-muted p-1" id="dropdownMenuButton_<?php echo e($sale->n_sl_no); ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_<?php echo e($sale->n_sl_no); ?>">
                                    <li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.view-details')): ?>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="<?php echo e(route('admin.salesorders.show', Crypt::encryptString($sale->n_sl_no))); ?>">
                                            <i class="fs-4 ti ti-eye text-primary"></i>View Details
                                        </a>
                                        <?php endif; ?>
                                    </li>
                                    <li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.edit')): ?>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="<?php echo e(route('admin.salesorders.edit', Crypt::encryptString($sale->n_sl_no))); ?>">
                                            <i class="fs-4 ti ti-edit text-success"></i>Edit
                                        </a>
                                        <?php endif; ?>
                                    </li>
                                    <li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.delete')): ?>
                                        <form action="<?php echo e(route('admin.salesorders.destroy', Crypt::encryptString($sale->n_sl_no))); ?>"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-3 text-danger">
                                                <i class="fs-4 ti ti-trash"></i>Delete
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </li>
                                    <li>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.follow-up')): ?>
                                            <!--Follow-up Button-->
                                            <button type="button"
                                                class="dropdown-item d-flex align-items-center gap-3 salesorder-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#salesUpdateModal"
                                                data-id="<?php echo e(Crypt::encryptString($sale->n_sl_no)); ?>">

                                                <i class="ti ti-pencil"></i>
                                                Update Order Status
                                            </button>

                                        <?php endif; ?>
                                    </li>

                                </ul>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">No sales records found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            <?php echo e($sales->links()); ?>

        </div>

    </div>
</div>

<!-- Follow-up Modal -->
<div class="modal fade" id="salesUpdateModal" tabindex="-1" aria-labelledby="salesUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">



            <form action="<?php echo e(route('admin.salesorders.salesUpdateStore')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="modal-header" style="background: linear-gradient(135deg, #0f5132, #074E30);">
                    <h5 class="modal-title text-white" id="salesUpdateModalLabel">
                        Sales Order Update Form
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" class="n_sale_id" name="n_sale_id" value="">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Order Update Date</label>
                            <input type="date" name="d_followup_date" class="form-control" required>
                        </div>

                        <?php if(isset($isFarmCareAdvisor) && $isFarmCareAdvisor != true): ?>
                        

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Order Status</label>
                            <select name="c_order_status" class="form-select" required>
                                <option value="">Select Status</option>

                                <?php if(isset($sale) && $sale->c_mode_of_payment != "Paid to Franchise"): ?>
                                    <option value="dispatched">Dispatched</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                <?php endif; ?>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="4"
                                placeholder="Enter follow-up remarks..." required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn buttonSpc">
                        Save Order Status
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
     $(document).ready(function(){

        $('#salesUpdateModal').on('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const salesNumber = $(button).data('id');

           var s= $('.n_sale_id').val(salesNumber);
//alert(salesNumber);
            console.log(salesNumber);
        });
    })
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/sales/index.blade.php ENDPATH**/ ?>