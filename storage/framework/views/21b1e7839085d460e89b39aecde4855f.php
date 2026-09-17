<?php $__env->startSection('content'); ?>
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Account Setting</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none"
                                href="<?php echo e(route('dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Account Setting</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <!-- Profile Update -->
        <!-- <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100 position-relative overflow-hidden">
        <div class="card-body p-4">
          <h5 class="card-title fw-semibold">Personal Details</h5>
          <p class="card-subtitle mb-4">Update your account's profile information and email address.</p>

          <form method="post" action="<?php echo e(route('profile.update')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('patch'); ?>

            <div class="mb-4">
              <label for="name" class="form-label fw-semibold">Your Name</label>
              <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>"
                required autofocus>
              <?php $__errorArgs = ['name'];
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

            <div class="mb-4">
              <label for="email" class="form-label fw-semibold">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>"
                required>
              <?php $__errorArgs = ['email'];
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

            <div class="d-flex align-items-center gap-3">
              <button type="submit" class="btn btn-primary">Save</button>
              <?php if(session('status') === 'profile-updated'): ?>
                <p class="mb-0 text-success fw-semibold">Saved.</p>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div> -->

        <!-- Password Update -->
        <!-- <div class="col-lg-6 d-flex align-items-stretch"> -->
        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold">Change Password</h5>
                <p class="card-subtitle mb-4">Ensure your account is using a long, random password to stay secure.</p>

                <form method="post" action="<?php echo e(route('password.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 password-input" id="current_password"
                                name="current_password">

                            <span class="toggle-password">
                                <i class="ti ti-eye"></i>
                            </span>
                        </div>

                        <?php $__errorArgs = ['current_password', 'updatePassword'];
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

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 password-input" id="password"
                                name="password">

                            <span class="toggle-password">
                                <i class="ti ti-eye"></i>
                            </span>
                        </div>

                        <?php $__errorArgs = ['password', 'updatePassword'];
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

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 password-input" id="password_confirmation"
                                name="password_confirmation">

                            <span class="toggle-password">
                                <i class="ti ti-eye"></i>
                            </span>
                        </div>

                        <?php $__errorArgs = ['password_confirmation', 'updatePassword'];
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
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <?php if(session('status') === 'password-updated'): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <strong>Success!</strong> Your password has been updated successfully. Please log out and
                            log in again using your new password.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.toggle-password').forEach(function(toggle) {

        toggle.addEventListener('click', function() {

            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }

        });

    });

});
</script>
<style>
.toggle-password {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    z-index: 10;
}

.toggle-password:hover {
    color: #0d6efd;
}

.password-input {
    padding-right: 45px;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/profile/edit.blade.php ENDPATH**/ ?>