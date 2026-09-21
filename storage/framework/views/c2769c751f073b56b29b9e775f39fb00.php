<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
    /* Customer Details Card */
    .customer-details-card {
        background: #ffffff;
        border: 1px solid #e1e7ef;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        margin-bottom: 25px;
    }

    /* Card Header */
    .customer-details-header {
        padding: 18px 36px;
        border-bottom: 1px solid #edf0f4;
        background: #fafbfd;
        font-size: 18px;
        font-weight: 500;
        color: #334155;
    }

    /* Card Body */
    .customer-details-body {
        padding: 36px;
    }

    /* Labels */
    .customer-details-card .form-label {
        display: block;
        font-size: 17px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 9px;
    }

    /* Required Star */
    .customer-details-card .required {
        color: #e87545;
    }

    /* Inputs */
    .customer-details-card .form-control,
    .customer-details-card .form-select {
        height: 50px;
        border: 1px solid #cfd8e3;
        border-radius: 11px;
        padding: 10px 18px;
        font-size: 17px;
        color: #475569;
        background-color: #fff;
        box-shadow: none;
    }

    /* Input Focus */
    .customer-details-card .form-control:focus,
    .customer-details-card .form-select:focus {
        border-color: #2f6b4f;
        box-shadow: 0 0 0 0.2rem rgba(47, 107, 79, 0.10);
    }

    /* Placeholder */
    .customer-details-card .form-control::placeholder {
        color: #94a3b8;
        opacity: 1;
    }

    /* Select */
    .customer-details-card .form-select {
        cursor: pointer;
    }

    /* Row spacing */
    .customer-details-card .form-row {
        margin-bottom: 18px;
    }

    /* Last row no extra bottom margin */
    .customer-details-card .form-row:last-child {
        margin-bottom: 0;
    }

    /* Validation error */
    .customer-details-card .text-danger {
        font-size: 13px !important;
    }

    /* Disabled fields */
    .customer-details-card input[readonly],
    .customer-details-card select:disabled {
        background-color: #f5f6f8;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .customer-details-header {
            padding: 15px 20px;
        }

        .customer-details-body {
            padding: 20px;
        }

        .customer-details-card .form-label {
            font-size: 15px;
        }

        .customer-details-card .form-control,
        .customer-details-card .form-select {
            font-size: 15px;
        }
    }

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

/* Radio Button Cards */
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
    white-space: nowrap;
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

/* Table and Responsive Fixes */
.tablescrolll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

#productTable tbody td input,
#productTable tbody td select {
    min-width: 100px;
}

#productTable tbody td select.category-select {
    min-width: 155px;
}

#productTable tbody td select.subcategory-select {
    min-width: 185px;
}

#productTable tbody td select.product-select {
    min-width: 220px;
}

#productTable tbody td select.attribute-select {
    min-width: 150px;
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
        flex-wrap: wrap;
    }
}

.customer-toggle {
    display: flex;
    width: 520px;
    max-width: 100%;
    padding: 8px;
    background: #e8edf3;
    border: 1px solid #d2d9e2;
    border-radius: 18px;
    max-height:80px;
}

.customer-toggle .toggle-btn {
    flex: 1;
    margin: 0;
    padding: 18px 25px;
    text-align: center;
    cursor: pointer;
    border-radius: 12px;
    color: #64748b;
    font-size: 17px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.customer-toggle .btn-check:checked + .toggle-btn {
    background: linear-gradient(90deg, #527f36, #155b48);
    color: #ffffff;
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
                            <label class="form-label">Booklet Serial No *</label>
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
                            <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                </div>


                <!-- Row 2: Farm Care Advisor & Booklet Proof -->
                <?php if(
                        (!isset($isTelecaller) || $isTelecaller == false) &&
                        (!isset($isFarmCareOfficer) || $isFarmCareOfficer == false)
                    ): ?>
                <div class="row g-3">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Farm Care Advisor *</label>
                        <?php if($isFarmCareAdvisor): ?>
                        <input type="hidden" name="farm_care_advisor_id" class="form-control advisor-highlight" value="<?php echo e(auth()->user()->n_employee_id); ?>" readonly>
                        <input type="text" class="form-control advisor-highlight" value="<?php echo e(auth()->user()->c_name); ?>" readonly>
                        <?php else: ?>
                        <select name="farm_care_advisor_id" class="form-control" data-message="Please Enter Farm Care Advisor">
                            <option value="">Select Farm Care Adviser</option>
                            <?php if(isset($employees)): ?>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->n_employee_id); ?>"
                                <?php echo e(isset($sale) && $sale->farm_care_advisor_id == $employee->n_employee_id ? 'selected' : ''); ?>>
                                <?php echo e($employee->c_employee_name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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

                        <input type="file" name="booklet_image" id="booklet_image" class="form-control" accept="image/*"
                            data-message="Please Enter Booklet Proof">
                        <input type="hidden" name="remove_booklet_image" id="remove_booklet_image" value="0">
                        <div class="text-danger mt-1 fs-2"></div>

                        <div class="mt-3" id="booklet_image_preview_container">
                            <img id="booklet_image_preview"
                                src="<?php echo e(isset($sale) && $sale->booklet_image ? asset('uploads/booklet_images/' . $sale->booklet_image) : ''); ?>"
                                alt="Booklet Proof Preview" class="img-thumbnail"
                                style="<?php echo e(isset($sale) && $sale->booklet_image ? '' : 'display:none;'); ?> width:50px; height:50px; object-fit:cover;">

                            <?php if(isset($sale) && $sale->booklet_image): ?>
                            <br>
                            <button type="button" id="remove_booklet_image_btn" class="btn btn-danger btn-sm mt-2">
                                Remove Image
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <?php endif; ?>
            </div>

            <!-- Section 2: Product Details (Hierarchical Category -> Subcategory -> Product -> Attributes) -->
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
                                <th style="min-width: 170px;">Category *</th>
                                <th style="min-width: 195px;">Sub Category</th>
                                <th style="min-width: 220px;">Product *</th>
                                <th style="min-width: 155px;">Attribute / Pack Size *</th>
                                <th style="min-width: 110px;">HSN Code</th>
                                <th style="min-width: 110px;">Price (Excl. GST)</th>
                                <th style="min-width: 90px;">Quantity *</th>
                                <th style="min-width: 100px;">Discount</th>
                                <th style="min-width: 80px;">GST %</th>
                                <th style="min-width: 110px;">GST Amount</th>
                                <th style="min-width: 120px;">Taxable Amount</th>
                                <th style="min-width: 120px;">Total (MRP)</th>
                                <th style="min-width: 60px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($sale->orderProducts) && count($sale->orderProducts) > 0): ?>
                            <?php $__currentLoopData = $sale->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="existing-product-row">
                                <!-- Category -->
                                <td>
                                    <input type="hidden" name="products[<?php echo e($key); ?>][n_category_id]" class="form-control subcategory-select" value="<?php echo e($val->category?->n_category_id); ?>">

                                    <input type="text" name="" class="form-control" value="<?php echo e($val->category?->c_category_name); ?>" readonly>

                                </td>

                                <!-- Sub Category -->
                                <td>
                                    <input type="hidden" name="products[<?php echo e($key); ?>][n_sub_category_id]" class="form-control subcategory-select" value="<?php echo e($val->subCategory?->n_category_id); ?>">

                                    <input type="text" name="" class="form-control" value="<?php echo e($val->subCategory?->c_category_name); ?>" readonly>

                                </td>

                                <!-- Product -->
                                <td>
                                    <input type="hidden" name="products[<?php echo e($key); ?>][product_id]" class="form-control product-select" value="<?php echo e($val->product->n_product_id); ?>">

                                    <input type="text" name="" class="form-control" value="<?php echo e($val->product->c_product_name); ?>" readonly>


                                </td>

                                <!-- Attribute / Pack Size -->
                                <td>
                                    <input type="hidden" name="products[<?php echo e($key); ?>][c_unit]" class="form-control attribute-select" value="<?php echo e($val->product->c_unit); ?>">

                                    <input type="text" name="" class="form-control" value="<?php echo e($val->product->c_unit); ?>" readonly>

                                </td>

                                <!-- HSN Code -->
                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][c_hsn_code]" class="form-control c_hsn_code"
                                        value="<?php echo e($val->c_hsn_code ?? ''); ?>" readonly>
                                </td>

                                <!-- Price -->
                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][product_price]" class="form-control price"
                                        value="<?php echo e($val->product_price ?? '0.00'); ?>" readonly>
                                </td>

                                <!-- Quantity -->
                                <td>
                                    <input type="number" name="products[<?php echo e($key); ?>][qty]" class="form-control qty"
                                        value="<?php echo e($val->qty ?? 1); ?>" min="1">
                                </td>



                                <!-- Discount -->
                                <td>
                                    <input type="number" name="products[<?php echo e($key); ?>][discount]" class="form-control discount"
                                        value="<?php echo e($val->discount ?? '0.00'); ?>" step="0.01" min="0">
                                </td>

                                <!-- GST % -->
                                <td>
                                    <input type="number" name="products[<?php echo e($key); ?>][n_gst_percentage]"
                                        class="form-control gst_percentage" value="<?php echo e($val->n_gst_percentage ?? 0); ?>"
                                        step="0.01" readonly>
                                </td>

                                <!-- GST Amount -->
                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][gst_amount]" class="form-control gst_amount"
                                        value="<?php echo e($val->gst_amount ?? '0.00'); ?>" readonly>
                                </td>

                                <!-- Taxable / Discounted Price -->
                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][discounted_price]"
                                        class="form-control discounted_price" value="<?php echo e($val->discounted_price ?? '0.00'); ?>"
                                        readonly>
                                </td>

                                <!-- Total (MRP) -->
                                <td>
                                    <input type="text" name="products[<?php echo e($key); ?>][product_total]" class="form-control total"
                                        value="<?php echo e($val->product_total ?? '0.00'); ?>" readonly>
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

                <!-- Product Details Summary Box -->
                <div class="row justify-content-end mt-4">
                    <div class="col-md-6 col-lg-5">
                        <div class="product-summary-box">
                            <div class="summary-line">
                                <span class="summary-label">Total Sales Amount</span>
                                <input type="text" name="n_total_sales_amount" class="form-control summary-input text-end"
                                    id="summaryTotalSales"
                                    value="<?php echo e(old('n_total_sales_amount', $sale->n_total_sales_amount ?? '0.00')); ?>" readonly>
                            </div>

                            <div class="summary-line">
                                <span class="summary-label">Total GST</span>
                                <input type="number" name="n_total_gst" class="form-control summary-input text-end"
                                    id="summaryGstAmount" value="<?php echo e(old('n_total_gst', $sale->n_total_gst ?? '0.00')); ?>" step="0.01"
                                    min="0" readonly>
                            </div>

                            <div class="summary-line">
                                <span class="summary-label">Total Discount</span>
                                <input type="text" name="n_product_discount_total" class="form-control summary-input text-end"
                                    id="summaryTotalDiscount"
                                    value="<?php echo e(old('n_total_discount', $sale->n_product_discount_total ?? '0.00')); ?>" readonly>
                            </div>

                            <div class="summary-line highlight-green">
                                <span class="summary-label fw-bold">Net Sales Amount</span>
                                <input type="text" name="n_net_sales_amount"
                                    class="form-control summary-input text-end fw-bold text-success" id="summaryNetSales"
                                    value="<?php echo e(old('n_net_sales_amount', $sale->n_net_sales_amount ?? '0.00')); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Type Selection -->
            <div class="customer-toggle mb-4">
                <input type="radio" class="btn-check" name="c_customer_type"
                    id="newCustomer" value="new" <?php echo e((!isset($sale) || (isset($sale) && $sale->c_customer_type=="new")) ? "checked" : ''); ?>>
                <label class="toggle-btn new" for="newCustomer">New Customer</label>

                <input type="radio" class="btn-check" name="c_customer_type"
                    id="existingCustomer" value="existing" <?php echo e((isset($sale) && $sale->c_customer_type=="existing") ? "checked" : ''); ?>>
                <label class="toggle-btn existing" for="existingCustomer">Existing Customer</label>
            </div>

            <!-- Existing Customer Lookup -->
            <div class="card border rounded-4 mb-4 d-none" id="lookupCard">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">Existing Customer Lookup</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input type="text" id="lookupMobile" value="<?php echo e(old('n_mobile', isset($sale) ? $sale->customer->n_mobile : '')); ?>" class="form-control" placeholder="Enter 10-digit Mobile Number">
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="lookupBtn" class="btn buttonSpc w-100">
                                <i class="ti ti-search me-1"></i> Find Customer
                            </button>
                        </div>
                        <div class="col-md-3">
                            <small id="lookupMessage" class="text-success fw-semibold"></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Customer Information -->
            <div class="border rounded p-4 mb-4">
                <div class="form-section-header mb-3">
                    <i class="ti ti-user fs-5"></i> Customer Information
                </div>

                <input type="hidden" name="n_customer_id" id="n_customer_id" class="form-control customer-id" value="<?php echo e((isset($sale) ? $sale->customer->n_customer_id : '')); ?>">

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Customer Code</label>
                        <input type="text" name="c_customer_code" id="c_customer_code" class="form-control customer-code"
                            value="<?php echo e($customerCode ?? (isset($sale) ? $sale->customer->c_customer_code : '')); ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Customer Name *</label>
                        <input type="text" name="c_customer_name" id="c_customer_name"
                            value="<?php echo e(old('c_customer_name', isset($sale) ? $sale->customer->c_customer_name : '')); ?>"
                            class="form-control c_customer_name mandatory" placeholder="Customer Name">
                        <?php $__errorArgs = ['c_customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mobile Number *</label>
                        <input type="text" maxlength="10" name="n_mobile" id="n_mobile"
                            value="<?php echo e(old('n_mobile', isset($sale) ? $sale->customer->n_mobile : '')); ?>"
                            class="form-control mandatory" placeholder="10 Digit Mobile Number">
                        <?php $__errorArgs = ['n_mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" maxlength="10" name="n_whatsapp" id="n_whatsapp"
                            value="<?php echo e(old('n_whatsapp', isset($sale) ? $sale->customer->n_whatsapp : '')); ?>"
                            class="form-control" placeholder="WhatsApp Number">
                        <?php $__errorArgs = ['n_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="c_email" id="c_email"
                            value="<?php echo e(old('c_email', isset($sale) ? $sale->customer->c_email : '')); ?>"
                            class="form-control" placeholder="example@domain.com">
                        <?php $__errorArgs = ['c_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Address Details -->
                <div class="form-section-header">
                    <i class="ti ti-map-pin"></i> Address Details
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <label for="c_address" class="form-label">Address</label>
                        <textarea id="c_address" name="c_address" rows="3" class="form-control"
                            placeholder="Enter Customer Address"><?php echo e(old('c_address', isset($sale) ? $sale->customer->c_address : '')); ?></textarea>
                        <?php $__errorArgs = ['c_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label for="c_post_office" class="form-label">
                            Post Office
                        </label>
                        <input type="text" id="c_post_office" name="c_post_office" maxlength="6" value="<?php echo e(old('c_post_office',isset($sale) ? $sale->customer->c_post_office : '')); ?>"
                            class="form-control" placeholder="Post Office">

                        <?php $__errorArgs = ['c_post_office'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    <div class="col-md-4">
                        <label for="n_state_id" class="form-label">State</label>
                        <select name="n_state_id" id="n_state_id" class="form-select">
                            <option value="">Select State</option>
                            <?php if(isset($states)): ?>
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($state->n_state_id); ?>" data-id="<?php echo e($state->n_state_id); ?>"
                                <?php echo e(old('n_state_id', isset($sale) ? $sale->customer->n_state_id : '') == $state->n_state_id ? 'selected' : ''); ?>>
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
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label for="n_district_id" class="form-label">District</label>
                        <select name="n_district_id" id="n_district_id" class="form-select">
                            <option value="">Select District</option>
                            <?php if(isset($districts)): ?>
                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($district->id); ?>"
                                <?php echo e(old('n_district_id', isset($sale) ? $sale->customer->n_district_id : '') == $district->id ? 'selected' : ''); ?>>
                                <?php echo e($district->district_name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['n_district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label for="c_thaluk" class="form-label">
                            Thaluk
                        </label>
                        <input type="text" id="c_thaluk" name="c_thaluk" maxlength="6" value="<?php echo e(old('c_thaluk',isset($sale) ? $sale->customer->c_thaluk : '')); ?>"
                            class="form-control" placeholder="Thaluk">

                        <?php $__errorArgs = ['c_thaluk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    <div class="col-md-4">
                        <label for="c_pincode" class="form-label">Pincode</label>
                        <input type="text" id="c_pincode" name="c_pincode" maxlength="6"
                            value="<?php echo e(old('c_pincode', isset($sale) ? $sale->customer->c_pincode : '')); ?>"
                            class="form-control" placeholder="Pincode">
                        <?php $__errorArgs = ['c_pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Customer Status -->
                <div class="form-section-header">
                    <i class="ti ti-checkup-list"></i> Customer Status
                </div>

                <div class="row g-4 mb-2">
                    <div class="col-md-4">
                        <label for="c_status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select id="c_status" name="c_status" class="form-select mandatory">
                            <option value="">Select Status</option>
                            <option value="Y" <?php echo e(old('c_status', isset($sale) ? $sale->customer->c_status : 'Y') == 'Y' ? 'selected' : ''); ?>>Active</option>
                            <option value="N" <?php echo e(old('c_status', isset($sale) ? $sale->customer->c_status : '') == 'N' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                        <?php $__errorArgs = ['c_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Section 4: Payment Details -->
            <div class="form-box mb-4">
                <div class="form-section-header mb-3">
                    <i class="ti ti-credit-card fs-5"></i> Payment Details
                </div>

                <div class="row mb-4 align-items-center">
                    <label class="col-md-3 col-form-label fw-semibold">Mode of Payment *</label>

                    <div class="col-md-9 d-flex flex-wrap">
                        <div class="payment-option">
                            <input class="form-check-input mandatory mode_of_payment" type="radio" name="c_mode_of_payment"
                                id="cod" value="Cash on Delivery" data-message="Please Choose a Payment Mode"
                                <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Cash on Delivery" ? 'checked' : ''); ?>>
                            <label for="cod" class="mb-0">
                                <i class="ti ti-truck"></i> Cash on Delivery
                            </label>
                        </div>

                        <?php if(isset($isTelecaller) && $isTelecaller==false): ?>
                        <div class="payment-option">
                            <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="upi"
                                value="UPI"
                                <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "UPI" ? 'checked' : ''); ?>>
                            <label for="upi" class="mb-0">
                                <i class="ti ti-brand-google-pay"></i> UPI
                            </label>
                        </div>

                        <div class="payment-option">
                            <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="bkd"
                                value="Bank Deposit"
                                <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Bank Deposit" ? 'checked' : ''); ?>>
                            <label for="bkd" class="mb-0">
                                <i class="ti ti-building-bank"></i> Bank Deposit
                            </label>
                        </div>
                        <?php endif; ?>

                        <div class="payment-option">
                            <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment" id="pf"
                                value="Paid to Franchise"
                                <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Paid to Franchise" ? 'checked' : ''); ?>>
                            <label for="pf" class="mb-0">
                                <i class="ti ti-cash"></i> Paid to Franchise
                            </label>
                        </div>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>
                </div>

                <div class="row g-4 mt-1" id="ps">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Payment Status</label>
                        <select name="payment_status" id="payment_status" data-message="Please Select Payment Status" class="form-select">
                            <option value="">Select Status</option>
                            <option value="pending" <?php echo e(old('payment_status', $sale->payment_status ?? '') == "pending" ? 'selected' : ''); ?>>Pending</option>
                            <option value="paid" <?php echo e(old('payment_status', $sale->payment_status ?? '') == "paid" ? 'selected' : ''); ?>>Paid</option>
                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </div>
                </div>

                <!-- Payment Details Extra Fields -->
                <div class="row g-4 mt-1" id="paymet-proofs">
                    <div class="col-md-4">
                        <label class="form-label">Amount to Pay *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success fw-bold">₹</span>
                            <input type="text" name="n_amount_to_pay" data-message="Please Enter Amount to Pay"
                                id="n_amount_to_pay" class="form-control fw-bold text-success"
                                value="<?php echo e(old('n_amount_to_pay', $sale->n_amount_to_pay ?? '')); ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Transaction ID *</label>
                        <input type="text" id="c_transaction_id" name="c_transaction_id"
                            value="<?php echo e(old('c_transaction_id', $sale->c_transaction_id ?? '')); ?>"
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

                        <input type="file" id="payment_image" name="payment_image" data-message="Please Enter Transaction Proof"
                            class="form-control" accept="image/*">
                        <input type="hidden" name="remove_payment_image" id="remove_payment_image" value="0">
                        <div class="text-danger mt-1 fs-2"></div>

                        <div class="mt-3" id="payment_preview_container">
                            <img id="payment_image_preview"
                                src="<?php echo e(isset($sale) && $sale->payment_image ? asset('uploads/payment_images/' . $sale->payment_image) : ''); ?>"
                                alt="Transaction Proof Preview" class="img-thumbnail"
                                style="<?php echo e(isset($sale) && $sale->payment_image ? '' : 'display:none;'); ?> width:50px; height:50px; object-fit:cover;">

                            <?php if(isset($sale) && $sale->payment_image): ?>
                            <br>
                            <button type="button" id="remove_payment_image_btn" class="btn btn-danger btn-sm mt-2">
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
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Order Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input mandatory" type="radio" name="order_type" id="company"
                                    value="company" <?php echo e(old('order_type', $sale->order_type ?? '') == 'company' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="company">Company</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input mandatory" type="radio" name="order_type" id="franchise_type"
                                    value="franchise" <?php echo e(old('order_type', $sale->order_type ?? '') == 'franchise' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="franchise_type">Franchise</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div id="franchise-location-details">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <select class="form-select mandatory" id="franchise_state" name="n_state_id" data-message="Please Select State">
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
                            <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">District <span class="text-danger">*</span></label>
                            <select class="form-select" id="franchise_district" name="n_district_id" data-message="Please Select District"
                                <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'disabled' : ''); ?>>
                                <option value="">Select District</option>
                                <?php if(isset($sale->n_district_id) && isset($sale->n_state_id)): ?>
                                    <?php
                                        $franchiseDistricts = \App\Models\District::where('state_id', $sale->n_state_id)->get();
                                    ?>
                                    <?php $__currentLoopData = $franchiseDistricts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($district->id); ?>"
                                        <?php echo e(old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : ''); ?>>
                                        <?php echo e($district->district_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Panchayath</label>
                            <select class="form-select" id="franchise_panchayath" name="n_panchayath_id">
                                <option value="">Select Panchayath</option>
                                <?php if(isset($sale->n_district_id)): ?>
                                    <?php
                                        $franchisePanchayaths = \App\Models\Panchayath::where('district_id', $sale->n_district_id)->get();
                                    ?>
                                    <?php $__currentLoopData = $franchisePanchayaths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $panchayath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($panchayath->id); ?>"
                                        <?php echo e(old('n_panchayath_id', $sale->n_panchayath_id ?? '') == $panchayath->id ? 'selected' : ''); ?>>
                                        <?php echo e($panchayath->panchayath_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nearest Franchise <span class="text-danger">*</span></label>
                            <select class="form-select mandatory" id="franchise" name="nearest_franchise_id" data-message="Please Select Nearest Franchise">
                                <option value="">Select Franchise</option>
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
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 d-flex gap-2 flex-wrap">
                <?php if(isset($viewmode) && $viewmode=="on"): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.approval')): ?>
                    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc" data-bs-toggle="modal"
                        data-bs-target="#approveModal" data-bs-dismiss="modal" data-id="<?php echo e(Crypt::encryptString(isset($sale) && $sale->n_sl_no ? $sale->n_sl_no : '')); ?>">
                        Approve
                    </button>
                    <?php endif; ?>

                    <?php if(isset($sale) && $sale->n_sl_no): ?>
                    <a href="<?php echo e(route('admin.invoice-orders.preview', $sale->n_sl_no)); ?>" class="btn mt-1 buttonSpc">
                        Order Summary Preview
                    </a>
                    <a href="<?php echo e(route('admin.invoice.download', $sale->n_sl_no)); ?>">
                        <button type="button" class="btn buttonSpc" style="height:61px;margin-top: 4px;">Generate Invoice</button>
                    </a>
                    <?php endif; ?>
                <?php else: ?>
                    <button type="button" class="btn buttonSpc" style="width:150px;position:relative;"
                        id="btn_create"><?php echo e(isset($sale->n_sl_no) ? 'Update' : 'Create'); ?></button>
                    <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="approveForm" action="<?php echo e(route('admin.salesorders.approval.save')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-header" style="background: linear-gradient(135deg, #0f5132, #074E30);">
                    <h5 class="modal-title text-white" id="approveModalLabel">Approval</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="sales_id" id="sales_id" value="<?php echo e(Crypt::encryptString(isset($sale) && $sale->n_sl_no ? $sale->n_sl_no : '')); ?>">

                    <div class="mb-3">
                        <label class="form-label">Remarks <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="remarks" id="approval_remarks" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Approval Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" id="approval_status" required>
                            <option value="">Select Status</option>
                            <option value="Approved">Approve</option>
                            <option value="Rejected">Reject</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn buttonSpc" id="approvalSubmit">Submit</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>

$(document).ready(function () {
    console.log('Sales Order JS loaded with Category & Attribute flow');

    let rowIndex = $('#productTable tbody tr').length;

    /*
    |--------------------------------------------------------------------------
    | Helper to Normalize Category Value to Catalog Key
    |--------------------------------------------------------------------------
    */
    function resolveCategoryKey(catVal) {
        if (!catVal) return '';
        let lower = String(catVal).toLowerCase().trim();
        if (lower.indexOf('organ') !== -1 || lower === '1') {
            return 'organics';
        }
        if (lower.indexOf('plant') !== -1 || lower.indexOf('garden') !== -1 || lower.indexOf('foliage') !== -1 || lower === '6') {
            return 'garden_plants';
        }
        return catVal;
    }

    /*
    |--------------------------------------------------------------------------
    | Add New Product Row
    |--------------------------------------------------------------------------
    */
    $('#addRow').on('click', function () {
        let row = `
            <tr class="new-product-row">
                <!-- 1. Category -->
                <td>

                    <select name="products[${rowIndex}][n_category_id]" class="form-select category-select mandatory" data-message="Please Select Category">
                        <option value="">Select Category First</option>
                            <?php $__currentLoopData = $productCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <option value="<?php echo e($category->n_category_id); ?>" data-categoryCode="<?php echo e($category->c_category_code); ?>">
                                            <?php echo e($category->c_category_name); ?>

                                        </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>

                <!-- 2. Sub Category -->
                <td>
                    <select name="products[${rowIndex}][n_sub_category_id]" class="form-select subcategory-select" disabled>
                        <option value="">Select Sub Category </option>
                    </select>
                </td>

                <!-- 3. Product -->
                <td>
                    <select name="products[${rowIndex}][c_product_name]" class="form-select product-select mandatory" data-message="Please Select Product" disabled>
                        <option value="">Select Category First</option>
                    </select>
                </td>

                <!-- 4. Attribute / Pack Size -->
                <td>
                    <select name="products[${rowIndex}][c_unit]" class="form-select packSize-select" data-message="Please Select Pack Size" disabled>
                        <option value="">Select Product First</option>

                    </select>
                    <input type="hidden" class="n_product_id" name="products[${rowIndex}][product_id]" value=''>
                </td>

                <!-- 5. HSN Code -->
                <td>
                    <input type="text" name="products[${rowIndex}][c_hsn_code]" class="form-control c_hsn_code" value="" readonly>
                </td>

                <!-- 6. Price (Excl GST) -->
                <td>
                    <input type="text" name="products[${rowIndex}][product_price]" class="form-control price" value="0.00" readonly>
                </td>

                <!-- 7. Quantity -->
                <td>
                    <input type="number" name="products[${rowIndex}][qty]" class="form-control qty" value="1" min="1">
                </td>


                <!-- 9. Discount -->
                <td>
                    <input type="number" name="products[${rowIndex}][discount]" class="form-control discount" value="0.00" step="0.01" min="0">
                </td>

                <!-- 10. GST % -->
                <td>
                    <input type="number" name="products[${rowIndex}][n_gst_percentage]" class="form-control gst_percentage" value="0.00" step="0.01" readonly>
                </td>

                <!-- 11. GST Amount -->
                <td>
                    <input type="text" name="products[${rowIndex}][gst_amount]" class="form-control gst_amount" value="0.00" readonly>
                </td>

                <!-- 12. Discounted / Taxable Price -->
                <td>
                    <input type="text" name="products[${rowIndex}][discounted_price]" class="form-control discounted_price" value="0.00" readonly>
                </td>

                <!-- 13. Total (MRP) -->
                <td>
                    <input type="text" name="products[${rowIndex}][product_total]" class="form-control total" value="0.00" readonly>
                </td>

                <!-- 14. Action -->
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm removeRow">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#productTable tbody').append(row);
        rowIndex++;
    });


        $(document).ready(function () {

        /*
        |--------------------------------------------------------------------------
        | CATEGORY CHANGE
        |--------------------------------------------------------------------------
        */

            $(document).on('change', '.category-select', function () {

                let row = $(this).closest('.new-product-row');

                let categoryId = $(this).val();

                let subCategory = row.find('.subcategory-select');

                let product = row.find('.product-select');
                let packSize = row.find('.packSize-select');

                // Reset dependent dropdowns
                subCategory.html(
                    '<option value="">Select Category First</option>'
                );

                product.html(
                    '<option value="">Select Sub Category First</option>'
                );

                packSize.html(
                    '<option value="">Select Product First</option>'
                );

                if (!categoryId) {
                    return;
                }


                let url = "<?php echo e(route('admin.get.product.subcategories', ['categoryId' => ':categoryId'])); ?>";
                url = url.replace(':categoryId', categoryId);

                $.ajax({
                        url: url,
                        type: 'GET',

                        success: function (data) {



                            subCategory.empty();

                            subCategory.append(
                                $('<option>', {
                                    value: '',
                                    text: 'Select Sub Category',

                                })
                            );

                            $.each(data.subcategories, function (index, item) {

                                subCategory.append(
                                    $('<option>', {
                                        value: item.n_category_id,
                                        text: item.c_category_name,

                                    })
                                );
                               // Enable subcategory dropdown
                                subCategory.prop('disabled', false);
                            });

                        },

                    error: function (xhr) {
                        console.log('Sub Category Error:', xhr.responseText);
                    }
                });
            });


        /*
        |--------------------------------------------------------------------------
        | SUB CATEGORY CHANGE
        |--------------------------------------------------------------------------
        */

            $(document).on('change', '.subcategory-select', function () {

                let row = $(this).closest('.new-product-row');

                let subCategoryId = $(this).val();

                let product = row.find('.product-select');
                let packSize = row.find('.packSize-select');

                // Reset product and attribute
                product.html(
                    '<option value="">Select Product</option>'
                );

                packSize.html(
                    '<option value="">Select Product First</option>'
                );

                if (!subCategoryId) {
                    return;
                }

                let url = "<?php echo e(route('admin.get.products', ['subCategoryId' => ':subCategoryId'])); ?>";
                url = url.replace(':subCategoryId', subCategoryId);

                $.ajax({

                    url: url,
                    type: 'GET',

                    success: function (data) {
                            product.empty();

                            product.append(
                                $('<option>', {
                                    value: '',
                                    text: 'Select Product',

                                })
                            );

                        $.each(data.products, function (index, item) {

                            product.append(
                                $('<option>', {
                                    value: item.n_product_id,
                                    text: item.c_product_name
                                })
                            );

                            // Enable product dropdown
                                product.prop('disabled', false);

                        });

                    },

                    error: function (xhr) {
                        console.log('Product Error:', xhr.responseText);
                    }

                });

            });


        /*
        |--------------------------------------------------------------------------
        | PRODUCT CHANGE
        |--------------------------------------------------------------------------
        */

            $(document).on('change', '.product-select', function () {


                let row = $(this).closest('.new-product-row');
                let categoryCode=row.find(".category-select").find(':selected').attr("data-categoryCode");
                let productId = $(this).val();

                let productName = $(this).find(':selected').text();

                let packSize = row.find('.packSize-select');

                packSize.html(
                    '<option value="">Select Attribute / Pack Size</option>'
                );

                if (!productId) {
                    return;
                }

                if(categoryCode=="PLANT"){

                    let url = "<?php echo e(route('admin.get.attributesFromProductname',['productId' => ':productId'])); ?>";
                    url = url.replace(':productId', productId);

                    $.ajax({

                        url: url,
                        type: 'GET',

                        success: function (data) {

                            // Example:
                            // Set HSN
                            row.find('.c_hsn_code').val(data.c_hsn_code);
                            //set gst percentage
                            row.find('.gst_percentage').val(data.n_gst_percentage);
                            row.find('.n_product_id').val(data.n_product_id);

                            globalmrp=data.n_mrp;
                            globalGstPercentage=data.n_gst_percentage

                            calculateRowWithMrp(row, data.n_mrp,data.n_gst_percentage);

                        },

                        error: function (xhr) {
                            console.log(
                                'Attribute Details Error:',
                                xhr.responseText
                            );
                        }

                    });

                }
            else{

                let url = "<?php echo e(route('admin.get.product.packSize',['productName' => ':productName'])); ?>";
                url = url.replace(':productName', productName);

                $.ajax({

                    url: url,
                    type: 'GET',

                    success: function (data) {
                        packSize.empty();

                        packSize.append(
                            $('<option>', {
                                value: '',
                                text: 'Select Pack Size',
                            })
                        );
                        $.each(data.units, function (index, item) {

                            packSize.append(
                                $('<option>', {
                                    value: item.c_unit,
                                    text: item.c_unit
                                })
                            );

                            // Enable packSize dropdown
                                packSize.prop('disabled', false);

                        });

                    },

                    error: function (xhr) {
                        console.log(
                            'Pack Size Error:',
                            xhr.responseText
                        );
                    }

                });
            }

            });


            /*
            |--------------------------------------------------------------------------
            | ATTRIBUTE / PACK SIZE CHANGE
            |--------------------------------------------------------------------------
            */

                let globalmrp=0;
                let globalGstPercentage='';

                $(document).on('change', '.packSize-select', function () {

                    let row = $(this).closest('.new-product-row');
                    let productId = row.find(".product-select").find(':selected').val();
                    let productName = row.find(".product-select").find(':selected').text();
                    let packSize = $(this).val();

                    if (!packSize) {
                        return;
                    }

                    let url = "<?php echo e(route('admin.get.product.attributes',['productName' => ':productName','packSize'=>':packSize'])); ?>";
                    url = url.replace(':productName', productName);
                    url = url.replace(':packSize', packSize);

                    $.ajax({

                        url: url,
                        type: 'GET',

                        success: function (data) {

                            // Example:
                            // Set HSN
                            row.find('.c_hsn_code').val(data.c_hsn_code);
                            //set gst percentage
                            row.find('.gst_percentage').val(data.n_gst_percentage);
                            row.find('.n_product_id').val(data.n_product_id);

                            globalmrp=data.n_mrp;
                            globalGstPercentage=data.n_gst_percentage

                            calculateRowWithMrp(row, data.n_mrp,data.n_gst_percentage);

                        },

                        error: function (xhr) {
                            console.log(
                                'Attribute Details Error:',
                                xhr.responseText
                            );
                        }

                    });

                });

             /*
            |--------------------------------------------------------------------------
            | Quantity / Discount Change Event
            |--------------------------------------------------------------------------
            */

            $(document).on('input change', '.qty, .discount', function () {

                let row = $(this).closest('.new-product-row');

                calculateRowWithMrp(
                    row,
                    parseFloat(globalmrp) || 0,
                    parseFloat(globalGstPercentage) || 0
                );
                if($(this).closest('.existing-product-row')){
                    calculateExistingRow($(this).closest('.existing-product-row'));
                }

            });
            /* $(document).on('input', '.qty, .discount', function () {

                const input = this;
                const row = $(input).closest('.new-product-row');

                clearTimeout(row.data('calculationTimer'));

                const timer = setTimeout(function () {

                    calculateRowWithMrp(
                        row,
                        parseFloat(globalmrp) || 0,
                        parseFloat(globalGstPercentage) || 0
                    );

                }, 200);

                row.data('calculationTimer', timer);
            });


            $(document).on('change', '.qty, .discount', function () {

                const row = $(this).closest('.new-product-row');

                clearTimeout(row.data('calculationTimer'));

                calculateRowWithMrp(
                    row,
                    parseFloat(globalmrp) || 0,
                    parseFloat(globalGstPercentage) || 0
                );

            }); */
        });
        // /*
        // |--------------------------------------------------------------------------
        // | Subcategory Selection Change
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.subcategory-select', function () {
        //     let row = $(this).closest('tr');
        //     let catKey = resolveCategoryKey(row.find('.category-select').val());
        //     let subCatVal = $(this).val();

        //     let productSelect = row.find('.product-select');
        //     let attrSelect = row.find('.attribute-select');

        //     productSelect.empty();
        //     attrSelect.empty().prop('disabled', true);
        //     clearRowPricing(row);

        //     if (!catKey || !subCatVal || subCatVal === 'NA') {
        //         productSelect.html('<option value="">Select Sub Category First</option>').prop('disabled', true);
        //         return;
        //     }

        //     let catData = productCatalog[catKey];
        //     if (catData && catData.subcategories && catData.subcategories[subCatVal]) {
        //         productSelect.prop('disabled', false);
        //         productSelect.append('<option value="">Select Product</option>');

        //         catData.subcategories[subCatVal].forEach(function (p) {
        //             let opt = $(`<option value="${p.id}">${p.name} ${p.code ? '(' + p.code + ')' : ''}</option>`);
        //             opt.data('product-info', p);
        //             productSelect.append(opt);
        //         });
        //     }
        // });

        // /*
        // |--------------------------------------------------------------------------
        // | Product Selection Change -> Populates Attributes (Pack Sizes / Variants)
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.product-select', function () {
        //     let row = $(this).closest('tr');
        //     let selectedOption = $(this).find(':selected');
        //     let productInfo = selectedOption.data('product-info');

        //     let attrSelect = row.find('.attribute-select');
        //     attrSelect.empty();
        //     clearRowPricing(row);

        //     if (!productInfo || !productInfo.attributes || productInfo.attributes.length === 0) {
        //         attrSelect.html('<option value="">No Attributes Available</option>').prop('disabled', true);
        //         return;
        //     }

        //     attrSelect.prop('disabled', false);

        //     if (productInfo.attributes.length > 1) {
        //         attrSelect.append('<option value="">Select Pack Size / Attribute</option>');
        //     }

        //     productInfo.attributes.forEach(function (attr) {
        //         let opt = $(`<option value="${attr.name}">${attr.name} - ₹${attr.mrp.toFixed(2)}</option>`);
        //         opt.attr('data-price', attr.mrp);
        //         opt.attr('data-unit', attr.unit || '');
        //         opt.attr('data-gst', productInfo.gst || 0);
        //         opt.attr('data-hsn-code', productInfo.hsn || '');
        //         attrSelect.append(opt);
        //     });

        //     // Automatically select if single attribute (e.g., plants or 1 NOS)
        //     if (productInfo.attributes.length === 1) {
        //         attrSelect.val(productInfo.attributes[0].name).trigger('change');
        //     }
        // });

        // /*
        // |--------------------------------------------------------------------------
        // | Attribute Selection Change -> Calculates Pricing and Totals
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.attribute-select', function () {
        //     let row = $(this).closest('tr');
        //     let selectedOption = $(this).find(':selected');

        //     if (!selectedOption.val()) {
        //         clearRowPricing(row);
        //         calculateSummary();
        //         return;
        //     }

        //     let mrp = parseFloat(selectedOption.attr('data-price')) || 0;
        //     let gstPercentage = parseFloat(selectedOption.attr('data-gst')) || 0;
        //     let hsnCode = selectedOption.attr('data-hsn-code') || '';
        //     let unit = selectedOption.attr('data-unit') || '';

        //     row.find('.c_hsn_code').val(hsnCode);
        //     row.find('.c_unit').val(unit);
        //     row.find('.gst_percentage').val(gstPercentage.toFixed(2));

        //     calculateRowWithMrp(row, mrp, gstPercentage);
        // });


        /*
        |--------------------------------------------------------------------------
        | Calculation Formula (MRP Includes GST)
        |--------------------------------------------------------------------------
        */
        function calculateRowWithMrp(row, mrp, gstPercentage) {

            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;

            if (qty < 0) qty = 0;
            if (discount < 0) discount = 0;

            let price = 0;
            let grossAmount = 0;
            let taxableAmount = 0;
            let gstAmount = 0;
            let lineTotal = 0;

            if (mrp > 0) {
                // Exclusive price
                price = mrp / (1 + (gstPercentage / 100));

                // Price × Quantity
                grossAmount = price * qty;

                // Taxable Amount
                taxableAmount = grossAmount - discount;
                if (taxableAmount < 0) taxableAmount = 0;

                // GST Amount
                gstAmount = (taxableAmount * gstPercentage) / 100;

                // Line Total
                lineTotal = taxableAmount + gstAmount;
            }

            row.find('.price').val(price.toFixed(2));
            row.find('.gst_amount').val(gstAmount.toFixed(2));
            row.find('.discounted_price').val(taxableAmount.toFixed(2));
            row.find('.total').val(lineTotal.toFixed(2));

            calculateSummary();
        }

        function calculateExistingRow(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;
            let gstPercentage = parseFloat(row.find('.gst_percentage').val()) || 0;

            if (qty < 0) qty = 0;
            if (discount < 0) discount = 0;

            let grossAmount = price * qty;
            let taxableAmount = grossAmount - discount;
            if (taxableAmount < 0) taxableAmount = 0;

            let gstAmount = (taxableAmount * gstPercentage) / 100;
            let lineTotal = taxableAmount + gstAmount;

            row.find('.gst_amount').val(gstAmount.toFixed(2));
            row.find('.discounted_price').val(taxableAmount.toFixed(2));
            row.find('.total').val(lineTotal.toFixed(2));
        }

        function clearRowPricing(row) {
            row.find('.c_hsn_code').val('');
            row.find('.price').val('0.00');
            row.find('.c_unit').val('');
            row.find('.discount').val('0.00');
            row.find('.gst_percentage').val('0.00');
            row.find('.gst_amount').val('0.00');
            row.find('.discounted_price').val('0.00');
            row.find('.total').val('0.00');
        }

        /*
        |--------------------------------------------------------------------------
        | Summary Totals
        |--------------------------------------------------------------------------
        */
       /*  function calculateSummary() {
            let totalSales = 0;
            let totalDiscount = 0;
            let totalTaxable = 0;
            let totalGst = 0;

            $('#productTable tbody tr').each(function () {
                let row = $(this);
                let price = parseFloat(row.find('.price').val()) || 0;
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let discount = parseFloat(row.find('.discount').val()) || 0;
                let gstAmount = parseFloat(row.find('.gst_amount').val()) || 0;
                let taxable = parseFloat(row.find('.discounted_price').val()) || 0;

                let gross = price * qty;

                totalSales += gross;
                totalDiscount += discount;
                totalTaxable += taxable;
                totalGst += gstAmount;
            });

            let netSalesAmount = totalTaxable + totalGst;

            $('#summaryTotalSales').val(totalSales.toFixed(2));
            $('#summaryTotalDiscount').val(totalDiscount.toFixed(2));
            $('#summaryGstAmount').val(totalGst.toFixed(2));
            $('#summaryNetSales').val(netSalesAmount.toFixed(2));
            $('#n_amount_to_pay').val(netSalesAmount.toFixed(2));
        } */

        function calculateSummary() {
            let totalSales = 0;
            let totalDiscount = 0;
            let totalTaxable = 0;
            let totalGst = 0;

            $('#productTable tbody tr').each(function () {

                let row = $(this);

                let price = parseFloat(row.find('.price').val()) || 0;
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let discount = parseFloat(row.find('.discount').val()) || 0;
                let gstAmount = parseFloat(row.find('.gst_amount').val()) || 0;

                let gross = price * qty;

                totalSales += gross;
                totalDiscount += discount;
                totalGst += gstAmount;

                // Taxable amount after discount
                totalTaxable += Math.max(gross - discount, 0);
            });

            // Net = Taxable + GST
            let netSalesAmount = totalTaxable + totalGst;

            $('#summaryTotalSales').val(totalSales.toFixed(2));
            $('#summaryTotalDiscount').val(totalDiscount.toFixed(2));
            $('#summaryGstAmount').val(totalGst.toFixed(2));
            $('#summaryNetSales').val(netSalesAmount.toFixed(2));
            $('#n_amount_to_pay').val(netSalesAmount.toFixed(2));
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Product Row
        |--------------------------------------------------------------------------
        */
        $(document).on('click', '.removeRow', function () {
            $(this).closest('tr').remove();
            calculateSummary();
        });

        /*
        |--------------------------------------------------------------------------
        | Payment Mode Handling
        |--------------------------------------------------------------------------
        */
        $('.mode_of_payment').on('change', function () {
            handlePaymentMode();
        });

        function handlePaymentMode() {
            let paymentMode = $('.mode_of_payment:checked').val();

            if (!paymentMode) {
                $('#paymet-proofs').hide();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').removeClass('mandatory');
                $('#payment_image').removeClass('mandatory');
                return;
            }

            if (paymentMode === 'Paid to Franchise' || paymentMode === 'Cash on Delivery') {
                $('#paymet-proofs').hide();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').removeClass('mandatory');
                $('#payment_image').removeClass('mandatory');
            } else {
                $('#paymet-proofs').show();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').addClass('mandatory');
                $('#payment_image').addClass('mandatory');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Franchise Location Cascading (State -> District -> Panchayath -> Store)
        |--------------------------------------------------------------------------
        */
        $('#franchise_state').on('change', function () {
            let stateId = $(this).val();

            $('#franchise_district').html('<option value="">Loading...</option>');
            $('#franchise_panchayath').html('<option value="">Select Panchayath</option>');
            $('#franchise').html('<option value="">Select Franchise</option>');

            if (!stateId) {
                $('#franchise_district').html('<option value="">Select District</option>');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.filterDistrict')); ?>",
                data: { state: stateId },
                dataType: 'json',
                success: function (response) {
                    $('#franchise_district').html('<option value="">Select District</option>');
                    if (response.districts) {
                        $.each(response.districts, function (index, district) {
                            $('#franchise_district').append(`<option value="${district.id}">${district.district_name}</option>`);
                        });
                    }
                },
                error: function () {
                    $('#franchise_district').html('<option value="">Unable to load districts</option>');
                }
            });
        });

        $('#franchise_district').on('change', function () {
            let districtId = $(this).val();

            $('#franchise_panchayath').html('<option value="">Loading...</option>');
            $('#franchise').html('<option value="">Select Franchise</option>');

            if (!districtId) {
                $('#franchise_panchayath').html('<option value="">Select Panchayath</option>');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.filterPanchayath')); ?>",
                data: { district: districtId },
                dataType: 'json',
                success: function (response) {
                    $('#franchise_panchayath').html('<option value="">Select Panchayath</option>');
                    if (response.panchayaths && response.panchayaths.length > 0) {
                        $.each(response.panchayaths, function (index, panchayat) {
                            $('#franchise_panchayath').append(`<option value="${panchayat.id}">${panchayat.panchayath_name}</option>`);
                        });
                    } else {
                        $('#franchise_panchayath').html('<option value="">No Panchayaths Found</option>');
                    }
                },
                error: function () {
                    $('#franchise_panchayath').html('<option value="">Unable to load Panchayaths</option>');
                }
            });
        });

        $('#franchise_panchayath').on('change', function () {
            const panchayathId = $(this).val();
            if (!panchayathId) {
                $('#franchise').html('<option value="">Select Franchise</option>');
                return;
            }
            findNearestFranchise(panchayathId);
        });

        function findNearestFranchise(panchayathId) {
            $('#franchise').html('<option value="">Finding franchise...</option>');

            fetch("<?php echo e(route('admin.franchise.nearest')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ panchayath_id: panchayathId })
            })
            .then(res => res.json())
            .then(function (data) {
                $('#franchise').html('<option value="">Select Franchise</option>');
                if (!data.success) {
                    $('#franchise').html('<option value="">No Franchise Found</option>');
                    return;
                }

                let franchises = Array.isArray(data.franchises) ? data.franchises : (data.franchises ? [data.franchises] : []);
                if (franchises.length === 0) {
                    $('#franchise').html('<option value="">No Franchise Found</option>');
                    return;
                }

                franchises.forEach(function (f) {
                    $('#franchise').append(`<option value="${f.n_store_id}">${f.c_store_name} ${f.c_store_code ? '(' + f.c_store_code + ')' : ''}</option>`);
                });

                $('#franchise').val(franchises[0].n_store_id);
            })
            .catch(function () {
                $('#franchise').html('<option value="">Unable to find franchise</option>');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Order Type (Company vs Franchise)
        |--------------------------------------------------------------------------
        */
        function toggleOrderType() {
            const orderType = $('input[name="order_type"]:checked').val();
            if (orderType === 'franchise') {
                $('#franchise-location-details').show();
                $('#franchise_state, #franchise_district, #franchise_panchayath, #franchise').addClass('mandatory');
            } else if (orderType === 'company') {
                $('#franchise-location-details').hide();
                $('#franchise_state, #franchise_district, #franchise_panchayath, #franchise').removeClass('mandatory');
            }
        }

        $('input[name="order_type"]').on('change', toggleOrderType);

        /*
        |--------------------------------------------------------------------------
        | Image Upload Preview Helper
        |--------------------------------------------------------------------------
        */
        function setupImageUpload(inputId, previewId, containerId, removeInputId, removeButtonId) {
            $(document).on('change', '#' + inputId, function (event) {
                const file = event.target.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    $(this).val('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + previewId).attr('src', e.target.result).show();
                    $('#' + removeInputId).val('0');

                    if ($('#' + removeButtonId).length === 0) {
                        $('#' + containerId).append(
                            `<br><button type="button" id="${removeButtonId}" class="btn btn-danger btn-sm mt-2">Remove Image</button>`
                        );
                    } else {
                        $('#' + removeButtonId).show();
                    }
                };
                reader.readAsDataURL(file);
            });

            $(document).on('click', '#' + removeButtonId, function () {
                $('#' + inputId).val('');
                $('#' + previewId).attr('src', '').hide();
                $('#' + removeInputId).val('1');
                $(this).hide();
            });
        }

        setupImageUpload('payment_image', 'payment_image_preview', 'payment_preview_container', 'remove_payment_image', 'remove_payment_image_btn');
        setupImageUpload('booklet_image', 'booklet_image_preview', 'booklet_image_preview_container', 'remove_booklet_image', 'remove_booklet_image_btn');

        /*
    |--------------------------------------------------------------------------
    | View Mode
    |--------------------------------------------------------------------------
    */
    var viewmode = "<?php echo e($viewmode ?? 'off'); ?>";
    if (viewmode === 'on') {
        $('#frm_create input:not([type="hidden"]):not([type="button"]):not([type="submit"])').prop('readonly', true);
        $('#frm_create textarea').prop('readonly', true);
        $('#frm_create select, #frm_create input[type="radio"], #frm_create input[type="checkbox"], #frm_create input[type="file"], #addRow, .removeRow').prop('disabled', true);
    }

    // Initialize Page
    calculateSummary();
    toggleOrderType();
    handlePaymentMode();
});

/*
|--------------------------------------------------------------------------
| Customer Toggle & Mobile Lookup
|--------------------------------------------------------------------------
*/
document.addEventListener('DOMContentLoaded', function () {
    const lookupCard = document.getElementById('lookupCard');
    const newCustomer = document.getElementById('newCustomer');
    const existingCustomer = document.getElementById('existingCustomer');

    function toggleCustomerType() {
        const selected = document.querySelector('input[name="c_customer_type"]:checked');
        if (!selected || !lookupCard) return;

        if (selected.value === 'existing') {
            lookupCard.classList.remove('d-none');
        } else {
            lookupCard.classList.add('d-none');
            $("#c_customer_code").val("<?php echo e($customerCode ?? (isset($sale) ? $sale->c_customer_code : '')); ?>");
        }
    }

    if (newCustomer) newCustomer.addEventListener('change', toggleCustomerType);
    if (existingCustomer) existingCustomer.addEventListener('change', toggleCustomerType);
    toggleCustomerType();

    const lookupBtn = document.getElementById('lookupBtn');
    if (lookupBtn) {
        lookupBtn.addEventListener('click', function () {
            const mobile = document.getElementById('lookupMobile').value.trim();
            if (!/^[0-9]{10}$/.test(mobile)) {
                alert('Please enter a valid 10-digit mobile number.');
                return;
            }

            fetch("<?php echo e(route('admin.leads.existingCustomer')); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                },
                body: JSON.stringify({ mobile: mobile })
            })
            .then(res => res.json())
            .then(function (data) {
                if (data.status === true && data.customer) {
                    $("#n_customer_id").val(data.customer.n_customer_id);
                    $("#c_customer_code").val(data.customer.c_customer_code);
                    $(".c_customer_name").val(data.customer.c_customer_name);
                    $('[name="n_whatsapp"]').val(data.customer.n_whatsapp || '');
                    $('[name="n_mobile"]').val(data.customer.n_mobile || '');
                    $('[name="c_email"]').val(data.customer.c_email || '');
                    $('[name="c_address"]').val(data.customer.c_address || '');
                    $('[name="c_pincode"]').val(data.customer.c_pincode || '');

                    if (data.customer.n_state_id) {
                        $('[name="n_state_id"]').val(data.customer.n_state_id);
                        districtFilter(data.customer.n_state_id, data.customer.n_district_id);
                    }
                    $('#lookupMessage').text('Customer loaded successfully!');
                } else {
                    alert('Customer not found.');
                }
            })
            .catch(function () {
                alert('Unable to find customer. Please try again.');
            });
        });
    }

    function districtFilter(state, selectedDistrict = null) {
        if (!state) return;
        $.ajax({
            type: 'GET',
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            data: { state: state },
            dataType: 'json',
            success: function (data) {
                $('#n_district_id').empty().append('<option value="">Select District</option>');
                if (data.districts) {
                    $.each(data.districts, function (index, d) {
                        $('#n_district_id').append(`<option value="${d.id}">${d.district_name}</option>`);
                    });
                    if (selectedDistrict) {
                        $('#n_district_id').val(selectedDistrict);
                    }
                }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>