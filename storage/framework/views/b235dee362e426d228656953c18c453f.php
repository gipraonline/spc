

<?php $__env->startPush('styles'); ?>
<style>
.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffeeba;
    font-weight: 600;
}

.status-paid {
    background-color: #d1e7dd;
    color: #0f5132;
    border-color: #badbcc;
    font-weight: 600;
}

.status-default {
    background-color: #e2e3e5;
    color: #41464b;
    border-color: #d6d8db;
    font-weight: 600;
}

.status-select {
    width: 140px !important;
    min-width: 140px;
}

.remarks-input {
    width: 100%;
    min-width: 280px;
    max-width: 450px;
    resize: vertical;
    overflow-y: auto;
}

.remarks-status {
    display: block;
    margin-top: 3px;
    font-size: 11px;
}

/* Table column widths */
.payment-table {
    min-width: 1300px;
}

.payment-table th,
.payment-table td {
    vertical-align: middle;
}

.payment-table th:nth-child(1),
.payment-table td:nth-child(1) {
    width: 70px;
    min-width: 70px;
    white-space: nowrap;
}

.payment-table th:nth-child(2),
.payment-table td:nth-child(2) {
    width: 180px;
    min-width: 180px;
    white-space: nowrap;
}

.payment-table th:nth-child(3),
.payment-table td:nth-child(3) {
    width: 150px;
    min-width: 150px;
    white-space: nowrap;
}

.payment-table th:nth-child(4),
.payment-table td:nth-child(4) {
    width: 220px;
    min-width: 220px;
}

.payment-table th:nth-child(5),
.payment-table td:nth-child(5) {
    width: 150px;
    min-width: 150px;
    white-space: nowrap;
}

.payment-table th:nth-child(6),
.payment-table td:nth-child(6) {
    width: 170px;
    min-width: 170px;
}

.payment-table th:nth-child(7),
.payment-table td:nth-child(7) {
    width: 160px;
    min-width: 160px;
}

.payment-table th:nth-child(8),
.payment-table td:nth-child(8) {
    width: 350px;
    min-width: 350px;
}

.payment-status-form {
    margin: 0;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            Payment Management
        </h4>
    </div>

    
    <div class="card">
        <div class="card-body">

            <form method="GET" action="<?php echo e(route('admin.payment-management.index')); ?>">

                <div class="row g-3">

                    
                    <div class="col-md-3">
                        <label for="payment_mode" class="form-label">
                            Payment Mode
                        </label>

                        <select name="payment_mode" id="payment_mode" class="form-control">
                            <option value="">
                                All
                            </option>

                            <?php $__currentLoopData = $paymentModes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentMode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($paymentMode); ?>"
                                <?php echo e(request('payment_mode') == $paymentMode ? 'selected' : ''); ?>>
                                <?php echo e($paymentMode); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="col-md-3">
                        <label for="filter_status" class="form-label">
                            Payment Status
                        </label>

                        <select name="status" id="filter_status" class="form-control">
                            <option value="">
                                All
                            </option>

                            <?php $__currentLoopData = $paymentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentStatus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($paymentStatus); ?>"
                                <?php echo e(request('status') == $paymentStatus ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($paymentStatus)); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">
                            From Date
                        </label>

                        <input type="date" name="from_date" id="from_date" class="form-control"
                            value="<?php echo e(request('from_date')); ?>">
                    </div>

                    
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">
                            To Date
                        </label>

                        <input type="date" name="to_date" id="to_date" class="form-control"
                            value="<?php echo e(request('to_date')); ?>">
                    </div>

                </div>

                
                <div class="row mt-3">
                    <div class="col-12 d-flex">

                        <button type="submit" class="btn btn-primary me-2">
                            Filter
                        </button>

                        <a href="<?php echo e(route('admin.payment-management.index')); ?>" class="btn btn-secondary me-2">
                            Reset
                        </a>

                        <a href="<?php echo e(route(
                                'admin.payment-management.export',
                                request()->query()
                            )); ?>" class="btn btn-success">
                            Export Excel
                        </a>

                    </div>
                </div>

            </form>

        </div>
    </div>

    
    <div class="card mt-4">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle payment-table">

                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Order No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            
                            <td>
                                <?php echo e($orders->firstItem() + $index); ?>

                            </td>

                            
                            <td>
                                <?php echo e($order->c_order_no); ?>

                            </td>

                            
                            <td>
                                <?php echo e($order->d_date?->format('d-m-Y')); ?>

                            </td>

                            
                            <td>
                                <?php echo e($order->c_customer_name); ?>

                            </td>

                            
                            <td>
                                ₹<?php echo e(number_format($order->n_net_sales_amount, 2)); ?>

                            </td>

                            
                            <td>
                                <?php echo e($order->c_mode_of_payment); ?>

                            </td>

                            
                            <td>

                                <form method="POST" action="<?php echo e(route(
                                            'admin.payment-management.update-status',
                                            $order
                                        )); ?>" class="payment-status-form">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>

                                    <select name="payment_status" class="form-control status-select
                                                <?php if($order->payment_status === 'pending'): ?>
                                                    status-pending
                                                <?php elseif($order->payment_status === 'paid'): ?>
                                                    status-paid
                                                <?php else: ?>
                                                    status-default
                                                <?php endif; ?>" onchange="this.form.submit()">

                                        <?php $__currentLoopData = $paymentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentStatus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <option value="<?php echo e($paymentStatus); ?>"
                                            <?php echo e($order->payment_status === $paymentStatus ? 'selected' : ''); ?>>
                                            <?php echo e(ucfirst($paymentStatus)); ?>

                                        </option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>

                                </form>

                            </td>

                            
                            <td>

                                <div class="remarks-wrapper">

                                    <textarea class="form-control form-control-sm remarks-input" rows="2"
                                        maxlength="1000" placeholder="Enter remarks..." data-url="<?php echo e(route(
                                                'admin.payment-management.update-remarks',
                                                $order
                                            )); ?>"><?php echo e($order->latestPaymentStatusLog?->remarks ?? ''); ?></textarea>

                                    <small class="remarks-status text-muted"></small>

                                </div>

                            </td>

                        </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No payment records found.
                            </td>
                        </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

            
            <div class="mt-3">
                <?php echo e($orders->links()); ?>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.remarks-input').forEach(function(input) {

        let timeout;

        input.addEventListener('input', function() {

            clearTimeout(timeout);

            const field = this;

            const wrapper = field.closest('.remarks-wrapper');

            const status = wrapper ?
                wrapper.querySelector('.remarks-status') :
                null;

            // Show typing status
            if (status) {
                status.textContent = 'Typing...';
                status.className =
                    'remarks-status text-muted';
            }

            timeout = setTimeout(function() {

                const remarks = field.value.trim();

                /*
                 * Do not allow empty remarks.
                 * This prevents clearing an existing remark.
                 */
                if (remarks === '') {

                    if (status) {
                        status.textContent =
                            'Remark cannot be empty';

                        status.className =
                            'remarks-status text-danger';
                    }

                    return;
                }

                // Show saving status
                if (status) {
                    status.textContent = 'Saving...';
                    status.className =
                        'remarks-status text-warning';
                }

                fetch(field.dataset.url, {

                        method: 'PATCH',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },

                        body: JSON.stringify({
                            remarks: remarks
                        })

                    })

                    .then(async function(response) {

                        let data = {};

                        try {
                            data = await response.json();
                        } catch (e) {
                            // Response is not JSON
                        }

                        if (!response.ok) {
                            throw new Error(
                                data.message ||
                                'Failed to save remarks'
                            );
                        }

                        return data;
                    })

                    .then(function(data) {

                        if (status) {
                            status.textContent = 'Saved ✓';
                            status.className =
                                'remarks-status text-success';
                        }

                        setTimeout(function() {

                            if (status) {
                                status.textContent = '';
                            }

                        }, 2000);
                    })

                    .catch(function(error) {

                        console.error(
                            'Remarks save error:',
                            error
                        );

                        if (status) {
                            status.textContent =
                                error.message || 'Save failed';

                            status.className =
                                'remarks-status text-danger';
                        }
                    });

            }, 1000);

        });

    });

});
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/payment-management/index.blade.php ENDPATH**/ ?>