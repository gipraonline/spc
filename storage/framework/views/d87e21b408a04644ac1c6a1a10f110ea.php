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

.widget-icon.green-dark {
    background-color: #166534;
}

.widget-icon.orange {
    background-color: #ea580c;
}

.widget-icon.blue {
    background-color: #0284c7;
}

.widget-icon.green-emerald {
    background-color: #7CA243;
}

.widget-icon.purple {
    background-color: #7c3aed;
}

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
    color: #5E8D3D;
}

.buttonSpc {
    background: linear-gradient(135deg, #5E8D3D 0%, #7CA243 100%) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    box-shadow: 0 4px 12px rgba(94, 141, 61, 0.2);
    transition: all 0.2s ease;
}

.buttonSpc:hover {
    background: linear-gradient(135deg, #1F5C2E 0%, #1F5C2E 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(94, 141, 61, 0.3);
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
    border-color: #7CA243;
    box-shadow: 0 0 0 3px rgba(94, 141, 61, 0.12);
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
    color: #1F5C2E;
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

.badge-status.rejected {
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
#sales-table {
    scroll-margin-top: 80px;
}

html {
    scroll-behavior: smooth;
}


/* Approval status button */
.approval-status-btn {
    max-width: 100%;
    min-height: 38px;
    padding: 7px 16px;
    border-radius: 20px;
    white-space: normal;
    overflow-wrap: anywhere;
    font-size: 14px;
}

/* ============================================================
   APPROVAL MODAL
   ============================================================ */
.approval-modal-dialog {
    width: calc(100% - 32px);
    max-width: 560px;
    margin: 16px auto;
}

.approval-modal-content {
    width: 100%;
    border: 0;
    border-radius: 14px;
    overflow: hidden;
}

.approval-modal-header {
    background: linear-gradient(135deg, #1b6b49, #0e3d26);
    padding: 16px 20px;
}

.approval-modal-body {
    padding: 20px;
}

.approval-modal-body .form-control,
.approval-modal-body .form-select {
    width: 100%;
    min-height: 44px;
    font-size: 16px;
}

.approval-modal-body textarea.form-control {
    min-height: 110px;
    resize: vertical;
}

/* Footer: one full-width wrapper that holds all the actions */
.approval-modal-footer {
    display: block;
    padding: 16px 20px 20px;
    border-top: 1px solid #e5e7eb;
}

.approval-modal-footer > * {
    margin: 0;
}

/* Actions row (applies on ALL screen sizes now) */
.approval-form-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    width: 100%;
    margin: 0;
    padding: 0;
    border: 0;
}

/* Common button design (overrides .buttonSpc / .btn globals) */
.approval-form-actions .btn {
    flex: 0 0 auto;
    width: auto;
    height: auto;
    min-height: 42px;
    margin: 0;
    padding: 9px 16px !important;
    border-radius: 8px !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 13px !important;
    font-weight: 600 !important;
    line-height: 1.2;
    white-space: nowrap;
    box-shadow: none;
    transform: none;
}

.approval-form-actions .btn i {
    font-size: 16px;
    line-height: 1;
}

/* Submit: primary action (solid green) */
.approval-form-actions #approvalSubmit {
    background: #166534 !important;
    color: #ffffff !important;
    border: 1px solid #166534 !important;
}

.approval-form-actions #approvalSubmit:hover {
    background: #0e3d26 !important;
    border-color: #0e3d26 !important;
}

/* Secondary actions: View Details / Order Preview / Download Invoice (outlined) */
.approval-form-actions .popup-action-btn {
    background: #ffffff !important;
    color: #166534 !important;
    border: 1px solid #cbd5d1 !important;
}

.approval-form-actions .popup-action-btn:hover {
    background: #f0fdf4 !important;
    border-color: #166534 !important;
}

/* Cancel: pushed to the far right on desktop */
.approval-form-actions .btn-cancel {
    margin-left: auto;
    background: transparent !important;
    color: #64748b !important;
    border: 1px solid transparent !important;
}

.approval-form-actions .btn-cancel:hover {
    background: #f1f5f9 !important;
    color: #334155 !important;
}

/* Mobile: neat 2-column grid
   Row 1: Submit (full width)
   Row 2: View Details | Order Preview
   Row 3: Download Invoice (full width, or half if alone)
   Row 4: Cancel (full width) */
@media (max-width: 576px) {
    .approval-modal-dialog {
        width: calc(100% - 20px);
        max-width: none;
        margin: 10px auto;
    }

    .approval-modal-header {
        padding: 14px 16px;
    }

    .approval-modal-body {
        padding: 16px;
    }

    .approval-modal-footer {
        padding: 14px 16px 16px;
    }

    .approval-form-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .approval-form-actions .btn {
        width: 100%;
        min-height: 44px;
        padding: 9px 8px !important;
        white-space: normal;
        text-align: center;
    }

    .approval-form-actions #approvalSubmit,
    .approval-form-actions .btn-cancel,
    .approval-form-actions .popup-action-btn[data-action="generate-invoice"] {
        grid-column: 1 / -1;
    }

    .approval-form-actions .btn-cancel {
        margin-left: 0;
        border: 1px solid #e2e8f0 !important;
    }

    .approval-status-btn {
        min-height: 40px;
        padding: 8px 12px;
    }
}


/* Responsive Grid Breakpoints */
@media (max-width: 1200px) {
    .widgets-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .widgets-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .widget-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    // Keep existing filters (date, search, payment, mode of payment...) and only swap the status
    $cardUrl = function (?string $status = null) {
    $params = request()->except(['status', 'order_status', 'page', 'export']);
    if ($status) {
        $params['status'] = $status;
    }
    return route('admin.salesorders.index', $params) . '#sales-table';
};

    $activeStatus = $requestedStatus ?? '';
?>

<?php if($errors->any()): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>

<div class="widgets-grid">

    <!-- Total Sales Orders -->
    <a href="<?php echo e($cardUrl()); ?>" class="widget-card <?php echo e($activeStatus === '' ? 'active' : ''); ?>">
        <div class="widget-icon green-dark">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($totalSalesOrders ?? 0); ?></span>
            <span class="widget-label">Total Sales Orders</span>
        </div>
    </a>

    <!-- Pending -->
    <a href="<?php echo e($cardUrl('pending')); ?>" class="widget-card <?php echo e($activeStatus === 'pending' ? 'active' : ''); ?>">
        <div class="widget-icon orange">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($pendingOrders ?? 0); ?></span>
            <span class="widget-label">Pending</span>
        </div>
    </a>

    <!-- Order Approved -->
    <a href="<?php echo e($cardUrl('approved')); ?>" class="widget-card <?php echo e($activeStatus === 'approved' ? 'active' : ''); ?>">
        <div class="widget-icon green-emerald">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($approvedOrders ?? 0); ?></span>
            <span class="widget-label">Order Approved</span>
        </div>
    </a>

    <!-- Dispatched -->
    <a href="<?php echo e($cardUrl('dispatched')); ?>" class="widget-card <?php echo e($activeStatus === 'dispatched' ? 'active' : ''); ?>">
        <div class="widget-icon purple">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($dispatchedOrders ?? 0); ?></span>
            <span class="widget-label">Dispatched</span>
        </div>
    </a>

    <!-- Order Completed -->
    <a href="<?php echo e($cardUrl('completed')); ?>" class="widget-card <?php echo e($activeStatus === 'completed' ? 'active' : ''); ?>">
        <div class="widget-icon blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="widget-details">
            <span class="widget-count"><?php echo e($completedOrders ?? 0); ?></span>
            <span class="widget-label">Order Completed</span>
        </div>
    </a>

</div>

<!-- Main Table & Filter Card -->
<div class="card w-100 position-relative overflow-hidden mb-4" id="sales-table">
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
                            <select name="payment_status" id="leadStatus" class="form-select">

                                <option value="">Select Status</option>

                                <option value="pending"
                                    <?php echo e(old('payment_status', $sale->payment_status ?? '') == "pending" ? 'selected' : ''); ?>>
                                    Pending</option>
                                <option value="confirmed"
                                    <?php echo e(old('payment_status', $sale->payment_status ?? '') == "confirmed" ? 'selected' : ''); ?>>
                                    Paid</option>

                            </select>
                        </div>

                        <!-- Order Status -->
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label fw-semibold">Order Status</label>

                            <select name="order_status" class="form-select">

                                <option value="">Select Status</option>

                                <option value="pending" <?php echo e(request('order_status') == 'pending' ? 'selected' : ''); ?>>
                                    Pending
                                </option>

                                <option value="approved" <?php echo e(request('order_status') == 'approved' ? 'selected' : ''); ?>>
                                    Approved
                                </option>

                                <option value="rejected" <?php echo e(request('order_status') == 'rejected' ? 'selected' : ''); ?>>
                                    Rejected
                                </option>

                                <option value="dispatched"
                                    <?php echo e(request('order_status') == 'dispatched' ? 'selected' : ''); ?>>
                                    Dispatched
                                </option>

                                <option value="shipped" <?php echo e(request('order_status') == 'shipped' ? 'selected' : ''); ?>>
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

                                <option value="returned" <?php echo e(request('order_status') == 'returned' ? 'selected' : ''); ?>>
                                    Returned
                                </option>


                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-6 col-md-9 d-flex flex-wrap gap-2">
                            <button class="btn buttonSpc px-4 text-nowrap">
                                <i class="ti ti-filter me-1"></i>Filter Report
                            </button>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sales-orders.export', 'sales-orders.view'])): ?>
                            <button type="submit" name="export" value="excel"
                                class="btn text-nowrap px-4 d-inline-flex align-items-center"
                                style="background:#fff;color:#1F5C2E;border:1.5px solid #5E8D3D;font-weight:600;">
                                <i class="ti ti-file-spreadsheet me-1" style="color:#5E8D3D;"></i>Export Excel
                            </button>
                            <?php endif; ?>
                            <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary text-nowrap px-4">
                                <i class="ti ti-refresh me-1"></i>Reset
                            </a>
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
                        
                        <th scope="col" class="text-end">Sales Total</th>
                        <th scope="col">Payment Image</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Order Status</th>
                        <th scope="col">Approval Status</th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any([ 'sales-orders.edit', 'sales-orders.delete'])): ?>
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
                        <td><?php echo e($sale?->customer->c_customer_name ?? 'N/A'); ?></td>
                        <td><?php echo e($sale?->customer->c_address ?? 'N/A'); ?></td>

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
                        <td class="text-end">
                            ₹ <?php echo e(number_format($sale->n_net_sales_amount ?? 0, 2)); ?>

                        </td>
                        <td>
                            <?php if($sale->payment_image): ?>
                            <a href="<?php echo e(route('admin.salesorders.proof', ['type' => 'payment_images', 'filename' => $sale->payment_image])); ?>" target="_blank">
                                <img src="<?php echo e(route('admin.salesorders.proof', ['type' => 'payment_images', 'filename' => $sale->payment_image])); ?>" width="50"
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

                        <td>
                            <?php if(!empty($isOfficeAdmin)): ?>
                                <?php
                                $approvalStatus = strtolower($sale->approval?->status ?? 'pending');
                                ?>

                                <button type="button"
                                    class="btn btn-sm
                                        badge-status <?php echo e(strtolower($approvalStatus) === 'approved' ? 'approved' :
                                        (strtolower($approvalStatus) === 'rejected' ? 'rejected' : 'pending')); ?>"
                                    data-id="<?php echo e(Crypt::encryptString(isset($sale) && $sale->n_sl_no ? $sale->n_sl_no : '')); ?>"
                                    data-status="<?php echo e($approvalStatus); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#approveModal"
                                    onclick="openStatusPopup(this)">
                                    <?php echo e(ucfirst($approvalStatus ?? 'Pending')); ?>

                                </button>


                            <?php else: ?>

                                <?php
                                $approvalStatus = strtolower($sale->approval?->status ?? 'pending');
                                ?>
                                <?php if($approvalStatus == 'approved'): ?>
                                <span class="badge-status approved">Approved</span>
                                <?php elseif($approvalStatus == 'rejected'): ?>
                                <span class="badge-status rejected">Rejected</span>
                                <?php else: ?>
                                <span class="badge-status pending">Pending</span>
                                <?php endif; ?>

                            <?php endif; ?>
                        </td>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sales-orders.edit', 'sales-orders.delete'])): ?>
                        <td>
                            <div class="dropdown dropstart">
                                <a href="#" class="text-muted p-1" id="dropdownMenuButton_<?php echo e($sale->n_sl_no); ?>"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_<?php echo e($sale->n_sl_no); ?>">
                                    <?php if(empty($isOfficeAdmin)): ?>
                                        <li>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.view-details')): ?>
                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                href="<?php echo e(route('admin.salesorders.show', Crypt::encryptString($sale->n_sl_no))); ?>">
                                                <i class="fs-4 ti ti-eye text-primary"></i>View Details
                                            </a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; ?>
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
                                        <form
                                            action="<?php echo e(route('admin.salesorders.destroy', Crypt::encryptString($sale->n_sl_no))); ?>"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="dropdown-item d-flex align-items-center gap-3 text-danger">
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
                                            data-bs-toggle="modal" data-bs-target="#salesUpdateModal"
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
                        <td colspan="12" class="text-center py-4 text-muted">No sales records found</td>
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

                <div class="modal-header" style="background: linear-gradient(135deg, #5E8D3D, #1F5C2E);">
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


<!-- Approval Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1"
        aria-labelledby="approveModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable approval-modal-dialog">
            <div class="modal-content approval-modal-content">

                <form method="POST" id="approveForm"
                    action="<?php echo e(route('admin.salesorders.approval.save')); ?>">
                    <?php echo csrf_field(); ?>


                    <div class="modal-header approval-modal-header">
                        <h5 class="modal-title text-white" id="approveModalLabel">
                            Approval
                        </h5>

                        <button type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body approval-modal-body">

                        <input type="hidden" name="sales_id" id="sales_id">

                        <div class="mb-3">
                            <label for="approval_remarks" class="form-label">
                                Remarks <span class="text-danger">*</span>
                            </label>

                            <textarea class="form-control"
                                    name="remarks"
                                    id="approval_remarks"
                                    rows="4"
                                    maxlength="1000"
                                    placeholder="Enter remarks"
                                    required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="approval_status" class="form-label">
                                Approval Status <span class="text-danger">*</span>
                            </label>

                            <select class="form-select"
                                    name="status"
                                    id="approval_status"
                                    required>
                                <option value="">Select Status</option>
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer approval-modal-footer">
                        <div class="approval-form-actions">
                            <button type="submit" class="btn buttonSpc" id="approvalSubmit">
                                <i class="ti ti-check"></i> Submit
                            </button>

                            <button type="button" class="btn buttonSpc popup-action-btn"
                                data-action="view-details">
                                <i class="ti ti-eye"></i> View Details
                            </button>

                            <?php if(!empty($isOfficeAdmin)): ?>
                                <button type="button" class="btn buttonSpc popup-action-btn office-admin-action"
                                    data-action="order-summary">
                                    <i class="ti ti-file-text"></i> Order Preview
                                </button>

                                <button type="button" class="btn buttonSpc popup-action-btn office-admin-action"
                                    data-action="generate-invoice">
                                    <i class="ti ti-download"></i> Download Invoice
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-cancel"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>


<script>
    $(document).ready(function () {

        window.currentApprovalButton = null;

        // Open approval popup
        window.openStatusPopup = function (button) {
            window.currentApprovalButton = button;

            const saleId = button.dataset.id || '';
            const approvalStatus = (button.dataset.status || '')
                .trim()
                .toLowerCase();

            // Store the selected sales order ID
            $('#sales_id').val(saleId);
            $('.popup-action-btn').attr('data-id', saleId);

            // Show invoice actions only for approved orders
            document.querySelectorAll('.office-admin-action').forEach(function (action) {
                action.hidden = approvalStatus !== 'approved';
            });

            //$('#approval_status').val('');
            $('#approval_remarks').val('');
        };

        // Submit approval form
        $('#approveForm').on('submit', function (e) {
            e.preventDefault();

            const form = this;
            const submitButton = document.getElementById('approvalSubmit');

            if (submitButton.disabled) {
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';

            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',

                success: function (response) {
                    if (response.success) {
                        if (window.currentApprovalButton) {
                            const button = window.currentApprovalButton;
                            const status = String(response.status || '')
                                .trim()
                                .toLowerCase();

                            button.textContent = status
                                ? status.charAt(0).toUpperCase() + status.slice(1)
                                : 'Pending';

                            button.dataset.status = status;

                            button.classList.remove(
                                'approved',
                                'rejected',
                                'pending'
                            );

                            button.classList.add(
                                status === 'approved' || status === 'rejected'
                                    ? status
                                    : 'pending'
                            );
                        }

                        const modalElement = document.getElementById('approveModal');

                        bootstrap.Modal
                            .getOrCreateInstance(modalElement)
                            .hide();

                        window.currentApprovalButton = null;
                    } else {
                        alert(response.message ||
                            'Approval update was not successful.');
                    }
                },

                error: function (xhr) {
                    console.error('Approval error:', xhr.responseText);

                    alert(
                        xhr.responseJSON?.message ||
                        'Unable to update approval status. Please try again.'
                    );
                },

                complete: function () {
                    submitButton.disabled = false;
                    submitButton.innerHTML =
                        '<i class="ti ti-check"></i> Submit';
                }
            });
        });

        // Handle popup action buttons
        $(document).on('click', '.popup-action-btn', function () {
            const saleId = this.dataset.id;
            const action = this.dataset.action;

            if (!saleId) {
                alert('Sales order ID is missing.');
                return;
            }

            let url = '';

            switch (action) {
                case 'view-details':
                    url = <?php echo json_encode(route('admin.salesorders.show', ['id' => '__ID__']), 512) ?>;
                    break;

                case 'order-summary':
                    url = <?php echo json_encode(route('admin.invoice-orders.preview', ['id' => '__ID__']), 512) ?>;
                    break;

                case 'generate-invoice':
                    url = <?php echo json_encode(route('admin.invoice.download', ['id' => '__ID__']), 512) ?>;
                    break;

                default:
                    return;
            }

            url = url.replace('__ID__', encodeURIComponent(saleId));

            window.location.href = url;
        });

        // Existing Sales Order Update modal logic
        $('#salesUpdateModal').on('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const salesNumber = $(button).data('id');

            $('.n_sale_id').val(salesNumber);

            console.log('Sales ID:', salesNumber);
        });

    });
</script>


<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc_new\resources\views/admin/sales/index.blade.php ENDPATH**/ ?>