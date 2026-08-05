

<?php $__env->startSection('content'); ?>

<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #F7941E;
    --text-muted: #64748b;
    --border-radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, .05);
}

.customer-card {
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header-styled {
    padding: 1.5rem 2rem;
    border-bottom: 2px solid #f8fafc;
    border-top: 4px solid var(--primary-green);
    background: #fff;
}

.card-title-custom {
    font-weight: 800;
    color: #1a202c;
}

.form-section-header {
    font-size: .8rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--primary-green);
    letter-spacing: 1px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section-header::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f5f9;
}

.form-label {
    font-weight: 700;
    color: #4a5568;
    font-size: .85rem;
}

.form-control,
.form-select {

    border-radius: 10px;
    padding: .75rem 1rem;
    background: #fdfdfe;

}

.form-control:focus,
.form-select:focus {

    border-color: var(--primary-green);
    box-shadow: 0 0 0 4px rgba(57, 181, 74, .08);

}

.btn-cancel-custom {

    border-radius: 10px;
    padding: 10px 25px;

}
</style>

<div class="card customer-card mb-4">

    <div class="card-header-styled d-flex justify-content-between align-items-center">

        <h5 class="card-title-custom mb-0">
            EditCustomer
        </h5>

    </div>

    <div class="card-body p-4 p-md-5">

        <form method="POST" action="<?php echo e(route('admin.customers.update', $customer)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Customer Information -->

            <div class="form-section-header">

                <i class="ti ti-user"></i>

                Customer Information

            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-6">

                    <label class="form-label">
                        Customer Code *
                    </label>

                    <input type="text" name="c_customer_code"
                        value="<?php echo e(old('c_customer_code', $customer->c_customer_code)); ?>" readonly
                        class="form-control mandatory" placeholder="CUS-001">

                    <?php $__errorArgs = ['c_customer_code'];
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

                    <label class="form-label">
                        Customer Name *
                    </label>

                    <input type="text" name="c_customer_name"
                        value="<?php echo e(old('c_customer_name', $customer->c_customer_name)); ?>" class="form-control mandatory"
                        placeholder="Customer Name">

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

                    <label class="form-label">
                        Mobile Number *
                    </label>

                    <input type="text" maxlength="10" name="n_mobile" value="<?php echo e(old('n_mobile', $customer->n_mobile)); ?>"
                        class="form-control mandatory">

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

                    <label class="form-label">
                        WhatsApp Number
                    </label>

                    <input type="text" maxlength="10" name="n_whatsapp"
                        value="<?php echo e(old('n_whatsapp', $customer->n_whatsapp)); ?>" class="form-control">

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

                    <label class="form-label">
                        Email
                    </label>

                    <input type="email" name="c_email" value="<?php echo e(old('c_email', $customer->c_email)); ?>"
                        class="form-control">

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

                <i class="ti ti-map-pin"></i>

                Address Details

            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-12">

                    <label for="c_address" class="form-label">
                        Address
                    </label>

                    <textarea id="c_address" name="c_address" rows="3" class="form-control"
                        placeholder="Enter Customer Address"><?php echo e(old('c_address', $customer->c_address)); ?></textarea>

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

                    <select name="c_state" id="c_state" class="form-select">

                        <option value="">Select State</option>

                        <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($state->name); ?>" data-id="<?php echo e($state->n_state_id); ?>"
                            <?php echo e(old('c_state', $customer->c_state) == $state->name ? 'selected' : ''); ?>>

                            <?php echo e($state->name); ?>


                        </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>


                <div class="col-md-4">

                    <select name="c_district" id="c_district" class="form-select">

                        <option value="">Select District</option>

                        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($district->district_name); ?>"
                            <?php echo e(old('c_district', $customer->c_district) == $district->district_name ? 'selected' : ''); ?>>

                            <?php echo e($district->district_name); ?>


                        </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>


                <div class="col-md-4">

                    <label for="c_pincode" class="form-label">
                        Pincode
                    </label>

                    <input type="text" id="c_pincode" name="c_pincode" maxlength="6"
                        value="<?php echo e(old('c_pincode', $customer->c_pincode)); ?>" class="form-control" placeholder="Pincode">

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
                <i class="ti ti-checkup-list"></i>
                Customer Status
            </div>

            <div class="row g-4 mb-5">

                <div class="col-md-4">

                    <label for="c_status" class="form-label">
                        Status <span class="text-danger">*</span>
                    </label>

                    <select id="c_status" name="c_status" class="form-select mandatory">

                        <option value="">Select Status</option>

                        <option value="Y" <?php echo e(old('c_status', $customer->c_status) == 'Y' ? 'selected' : ''); ?>>
                            Active
                        </option>

                        <option value="N" <?php echo e(old('c_status', $customer->c_status) == 'N' ? 'selected' : ''); ?>>
                            Inactive
                        </option>
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

            <!-- Footer -->

            <div class="d-flex gap-3 pt-4 border-top">

                <button type="submit" class="btn buttonSpc">

                    <i class="ti ti-device-floppy me-1"></i>

                    Update Customer

                </button>

                <a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-outline-secondary btn-cancel-custom">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

<?php $__env->startPush('scripts'); ?>

<script src="<?php echo e(asset('dist/js/custom.js')); ?>"></script>

<script>
$(document).ready(function() {

    // Allow only numbers for Mobile, WhatsApp & Pincode
    $('#n_mobile, #n_whatsapp, #c_pincode').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // Convert Customer Code to uppercase
    $('#c_customer_code').on('keyup', function() {
        $(this).val($(this).val().toUpperCase());
    });

});
</script>
<script>
$('#c_state').change(function() {

    let stateId = $(this).find(':selected').data('id');

    $('#c_district').html('<option>Loading...</option>');

    $.get('/admin/districts/' + stateId, function(data) {

        $('#c_district').html('<option value="">Select District</option>');

        $.each(data, function(index, district) {

            $('#c_district').append(
                '<option value="' + district.district_name + '">' +
                district.district_name +
                '</option>'
            );

        });

    });

});
</script>

<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/customers/edit.blade.php ENDPATH**/ ?>