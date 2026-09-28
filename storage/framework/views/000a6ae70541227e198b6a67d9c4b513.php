<?php $__env->startSection('title', $module['title']); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Records',
        'heroIcon' => 'fa-regular fa-building',
        'heroSummary' => 'Departments, designations and the company holiday calendar.',
        'heroStats' => [
            ['label' => 'Departments', 'icon' => 'fa-solid fa-sitemap', 'value' => $departments->count()],
            ['label' => 'Designations', 'icon' => 'fa-solid fa-briefcase', 'value' => $designations->count()],
            ['label' => 'Holidays', 'icon' => 'fa-regular fa-calendar', 'value' => $holidays->count()],
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content">

        
        <div class="tabs">
            <button
                type="button"
                class="tab active"
                data-tab="departments"
                onclick="hrTab(this, 'departments')"
            >
                Departments
            </button>

            <button
                type="button"
                class="tab"
                data-tab="designations"
                onclick="hrTab(this, 'designations')"
            >
                Designations
            </button>

            <button
                type="button"
                class="tab"
                data-tab="holidays"
                onclick="hrTab(this, 'holidays')"
            >
                Holiday calendar
            </button>
        </div>


        
        <div
            class="tabpanel active"
            data-tabpanel="departments"
        >
            <div class="grid-2">

                
                <div class="table-card">

                    <div class="tc-head">
                        <h3>
                            <span class="wh-ico">
                                <i class="fa-solid fa-sitemap"></i>
                            </span>
                            Departments
                        </h3>
                    </div>

                    <div class="tc-body">

                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Employees</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.edit')): ?>

                                                <form
                                                    method="POST"
                                                    action="<?php echo e(route('hr.organization.department.update', $d)); ?>"
                                                    style="display:flex;gap:6px;"
                                                >
                                                    <?php echo csrf_field(); ?>

                                                    <input
                                                        name="name"
                                                        value="<?php echo e($d->name); ?>"
                                                        style="padding:5px 8px;font-size:12.5px;"
                                                    >

                                                    <input
                                                        name="code"
                                                        value="<?php echo e($d->code); ?>"
                                                        style="padding:5px 8px;font-size:12.5px;width:70px;"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn-ghost"
                                                        style="padding:0;"
                                                    >
                                                        Save
                                                    </button>
                                                </form>

                                            <?php else: ?>

                                                <b><?php echo e($d->name); ?></b>

                                            <?php endif; ?>

                                        </td>

                                        <td>
                                            <?php echo e($d->code); ?>

                                        </td>

                                        <td>
                                            <?php echo e($d->employees_count); ?>

                                        </td>
                                    </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-widget">
                                                <div class="ew-ico">
                                                    <i class="fa-solid fa-sitemap"></i>
                                                </div>

                                                <b>No departments</b>

                                                <span>
                                                    No departments have been created yet.
                                                </span>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>


                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.create')): ?>

                    <div class="card">

                        <div class="widget-head">
                            <div class="wh-ico">
                                <i class="fa-solid fa-plus"></i>
                            </div>

                            <div>
                                <h3>Add department</h3>
                                <p>Create a new department for the org.</p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="<?php echo e(route('hr.organization.department.store')); ?>"
                        >
                            <?php echo csrf_field(); ?>

                            <div class="field-grid">

                                <div class="field">
                                    <label>Name</label>

                                    <input
                                        name="name"
                                        value="<?php echo e(old('name')); ?>"
                                        placeholder="e.g. Finance"
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
                                    <label>Code</label>

                                    <input
                                        name="code"
                                        value="<?php echo e(old('code')); ?>"
                                        placeholder="e.g. FIN"
                                        required
                                    >

                                    <?php $__errorArgs = ['code'];
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
                                    <i class="fa-solid fa-plus"></i>
                                    Add department
                                </button>
                            </div>

                        </form>
                    </div>

                <?php endif; ?>

            </div>
        </div>


        
        <div
            class="tabpanel"
            data-tabpanel="designations"
        >
            <div class="grid-2">

                
                <div class="table-card">

                    <div class="tc-head">
                        <h3>
                            <span class="wh-ico">
                                <i class="fa-solid fa-briefcase"></i>
                            </span>
                            Designations
                        </h3>
                    </div>

                    <div class="tc-body">

                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Employees</th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.delete')): ?><th></th><?php endif; ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                    <tr>
                                        <td>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.edit')): ?>

                                                <form
                                                    method="POST"
                                                    action="<?php echo e(route('hr.organization.designation.update', $d)); ?>"
                                                    style="display:flex;gap:6px;"
                                                >
                                                    <?php echo csrf_field(); ?>

                                                    <input
                                                        name="title"
                                                        value="<?php echo e($d->title); ?>"
                                                        style="padding:5px 8px;font-size:12.5px;"
                                                    >

                                                    <select
                                                        name="department_id"
                                                        style="padding:5px 8px;font-size:12.5px;"
                                                    >
                                                        <option value="">
                                                            &mdash;
                                                        </option>

                                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option
                                                                value="<?php echo e($dep->id); ?>"
                                                                <?php if($d->department_id === $dep->id): echo 'selected'; endif; ?>
                                                            >
                                                                <?php echo e($dep->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>

                                                    <button
                                                        type="submit"
                                                        class="btn-ghost"
                                                        style="padding:0;"
                                                    >
                                                        Save
                                                    </button>

                                                </form>

                                            <?php else: ?>

                                                <b><?php echo e($d->title); ?></b>

                                            <?php endif; ?>

                                        </td>

                                        <td>
                                            <?php echo e($d->department->name ?? '—'); ?>

                                        </td>

                                        <td>
                                            <?php echo e($d->employees_count); ?>

                                        </td>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.delete')): ?>
                                            <td>
                                                <form
                                                    method="POST"
                                                    action="<?php echo e(route('hr.organization.designation.destroy', $d)); ?>"
                                                    onsubmit="return confirm('Delete this designation from HR and SPC?')"
                                                >
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn-ghost" style="padding:0;color:#c0392b;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-widget">

                                                <div class="ew-ico">
                                                    <i class="fa-solid fa-briefcase"></i>
                                                </div>

                                                <b>No designations</b>

                                                <span>
                                                    No designations have been created yet.
                                                </span>

                                            </div>
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>


                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.create')): ?>

                    <div class="card">

                        <div class="widget-head">
                            <div class="wh-ico">
                                <i class="fa-solid fa-plus"></i>
                            </div>

                            <div>
                                <h3>Add designation</h3>
                                <p>Create a new job title.</p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="<?php echo e(route('hr.organization.designation.store')); ?>"
                        >
                            <?php echo csrf_field(); ?>

                            <div class="field-grid">

                                <div class="field">
                                    <label>Title</label>

                                    <input
                                        name="title"
                                        value="<?php echo e(old('title')); ?>"
                                        placeholder="e.g. Finance Manager"
                                        required
                                    >

                                    <?php $__errorArgs = ['title'];
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
                                    <label>Department</label>

                                    <select name="department_id">
                                        <option value="">
                                            &mdash;
                                        </option>

                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($dep->id); ?>"
                                                <?php if(old('department_id') == $dep->id): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($dep->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php $__errorArgs = ['department_id'];
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
                                    <label>Reports to</label>

                                    <select name="parent_designation_id">
                                        <option value="">&mdash; None (top level) &mdash;</option>

                                        <?php $__currentLoopData = $parentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($opt->n_designation_id); ?>"
                                                <?php if(old('parent_designation_id') == $opt->n_designation_id): echo 'selected'; endif; ?>
                                            >
                                                <?php echo e($opt->c_designation); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php $__errorArgs = ['parent_designation_id'];
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
                                    <i class="fa-solid fa-plus"></i>
                                    Add designation
                                </button>
                            </div>

                        </form>
                    </div>

                <?php endif; ?>

            </div>
        </div>


        
        <div
            class="tabpanel"
            data-tabpanel="holidays"
        >
            <div class="grid-2">

                
                <div class="table-card">

                    <div class="tc-head">
                        <h3>
                            <span class="wh-ico">
                                <i class="fa-regular fa-calendar"></i>
                            </span>
                            Holiday calendar
                        </h3>
                    </div>

                    <div class="tc-body">

                        <table>
                            <thead>
                                <tr>
                                    <th>Holiday</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $holidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                    <tr>

                                        <td>
                                            <?php echo e($h->name); ?>

                                        </td>

                                        <td>
                                            <?php echo e(\Illuminate\Support\Carbon::parse($h->holiday_date)->format('d M Y (D)')); ?>

                                        </td>

                                        <td>
                                            <span class="pill <?php echo e($h->is_optional ? 'pill-muted' : 'pill-ok'); ?>">
                                                <?php echo e($h->is_optional ? 'Optional' : 'Mandatory'); ?>

                                            </span>
                                        </td>

                                        <td>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.delete')): ?>

                                                <form
                                                    method="POST"
                                                    action="<?php echo e(route('hr.organization.holiday.destroy', $h)); ?>"
                                                    onsubmit="return confirm('Remove this holiday?');"
                                                >
                                                    <?php echo csrf_field(); ?>

                                                    <button
                                                        type="submit"
                                                        class="btn-ghost"
                                                    >
                                                        <i class="fa-solid fa-trash"></i>
                                                        Remove
                                                    </button>
                                                </form>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-widget">

                                                <div class="ew-ico">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </div>

                                                <b>No holidays</b>

                                                <span>
                                                    No holidays have been added yet.
                                                </span>

                                            </div>
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>


                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organization.create')): ?>

                    <div class="card">

                        <div class="widget-head">
                            <div class="wh-ico">
                                <i class="fa-regular fa-calendar-plus"></i>
                            </div>

                            <div>
                                <h3>Add holiday</h3>
                                <p>
                                    Feeds dashboards and WFH/leave context.
                                </p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="<?php echo e(route('hr.organization.holiday.store')); ?>"
                        >
                            <?php echo csrf_field(); ?>

                            <div class="field-grid">

                                <div class="field full">
                                    <label>Name</label>

                                    <input
                                        name="name"
                                        value="<?php echo e(old('name')); ?>"
                                        placeholder="e.g. Diwali"
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
                                    <label>Date</label>

                                    <input
                                        type="date"
                                        name="holiday_date"
                                        value="<?php echo e(old('holiday_date')); ?>"
                                        required
                                    >

                                    <?php $__errorArgs = ['holiday_date'];
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
                                    style="flex-direction:row;align-items:center;gap:8px;margin-top:22px;"
                                >
                                    <input
                                        type="checkbox"
                                        name="is_optional"
                                        value="1"
                                        style="width:auto;"
                                        <?php if(old('is_optional')): echo 'checked'; endif; ?>
                                    >

                                    <label style="margin:0;">
                                        Optional holiday
                                    </label>
                                </div>

                            </div>

                            <div class="form-actions">
                                <button
                                    type="submit"
                                    class="btn-primary"
                                >
                                    <i class="fa-regular fa-calendar-plus"></i>
                                    Add holiday
                                </button>
                            </div>

                        </form>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </div>


    
    <script>
        function hrTab(btn, name) {
            document
                .querySelectorAll('.tabs .tab')
                .forEach(t => t.classList.remove('active'));

            document
                .querySelectorAll('.content > .tabpanel')
                .forEach(p => p.classList.remove('active'));

            btn.classList.add('active');

            document
                .querySelector('.content > [data-tabpanel="' + name + '"]')
                .classList.add('active');
        }
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/hr/modules/organization.blade.php ENDPATH**/ ?>