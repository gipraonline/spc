<?php $__env->startSection('content'); ?>
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Add Sales Orders</h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" id="frm_create" action="<?php echo e(route('admin.salesorders.store')); ?>">
            <?php echo csrf_field(); ?>

            <!-- Row 1 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date *</label>
                    <input type="date" name="d_date" class="form-control mandatory" data-message="Please Select a Date" value="">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Bill No *</label>
                    <input type="text" name="c_bill_no" class="form-control mandatory" data-message="Please Enter Bill No" value="<?php echo e(old('c_bill_no')); ?>">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>


            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Farm Care Advisor *</label>
                    <select name="farm_care_advisor_id" data-message="Please Select Farm Care Advisor" class="form-select mandatory">
                        <option value="">Select</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->n_employee_id); ?>">
                            <?php echo e($employee->c_employee_name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

            </div>

            <!-- Row 3 -->
            <div class="row">
                <label class="form-label">Products *</label>
                <button type="button" class="btn btn-success" id="addRow">Add Product</button>
                <table class="table table-responsive " id="productTable">
                    <thead>
                        <tr>
                            <th width="45%">Product</th>
                            <th width="20%">Price</th>
                            <th width="20%">Quantity</th>
                            <th width="10%">Total</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>

            <!-- Section 4: Contact & Status -->
            <div class="form-section-header" >
                <i class="ti ti-mail fs-5"></i> Contact & Status
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_customer_name" class="form-label">Customer Name *</label>
                    <input type="text" id="c_customer_name" name="c_customer_name" value="<?php echo e(old('c_customer_name')); ?>"
                        data-message="Please add Customer Name" class="form-control mandatory" placeholder="Customer Name">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="c_customer_email" class="form-label">Customer Email *</label>
                    <input type="text" id="c_customer_email" name="c_customer_email" value="<?php echo e(old('c_customer_email')); ?>"
                        data-message="Please enter Customer Email" class="form-control mandatory"
                        placeholder="Enter Customer Email">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="account_number" class="form-label">Customer Address *</label>
                    <input type="text" id="account_number" name="account_number" value="<?php echo e(old('c_customer_address')); ?>"
                        data-message="Please add Customer Address" class="form-control mandatory" placeholder="ACC-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">Customer Mobile *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" value="<?php echo e(old('n_customer_mobile')); ?>"
                        data-message="Please enter Customer Mobile" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">District</label>
                    <select class="form-select mandatory" data-message="Please enter District" id="district" name="district">
                        <option value="" selected>Select District</option>
                        <?php if(isset($districts)): ?>
                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="1"><?php echo e($district->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select mandatory" data-message="Please enter State"  id="state" name="state">
                        <option value="" selected>Select State</option>
                         <?php if(isset($states)): ?>
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="1"><?php echo e($state->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>


           <div class="row mb-3 align-items-center">
                <label class="col-md-2 col-form-label">
                    Mode of Payment
                </label>

                <div class="col-md-9">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input mandatory" type="radio" name="mode_of_payment" id="cod" data-message="Please enter Mode of Payment" value="cash_on_delivery" >
                        <label class="form-check-label" for="cod">
                            Cash on Delivery
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="mode_of_payment" id="upi" value="UPI">
                        <label class="form-check-label" for="upi">
                            UPI
                        </label>
                    </div>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>


             <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">Nearest Franchise</label>
                    <select class="form-select mandatory" data-message="Please enter Nearest Franchise" id="state" name="nearest_franchise_id">
                        <option value="" selected>Select Franchise</option>
                         <?php if(isset($shops)): ?>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="1"><?php echo e($shop->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">Payment Status</label>
                    <select class="form-select mandatory" data-message="Please enter Payment Status" id="state" name="payment_status">
                        <option value="" selected>Select Status</option>
                        <option value="1">Ordered</option>
                        <option value="2">Paid</option>
                        <option value="3">Approved</option>
                        <option value="4">Cancelled</option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">Delivery Status</label>
                    <select class="form-select mandatory" data-message="Please enter Delivory Status" id="state" name="delivery_status">
                        <option value="" selected>Select Delivery Status</option>
                        <option value="1">Ordered</option>
                        <option value="2">Shipped</option>
                        <option value="3">Delivered</option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-3">
                <button type="button" class="btn btn-primary" id="btn_create">Create</button>
                <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function(){
            let rowIndex = 0;

            $("#addRow").click(function () {

                let row = `
                <tr>
                    <td>
                        <select name="products[${rowIndex}][product_id]" class="form-control product mandatory" data-message="Please Select Product">
                            <option value="">Select Product</option>

                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>"
                                        data-price="<?php echo e($product->price); ?>">
                                    <?php echo e($product->product_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>
                        <div class="text-danger mt-1 fs-2"></div>
                    </td>

                    <td>
                        <input type="text"
                            name="products[${rowIndex}][price]"
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


            $(document).on("change",".product",function(){

                let row = $(this).closest("tr");

                let price = $(this).find(":selected").data("price");

                row.find(".price").val(price);

                let qty = row.find(".qty").val();

                row.find(".total").val(price * qty);

            });



            $(document).on("click",".removeRow",function(){
                $(this).closest("tr").remove();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>