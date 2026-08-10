<?php $__env->startPush('styles'); ?>
<style>
.order-number {
    background: #f5f5f5;
    font-weight: bold;
    color: #086a0e;
}

.form-section {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    background: #fff;
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    color: #086a0e;
    border-bottom: 1px solid #eee;
    padding-bottom: 12px;
}

.form-box {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    background: #ffffff;
}

.form-section-header {
    font-size: 16px;
    font-weight: 600;
    color: #086a0e;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 20px;
}

.payment-option {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px 18px;
    margin-right: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.payment-option:hover {
    background: #f8f9fa;
}

.payment-option input[type="radio"] {
    margin: 0;
}

.payment-option input[type="radio"]:checked+label {
    color: #087f23;
    font-weight: 600;
}

.advisor-highlight {

    /* Light yellow */
    background: #f5f5f5;
     !important;
    color: #087f23;
     !important;
    font-weight: 700;

}

.advisor-highlight:focus {

    border-color: #087f23;
     !important;

}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Crypt;
?>
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Add Sales Orders</h5>
    </div>
    <div class="card-body p-4">

        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" id="frm_create" action="<?php echo e(route('admin.leads.store')); ?>">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="id" class="form-control" value="<?php echo e(isset($sale) ? $sale->n_sl_no : ''); ?>">

            <!-- Section 1: Order Information -->
            <div class="form-section mb-4">

                <div class="section-title mb-3">
                    <i class="ti ti-file-invoice fs-5"></i>
                    Order Information
                </div>

                <!-- Row 1 -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="d_date" class="form-control mandatory"
                            data-message="Please Select a Date"
                            value="<?php echo e(old('d_date', isset($sale) ? $sale->d_date->format('Y-m-d') : '')); ?>"
                            <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">Order No *</label>

                        <input type="text" name="c_order_no" class="form-control mandatory order-number"
                            data-message="Please Enter Order No"
                            value="<?php echo e(old('c_order_no', isset($sale) ? $sale->c_order_no : $orderNo)); ?>" readonly>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                </div>


                <!-- Row 2: Farm Care Advisor -->
                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Farm Care Advisor *
                        </label>


                        <?php if($isFarmCareAdvisor): ?>

                        <input type="text" class="form-control advisor-highlight" value="<?php echo e(auth()->user()->c_name); ?>"
                            readonly>
                        <?php else: ?>

                        <select name="farm_care_advisor_id" class="form-control mandatory">
                            <option value="">Select Farm Care Adviser</option>

                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->n_employee_id); ?>">
                                <?php echo e($employee->c_employee_name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <?php endif; ?>
                        <div class="text-danger mt-1 fs-2"></div>

                    </div>

                </div>

            </div>
            <!-- Row 3 : Product Details -->

            <div class="form-section mb-4">

                <div class="section-title d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <i class="ti ti-shopping-cart fs-5"></i>
                        Product Details *
                    </div>


                    <?php if(isset($viewmode) && $viewmode=='off'): ?>

                    <button type="button" class="btn buttonSpc btn-sm" id="addRow">
                        <i class="ti ti-plus"></i>
                        Add New Product
                    </button>

                    <?php endif; ?>

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered table-responsive align-middle" id="productTable">

                        <thead class="table-light">

                            <tr>
                                <th width="45%">Product</th>
                                <th width="15%">Price</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Total</th>
                                <th width="10%">Action</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php if(isset($sale->orderProducts)): ?>

                            <?php $__currentLoopData = $sale->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>

                                <td>
                                    <select name="products[<?php echo e($key); ?>][product_id]"
                                        class="form-control product mandatory">

                                        <option value="">
                                            Select Product
                                        </option>

                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <option value="<?php echo e($product->n_product_id); ?>"
                                            data-price="<?php echo e($product->n_selling_price); ?>"
                                            <?php echo e($val->product_id == $product->n_product_id ? 'selected' : ''); ?>>

                                            <?php echo e($product->c_product_name); ?>


                                        </option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>
                                </td>


                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][product_price]"
                                        class="form-control price" value="<?php echo e($val->product_price); ?>" readonly>
                                </td>


                                <td>
                                    <input type="number" name="products[<?php echo e($key); ?>][qty]" class="form-control qty"
                                        value="<?php echo e($val->qty); ?>">
                                </td>


                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][product_total]"
                                        class="form-control total" value="<?php echo e($val->product_total); ?>" readonly>
                                </td>


                                <td class="text-center">

                                    <button type="button" class="btn btn-danger btn-sm removeRow">
                                        <i class="ti ti-trash"></i>
                                    </button>

                                </td>

                            </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Section 3: Customer Information -->
            <div class="border rounded p-4 mb-4">

                <div class="form-section-header mb-3">
                    <i class="ti ti-user fs-5"></i> Customer Information
                </div>


                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <label for="c_customer_name" class="form-label">Customer *</label>

                        <select name="customer_id" id="customer_id" class="form-select mandatory">
                            <option value="">Select Customer</option>
                            <?php if(isset($customers)): ?>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->n_customer_id); ?>" data-name="<?php echo e($customer->c_customer_name); ?>"
                                    data-email="<?php echo e($customer->c_email); ?>" data-mobile="<?php echo e($customer->n_mobile); ?>"
                                    data-address="<?php echo e($customer->c_address); ?>" data-state="<?php echo e($customer->n_state_id); ?>"
                                    data-district="<?php echo e($customer->n_district_id); ?>" <?php echo e(isset($sale->n_customer_id) && $sale->n_customer_id==$customer->n_customer_id ? "selected": ''); ?>>
                                    <?php echo e($customer->c_customer_name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                        </select>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>


                    <div class="col-md-6">
                        <label for="c_customer_email" class="form-label">Customer Email *</label>

                        <input type="text" id="c_customer_email" name="c_customer_email"
                            value="<?php echo e(old('c_customer_email',isset($sale) ? $sale->c_customer_email : '')); ?>"
                            <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>

                            data-message="Please enter Customer Email" class="form-control mandatory"
                            placeholder="Enter Customer Email">

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                </div>



                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <label for="n_customer_mobile" class="form-label">Customer Mobile *</label>

                        <input type="text" id="n_customer_mobile" name="n_customer_mobile"
                            value="<?php echo e(old('n_customer_mobile',isset($sale) ? $sale->n_customer_mobile : '')); ?>"
                            <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>

                            data-message="Please enter Customer Mobile" class="form-control mandatory"
                            placeholder="Enter Customer Mobile">

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>


                    <div class="col-md-6">
                        <label for="c_customer_address" class="form-label">Customer Address *</label>

                        <input type="text" id="c_customer_address" name="c_customer_address"
                            value="<?php echo e(old('c_customer_address',isset($sale) ? $sale->c_customer_address : '')); ?>"
                            <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>

                            data-message="Please add Customer Address" class="form-control mandatory"
                            placeholder="Customer Address">

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                </div>



                <div class="row g-4 mb-4">

                    <div class="col-md-6">

                        <label class="form-label">State</label>

                        <select class="form-select mandatory" data-message="Please enter State" id="customer_state"
                            name="n_state_id" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>

                            <option value="">Select State</option>

                            <?php if(isset($states)): ?>
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $State): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($State->n_state_id); ?>"
                                <?php echo e(old('n_state_id', $sale->n_state_id ?? '') == $State->n_state_id ? 'selected' : ''); ?>>
                                <?php echo e($State->name); ?>

                            </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                        </select>

                        <div class="text-danger mt-1 fs-2"></div>

                    </div>



                    <div class="col-md-6">

                        <label class="form-label">District</label>

                        <select class="form-select mandatory" data-message="Please enter District"
                            id="customer_district" name="n_district_id"
                            <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>

                            <option value="">Select District</option>

                            <?php if(isset($sale->n_district_id)): ?>

                            <?php
                            $districts = \App\Models\District::where('state_id', $sale->n_state_id)->get();
                            ?>

                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($district->id); ?>"
                                <?php echo e(old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : ''); ?>>
                                <?php echo e($district->district_name); ?>

                            </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php endif; ?>

                        </select>

                        <div class="text-danger mt-1 fs-2"></div>

                    </div>

                </div>

            </div>
            <!-- Section 4: Payment Details -->
            <div class="form-box">

                <div class="form-section-header mb-3">
                    <i class="ti ti-credit-card fs-5"></i>
                    Payment Details
                </div>



                <!-- Payment Details Section -->
                <div class="form-box mb-4">

                    <div class="form-section-header mb-3">
                        <i class="ti ti-wallet fs-5"></i>
                        Payment Details
                    </div>

                    <div class="row mb-3 align-items-center">

                        <label class="col-md-3 col-form-label fw-semibold">
                            Mode of Payment *
                        </label>

                        <div class="col-md-9">

                            <div class="payment-option">
                                <input class="form-check-input mandatory" type="radio" name="c_mode_of_payment" id="cod"
                                    value="cash_on_delivery">

                                <label for="cod" class="mb-0">
                                    <i class="ti ti-truck"></i>
                                    Cash on Delivery
                                </label>
                            </div>


                            <div class="payment-option">
                                <input class="form-check-input" type="radio" name="c_mode_of_payment" id="upi"
                                    value="UPI">

                                <label for="upi" class="mb-0">
                                    <i class="ti ti-brand-google-pay"></i>
                                    UPI
                                </label>
                            </div>

                            <div class="payment-option">
                                <input class="form-check-input" type="radio" name="c_mode_of_payment" id="bkd"
                                    value="Bank Deposit">

                                <label for="bkd" class="mb-0">
                                    <i class="ti ti-building-bank"></i>
                                    Bank Deposit
                                </label>
                            </div>
                            <div class="payment-option">
                                <input class="form-check-input" type="radio" name="c_mode_of_payment" id="pf"
                                    value="Paid to Franchise">

                                <label for="pf" class="mb-0">
                                    <i class="ti ti-cash"></i>
                                    Paid to Franchise
                                </label>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- Franchise Details Section -->
                <div class="form-box">

                    <div class="form-section-header mb-3">
                        <i class="ti ti-map-pin fs-5"></i>
                        Franchise Details
                    </div>


                    <div class="row g-4 mb-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                State <span class="text-danger">*</span>
                            </label>

                            <select class="form-select mandatory" id="franchise_state" name="n_state_id"
                                data-message="Please Select State">

                                <option value="">Select State</option>

                                <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($state->n_state_id); ?>"
                                    <?php echo e(old('n_state_id', $sale->n_state_id ?? '') == $state->n_state_id ? 'selected' : ''); ?>>

                                    <?php echo e($state->name); ?>


                                </option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>

                            <?php $__errorArgs = ['n_state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1 fs-2">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                District <span class="text-danger">*</span>
                            </label>

                            <select class="form-select mandatory" id="franchise_district" name="n_district_id"
                                data-message="Please Select District">

                                <option value="">
                                    Select District
                                </option>

                            </select>

                            <?php $__errorArgs = ['n_district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1 fs-2">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                    </div>


                    <div class="row g-4 mb-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                Nearest Franchise
                            </label>

                            <select class="form-select mandatory" id="franchise" name="nearest_franchise_id"
                                data-message="Please enter Nearest Franchise">

                                <option value="">
                                    Select Franchise
                                </option>

                                <?php if(isset($franchises)): ?>

                                <?php $__currentLoopData = $franchises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $franchise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($franchise->n_store_id); ?>"
                                    <?php echo e(old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : ''); ?>>

                                    <?php echo e($franchise->c_store_name); ?> (<?php echo e($franchise->c_store_code); ?>)

                                </option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php endif; ?>

                            </select>

                        </div>

                    </div>

                </div>

                <!-- Buttons -->
                <div class="mt-3 d-flex gap-2">
                    <?php if(isset($viewmode) && $viewmode=="on"): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leads.follow-up')): ?>
                    <!--Follow-up Button-->
                    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc"
                        data-bs-toggle="modal" data-bs-target="#followUpModal"
                        data-id="<?php echo e(isset($sale) ? Crypt::encryptString($sale->n_sl_no) : ''); ?>" id="followup">Update
                        Follow-up</button>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leads.approve')): ?>
                    <!--Approval Button-->
                    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc"
                        data-bs-toggle="modal" data-bs-target="#approveModal"
                        data-id="<?php echo e(isset($sale) ? Crypt::encryptString($sale->n_sl_no) : ''); ?>"
                        id="approve">Approve</button>
                    <?php endif; ?>
                    <?php else: ?>
                    <button type="button" class="btn mt-1 buttonSpc"
                        id="btn_create"><?php echo e(isset($sale->n_sl_no) ? 'Update' : 'Create'); ?></button>
                    <a href="<?php echo e(route('admin.leads.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
        </form>
    </div>
</div>

<!-- Follow-up Modal -->
<div class="modal fade" id="followUpModal" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="<?php echo e(route('admin.leads.followup.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="modal-header">
                    <h5 class="modal-title text-white" id="followUpModalLabel">
                        Lead Follow-up Form
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="lead_id" value="<?php echo e($lead->id ?? ''); ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Follow-up Date</label>
                            <input type="date" name="followup_date" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Next Follow-up Date</label>
                            <input type="date" name="next_followup_date" class="form-control">
                        </div>

                        <?php if(isset($user) && $user->identifier != "FCA"): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Follow-up Type</label>
                            <select name="followup_type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Phone Call">Phone Call</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Site Visit">Site Visit</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lead Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Interested">Interested</option>
                                <option value="Negotiation">Negotiation</option>
                                <option value="Won">Won</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option>Low</option>
                                <option selected>Medium</option>
                                <option>High</option>
                                <option>Urgent</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reminder</label>
                            <input type="datetime-local" name="reminder_at" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="4"
                                placeholder="Enter follow-up remarks..." required></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn buttonSpc">
                        Save Follow-up
                    </button>

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<!--Approval Form modal-->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="approveForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="approveModalLabel">Approval</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="approval_id">

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Approval Status</label>
                        <select class="form-select" name="status">
                            <option value="Approved">Pending</option>
                            <option value="Approved">Approve</option>
                            <option value="Rejected">Reject</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn buttonSpc">Submit</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>

        </form>
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    console.log("First script loaded");
    let rowIndex = <?php echo e(isset($sale) ? $sale->orderProducts->count() : 0); ?>;
    $("#addRow").click(function() {

        let row = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-control product mandatory" data-message="Please Select Product">
                            <option value="">Select Product</option>

                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->n_product_id); ?>"
                                        data-price="<?php echo e($product->n_selling_price); ?>">
                                    <?php echo e($product->c_product_name); ?>(<?php echo e($product->c_product_code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </td>

                    <td>
                        <input type="text"
                            name="products[${rowIndex}][product_price]"
                            class="form-control price"
                            readonly>
                    </td>

                    <td>
                        <input type="number"
                            min="1"
                            value="1"
                            name="products[${rowIndex}][qty]"
                            class="form-control qty">
                    </td>

                    <td>
                        <input type="text"
                            class="form-control total"
                            name="products[${rowIndex}][product_total]"
                            readonly>
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-danger removeRow">
                            X
                        </button>
                    </td>
                </tr>`;

        $("#productTable tbody").append(row);

        rowIndex++;
    });


    $(document).on("change", ".product", function() {

        productTotal($(this));

    });

    $(document).on("change", ".qty", function() {

        productTotal($(this).parent().siblings().find(".product"));

    });



    $(document).on("click", ".removeRow", function() {
        $(this).closest("tr").remove();
    });

    $(document).on("change", "#state", function() {
        var state = $(this).val();
        $.ajax({
            type: "get",
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            data: {
                state: state
            },
            cache: false,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $("#district").empty();
                $("#district").append('<option value="">Select District</option>');

                $.each(data.districts, function(index, district) {
                    $("#district").append(
                        '<option value="' + district.id + '">' + district
                        .district_name + '</option>'
                    );
                });

            }


        });
    });


    function productTotal(id) {
        let row = id.closest("tr");

        let price = id.find(":selected").data("price");

        row.find(".price").val(price);

        let qty = row.find(".qty").val();

        row.find(".total").val(price * qty);
    }

    const approveModal = document.getElementById('approveModal');

    approveModal.addEventListener('show.bs.modal', function(event) {

        let button = event.relatedTarget;
        let id = button.getAttribute('data-id');

        document.getElementById('approval_id').value = id;

        // Set form action dynamically
        document.getElementById('approveForm').action = "<?php echo e(route('admin.leads.approval.save')); ?>";
    });
});
</script>

<!--  ======================================================
 Customer Selection
 Auto-populate customer details and load customer district
 based on the selected customer's state.
 ====================================================== -->
<script>
$(document).ready(function() {
    $("#customer_id").change(function() {

        let option = $(this).find(":selected");

        $("#c_customer_email").val(option.data("email"));
        $("#n_customer_mobile").val(option.data("mobile"));
        $("#c_customer_address").val(option.data("address"));

        let stateId = option.data("state");
        let districtId = option.data("district");

        $("#customer_state").val(stateId);

        $.ajax({
            type: "GET",
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            data: {
                state: stateId
            },
            dataType: "json",
            success: function(data) {

                $("#customer_district").html('<option value="">Select District</option>');

                $.each(data.districts, function(i, district) {
                    $("#customer_district").append(
                        '<option value="' + district.id + '">' +
                        district.district_name +
                        '</option>'
                    );
                });

                // Select customer's district after options are loaded
                $("#customer_district").val(districtId);

            }
        });

    });
});
</script>

<!--======================================================
 Franchise State Selection
 Load districts based on the selected franchise state.
 ====================================================== -->

<script>
$('#franchise_state').change(function() {

    let stateId = $(this).val();

    $('#franchise_district').html('<option value="">Loading...</option>');

    $.ajax({

        url: "<?php echo e(route('admin.filterDistrict')); ?>",

        type: "GET",

        data: {
            state: stateId
        },

        success: function(response) {

            $('#franchise_district').html('<option value="">Select District</option>');

            $.each(response.districts, function(index, district) {

                $('#franchise_district').append(
                    '<option value="' + district.id + '">' +
                    district.district_name +
                    '</option>'
                );

            });

        }

    });

});
</script>

<!--======================================================
 Franchise District Selection
 Load nearest franchise list based on the selected
 franchise state and district.
 =========================================================-->

<script>
    $('#franchise_district').change(function() {

        let stateId = $('#franchise_state').val();
        let districtId = $(this).val();

        $.ajax({
            url: "<?php echo e(url('admin/filter-franchise')); ?>",
            type: "GET",
            data: {
                state: stateId,
                district: districtId
            },
            dataType: "json",
            success: function(response) {

                $('#franchise').html('<option value="">Select Franchise</option>');

                $.each(response.franchises, function(i, franchise) {

                    $('#franchise').append(
                        '<option value="' + franchise.n_store_id + '">' +
                        franchise.c_store_name + ' (' + franchise.c_store_code + ')' +
                        '</option>'
                    );

                });

            }
        });

    });
</script>


<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>