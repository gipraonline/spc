<?php $__env->startSection('content'); ?>
<style>
.action-btn {
    width: 60px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}
</style>
<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">User Management</h5>

        <div class="d-flex gap-2">

            <?php if($hasActivePasswords): ?>
            <button type="button" id="copyAllLoginDetailsBtn" class="btn buttonSpc"
                data-url="<?php echo e(route('admin.users.copy-all-login-details')); ?>">
                📋 Copy All Login Details
            </button>
            <?php else: ?>
            <button type="button" class="btn buttonSpc" disabled>
                🔒 No Active Passwords
            </button>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-management.create')): ?>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn buttonSpc">
                + Create User
            </a>
            <?php endif; ?>

        </div>

    </div>


    <div class="card-body">

        <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>


            <?php if(session('password')): ?>
            <hr class="my-2">
            <strong>Temporary Password:</strong>
            <span class="text-danger"><?php echo e(session('password')); ?></span>
            <br>
            <small class="text-muted">
                Please share this password securely with the user.
            </small>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th width="80">#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-management.edit', 'user-management.delete'])): ?>
                    <th width="180">Action</th>
                    <?php endif; ?>
                    <th>Password</th>
                </tr>
            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <tr>

                    <td><?php echo e($users->firstItem() + $loop->index); ?></td>

                    <td><?php echo e($user->c_name); ?></td>

                    <td><?php echo e($user->c_username); ?></td>

                    <td><?php echo e($user->roles->pluck('name')->implode(', ')); ?></td>
                    <td>
                        <?php if(
                        $user->initial_password &&
                        $user->initial_password_expires_at &&
                        now()->lessThanOrEqualTo($user->initial_password_expires_at)
                        ): ?>
                        <button type="button" class="btn btn-sm btn-outline-primary show-password-btn"
                            data-url="<?php echo e(route('admin.users.show-password', $user)); ?>">
                            👁 Show Password
                        </button>
                        <?php else: ?>
                        <span class="text-muted">
                            🔒 Expired
                        </span>
                        <?php endif; ?>
                    </td>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-management.edit', 'user-management.delete'])): ?>
                    <td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-management.edit')): ?>
                        <a href="<?php echo e(route('admin.users.edit', $user->n_role_id)); ?>"
                            class="btn btn-primary btn-sm action-btn">
                            Edit
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-management.delete')): ?>
                        <form action="<?php echo e(route('admin.users.destroy', $user->n_role_id)); ?>" method="POST"
                            class="d-inline">

                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>

                            <button type="submit" class="btn btn-danger btn-sm action-btn"
                                onclick="return confirm('Delete this user?')">
                                Delete
                            </button>

                        </form>
                        <?php endif; ?>

                    </td>
                    <?php endif; ?>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <tr>
                    <td colspan="5" class="text-center">
                        No Users Found.
                    </td>
                </tr>

                <?php endif; ?>

            </tbody>

        </table>
        <div class="d-flex justify-content-center mt-3">
            <?php echo e($users->links()); ?>

        </div>
    </div>

</div>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    User Login Password
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    This password can only be viewed temporarily.
                    Do not share it with unauthorized persons.
                </div>

                <label class="form-label">
                    Password
                </label>

                <div class="input-group">
                    <input type="text" id="displayPassword" class="form-control" readonly>

                    <button type="button" class="btn btn-outline-secondary" id="copyPasswordBtn">
                        Copy
                    </button>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        Available until:
                    </small>

                    <strong id="passwordExpiry"></strong>
                </div>

            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const modalElement = document.getElementById('passwordModal');
    const passwordModal = new bootstrap.Modal(modalElement);

    const passwordInput = document.getElementById('displayPassword');
    const expiryElement = document.getElementById('passwordExpiry');
    const copyButton = document.getElementById('copyPasswordBtn');

    // =====================================================
    // SHOW INDIVIDUAL PASSWORD
    // =====================================================

    document.querySelectorAll('.show-password-btn').forEach(function(button) {

        button.addEventListener('click', function() {

            const url = this.dataset.url;

            passwordInput.value = '';
            expiryElement.textContent = '';

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message || 'Unable to retrieve password.'
                        );
                    }

                    return data;
                })
                .then(data => {

                    if (data.success) {

                        passwordInput.value = data.password;
                        expiryElement.textContent = data.expires_at;

                        passwordModal.show();

                    } else {

                        alert(
                            data.message ||
                            'Unable to retrieve password.'
                        );
                    }
                })
                .catch(error => {

                    console.error(error);

                    alert(
                        error.message ||
                        'Something went wrong while retrieving the password.'
                    );
                });
        });
    });


    // =====================================================
    // COPY INDIVIDUAL PASSWORD
    // =====================================================

    copyButton.addEventListener('click', function() {

        const password = passwordInput.value;

        if (!password) {
            return;
        }

        navigator.clipboard.writeText(password)
            .then(function() {

                copyButton.textContent = 'Copied!';

                setTimeout(function() {
                    copyButton.textContent = 'Copy';
                }, 1500);

            })
            .catch(function() {

                alert('Unable to copy password.');

            });
    });


    // =====================================================
    // COPY ALL LOGIN DETAILS
    // =====================================================

    const copyAllButton =
        document.getElementById('copyAllLoginDetailsBtn');

    if (copyAllButton) {

        copyAllButton.addEventListener('click', function() {

            const url = this.dataset.url;

            if (!url) {
                alert('Copy URL is missing.');
                return;
            }

            const originalText = copyAllButton.innerHTML;

            copyAllButton.disabled = true;
            copyAllButton.innerHTML = 'Preparing...';

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message ||
                            'Unable to retrieve login details.'
                        );
                    }

                    return data;
                })
                .then(data => {

                    if (!data.success || !data.details) {
                        throw new Error(
                            data.message ||
                            'No active login details available.'
                        );
                    }

                    return navigator.clipboard.writeText(data.details);
                })
                .then(() => {

                    copyAllButton.innerHTML = '✓ Copied!';

                    setTimeout(() => {

                        copyAllButton.innerHTML = originalText;
                        copyAllButton.disabled = false;

                    }, 2000);

                })
                .catch(error => {

                    console.error(
                        'Copy login details error:',
                        error
                    );

                    alert(
                        error.message ||
                        'Unable to copy login details.'
                    );

                    copyAllButton.innerHTML = originalText;
                    copyAllButton.disabled = false;
                });
        });
    }


    // =====================================================
    // CLEAR PASSWORD WHEN MODAL CLOSES
    // =====================================================

    modalElement.addEventListener('hidden.bs.modal', function() {

        passwordInput.value = '';
        expiryElement.textContent = '';

    });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/admin/users/index.blade.php ENDPATH**/ ?>