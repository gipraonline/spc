<?php $__env->startPush('styles'); ?>
<style>
    /* Creative Light Theme & Green Color Combinations */
    :root {
        --bg-body: #f4f8f5;
        --card-bg: #ffffff;
        --primary-green: #0f5132;
        --emerald-green: #059669;
        --light-green-bg: #f0fdf4;
        --border-green: #d1e7dd;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background-color: var(--card-bg);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-green);
    }

    /* Form Section Box Styling */
    .form-section, .form-box, .border.rounded {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 24px !important;
        margin-bottom: 24px !important;
        background-color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(15, 81, 50, 0.02);
    }

    /* Section Titles */
    .section-title, .form-section-header {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-green);
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Green Badge Pills matching the reference image */
    .badge-new {
        background-color: #059669;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-updated {
        background-color: #10b981;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-renamed {
        background-color: #047857;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Labels & Form Controls */
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        color: #1e293b;
        background-color: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--emerald-green);
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
        outline: none;
    }

    /* Highlighted & Readonly Inputs */
    .order-number, .advisor-highlight {
        background-color: #f4f8f5 !important;
        color: var(--primary-green) !important;
        font-weight: 700 !important;
        border-color: var(--border-green) !important;
    }

    /* Primary & Secondary Buttons */
    .buttonSpc, #addRow, #btn_create {
        background: linear-gradient(135deg, #0f5132 0%, #059669 100%) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 10px 22px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
        transition: all 0.2s ease;
    }

    .buttonSpc:hover, #addRow:hover, #btn_create:hover {
        background: linear-gradient(135deg, #0b3e26 0%, #047857 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3);
    }

    .btn-outline-secondary {
        border: 1px solid #cbd5e1 !important;
        color: #475569 !important;
        border-radius: 8px !important;
        padding: 10px 20px !important;
        font-weight: 600 !important;
    }

    .btn-outline-secondary:hover {
        background-color: #f8fafc !important;
        color: #1e293b !important;
    }

    /* Product Table Styling */
    #productTable {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    #productTable thead th {
        background-color: #f8faf8;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        padding: 12px 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    #productTable tbody td {
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .removeRow {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
    }

    .removeRow:hover {
        background-color: #b91c1c !important;
    }

    /* Summary Box on Right Side of Product Details */
    .product-summary-box {
        background-color: #f8faf8;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
    }

    .summary-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 12px;
    }

    .summary-line:last-child {
        margin-bottom: 0;
    }

    .summary-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        flex: 1;
    }

    .summary-input {
        width: 130px;
        text-align: right;
        font-weight: 600;
        background-color: #ffffff !important;
    }

    .summary-line.highlight-green {
        background-color: #e8f5e9;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #a7f3d0;
        margin-top: 8px;
    }

    .summary-line.highlight-green .summary-label {
        color: #0f5132;
        font-size: 14px;
    }

    .summary-line.highlight-green .summary-input {
        color: #0f5132 !important;
        font-size: 16px;
        font-weight: 800;
        border-color: #a7f3d0;
    }

    /* Payment Radio Options */
    .payment-option {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 18px;
        margin-right: 12px;
        margin-bottom: 8px;
        cursor: pointer;
        background-color: #ffffff;
        transition: all 0.2s ease;
    }

    .payment-option:hover {
        background-color: #f4f8f5;
        border-color: #0f5132;
    }

    .payment-option input[type="radio"] {
        margin: 0;
        accent-color: #0f5132;
        width: 16px;
        height: 16px;
    }

    .payment-option input[type="radio"]:checked + label {
        color: #0f5132;
        font-weight: 700;
    }

    /* Modal Backdrop Z-Index fix */
    #approveModal {
        z-index: 1060 !important;
    }

    .modal-backdrop {
        z-index: 1050 !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Crypt;
?>
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
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

        <form method="POST" id="frm_create" action="<?php echo e(route('admin.salesorders.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="id" class="form-control" value="<?php echo e(isset($sale) ? $sale->n_sl_no : ''); ?>">

            <!-- Section 1: Order Information -->
            <div class="form-section mb-4">

                <div class="section-title mb-3">
                    <i class="ti ti-file-invoice fs-5"></i>
                    Order Information
                </div>

                <!-- Row 1: Date & Booklet Serial No -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="d_date" class="form-control mandatory"
                            data-message="Please Select a Date"
                            value="<?php echo e(old('d_date', isset($sale) ? $sale->d_date->format('Y-m-d') : date('Y-m-d'))); ?>"
                            <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Booklet Serial No *
                        </label>
                        <div class="position-relative">
                            <input type="text" name="c_booklet_serial_no" placeholder="BK-2026-0417" class="form-control order-number fw-bold text-success"
                                value="<?php echo e(old('c_booklet_serial_no', isset($sale->c_booklet_serial_no) ? $sale->c_booklet_serial_no : '')); ?>" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>
                        </div>
                        <small class="text-success fs-1 mt-1 d-block">✓ Booklet Serial No is available.</small>
                    </div>

                </div>

                <!-- Row 2: Order No & Order Amount -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Order No *</label>
                        <input type="text" name="c_order_no" class="form-control mandatory order-number"
                            data-message="Please Enter Order No"
                            value="<?php echo e(old('c_order_no', isset($sale) ? $sale->c_order_no : '')); ?>" >
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Farm Care Advisor *
                        </label>

                        <?php if($isFarmCareAdvisor): ?>
                        <input type="text" class="form-control advisor-highlight" value="<?php echo e(auth()->user()->c_name); ?>" readonly>
                        <?php else: ?>
                        <select name="farm_care_advisor_id" class="form-control mandatory">
                            <option value="">Select Farm Care Adviser</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->n_employee_id); ?>" <?php echo e(isset($sale) && $sale->farm_care_advisor_id == $employee->n_employee_id  ? 'selected' : ''); ?>>
                                <?php echo e($employee->c_employee_name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php endif; ?>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>


                </div>

                <!-- Row 3: Farm Care Advisor & Sales Order Booklet Proof -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Sales Order Booklet Proof *
                        </label>
                        <input type="file" name="f_booklet_proof" class="form-control">
                    </div>

                </div>

            </div>

            <!-- Section 2: Product Details -->
            <div class="form-section mb-4">

                <div class="section-title d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <i class="ti ti-shopping-cart fs-5"></i>
                        Product Details *
                    </div>

                    <?php if(!isset($viewmode) || $viewmode=='off'): ?>
                    <button type="button" class="btn buttonSpc btn-sm" id="addRow">
                        <i class="ti ti-plus"></i>
                        Add New Product
                    </button>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="productTable">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">Product</th>
                                <th width="15%">Price</th>
                                <th width="15%">Quantity</th>
                                
                                <th width="12%">Total</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($sale->orderProducts) && count($sale->orderProducts) > 0): ?>
                                <?php $__currentLoopData = $sale->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <select name="products[<?php echo e($key); ?>][product_id]" class="form-control product mandatory">
                                            <option value="">Select Product</option>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($product->n_product_id); ?>" data-price="<?php echo e($product->n_mrp); ?>"
                                                <?php echo e($val->product_id == $product->n_product_id ? 'selected' : ''); ?>>
                                                <?php echo e($product->c_product_name); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="products[<?php echo e($key); ?>][product_price]" class="form-control price" value="<?php echo e($val->product_price); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="products[<?php echo e($key); ?>][qty]" class="form-control qty" value="<?php echo e($val->qty); ?>">
                                    </td>
                                    
                                    <td>
                                        <input type="text" name="products[<?php echo e($key); ?>][product_total]" class="form-control total" value="<?php echo e($val->product_total); ?>" readonly>
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

                <!-- Product Details Summary Box (Right Aligned as shown in shared image) -->

                <div class="row justify-content-end mt-4">
                    <div class="col-md-6 col-lg-5">
                        <div class="product-summary-box">

                            <!-- Total Sales Amount -->
                            <div class="summary-line">
                                <span class="summary-label">
                                    Total Sales Amount
                                </span>

                                <input type="text"
                                    name="n_total_sales_amount"
                                    class="form-control summary-input text-end"
                                    id="summaryTotalSales"
                                    value="<?php echo e(old('n_total_sales_amount', $sale->n_total_sales_amount ?? '0.00')); ?>"
                                    readonly>
                            </div>

                            <!-- Product Discount Total -->
                          

                            <!-- Additional Discount -->
                           

                            <!-- Total Discount -->
                            <div class="summary-line">
                                <span class="summary-label">
                                    Total Discount
                                </span>

                                <input type="text"
                                    name="n_total_discount"
                                    class="form-control"
                                    id="summaryTotalDiscount"
                                    value="<?php echo e(old('n_total_discount', $sale->n_total_discount ?? '0.00')); ?>"
                                    >
                            </div>

                            <!-- Net Sales Amount -->
                            <div class="summary-line highlight-green">
                                <span class="summary-label fw-bold">
                                    Net Sales Amount
                                </span>

                                <input type="text"
                                    name="n_net_sales_amount"
                                    class="form-control summary-input text-end fw-bold text-success"
                                    id="summaryNetSales"
                                    value="<?php echo e(old('n_net_sales_amount', $sale->n_net_sales_amount ?? '0.00')); ?>"
                                    readonly>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Section 3: Customer Information -->
            <div class="border rounded p-4 mb-4">

                <div class="form-section-header mb-3">
                    <i class="ti ti-user fs-5"></i> Customer Information
                </div>

                <input type="hidden" name="c_customer_name" id="c_customer_name" value="">
                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <label for="c_customer_name" class="form-label">Customer *</label>

                        <select name="n_customer_id" id="n_customer_id" class="form-select mandatory">
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

                <div class="row mb-4 align-items-center">
                    <label class="col-md-3 col-form-label fw-semibold">
                        Mode of Payment *
                    </label>

                    <div class="col-md-9">
                        <div class="payment-option">
                            <input class="form-check-input mandatory mode_of_payment" type="radio" name="c_mode_of_payment" id="cod"
                                value="Cash on delivery" checked <?php echo e(old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "cash_on_delivery" ? 'checked' : ''); ?>>

                            <label for="cod" class="mb-0">
                                <i class="ti ti-truck"></i>
                                Cash on Delivery
                            </label>
                        </div>

                        <div class="payment-option">
                            <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="upi"
                                value="UPI" <?php echo e(old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "UPI" ? 'checked' : ''); ?>>

                            <label for="upi" class="mb-0">
                                <i class="ti ti-brand-google-pay"></i>
                                UPI
                            </label>
                        </div>

                        <div class="payment-option">
                            <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="bkd"
                                value="Bank Deposit"  <?php echo e(old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "Bank Deposit" ? 'checked' : ''); ?>>

                            <label for="bkd" class="mb-0">
                                <i class="ti ti-building-bank"></i>
                                Bank Deposit
                            </label>
                        </div>

                        <div class="payment-option">
                            <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="pf"
                                value="Paid to Franchise"  <?php echo e(old('mode_of_payment', $sale->c_mode_of_payment ?? '') == "Paid to Franchise" ? 'checked' : ''); ?>>

                            <label for="pf" class="mb-0">
                                <i class="ti ti-cash"></i>
                                Paid to Franchise
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Extra Fields (as shown in reference image) -->
                <div class="row g-4 mt-2">
                    <div class="col-md-4">
                        <label class="form-label">
                            Amount to Pay * <span class="badge-new">NEW</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success fw-bold">₹</span>
                            <input type="text" name="n_amount_to_pay" class="form-control fw-bold text-success" value="4,250.00" readonly>
                        </div>
                        <small class="text-muted fs-1 mt-1 d-block">Should match product total: ₹4,250.00</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Transaction ID * <span class="badge-new">NEW</span>
                        </label>
                        <input type="text" name="c_transaction_id" class="form-control" placeholder="Enter Transaction / UTR / Reference No">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Transaction Proof * <span class="badge-updated">UPDATED</span>
                        </label>
                        <input type="file" name="f_transaction_proof" class="form-control">
                    </div>
                </div>

            </div>

            <!-- Section 5: Franchise Details Section -->
            <div class="form-box">

                <div class="form-section-header mb-3">
                    <i class="ti ti-map-pin fs-5"></i>
                    SPC Organic Clinic / Franchise / Stock Point Details <span class="badge-renamed">RENAMED</span>
                </div>

                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <label class="form-label">
                            State <span class="text-danger">*</span>
                        </label>

                        <select class="form-select mandatory" id="franchise_state" name="n_state_id"
                            data-message="Please Select State">

                            <option value="">Select State</option>

                            <?php if(isset($states)): ?>
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($state->n_state_id); ?>"
                                <?php echo e(old('n_state_id', $sale->n_state_id ?? '') == $state->n_state_id ? 'selected' : ''); ?>>
                                <?php echo e($state->name); ?>

                            </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

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
                        <label for="state" class="form-label">District</label>
                        <select class="form-select mandatory" data-message="Please enter District" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>  id="franchise_district" name="n_district_id">
                            <option value="" selected>Select District</option>
                            <?php if(isset($sale->n_district_id)): ?>
                                <?php $districts = \App\Models\District::where('state_id', $sale->n_state_id)->get(); ?>
                                <?php if(isset($districts)): ?>
                                    <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($district->id); ?>" <?php echo e(old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : ''); ?>><?php echo e($district->district_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endif; ?>

                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
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

                    <div class="col-md-6" style="position:relative;display:none;" id="payment_image">
                        <label class="form-label">
                            Image
                        </label>
                        <input type="file" name="payment_image" class="form-control">
                    </div>

                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-4 d-flex gap-2">
                <?php if(isset($viewmode) && $viewmode=="on"): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.follow-up')): ?>
                <!--Follow-up Button-->
                <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc"
                    data-bs-toggle="modal" data-bs-target="#followUpModal"
                    data-id="<?php echo e(isset($sale) ? Crypt::encryptString($sale->n_sl_no) : ''); ?>" id="followup">Update
                    Follow-up</button>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.approval')): ?>
                <!--Approval Button-->
                <button type="button"
                    style="width:150px;position:relative;"
                    class="btn mt-1 buttonSpc"
                    data-bs-toggle="modal"
                    data-bs-target="#approveModal"
                    data-bs-dismiss="modal"
                    data-id="<?php echo e(Crypt::encryptString($sale->n_sl_no)); ?>">
                    Approve
                </button>
                <?php endif; ?>
                <?php if(isset($sale) && $sale->n_sl_no): ?>
                     <a href="<?php echo e(route('admin.invoice.download', $sale->n_sl_no)); ?>"><button type="button" class="btn mt-1 buttonSpc"
                    id="btn_create">Download Invoice</button></a>
               <?php endif; ?>
                <?php else: ?>
                <button type="button" class="btn buttonSpc"
                    id="btn_create"><?php echo e(isset($sale->n_sl_no) ? 'Update' : 'Create'); ?></button>
                <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Follow-up Modal -->
<div class="modal fade" id="followUpModal" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="<?php echo e(route('admin.salesorders.followup.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="modal-header" style="background: linear-gradient(135deg, #0f5132, #074E30);">
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


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
$(document).ready(function() {

    console.log("Sales Order JS loaded");

    /*
    |--------------------------------------------------------------------------
    | Product Rows
    |--------------------------------------------------------------------------
    */

    let rowIndex = $('#productTable tbody tr').length;

    /*
    |--------------------------------------------------------------------------
    | Add New Product
    |--------------------------------------------------------------------------
    */

    $('#addRow').on('click', function() {

        let row = `
            <tr>

                <td>
                    <select
                        name="products[${rowIndex}][product_id]"
                        class="form-control product mandatory"
                        data-message="Please Select Product">

                        <option value="">Select Product</option>

                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($product->n_product_id); ?>"
                                data-price="<?php echo e($product->n_mrp); ?>">
                                <?php echo e($product->c_product_name); ?> (<?php echo e($product->c_product_code); ?>)(<?php echo e($product->c_unit); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                    <div class="text-danger mt-1 fs-2"></div>
                </td>

                <td>
                    <input
                        type="text"
                        name="products[${rowIndex}][product_price]"
                        class="form-control price"
                        readonly>
                </td>

                <td>
                    <input
                        type="number"
                        name="products[${rowIndex}][qty]"
                        class="form-control qty"
                        value="1"
                        min="1">
                </td>`+

                /* <td>
                    <input
                        type="number"
                        name="products[${rowIndex}][discount]"
                        class="form-control discount"
                        value="0.00"
                        step="0.01">
                </td>
 */
                `
                <td>
                    <input
                        type="text"
                        name="products[${rowIndex}][product_total]"
                        class="form-control total"
                        readonly>
                </td>

                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-danger btn-sm removeRow">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>

            </tr>
        `;

        $('#productTable tbody').append(row);

        rowIndex++;

        console.log("Product row added. Current rowIndex:", rowIndex);
    });


    /*
    |--------------------------------------------------------------------------
    | Product Selection - Calculate Price & Total
    |--------------------------------------------------------------------------
    */

    $(document).on('change', '.product', function() {
        let product = $(this);
        productTotal(product);
    });


    /*
    |--------------------------------------------------------------------------
    | Quantity Change & Discount Change - Recalculate Total
    |--------------------------------------------------------------------------
    */

    $(document).on('input change', '.qty, .discount', function() {
        let row = $(this).closest('tr');
        let product = row.find('.product');
        productTotal(product);
    });


    /*
    |--------------------------------------------------------------------------
    | Product Total Function
    |--------------------------------------------------------------------------
    */

    /* function productTotal(productSelect) {
        let row = productSelect.closest('tr');
        let selectedOption = productSelect.find(':selected');
        let price = parseFloat(selectedOption.attr('data-price')) || parseFloat(row.find('.price').val()) || 0;
        let qty = parseFloat(row.find('.qty').val()) || 0;
        let discount = parseFloat(row.find('.discount').val()) || 0;

        let lineTotal = (price * qty) - discount;
        if (lineTotal < 0) lineTotal = 0;

        row.find('.price').val(price.toFixed(2));
        row.find('.total').val(lineTotal.toFixed(2));
    }
 */

    function productTotal(productSelect) {

        let row = productSelect.closest('tr');

        let selectedOption = productSelect.find(':selected');

        let price = parseFloat(selectedOption.attr('data-price'))
            || parseFloat(row.find('.price').val())
            || 0;

        let qty = parseFloat(row.find('.qty').val()) || 0;

        let discount = parseFloat(row.find('.discount').val()) || 0;

        let grossAmount = price * qty;

        let lineTotal = grossAmount - discount;

        if (lineTotal < 0) {
            lineTotal = 0;
        }

        row.find('.price').val(price.toFixed(2));
        row.find('.total').val(lineTotal.toFixed(2));

        calculateSummary();
    }

    function calculateSummary() {

        let totalSalesAmount = 0;
        let productDiscountTotal = 0;

        $('#productTable tbody tr').each(function () {

            let row = $(this);

            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;

            totalSalesAmount += price * qty;
            productDiscountTotal += discount;
        });

        let additionalDiscount =
            parseFloat($('#summaryAdditionalDiscount').val()) || 0;

        let totalDiscount =
            productDiscountTotal + additionalDiscount;

        let netSalesAmount =
            totalSalesAmount - totalDiscount;

        if (netSalesAmount < 0) {
            netSalesAmount = 0;
        }

        $('#summaryTotalSales').val(totalSalesAmount.toFixed(2));

        $('#summaryProductDiscount').val(
            productDiscountTotal.toFixed(2)
        );

        $('#summaryTotalDiscount').val(
            totalDiscount.toFixed(2)
        );

        $('#summaryNetSales').val(
            netSalesAmount.toFixed(2)
        );
    }

    $(document).on(
        'input',
        '#summaryTotalDiscount',
        function () {
            calculateSummary();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Remove Product Row
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
    });


    /*
    |--------------------------------------------------------------------------
    | Customer Selection
    |--------------------------------------------------------------------------
    */

    $('#n_customer_id').on('change', function() {

        let option = $(this).find(':selected');

        let email = option.data('email') || '';
        let mobile = option.data('mobile') || '';
        let address = option.data('address') || '';

        let stateId = option.data('state') || '';
        let districtId = option.data('district') || '';
        let customer_name = option.data('name') || '';

        $('#c_customer_email').val(email);
        $('#n_customer_mobile').val(mobile);
        $('#c_customer_address').val(address);

        $('#customer_state').val(stateId);
        $("#c_customer_name").val(customer_name);

        /*
        |--------------------------------------------------------------------------
        | Load Customer Districts
        |--------------------------------------------------------------------------
        */

        if (!stateId) {
            $('#customer_district').html('<option value="">Select District</option>');
            return;
        }

        $.ajax({
            type: 'GET',
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            data: { state: stateId },
            dataType: 'json',
            beforeSend: function() {
                $('#customer_district').html('<option value="">Loading...</option>');
            },
            success: function(data) {
                $('#customer_district').html('<option value="">Select District</option>');
                if (data.districts) {
                    $.each(data.districts, function(index, district) {
                        $('#customer_district').append(
                            '<option value="' + district.id + '">' + district.district_name + '</option>'
                        );
                    });
                }
                $('#customer_district').val(districtId);
            },
            error: function(xhr) {
                console.error('Customer district loading failed:', xhr.responseText);
                $('#customer_district').html('<option value="">Unable to load districts</option>');
            }
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Franchise State Selection
    |--------------------------------------------------------------------------
    */

    $('#franchise_state').on('change', function() {

        let stateId = $(this).val();

        $('#franchise_district').html('<option value="">Loading...</option>');
        $('#franchise').html('<option value="">Select Franchise</option>');

        if (!stateId) {
            $('#franchise_district').html('<option value="">Select District</option>');
            return;
        }

        $.ajax({
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            type: 'GET',
            data: { state: stateId },
            dataType: 'json',
            success: function(response) {
                $('#franchise_district').html('<option value="">Select District</option>');
                if (response.districts) {
                    $.each(response.districts, function(index, district) {
                        $('#franchise_district').append(
                            '<option value="' + district.id + '">' + district.district_name + '</option>'
                        );
                    });
                }
            },
            error: function(xhr) {
                console.error('Franchise district loading failed:', xhr.responseText);
                $('#franchise_district').html('<option value="">Unable to load districts</option>');
            }
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Franchise District Selection
    |--------------------------------------------------------------------------
    */

    $('#franchise_district').on('change', function() {

        let stateId = $('#franchise_state').val();
        let districtId = $(this).val();

        $('#franchise').html('<option value="">Loading...</option>');

        if (!stateId || !districtId) {
            $('#franchise').html('<option value="">Select Franchise</option>');
            return;
        }

        $.ajax({
            url: "<?php echo e(url('admin/filter-franchise')); ?>",
            type: 'GET',
            data: {
                state: stateId,
                district: districtId
            },
            dataType: 'json',
            success: function(response) {
                $('#franchise').html('<option value="">Select Franchise</option>');
                if (response.franchises) {
                    $.each(response.franchises, function(index, franchise) {
                        $('#franchise').append(
                            '<option value="' + franchise.n_store_id + '">' +
                            franchise.c_store_name + ' (' + franchise.c_store_code + ')' +
                            '</option>'
                        );
                    });
                }
            },
            error: function(xhr) {
                console.error('Franchise loading failed:', xhr.responseText);
                $('#franchise').html('<option value="">Unable to load franchise</option>');
            }
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Approval Modal
    |--------------------------------------------------------------------------
    */

    const approveModalEl = document.getElementById('approveModal');
    if (approveModalEl) {
        approveModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;
            const id = button.getAttribute('data-id');
            console.log('Approval ID:', id);
            document.getElementById('approval_id').value = id;
            document.getElementById('approval_remarks').value = '';
            document.getElementById('approval_status').value = '';
        });
    }

    $(document).on('click', '.approvalSubmit', function () {
        const id = $(this).attr('data-id');
        console.log('Encrypted ID:', id);
        $('#approval_id').val(id);
        $('#approvalForm').attr('action', "<?php echo e(route('admin.salesorders.approval.save')); ?>");
        $('#approval_remarks').val('');
        $('#approval_status').val('');
    });

    /*
    |--------------------------------------------------------------------------
    | Existing Product Rows Calculation
    |--------------------------------------------------------------------------
    */

    $('#productTable tbody .product').each(function() {
        if ($(this).val()) {
            productTotal($(this));
        }
    });

});
</script>

<script>
    $(document).ready(function() {
        $("#n_customer_id").change(function() {
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
                data: { state: stateId },
                dataType: "json",
                success: function(data) {
                    $("#customer_district").html('<option value="">Select District</option>');

                    $.each(data.districts, function(i, district) {
                        $("#customer_district").append(
                            '<option value="' + district.id + '">' + district.district_name + '</option>'
                        );
                    });

                    $("#customer_district").val(districtId);
                }
            });
        });
    });
</script>

<script>
$(document).ready(function(){

    $('#franchise_state').change(function() {
        let stateId = $(this).val();
        $('#franchise_district').html('<option value="">Loading...</option>');

        $.ajax({
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            type: "GET",
            data: { state: stateId },
            success: function(response) {
                $('#franchise_district').html('<option value="">Select District</option>');
                $.each(response.districts, function(index, district) {
                    $('#franchise_district').append(
                        '<option value="' + district.id + '">' + district.district_name + '</option>'
                    );
                });
            }
        });
    });

    $(".mode_of_payment").click(function(){
        var payment_mode=$(this).val();
        if(payment_mode=="UPI" || payment_mode=="Bank Deposit" || payment_mode=="Paid to Franchise" ){
            $("#payment_image").show();
        }
        else{
            $("#payment_image").hide();
        }
    });

});
</script>

<script>
$(document).ready(function(){
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
});
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>