<?php $__env->startPush('styles'); ?>
<style>
/* Global Dashboard Reset & Typography */
.content-wrapper {
    background-color: #f4f8f5;
    min-height: 100vh;
    padding: 24px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1e293b;
    box-sizing: border-box;
}

.dashboard-container {
    max-width: 100%;
    margin: 0 auto;
}

/* Page Header */
.dashboard-header {
    margin-bottom: 24px;
}

.dashboard-header h1 {
    font-size: 26px;
    font-weight: 700;
    color: #0f5132;
    margin: 0 0 4px 0;
    letter-spacing: -0.3px;
}

.dashboard-header p {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

/* Common Card Styling */
.dashboard-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.dashboard-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f5132;
    margin: 0 0 16px 0;
}

/* Grid Layouts */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.middle-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

.bottom-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 20px;
}

/* Top Stat Cards */
.stat-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 24px;
    height: 24px;
}

.stat-icon.green {
    background-color: #059669;
    color: #ffffff;
}

.stat-icon.orange {
    background-color: #f97316;
    color: #ffffff;
}

.stat-icon.blue {
    background-color: #0284c7;
    color: #ffffff;
}

.stat-icon.purple {
    background-color: #7c3aed;
    color: #ffffff;
}

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
}

.stat-value.green {
    color: #059669;
}

.stat-value.orange {
    color: #f97316;
}

.stat-value.blue {
    color: #0284c7;
}

.stat-value.gray {
    color: #94a3b8;
}

.stat-subtext {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 4px;
    font-weight: 500;
}

.stat-subtext.orange {
    color: #f97316;
}

/* Check In / Check Out Card */
.status-badge-pill {
    display: inline-block;
    background-color: #e8f5e9;
    color: #059669;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    margin-bottom: 20px;
}

.check-times-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
}

.check-time-box {
    display: flex;
    flex-direction: column;
}

.check-time-box.has-border {
    border-left: 1px dashed #e2e8f0;
    padding-left: 16px;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.btn-action-solid {
    background-color: #059669;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-action-solid:hover {
    background-color: #047857;
}

.btn-action-outline {
    background-color: transparent;
    color: #059669;
    border: 1px solid #059669;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-action-outline:hover {
    background-color: #f0fdf4;
}

/* Today's Summary Card */
.summary-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.summary-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.summary-icon-box {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.summary-icon-box.green {
    background-color: #e8f5e9;
    color: #059669;
}

.summary-icon-box.orange {
    background-color: #fff3e0;
    color: #f97316;
}

.summary-title {
    font-size: 14px;
    font-weight: 500;
    color: #334155;
}

.summary-count {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
}

/* My Orders Card & Donut Chart */
.orders-chart-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 16px;
    margin: 10px 0 20px 0;
}

.donut-chart-container {
    position: relative;
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: conic-gradient(#059669 0% 75%, #86efac 75% 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.donut-chart-center {
    width: 90px;
    height: 90px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.donut-chart-center .number {
    font-size: 22px;
    font-weight: 700;
    color: #0f5132;
    line-height: 1;
}

.donut-chart-center .label {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}

.chart-legend {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.legend-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-top: 4px;
}

.legend-dot.completed {
    background-color: #059669;
}

.legend-dot.pending {
    background-color: #86efac;
}

.legend-text strong {
    display: block;
    font-size: 13px;
    color: #1e293b;
}

.legend-text span {
    font-size: 12px;
    color: #64748b;
}

.btn-full-width {
    width: 100%;
    text-align: center;
    justify-content: center;
}

/* Table Styling for Recent Orders */
.orders-table-wrapper {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

.orders-table th {
    background-color: #f8faf8;
    color: #475569;
    font-size: 13px;
    font-weight: 600;
    padding: 12px 14px;
    border-bottom: 1px solid #e2e8f0;
}

.orders-table td {
    padding: 12px 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}

.orders-table tr:last-child td {
    border-bottom: none;
}

.status-pill-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-pill-badge.completed {
    border: 1px solid #059669;
    color: #059669;
    background-color: #ffffff;
}

.status-pill-badge.pending {
    border: 1px solid #f97316;
    color: #f97316;
    background-color: #ffffff;
}

/* Timeline Layout for Today's Schedule */
.schedule-timeline {
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: relative;
    padding-left: 24px;
}

.schedule-timeline::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 6px;
    bottom: 6px;
    width: 2px;
    background-color: #e2e8f0;
}

.timeline-item {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
}

.timeline-dot {
    position: absolute;
    left: -24px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #059669;
    border: 2px solid #ffffff;
    box-shadow: 0 0 0 2px #e8f5e9;
}

.timeline-dot.orange {
    background-color: #f97316;
    box-shadow: 0 0 0 2px #fff3e0;
}

.timeline-time {
    font-weight: 600;
    color: #475569;
    min-width: 70px;
}

.timeline-desc {
    flex: 1;
    padding: 0 12px;
    color: #334155;
    font-weight: 500;
}

.timeline-desc.lunch {
    color: #f97316;
}

.timeline-status {
    font-weight: 600;
    color: #059669;
}

/* Responsive Breakpoints */
@media (max-width: 1100px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .middle-grid {
        grid-template-columns: 1fr;
    }

    .bottom-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .content-wrapper {
        padding: 16px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .check-times-container {
        grid-template-columns: 1fr;
    }

    .check-time-box.has-border {
        border-left: none;
        padding-left: 0;
        border-top: 1px dashed #e2e8f0;
        padding-top: 12px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .orders-chart-wrapper {
        flex-direction: column;
    }
}

/* Order Overview */
.order-overview {
    margin-bottom: 24px;
}

.order-overview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}

.order-overview-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f5132;
    margin: 0;
}

.order-overview-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 3px 0 0;
}

.order-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.order-stat-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    overflow: hidden;
    transition: all 0.2s ease;
}

.order-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
}

.order-stat-card::after {
    content: '';
    position: absolute;
    right: -20px;
    top: -20px;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    opacity: 0.08;
}

.order-stat-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.order-stat-icon svg {
    width: 23px;
    height: 23px;
}

.order-stat-content {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.order-stat-label {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
}

.order-stat-value {
    font-size: 24px;
    line-height: 1.2;
    font-weight: 700;
    color: #1e293b;
}

/* Approved */
.order-approved .order-stat-icon {
    background: #e8f5e9;
    color: #059669;
}

.order-approved::after {
    background: #059669;
}

/* Dispatched */
.order-dispatched .order-stat-icon {
    background: #e0f2fe;
    color: #0284c7;
}

.order-dispatched::after {
    background: #0284c7;
}

/* Pending */
.order-pending .order-stat-icon {
    background: #fff3e0;
    color: #f97316;
}

.order-pending::after {
    background: #f97316;
}

/* Delivered */
.order-delivered .order-stat-icon {
    background: #ede9fe;
    color: #7c3aed;
}

.order-delivered::after {
    background: #7c3aed;
}

@media (max-width: 1100px) {
    .order-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .order-stats-grid {
        grid-template-columns: 1fr;
    }

    .order-stat-card {
        padding: 16px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section class="content-wrapper">
    <div class="dashboard-container">

        <!-- Welcome Header -->
        <div class="dashboard-header">
            <h1>Welcome, <?php echo e($user->name); ?>!</h1>
            <p>Here's your work overview for today.</p>
        </div>


        <!-- Top Stat Cards (4 Columns) -->
        <div class="stats-grid">
            <!-- Check In Time -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.check-in')): ?>
            <div class="dashboard-card stat-card">


                
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4" />
                    </svg>
                </div>

                <div class="stat-details">
                    <span class="stat-value green">
                        <?php echo e($checkInTime?->format('h:i A') ?? '--:-- --'); ?>

                    </span>

                    <span class="stat-subtext">
                        <?php echo e($todayLog?->work_date?->format('d M Y') ?? now()->format('d M Y')); ?>

                    </span>
                </div>

                <?php endif; ?>

            </div>
            <!-- Check Out Time -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.check-out')): ?>
            <div class="dashboard-card stat-card">
                <div class="stat-icon orange">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <div class="stat-details">
                    <!-- Check Out -->
                    <span class="stat-value <?php echo e($checkOutTime ? 'orange' : 'gray'); ?>">
                        <?php echo e($checkOutTime?->format('h:i A') ?? '--:-- --'); ?>

                    </span>

                    <span class="stat-subtext orange">
                        <?php echo e($checkOutTime ? 'Checked Out' : 'Not Checked Out'); ?>

                    </span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Total Working Hours -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.working-hours')): ?>
            <div class="dashboard-card stat-card">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-details">
                    <!-- Total Working Hours -->
                    <span class="stat-value blue">
                        <?php echo e($totalWorkingHours); ?> Hrs
                    </span>

                    <span class="stat-subtext">
                        Today
                    </span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Work Status -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.work-status')): ?>
            <div class="dashboard-card stat-card">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div class="stat-details">
                    <!-- Work Status -->
                    <span
                        class="stat-value <?php echo e($workStatus === 'Checked In' ? 'green' : ($workStatus === 'Checked Out' ? 'orange' : 'gray')); ?>">
                        <?php echo e($workStatus); ?>

                    </span>

                    <span class="stat-subtext">
                        <?php echo e($workStatusSubtext); ?>

                    </span>
                </div>
            </div>

        </div>
        <?php endif; ?>
        <!-- Middle Section (3 Cards) -->
        <div class="middle-grid">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.attendance')): ?>
            <!-- Check In / Check Out Card -->
            <div class="dashboard-card">
                <h2 class="card-title">Check In / Check Out</h2>

                <span class="status-badge-pill" id="statusPill">
                    Status : <?php echo e($workStatus); ?>

                </span>

                <div class="check-times-container">

                    <!-- Check In -->
                    <div class="check-time-box">
                        <span class="stat-label">Check In Time</span>

                        <span class="stat-value green" style="font-size: 18px;">
                            <?php echo e($checkInTime?->format('h:i A') ?? '--:-- --'); ?>

                        </span>

                        <span class="stat-subtext">
                            <?php echo e($todayLog?->work_date?->format('d M Y') ?? now()->format('d M Y')); ?>

                        </span>
                    </div>


                    <!-- Check Out -->
                    <div class="check-time-box has-border">
                        <span class="stat-label">Check Out Time</span>

                        <span class="stat-value <?php echo e($checkOutTime ? 'orange' : 'gray'); ?>" style="font-size: 18px;"
                            id="checkOutBoxValue">
                            <?php echo e($checkOutTime?->format('h:i A') ?? '--:-- --'); ?>

                        </span>
                    </div>

                </div>


                <div class="action-buttons">

                    <!-- Check Out Button -->
                    <?php if($todayLog && $checkInTime && !$checkOutTime): ?>
                    <button class="btn-action-solid" id="btnCheckOut">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>

                        <span>Check Out</span>
                    </button>
                    <?php elseif($checkOutTime): ?>
                    <button class="btn-action-solid" disabled>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>

                        <span>Checked Out</span>
                    </button>
                    <?php else: ?>
                    <button class="btn-action-solid" disabled>
                        <span>Not Checked In</span>
                    </button>
                    <?php endif; ?>


                    <!-- View Attendance -->
                    <button class="btn-action-outline" id="btnViewAttendance">
                        View Attendance
                    </button>

                </div>
            </div>
            <?php endif; ?>



            <!-- Today's Summary Card -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.summary')): ?>
            <div class="dashboard-card">
                <h2 class="card-title">Today's Summary</h2>

                <div class="summary-list">
                    <div class="summary-item">
                        <div class="summary-left">
                            <div class="summary-icon-box green">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="summary-title">Orders Taken</span>
                        </div>
                        <span class="summary-count">
                            <?php echo e($summary['ordersTaken']); ?>

                        </span>
                    </div>

                    <div class="summary-item">
                        <div class="summary-left">
                            <div class="summary-icon-box green">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="summary-title">Orders Completed</span>
                        </div>
                        <span class="summary-count"> <?php echo e($summary['ordersCompleted']); ?></span>
                    </div>

                    <div class="summary-item">
                        <div class="summary-left">
                            <div class="summary-icon-box orange">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="summary-title">Pending Orders</span>
                        </div>
                        <span class="summary-count"> <?php echo e($summary['pendingOrders']); ?></span>
                    </div>

                    <div class="summary-item">
                        <div class="summary-left">
                            <div class="summary-icon-box green">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="summary-title">Customers Visited</span>
                        </div>
                        <span class="summary-count"><?php echo e($summary['customersVisited']); ?></span>
                    </div>

                    <div class="summary-item">
                        <div class="summary-left">
                            <div class="summary-icon-box green">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="summary-title">Reports Submitted</span>
                        </div>
                        <span class="summary-count"><?php echo e($summary['reportsSubmitted']); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- My Orders Card -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.my-orders')): ?>
            <div class="dashboard-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h2 class="card-title">My Orders</h2>

                    <div class="orders-chart-wrapper">
                        <!-- Conic Gradient Donut Chart -->
                        <div class="donut-chart-container">
                            <div class="donut-chart-center">
                                <span class="number">8</span>
                                <span class="label">Total</span>
                            </div>
                        </div>

                        <!-- Chart Legend -->
                        <div class="chart-legend">
                            <div class="legend-item">
                                <div class="legend-dot completed"></div>
                                <div class="legend-text">
                                    <strong>Completed</strong>
                                    <span>6 (75%)</span>
                                </div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot pending"></div>
                                <div class="legend-text">
                                    <strong>Pending</strong>
                                    <span>2 (25%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn-action-outline btn-full-width" id="btnViewOrders">View All Orders</button>
            </div>
            <?php endif; ?>

        </div>




        <!-- Bottom Section (Recent Orders & Schedule) -->
        <div class="bottom-grid">

            <!-- Recent Orders Table -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.recent-orders')): ?>
            <div class="dashboard-card">
                <h2 class="card-title">Recent Orders</h2>

                <div class="orders-table-wrapper">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>ORD00123</strong></td>
                                <td>Rahul Kumar</td>
                                <td><span class="status-pill-badge completed">Completed</span></td>
                                <td>11 Aug 2025</td>
                            </tr>
                            <tr>
                                <td><strong>ORD00122</strong></td>
                                <td>Anita Sharma</td>
                                <td><span class="status-pill-badge completed">Completed</span></td>
                                <td>11 Aug 2025</td>
                            </tr>
                            <tr>
                                <td><strong>ORD00121</strong></td>
                                <td>Vijay Singh</td>
                                <td><span class="status-pill-badge pending">Pending</span></td>
                                <td>11 Aug 2025</td>
                            </tr>
                            <tr>
                                <td><strong>ORD00120</strong></td>
                                <td>Neha Verma</td>
                                <td><span class="status-pill-badge completed">Completed</span></td>
                                <td>10 Aug 2025</td>
                            </tr>
                            <tr>
                                <td><strong>ORD00119</strong></td>
                                <td>Mohit Patel</td>
                                <td><span class="status-pill-badge pending">Pending</span></td>
                                <td>10 Aug 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Today's Schedule -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.schedule')): ?>
            <div class="dashboard-card">
                <h2 class="card-title">Today's Schedule</h2>

                <div class="schedule-timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <span class="timeline-time">09:15 AM</span>
                        <span class="timeline-desc timeline-status">Checked In</span>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <span class="timeline-time">10:00 AM</span>
                        <span class="timeline-desc">Customer Visit - Rahul Kumar</span>
                        <span class="timeline-status">Completed</span>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <span class="timeline-time">11:30 AM</span>
                        <span class="timeline-desc">Customer Visit - Anita Sharma</span>
                        <span class="timeline-status">Completed</span>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot orange"></div>
                        <span class="timeline-time">01:00 PM</span>
                        <span class="timeline-desc lunch">Lunch Break</span>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <span class="timeline-time">02:00 PM</span>
                        <span class="timeline-desc">Customer Visit - Vijay Singh</span>
                        <span class="timeline-status">Completed</span>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <span class="timeline-time">04:00 PM</span>
                        <span class="timeline-desc">Customer Visit - Neha Varma</span>
                        <span class="timeline-status">Completed</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>


    <!-- Order Overview Card Section -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['dashboard.sales-card-pending', 'dashboard.sales-card-approved', 'dashboard.sales-card-dispatched',
    'dashboard.sales-card-delivered'])): ?>
    <div class="order-overview">

        <div class="order-overview-header">
            <div>
                <h2 class="order-overview-title">Order Overview</h2>
                <p class="order-overview-subtitle">
                    Current order status at a glance
                </p>
            </div>
        </div>

        <div class="order-stats-grid">

            <!-- Pending Orders -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.sales-card-pending')): ?>
            <div class="order-stat-card order-pending">
                <a href="<?php echo e(route('admin.salesorders.index', [
                            'search' => '',
                            'start_date' => '',
                            'end_date' => '',
                            'payment_status' => '',
                            'order_status' => 'pending',
                        ])); ?>">

                    <div class="order-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                                                                                   9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div class="order-stat-content">
                        <span class="order-stat-label">Pending Orders</span>
                        <span class="order-stat-value">
                            <?php echo e($pendingOrders); ?>

                        </span>
                    </div>
                </a>

            </div>
            <?php endif; ?>
            <!-- Approved Orders -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.sales-card-approved')): ?>
            <div class="order-stat-card order-approved">
                <a href="<?php echo e(route('admin.salesorders.index', [
                            'search' => '',
                            'start_date' => '',
                            'end_date' => '',
                            'payment_status' => '',
                            'order_status' => 'approved',
                        ])); ?>">
                    <div class="order-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0
                                                                                   9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div class="order-stat-content">
                        <span class="order-stat-label">Approved Orders</span>
                        <span class="order-stat-value">
                            <?php echo e($approvedOrders); ?>

                        </span>
                    </div>
                </a>

            </div>
            <?php endif; ?>

            <!-- Dispatched Orders -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.sales-card-dispatched')): ?>
            <div class="order-stat-card order-dispatched">

                <a href="<?php echo e(route('admin.salesorders.index', [
                            'search' => '',
                            'start_date' => '',
                            'end_date' => '',
                            'payment_status' => '',
                            'order_status' => 'dispatched',
                        ])); ?>">

                    <div class="order-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h11v10H3V7zm11 3h4l3 3v4h-7v-7z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 17a2 2 0 104 0m-13 0a2 2 0 104 0" />
                        </svg>
                    </div>

                    <div class="order-stat-content">
                        <span class="order-stat-label">Dispatched Orders</span>
                        <span class="order-stat-value">
                            <?php echo e($dispatchedOrders); ?>

                        </span>
                    </div>
                </a>

            </div>
            <?php endif; ?>


            <!-- Delivered Orders -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard.sales-card-delivered')): ?>
            <div class="order-stat-card order-delivered">
                <a href="<?php echo e(route('admin.salesorders.index', [
                            'search' => '',
                            'start_date' => '',
                            'end_date' => '',
                            'payment_status' => '',
                            'order_status' => 'delivered',
                        ])); ?>">

                    <div class="order-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div class="order-stat-content">
                        <span class="order-stat-label">Delivered Orders</span>
                        <span class="order-stat-value">
                            <?php echo e($deliveredOrders); ?>

                        </span>
                    </div>
                </a>

            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <!-- end - Order Overview Card Section -->
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Check Out functionality demo
    const btnCheckOut = document.getElementById('btnCheckOut');
    const checkOutDisplay = document.getElementById('checkOutDisplay');
    const checkOutBoxValue = document.getElementById('checkOutBoxValue');
    const checkOutSubtext = document.getElementById('checkOutSubtext');
    const workStatusDisplay = document.getElementById('workStatusDisplay');
    const statusPill = document.getElementById('statusPill');


    let isCheckedOut = false;

    btnCheckOut.addEventListener('click', function() {
        isCheckedOut = !isCheckedOut;

        if (isCheckedOut) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            checkOutDisplay.textContent = timeStr;
            checkOutDisplay.classList.remove('gray');
            checkOutDisplay.classList.add('orange');

            checkOutBoxValue.textContent = timeStr;
            checkOutBoxValue.classList.remove('gray');
            checkOutBoxValue.classList.add('orange');

            checkOutSubtext.textContent = 'Checked Out';
            checkOutSubtext.style.color = '#059669';

            workStatusDisplay.textContent = 'Checked Out';
            workStatusDisplay.className = 'stat-value orange';

            statusPill.textContent = 'Status : Checked Out';
            statusPill.style.backgroundColor = '#fff3e0';
            statusPill.style.color = '#f97316';

            btnCheckOut.querySelector('span').textContent = 'Checked Out';
            btnCheckOut.style.backgroundColor = '#64748b';
        } else {
            checkOutDisplay.textContent = '--:-- --';
            checkOutDisplay.className = 'stat-value gray';

            checkOutBoxValue.textContent = '--:-- --';
            checkOutBoxValue.className = 'stat-value gray';

            checkOutSubtext.textContent = 'Not Checked Out';
            checkOutSubtext.style.color = '#f97316';

            workStatusDisplay.textContent = 'Checked In';
            workStatusDisplay.className = 'stat-value green';

            statusPill.textContent = 'Status : Checked In';
            statusPill.style.backgroundColor = '#e8f5e9';
            statusPill.style.color = '#059669';

            btnCheckOut.querySelector('span').textContent = 'Check Out';
            btnCheckOut.style.backgroundColor = '#059669';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/dashboard.blade.php ENDPATH**/ ?>