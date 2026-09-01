<?php $__env->startPush('styles'); ?>
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"
/>

<style>
/* Creative Light Theme & Green Palette */
:root {
    --bg-body: #f4f8f5;
    --card-bg: #ffffff;
    --primary-green: #0f5132;
    --emerald-green: #059669;
    --light-green-bg: #f0fdf4;
    --border-green: #d1e7dd;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --border-slate: #e2e8f0;
}

/* Main Card Container */
.card {
    border-radius: 14px;
    border: 1px solid var(--border-slate);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    background-color: var(--card-bg);
}

.card-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-green);
}

/* Form Section Box Styling */
.form-section,
.form-box,
.border.rounded {
    border: 1px solid var(--border-slate) !important;
    border-radius: 12px !important;
    padding: 24px !important;
    margin-bottom: 24px !important;
    background-color: #ffffff !important;
    box-shadow: 0 2px 10px rgba(15, 81, 50, 0.02);
}

/* Section Titles */
.section-title,
.form-section-header {
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

/* Badge Pills */
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

/* Form Controls & Inputs */
.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.form-control,
.form-select {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    color: #1e293b;
    background-color: #ffffff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--emerald-green);
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    outline: none;
}

/* Highlighted Readonly Inputs */
.order-number,
.advisor-highlight {
    background-color: #f4f8f5 !important;
    color: var(--primary-green) !important;
    font-weight: 700 !important;
    border-color: var(--border-green) !important;
}

/* Radio Button Cards (Payment & Order Status) */
.payment-option,
.order-status-option {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 16px;
    margin-right: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    background-color: #ffffff;
    transition: all 0.2s ease;
}

.payment-option:hover,
.order-status-option:hover {
    background-color: #f4f8f5;
    border-color: var(--primary-green);
}

.payment-option input[type="radio"],
.order-status-option input[type="radio"] {
    margin: 0;
    accent-color: var(--primary-green);
    width: 16px;
    height: 16px;
}

.payment-option input[type="radio"]:checked+label,
.order-status-option input[type="radio"]:checked+label {
    color: var(--primary-green);
    font-weight: 700;
}

/* Primary & Secondary Buttons */
.buttonSpc,
#addRow,
#btn_create {
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

.buttonSpc:hover,
#addRow:hover,
#btn_create:hover {
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
    border: 1px solid var(--border-slate);
}

#productTable thead th {
    background-color: #f8faf8;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
    padding: 12px 14px;
    border-bottom: 1px solid var(--border-slate);
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

/* Product Details Summary Box */
.product-summary-box {
    background-color: #f8faf8;
    border: 1px solid var(--border-slate);
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
    width: 140px;
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

/* Modal Backdrop Z-Index */
#approveModal {
    z-index: 1060 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

/* Mobile Responsive Optimizations */
@media (max-width: 768px) {
    .card-body {
        padding: 16px !important;
    }

    .form-section,
    .form-box,
    .border.rounded {
        padding: 16px !important;
        margin-bottom: 16px !important;
    }

    .payment-option,
    .order-status-option {
        width: 100%;
        margin-right: 0;
    }

    .product-summary-box {
        width: 100% !important;
        margin-top: 16px;
    }

    .summary-input {
        width: 110px;
    }
}

.tablescrolll {
    overflow-x: scroll;
}


#productTable tbody td input,
#productTable tbody td select {
    width: stretch;
    min-width: 100%;
}


#productTable thead th {

    white-space: nowrap;
}

@media screen and (max-width:767px) {
    .summary-line {
        flex-wrap: wrap;
    }

    .text-end {
        text-align: left !important;
    }

    .section-title,
    .form-section-header {
        flex-wrap: wrap
    }

    .tablescrolll {
        overflow-x: scroll;
    }
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

        <form method="POST" id="frm_create" action="<?php echo e(route('admin.salesorders.store')); ?>"
            enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="id" class="form-control" value="<?php echo e(isset($sale) ? $sale->n_sl_no : ''); ?>">

            <!-- Section 1: Order Information -->
            <div class="form-section mb-4">

                <div class="section-title mb-3">
                    <i class="ti ti-file-invoice fs-5"></i>
                    Order Information
                </div>

                <!-- Row 1: Date & Booklet Serial No -->
                <div class="row g-3">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="d_date" class="form-control mandatory"
                            data-message="Please Select a Date"
                            value="<?php echo e(old('d_date', isset($sale) ? $sale->d_date->format('Y-m-d') : date('Y-m-d'))); ?>"
                            <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>

                        <div class="text-danger mt-1 fs-2"></div>
                    </div>
                    <?php if(isset($isFarmCareAdvisor) && $isFarmCareAdvisor==true ): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Booklet Serial No *
                            </label>
                            <div class="position-relative">
                                <input type="text" name="c_order_no" placeholder="BK-2026-0417"
                                    class="form-control order-number fw-bold text-success mandatory"
                                    data-message="Please Enter Booklet Serial No"
                                    value="<?php echo e(old('c_order_no', isset($sale->c_order_no) ? $sale->c_order_no : '')); ?>"
                                    <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>
                                <div class="text-danger mt-1 fs-2"></div>
                            </div>

                            <?php $__errorArgs = ['c_order_no'];
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
                    <?php endif; ?>
                    <?php if(isset($isTelecaller) && $isTelecaller==true): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tele Order No *
                            </label>
                            <div class="position-relative">
                                <input type="text" name="c_order_no" placeholder="BK-2026-0417"
                                    class="form-control order-number fw-bold text-success mandatory"
                                    data-message="Please Enter Booklet Serial No"
                                    value="<?php echo e($TeleorderNo); ?>">
                                <div class="text-danger mt-1 fs-2"></div>
                            </div>

                            <?php $__errorArgs = ['c_order_no'];
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
                    <?php endif; ?>
                </div>

                <!-- Row 2: Order No & Farm Care Advisor -->
                <div class="row g-3">

                    <?php if(
                        (!isset($isTelecaller) || $isTelecaller == false) &&
                        (!isset($isFarmCareOfficer) || $isFarmCareOfficer == false)
                        ): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Farm Care Advisor *
                            </label>

                            <?php if($isFarmCareAdvisor): ?>
                            <input type="text" class="form-control advisor-highlight" value="<?php echo e(auth()->user()->c_name); ?>"
                                readonly>
                            <?php else: ?>
                            <select name="farm_care_advisor_id" class="form-control "
                                data-message="Please Enter Farm Care Advisor">
                                <option value="">Select Farm Care Adviser</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->n_employee_id); ?>"
                                    <?php echo e(isset($sale) && $sale->farm_care_advisor_id == $employee->n_employee_id  ? 'selected' : ''); ?>>
                                    <?php echo e($employee->c_employee_name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>
                            <div class="text-danger mt-1 fs-2"></div>
                            <?php endif; ?>

                        </div>

                    



                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Sales Order Booklet Proof
                                <?php if(!isset($sale) || !$sale->booklet_image): ?>
                                    <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>

                            <input type="file"
                                name="booklet_image"
                                id="booklet_image"
                                class="form-control"
                                accept="image/*"
                                data-message="Please Enter Booklet Proof">

                            <!-- Tell Laravel to delete existing image -->
                            <input type="hidden"
                                name="remove_booklet_image"
                                id="remove_booklet_image"
                                value="0">

                            <div class="text-danger mt-1 fs-2"></div>

                            <!-- Image Preview -->
                            <div class="mt-3" id="booklet_image_preview_container">

                                <img
                                    id="booklet_image_preview"
                                    src="<?php echo e(isset($sale) && $sale->booklet_image ?  asset('uploads/booklet_images/' . $sale->booklet_image)  : ''); ?>"
                                    alt="Booklet Proof Preview"
                                    class="img-thumbnail"
                                    style="<?php echo e(isset($sale) && $sale->booklet_image ? '' : 'display:none;'); ?> width:50px; height:50px; object-fit:cover;">

                                <?php if(isset($sale) && $sale->booklet_image): ?>
                                    <br>

                                    <button type="button"
                                        id="remove_booklet_image_btn"
                                        class="btn btn-danger btn-sm mt-2">
                                        Remove Image
                                    </button>
                                <?php endif; ?>

                            </div>


                        </div>
                    <?php endif; ?>

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
                <div class="tablescrolll">
                    <table class="table table-bordered table-responsive align-middle" id="productTable">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Product</th>
                                <th width="12%">HSN Code</th>
                                <th width="12%">Price</th>
                                <th width="10%">Quantity</th>
                                <th width="13%">Unit</th>
                                <th width="12%">Discount</th>
                                <th width="10%">GST %</th>
                                <th width="13%">GST Amount</th>
                                <th width="13%">Discounted Price</th>
                                <th width="10%">MRP</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($sale->orderProducts) && count($sale->orderProducts) > 0): ?>
                            <?php $__currentLoopData = $sale->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <select name="products[<?php echo e($key); ?>][product_id]"
                                        class="form-control product mandatory" >

                                        <option value="">Select Product</option>

                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <option value="<?php echo e($product->n_product_id); ?>"
                                            data-price="<?php echo e($product->n_mrp); ?>"
                                            data-hsn-code="<?php echo e($product->c_hsn_code ?? ''); ?>"
                                            data-unit="<?php echo e($product->c_unit ?? ''); ?>"
                                            data-gst="<?php echo e($product->n_gst_percentage ?? 0); ?>"
                                            <?php echo e($val->product_id == $product->n_product_id ? 'selected' : ''); ?>>
                                            <?php echo e($product->c_product_name); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>
                                </td>

                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][c_hsn_code]"
                                        class="form-control c_hsn_code" value="<?php echo e($val->c_hsn_code); ?>" >
                                </td>


                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][product_price]"
                                        class="form-control price" value="<?php echo e($val->product_price); ?>" readonly>
                                </td>

                                <td>
                                    <input type="number" name="products[<?php echo e($key); ?>][qty]" class="form-control qty"
                                        value="<?php echo e($val->qty); ?>" min="1">
                                </td>

                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][c_unit]" class="form-control c_unit"
                                        value="<?php echo e($val->c_unit); ?>" readonly>
                                </td>

                                <td>
                                    <input type="number" name="products[<?php echo e($key); ?>][discount]"
                                        class="form-control discount" value="<?php echo e($val->discount ?? '0.00'); ?>" step="">
                                </td>

                                <!-- Product GST % -->
                                <td>
                                    <input type="number" name="products[<?php echo e($key); ?>][n_gst_percentage]"
                                        class="form-control gst_percentage" value="<?php echo e($val->n_gst_percentage ?? 0); ?>"
                                        step="0.01" readonly>
                                </td>

                                <!-- Product GST Amount -->
                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][gst_amount]"
                                        class="form-control gst_amount" value="<?php echo e($val->gst_amount ?? '0.00'); ?>"
                                        readonly>
                                </td>

                                <!-- Discounted Price -->
                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][discounted_price]"
                                        class="form-control discounted_price"
                                        value="<?php echo e($val->discounted_price ?? '0.00'); ?>" readonly>
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

                <!-- Product Details Summary Box (Right Aligned as shown in shared image) -->

                <div class="row justify-content-end mt-4">
                    <div class="col-md-6 col-lg-5">
                        <div class="product-summary-box">

                            <!-- Total Sales Amount -->
                            <div class="summary-line">
                                <span class="summary-label">
                                    Total Sales Amount
                                </span>

                                <input type="text" name="n_total_sales_amount"
                                    class="form-control summary-input text-end" id="summaryTotalSales"
                                    value="<?php echo e(old('n_total_sales_amount', $sale->n_total_sales_amount ?? '0.00')); ?>"
                                    readonly>
                            </div>

                            

                        <!-- Additional Discount -->
                        <div class="summary-line">
                            <span class="summary-label">
                                Total GST

                            </span>

                            <input type="number" name="n_total_gst" class="form-control summary-input text-end"
                                id="summaryGstAmount" value="<?php echo e(old('n_total_gst', $sale->n_total_gst ?? '0.00')); ?>"
                                step="0.01" min="0">
                        </div>
                        <!-- Total Discount -->
                        <div class="summary-line">
                            <span class="summary-label">
                                Total Discount
                            </span>

                            <input type="text" name="n_product_discount_total" class="form-control summary-input"
                                id="summaryTotalDiscount"
                                value="<?php echo e(old('n_total_discount', $sale->n_product_discount_total ?? '0.00')); ?>">
                        </div>

                        <!-- Net Sales Amount -->
                        <div class="summary-line highlight-green">
                            <span class="summary-label fw-bold">
                                Net Sales Amount
                            </span>

                            <input type="text" name="n_net_sales_amount"
                                class="form-control summary-input text-end fw-bold text-success" id="summaryNetSales"
                                value="<?php echo e(old('n_net_sales_amount', $sale->n_net_sales_amount ?? '0.00')); ?>" readonly>
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

        <input type="hidden" name="c_customer_name" id="c_customer_name"
            value="<?php echo e(isset($sale) ? $sale->c_customer_name : ''); ?>">

        
        <div class="row g-4 mb-4">

            <div class="col-md-6">
                <label for="n_customer_id" class="form-label">
                    Customer *
                </label>

                <select name="n_customer_id" id="n_customer_id" class="form-select mandatory">

                    <option value="">Select Customer</option>

                    <?php if(isset($customers)): ?>
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option value="<?php echo e($customer->n_customer_id); ?>" data-name="<?php echo e($customer->c_customer_name); ?>"
                        data-email="<?php echo e($customer->c_email); ?>" data-mobile="<?php echo e($customer->n_mobile); ?>"
                        data-address="<?php echo e($customer->c_address); ?>" data-state="<?php echo e($customer->n_state_id); ?>"
                        data-district="<?php echo e($customer->n_district_id); ?>" data-pincode="<?php echo e($customer->c_pincode); ?>" <?php echo e(isset($sale->n_customer_id) &&
                               $sale->n_customer_id == $customer->n_customer_id
                               ? 'selected'
                               : ''); ?>>

                        <?php echo e($customer->c_customer_name); ?>


                    </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                </select>

                <div class="text-danger mt-1 fs-2"></div>
            </div>


            <div class="col-md-6">
                <label for="c_customer_email" class="form-label">
                    Customer Email *
                </label>

                <input type="text" id="c_customer_email" name="c_customer_email"
                    value="<?php echo e(old('c_customer_email', isset($sale) ? $sale->c_customer_email : '')); ?>"
                    <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'readonly' : ''); ?>

                    data-message="Please enter Customer Email" class="form-control mandatory"
                    placeholder="Enter Customer Email">

                <div class="text-danger mt-1 fs-2"></div>
            </div>

        </div>


        
        <div class="row g-4 mb-4">

            <div class="col-md-6">
                <label for="n_customer_mobile" class="form-label">
                    Customer Mobile *
                </label>

                <input type="text" id="n_customer_mobile" name="n_customer_mobile"
                    value="<?php echo e(old('n_customer_mobile', isset($sale) ? $sale->n_customer_mobile : '')); ?>"
                    <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'readonly' : ''); ?>

                    data-message="Please enter Customer Mobile" class="form-control mandatory"
                    placeholder="Enter Customer Mobile">

                <div class="text-danger mt-1 fs-2"></div>
            </div>


            <div class="col-md-6">
                <label for="c_customer_pincode" class="form-label">
                    Pincode
                </label>

                <input type="text" id="c_customer_pincode" name="c_customer_pincode"
                    value="<?php echo e(old('c_customer_pincode', $sale->customer?->c_pincode ?? '')); ?>"
                    <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'readonly' : ''); ?> class="form-control"
                    placeholder="Enter Pincode" maxlength="6" pattern="[0-9]{6}" inputmode="numeric">

                <div class="text-danger mt-1 fs-2"></div>
            </div>

        </div>


        
        <div class="row g-4 mb-4">

            <div class="col-md-12">
                <label for="c_customer_address" class="form-label">
                    Customer Address *
                </label>

                <input type="text" id="c_customer_address" name="c_customer_address"
                    value="<?php echo e(old('c_customer_address', isset($sale) ? $sale->c_customer_address : '')); ?>"
                    <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'readonly' : ''); ?>

                    data-message="Please add Customer Address" class="form-control mandatory"
                    placeholder="Customer Address">

                <div class="text-danger mt-1 fs-2"></div>
            </div>

        </div>


        
        <div class="row g-4 mb-4">

            <div class="col-md-6">
                <label for="customer_state" class="form-label">
                    State
                </label>

                <select class="form-select mandatory" data-message="Please enter State" id="customer_state"
                    name="n_state_id" <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'disabled' : ''); ?>>

                    <option value="">Select State</option>

                    <?php if(isset($states)): ?>
                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $State): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option value="<?php echo e($State->n_state_id); ?>" <?php echo e(old('n_state_id', $sale->n_state_id ?? '') == $State->n_state_id
                               ? 'selected'
                               : ''); ?>>

                        <?php echo e($State->name); ?>


                    </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                </select>

                <div class="text-danger mt-1 fs-2"></div>
            </div>


            <div class="col-md-6">
                <label for="customer_district" class="form-label">
                    District
                </label>

                <select class="form-select mandatory" data-message="Please enter District" id="customer_district"
                    name="n_district_id" <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'disabled' : ''); ?>>

                    <option value="">Select District</option>

                    <?php if(isset($sale->n_district_id)): ?>

                    <?php
                    $districts = \App\Models\District::where(
                    'state_id',
                    $sale->n_state_id
                    )->get();
                    ?>

                    <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option value="<?php echo e($district->id); ?>" <?php echo e(old('n_district_id', $sale->n_district_id ?? '') == $district->id
                               ? 'selected'
                               : ''); ?>>

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
    <div class="form-box mb-4">

        <div class="form-section-header mb-3">
            <i class="ti ti-credit-card fs-5"></i>
            Payment Details
        </div>

        <div class="row mb-4 align-items-center">
            <label class="col-md-3 col-form-label fw-semibold">
                Mode of Payment *
            </label>

            <div class="col-md-9 d-flex flex-wrap">
                <div class="payment-option">
                    <input class="form-check-input mandatory mode_of_payment " type="radio" name="c_mode_of_payment"
                        id="cod" value="Cash on Delivery" data-message="Please Choose a Payment Mode"
                        <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Cash on Delivery" ? 'checked' : ''); ?>>

                    <label for="cod" class="mb-0">
                        <i class="ti ti-truck"></i>
                        Cash on Delivery
                    </label>

                </div>
                <?php if(isset($isTelecaller) && $isTelecaller==false): ?>
                <div class="payment-option">
                    <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="upi"
                        value="UPI"
                        <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "UPI" ? 'checked' : ''); ?>>

                    <label for="upi" class="mb-0">
                        <i class="ti ti-brand-google-pay"></i>
                        UPI
                    </label>
                </div>

                <div class="payment-option">
                    <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="bkd"
                        value="Bank Deposit"
                        <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Bank Deposit" ? 'checked' : ''); ?>>

                    <label for="bkd" class="mb-0">
                        <i class="ti ti-building-bank"></i>
                        Bank Deposit
                    </label>
                </div>
                <?php endif; ?>
                <div class="payment-option">
                    <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="pf"
                        value="Paid to Franchise"
                        <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Paid to Franchise" ? 'checked' : ''); ?>>

                    <label for="pf" class="mb-0">
                        <i class="ti ti-cash"></i>
                        Paid to Franchise
                    </label>
                </div>
                <div class="text-danger mt-1 fs-2"></div>
            </div>
        </div>

        <div class="row g-4 mt-1" id="ps">
            <div class="col-md-4">

                <label class="form-label fw-semibold">
                    Payment Status
                </label>

                <select name="payment_status" id="payment_status" data-message="Please Select Payment Status"
                    class="form-select">

                    <option value="">Select Status</option>

                    <option value="pending"
                        <?php echo e(old('payment_status', $sale->payment_status ?? '') == "pending" ? 'selected' : ''); ?>>Pending
                    </option>
                    <option value="paid"
                        <?php echo e(old('payment_status', $sale->payment_status ?? '') == "paid" ? 'selected' : ''); ?>>Paid
                    </option>

                </select>
                <div class="text-danger mt-1 fs-2"></div>

            </div>
        </div>

        <!-- Payment Details Extra Fields -->
        <div class="row g-4 mt-1" id="paymet-proofs" >
            <div class="col-md-4">
                <label class="form-label">
                    Amount to Pay *
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-success fw-bold">₹</span>
                    <input type="text" name="n_amount_to_pay" data-message="Please Enter Transaction id"
                        id="n_amount_to_pay" class="form-control fw-bold text-success" value="" readonly>
                </div>
                <small class="text-muted fs-1 mt-1 d-block">Should match product total: ₹4,250.00</small>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    Transaction ID *
                </label>
                <input type="text" id="c_transaction_id" name="c_transaction_id" value="<?php echo e(old('c_transaction_id', $sale->c_transaction_id ?? '')); ?>"
                    data-message="Please Enter Transaction id" class="form-control"
                    placeholder="Enter Transaction / UTR / Reference No">
                <div class="text-danger mt-1 fs-2"></div>
            </div>

           

            <div class="col-md-4">
                    <label class="form-label">
                        Transaction Proof
                        <?php if(!isset($sale) || !$sale->payment_image): ?>
                            <span class="text-danger">*</span>
                        <?php endif; ?>
                    </label>


                <input type="file"
                    id="payment_image"
                    name="payment_image"
                    data-message="Please Enter Transaction Proof"
                    class="form-control"
                    accept="image/*">

                <!-- Used to tell Laravel to delete the existing image -->
                <input type="hidden"
                    name="remove_payment_image"
                    id="remove_payment_image"
                    value="0">

                <div class="text-danger mt-1 fs-2"></div>


                <!-- Image Preview -->
                <div class="mt-3" id="payment_preview_container">

                    <img
                        id="payment_image_preview"
                        src="<?php echo e(isset($sale) && $sale->payment_image ? asset('uploads/payment_images/' . $sale->payment_image) : ''); ?>"
                        alt="Transaction Proof Preview"
                        class="img-thumbnail"
                        style="<?php echo e(isset($sale) && $sale->payment_image ? '' : 'display:none;'); ?> width:50px; height:50px; object-fit:cover;">

                    <?php if(isset($sale) && $sale->payment_image): ?>
                        <br>

                        <button type="button"
                            id="remove_payment_image_btn"
                            class="btn btn-danger btn-sm mt-2">
                            Remove Image
                        </button>
                    <?php endif; ?>

                </div>


                </div>


        </div>

    </div>
    

<!-- Section 6: Franchise / Company Details Section -->
    <div class="form-box mb-4" id="franchise-details">

       <?php if(isset($isAdmin) && $isAdmin==true): ?>

            <!-- Company / Franchise Selection -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-bold">
                        Order Type <span class="text-danger">*</span>
                    </label>

                    <div class="d-flex gap-4">

                        <!-- Company -->
                        <div class="form-check">
                            <input class="form-check-input mandatory"
                                type="radio"
                                name="order_type"
                                id="company"
                                value="company"
                                <?php echo e(old('order_type', $sale->order_type ?? '') == 'company' ? 'checked' : ''); ?>>

                            <label class="form-check-label" for="company">
                                Company
                            </label>
                        </div>

                        <!-- Franchise -->
                        <div class="form-check">
                            <input class="form-check-input mandatory"
                                type="radio"
                                name="order_type"
                                id="franchise_type"
                                value="franchise"
                                <?php echo e(old('order_type', $sale->order_type ?? '') == 'franchise' ? 'checked' : ''); ?>>

                            <label class="form-check-label" for="franchise_type">
                                Franchise
                            </label>
                        </div>

                    </div>
                </div>
            </div>
       <?php endif; ?>

        <!-- Franchise Location Details -->
        <div id="franchise-location-details">

            <div class="row g-4 mb-4">

                <!-- State -->
                <div class="col-md-6">
                    <label class="form-label">
                        State <span class="text-danger">*</span>
                    </label>

                    <select class="form-select mandatory"
                        id="franchise_state"
                        name="n_state_id"
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


                <!-- District -->
                <div class="col-md-6">
                    <label class="form-label">
                        District <span class="text-danger">*</span>
                    </label>

                    <select class="form-select "
                        id="franchise_district"
                        name="n_district_id"
                        data-message="Please Select District"
                        <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'disabled' : ''); ?>>

                        <option value="">Select District</option>

                        <?php if(isset($sale->n_district_id)): ?>
                            <?php
                                $districts = \App\Models\District::where(
                                    'state_id',
                                    $sale->n_state_id
                                )->get();
                            ?>

                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($district->id); ?>"
                                    <?php echo e(old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : ''); ?>>
                                    <?php echo e($district->district_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </select>
                </div>


                <!-- Panchayath -->
                <div class="col-md-6">
                    <label class="form-label">
                        Panchayath
                    </label>

                    <select class="form-select"
                        id="franchise_panchayath"
                        name="n_panchayath_id">

                        <option value="">Select Panchayath</option>

                        <?php if(isset($sale->n_district_id)): ?>

                            <?php
                                $panchayaths = \App\Models\Panchayath::where(
                                    'district_id',
                                    $sale->n_district_id
                                )->get();
                            ?>

                            <?php $__currentLoopData = $panchayaths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $panchayath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($panchayath->id); ?>"
                                    <?php echo e(old('n_panchayath_id', $franchisePanchayathId ?? '') == $panchayath->id ? 'selected' : ''); ?>>
                                    <?php echo e($panchayath->panchayath_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endif; ?>

                    </select>
                </div>


                <!-- Nearest Franchise -->
                <div class="col-md-6">
                    <label class="form-label">
                        Nearest Franchise <span class="text-danger">*</span>
                    </label>

                    <select class="form-select mandatory"
                        id="franchise"
                        name="nearest_franchise_id"
                        data-message="Please Select Nearest Franchise">

                        <option value="">Select Franchise</option>

                        <?php if(isset($franchises)): ?>
                            <?php $__currentLoopData = $franchises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $franchise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($franchise->n_store_id); ?>"
                                    <?php echo e(old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : ''); ?>>
                                    <?php echo e($franchise->c_store_name); ?>

                                    (<?php echo e($franchise->c_store_code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </select>
                </div>

            </div>

        </div>
    </div>
<!-- Action Buttons -->
<div class="mt-4 d-flex gap-2 flex-wrap">
    <?php if(isset($viewmode) && $viewmode=="on"): ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.approval')): ?>
    <!--Approval Button-->
    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc" data-bs-toggle="modal"
        data-bs-target="#approveModal" data-bs-dismiss="modal" data-id="<?php echo e(Crypt::encryptString($sale->n_sl_no)); ?>">
        Approve
    </button>
    <?php endif; ?>
    <?php if(isset($sale) && $sale->n_sl_no): ?>
    
    <a href="<?php echo e(route('admin.invoice-orders.preview', $sale->n_sl_no)); ?>" class="btn mt-1 buttonSpc">
        Order Summary Preview
    </a>

    <a href="<?php echo e(route('admin.invoice.download', $sale->n_sl_no)); ?>"><button type="button" class="btn buttonSpc"
            style="height:61px;margin-top: 4px;">Generate Invoice</button></a>
    <?php endif; ?>
    <?php else: ?>
    <button type="button" class="btn buttonSpc" style="width:150px;position:relative;"
        id="btn_create"><?php echo e(isset($sale->n_sl_no) ? 'Update' : 'Create'); ?></button>
    <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
    <?php endif; ?>
</div>

</form>

</div>




</form>
</div>
</div>


<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="approveForm" action="<?php echo e(route('admin.salesorders.approval.save')); ?>">

                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>


                <div class="modal-content">

                    <div class="modal-header" style="background: linear-gradient(135deg, #0f5132, #074E30);"">

                    <h5 class=" modal-title text-white" id="approveModalLabel">
                        Approval
                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="approval_id" id="approval_id">

                        <div class="mb-3">

                            <label class="form-label">
                                Remarks <span class="text-danger">*</span>
                            </label>

                            <textarea class="form-control" name="remarks" id="approval_remarks" rows="3"
                                required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Approval Status
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" name="status" id="approval_status" required>

                                <option value="">
                                    Select Status
                                </option>

                                <option value="Approved">
                                    Approve
                                </option>

                                <option value="Rejected">
                                    Reject
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn buttonSpc" id="approvalSubmit">
                            Submit
                        </button>

                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                    </div>

                </div>

            </form>



        </div>
    </div>
</div>


<!-- Approval Modal -->

<?php
$hasPaymentImage = isset($sale) && !empty($sale->payment_image);
?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    $(document).ready(function () {

        console.log("Sales Order JS loaded");

        /*
        |--------------------------------------------------------------------------
        | Product Row Index
        |--------------------------------------------------------------------------
        */

        let rowIndex = $('#productTable tbody tr').length;


        /*
        |--------------------------------------------------------------------------
        | Add New Product
        |--------------------------------------------------------------------------
        */

        $('#addRow').on('click', function () {

            let row = `
                <tr class="new-product-row">

                    <!-- Product -->

                    <td>
                        <select
                            name="products[${rowIndex}][product_id]"
                            class="form-control product mandatory"
                            data-message="Please Select Product">

                            <option value="">Select Product</option>

                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($product->n_product_id); ?>"
                                    data-price="<?php echo e($product->n_mrp); ?>"
                                    data-gst="<?php echo e($product->n_gst_percentage); ?>"
                                    data-hsn-code="<?php echo e($product->c_hsn_code); ?>"
                                    data-unit="<?php echo e($product->c_unit); ?>">

                                    <?php echo e($product->c_product_name); ?>

                                    (<?php echo e($product->c_product_code); ?>)
                                    (<?php echo e($product->c_unit); ?>)

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <div class="text-danger mt-1 fs-2"></div>
                    </td>


                    <!-- HSN Code -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][c_hsn_code]"
                            class="form-control c_hsn_code"
                            value=""
                            readonly>
                    </td>


                    <!-- Price -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][product_price]"
                            class="form-control price"
                            value="0.00"
                            readonly>
                    </td>


                    <!-- Quantity -->
                    <td>
                        <input
                            type="number"
                            name="products[${rowIndex}][qty]"
                            class="form-control qty"
                            value="1"
                            min="1">
                    </td>


                    <!-- Unit -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][c_unit]"
                            class="form-control c_unit"
                            value=""
                            readonly>
                    </td>


                    <!-- Discount -->
                    <td>
                        <input
                            type="number"
                            name="products[${rowIndex}][discount]"
                            class="form-control discount"
                            value="0.00"
                            step="0.01"
                            min="0">
                    </td>


                    <!-- GST % -->
                    <td>
                        <input
                            type="number"
                            name="products[${rowIndex}][n_gst_percentage]"
                            class="form-control gst_percentage"
                            value="0.00"
                            step="0.01"
                            readonly>
                    </td>


                    <!-- GST Amount -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][gst_amount]"
                            class="form-control gst_amount"
                            value="0.00"
                            readonly>
                    </td>


                    <!-- Discounted Price -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][discounted_price]"
                            class="form-control discounted_price"
                            value="0.00"
                            readonly>
                    </td>


                    <!-- Product Total -->
                    <td>
                        <input
                            type="text"
                            name="products[${rowIndex}][product_total]"
                            class="form-control total"
                            value="0.00"
                            readonly>
                    </td>


                    <!-- Remove -->
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

            console.log("New product row added");
        });


     /*    $(document).on('change', '.product', function () {

            let row = $(this).closest('tr');

            let selectedOption = $(this).find('option:selected');

            // Product values
            let price = parseFloat(selectedOption.data('price')) || 0;
            let gstPercentage = parseFloat(selectedOption.data('gst')) || 0;
            let hsnCode = selectedOption.data('hsn-code') || '';
            let unit = selectedOption.data('unit') || '';

            // Set values
            row.find('.price').val(price.toFixed(2));
            row.find('.gst_percentage').val(gstPercentage);
            row.find('.c_hsn_code').val(hsnCode);
            row.find('.c_unit').val(unit);

            // Reset values
            row.find('.qty').val(1);
            row.find('.discount').val('0.00');

            // Calculate
            productTotal(row);
        }); */


        /*
        |--------------------------------------------------------------------------
        | Product Selection
        |--------------------------------------------------------------------------
        | This will only affect NEW rows because existing product selects
        | should be disabled in the edit Blade.
        |--------------------------------------------------------------------------
        */

        $(document).on('change', '.new-product-row .product', function () {

            productTotal($(this));

        });


        /*
        |--------------------------------------------------------------------------
        | Quantity / Discount Change
        |--------------------------------------------------------------------------
        | This works for both existing and new rows.
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input change',
            '.qty, .discount',
            function () {

                let row = $(this).closest('tr');

                /*
                |--------------------------------------------------------------------------
                | Existing Row
                |--------------------------------------------------------------------------
                | Do calculations without reading product dropdown data.
                | Therefore saved HSN and Unit will never change.
                |--------------------------------------------------------------------------
                */

                if (!row.hasClass('new-product-row')) {

                    calculateExistingRow(row);
                    calculateSummary();

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | New Row
                |--------------------------------------------------------------------------
                */

                let product = row.find('.product');

                if (product.length && product.val()) {
                    productTotal(product);
                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | New Product Calculation
        |--------------------------------------------------------------------------
        */

        function productTotal(productSelect) {

            let row = productSelect.closest('tr');

            if (!row.length) {
                return;
            }

            let selectedOption = productSelect.find(':selected');

            let mrp = parseFloat(
                selectedOption.attr('data-price')
            ) || 0;

            let gstPercentage = parseFloat(
                selectedOption.attr('data-gst')
            ) || 0;

            let hsnCode =
                selectedOption.attr('data-hsn-code') || '';

            let unit =
                selectedOption.attr('data-unit') || '';

            let qty = parseFloat(
                row.find('.qty').val()
            ) || 0;

            let discount = parseFloat(
                row.find('.discount').val()
            ) || 0;


            if (qty < 0) {
                qty = 0;
            }

            if (discount < 0) {
                discount = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | MRP includes GST
            |--------------------------------------------------------------------------
            */

            let price = 0;
            let grossAmount = 0;
            let taxableAmount = 0;
            let gstAmount = 0;
            let lineTotal = 0;


            if (mrp > 0) {

                // GST exclusive price
                price = mrp / (1 + (gstPercentage / 100));

                // Price × Quantity
                grossAmount = price * qty;

                // After discount
                taxableAmount = grossAmount - discount;

                if (taxableAmount < 0) {
                    taxableAmount = 0;
                }

                // GST
                gstAmount =
                    taxableAmount * gstPercentage / 100;

                // Final Total
                lineTotal =
                    taxableAmount + gstAmount;
            }


            /*
            |--------------------------------------------------------------------------
            | Set Values - NEW ROW ONLY
            |--------------------------------------------------------------------------
            */

            row.find('.c_hsn_code').val(hsnCode);

            row.find('.c_unit').val(unit);

            row.find('.price').val(
                price.toFixed(2)
            );

            row.find('.gst_percentage').val(
                gstPercentage.toFixed(2)
            );

            row.find('.gst_amount').val(
                gstAmount.toFixed(2)
            );

            row.find('.discounted_price').val(
                taxableAmount.toFixed(2)
            );

            row.find('.total').val(
                lineTotal.toFixed(2)
            );


            calculateSummary();
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Product Row Calculation
        |--------------------------------------------------------------------------
        | Uses the values already saved in the database.
        | Does NOT touch HSN code or Unit.
        |--------------------------------------------------------------------------
        */

        function calculateExistingRow(row) {

            let price =
                parseFloat(row.find('.price').val()) || 0;

            let qty =
                parseFloat(row.find('.qty').val()) || 0;

            let discount =
                parseFloat(row.find('.discount').val()) || 0;

            let gstPercentage =
                parseFloat(row.find('.gst_percentage').val()) || 0;


            if (qty < 0) {
                qty = 0;
            }

            if (discount < 0) {
                discount = 0;
            }


            let grossAmount =
                price * qty;

            let taxableAmount =
                grossAmount - discount;

            if (taxableAmount < 0) {
                taxableAmount = 0;
            }


            let gstAmount =
                taxableAmount * gstPercentage / 100;

            let total =
                taxableAmount + gstAmount;


            /*
            |--------------------------------------------------------------------------
            | Update calculated values only
            |--------------------------------------------------------------------------
            */

            row.find('.gst_amount').val(
                gstAmount.toFixed(2)
            );

            row.find('.discounted_price').val(
                taxableAmount.toFixed(2)
            );

            row.find('.total').val(
                total.toFixed(2)
            );

            // HSN and Unit are intentionally NOT changed
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Summary
        |--------------------------------------------------------------------------
        */

        function calculateSummary() {

            let totalSales = 0;
            let productDiscount = 0;
            let totalGst = 0;


            $('#productTable tbody tr').each(function () {

                let row = $(this);

                let price =
                    parseFloat(row.find('.price').val()) || 0;

                let qty =
                    parseFloat(row.find('.qty').val()) || 0;

                let discount =
                    parseFloat(row.find('.discount').val()) || 0;

                let gstPercentage =
                    parseFloat(
                        row.find('.gst_percentage').val()
                    ) || 0;


                let grossAmount =
                    price * qty;

                let taxableAmount =
                    grossAmount - discount;


                if (taxableAmount < 0) {
                    taxableAmount = 0;
                }


                let gstAmount =
                    taxableAmount *
                    gstPercentage / 100;

                let productTotal =
                    taxableAmount + gstAmount;


                /*
                |--------------------------------------------------------------------------
                | Update calculations
                |--------------------------------------------------------------------------
                */

                row.find('.gst_amount').val(
                    gstAmount.toFixed(2)
                );

                row.find('.discounted_price').val(
                    taxableAmount.toFixed(2)
                );

                row.find('.total').val(
                    productTotal.toFixed(2)
                );


                /*
                |--------------------------------------------------------------------------
                | Summary
                |--------------------------------------------------------------------------
                */

                totalSales += grossAmount;
                productDiscount += discount;
                totalGst += gstAmount;

            });


            /*
            |--------------------------------------------------------------------------
            | Additional Discount
            |--------------------------------------------------------------------------
            */

            let additionalDiscount =
                parseFloat(
                    $('#summaryAdditionalDiscount').val()
                ) || 0;


            if (additionalDiscount < 0) {
                additionalDiscount = 0;
            }


            let totalDiscount =
                productDiscount + additionalDiscount;

            let taxableAmount =
                totalSales - totalDiscount;


            if (taxableAmount < 0) {
                taxableAmount = 0;
            }


            let netSalesAmount =
                taxableAmount + totalGst;


            /*
            |--------------------------------------------------------------------------
            | Display Summary
            |--------------------------------------------------------------------------
            */

            $('#summaryTotalSales').val(
                totalSales.toFixed(2)
            );

            $('#summaryProductDiscount').val(
                productDiscount.toFixed(2)
            );

            $('#summaryTotalDiscount').val(
                totalDiscount.toFixed(2)
            );

            $('#summaryTaxableAmount').val(
                taxableAmount.toFixed(2)
            );

            $('#summaryGstAmount').val(
                totalGst.toFixed(2)
            );

            $('#summaryNetSales').val(
                netSalesAmount.toFixed(2)
            );

            $('#n_amount_to_pay').val(
                netSalesAmount.toFixed(2)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Additional Discount Change
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input change',
            '#summaryAdditionalDiscount',
            function () {

                calculateSummary();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Remove Product Row
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.removeRow',
            function () {

                $(this).closest('tr').remove();

                calculateSummary();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Do NOT run productTotal() for all products on page load.
        |
        | REMOVE this old code:
        |
        | $('#productTable tbody .product').each(function () {
        |     if ($(this).val()) {
        |         productTotal($(this));
        |     }
        | });
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Calculate summary on page load
        |--------------------------------------------------------------------------
        */

        calculateSummary();

    });
    function handlePaymentMode() {

        let paymentMode =
            $('.mode_of_payment:checked').val();


        /*
        |--------------------------------------------------------------------------
        | No Payment Mode Selected
        |--------------------------------------------------------------------------
        */

        if (!paymentMode) {

            $('#paymet-proofs').hide();

            $('#ps').show();

            $('#franchise-details').show();


            $('#franchise_state').addClass('mandatory');
            $('#franchise_district').addClass('mandatory');
            $('#franchise_panchayath').addClass('mandatory');
            $('#franchise').addClass('mandatory');
            $('#payment_status').addClass('mandatory');


            $('#c_transaction_id').removeClass('mandatory');
            $('#payment_image').removeClass('mandatory');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Paid to Franchise / Cash on Delivery
        |--------------------------------------------------------------------------
        */

        if (
            paymentMode === 'Paid to Franchise' ||
            paymentMode === 'Cash on Delivery'
        ) {

            $('#paymet-proofs').hide();

            $('#ps').show();

            $('#franchise-details').show();


            $('#franchise_state').addClass('mandatory');
            $('#franchise_district').addClass('mandatory');
            $('#franchise_panchayath').addClass('mandatory');
            $('#franchise').addClass('mandatory');
            $('#payment_status').addClass('mandatory');


            $('#c_transaction_id').removeClass('mandatory');
            $('#payment_image').removeClass('mandatory');

        }


        /*
        |--------------------------------------------------------------------------
        | Other Payment Modes
        |--------------------------------------------------------------------------
        */

        else {

            $('#paymet-proofs').show();

            $('#ps').show();

            $('#franchise-details').show();


            $('#franchise_state').addClass('mandatory');
            $('#franchise_district').addClass('mandatory');
            $('#franchise_panchayath').addClass('mandatory');
            $('#franchise').addClass('mandatory');
            $('#payment_status').addClass('mandatory');


            $('#c_transaction_id').addClass('mandatory');
           // $('#payment_image').addClass('mandatory');

        }

    }
</script>
<script>
    $(document).ready(function() {

        <?php if(isset($viewmode) && $viewmode == 'on'): ?>

        // Make all text/number/date/email inputs readonly
        $('#frm_create input:not([type="hidden"]):not([type="button"]):not([type="submit"])')
            .prop('readonly', true);

        // Make all textareas readonly
        $('#frm_create textarea').prop('readonly', true);

        // Select, radio, checkbox and file inputs do not support readonly
        // so disable them
        $('#frm_create select').prop('disabled', true);
        $('#frm_create input[type="radio"]').prop('disabled', true);
        $('#frm_create input[type="checkbox"]').prop('disabled', true);
        $('#frm_create input[type="file"]').prop('disabled', true);

        // Disable product add/remove controls
        $('#addRow').prop('disabled', true);
        $('.removeRow').prop('disabled', true);

    <?php endif; ?>


    $("#n_customer_id").change(function() {
        let option = $(this).find(":selected");

        let stateId = option.data("state");
        let districtId = option.data("district");

        $("#c_customer_name").val(option.data("name") || '');
        $("#c_customer_email").val(option.data("email") || '');
        $("#n_customer_mobile").val(option.data("mobile") || '');
        $("#c_customer_address").val(option.data("address") || '');
        $("#c_customer_pincode").val(option.data("pincode") || '');

        $("#customer_state").val(stateId);

        $.ajax({
            type: "GET",
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            data: {
                state: stateId
            },
            dataType: "json",
            success: function(data) {
                $("#customer_district").html(
                    '<option value="">Select District</option>'
                );

                $.each(data.districts, function(i, district) {
                    $("#customer_district").append(
                        '<option value="' + district.id + '">' +
                        district.district_name +
                        '</option>'
                    );
                });

                $("#customer_district").val(districtId);
            }
        });
    });
});
$('#n_customer_id').trigger('change');
</script>
<script>
/*
|--------------------------------------------------------------------------
| FRANCHISE LOCATION: State → District
|--------------------------------------------------------------------------
*/

$('#franchise_state').on('change', function() {

    let stateId = $(this).val();

    $('#franchise_district').html(
        '<option value="">Loading...</option>'
    );

    $('#franchise_panchayath').html(
        '<option value="">Select Panchayath</option>'
    );

    $('#franchise').html(
        '<option value="">Select Franchise</option>'
    );

    if (!stateId) {
        $('#franchise_district').html(
            '<option value="">Select District</option>'
        );
        return;
    }

    $.ajax({
        type: 'GET',
        url: "<?php echo e(route('admin.filterDistrict')); ?>",
        data: {
            state: stateId
        },
        dataType: 'json',

        success: function(response) {

            $('#franchise_district').html(
                '<option value="">Select District</option>'
            );

            if (response.districts) {

                $.each(response.districts, function(index, district) {

                    $('#franchise_district').append(
                        '<option value="' + district.id + '">' +
                        district.district_name +
                        '</option>'
                    );

                });
            }
        },

        error: function(xhr) {
            console.error('District AJAX Error:', xhr.responseText);

            $('#franchise_district').html(
                '<option value="">Unable to load districts</option>'
            );
        }
    });
});


/*
|--------------------------------------------------------------------------
| FRANCHISE LOCATION: District → Panchayath
|--------------------------------------------------------------------------
*/

$('#franchise_district').on('change', function() {

    let districtId = $(this).val();

    $('#franchise_panchayath').html(
        '<option value="">Loading...</option>'
    );

    $('#franchise').html(
        '<option value="">Select Franchise</option>'
    );

    if (!districtId) {

        $('#franchise_panchayath').html(
            '<option value="">Select Panchayath</option>'
        );

        return;
    }

    $.ajax({
        type: 'GET',
        url: "<?php echo e(route('admin.filterPanchayath')); ?>",
        data: {
            district: districtId
        },
        dataType: 'json',

        success: function(response) {

            console.log('Panchayath response:', response);

            $('#franchise_panchayath').html(
                '<option value="">Select Panchayath</option>'
            );

            if (
                response.panchayaths &&
                response.panchayaths.length > 0
            ) {

                $.each(response.panchayaths, function(index, panchayat) {

                    $('#franchise_panchayath').append(
                        '<option value="' + panchayat.id + '">' +
                        panchayat.panchayath_name +
                        '</option>'
                    );

                });

            } else {

                $('#franchise_panchayath').html(
                    '<option value="">No Panchayaths Found</option>'
                );
            }
        },

        error: function(xhr) {

            console.error(
                'Panchayath AJAX Error:',
                xhr.responseText
            );

            $('#franchise_panchayath').html(
                '<option value="">Unable to load Panchayaths</option>'
            );
        }
    });
});


/*
|--------------------------------------------------------------------------
| FRANCHISE LOCATION: Panchayath → Franchise
|--------------------------------------------------------------------------
*/

 /*    $('#franchise_panchayath').on('change', function() {

        let stateId = $('#franchise_state').val();
        let districtId = $('#franchise_district').val();
        let panchayathId = $(this).val();

        console.log('Loading franchises:', {
            state: stateId,
            district: districtId,
            panchayath: panchayathId
        });

        $('#franchise').html(
            '<option value="">Loading franchises...</option>'
        );

        if (!stateId || !districtId || !panchayathId) {

            $('#franchise').html(
                '<option value="">Select Franchise</option>'
            );

            return;
        }

        $.ajax({
            type: 'GET',

            url: "<?php echo e(url('admin/filter-franchise')); ?>",

            data: {
                state: stateId,
                district: districtId,
                panchayath: panchayathId
            },

            dataType: 'json',

            success: function(response) {

                console.log('Franchise response:', response);

                $('#franchise').html(
                    '<option value="">Select Franchise</option>'
                );

                if (
                    response.franchises &&
                    response.franchises.length > 0
                ) {

                    $.each(response.franchises, function(index, franchise) {

                        $('#franchise').append(
                            '<option value="' +
                            franchise.n_store_id +
                            '">' +
                            franchise.c_store_name +
                            ' (' +
                            franchise.c_store_code +
                            ')' +
                            '</option>'
                        );

                    });

                } else {

                    $('#franchise').html(
                        '<option value="">No Franchises Found</option>'
                    );

                    console.log('No franchises found');
                }
            },

            error: function(xhr, status, error) {

                console.error('Franchise AJAX failed');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);

                $('#franchise').html(
                    '<option value="">Unable to load franchises</option>'
                );
            }
        });

    }); */
</script>


<script>
    $('#franchise_panchayath').on('change', function () {

        const panchayathId = $(this).val();

        console.log('Selected Panchayath ID:', panchayathId);

        // No Panchayath selected
        if (!panchayathId) {

            $('#franchise').html(
                '<option value="">Select Franchise</option>'
            );

            return;
        }

        // Find franchises by Panchayath
        findNearestFranchise(panchayathId);
    });


    function findNearestFranchise(panchayathId) {

        console.log('Finding franchises for Panchayath:', panchayathId);

        $('#franchise').html(
            '<option value="">Finding franchise...</option>'
        );

        fetch("<?php echo e(route('admin.franchise.nearest')); ?>", {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                "Accept": "application/json"
            },

            body: JSON.stringify({
                panchayath_id: panchayathId
            })

        })

        .then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'HTTP error: ' + response.status
                );
            }

            return response.json();

        })

        .then(function (data) {

            console.log('Franchise response:', data);

            $('#franchise').html(
                '<option value="">Select Franchise</option>'
            );

            if (!data.success) {

                $('#franchise').html(
                    '<option value="">No Franchise Found</option>'
                );

                return;
            }

            let franchises = Array.isArray(data.franchises)
                ? data.franchises
                : (data.franchises ? [data.franchises] : []);

            if (franchises.length === 0) {

                $('#franchise').html(
                    '<option value="">No Franchise Found</option>'
                );

                return;
            }

            franchises.forEach(function (franchise) {

                $('#franchise').append(`
                    <option value="${franchise.n_store_id}">
                        ${franchise.c_store_name}
                        ${
                            franchise.c_store_code
                                ? ' (' + franchise.c_store_code + ')'
                                : ''
                        }
                    </option>
                `);

            });

            // Automatically select first franchise
            $('#franchise').val(
                franchises[0].n_store_id
            );

        })

        .catch(function (error) {

            console.error(
                'Nearest franchise error:',
                error
            );

            $('#franchise').html(
                '<option value="">Unable to find franchise</option>'
            );

        });

    }

    function toggleOrderType() {

        const orderType = $('input[name="order_type"]:checked').val();

        if (orderType === 'franchise') {

            // Show franchise section
            $('#franchise-location-details').show();

            // Add mandatory validation
            $('#franchise_state').addClass('mandatory');
            $('#franchise_district').addClass('mandatory');
            $('#franchise_panchayath').addClass('mandatory');
            $('#franchise').addClass('mandatory');

        } else if (orderType === 'company') {

            // Hide franchise section
            $('#franchise-location-details').hide();

            // Remove mandatory validation
            $('#franchise_state').removeClass('mandatory');
            $('#franchise_district').removeClass('mandatory');
            $('#franchise_panchayath').removeClass('mandatory');
            $('#franchise').removeClass('mandatory');

            // Optional: clear values
            $('#franchise_state').val('');
            $('#franchise_district').val('');
            $('#franchise_panchayath').val('');
            $('#franchise').val('');
        }
    }

    // When Company / Franchise changes
    $('input[name="order_type"]').on('change', function () {
        toggleOrderType();
    });

    // Run when page loads
    toggleOrderType();


    $(document).ready(function () {

        function setupImageUpload(inputId, previewId, containerId, removeInputId, removeButtonId) {

            // Preview selected image
            $(document).on('change', '#' + inputId, function (event) {

                const file = event.target.files[0];

                if (!file) return;

                // Allow images only
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    $(this).val('');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {

                    // Show selected image preview
                    $('#' + previewId)
                        .attr('src', e.target.result)
                        .show();

                    // New image selected, don't delete
                    $('#' + removeInputId).val('0');

                    // Create Remove button if it doesn't exist
                    if ($('#' + removeButtonId).length === 0) {

                        $('#' + containerId).append(`
                            <br>
                            <button type="button"
                                id="${removeButtonId}"
                                class="btn btn-danger btn-sm mt-2">
                                Remove Image
                            </button>
                        `);

                    } else {
                        $('#' + removeButtonId).show();
                    }
                };

                reader.readAsDataURL(file);
            });


            // Remove image
            $(document).on('click', '#' + removeButtonId, function () {

                // Clear selected file
                $('#' + inputId).val('');

                // Hide image preview
                $('#' + previewId)
                    .attr('src', '')
                    .hide();

                // Tell Laravel to remove existing image
                $('#' + removeInputId).val('1');

                // Hide remove button
                $(this).hide();
            });
        }


        // ===============================
        // Payment Image
        // ===============================
        setupImageUpload(
            'payment_image',
            'payment_image_preview',
            'payment_preview_container',
            'remove_payment_image',
            'remove_payment_image_btn'
        );


        // ===============================
        // Booklet Image
        // ===============================
        setupImageUpload(
            'booklet_image',
            'booklet_image_preview',
            'booklet_image_preview_container',
            'remove_booklet_image',
            'remove_booklet_image_btn'
        );

    });

    /*
    |--------------------------------------------------------------------------
    | Payment Mode Change
    |--------------------------------------------------------------------------
    */

    $('.mode_of_payment').on('change', function () {
        handlePaymentMode();
    });


    /*
    |--------------------------------------------------------------------------
    | Run Payment Mode on Page Load
    |--------------------------------------------------------------------------
    */

    handlePaymentMode();


    /*
    |--------------------------------------------------------------------------
    | Handle Payment Mode
    |--------------------------------------------------------------------------
    */

    function handlePaymentMode() {

        let paymentMode =
            $('.mode_of_payment:checked').val();


        /*
        |--------------------------------------------------------------------------
        | No Payment Mode Selected
        |--------------------------------------------------------------------------
        */

        if (!paymentMode) {

            $('#paymet-proofs').hide();
            $('#ps').show();
            $('#franchise-details').show();

            $('#franchise_state').addClass('mandatory');
            $('#franchise_district').addClass('mandatory');
            $('#franchise_panchayath').addClass('mandatory');
            $('#franchise').addClass('mandatory');
            $('#payment_status').addClass('mandatory');

            $('#c_transaction_id').removeClass('mandatory');
            $('#payment_image').removeClass('mandatory');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Paid to Franchise / Cash on Delivery
        |--------------------------------------------------------------------------
        */

        if (
            paymentMode === 'Paid to Franchise' ||
            paymentMode === 'Cash on Delivery'
        ) {

            $('#paymet-proofs').hide();
            $('#ps').show();
            $('#franchise-details').show();

            $('#franchise_state').addClass('mandatory');
            $('#franchise_district').addClass('mandatory');
            $('#franchise_panchayath').addClass('mandatory');
            $('#franchise').addClass('mandatory');
            $('#payment_status').addClass('mandatory');

            $('#c_transaction_id').removeClass('mandatory');
            $('#payment_image').removeClass('mandatory');

        } else {

            /*
            |--------------------------------------------------------------------------
            | Other Payment Modes
            |--------------------------------------------------------------------------
            */

            $('#paymet-proofs').show();
            $('#ps').show();
            $('#franchise-details').show();

            $('#franchise_state').addClass('mandatory');
            $('#franchise_district').addClass('mandatory');
            $('#franchise_panchayath').addClass('mandatory');
            $('#franchise').addClass('mandatory');
            $('#payment_status').addClass('mandatory');

            $('#c_transaction_id').addClass('mandatory');

            // Uncomment if payment image is mandatory
            // $('#payment_image').addClass('mandatory');
        }
    }

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>