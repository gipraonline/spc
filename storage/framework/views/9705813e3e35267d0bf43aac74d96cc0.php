<?php $__env->startPush('styles'); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ===== Employee Records — same visual language as the HR "Employee Records" module ===== */
.employee-page {
    --brand: #4E7A33;
    --brand-strong: #0E5239;
    --brand-ink: #1F3D14;
    --brand-bright: #5E8D3D;
    --brand-glow: rgba(94, 141, 61, .35);
    --brand-soft: #E4F3EB;
    --brand-softer: #F2F9F5;
    --line: rgba(18, 58, 40, 0.13);
    --line-soft: rgba(18, 58, 40, 0.07);
    --text: #22352C;
    --text-muted: #61756B;
    --ok-soft: #DCF3E4;
    --warn-soft: #FCF0D8;
    --bad-soft: #FBE7E4;
    --shadow-sm: 0 1px 2px rgba(10, 61, 44, .05);
    --font-head: 'Kanit', sans-serif;
    --font-body: 'Outfit', sans-serif;
    padding: 24px clamp(16px, 3vw, 32px) 8px;
    font-family: var(--font-body);
    color: var(--text);
}

.employee-page-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.employee-page-heading h2 {
    margin: 0;
    font-family: var(--font-head);
    font-weight: 600;
    font-size: 21px;
    color: var(--brand-ink);
    display: flex;
    align-items: center;
    gap: 10px;
}

.employee-page-heading h2 i {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.employee-page-heading p {
    margin: 5px 0 0;
    color: var(--text-muted);
    font-size: 13px;
}

.employee-directory-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.employee-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 18px 20px 16px;
    background: #fff;
    flex-wrap: wrap;
}

.employee-search {
    position: relative;
    height: 41px;
    width: 280px;
    max-width: 100%;
    display: flex;
    align-items: center;
    gap: 9px;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 0 12px;
    background: #FBFDFC;
}

.employee-search:focus-within {
    border-color: var(--brand-bright);
    background: #fff;
    box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .14);
}

.employee-search span {
    font-size: 14px;
    color: var(--text-muted);
}

.employee-search input {
    border: 0;
    background: transparent;
    padding: 0;
    font-size: 13px;
    min-width: 0;
    outline: 0;
    box-shadow: none;
    width: 100%;
}

.employee-search #employee_suggestions {
    top: calc(100% + 6px);
    left: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(10, 61, 44, .12);
    z-index: 1000;
    background: #fff;
}

.employee-search #employee_suggestions .list-group-item {
    border: 0;
    padding: 10px 14px;
    cursor: pointer;
    font-size: 13px;
    font-family: var(--font-body);
}

.employee-search #employee_suggestions .list-group-item:hover {
    background: var(--brand-softer);
}

.employee-filters {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.employee-filters select {
    height: 41px;
    min-width: 170px;
    padding: 0 10px;
    border: 1px solid var(--line);
    background: #fff;
    font-size: 13px;
    border-radius: 12px;
    font-family: var(--font-body);
    color: var(--text);
}

.employee-filter-btn,
.employee-reset-btn {
    height: 41px;
    border: 0;
    background: var(--brand-softer);
    color: var(--brand);
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    padding: 0 16px;
    border-radius: 12px;
    font-family: var(--font-body);
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.employee-filter-btn {
    background: var(--brand);
    color: #fff;
}

.employee-filter-btn:hover {
    filter: brightness(1.07);
}

.employee-reset-btn:hover {
    background: var(--brand-soft);
}

.employee-add {
    height: 41px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0 17px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    font-family: var(--font-body);
    box-shadow: 0 10px 20px -10px var(--brand-glow);
    text-decoration: none;
}

.employee-add:hover {
    filter: brightness(1.07);
    color: #fff;
}

.employee-table-wrap {
    overflow-x: auto;
    border-top: 1px solid var(--line-soft);
}

.employee-table {
    min-width: 1080px;
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}

.employee-table th {
    padding: 12px;
    border-bottom: 1px solid var(--line-soft);
    background: #F7FBF8;
    color: #5B6E63;
    font-size: 10.5px;
    letter-spacing: .07em;
    text-transform: uppercase;
    text-align: left;
}

.employee-table td {
    padding: 12px;
    border-bottom: 1px solid var(--line-soft);
    color: var(--text);
    white-space: nowrap;
    height: 64px;
}

.employee-table tbody tr:last-child td {
    border-bottom: 0;
}

.employee-table tbody tr:hover {
    background: #F4FAF7;
}

.employee-table th:first-child,
.employee-table td:first-child {
    padding-left: 14px;
}

.employee-table th.actions-head {
    width: 160px;
}

.employee-person {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 205px;
}

.employee-avatar {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    background: linear-gradient(135deg, var(--brand-soft), #D2EEDF);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
    font-family: var(--font-head);
}

.employee-name {
    font-family: var(--font-head);
    font-size: 13.5px;
    font-weight: 500;
    line-height: 1.3;
    color: var(--brand-ink);
}

.employee-email {
    font-size: 11.5px;
    color: var(--text-muted);
    line-height: 1.35;
    margin-top: 2px;
}

.employee-id {
    font-family: var(--font-body);
    font-size: 11.5px;
    color: #64756B;
}

.employee-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5.5px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}

.employee-status i {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: block;
    background: currentColor;
}

.employee-status.active {
    background: var(--ok-soft);
    color: #116A38;
}

.employee-status.inactive {
    background: var(--bad-soft);
    color: #942B2B;
}

.employee-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 15px;
}

.employee-actions .ea-link {
    background: none;
    border: none;
    font-size: 12.5px;
    color: var(--brand);
    font-weight: 600;
    cursor: pointer;
    font-family: var(--font-body);
    padding: 0;
    text-decoration: none;
}

.employee-actions .ea-link:hover {
    color: var(--brand-strong);
    text-decoration: underline;
}

.employee-actions form {
    margin: 0;
}

.employee-table th.profile-head {
    width: 230px;
}

.employee-row-btns {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
}

.employee-history-btn {
    cursor: pointer;
    height: 31px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 13px;
    border-radius: 9px;
    border: 1px solid #1F5C2E;
    background: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    color: #fff;
    font: 500 11.5px/1 var(--font-body);
    text-decoration: none;
    white-space: nowrap;
}

.employee-history-btn:hover {
    filter: brightness(1.08);
    color: #fff;
}

.employee-status.former {
    background: #EEE9E0;
    color: #6B5B3A;
}

.employee-profile-btn {
    cursor: pointer;
    height: 31px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 13px;
    border-radius: 9px;
    border: 1px solid var(--brand);
    background: var(--brand-softer);
    color: var(--brand);
    font: 500 11.5px/1 var(--font-body);
    text-decoration: none;
}

.employee-profile-btn:hover {
    background: var(--brand);
    color: #fff;
}

.employee-actions .deactivate {
    height: 31px;
    border-radius: 9px;
    padding: 0 13px;
    font: 500 11.5px/1 var(--font-body);
    cursor: pointer;
    border: 1px solid #C23A3A;
    background: #C23A3A;
    color: #fff;
}

.employee-actions .deactivate:hover {
    filter: brightness(1.08);
}

.employee-table td.employee-empty {
    height: auto;
    padding: 46px 20px;
    text-align: center;
    white-space: normal;
    color: var(--text-muted);
    font-size: 13px;
}

.employee-table tbody tr:hover td.employee-empty {
    background: transparent;
}

.employee-empty-ico {
    width: 46px;
    height: 46px;
    margin: 0 auto 12px;
    border-radius: 14px;
    background: var(--brand-soft);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.employee-empty b {
    display: block;
    font-family: var(--font-head);
    font-size: 15px;
    font-weight: 500;
    color: var(--brand-ink);
    margin-bottom: 3px;
}

.employee-empty span {
    display: block;
}

.employee-pagination {
    padding: 16px 20px;
}

.employee-alert {
    margin-bottom: 16px;
}

@media (max-width:1100px) {
    .employee-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .employee-search {
        width: 100%;
    }

    .employee-filters {
        justify-content: flex-start;
    }
}
</style>

<div class="employee-page">
    <div class="employee-page-heading">
        <div>
            <h2><i class="fa-solid fa-address-book"></i>Employee Records</h2>
            <p>Search, filter and manage the employee master.</p>
        </div>
    </div>

    <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success employee-alert" role="alert">
        <?php echo e($message); ?>

    </div>
    <?php endif; ?>
    <?php if($message = Session::get('warning')): ?>
    <div class="alert alert-warning employee-alert" role="alert">
        <?php echo e($message); ?>

    </div>
    <?php endif; ?>
    <?php if($message = Session::get('error')): ?>
    <div class="alert alert-danger employee-alert" role="alert">
        <?php echo e($message); ?>

    </div>
    <?php endif; ?>

    <div class="employee-directory-card">
        <form method="POST" action="<?php echo e(route('admin.employees.search')); ?>" class="employee-toolbar">
            <?php echo csrf_field(); ?>

            <div class="employee-search">
                <span aria-hidden="true"><i class="fa-solid fa-magnifying-glass" style="font-size:12px;"></i></span>
                <input type="text" id="employee_search" name="employee_search" value="<?php echo e(session('employee_search')); ?>"
                    autocomplete="off" placeholder="Search by Name or Code">
                <input type="hidden" id="employee_id" name="employee_id">
                <ul id="employee_suggestions" class="list-group position-absolute w-100"></ul>
            </div>

            <div class="employee-filters">
                <select id="n_designation_id" name="n_designation_id">
                    <option value="">All Designations</option>
                    <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($designation->n_designation_id); ?>"
                        <?php echo e(session('designation_filter') == $designation->n_designation_id ? 'selected' : ''); ?>>
                        <?php echo e($designation->c_designation); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.history')): ?>
                <select id="employee_status" name="employee_status">
                    <option value="current" <?php echo e(($statusFilter ?? 'current') === 'current' ? 'selected' : ''); ?>>Current
                        employees</option>
                    <option value="former" <?php echo e(($statusFilter ?? 'current') === 'former' ? 'selected' : ''); ?>>Former
                        employees (deleted / left)</option>
                </select>
                <?php endif; ?>

                <button type="submit" class="employee-filter-btn">
                    <i class="fa-solid fa-filter" style="margin-right:6px;font-size:11px;"></i>Filter
                </button>

                <a href="<?php echo e(route('admin.employees.clearSearch')); ?>" class="employee-reset-btn">
                    <i class="fa-solid fa-rotate-left" style="margin-right:6px;font-size:11px;"></i>Reset
                </a>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['employees.export', 'employees.view'])): ?>
                <a href="<?php echo e(route('admin.employees.export')); ?>" class="employee-add" style="background:linear-gradient(135deg,#2f8f5b,#1a6b3f);">
                    <i class="fa-solid fa-file-excel" style="font-size:11px;"></i>Export
                </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.create')): ?>
                <a href="<?php echo e(route('admin.employees.create')); ?>" class="employee-add">
                    <i class="fa-solid fa-user-plus" style="font-size:11px;"></i>Add Employee
                </a>
                <?php endif; ?>
            </div>
        </form>

        <div class="employee-table-wrap">
            <table class="employee-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Code</th>
                        <th>Designation</th>
                        <th>Reporting To</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['employees.edit','employees.delete'])): ?>
                        <th class="actions-head"></th>
                        <?php endif; ?>
                        <th class="profile-head"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                    $empName = $employee->c_employee_name ?? '?';
                    $initials = collect(preg_split('/\s+/', trim($empName)))
                    ->filter()->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->implode('');
                    $isActive = $employee->c_status === 'Y';
                    $isFormer = $employee->trashed();
                    ?>
                    <tr>
                        <td>
                            <div class="employee-person">
                                <div class="employee-avatar"><?php echo e($initials ?: '?'); ?></div>
                                <div>
                                    <div class="employee-name"><?php echo e($empName); ?></div>
                                    <div class="employee-email"><?php echo e($employee->c_employee_email ?? '—'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="employee-id"><?php echo e($employee->c_employee_code); ?></td>
                        <td><?php echo e($employee->designation?->c_designation ?? '—'); ?></td>
                        <td><?php echo e($employee->reportingManager?->designation?->c_designation ?? '—'); ?></td>
                        <td><?php echo e($employee->c_employee_phone ?? '—'); ?></td>
                        <td>
                            <span
                                class="employee-status <?php echo e($isFormer ? 'former' : ($isActive ? 'active' : 'inactive')); ?>">
                                <i></i><?php echo e($isFormer ? 'Former' : ($isActive ? 'Active' : 'Inactive')); ?>

                            </span>
                        </td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['employees.edit','employees.delete'])): ?>
                        <td>
                            <div class="employee-actions">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.edit')): ?>
                                <?php if (! ($isFormer)): ?>
                                <a href="<?php echo e(route('admin.employees.edit', $employee)); ?>" class="ea-link">Edit</a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.delete')): ?>
                                <?php if (! ($isFormer)): ?>
                                <form method="POST" action="<?php echo e(route('admin.employees.destroy', $employee)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="deactivate"
                                        onclick="return confirm('Delete this employee? Their history is kept and stays available under Former employees.')">Delete</button>
                                </form>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                        <td class="profile-cell">
                            <div class="employee-row-btns">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.history')): ?>
                                <a href="<?php echo e(route('admin.employees.history', $employee)); ?>" class="employee-history-btn"
                                    title="Performance, career timeline and resignation / termination record">
                                    <i class="fa-solid fa-clock-rotate-left"></i>History
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employees.profile')): ?>
                                <button type="button" class="employee-profile-btn" data-bs-toggle="modal"
                                    data-bs-target="#employeeProfileModal" data-name="<?php echo e($empName); ?>"
                                    data-initials="<?php echo e($initials ?: '?'); ?>"
                                    data-email="<?php echo e($employee->c_employee_email ?? '—'); ?>"
                                    data-phone="<?php echo e($employee->c_employee_phone ?? '—'); ?>"
                                    data-code="<?php echo e($employee->c_employee_code ?? '—'); ?>"
                                    data-address="<?php echo e($employee->c_employee_address ?? '—'); ?>"
                                    data-dob="<?php echo e(!empty($employee->date_of_birth) ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : '—'); ?>"
                                    data-gender="<?php echo e(!empty($employee->gender) ? ucfirst($employee->gender) : '—'); ?>"
                                    data-personal-email="<?php echo e($employee->personal_email ?? '—'); ?>"
                                    data-city="<?php echo e($employee->city ?? '—'); ?>"
                                    data-department="<?php echo e($employee->department?->name ?? '—'); ?>"
                                    data-joining="<?php echo e(!empty($employee->date_of_joining) ? \Carbon\Carbon::parse($employee->date_of_joining)->format('d M Y') : '—'); ?>"
                                    data-designation="<?php echo e($employee->designation?->c_designation ?? '—'); ?>"
                                    data-reporting="<?php echo e($employee->reportingManager?->c_employee_name ?? '—'); ?>"
                                    data-status="<?php echo e($isActive ? 'Active' : 'Inactive'); ?>"
                                    data-account="<?php echo e($employee->kycSubmission?->account_number ?? '—'); ?>"
                                    data-ifsc="<?php echo e($employee->kycSubmission?->ifsc_code ?? '—'); ?>"
                                    data-bank="<?php echo e($employee->kycSubmission?->bank_name ?? '—'); ?>"
                                    data-branch="<?php echo e($employee->kycSubmission?->bank_branch ?? '—'); ?>">
                                    <i class="fa-solid fa-user"></i>Profile
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="employee-empty">
                            <div class="employee-empty-ico"><i class="fa-solid fa-magnifying-glass"></i></div>
                            <b>No employees found</b>
                            <span>Try changing the search or filters.</span>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="employee-pagination">
            <?php echo e($employees->links()); ?>

        </div>
    </div>
</div>

<!-- Employee Profile (view only) -->
<div class="modal fade" id="employeeProfileModal" tabindex="-1" aria-labelledby="employeeProfileTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content epv">
            <div class="epv-hero">
                <button type="button" class="epv-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
                <div class="epv-avatar" id="pf_initials">?</div>
                <div class="epv-hero-text">
                    <h5 id="employeeProfileTitle">Employee Profile</h5>
                    <div class="epv-sub"><span id="pf_designation_hero">—</span></div>
                    <div class="epv-chips">
                        <span class="epv-chip"><i class="fa-solid fa-id-badge"></i><span
                                id="pf_code_hero">—</span></span>
                        <span class="epv-chip epv-status" id="pf_status_chip"><i class="dot"></i><span
                                id="pf_status_hero">—</span></span>
                    </div>
                </div>
            </div>

            <div class="modal-body epv-body">
                <div class="epv-section"><i class="fa-solid fa-user"></i>Identification</div>
                <div class="epv-grid">
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-user"></i></span>
                        <div>
                            <div class="epv-label">Employee Name</div>
                            <div class="epv-value" id="pf_name">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-id-badge"></i></span>
                        <div>
                            <div class="epv-label">Employee Code</div>
                            <div class="epv-value" id="pf_code">—</div>
                        </div>
                    </div>
                    <div class="epv-item full">
                        <span class="epv-ico"><i class="fa-solid fa-location-dot"></i></span>
                        <div>
                            <div class="epv-label">Address</div>
                            <div class="epv-value" id="pf_address">—</div>
                        </div>
                    </div>
                </div>

                <div class="epv-section"><i class="fa-solid fa-address-card"></i>Personal &amp; HR Details</div>
                <div class="epv-grid">
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-cake-candles"></i></span>
                        <div>
                            <div class="epv-label">Date of Birth</div>
                            <div class="epv-value" id="pf_dob">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-venus-mars"></i></span>
                        <div>
                            <div class="epv-label">Gender</div>
                            <div class="epv-value" id="pf_gender">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-envelope-open"></i></span>
                        <div>
                            <div class="epv-label">Personal Email</div>
                            <div class="epv-value" id="pf_personal_email">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-city"></i></span>
                        <div>
                            <div class="epv-label">City</div>
                            <div class="epv-value" id="pf_city">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-sitemap"></i></span>
                        <div>
                            <div class="epv-label">Department</div>
                            <div class="epv-value" id="pf_department">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-calendar-plus"></i></span>
                        <div>
                            <div class="epv-label">Date of Joining</div>
                            <div class="epv-value" id="pf_joining">—</div>
                        </div>
                    </div>
                </div>

                <div class="epv-section"><i class="fa-solid fa-briefcase"></i>Role &amp; Assignment</div>
                <div class="epv-grid">
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-briefcase"></i></span>
                        <div>
                            <div class="epv-label">Designation</div>
                            <div class="epv-value" id="pf_designation">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-user-check"></i></span>
                        <div>
                            <div class="epv-label">Reporting Manager</div>
                            <div class="epv-value" id="pf_reporting">—</div>
                        </div>
                    </div>
                </div>

                <div class="epv-section"><i class="fa-solid fa-building-columns"></i>Account Details</div>
                <div class="epv-grid">
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-hashtag"></i></span>
                        <div>
                            <div class="epv-label">Account Number</div>
                            <div class="epv-value" id="pf_account">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-barcode"></i></span>
                        <div>
                            <div class="epv-label">IFSC Code</div>
                            <div class="epv-value" id="pf_ifsc">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-building-columns"></i></span>
                        <div>
                            <div class="epv-label">Bank Name</div>
                            <div class="epv-value" id="pf_bank">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-map"></i></span>
                        <div>
                            <div class="epv-label">Branch Name</div>
                            <div class="epv-value" id="pf_branch">—</div>
                        </div>
                    </div>
                </div>

                <div class="epv-section"><i class="fa-solid fa-envelope"></i>Contact &amp; Status</div>
                <div class="epv-grid">
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-envelope"></i></span>
                        <div>
                            <div class="epv-label">Email Address</div>
                            <div class="epv-value" id="pf_email">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-phone"></i></span>
                        <div>
                            <div class="epv-label">Phone Number</div>
                            <div class="epv-value" id="pf_phone">—</div>
                        </div>
                    </div>
                    <div class="epv-item">
                        <span class="epv-ico"><i class="fa-solid fa-circle-half-stroke"></i></span>
                        <div>
                            <div class="epv-label">Employment Status</div>
                            <div class="epv-value" id="pf_status">—</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="epv-foot">
                <span class="epv-foot-note"><i class="fa-solid fa-eye"></i>View only</span>
                <button type="button" class="epv-close-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('employeeProfileModal').addEventListener('show.bs.modal', function(event) {
    const d = event.relatedTarget.dataset;
    const map = {
        name: 'pf_name',
        code: 'pf_code',
        email: 'pf_email',
        phone: 'pf_phone',
        designation: 'pf_designation',
        reporting: 'pf_reporting',
        status: 'pf_status',
        account: 'pf_account',
        ifsc: 'pf_ifsc',
        bank: 'pf_bank',
        branch: 'pf_branch',
        address: 'pf_address',
        dob: 'pf_dob',
        gender: 'pf_gender',
        personalEmail: 'pf_personal_email',
        city: 'pf_city',
        department: 'pf_department',
        joining: 'pf_joining'
    };
    Object.keys(map).forEach(function(k) {
        const el = document.getElementById(map[k]);
        const v = d[k] || '—';
        el.textContent = v;
        el.classList.toggle('is-empty', v === '—');
    });
    // hero header
    document.getElementById('pf_initials').textContent = d.initials || '?';
    document.getElementById('employeeProfileTitle').textContent = d.name || 'Employee Profile';
    document.getElementById('pf_designation_hero').textContent = d.designation || '—';
    document.getElementById('pf_code_hero').textContent = d.code || '—';
    document.getElementById('pf_status_hero').textContent = d.status || '—';
    document.getElementById('pf_status_chip').classList.toggle('inactive', d.status !== 'Active');
});
</script>
<style>
#employeeProfileModal .modal-dialog {
    max-width: 760px;
}

#employeeProfileModal .epv {
    --brand: #4E7A33;
    --brand-strong: #0E5239;
    --brand-ink: #1F3D14;
    --brand-soft: #E4F3EB;
    --brand-softer: #F2F9F5;
    --line: rgba(18, 58, 40, .13);
    --line-soft: rgba(18, 58, 40, .07);
    --text: #22352C;
    --text-muted: #61756B;
    font-family: 'Outfit', sans-serif;
    color: var(--text);
    border: 0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 30px 70px -20px rgba(10, 61, 44, .45);
}

.epv-hero {
    position: relative;
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 26px 30px;
    background: linear-gradient(135deg, #0E5239 0%, #1F5C2E 55%, #4E7A33 130%);
    color: #fff;
    overflow: hidden;
}

.epv-hero::after {
    content: '';
    position: absolute;
    right: -50px;
    top: -60px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .07);
}

.epv-close {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 2;
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 10px;
    background: rgba(255, 255, 255, .14);
    color: #fff;
    cursor: pointer;
}

.epv-close:hover {
    background: rgba(255, 255, 255, .26);
}

.epv-avatar {
    width: 72px;
    height: 72px;
    flex-shrink: 0;
    border-radius: 20px;
    background: rgba(255, 255, 255, .18);
    border: 2px solid rgba(255, 255, 255, .35);
    display: flex;
    align-items: center;
    justify-content: center;
    font: 600 26px/1 'Kanit', sans-serif;
    letter-spacing: .03em;
}

.epv-hero-text {
    min-width: 0;
    position: relative;
    z-index: 1;
}

.epv-hero-text h5 {
    margin: 0;
    font: 600 22px/1.2 'Kanit', sans-serif;
    color: #fff;
    word-break: break-word;
}

.epv-sub {
    margin-top: 3px;
    font-size: 13.5px;
    color: rgba(255, 255, 255, .82);
}

.epv-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}

.epv-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 26px;
    padding: 0 11px;
    border-radius: 999px;
    background: rgba(255, 255, 255, .16);
    font-size: 12px;
    font-weight: 500;
}

.epv-status .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #7CE39B;
    box-shadow: 0 0 0 3px rgba(124, 227, 155, .25);
}

.epv-status.inactive .dot {
    background: #FF9C93;
    box-shadow: 0 0 0 3px rgba(255, 156, 147, .25);
}

.epv-body {
    padding: 22px 30px 6px;
    background: #fff;
}

.epv-section {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 4px 0 12px;
    font: 600 11.5px/1 'Kanit', sans-serif;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: var(--brand);
}

.epv-section::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--line-soft);
}

.epv-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px 14px;
    margin-bottom: 22px;
}

.epv-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 14px;
    min-width: 0;
    background: var(--brand-softer);
    border: 1px solid var(--line-soft);
    border-radius: 14px;
    transition: border-color .2s, transform .2s;
}

.epv-item:hover {
    border-color: var(--line);
    transform: translateY(-1px);
}

.epv-item.full {
    grid-column: 1 / -1;
}

.epv-ico {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.epv-label {
    font-size: 11.5px;
    font-weight: 500;
    color: var(--text-muted);
    margin-bottom: 2px;
}

.epv-value {
    font-size: 14px;
    font-weight: 500;
    color: var(--brand-ink);
    word-break: break-word;
}

.epv-value.is-empty {
    color: #A5B4AB;
    font-weight: 400;
}

.epv-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 30px 18px;
    border-top: 1px solid var(--line-soft);
    background: #fff;
}

.epv-foot-note {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
}

.epv-close-btn {
    height: 40px;
    padding: 0 22px;
    border: 0;
    border-radius: 12px;
    cursor: pointer;
    font: 500 13px/1 'Outfit', sans-serif;
    color: #fff;
    background: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    box-shadow: 0 10px 20px -10px rgba(94, 141, 61, .5);
}

.epv-close-btn:hover {
    filter: brightness(1.07);
}

@media (max-width: 640px) {
    .epv-hero {
        padding: 22px 18px;
        flex-direction: column;
        align-items: flex-start;
    }

    .epv-body {
        padding: 18px 18px 4px;
    }

    .epv-grid {
        grid-template-columns: 1fr;
    }

    .epv-foot {
        padding: 12px 18px 16px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let designation = document.getElementById('n_designation_id');
    let storeDiv = document.getElementById('store_div');
    let storeSelect = document.getElementById('n_store_id');
    let employeeInput = document.getElementById('employee_search');

    function toggleStore() {
        if (!storeDiv || !storeSelect) return;
        let selectedOption = designation.options[designation.selectedIndex];
        let isRequired = selectedOption ? selectedOption.getAttribute('data-store') : null;

        if (isRequired === "1") {
            storeDiv.style.opacity = '1';
            storeDiv.style.pointerEvents = 'auto';
        } else {
            storeDiv.style.opacity = '0.5';
            storeDiv.style.pointerEvents = 'none';
            storeSelect.value = '';
        }
    }
    designation.addEventListener('change', toggleStore);
    toggleStore();

    // Employee search suggestions
    function toggleFiltersByEmployee() {
        let hasValue = employeeInput && employeeInput.value.trim().length > 0;

        if (hasValue) {
            designation.disabled = true;
            if (storeSelect) storeSelect.disabled = true;
            if (storeDiv) {
                storeDiv.style.opacity = '0.5';
                storeDiv.style.pointerEvents = 'none';
            }
        } else {
            designation.disabled = false;
            if (storeSelect) storeSelect.disabled = false;
            toggleStore();
        }
    }
    if (employeeInput) {
        employeeInput.addEventListener('input', toggleFiltersByEmployee);
    }
    toggleFiltersByEmployee();

    function toggleEmployeeState() {
        let designationVal = designation.value ? designation.value.trim() : '';
        let storeVal = storeSelect && storeSelect.value ? storeSelect.value.trim() : '';
        let shouldDisableEmployee = (designationVal !== '' || storeVal !== '');

        if (employeeInput) {
            if (shouldDisableEmployee) {
                employeeInput.disabled = true;
                employeeInput.value = '';
                let empId = document.getElementById('employee_id');
                if (empId) empId.value = '';
                let sug = document.getElementById('employee_suggestions');
                if (sug) sug.innerHTML = '';
            } else {
                employeeInput.disabled = false;
            }
        }
    }
    designation.addEventListener('change', toggleEmployeeState);
    if (storeSelect) storeSelect.addEventListener('change', toggleEmployeeState);
    if (employeeInput) employeeInput.addEventListener('input', toggleEmployeeState);
    toggleEmployeeState();
});

// Pass employee data to JS for search suggestions
window.employees = <?php echo json_encode($employeesForSearch, 15, 512) ?>;
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc_new\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>