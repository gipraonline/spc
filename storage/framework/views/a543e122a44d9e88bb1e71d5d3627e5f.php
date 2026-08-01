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

        <form method="POST" id="frm_create" action="<?php echo e(route('admin.salesorders.store')); ?>">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="id" class="form-control"  value="<?php echo e(isset($sale) ? $sale->n_sl_no : ''); ?>">

            <!-- Row 1 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date *</label>
                    <input type="date" name="d_date" class="form-control mandatory" data-message="Please Select a Date" value="<?php echo e(old('d_date', isset($sale) ? $sale->d_date->format('Y-m-d') : '')); ?>" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Bill No *</label>
                    <input type="text" name="c_bill_no" class="form-control mandatory" data-message="Please Enter Bill No" value="<?php echo e(old('c_bill_no',isset($sale) ? $sale->c_bill_no : '')); ?>" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>


            </div>

            <!-- Row 2 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Farm Care Advisor *</label>
                    <select name="farm_care_advisor_id" data-message="Please Select Farm Care Advisor" class="form-select mandatory" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
                        <option value="">Select</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->n_employee_id); ?>" <?php echo e(old('farm_care_advisor_id', $sale->farm_care_advisor_id ?? '') == $employee->n_employee_id ? 'selected' : ''); ?> >
                            <?php echo e($employee->c_employee_name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

            </div>

            <!-- Row 3 -->
            <div class="row">
                <label class="form-label">Order Details *</label>
                <?php if(isset($viewmode) && $viewmode=='off'): ?>
                    <button type="button" style="width:150px;position:relative;" class="btn mb-1 btn-primary" id="addRow">Add Product</button>
                <?php endif; ?>
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
                    <?php if(isset($sale->orderProducts)): ?>

                        <?php $__currentLoopData = $sale->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <tr>
                                <td>
                                    <select name="products[<?php echo e($key); ?>][product_id]" class="form-control product mandatory">
                                        <option value="">Select Product</option>
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
                                    <input type="text"
                                        name="products[<?php echo e($key); ?>][product_price]"
                                        class="form-control price"
                                        value="<?php echo e($val->product_price); ?>"
                                        readonly>
                                </td>

                                <td>
                                    <input type="number"
                                        name="products[<?php echo e($key); ?>][qty]"
                                        class="form-control qty"
                                        value="<?php echo e($val->qty); ?>">
                                </td>

                                <td>
                                    <input type="text"
                                        name="products[<?php echo e($key); ?>][product_total]"
                                        class="form-control total"
                                        value="<?php echo e($val->product_total); ?>"
                                        readonly>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger removeRow">X</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>



                    </tbody>
                </table>
            </div>

            <!-- Section 4: Contact & Status -->
            <div class="form-section-header mb-3" >
                <i class="ti ti-mail fs-5"></i> Contact & Status
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_customer_name" class="form-label">Customer Name *</label>
                    <input type="text" id="c_customer_name" name="c_customer_name" value="<?php echo e(old('c_customer_name',isset($sale) ? $sale->c_customer_name : '')); ?>"
                       <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?> data-message="Please add Customer Name" class="form-control mandatory" placeholder="Customer Name">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="c_customer_email" class="form-label">Customer Email *</label>
                    <input type="text" id="c_customer_email" name="c_customer_email" value="<?php echo e(old('c_customer_email',isset($sale) ? $sale->c_customer_email : '')); ?>"
                        <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>

                        data-message="Please enter Customer Email" class="form-control mandatory"
                        placeholder="Enter Customer Email">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="c_customer_address" class="form-label">Customer Address *</label>
                    <input type="text" id="c_customer_address" name="c_customer_address" value="<?php echo e(old('c_customer_address',isset($sale) ? $sale->c_customer_address : '')); ?>"
                        <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?> data-message="Please add Customer Address" class="form-control mandatory" placeholder="ACC-001">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>

                <div class="col-md-6">
                    <label for="n_customer_mobile" class="form-label">Customer Mobile *</label>
                    <input type="text" id="n_customer_mobile" name="n_customer_mobile" value="<?php echo e(old('n_customer_mobile',isset($sale) ? $sale->n_customer_mobile : '')); ?>"
                        <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?> data-message="Please enter Customer Mobile" class="form-control mandatory"
                        placeholder="Enter IFSC code">
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select mandatory" data-message="Please enter State" id="state" name="n_state_id" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
                        <option value="" selected>Select State</option>
                        <?php if(isset($states)): ?>
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $State): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($State->n_state_id); ?>" <?php echo e(old('n_state_id', $sale->n_state_id ?? '') == $State->n_state_id ? 'selected' : ''); ?>><?php echo e($State->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">District</label>
                    <select class="form-select mandatory" data-message="Please enter District" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>  id="district" name="n_district_id">
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


           <div class="row mb-3 align-items-center">
                <label class="col-md-2 col-form-label">
                    Mode of Payment
                </label>

                <div class="col-md-9">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input mandatory" type="radio" name="c_mode_of_payment" id="cod" data-message="Please enter Mode of Payment" value="cash_on_delivery" <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == 'cash_on_delivery' ? 'checked' : ''); ?> <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
                        <label class="form-check-label" for="cod">
                            Cash on Delivery
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="c_mode_of_payment" id="upi" value="UPI" <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == 'UPI' ? 'checked' : ''); ?> <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
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
                    <select class="form-select mandatory" data-message="Please enter Nearest Franchise" id="franchise" name="nearest_franchise_id" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
                        <option value="" selected>Select Franchise</option>
                         <?php if(isset($franchises)): ?>
                            <?php $__currentLoopData = $franchises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $franchise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($franchise->n_store_id); ?>" <?php echo e(old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : ''); ?>><?php echo e($franchise->c_store_name); ?>(<?php echo e($franchise->c_store_code); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">Payment Status</label>
                    <select class="form-select mandatory" data-message="Please enter Payment Status" id="payment_status" name="payment_status" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
                        <option value="">Select Status</option>
                        <option value="pending" <?php echo e(old('payment_status', $sale->payment_status ?? '') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(old('payment_status', $sale->payment_status ?? '') == 'approved' ?  'selected' : ''); ?>>Approved</option>
                        <option value="cancelled" <?php echo e(old('payment_status', $sale->payment_status ?? '') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="state" class="form-label">Delivery Status</label>
                    <select class="form-select mandatory" data-message="Please enter Delivory Status" id="delivery_status" name="delivery_status" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
                        <option value="">Select Delivery Status</option>
                        <option value="pending" <?php echo e(old('delivery_status', $sale->delivery_status ?? '') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="shipped" <?php echo e(old('delivery_status', $sale->delivery_status ?? '') == 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                        <option value="delivered" <?php echo e(old('delivery_status', $sale->delivery_status ?? '') == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                    </select>
                    <div class="text-danger mt-1 fs-2"></div>
                </div>
            </div>


            <!-- Buttons -->
            <div class="mt-3">
                <?php if(isset($viewmode) && $viewmode=="on"): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.approve')): ?>
                        <button type="button" style="width:150px;position:relative;" class="btn mb-1 btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal" data-id="<?php echo e(isset($sale) ? Crypt::encryptString($sale->n_sl_no) : ''); ?>" id="approve">Approve</button>
                    <?php endif; ?>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" id="btn_create"><?php echo e(isset($sale->n_sl_no) ? 'Update' : 'Create'); ?></button>
                    <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
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
                    <h5 class="modal-title" id="approveModalLabel">Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>

        </form>
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function(){
            let rowIndex = <?php echo e(isset($sale) ? count($sale->orderProducts) : 0); ?>;

            $("#addRow").click(function () {

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


            $(document).on("change",".product",function(){

                productTotal($(this));

            });

             $(document).on("change",".qty",function(){

                productTotal($(this).parent().siblings().find(".product"));

            });



            $(document).on("click",".removeRow",function(){
                $(this).closest("tr").remove();
            });

            $(document).on("change","#state",function(){
                var state=$(this).val();
                $.ajax({
                        type: "get",
                        url:"<?php echo e(route('admin.filterDistrict')); ?>",
                        data: { state:state },
                        cache: false,
                        dataType:'json',
                        success: function(data)
                        {
                            console.log(data);
                                $("#district").empty();
                                $("#district").append('<option value="">Select District</option>');

                                $.each(data.districts, function (index, district) {
                                    $("#district").append(
                                        '<option value="' + district.id + '">' + district.district_name + '</option>'
                                    );
                                });
                        }
                });
            });

            function productTotal(id){
                let row = id.closest("tr");

                let price = id.find(":selected").data("price");

                row.find(".price").val(price);

                let qty = row.find(".qty").val();

                row.find(".total").val(price * qty);
            }

            const approveModal = document.getElementById('approveModal');

                approveModal.addEventListener('show.bs.modal', function (event) {

                    let button = event.relatedTarget;
                    let id = button.getAttribute('data-id');

                    document.getElementById('approval_id').value = id;

                    // Set form action dynamically
                    document.getElementById('approveForm').action = "<?php echo e(route('admin.salesorders.approval.save')); ?>" ;
                });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>