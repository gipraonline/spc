<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>SPC</title>
    <!-- Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Central Bazaar Incentive Admin" />
    <meta name="author" content="" />
    <meta name="keywords" content="Incentive" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('dist/images/logos/fav.png')); ?>" />
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="<?php echo e(asset('dist/css/style.min.css')); ?>" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <?php echo $__env->yieldPushContent('styles'); ?>


    <style>
    /* FILTER WRAPPER */
    .filter-card-wrapper {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 24px;
    }

    /* HEADER */
    .filter-header-sub {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        color: #374151;
    }

    /* ICON */
    .icon-box {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4f6df5;
    }

    /* LABEL */
    .custom-filter-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }

    /* INPUTS */
    .styled-select {
        height: 46px;
        border-radius: 12px;
        border: 1px solid #dbe2ea;
        font-size: 14px;
        font-weight: 500;
    }

    .styled-select:focus {
        border-color: #4f6df5;
        box-shadow: 0 0 0 4px rgba(79, 109, 245, 0.08);
    }

    /* BUTTON */
    .filter-action-container {
        display: flex;
        align-items: end;
    }

    .btn-creative-filter {
        width: 100%;
        height: 46px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(90deg, #5b7cff 0%, #4f6df5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 700;
    }

    .btn-creative-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(79, 109, 245, 0.25);
    }

    .btn-creative-filter {
        height: 42px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;

        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        background: linear-gradient(135deg, #5d87ff 0%, #4f73f6 100%);
        border: none;

        transition: all 0.2s ease-in-out;
    }

    .btn-creative-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(93, 135, 255, 0.25);
    }

    body {
        background-color: #f1f5f9;
    }

    aside.left-sidebar {
        background: #fff;
        border-right: 1px solid #e2e8f0;
        padding: 2rem 0.25rem;
    }

    .buttonSpc {
        border-radius: 16px;
        background: linear-gradient(135deg, #5A8D3A, #074E30);
        align-items: center;
        color: #fff !important;
        min-width: 110px;
    }

    .btn-outline-secondary {
        border-radius: 16px !important;
        align-items: center;
        font-weight: 600;
        min-width: 110px;
    }

    .modal-header{
        background: linear-gradient(135deg, #5A8D3A, #074E30);
    }
    header.app-header {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link {
        color: #64748b;
        font-weight: 700;
        font-size: 15px;
    }

    .sidebar-nav ul .sidebar-item.selected>.sidebar-link,
    .sidebar-nav ul .sidebar-item.selected>.sidebar-link.active,
    .sidebar-nav ul .sidebar-item>.sidebar-link.active {
        background: linear-gradient(135deg, #5A8D3A, #074E30);
    }

    .body-wrapper>.container-fluid {
        max-width: 100%;
        height: auto;
        min-height: 90vh;
    }

    .card-title {
        font-size: 23px;
        background: linear-gradient(45deg, #1f4a8d, #15376e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800 !important;
    }

    .card.w-100.position-relative.overflow-hidden {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #1f376d;
    }

    table th,
    th.border-bottom-0 h6 {
        background: linear-gradient(135deg, #5A8D3A, #074E30);
        color: #fff !important;

    }

    table .text-muted {
        --bs-text-opacity: 1;
        color: rgb(31 55 109) !important;
        font-weight: 800;
    }

    table .text-success {
        --bs-text-opacity: 1;
        color: rgb(13 209 54) !important;
        font-weight: 800 !important;
    }

    table td {
        color: #000 !important;
        font-weight: 600;
    }

    .table>:not(caption)>*>* {


        border-bottom-width: var(--bs-border-width);
        box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
        border: 1px solid #ccc;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-color-state: var(--bs-table-hover-color);
        --bs-table-bg-state: #f1f5f9;
    }

    .border-bottom-0 {
        border-bottom: 1px solid #cbc2c2 !important;
    }


    .card-header-styled h5,
    .page-main-title {
        font-size: 23px;
        background: linear-gradient(45deg, #1f4a8d, #15376e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800 !important;
    }

    .card-header-styled {

        border-bottom: 1px solid #ccc;
    }

    .hide-menu {
        display: inline-block;
        width: 180px;
        /* Adjust as needed */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media screen and (max-width:767px) {
        .px-4.py-3.border-bottom.d-flex.justify-content-between.align-items-center {
            gap: 13px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between !important;
        }

        .premium-table-container {
            overflow-x: scroll;
        }

        .mt-3.mt-md-0.d-flex.gap-2 {
            flex-wrap: wrap;
        }

        .metric-bar {
            display: inline-block;
        }

        .table-responsive-custom {
            overflow-x: scroll;
        }
    }

    .active>.page-link,
    .page-link.active {
        z-index: 3;
        color: var(--bs-pagination-active-color);
        background-color: #023f87;
        border-color: #023f87;
    }

    .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between {
        gap: 0;
        flex-direction: column-reverse;
    }


    a.text-nowrap.logo-img img {
        width: 200px;
        margin: auto;
        display: block;
    }


    @media screen and (min-width: 992px) {
        #main-wrapper[data-layout=vertical][data-sidebartype=mini-sidebar] .left-sidebar .brand-logo {
            padding: 0;
        }

        #main-wrapper[data-layout=vertical][data-sidebartype=mini-sidebar] .logo-img {
            width: 70px;
            overflow: hidden;
        }

        #main-wrapper[data-layout=vertical][data-sidebartype=mini-sidebar] .left-sidebar .sidebar-nav ul .sidebar-item .sidebar-link {
            padding: 11px 4px;
        }
    }

    .btn-filter {
        background: #2f4b8f;
        color: #fff;
        padding: 10px 30px;
        border-radius: 6px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: 0.3s;
        font-weight: 800;
    }

    .btn-filter:hover {
        background: #fff;
        color: #4a5a82;

    }

    .card-title-custom {
        font-weight: 700;
        color: #2c3e50;
    }

    .trend-neutral {
        color: #475569;
        background: #e2e8f0;
    }

    #store_results {
        max-height: 100px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    #store_results {
        position: absolute;
        width: 100%;
        z-index: 9999;
        max-height: 150px;
        overflow-y: auto;
        background: #fff;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    #store_div {
        position: relative;
    }

    #cluster_store_results {
        max-height: 100px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .custom-btn {
        font-size: 14px;
        padding: 10px 18px;
    }

    #employee_search {
        height: 36px;
        border-radius: 50rem;
        /* pill shape like your buttons */
        border: 1px solid #0d6efd;
        /* primary like "All" button */
        padding: 0 14px;
        box-shadow: none !important;
        background: transparent;
        color: #437ccb;
        font-weight: 500;
        font-size: 15px;
    }

    #employee_search::placeholder {
        color: #072653;
        opacity: 0.7;
    }

    #employee_search:focus {
        border-color: #0b5ed7;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.15);
    }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div style="width: 2rem !important;" class="spinner-border text-danger lds-ripple" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>

                <div class="brand-logo d-flex align-products-center justify-content-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-nowrap logo-img">
                        <img src="<?php echo e(asset('dist/images/logos/spclogo.png')); ?>" alt="Centreal Bazaar Logo">
                    </a>
                    <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8 text-muted"></i>
                    </div>
                </div>
                <?php
                $dynamicMenus = \App\Http\Controllers\Admin\MenuController::getMenus();
                ?>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>

                    <ul id="sidebarnav">

                        <?php $__currentLoopData = $dynamicMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        
                        <?php if($parent->children->count() == 0): ?>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?php echo e($parent->route_name ? route($parent->route_name) : '#'); ?>">

                                <span>
                                    <i data-lucide="<?php echo e($parent->icon); ?>"></i>
                                </span>

                                <span class="hide-menu">
                                    <?php echo e($parent->name); ?>

                                </span>

                            </a>
                        </li>

                        <?php else: ?>

                        

                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">
                                <?php echo e($parent->name); ?>

                            </span>
                        </li>

                        <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <li class="sidebar-item">

                            <a class="sidebar-link" href="<?php echo e(route($child->route_name)); ?>">

                                <span>
                                    <i data-lucide="<?php echo e($child->icon); ?>"></i>
                                </span>

                                <span class="hide-menu">
                                    <?php echo e($child->name); ?>

                                </span>

                            </a>

                        </li>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endif; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>
                </nav>
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->

        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                    </ul>

                    <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="p-2">
                            <i class="ti ti-dots fs-7"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <div class="d-flex align-products-center justify-content-between">
                            <a href="javascript:void(0)"
                                class="nav-link d-flex d-lg-none align-products-center justify-content-center"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                aria-controls="offcanvasWithBothOptions">
                                <i class="ti ti-align-justified fs-7"></i>
                            </a>
                            <ul class="navbar-nav flex-row ms-auto align-products-center justify-content-center">
                                <li class="nav-item dropdown">
                                    <a class="nav-link pe-0" href="javascript:void(0)" id="drop1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-products-center">
                                            <div class="user-profile-img">
                                                <img src="<?php echo e(asset('dist/images/profile/user-1.jpg')); ?>"
                                                    class="rounded-circle" width="35" height="35" alt="" />
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                        aria-labelledby="drop1">
                                        <div class="profile-dropdown position-relative" data-simplebar>
                                            <div class="py-3 px-7 pb-0">
                                                <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                            </div>
                                            <div class="d-flex align-products-center py-9 mx-7 border-bottom">
                                                <img src="<?php echo e(asset('dist/images/profile/user-1.jpg')); ?>"
                                                    class="rounded-circle" width="80" height="80" alt="" />
                                                <div class="ms-3">
                                                    <h5 class="mb-1 fs-3"><?php echo e(Auth::user()->name); ?></h5>
                                                    <p class="mb-0 d-flex text-dark align-products-center gap-2">
                                                        <i class="ti ti-mail fs-4"></i> <?php echo e(Auth::user()->email); ?>

                                                    </p>
                                                </div>
                                            </div>
                                            <div class="message-body">
                                                <a href="<?php echo e(route('profile.edit')); ?>"
                                                    class="py-8 px-7 d-flex align-products-center">
                                                    <span
                                                        class="d-flex align-products-center justify-content-center bg-light rounded-1 p-6">
                                                        <img src="<?php echo e(asset('dist/images/svgs/icon-account.svg')); ?>" alt=""
                                                            width="24" height="24">
                                                    </span>
                                                    <div class="w-75 d-inline-block v-middle ps-3">
                                                        <h6 class="mb-1 bg-hover-primary fw-semibold"> My Profile </h6>
                                                        <span class="d-block text-dark">Account Settings</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="d-grid py-4 px-7 pt-8">
                                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <a href="<?php echo e(route('logout')); ?>"
                                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                                        class="btn btn-outline-primary w-100">Log Out</a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <!--  Header End -->

            <div class="container-fluid">
                <!-- Page Heading -->
                <?php if(isset($header)): ?>
                <div class="mb-4">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        <?php echo e($header); ?>

                    </h2>
                </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
                <?php echo e($slot ?? ''); ?>


            </div>

            <p style="
    text-align: center;
    color: #15386f;
    font-weight: 600;
">Copyright © 2026  All Rights Reserved.

            </p>
        </div>
    </div>


    <div class="modal fade" id="approveModal" tabindex="-1"
     aria-labelledby="approveModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

        <form method="POST"
              id="approveForm"
              action="<?php echo e(route('admin.salesorders.approval.save')); ?>">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>


            <div class="modal-content">

                <div class="modal-header"
                     style="background: linear-gradient(135deg, #5A8D3A, #074E30);">

                    <h5 class="modal-title text-white"
                        id="approveModalLabel">
                        Approval
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="id"
                           id="approval_id">

                    <div class="mb-3">

                        <label class="form-label">
                            Remarks <span class="text-danger">*</span>
                        </label>

                        <textarea
                            class="form-control"
                            name="remarks"
                            id="approval_remarks"
                            rows="3"
                            required></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Approval Status
                            <span class="text-danger">*</span>
                        </label>

                        <select class="form-select"
                                name="status"
                                id="approval_status"
                                required>

                            <option value="">
                                Select Status
                            </option>

                            <option value="Approved">
                                Approve
                            </option>

                            <option value="Rejected">
                                Reject
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn buttonSpc"
                            id="approvalSubmit">
                        Submit
                    </button>

                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                </div>

            </div>

        </form>



        </div>
    </div>
</div>
<






    <!-- Import Js Files -->
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo e(asset('dist/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/libs/simplebar/dist/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- core files -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('dist/js/app.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/js/app.init.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/js/sidebarmenu.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/js/custom.js')); ?>"></script>
    <!-- Link stores Js and CSS files-->


    <script>
    lucide.createIcons();
    </script>


    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/layouts/app.blade.php ENDPATH**/ ?>