<?php $__env->startPush('styles'); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
<style>
/* ===== Add / Edit Customer — same visual language as the Employee Records module ===== */
.employee-page.efp {
    --brand: #17694a;
    --brand-strong: #0e3d26;
    --brand-ink: #08301e;
    --brand-bright: #1b6b49;
    --brand-glow: rgba(14, 61, 38, .35);
    --brand-soft: #e7efe9;
    --brand-softer: #f4f7f4;
    --line: rgba(14, 61, 38, 0.13);
    --line-soft: rgba(14, 61, 38, 0.07);
    --text: #101a14;
    --text-muted: #6e7a71;
    --bad: #C23A3A;
    --shadow-sm: 0 1px 2px rgba(8, 46, 29, .05);
    --font-head: 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Plus Jakarta Sans', sans-serif;
    padding: 24px clamp(16px, 3vw, 32px) 8px;
    font-family: var(--font-body);
    color: var(--text);
}

/* Page heading */
.efp .employee-page-heading { margin-bottom: 18px; }
.efp .employee-page-heading h2 {
    margin: 0;
    font-family: var(--font-head);
    font-weight: 600;
    font-size: 21px;
    color: var(--brand-ink);
    display: flex;
    align-items: center;
    gap: 10px;
}
.efp .employee-page-heading h2 i {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}
.efp .employee-page-heading p { margin: 5px 0 0; color: var(--text-muted); font-size: 13px; }

/* Split card */
.efp-card {
    display: grid;
    grid-template-columns: 300px minmax(0, 1fr);
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

/* Left panel */
.efp-side {
    background: linear-gradient(160deg, #0e3d26 0%, #0e3d26 55%, #17694a 125%);
    color: #fff;
    padding: 28px 22px;
}
.efp-side-inner { position: sticky; top: 24px; }
.efp-side-ico {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: rgba(255, 255, 255, .14);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-left: 4px;
}
.efp-side h3 {
    margin: 16px 4px 6px;
    font-family: var(--font-head);
    font-size: 20px;
    font-weight: 600;
    line-height: 1.25;
    color: #fff;
    word-break: break-word;
}
.efp-side p { margin: 0 4px; font-size: 13px; line-height: 1.55; color: rgba(255, 255, 255, .78); }
.efp-steps { margin-top: 22px; display: grid; gap: 2px; }
.efp-step {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 8px 10px;
    border-radius: 10px;
    font-size: 13px;
    color: rgba(255, 255, 255, .9);
    text-decoration: none;
}
.efp-step:hover { background: rgba(255, 255, 255, .09); color: #fff; }
.efp-step .num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .16);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
}

/* Form body */
.efp-body { padding: 26px 30px 0; background: #fff; min-width: 0; margin: 0; }
.efp-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: var(--brand-softer);
    border: 1px solid var(--line-soft);
    color: var(--brand-ink);
    border-radius: 12px;
    padding: 11px 14px;
    font-size: 12.5px;
    line-height: 1.5;
    margin-bottom: 22px;
}
.efp-note i { color: var(--brand); font-size: 16px; margin-top: 1px; }
.efp-alert {
    background: #FBE7E4;
    border: 1px solid rgba(194, 58, 58, .25);
    color: #942B2B;
    border-radius: 12px;
    padding: 11px 14px 11px 18px;
    font-size: 12.5px;
    margin-bottom: 22px;
}
.efp-alert ul { margin: 0; padding-left: 16px; }

.efp-section {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-head);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--brand);
    margin: 4px 0 14px;
    scroll-margin-top: 20px;
}
.efp-section i { font-size: 16px; }
.efp-section::after { content: ''; flex: 1; height: 1px; background: var(--line-soft); }

.efp-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px 18px;
    margin-bottom: 26px;
}
.efp-field { min-width: 0; }
.efp-field.full { grid-column: 1 / -1; }
.efp-field > label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: #3f4a43;
    margin-bottom: 6px;
}
.efp-field > label i { color: var(--brand); font-size: 15px; }

.efp-field .form-control,
.efp-field .form-select {
    height: 42px;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 0 12px;
    font-family: var(--font-body);
    font-size: 13.5px;
    font-weight: 400;
    color: var(--text);
    background-color: #fbfdfb;
    box-shadow: none;
    transition: border-color .2s, box-shadow .2s, background-color .2s;
}
.efp-field .form-select { padding-right: 34px; }
.efp-field textarea.form-control { height: auto; min-height: 92px; padding: 10px 12px; resize: vertical; }
.efp-field .form-control::placeholder { color: #97a299; }
.efp-field .form-control:focus,
.efp-field .form-select:focus {
    border-color: var(--brand-bright);
    background-color: #fff;
    box-shadow: 0 0 0 3.5px rgba(14, 61, 38, .14);
    outline: 0;
}
.efp-field .form-control:disabled,
.efp-field .form-control[readonly] {
    background-color: #F1F5F2;
    color: var(--text-muted);
    cursor: not-allowed;
}
.efp-field .form-control.is-invalid,
.efp-field .form-select.is-invalid { border-color: var(--bad); }
.efp-hint { display: block; margin-top: 5px; font-size: 11.5px; color: var(--text-muted); }
.efp-err { margin-top: 5px; font-size: 12px; }

/* Footer */
.efp-foot {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    padding: 16px 0 22px;
    border-top: 1px solid var(--line-soft);
}
.efp-hint-secure {
    margin-right: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
}
.efp-btn {
    height: 41px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0 18px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid transparent;
    line-height: 1;
}
.efp-btn-secondary { background: var(--brand-softer); color: var(--brand); border-color: var(--line); }
.efp-btn-secondary:hover { background: var(--brand-soft); color: var(--brand-strong); }
.efp-btn-primary {
    background: linear-gradient(135deg, #1b6b49, #0e3d26);
    color: #fff;
    box-shadow: 0 10px 20px -10px var(--brand-glow);
}
.efp-btn-primary:hover { filter: brightness(1.07); color: #fff; }

@media (max-width: 992px) {
    .efp-card { grid-template-columns: 1fr; }
    .efp-side { padding: 22px 20px; }
    .efp-side-inner { position: static; }
    .efp-steps { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); }
}
@media (max-width: 640px) {
    .efp-grid { grid-template-columns: 1fr; }
    .efp-body { padding: 20px 18px 0; }
}

/* 6-column grid so 2 / 3 / 6 wide fields can share rows */
.efp-grid-6 { grid-template-columns: repeat(6, minmax(0, 1fr)); }
.efp-grid-6 > * { grid-column: span 6; }
.efp-grid-6 > .span-3 { grid-column: span 3; }
.efp-grid-6 > .span-2 { grid-column: span 2; }
.efp-err:empty { display: none; }
@media (max-width: 1200px) { .efp-grid-6 > .span-2 { grid-column: span 3; } }
@media (max-width: 640px) {
    .efp-grid-6 > *, .efp-grid-6 > .span-2, .efp-grid-6 > .span-3 { grid-column: 1 / -1; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="employee-page efp">
    <div class="employee-page-heading">
        <h2><i class="ti ti-users"></i>Edit Customer</h2>
        <p>Update the details for this customer.</p>
    </div>

    <div class="efp-card">
        <aside class="efp-side">
            <div class="efp-side-inner">
                <div class="efp-side-ico"><i class="ti ti-users"></i></div>
                <h3>Edit — <?php echo e($customer->c_customer_name); ?></h3>
                <p><?php echo e($customer->c_customer_code); ?></p>
                <div class="efp-steps">
                    <a href="#sec-info" class="efp-step"><span class="num">1</span>Customer information</a>
                    <a href="#sec-address" class="efp-step"><span class="num">2</span>Address details</a>
                    <a href="#sec-status" class="efp-step"><span class="num">3</span>Customer status</a>
                </div>
            </div>
        </aside>

        <form method="POST" action="<?php echo e(route('admin.customers.update', $customer)); ?>" class="efp-body">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="efp-note"><i class="ti ti-pencil"></i>The customer code is locked and can't be edited.</div>

            <!-- Customer Information -->
            <div class="efp-section" id="sec-info"><i class="ti ti-user"></i>Customer Information</div>
            <div class="efp-grid efp-grid-6">
                <div class="efp-field span-3">
                    <label><i class="ti ti-id-badge-2"></i>Customer Code *</label>
                    <input type="text" name="c_customer_code"
                        value="<?php echo e(old('c_customer_code', $customer->c_customer_code)); ?>" readonly
                        class="form-control mandatory" placeholder="CUS-001">
                    <?php $__errorArgs = ['c_customer_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="efp-field span-3">
                    <label><i class="ti ti-user"></i>Customer Name *</label>
                    <input type="text" name="c_customer_name"
                        value="<?php echo e(old('c_customer_name', $customer->c_customer_name)); ?>" class="form-control mandatory"
                        placeholder="Customer Name">
                    <?php $__errorArgs = ['c_customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="efp-field span-3">
                    <label><i class="ti ti-phone"></i>Mobile Number *</label>
                    <input type="text" maxlength="10" name="n_mobile" value="<?php echo e(old('n_mobile', $customer->n_mobile)); ?>"
                        class="form-control mandatory">
                    <?php $__errorArgs = ['n_mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="efp-field span-3">
                    <label><i class="ti ti-brand-whatsapp"></i>WhatsApp Number(Optional)</label>
                    <input type="text" maxlength="10" name="n_whatsapp"
                        value="<?php echo e(old('n_whatsapp', $customer->n_whatsapp)); ?>" class="form-control">
                    <?php $__errorArgs = ['n_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="efp-field">
                    <label><i class="ti ti-mail"></i>Email(Optional)</label>
                    <input type="email" name="c_email" value="<?php echo e(old('c_email', $customer->c_email)); ?>"
                        class="form-control">
                    <?php $__errorArgs = ['c_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Address Details -->
            <div class="efp-section" id="sec-address"><i class="ti ti-map-pin"></i>Address Details</div>
            <div class="efp-grid efp-grid-6">
                <div class="efp-field">
                    <label for="c_address"><i class="ti ti-home"></i>Address</label>
                    <textarea id="c_address" name="c_address" rows="3" class="form-control"
                        placeholder="Enter Customer Address"><?php echo e(old('c_address', $customer->c_address) ?? ''); ?></textarea>
                    <?php $__errorArgs = ['c_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                

                <div class="efp-field span-2">
                    <label for="n_state_id"><i class="ti ti-map-2"></i>State</label>
                    <select name="n_state_id" id="n_state_id" class="form-select">
                        <option value="">Select State</option>
                        <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($state->n_state_id); ?>"
                            <?php echo e(old('n_state_id', $customer->n_state_id) == $state->n_state_id ? 'selected' : ''); ?>>
                            <?php echo e($state->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="efp-field span-2">
                    <label for="n_district_id"><i class="ti ti-map"></i>District</label>
                    <select name="n_district_id" id="n_district_id" class="form-select">
                        <option value="">Select District</option>
                        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($district->id); ?>"
                            <?php echo e(old('n_district_id', $customer->n_district_id) == $district->id ? 'selected' : ''); ?>>
                            <?php echo e($district->district_name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="efp-field span-3">
                    <label for="c_thaluk"><i class="ti ti-building-community"></i>Thaluk</label>
                    <input type="text" id="c_thaluk" name="c_thaluk" value="<?php echo e(old('c_thaluk',$customer->c_thaluk) ?? ''); ?>"
                        class="form-control" placeholder="Thaluk">
                    <?php $__errorArgs = ['c_thaluk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="efp-field span-2">
                    <label for="c_post_office"><i class="ti ti-mailbox"></i>Post Office</label>
                    <input type="text" id="c_post_office" name="c_post_office" value="<?php echo e(old('c_post_office',$customer->c_post_office)); ?>"
                        class="form-control" placeholder="Post Office">
                    <?php $__errorArgs = ['c_post_office'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="efp-field span-3">
                    <label for="c_pincode"><i class="ti ti-map-pin-code"></i>Pincode</label>
                    <input type="text" id="c_pincode" name="c_pincode" maxlength="6"
                        value="<?php echo e(old('c_pincode', $customer->c_pincode)); ?>" class="form-control" placeholder="Pincode">
                    <?php $__errorArgs = ['c_pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Customer Status -->
            <div class="efp-section" id="sec-status"><i class="ti ti-checkup-list"></i>Customer Status</div>
            <div class="efp-grid efp-grid-6">
                <div class="efp-field span-3">
                    <label for="c_status"><i class="ti ti-circle-half-2"></i>Status *</label>
                    <select id="c_status" name="c_status" class="form-select mandatory">
                        <option value="">Select Status</option>
                        <option value="Y" <?php echo e(old('c_status', $customer->c_status) == 'Y' ? 'selected' : ''); ?>>Active</option>
                        <option value="N" <?php echo e(old('c_status', $customer->c_status) == 'N' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                    <?php $__errorArgs = ['c_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger efp-err"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="efp-foot">
                <span class="efp-hint-secure"><i class="ti ti-asterisk"></i>Fields marked * are required</span>
                <a href="<?php echo e(route('admin.customers.index')); ?>" class="efp-btn efp-btn-secondary">Cancel</a>
                <button type="submit" class="efp-btn efp-btn-primary">
                    <i class="ti ti-device-floppy"></i>Update Customer
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

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
$('#n_state_id').change(function() {

    let stateId = $(this).val();

    $('#n_district_id').html('<option>Loading...</option>');

    $.get('/admin/districts/' + stateId, function(data) {

        $('#n_district_id').html('<option value="">Select District</option>');

        $.each(data, function(index, district) {

            $('#n_district_id').append(
                '<option value="' + district.id + '">' +
                district.district_name +
                '</option>'
            );

        });

    });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/customers/edit.blade.php ENDPATH**/ ?>