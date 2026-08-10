<?php $__env->startPush('styles'); ?>
<style>
    .customer-toggle{
        display:flex;
        width:420px;
        max-width:100%;
        padding:6px;
        background:#edf2f7;
        border:1px solid #dce3ea;
        border-radius:14px;
    }

    .customer-toggle .toggle-btn{
        flex:1;
        margin:0;
        padding:12px 20px;
        text-align:center;
        border-radius:10px;
        cursor:pointer;
        font-weight:600;
        color:#5b6b8a;
        transition:all .3s ease;
    }


    .customer-toggle .btn-check:checked + .toggle-btn{
        background: linear-gradient(135deg, #5A8D3A, #074E30);
        color:#fff;
    }

    .customer-toggle .customer-toggle .toggle-btn:hover{
        background: linear-gradient(135deg, #5A8D3A, #074E30);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Crypt;
?>
<div class="card w-100 position-relative overflow-hidden mb-4">

    <!-- Header -->
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">
            Lead Entry
        </h5>

        <a href="<?php echo e(route('admin.leads.index')); ?>" class="btn buttonSpc">
            <i class="ti ti-list-details me-1"></i>
            View Leads
        </a>
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

        <form action="<?php echo e(route('admin.leads.store')); ?>"
              method="POST" id="frm_create">

            <?php echo csrf_field(); ?>

            <input type="hidden" name="n_lead_id" value="<?php echo e($lead->n_lead_id); ?>">



            <?php if(isset($user) && $user->identifier != "FCA"): ?>
                <div class="customer-toggle mb-4">
                    <select name="n_fca_id" class="form-control mandatory">
                                    <option value="">Select Farm Care Adviser</option>

                                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->n_employee_id); ?>" <?php echo e(isset($lead->n_fca_id) && $lead->n_fca_id==$employee->n_employee_id ? "selected": ''); ?>>
                                        <?php echo e($employee->c_employee_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endif; ?>
            <!-- Customer Type -->
            <div class="customer-toggle mb-4">

                <input type="radio" class="btn-check " name="c_customer_type"
                    id="newCustomer" value="new" <?php echo e(isset($lead) && $lead->c_customer_type=="new" ? "checked" : ''); ?>>

                <label class="toggle-btn" for="newCustomer">
                    New Customer
                </label>

                <input type="radio" class="btn-check" name="c_customer_type"
                    id="existingCustomer" value="existing" <?php echo e(isset($lead) && $lead->c_customer_type=="existing" ? "checked" : ''); ?>>

                <label class="toggle-btn" for="existingCustomer">
                    Existing Customer
                </label>

            </div>


            <?php if(!isset($lead->n_lead_id)): ?>
            <!-- Existing Customer Lookup -->
            <div class="card border rounded-4 mb-4 d-none" id="lookupCard">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        Existing Customer Lookup
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row align-items-end">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Mobile Number
                            </label>

                            <input type="text"
                                id="lookupMobile"
                                class="form-control"
                                placeholder="Enter Mobile Number">
                        </div>

                        <div class="col-md-3">
                            <button type="button"
                                    id="lookupBtn"
                                    class="btn buttonSpc w-100">
                                <i class="ti ti-search me-1"></i>
                                Find Customer
                            </button>
                        </div>

                        <div class="col-md-3">
                            <small id="lookupMessage"
                                class="text-success fw-semibold">
                            </small>
                        </div>

                    </div>

                </div>

            </div>
            <?php endif; ?>
            <!-- Customer Details -->

            <div class="card border rounded-4 mb-4">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        Customer  Details
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row">

                        <!-- Customer Name -->

                        <div class="col-lg-4 mb-3">

                            <label class="form-label fw-semibold">
                                Customer Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="c_customer_name"
                                   class="form-control <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('c_customer_name',$lead->c_customer_name ?? '')); ?>"
                                   placeholder="Enter Customer Name">

                            <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        <!-- Mobile -->

                        <div class="col-lg-4 mb-3">

                            <label class="form-label fw-semibold">
                                Mobile Number
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="n_mobile"
                                   class="form-control <?php $__errorArgs = ['n_mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('n_mobile',$lead->n_mobile ?? '')); ?>"
                                   maxlength="10"
                                   placeholder="Enter Mobile Number">

                            <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        <!-- District -->

                       <div class="col-md-4">
                            <label for="c_email" class="form-label">Email</label>
                            <input type="text" id="c_email" name="c_email" value="<?php echo e(old('c_email',$lead->c_email ?? '')); ?>"
                                data-message="Please enter Customer Email" class="form-control "
                                placeholder="Enter Customer Email">
                            <div class="text-danger mt-1 fs-2"></div>
                        </div>

                         <!-- address -->

                         <div class="col-md-12">
                            <label for="c_customer_address" class="form-label">Customer Address *</label>
                            <input type="text" id="c_address" name="c_address" value="<?php echo e(old('c_address',isset($lead) ? $lead->c_address : '')); ?>"
                               data-message="Please add Customer Address" class="form-control mandatory" placeholder="Customer Address">
                            <div class="text-danger mt-1 fs-2"></div>
                        </div>

                        <!-- State -->

                        <div class="col-md-6">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select mandatory" data-message="Please enter State" id="state" name="n_state_id" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>>
                                <option value="" selected>Select State</option>
                                <?php if(isset($states)): ?>
                                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $State): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($State->n_state_id); ?>" <?php echo e(old('n_state_id', $lead->n_state_id ?? '') == $State->n_state_id ? 'selected' : ''); ?>><?php echo e($State->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <div class="text-danger mt-1 fs-2"></div>
                        </div>


                        <!-- District -->

                        <div class="col-md-6">
                            <label for="state" class="form-label">District</label>
                            <select class="form-select mandatory" data-message="Please enter District" <?php echo e(isset($viewmode) && $viewmode=='on' ? 'disabled' : ''); ?>  id="district" name="n_district_id">
                                <option value="" selected>Select District</option>
                                <?php if(isset($lead->n_district_id)): ?>
                                    <?php $districts = \App\Models\District::where('state_id', $lead->n_state_id)->get(); ?>
                                    <?php if(isset($districts)): ?>
                                        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($district->id); ?>" <?php echo e(old('n_district_id', $lead->n_district_id ?? '') == $district->id ? 'selected' : ''); ?>><?php echo e($district->district_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endif; ?>

                            </select>
                            <div class="text-danger mt-1 fs-2"></div>
                        </div>


                    </div>

                </div>

            </div>
                        <!-- ============================= -->
            <!-- Discussion Details -->
            <!-- ============================= -->

            <div class="card border rounded-4 mb-4">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        Visit Details
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row">

                        <!-- Visit Date -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label fw-semibold">
                                Visit Date
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                name="d_visit_date"
                                id="d_visit_date"
                                class="form-control <?php $__errorArgs = ['d_visit_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('d_visit_date', $lead->d_visit_date ? \Carbon\Carbon::parse($lead->d_visit_date)->format('Y-m-d') : '')); ?>">


                            <?php $__errorArgs = ['d_visit_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>


                    </div>

                </div>

            </div>




                <!-- ============================= -->
            <!-- Lead Status -->
            <!-- ============================= -->

            <div class="card border rounded-4 mb-4">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        Lead Status
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-6 mb-3">

                            <label class="form-label fw-semibold">
                                Lead Status
                            </label>

                            <select name="c_lead_status"
                                    id="leadStatus"
                                    class="form-select">

                                <option value="">Select Status</option>

                                <option value="new" <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "new" ? 'selected' : ''); ?>>New</option>
                                <option value="contacted"  <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "contacted" ? 'selected' : ''); ?>>Contacted</option>
                                <option value="interested"  <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "interested" ? 'selected' : ''); ?>>Interested</option>
                                <option value="follow-up"  <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "follow-up" ? 'selected' : ''); ?>>Follow-up Required</option>
                                <option value="negotiation"  <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "negotiation" ? 'selected' : ''); ?>>Negotiation</option>
                                <option value="won" <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "won" ? 'selected' : ''); ?>>Won</option>
                                <option value="lost" <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "lost" ? 'selected' : ''); ?>>Lost</option>
                                <option value="not-nterested"  <?php echo e(old('c_lead_status', $lead->c_lead_status ?? '') == "not-nterested" ? 'selected' : ''); ?>>Not Interested</option>

                            </select>

                        </div>

                        <div class="col-lg-6 mb-3">

                            <label class="form-label fw-semibold">
                                Expected availability
                            </label>

                            <input type="date"
                                   name="d_expected_availability_date"
                                   class="form-control"
                                   value="<?php echo e(old('d_expected_availability_date', $lead->d_expected_availability_date ? \Carbon\Carbon::parse($lead->d_expected_availability_date)->format('Y-m-d') : '')); ?>">

                        </div>

                    </div>

                </div>

            </div>


            <!-- ============================= -->
            <!-- Follow-up -->
            <!-- ============================= -->

            

            <!-- ============================= -->
            <!-- Priority -->
            <!-- ============================= -->

            <div class="card border rounded-4 mb-4">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        Lead Priority
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-3">

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="priority"
                                       value="Low"
                                       id="priorityLow"
                                       <?php echo e(old('priority', $lead->priority ?? '') == "Low" ? 'checked' : ''); ?>>

                                <label class="form-check-label"
                                       for="priorityLow">

                                    Low

                                </label>

                            </div>

                        </div>

                        <div class="col-lg-3">

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="priority"
                                       value="Medium"
                                       id="priorityMedium"
                                        <?php echo e(old('priority', $lead->priority ?? '') == "Medium" ? 'checked' : ''); ?>>

                                <label class="form-check-label"
                                       for="priorityMedium">

                                    Medium

                                </label>

                            </div>

                        </div>

                        <div class="col-lg-3">

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="priority"
                                       value="High"
                                       id="priorityHigh"
                                       <?php echo e(old('priority', $lead->priority ?? '') == "High" ? 'checked' : ''); ?>>

                                <label class="form-check-label"
                                       for="priorityHigh">

                                    High

                                </label>

                            </div>

                        </div>

                        <div class="col-lg-3">

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="priority"
                                       value="Urgent"
                                       id="priorityUrgent"
                                       <?php echo e(old('priority', $lead->priority ?? '') == "Urgent" ? 'checked' : ''); ?>>

                                <label class="form-check-label"
                                       for="priorityUrgent">

                                    Urgent

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

                        <!-- ========================================= -->
            <!-- Remarks -->
            <!-- ========================================= -->

            <div class="card border rounded-4 mb-4">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        Remarks
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-12">

                            <label class="form-label fw-semibold">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="5"
                                      class="form-control <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="Enter discussion details, objections, customer requirements, quantity interested, etc."><?php echo e(old('remarks',$lead->remarks ?? '')); ?></textarea>

                            <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================= -->
            <!-- Buttons -->
            <!-- ========================================= -->

            <div class="d-flex justify-content-end gap-2">
                <?php if(isset($viewMode) && $viewMode=="Off"): ?>

                        <a href="<?php echo e(route('admin.leads.index')); ?>"
                        class="btn btn-outline-secondary">

                            <i class="ti ti-arrow-left me-1"></i>
                            Cancel

                        </a>

                        <button type="button"
                                class="btn buttonSpc"  id="btn_create">

                            <i class="ti ti-device-floppy me-1"></i>
                            Save Lead

                        </button>
                 <?php endif; ?>
            </div>

        </form>

    </div>

</div>


<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>


    <script>
        //-------------------------------------------------------
        // Existing Customer Onload
        //-------------------------------------------------------

        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('input[name="c_customer_type"]')
                .forEach(function (radio) {

                    radio.addEventListener('change', toggleCustomerType);

                });

            toggleCustomerType();
        });

        //-------------------------------------------------------
        // Existing Customer Toggle
        //-------------------------------------------------------

        const lookupCard = document.getElementById('lookupCard');
        const newCustomer = document.getElementById('newCustomer');
        const existingCustomer = document.getElementById('existingCustomer');

        function toggleCustomerType() {

            const selected = document.querySelector(
                'input[name="c_customer_type"]:checked'
            );

            if (!selected) {
                return;
            }

            const lookupCard = document.getElementById('lookupCard');

            // lookupCard doesn't exist on edit page
            if (!lookupCard) {
                return;
            }

            if (selected.value === 'existing') {
                lookupCard.classList.remove('d-none');
            } else {
                lookupCard.classList.add('d-none');
            }
        }

        if (newCustomer) {
            newCustomer.addEventListener('change', toggleCustomerType);
        }

        if (existingCustomer) {
            existingCustomer.addEventListener('change', toggleCustomerType);
        }

        toggleCustomerType();


        //-------------------------------------------------------
        // Follow-up Card
        //-------------------------------------------------------

      /*   const leadStatus = document.getElementById('leadStatus');
        const followupCard = document.getElementById('followupCard');

        function toggleFollowup() {

            if (!leadStatus || !followupCard) {
                return;
            }

            const value = leadStatus.value;

            if (
                value === 'Follow-up' ||
                value === 'Interested' ||
                value === 'Negotiation'
            ) {
                followupCard.style.display = 'block';
            } else {
                followupCard.style.display = 'none';
            }
        }

        if (leadStatus) {
            leadStatus.addEventListener('change', toggleFollowup);
            toggleFollowup();
        }
 */

        //-------------------------------------------------------
        // Mobile Lookup
        //-------------------------------------------------------

        const lookupBtn = document.getElementById('lookupBtn');

        if (lookupBtn) {

            lookupBtn.addEventListener('click', function () {

                const mobileInput = document.getElementById('lookupMobile');
                const mobile = mobileInput.value.trim();

                if (!/^[0-9]{10}$/.test(mobile)) {
                    alert('Please enter a valid 10 digit mobile number.');
                    return;
                }

                fetch("<?php echo e(route('admin.leads.existingCustomer')); ?>", {
                    method: "POST",

                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                    },

                    body: JSON.stringify({
                        mobile: mobile
                    })
                })
                .then(function (response) {

                    console.log("HTTP Status:", response.status);

                    if (!response.ok) {
                        return response.text().then(function (text) {
                            throw new Error(text);
                        });
                    }

                    return response.json();
                })
                .then(function (data) {

                    console.log("Customer Response:", data);

                    if (data.status === true) {

                        //-------------------------------------------------------
                        // Customer Details
                        //-------------------------------------------------------

                        document.querySelector('[name="c_customer_name"]').value =
                            data.customer.c_customer_name || '';

                        document.querySelector('[name="n_mobile"]').value =
                            data.customer.n_mobile || '';

                        document.querySelector('[name="c_email"]').value =
                            data.customer.c_email || '';

                        document.querySelector('[name="c_address"]').value =
                            data.customer.c_address || '';


                        //-------------------------------------------------------
                        // State
                        //-------------------------------------------------------

                        const stateDropdown =
                            document.querySelector('[name="n_state_id"]');

                        const selectedState =
                            data.customer.n_state_id;

                        if (selectedState) {

                            stateDropdown.value = selectedState;

                        } else if (data.customer.c_state) {

                            Array.from(stateDropdown.options).forEach(function (option) {

                                if (
                                    option.text.trim().toLowerCase() ===
                                    data.customer.c_state.trim().toLowerCase()
                                ) {
                                    option.selected = true;
                                }

                            });
                        }


                        //-------------------------------------------------------
                        // District
                        //-------------------------------------------------------

                        const selectedDistrict =
                            data.customer.n_district_id || null;

                        districtFilter(
                            selectedState,
                            selectedDistrict
                        );

                    } else {

                        alert('Customer not found.');

                    }

                })
                .catch(function (error) {

                    console.error('Fetch Error:', error);

                    alert('Unable to find customer. Please try again.');

                });

            });
        }


        //-------------------------------------------------------
        // State Change
        //-------------------------------------------------------

        $(document).ready(function () {

            $(document).on('change', '#state', function () {

                const state = $(this).val();

                districtFilter(state);

            });

        });


        //-------------------------------------------------------
        // District Filter
        //-------------------------------------------------------

        function districtFilter(state, selectedDistrict = null) {

            if (!state) {

                $('#district').empty();

                $('#district').append(
                    '<option value="">Select District</option>'
                );

                return;
            }

            $.ajax({

                type: 'GET',

                url: "<?php echo e(route('admin.filterDistrict')); ?>",

                data: {
                    state: state
                },

                cache: false,

                dataType: 'json',

                success: function (data) {

                    $('#district').empty();

                    $('#district').append(
                        '<option value="">Select District</option>'
                    );

                    $.each(data.districts, function (index, district) {

                        $('#district').append(
                            '<option value="' +
                            district.id +
                            '">' +
                            district.district_name +
                            '</option>'
                        );

                    });


                    //-------------------------------------------------------
                    // Select Existing Customer District
                    //-------------------------------------------------------

                    if (selectedDistrict !== null && selectedDistrict !== '') {

                        $('#district').val(selectedDistrict);

                    }

                },

                error: function (xhr) {

                    console.error(
                        'District AJAX Error:',
                        xhr.responseText
                    );

                }

            });

        }
    </script>


<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/leads/create.blade.php ENDPATH**/ ?>