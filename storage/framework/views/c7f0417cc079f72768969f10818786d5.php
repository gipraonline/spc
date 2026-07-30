<?php $__env->startSection('content'); ?>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h3 class="mb-1 fw-bold">
                <i class="fas fa-lock text-primary me-2"></i>
                Permission Management
            </h3>

            <small class="text-muted">
                Permissions grouped by module
            </small>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission-management.create')): ?>
        <a href="<?php echo e(route('admin.permissions.create')); ?>" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-1"></i>
            Create Permission
        </a>
        <?php endif; ?>

    </div>


    <div class="card-body">

        <?php if(session('success')): ?>

        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>

        <?php endif; ?>


        <div class="accordion" id="permissionAccordion">

            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="accordion-item border rounded mb-3">

                <h2 class="accordion-header" id="heading<?php echo e($loop->index); ?>">


                    <button class="accordion-button <?php echo e(!$loop->first ? 'collapsed' : ''); ?>" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($loop->index); ?>">

                        <div class="d-flex justify-content-between align-items-center w-100">

                            <!-- Left -->
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder-open text-primary me-2"></i>
                                <strong><?php echo e($module); ?></strong>
                            </div>

                            <!-- Right -->
                            <div class="d-flex align-items-center">

                                <span class="badge bg-primary rounded-pill me-3">
                                    <?php echo e($modulePermissions->count()); ?>

                                </span>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission-management.create')): ?>

                                <a href="<?php echo e(route('admin.permissions.create', ['module' => strtolower($module)])); ?>"
                                    class="btn btn-success btn-sm rounded-circle" title=" Add Permission"
                                    onclick="event.stopPropagation();">

                                    <i class="fas fa-plus"></i>

                                </a>
                                <?php endif; ?>

                            </div>

                        </div>

                    </button>

                </h2>


                <div id="collapse<?php echo e($loop->index); ?>" class="accordion-collapse collapse <?php echo e($loop->first ? 'show' : ''); ?>"
                    data-bs-parent="#permissionAccordion">

                    <div class="accordion-body p-0">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">#</th>

                                    <th>Permission</th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['permission-management.edit', 'permission-management.delete'])): ?>
                                    <th width="180" class="text-center">
                                        Actions
                                    </th>
                                    <?php endif; ?>

                                </tr>

                            </thead>

                            <tbody>

                                <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                $action = ucfirst(explode('.', $permission->name)[1] ?? '');
                                ?>

                                <tr>

                                    <td><?php echo e($loop->iteration); ?></td>

                                    <td>

                                        <?php switch(strtolower($action)):

                                        case ('create'): ?>

                                        <span class="permission-badge badge-create">
                                            <i class="fas fa-plus me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>

                                        <?php case ('edit'): ?>

                                        <span class="permission-badge badge-edit">
                                            <i class="fas fa-pen me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>

                                        <?php case ('delete'): ?>

                                        <span class="permission-badge badge-delete">
                                            <i class="fas fa-trash me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>

                                        <?php case ('view'): ?>

                                        <span class="permission-badge badge-view">
                                            <i class="fas fa-eye me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>

                                        <?php case ('approve'): ?>

                                        <span class="permission-badge badge-approve">
                                            <i class="fas fa-check me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>

                                        <?php case ('export'): ?>

                                        <span class="permission-badge badge-export">
                                            <i class="fas fa-file-export me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>
                                        <?php case ('upload'): ?>

                                        <span class="permission-badge badge-upload">
                                            <i class="fas fa-file-upload me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>
                                        <?php case ('calculate'): ?>

                                        <span class="permission-badge badge-calculate">
                                            <i class="fas fa-calculator me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>

                                        <?php case ('view-details'): ?>

                                        <span class="permission-badge badge-view-details">
                                            <i class="fas fa-circle-info me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php break; ?>

                                        <?php default: ?>

                                        <span class="permission-badge badge-default">
                                            <i class="fas fa-circle me-1"></i>
                                            <?php echo e($action); ?>

                                        </span>

                                        <?php endswitch; ?>

                                    </td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['permission-management.edit', 'permission-management.delete'])): ?>
                                    <td class="text-center">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission-management.edit')): ?>
                                        <a href="<?php echo e(route('admin.permissions.edit',$permission->id)); ?>"
                                            class="btn btn-warning btn-sm me-2" title="Edit Permission">

                                            <i class="fas fa-edit"></i>

                                        </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission-management.delete')): ?>
                                        <form action="<?php echo e(route('admin.permissions.destroy',$permission->id)); ?>"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this permission?')">

                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>

                                            <button class="btn btn-danger btn-sm" title="Delete Permission">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>
                                        <?php endif; ?>

                                    </td>
                                    <?php endif; ?>
                                </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($permissions->links('pagination::bootstrap-5')); ?>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<style>
/* ===========================
   Card
=========================== */

.card {
    border-radius: 12px;
}

.card-header {
    padding: 18px 24px;
}

.card-body {
    padding: 20px;
}


/* ===========================
   Accordion
=========================== */

.accordion-item {
    border: 1px solid #e9ecef;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
}

.accordion-button {
    background: #fff;
    font-weight: 600;
    color: #2c3e50;
    padding: 16px 20px;
}

.accordion-button:not(.collapsed) {
    background: #f8f9fa;
    color: #0d6efd;
}

.accordion-button:focus {
    box-shadow: none;
}


/* ===========================
   Table
=========================== */

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #1f4277;
    color: #fff;
    font-weight: 600;
    padding: 14px;
    border: none;
}

.table tbody td {
    vertical-align: middle;
    padding: 14px;
}

.table-hover tbody tr:hover {
    background: #f8fbff;
}


/* ===========================
   Permission Badges
=========================== */

.permission-badge {

    width: 110px;
    height: 36px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 50px;

    font-size: 14px;
    font-weight: 600;

    letter-spacing: .2px;
}


/* View */

.badge-view {
    background: #E8F3FF;
    color: #0D6EFD;
}


/* Create */

.badge-create {
    background: #E8F8EF;
    color: #198754;
}


/* Edit */

.badge-edit {
    background: #FFF4DD;
    color: #C58A00;
}


/* Delete */

.badge-delete {
    background: #FDECEC;
    color: #DC3545;
}


/* Approve */

.badge-approve {
    background: #EEF2FF;
    color: #4F46E5;
}


/* Export */

.badge-export {
    background: #F1F3F5;
    color: #495057;
}


/* Upload */

.badge-upload {
    background: #E8F4FD;
    color: #1565C0;
}


/* Default */

.badge-default {
    background: #ECECEC;
    color: #343A40;
}

/* Calculate */
.badge-calculate {
    background: #FFF8E1;
    color: #F57F17;
}

/* View Details */
.badge-view-details {
    background: #E8F5E9;
    color: #2E7D32;
}


/* ===========================
   Action Buttons
=========================== */

.btn-warning {

    background: #FFC107;
    border: none;
}

.btn-warning:hover {

    background: #E0A800;
}

.btn-danger {

    border: none;
}

.btn-danger:hover {

    background: #BB2D3B;
}


/* ===========================
   Pagination
=========================== */

.pagination {

    margin-bottom: 0;
}

.page-link {

    border-radius: 6px;
    margin: 0 3px;
    color: #1f4277;
}

.page-item.active .page-link {

    background: #1f4277;
    border-color: #1f4277;
}


/* ===========================
   Alerts
=========================== */

.alert {

    border-radius: 8px;
}

.accordion-button::after {
    margin-left: 15px;
}

.accordion-button .btn {
    z-index: 100;
    position: relative;
}
</style>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/permissions/index.blade.php ENDPATH**/ ?>