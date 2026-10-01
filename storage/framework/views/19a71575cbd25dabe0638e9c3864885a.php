<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'System',
        'heroIcon' => 'fa-solid fa-shield-halved',
        'heroSummary' => 'Manage access, roles and the organization-wide audit trail.',
        'heroStats' => [
            ['label' => 'Users', 'icon' => 'fa-solid fa-user-gear', 'value' => $totalUsers],
            ['label' => 'Active', 'icon' => 'fa-solid fa-user-check', 'value' => $activeUsersCount],
            ['label' => 'Audit entries', 'icon' => 'fa-solid fa-timeline', 'value' => $totalAuditEntries],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">

        
        <div class="table-card">
            <div class="tc-head">
                <h3>
                    <span class="wh-ico">
                        <i class="fa-solid fa-users-gear"></i>
                    </span>
                    Users
                </h3>

                <span class="pill pill-muted">
                    <?php echo e($totalUsers); ?> accounts
                </span>
            </div>

            <div class="tc-body">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="cell-emp">
                                        <div class="av">
                                            <?php echo e(strtoupper(substr($u->name, 0, 1))); ?>

                                        </div>

                                        <div>
                                            <b><?php echo e($u->name); ?></b>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php echo e($u->email); ?>

                                </td>

                                <td>
                                    <?php echo e($u->roleLabel()); ?>

                                </td>

                                <td>
                                    <span class="pill <?php echo e($u->is_active ? 'pill-ok' : 'pill-bad'); ?>">
                                        <?php echo e($u->is_active ? 'Active' : 'Suspended'); ?>

                                    </span>
                                </td>

                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hr-system.edit')): ?>
                                        <a
                                            href="<?php echo e(request()->fullUrlWithQuery(['user' => $u->id])); ?>"
                                            class="btn-ghost"
                                        >
                                            <i
                                                class="fa-solid fa-pen"
                                                style="font-size:11px;"
                                            ></i>
                                            Edit
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-widget">
                                        <div class="ew-ico">
                                            <i class="fa-solid fa-users"></i>
                                        </div>

                                        <b>No users found</b>
                                        <span>
                                            There are no user accounts to display.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php echo e($users->links()); ?>

            </div>
        </div>


        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hr-system.create')): ?>
            <?php if(!$editingUser): ?>
                <div class="card" style="margin-top:20px;">
                    <div class="widget-head">
                        <div class="wh-ico">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>

                        <div>
                            <h3>Add user</h3>
                            <p>
                                Creates a portal account with the chosen role.
                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="<?php echo e(route('hr.system.user.store')); ?>"
                    >
                        <?php echo csrf_field(); ?>

                        <div class="field-grid">

                            <div class="field">
                                <label>Full name</label>

                                <input
                                    type="text"
                                    name="name"
                                    value="<?php echo e(old('name')); ?>"
                                    required
                                >

                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger">
                                        <?php echo e($message); ?>

                                    </small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                            <div class="field">
                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    value="<?php echo e(old('email')); ?>"
                                    required
                                >

                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger">
                                        <?php echo e($message); ?>

                                    </small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                            <div class="field">
                                <label>Role</label>

                                <select name="role" required>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option
                                            value="<?php echo e($key); ?>"
                                            <?php if(old('role') === $key): echo 'selected'; endif; ?>
                                        >
                                            <?php echo e($data['label']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger">
                                        <?php echo e($message); ?>

                                    </small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button
                                type="submit"
                                class="btn-primary"
                            >
                                <i class="fa-solid fa-user-plus"></i>
                                Create user
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hr-system.edit')): ?>
            <?php if($editingUser): ?>
                <div class="card" style="margin-top:20px;">
                    <div class="widget-head">
                        <div class="wh-ico">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>

                        <div>
                            <h3>Edit user</h3>

                            <p>
                                Updating <?php echo e($editingUser->name); ?>

                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="<?php echo e(route('hr.system.user.update', $editingUser)); ?>"
                    >
                        <?php echo csrf_field(); ?>

                        <div class="field-grid">

                            <div class="field">
                                <label>Full name</label>

                                <input
                                    type="text"
                                    name="name"
                                    value="<?php echo e(old('name', $editingUser->name)); ?>"
                                    required
                                >

                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger">
                                        <?php echo e($message); ?>

                                    </small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                            <div class="field">
                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    value="<?php echo e(old('email', $editingUser->email)); ?>"
                                    required
                                >

                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger">
                                        <?php echo e($message); ?>

                                    </small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                            <div class="field">
                                <label>Role</label>

                                <select name="role" required>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option
                                            value="<?php echo e($key); ?>"
                                            <?php if(
                                                old(
                                                    'role',
                                                    $editingUser->role
                                                ) === $key
                                            ): echo 'selected'; endif; ?>
                                        >
                                            <?php echo e($data['label']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger">
                                        <?php echo e($message); ?>

                                    </small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                            <div
                                class="field"
                                style="flex-direction:row;align-items:center;gap:8px;"
                            >
                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    style="width:auto;"
                                    <?php if(
                                        old(
                                            'is_active',
                                            $editingUser->is_active
                                        )
                                    ): echo 'checked'; endif; ?>
                                >

                                <label style="margin:0;">
                                    Active
                                </label>
                            </div>

                        </div>

                        <div class="form-actions">

                            <button
                                type="submit"
                                class="btn-primary"
                            >
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save user
                            </button>

                            <a
                                href="<?php echo e(route('hr.system.index')); ?>"
                                class="btn-secondary"
                            >
                                Cancel
                            </a>

                        </div>
                    </form>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        
        <div
            class="section-head"
            style="margin-top:30px;"
        >
            <h2>
                <i class="fa-solid fa-timeline"></i>
                Audit log
            </h2>

            <span class="hint">
                Every privileged action, newest first
            </span>
        </div>


        <div class="table-card">
            <div class="tc-body">

                <?php if($auditLog->isEmpty()): ?>

                    <div class="empty-widget">
                        <div class="ew-ico">
                            <i class="fa-solid fa-timeline"></i>
                        </div>

                        <b>No audit entries yet</b>

                        <span>
                            Privileged actions will be recorded here.
                        </span>
                    </div>

                <?php else: ?>

                    <table>
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $auditLog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>

                                    <td>
                                        <?php echo e(\Illuminate\Support\Carbon::parse($log->created_at)->format('d M, H:i')); ?>

                                    </td>

                                    <td>
                                        <div class="cell-emp">

                                            <div class="av">
                                                <?php echo e(strtoupper(
                                                    substr(
                                                        $log->user->name ?? 'S',
                                                        0,
                                                        1
                                                    )
                                                )); ?>

                                            </div>

                                            <div>
                                                <b>
                                                    <?php echo e($log->user->name ?? 'System'); ?>

                                                </b>
                                            </div>

                                        </div>
                                    </td>

                                    <td>
                                        <span class="pill pill-muted">
                                            <?php echo e($log->action); ?>

                                        </span>
                                    </td>

                                    <td>
                                        <?php echo e(ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $log->module
                                            )
                                        )); ?>

                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <?php echo e($auditLog->links()); ?>


                <?php endif; ?>

            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc_new\resources\views/hr/modules/system.blade.php ENDPATH**/ ?>