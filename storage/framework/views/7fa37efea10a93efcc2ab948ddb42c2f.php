<?php $__env->startPush('styles'); ?>
<style>
.spc-wrap{--sb:var(--brand,#5E8D3D);--sb2:#1F5C2E;--ssoft:var(--brand-soft,#EEF5E6);--sink:#1F3D14;--smut:#61756B;--sline:rgba(18,58,40,.12);}
.spc-heading{margin-bottom:18px;display:flex;justify-content:space-between;align-items:flex-start;gap:14px;flex-wrap:wrap;}
.spc-heading h2{display:flex;align-items:center;gap:10px;margin:0;font-family:var(--font-head,'Kanit',sans-serif);font-size:22px;font-weight:600;color:var(--sink);}
.spc-heading h2 i{color:var(--sb);background:var(--ssoft);width:34px;height:34px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:14px;}
.spc-heading p{margin:4px 0 0 44px;font-size:13px;color:var(--smut);}
.spc-card{background:#fff;border:1px solid var(--sline);border-radius:18px;box-shadow:0 5px 18px rgba(15,81,50,.08);overflow:hidden;}
.spc-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;height:45px;padding:0 20px;border-radius:12px;font-weight:600;font-size:14px;text-decoration:none;cursor:pointer;border:1px solid transparent;transition:transform .2s,box-shadow .2s;}
.spc-btn-primary{background:linear-gradient(135deg,#7CA243,#1F5C2E);color:#fff;box-shadow:0 10px 18px -10px rgba(31,92,46,.6);}
.spc-btn-primary:hover{color:#fff;transform:translateY(-2px);box-shadow:0 16px 24px -12px rgba(31,92,46,.7);}
.spc-btn-secondary{background:#fff;color:#475569;border-color:#d5dde3;}
.spc-btn-secondary:hover{background:#f6f8f7;color:#1F3D14;}
.spc-toolbar{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;padding:18px 20px;border-bottom:1px solid var(--sline);background:linear-gradient(45deg,rgba(203,255,205,.25),transparent 60%),#fff;}
.spc-toolbar .spc-f{display:flex;flex-direction:column;gap:6px;min-width:150px;flex:1 1 150px;}
.spc-toolbar label,.spc-lbl{font-size:11px;font-weight:700;color:var(--smut);text-transform:uppercase;letter-spacing:.06em;margin:0;}
.spc-toolbar .form-control,.spc-toolbar .form-select,.spc-field .form-control,.spc-field .form-select{height:45px;border-radius:12px;border:1px solid #d5dde3;background-color:#fff;font-size:14px;}
.spc-toolbar .form-control:focus,.spc-toolbar .form-select:focus,.spc-field .form-control:focus,.spc-field .form-select:focus{border-color:var(--sb);box-shadow:0 0 0 3px rgba(94,141,61,.15);}
.spc-toolbar .spc-btns{display:flex;gap:8px;}
.spc-table-wrap{overflow-x:auto;}
.spc-table{width:100%;margin:0;border-collapse:collapse;}
.spc-table thead th{background:#f8fafc;color:#64748b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:14px 18px;border-bottom:1px solid #eef1f4;white-space:nowrap;}
.spc-table tbody td{padding:14px 18px;font-size:13.5px;color:#334155;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
.spc-table tbody tr:hover{background:#fafcf8;}
.spc-table .text-end{text-align:right;}
.spc-person{display:flex;align-items:center;gap:12px;}
.spc-avatar{width:38px;height:38px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;background:linear-gradient(135deg,#7CA243,#1F5C2E);}
.spc-name{font-weight:700;color:var(--sink);line-height:1.2;}
.spc-sub{font-size:12px;color:var(--smut);}
.spc-code{background:#f1f5f9;padding:4px 10px;border-radius:6px;font-size:12px;color:#475569;font-weight:600;}
.spc-pill{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:30px;font-size:12px;font-weight:700;background:#eef2f6;color:#475569;}
.spc-pill i{width:7px;height:7px;border-radius:50%;background:currentColor;display:inline-block;}
.spc-pill.ok{background:#e6f4ea;color:#1F7A3A;}
.spc-pill.warn{background:#fff4dd;color:#B36B00;}
.spc-pill.off{background:#fde8e8;color:#B42318;}
.spc-pill.info{background:#e3f0fb;color:#1D6FA5;}
.spc-actions{display:flex;align-items:center;gap:14px;white-space:nowrap;}
.spc-actions form{margin:0;}
.spc-link{background:none;border:0;padding:0;font-size:13px;font-weight:700;color:var(--sb);text-decoration:none;cursor:pointer;}
.spc-link:hover{color:var(--sb2);text-decoration:underline;}
.spc-link.danger{color:#B42318;}
.spc-empty{text-align:center;padding:34px 20px;color:var(--smut);}
.spc-empty .ew{width:54px;height:54px;border-radius:16px;background:var(--ssoft);color:var(--sb);display:inline-flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:10px;}
.spc-empty b{display:block;color:var(--sink);}
.spc-foot{padding:14px 20px;}
.spc-alert{border-radius:12px;margin-bottom:16px;}
.spc-grid{display:grid;grid-template-columns:300px 1fr;}
@media(max-width:860px){.spc-grid{grid-template-columns:1fr;}}
.spc-side{position:relative;padding:28px 24px;color:#fff;background:radial-gradient(circle at 85% 8%,rgba(255,255,255,.14),transparent 45%),linear-gradient(160deg,#5E8D3D,#123a28);}
.spc-side .ico{width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:16px;}
.spc-side h3{font-family:var(--font-head,'Kanit',sans-serif);font-size:20px;font-weight:600;margin:0 0 6px;color:#fff;}
.spc-side p{font-size:13px;color:rgba(255,255,255,.75);margin:0 0 18px;}
.spc-steps{display:flex;flex-direction:column;gap:10px;margin-top:18px;}
.spc-step{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;}
.spc-step .num{width:26px;height:26px;border-radius:9px;background:rgba(255,255,255,.18);display:inline-flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;}
.spc-chip{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:30px;background:rgba(255,255,255,.16);font-size:12px;font-weight:700;margin:0 6px 6px 0;}
.spc-body{padding:26px 28px;}
.spc-note{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:12px;background:var(--ssoft);color:var(--sink);font-size:13px;margin-bottom:20px;}
.spc-note i{color:var(--sb);}
.spc-fields{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;}
@media(max-width:640px){.spc-fields{grid-template-columns:1fr;}}
.spc-field{display:flex;flex-direction:column;gap:6px;}
.spc-field label{font-size:12px;font-weight:700;color:var(--sink);margin:0;}
.spc-field label i{color:var(--sb);margin-right:6px;}
.spc-formfoot{display:flex;justify-content:flex-end;gap:10px;margin-top:24px;padding-top:18px;border-top:1px solid var(--sline);}
.spc-view{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;}
@media(max-width:640px){.spc-view{grid-template-columns:1fr;}}
.spc-item{padding:12px 14px;border:1px solid #eef1f4;border-radius:14px;background:#fbfcfb;}
.spc-item span{display:block;font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;}
.spc-item span i{color:var(--sb);margin-right:6px;}
.spc-item b{display:block;color:var(--sink);font-weight:700;}
.spc-item b small{color:var(--smut);font-weight:500;}
.spc-tiles{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:22px;}
@media(max-width:640px){.spc-tiles{grid-template-columns:1fr;}}
.spc-tile{position:relative;overflow:hidden;display:flex;align-items:center;gap:14px;background:linear-gradient(45deg,rgba(203,255,205,.3),transparent 58%),#fff;border:1px solid var(--sline);border-radius:18px;padding:16px 18px;box-shadow:0 5px 18px rgba(15,81,50,.08);transition:transform .2s,box-shadow .2s;}
.spc-tile::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#7CA243,#1F5C2E);opacity:.55;}
.spc-tile:hover{transform:translateY(-4px);box-shadow:0 22px 40px -18px rgba(8,48,31,.4);}
.spc-tile .ti-ico{width:46px;height:46px;border-radius:14px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px;color:#fff;background:linear-gradient(135deg,#7CA243,#1F5C2E);}
.spc-tile .ti-ico.amber{background:linear-gradient(135deg,#F4B942,#C07E08);}
.spc-tile .ti-ico.teal{background:linear-gradient(135deg,#2BB8A8,#0E6B5E);}
.spc-tile .ti-ico.blue{background:linear-gradient(135deg,#4FA3E0,#1D6FA5);}
.spc-tile .ti-ico.red{background:linear-gradient(135deg,#E5675B,#B42318);}
.spc-tile b{display:block;font-family:var(--font-head,'Kanit',sans-serif);font-size:22px;font-weight:600;color:var(--sink);line-height:1.1;}
.spc-tile span{font-size:10.5px;font-weight:700;color:var(--smut);text-transform:uppercase;letter-spacing:.08em;}
.spc-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:22px 20px;}
@media(max-width:991.98px){.spc-stats{grid-template-columns:repeat(2,1fr);}}
@media(max-width:575.98px){.spc-stats{grid-template-columns:1fr;}}
.spc-pool{padding:22px 24px;border-bottom:1px solid var(--sline);}
.spc-pool:last-child{border-bottom:0;}
.spc-pool-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:16px;}
.spc-pool-title{display:inline-flex;align-items:center;gap:10px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:50px;padding:8px 18px;font-weight:800;font-size:13px;color:var(--sink);text-transform:uppercase;}
.spc-pool-title i{color:var(--sb);}
.spc-pool-title em{font-style:normal;font-weight:500;color:#64748b;}
.spc-pool-total{display:flex;align-items:center;gap:14px;background:linear-gradient(135deg,#7CA243,#1F5C2E);color:#fff;border-radius:16px;padding:12px 20px;min-width:240px;justify-content:space-between;}
.spc-pool-total small{display:block;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.75);}
.spc-pool-total b{font-family:var(--font-head,'Kanit',sans-serif);font-size:20px;font-weight:600;}
.spc-pool-total .w{width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;}
.spc-amt{font-weight:800;color:var(--sb2);}
.spc-section-title{padding:16px 24px;border-bottom:1px solid var(--sline);display:flex;align-items:center;gap:10px;font-family:var(--font-head,'Kanit',sans-serif);font-weight:600;color:var(--sink);font-size:16px;background:linear-gradient(45deg,rgba(203,255,205,.25),transparent 60%),#fff;}
.spc-section-title i{color:var(--sb);background:var(--ssoft);width:30px;height:30px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;}

/* create-page overrides: restyle existing bootstrap markup, same fields */
.spc-form .card.border{border:1px solid var(--sline)!important;border-radius:16px!important;box-shadow:0 3px 12px rgba(15,81,50,.05);overflow:hidden;}
.spc-form .card-header.bg-light{background:linear-gradient(45deg,rgba(203,255,205,.25),transparent 60%),#fff!important;border-bottom:1px solid var(--sline);padding:14px 20px;}
.spc-form .card-header h6{display:flex;align-items:center;gap:10px;font-family:var(--font-head,'Kanit',sans-serif);font-size:15px;color:var(--sink);}
.spc-form .card-header h6 .spc-ico{color:var(--sb);background:var(--ssoft);width:30px;height:30px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;}
.spc-form .form-label{font-size:12px;font-weight:700;color:var(--sink);margin-bottom:6px;}
.spc-form .form-control,.spc-form .form-select{min-height:45px;border-radius:12px;border:1px solid #d5dde3;font-size:14px;}
.spc-form textarea.form-control{height:auto;}
.spc-form .form-control:focus,.spc-form .form-select:focus{border-color:var(--sb);box-shadow:0 0 0 3px rgba(94,141,61,.15);}
.spc-form .form-check-input:checked{background-color:var(--sb);border-color:var(--sb);}
.spc-form .btn.buttonSpc{background:linear-gradient(135deg,#7CA243,#1F5C2E);color:#fff;border:0;border-radius:12px;height:45px;font-weight:600;}
.spc-form .btn-outline-secondary{border-radius:12px;height:45px;display:inline-flex;align-items:center;}
.customer-toggle{display:flex;width:420px;max-width:100%;padding:6px;background:var(--ssoft);border:1px solid var(--sline);border-radius:14px;}
.customer-toggle .toggle-btn{flex:1;margin:0;padding:12px 20px;text-align:center;border-radius:10px;cursor:pointer;font-weight:600;color:var(--smut);transition:all .3s ease;}
.customer-toggle .btn-check:checked + .toggle-btn{background:linear-gradient(135deg,#7CA243,#1F5C2E);color:#fff;box-shadow:0 8px 16px -8px rgba(31,92,46,.6);}
#followUpModal .modal-content{border:0;border-radius:18px;overflow:hidden;}
#followUpModal .modal-header{background:linear-gradient(160deg,#5E8D3D,#123a28);}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Crypt;
?>
<div class="spc-wrap">
<div class="spc-heading">
    <div>
        <h2><i class="fa-solid fa-bullseye"></i>Lead Entry</h2>
        <p>Capture customer, visit, status and follow-up details.</p>
    </div>
    <a href="<?php echo e(route('admin.leads.index')); ?>" class="spc-btn spc-btn-secondary">
        <i class="ti ti-list-details"></i>
        View Leads
    </a>
</div>

<div class="card spc-card spc-form w-100 mb-4">

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
                        <span class="spc-ico"><i class="fa-solid fa-magnifying-glass"></i></span>
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
                        <span class="spc-ico"><i class="fa-solid fa-user"></i></span>
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
                        <span class="spc-ico"><i class="fa-solid fa-location-dot"></i></span>
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
                        <span class="spc-ico"><i class="fa-solid fa-signal"></i></span>
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
            <?php if(isset($lead->n_lead_id)): ?>
            <div class="card border rounded-4 mb-4"
                 id="followupCard">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        <span class="spc-ico"><i class="fa-solid fa-bell"></i></span>
                        Follow-up Details
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-4 mb-3">

                            <label class="form-label fw-semibold">
                                Next Follow-up Date
                            </label>

                            <input type="date"
                                   name="next_followup_date"
                                   class="form-control"
                                   value="<?php echo e(old('next_followup_date', $lead->next_followup_date ? \Carbon\Carbon::parse($lead->next_followup_date)->format('Y-m-d') : '')); ?>">

                        </div>

                        <div class="col-lg-4 mb-3">

                            <label class="form-label fw-semibold">
                                Follow-up Time
                            </label>

                            <input type="time"
                            name="next_followup_time"
                            class="form-control"
                            value="<?php echo e(old('next_followup_time', $lead->next_followup_time ? \Carbon\Carbon::parse($lead->next_followup_time)->format('H:i') : '')); ?>">

                        </div>

                        <div class="col-lg-4 mb-3">

                            <label class="form-label fw-semibold">
                                Follow-up Type
                            </label>

                            <select name="followup_type"
                                    class="form-select">

                                <option value="">Select</option>

                                <option value="phone_call" <?php echo e(old('followup_type', $lead->followup_type ?? '') == "phone_call" ? 'selected' : ''); ?>>Phone Call</option>
                                <option value="whats_app" <?php echo e(old('followup_type', $lead->followup_type ?? '') == "whats_app" ? 'selected' : ''); ?>>WhatsApp</option>
                                <option value="farm_visit" <?php echo e(old('followup_type', $lead->followup_type ?? '') == "farm_visit" ? 'selected' : ''); ?>>Farm Visit</option>
                                <option value="office_visit" <?php echo e(old('followup_type', $lead->followup_type ?? '') == "office_visit" ? 'selected' : ''); ?>>Office Visit</option>
                                <option value="video_call" <?php echo e(old('followup_type', $lead->followup_type ?? '') == "video_call" ? 'selected' : ''); ?>>Video Call</option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>
            <?php endif; ?>

            <!-- ============================= -->
            <!-- Priority -->
            <!-- ============================= -->

            <div class="card border rounded-4 mb-4">

                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold">
                        <span class="spc-ico"><i class="fa-solid fa-flag"></i></span>
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
                        <span class="spc-ico"><i class="fa-solid fa-comment-dots"></i></span>
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




</div>
<!-- Follow-up Modal -->
<div class="modal fade" id="followUpModal" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="<?php echo e(route('admin.salesorders.salesUpdateStore')); ?>" method="POST">
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

                    if (selectedDistrict !== null) {

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/leads/create.blade.php ENDPATH**/ ?>