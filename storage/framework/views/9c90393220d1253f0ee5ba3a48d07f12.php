<?php $__env->startSection('content'); ?>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-shield text-primary me-2"></i>
                Edit Role
            </h4>

            <small class="text-muted">
                Update role details, assigned menus and permissions.
            </small>
        </div>

        <a href="<?php echo e(route('admin.roles.index')); ?>" class="btn btn-outline-secondary rounded-pill">

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>

    <div class="card-body">

        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.roles.update',$role->id)); ?>">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Role Name <span class="text-danger">*</span>

                </label>

                <input type="text" name="name" class="form-control" value="<?php echo e(old('name',$role->name)); ?>"
                    placeholder="Enter Role Name">

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

            <hr>

            

            <?php
            $assignedMenus = $role->menus->pluck('id')->toArray();
            ?>

            <!-- <h5 class="section-title">

                <i class="fas fa-bars text-primary me-2"></i>

                Assign Menus

            </h5> -->
            <?php
            $assignedMenus = $role->menus->pluck('id')->toArray();
            ?>

            <div class="accordion mb-4" id="menuAccordion">

                <div class="accordion-item role-card">

                    <h2 class="accordion-header">

                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#menusCollapse">

                            <i class="fas fa-bars text-primary me-2"></i>

                            <strong>Assign Menus</strong>

                        </button>

                    </h2>

                    <div id="menusCollapse" class="accordion-collapse collapse show">

                        <div class="accordion-body">

                            <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="accordion mb-3" id="parentMenu<?php echo e($parent->id); ?>">

                                <div class="accordion-item">

                                    <h2 class="accordion-header">

                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#menu<?php echo e($parent->id); ?>">

                                            <i class="fas fa-folder text-primary me-2"></i>

                                            <?php echo e($parent->name); ?>


                                        </button>

                                    </h2>

                                    <div id="menu<?php echo e($parent->id); ?>" class="accordion-collapse collapse">

                                        <div class="accordion-body">

                                            <div class="row">

                                                <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <div class="col-md-4 mb-2">

                                                    <div class="form-check custom-check">

                                                        <input class="form-check-input" type="checkbox" name="menus[]"
                                                            value="<?php echo e($menu->id); ?>" id="menuCheck<?php echo e($menu->id); ?>"
                                                            <?php echo e(in_array($menu->id,$assignedMenus) ? 'checked' : ''); ?>>

                                                        <label class="form-check-label" for="menuCheck<?php echo e($menu->id); ?>">

                                                            <?php echo e($menu->name); ?>


                                                        </label>

                                                    </div>

                                                </div>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    </div>

                </div>

            </div>

            <hr>

            

            <?php
            $assignedPermissions = $role->permissions->pluck('name')->toArray();
            ?>

            <?php
            $assignedPermissions = $role->permissions->pluck('name')->toArray();
            ?>

            <div class="accordion mb-4" id="permissionAccordion">

                <div class="accordion-item role-card">

                    <h2 class="accordion-header">

                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#permissionCollapse">

                            <i class="fas fa-lock text-primary me-2"></i>

                            <strong>Assign Permissions</strong>

                        </button>

                    </h2>

                    <div id="permissionCollapse" class="accordion-collapse collapse">

                        <div class="accordion-body">

                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="accordion mb-3">

                                <div class="accordion-item">

                                    <h2 class="accordion-header">

                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#module<?php echo e(Str::slug($module)); ?>">

                                            <i class="fas fa-folder-open text-primary me-2"></i>

                                            <?php echo e(ucwords(str_replace(['-','_'],' ',$module))); ?>


                                        </button>

                                    </h2>

                                    <div id="module<?php echo e(Str::slug($module)); ?>" class="accordion-collapse collapse">

                                        <div class="accordion-body">

                                            <div class="row">

                                                <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <?php
                                                $action = ucfirst(last(explode('.', $permission->name)));
                                                ?>

                                                <div class="col-md-3 mb-2">

                                                    <div class="form-check custom-check">

                                                        <input class="form-check-input" type="checkbox"
                                                            name="permissions[]" value="<?php echo e($permission->name); ?>"
                                                            id="permission<?php echo e($permission->id); ?>"
                                                            <?php echo e(in_array($permission->name,$assignedPermissions) ? 'checked' : ''); ?>>

                                                        <label class="form-check-label"
                                                            for="permission<?php echo e($permission->id); ?>">

                                                            <?php echo e($action); ?>


                                                        </label>

                                                    </div>

                                                </div>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    </div>

                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="<?php echo e(route('admin.roles.index')); ?>" class="btn btn-light me-2">

                    Cancel

                </a>

                <button class="btn btn-primary px-4">

                    <i class="fas fa-save me-1"></i>

                    Update Role

                </button>

            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>

<style>
/* ==========================
   Card
========================== */

.card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 .125rem .5rem rgba(0, 0, 0, .05);
}

.card-header {
    padding: 20px 24px;
    background: #fff;
    border-bottom: 1px solid #e9ecef;
}

.card-body {
    padding: 24px;
}


/* ==========================
   Section Title
========================== */

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2b3c56;
    margin-bottom: 18px;
}


/* ==========================
   Form
========================== */

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control {

    height: 46px;
    border-radius: 8px;
    border: 1px solid #d9dee3;
}

.form-control:focus {

    border-color: #696cff;
    box-shadow: 0 0 0 .15rem rgba(105, 108, 255, .15);
}


/* ==========================
   Accordion
========================== */

.accordion-item {

    border: 1px solid #e9ecef;
    border-radius: 10px !important;
    overflow: hidden;
    margin-bottom: 16px;
    transition: .25s;
}

.accordion-item:hover {

    box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
}

.accordion-button {

    background: #fff;
    font-weight: 600;
    color: #2b3c56;
    padding: 18px 20px;
}

.accordion-button:not(.collapsed) {

    background: #eef2ff;
    color: #696cff;
}

.accordion-button:focus {

    box-shadow: none;
}

.accordion-body {

    background: #fff;
}


/* ==========================
   Parent Cards
========================== */

.role-card {

    border: 1px solid #eceef1;
    border-radius: 10px;
    overflow: hidden;
}

.role-card-header {

    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
}


/* ==========================
   Checkbox
========================== */

.custom-check {

    padding: 8px 0;
}

.custom-check .form-check-input {

    width: 18px;
    height: 18px;
    cursor: pointer;
}

.custom-check .form-check-input:checked {

    background-color: #696cff;
    border-color: #696cff;
}

.custom-check .form-check-label {

    margin-left: 8px;
    font-weight: 500;
    cursor: pointer;
    color: #495057;
}


/* ==========================
   Buttons
========================== */

.btn {

    border-radius: 8px;
    font-weight: 500;
}

.btn-primary {

    background: #696cff;
    border-color: #696cff;
    min-width: 160px;
}

.btn-primary:hover {

    background: #5f61e6;
    border-color: #5f61e6;
}

.btn-outline-secondary {

    border-radius: 25px;
}

.btn-light {

    border: 1px solid #dee2e6;
    min-width: 120px;
}


/* ==========================
   Badge
========================== */

.badge-count {

    background: #696cff;
    color: #fff;
    border-radius: 20px;
    font-size: 12px;
    padding: 5px 10px;
}


/* ==========================
   Alert
========================== */

.alert {

    border-radius: 10px;
}


/* ==========================
   Divider
========================== */

hr {

    margin: 28px 0;
    opacity: .15;
}


/* ==========================
   Hover Effect
========================== */

.form-check {

    padding: 10px;
    border-radius: 8px;
    transition: .2s;
}

.form-check:hover {

    background: #f8f9fa;
}


/* ==========================
   Scrollable Accordion
========================== */

.permission-scroll {

    max-height: 420px;
    overflow-y: auto;
    padding-right: 8px;
}

.permission-scroll::-webkit-scrollbar {

    width: 7px;
}

.permission-scroll::-webkit-scrollbar-thumb {

    background: #d6d6d6;
    border-radius: 20px;
}


/* ==========================
   Footer
========================== */

.form-footer {

    position: sticky;
    bottom: 0;
    background: #fff;
    padding-top: 18px;
}


/* ==========================
   Responsive
========================== */

@media(max-width:768px) {

    .card-header {

        flex-direction: column;
        align-items: flex-start !important;
    }

    .card-header a {

        margin-top: 15px;
    }

    .btn-primary {

        width: 100%;
    }

    .btn-light {

        width: 100%;
        margin-bottom: 10px;
    }

}
</style>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/roles/edit.blade.php ENDPATH**/ ?>