<?php $__env->startPush('styles'); ?>
<style>
:root {
    --brand: #0f5132;
    --brand-dark: #0a3d25;
    --brand-light: #eaf6ef;
    --primary: #059669;
    --primary-light: #ecfdf5;

    --text: #172b1f;
    --muted: #6b7c72;
    --muted-light: #94a39a;

    --border: #e6ebe8;
    --surface: #ffffff;
    --background: #f5f8f6;

    --orange: #f97316;
    --orange-light: #fff7ed;

    --blue: #0284c7;
    --blue-light: #eff8ff;

    --purple: #7c3aed;
    --purple-light: #f5f3ff;

    --red: #dc2626;
    --red-light: #fef2f2;

    --shadow-sm: 0 2px 8px rgba(15, 81, 50, .04);
    --shadow-md: 0 8px 24px rgba(15, 81, 50, .07);

    --radius: 16px;
}

* {
    box-sizing: border-box;
}

.content-wrapper {
    min-height: 100vh;
    padding: 28px;
    background:
        radial-gradient(circle at top right,
            rgba(5, 150, 105, .05),
            transparent 28%),
        var(--background);
    color: var(--text);
}

.dashboard-container {
    max-width: 1500px;
    margin: 0 auto;
}


/* =========================================================
   LINKS
========================================================= */

.card-link {
    display: block;
    text-decoration: none !important;
    color: inherit !important;
}

.card-link:hover,
.card-link:focus {
    text-decoration: none !important;
    color: inherit !important;
}

.clickable-card {
    cursor: pointer;
}

.clickable-card:hover {
    transform: translateY(-3px);
}


/* =========================================================
   HEADER
========================================================= */

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 30px;
}

.welcome-area {
    display: flex;
    align-items: center;
    gap: 16px;
}

.welcome-avatar {
    width: 54px;
    height: 54px;
    border-radius: 15px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: linear-gradient(135deg,
            #0f5132,
            #059669);

    color: #fff;

    font-size: 20px;
    font-weight: 700;

    box-shadow: 0 8px 18px rgba(5, 150, 105, .20);
}

.dashboard-header h1 {
    margin: 0 0 5px;
    color: var(--brand);
    font-size: 27px;
    font-weight: 750;
}

.dashboard-header p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
}

.dashboard-date {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 9px 13px;

    border: 1px solid var(--border);
    border-radius: 10px;

    background: #fff;
    color: var(--muted);

    font-size: 12px;
    font-weight: 600;
}


/* =========================================================
   SECTION
========================================================= */

.dashboard-section {
    margin-bottom: 30px;
}

.section-header {
    margin-bottom: 15px;
}

.section-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-indicator {
    width: 4px;
    height: 21px;
    border-radius: 5px;

    background: linear-gradient(to bottom,
            var(--brand),
            var(--primary));
}

.section-title {
    margin: 0;
    color: var(--text);
    font-size: 18px;
    font-weight: 750;
}

.section-subtitle {
    margin: 4px 0 0 14px;
    color: var(--muted);
    font-size: 12px;
}


/* =========================================================
   COMMON CARD
========================================================= */

.dashboard-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);

    box-shadow: var(--shadow-sm);

    transition:
        transform .2s ease,
        box-shadow .2s ease,
        border-color .2s ease;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    border-color: #d8e5de;
    box-shadow: var(--shadow-md);
}


/* =========================================================
   ATTENDANCE OVERVIEW
========================================================= */

.attendance-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 30px;
}

.attendance-card {
    position: relative;
    overflow: hidden;

    display: flex;
    align-items: center;
    gap: 15px;

    min-height: 112px;
    padding: 21px;
}

.attendance-icon {
    width: 54px;
    height: 54px;
    min-width: 54px;

    border-radius: 15px;

    display: flex;
    align-items: center;
    justify-content: center;
}

.attendance-icon svg {
    width: 25px;
    height: 25px;
}

.attendance-icon.green {
    background: var(--primary-light);
    color: var(--primary);
}

.attendance-icon.orange {
    background: var(--orange-light);
    color: var(--orange);
}

.attendance-icon.purple {
    background: var(--purple-light);
    color: var(--purple);
}

.attendance-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.attendance-label {
    display: block;
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
}

.attendance-value {
    display: block;

    font-size: 24px;
    font-weight: 750;
    line-height: 1.1;
}

.attendance-value.green {
    color: var(--primary);
}

.attendance-value.orange {
    color: var(--orange);
}

.attendance-value.purple {
    color: var(--purple);
}

.attendance-value.gray {
    color: var(--muted-light);
}

.attendance-subtext {
    color: var(--muted-light);
    font-size: 10px;
}

.attendance-subtext.orange {
    color: var(--orange);
}


/* =========================================================
   SALES
========================================================= */

.sales-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.sales-card {
    position: relative;
    overflow: hidden;

    display: flex;
    align-items: center;
    gap: 15px;

    min-height: 112px;
    padding: 21px;
}

.sales-icon {
    width: 54px;
    height: 54px;
    min-width: 54px;

    border-radius: 15px;

    display: flex;
    align-items: center;
    justify-content: center;
}

.sales-icon svg {
    width: 25px;
    height: 25px;
}

.sales-icon.customers {
    background: #e8f7ef;
    color: #059669;
}

.sales-icon.today {
    background: #fff3e8;
    color: #ea580c;
}

.sales-icon.total {
    background: #eaf5fc;
    color: #0284c7;
}

.sales-label {
    display: block;
    margin-bottom: 5px;

    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
}

.sales-value {
    display: block;

    color: var(--text);
    font-size: 24px;
    font-weight: 750;
}

.sales-caption {
    margin-top: 5px;

    color: var(--muted-light);
    font-size: 10px;
}


/* =========================================================
   ORDERS
========================================================= */

.admin-order-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

.staff-order-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
}

.order-card {
    position: relative;
    overflow: hidden;

    display: flex;
    align-items: center;
    gap: 13px;

    min-height: 96px;
    padding: 17px;
}

.order-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 15px;
    bottom: 15px;

    width: 3px;

    border-radius: 0 5px 5px 0;
}

.order-icon {
    width: 46px;
    height: 46px;
    min-width: 46px;

    border-radius: 13px;

    display: flex;
    align-items: center;
    justify-content: center;
}

.order-icon svg {
    width: 22px;
    height: 22px;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.order-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 600;
}

.order-count {
    color: var(--text);
    font-size: 25px;
    font-weight: 750;
}


/* =========================================================
   STATUS COLORS
========================================================= */

.pending .order-icon {
    background: var(--orange-light);
    color: var(--orange);
}

.pending::before {
    background: var(--orange);
}

.approved .order-icon {
    background: var(--primary-light);
    color: var(--primary);
}

.approved::before {
    background: var(--primary);
}

.dispatched .order-icon {
    background: var(--blue-light);
    color: var(--blue);
}

.dispatched::before {
    background: var(--blue);
}

.shipped .order-icon {
    background: var(--purple-light);
    color: var(--purple);
}

.shipped::before {
    background: var(--purple);
}

.delivered .order-icon {
    background: #ecfdf5;
    color: #16a34a;
}

.delivered::before {
    background: #16a34a;
}

.completed .order-icon {
    background: #dff8ec;
    color: #047857;
}

.completed::before {
    background: #047857;
}

.returned .order-icon {
    background: var(--red-light);
    color: var(--red);
}

.returned::before {
    background: var(--red);
}


/* =========================================================
   TOTAL ORDERS
========================================================= */

.total-order-card {
    background:
        linear-gradient(135deg,
            #0f5132,
            #087a4d,
            #059669);

    border: none;
    color: #fff;
}

.total-order-card .order-icon {
    background: rgba(255, 255, 255, .15);
    color: #fff;
}

.total-order-card .order-label,
.total-order-card .order-count {
    color: #fff;
}


/* =========================================================
   PAYMENT
========================================================= */

.payment-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.payment-card {
    padding: 18px;
}

.payment-card-header {
    display: flex;
    align-items: center;
    gap: 12px;

    margin-bottom: 18px;
}

.payment-icon {
    width: 44px;
    height: 44px;

    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #eff8ff;
    color: #0284c7;
}

.payment-icon svg {
    width: 21px;
    height: 21px;
}

.payment-mode {
    display: block;

    color: var(--text);
    font-size: 14px;
    font-weight: 700;

    text-transform: capitalize;
}

.payment-total {
    display: block;

    margin-top: 3px;

    color: var(--muted);
    font-size: 11px;
}

.payment-status-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.payment-status-link {
    text-decoration: none !important;
    color: inherit !important;
}

.payment-status {
    min-height: 52px;
    padding: 10px 12px;

    border-radius: 10px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    transition: .2s;
    cursor: pointer;
}

.payment-status:hover {
    transform: translateY(-2px);
}

.payment-status-label {
    font-size: 11px;
    font-weight: 600;
}

.payment-status strong {
    font-size: 18px;
    font-weight: 750;
}

.pending-status {
    background: var(--orange-light);
    color: var(--orange);
}

.paid-status {
    background: var(--primary-light);
    color: var(--primary);
}

.payment-empty {
    padding: 25px;

    color: var(--muted);
    text-align: center;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:1250px) {

    .admin-order-grid,
    .staff-order-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .payment-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width:950px) {

    .attendance-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .sales-grid,
    .admin-order-grid,
    .staff-order-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .payment-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width:650px) {

    .content-wrapper {
        padding: 15px;
    }

    .dashboard-header {
        align-items: flex-start;
    }

    .dashboard-date {
        display: none;
    }

    .attendance-grid,
    .sales-grid,
    .admin-order-grid,
    .staff-order-grid,
    .payment-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

<section class="content-wrapper">

    <div class="dashboard-container">


        

        <div class="dashboard-header">

            <div class="welcome-area">

                <div class="welcome-avatar">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>

                <div>

                    <h1>
                        Welcome, <?php echo e($user->name); ?>!
                    </h1>

                    <p>

                        <?php if($isAdminDashboard): ?>

                        Complete sales, order and payment lifecycle overview.

                        <?php else: ?>

                        Here's your sales, order and payment overview for today.

                        <?php endif; ?>

                    </p>

                </div>

            </div>


            <div class="dashboard-date">

                <?php echo e(now()->format('D, d M Y')); ?>


            </div>

        </div>


        

        <div class="attendance-grid">


            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.check-in')): ?>

            <div class="dashboard-card attendance-card">

                <div class="attendance-icon green">

                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4" />

                    </svg>

                </div>


                <div class="attendance-details">

                    <span class="attendance-label">
                        Check In Time
                    </span>

                    <span class="attendance-value green">
                        <?php echo e($checkInTime?->format('h:i A') ?? '--:-- --'); ?>

                    </span>

                    <span class="attendance-subtext">
                        <?php echo e($todayLog?->work_date?->format('d M Y') ?? now()->format('d M Y')); ?>

                    </span>

                </div>

            </div>

            <?php endif; ?>


            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.check-out')): ?>

            <div class="dashboard-card attendance-card">

                <div class="attendance-icon orange">

                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                    </svg>

                </div>


                <div class="attendance-details">

                    <span class="attendance-label">
                        Check Out Time
                    </span>

                    <span class="attendance-value <?php echo e($checkOutTime ? 'orange' : 'gray'); ?>">
                        <?php echo e($checkOutTime?->format('h:i A') ?? '--:-- --'); ?>

                    </span>

                    <span class="attendance-subtext orange">
                        <?php echo e($checkOutTime ? 'Checked Out' : 'Not Checked Out'); ?>

                    </span>

                </div>

            </div>

            <?php endif; ?>


            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.work-status')): ?>

            <div class="dashboard-card attendance-card">

                <div class="attendance-icon purple">

                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 3 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />

                    </svg>

                </div>


                <div class="attendance-details">

                    <span class="attendance-label">
                        Work Status
                    </span>

                    <span class="attendance-value
                        <?php echo e($workStatus === 'Checked In'
                            ? 'green'
                            : ($workStatus === 'Checked Out'
                                ? 'orange'
                                : 'gray')); ?>">

                        <?php echo e($workStatus); ?>


                    </span>

                    <span class="attendance-subtext">
                        <?php echo e($workStatusSubtext); ?>

                    </span>

                </div>

            </div>

            <?php endif; ?>

        </div>


        

        <div class="dashboard-section">

            <div class="section-header">

                <div class="section-title-wrap">

                    <span class="section-indicator"></span>

                    <h2 class="section-title">
                        Sales Overview
                    </h2>

                </div>

                <p class="section-subtitle">
                    Click a card to view related information.
                </p>

            </div>


            <div class="sales-grid">


                

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.view')): ?>

                <a href="<?php echo e(route('admin.customers.index')); ?>" class="card-link">

                    <div class="dashboard-card sales-card clickable-card">

                        <div class="sales-icon customers">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                       M17 20H7m10 0v-2
                                       M7 20H2v-2a3 3 0 015.356-1.857
                                       M15 7a3 3 0 11-6 0
                                       3 3 0 016 0z" />

                            </svg>

                        </div>

                        <div>

                            <span class="sales-label">
                                Total Customers
                            </span>

                            <span class="sales-value">
                                <?php echo e(number_format($totalCustomers)); ?>

                            </span>

                            <div class="sales-caption">
                                Click to view customers
                            </div>

                        </div>

                    </div>

                </a>

                <?php endif; ?>


                

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.view')): ?>

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'date' => now()->toDateString()
                ])); ?>" class="card-link">

                    <div class="dashboard-card sales-card clickable-card">

                        <div class="sales-icon today">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                       3 .895 3 2-1.343 2-3 2
                                       m0-10V6m0 12v-2
                                       m9-4a9 9 0 11-18 0
                                       9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div>

                            <span class="sales-label">
                                Today's Sales
                            </span>

                            <span class="sales-value">
                                ₹<?php echo e(number_format($todaysSalesValue, 2)); ?>

                            </span>

                            <div class="sales-caption">
                                Click to view today's orders
                            </div>

                        </div>

                    </div>

                </a>

                <?php endif; ?>


                

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.view')): ?>

                <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="card-link">

                    <div class="dashboard-card sales-card clickable-card">

                        <div class="sales-icon total">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18
                                       M7 16l4-4 3 3 5-6" />

                            </svg>

                        </div>

                        <div>

                            <span class="sales-label">
                                Total Sales
                            </span>

                            <span class="sales-value">
                                ₹<?php echo e(number_format($totalSalesValue, 2)); ?>

                            </span>

                            <div class="sales-caption">
                                Click to view all sales
                            </div>

                        </div>

                    </div>

                </a>

                <?php endif; ?>

            </div>

        </div>


        

        <div class="dashboard-section">

            <div class="section-header">

                <div class="section-title-wrap">

                    <span class="section-indicator"></span>

                    <h2 class="section-title">

                        <?php echo e($isAdminDashboard
                            ? 'Order Lifecycle'
                            : 'Order Overview'); ?>


                    </h2>

                </div>

                <p class="section-subtitle">
                    Click a status card to view corresponding orders.
                </p>

            </div>


            <div class="<?php echo e($isAdminDashboard
                ? 'admin-order-grid'
                : 'staff-order-grid'); ?>">


                

                <?php if($isAdminDashboard): ?>

                <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="card-link">

                    <div class="dashboard-card order-card total-order-card clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12
                                       a2 2 0 002 2h10
                                       a2 2 0 002-2V7
                                       a2 2 0 00-2-2h-2
                                       M9 5a3 3 0 016 0" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Total Orders
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($totalOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>

                <?php endif; ?>


                

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'status' => 'pending'
                ])); ?>" class="card-link">

                    <div class="dashboard-card order-card pending clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3
                                       a9 9 0 11-18 0
                                       9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Pending
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($pendingOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>


                

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'status' => 'approved'
                ])); ?>" class="card-link">

                    <div class="dashboard-card order-card approved clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4
                                       m6 2a9 9 0 11-18 0
                                       9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Approved
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($approvedOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>


                

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'status' => 'dispatched'
                ])); ?>" class="card-link">

                    <div class="dashboard-card order-card dispatched clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h11v10H3V7
                                       zm11 3h4l3 3v4h-7v-7z" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Dispatched
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($dispatchedOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>


                

                <?php if($isAdminDashboard): ?>

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'status' => 'shipped'
                ])); ?>" class="card-link">

                    <div class="dashboard-card order-card shipped clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M12 5l7 7-7 7" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Shipped
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($shippedOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>

                <?php endif; ?>


                

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'status' => 'delivered'
                ])); ?>" class="card-link">

                    <div class="dashboard-card order-card delivered clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Delivered
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($deliveredOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>


                

                <?php if($isAdminDashboard): ?>

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'status' => 'completed'
                ])); ?>" class="card-link">

                    <div class="dashboard-card order-card completed clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4
                                       m6 2a9 9 0 11-18 0
                                       9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Completed
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($completedOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>

                <?php endif; ?>


                

                <a href="<?php echo e(route('admin.salesorders.index', [
                    'status' => 'returned'
                ])); ?>" class="card-link">

                    <div class="dashboard-card order-card returned clickable-card">

                        <div class="order-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l-4-4 4-4
                                       M5 10h10a4 4 0 014 4v1" />

                            </svg>

                        </div>

                        <div class="order-info">

                            <span class="order-label">
                                Returned
                            </span>

                            <span class="order-count">
                                <?php echo e(number_format($returnedOrders)); ?>

                            </span>

                        </div>

                    </div>

                </a>

            </div>

        </div>


        

        <div class="dashboard-section">

            <div class="section-header">

                <div class="section-title-wrap">

                    <span class="section-indicator"></span>

                    <h2 class="section-title">
                        Payment Overview
                    </h2>

                </div>

                <p class="section-subtitle">
                    Click payment status to view corresponding orders.
                </p>

            </div>


            <div class="payment-grid">

                <?php $__empty_1 = true; $__currentLoopData = $paymentOverview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <div class="dashboard-card payment-card">


                    

                    <a href="<?php echo e(route('admin.payment-management.index', [
                        'payment_mode' => $mode
                    ])); ?>" class="card-link">

                        <div class="payment-card-header clickable-card">

                            <div class="payment-icon">

                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 7h20
                                           M5 11h2m-2 4h4
                                           m9-4h1m-1 4h1
                                           M4 5h16a2 2 0 012 2v10
                                           a2 2 0 01-2 2H4
                                           a2 2 0 01-2-2V7
                                           a2 2 0 012-2z" />

                                </svg>

                            </div>

                            <div>

                                <span class="payment-mode">
                                    <?php echo e($mode); ?>

                                </span>

                                <span class="payment-total">

                                    <?php echo e(number_format($payment['total'])); ?>


                                    <?php echo e($payment['total'] == 1
                                        ? 'Order'
                                        : 'Orders'); ?>


                                </span>

                            </div>

                        </div>

                    </a>


                    <div class="payment-status-row">


                        

                        <a href="<?php echo e(route('admin.payment-management.index', [
                            'payment_mode' => $mode,
                            'payment_status' => 'pending'
                        ])); ?>" class="payment-status-link">

                            <div class="payment-status pending-status">

                                <span class="payment-status-label">
                                    Pending
                                </span>

                                <strong>
                                    <?php echo e(number_format($payment['pending'])); ?>

                                </strong>

                            </div>

                        </a>


                        

                        <a href="<?php echo e(route('admin.payment-management.index', [
                            'payment_mode' => $mode,
                            'payment_status' => 'paid'
                        ])); ?>" class="payment-status-link">

                            <div class="payment-status paid-status">

                                <span class="payment-status-label">
                                    Paid
                                </span>

                                <strong>
                                    <?php echo e(number_format($payment['paid'])); ?>

                                </strong>

                            </div>

                        </a>

                    </div>

                </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <div class="dashboard-card payment-empty">
                    No payment data available.
                </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/dashboard.blade.php ENDPATH**/ ?>