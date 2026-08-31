@extends('layouts.app')

@push('styles')
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
        radial-gradient(circle at top right, rgba(5, 150, 105, .05), transparent 28%),
        var(--background);
    color: var(--text);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.dashboard-container {
    max-width: 1500px;
    margin: 0 auto;
}

/* ============================================================
   HEADER
============================================================ */

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
    flex-shrink: 0;

    background: linear-gradient(135deg, #0f5132, #059669);
    color: #fff;
    font-size: 20px;
    font-weight: 700;

    box-shadow: 0 8px 18px rgba(5, 150, 105, .20);
}

.dashboard-header h1 {
    margin: 0 0 5px;
    color: var(--brand);
    font-size: 27px;
    line-height: 1.2;
    font-weight: 750;
    letter-spacing: -.4px;
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
    background: rgba(255, 255, 255, .8);
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.dashboard-date svg {
    width: 16px;
    height: 16px;
    color: var(--primary);
}

/* ============================================================
   SECTION
============================================================ */

.dashboard-section {
    margin-bottom: 30px;
}

.section-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 15px;
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
    background: linear-gradient(to bottom, var(--brand), var(--primary));
}

.section-title {
    margin: 0;
    color: var(--text);
    font-size: 18px;
    font-weight: 750;
    letter-spacing: -.2px;
}

.section-subtitle {
    margin: 4px 0 0 14px;
    color: var(--muted);
    font-size: 12px;
}

/* ============================================================
   COMMON CARD
============================================================ */

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

/* ============================================================
   SALES / KPI
============================================================ */

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

.sales-card::after {
    content: "";
    position: absolute;
    width: 85px;
    height: 85px;
    right: -28px;
    top: -28px;
    border-radius: 50%;
    background: rgba(5, 150, 105, .035);
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
    font-weight: 550;
}

.sales-value {
    display: block;
    color: var(--text);
    font-size: 24px;
    line-height: 1.1;
    font-weight: 750;
    letter-spacing: -.4px;
}

.sales-caption {
    margin-top: 5px;
    color: var(--muted-light);
    font-size: 10px;
}

/* ============================================================
   ORDER OVERVIEW
============================================================ */

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
    line-height: 1;
    font-weight: 750;
}

/* ============================================================
   ORDER STATUS COLORS
============================================================ */

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

/* ============================================================
   TOTAL ORDER CARD
============================================================ */

.total-order-card {
    background: linear-gradient(135deg, #0f5132 0%, #087a4d 60%, #059669 100%);
    border: none;
    color: #fff;
}

.total-order-card:hover {
    border-color: transparent;
}

.total-order-card::after {
    content: "";
    position: absolute;
    width: 130px;
    height: 130px;
    right: -55px;
    bottom: -70px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .08);
}

.total-order-card::before {
    background: rgba(255, 255, 255, .7);
}

.total-order-card .order-icon {
    background: rgba(255, 255, 255, .15);
    color: #fff;
}

.total-order-card .order-label,
.total-order-card .order-count {
    color: #fff;
}

/* ============================================================
   PAYMENT OVERVIEW
============================================================ */

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
    min-width: 44px;
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

.payment-mode-info {
    min-width: 0;
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

.payment-status {
    min-height: 52px;
    padding: 10px 12px;

    border-radius: 10px;

    display: flex;
    align-items: center;
    justify-content: space-between;
}

.payment-status-label {
    font-size: 11px;
    font-weight: 600;
}

.payment-status strong {
    font-size: 18px;
    line-height: 1;
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
    font-size: 13px;
}

@media (max-width: 1250px) {
    .payment-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 650px) {
    .payment-grid {
        grid-template-columns: 1fr;
    }
}

/* ============================================================
   ATTENDANCE
============================================================ */

.attendance-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

.attendance-card {
    padding: 18px;
    min-height: 125px;
}

.attendance-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 13px;
}

.attendance-label {
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
}

.attendance-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #cbd5cf;
}

.attendance-dot.green {
    background: var(--primary);
    box-shadow: 0 0 0 4px rgba(5, 150, 105, .10);
}

.attendance-dot.orange {
    background: var(--orange);
    box-shadow: 0 0 0 4px rgba(249, 115, 22, .10);
}

.attendance-dot.blue {
    background: var(--blue);
    box-shadow: 0 0 0 4px rgba(2, 132, 199, .10);
}

.attendance-value {
    display: block;
    color: var(--text);
    font-size: 21px;
    line-height: 1.1;
    font-weight: 750;
}

.attendance-value.green {
    color: var(--primary);
}

.attendance-value.orange {
    color: var(--orange);
}

.attendance-value.blue {
    color: var(--blue);
}

.attendance-subtext {
    display: block;
    margin-top: 6px;
    color: var(--muted-light);
    font-size: 11px;
}

/* ============================================================
   ATTENDANCE ACTION
============================================================ */

.attendance-action-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-top: 15px;
    padding: 18px 20px;
}

.attendance-action-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.attendance-action-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: var(--brand-light);
    color: var(--primary);

    display: flex;
    align-items: center;
    justify-content: center;
}

.attendance-action-icon svg {
    width: 20px;
    height: 20px;
}

.attendance-action-title {
    margin: 0 0 3px;
    color: var(--text);
    font-size: 13px;
    font-weight: 700;
}

.attendance-action-subtitle {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
}

.attendance-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    border-radius: 9px;
    padding: 9px 15px;

    font-size: 12px;
    font-weight: 650;
    cursor: pointer;

    border: 1px solid transparent;
    transition: all .18s ease;
}

.btn-action svg {
    width: 15px;
    height: 15px;
}

.btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
    box-shadow: 0 4px 10px rgba(5, 150, 105, .18);
}

.btn-primary:hover {
    background: #047857;
    border-color: #047857;
    transform: translateY(-1px);
}

.btn-primary:disabled {
    background: #cbd5d0;
    border-color: #cbd5d0;
    box-shadow: none;
    cursor: not-allowed;
    transform: none;
}

.btn-secondary {
    background: #fff;
    color: var(--primary);
    border-color: #cde5d9;
}

.btn-secondary:hover {
    background: var(--brand-light);
}

/* ============================================================
   TODAY SUMMARY
============================================================ */

.summary-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
}

.summary-item {
    position: relative;
    padding: 16px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 13px;
    transition: all .2s ease;
}

.summary-item:hover {
    border-color: #d4e5dc;
    box-shadow: var(--shadow-sm);
    transform: translateY(-1px);
}

.summary-item::before {
    content: "";
    position: absolute;
    left: 0;
    top: 15px;
    bottom: 15px;
    width: 3px;
    border-radius: 0 4px 4px 0;
    background: #d1e7db;
}

.summary-label {
    display: block;
    margin-bottom: 7px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 550;
}

.summary-value {
    color: var(--text);
    font-size: 21px;
    font-weight: 750;
}

/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 1250px) {

    .admin-order-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .staff-order-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .payment-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .summary-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 950px) {

    .content-wrapper {
        padding: 22px;
    }

    .sales-grid,
    .attendance-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .admin-order-grid,
    .staff-order-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .payment-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .dashboard-header {
        align-items: flex-start;
    }

    .dashboard-date {
        display: none;
    }
}

@media (max-width: 650px) {

    .content-wrapper {
        padding: 15px;
    }

    .dashboard-header {
        margin-bottom: 24px;
    }

    .welcome-avatar {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        font-size: 17px;
    }

    .dashboard-header h1 {
        font-size: 22px;
    }

    .dashboard-header p {
        font-size: 12px;
    }

    .sales-grid,
    .attendance-grid,
    .admin-order-grid,
    .staff-order-grid,
    .payment-grid,
    .summary-grid {
        grid-template-columns: 1fr;
    }

    .section-title {
        font-size: 17px;
    }

    .attendance-action-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .attendance-actions {
        width: 100%;
    }

    .attendance-actions .btn-action {
        flex: 1;
    }

    .sales-card {
        min-height: 100px;
    }
}
</style>
@endpush


@section('content')

<section class="content-wrapper">

    <div class="dashboard-container">

        {{-- ============================================================
             HEADER
        ============================================================= --}}
        <div class="dashboard-header">

            <div class="welcome-area">

                <div class="welcome-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>

                    <h1>
                        Welcome, {{ $user->name }}!
                    </h1>

                    @if($isAdminDashboard)

                    <p>
                        Complete sales, order and payment lifecycle overview for the organisation.
                    </p>

                    @else

                    <p>
                        Here's your sales, order and payment overview for today.
                    </p>

                    @endif

                </div>

            </div>


            <div class="dashboard-date">

                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />

                </svg>

                {{ now()->format('D, d M Y') }}

            </div>

        </div>


        {{-- ============================================================
             ATTENDANCE
        ============================================================= --}}
        @if(!$isAdminDashboard)

        @canany([
        'dashboard.check-in',
        'dashboard.check-out',
        'dashboard.working-hours',
        'dashboard.work-status'
        ])

        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <div class="section-title-wrap">

                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            Attendance
                        </h2>

                    </div>

                    <p class="section-subtitle">
                        Your attendance and working status for today.
                    </p>

                </div>

            </div>


            <div class="attendance-grid">

                {{-- Check In --}}
                @can('dashboard.check-in')

                <div class="dashboard-card attendance-card">

                    <div class="attendance-top">

                        <span class="attendance-label">
                            Check In
                        </span>

                        <span class="attendance-dot green"></span>

                    </div>

                    <span class="attendance-value green">

                        {{ $checkInTime?->format('h:i A') ?? '--:-- --' }}

                    </span>

                    <span class="attendance-subtext">

                        {{ $todayLog?->work_date?->format('d M Y') ?? now()->format('d M Y') }}

                    </span>

                </div>

                @endcan


                {{-- Check Out --}}
                @can('dashboard.check-out')

                <div class="dashboard-card attendance-card">

                    <div class="attendance-top">

                        <span class="attendance-label">
                            Check Out
                        </span>

                        <span class="attendance-dot {{ $checkOutTime ? 'orange' : '' }}"></span>

                    </div>

                    <span class="attendance-value {{ $checkOutTime ? 'orange' : '' }}">

                        {{ $checkOutTime?->format('h:i A') ?? '--:-- --' }}

                    </span>

                    <span class="attendance-subtext">

                        {{ $checkOutTime ? 'Checked Out' : 'Not Checked Out' }}

                    </span>

                </div>

                @endcan


                {{-- Working Hours --}}
                @can('dashboard.working-hours')

                <div class="dashboard-card attendance-card">

                    <div class="attendance-top">

                        <span class="attendance-label">
                            Working Hours
                        </span>

                        <span class="attendance-dot blue"></span>

                    </div>

                    <span class="attendance-value blue">

                        {{ $totalWorkingHours }} Hrs

                    </span>

                    <span class="attendance-subtext">
                        Total time worked today
                    </span>

                </div>

                @endcan


                {{-- Work Status --}}
                @can('dashboard.work-status')

                <div class="dashboard-card attendance-card">

                    <div class="attendance-top">

                        <span class="attendance-label">
                            Work Status
                        </span>

                        <span class="attendance-dot
                                {{ $workStatus === 'Checked In'
                                    ? 'green'
                                    : ($workStatus === 'Checked Out' ? 'orange' : '') }}">
                        </span>

                    </div>

                    <span class="attendance-value
                            {{ $workStatus === 'Checked In'
                                ? 'green'
                                : ($workStatus === 'Checked Out' ? 'orange' : '') }}">

                        {{ $workStatus }}

                    </span>

                    <span class="attendance-subtext">
                        {{ $workStatusSubtext }}
                    </span>

                </div>

                @endcan

            </div>


            {{-- Attendance Actions --}}
            @can('dashboard.attendance')

            <div class="dashboard-card attendance-action-card">

                <div class="attendance-action-info">

                    <div class="attendance-action-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                    </div>

                    <div>

                        <p class="attendance-action-title">
                            Today's Attendance
                        </p>

                        <p class="attendance-action-subtitle">
                            Manage your attendance record for today.
                        </p>

                    </div>

                </div>


                <div class="attendance-actions">

                    @if($todayLog && $checkInTime && !$checkOutTime)

                    <button type="button" class="btn-action btn-primary" id="btnCheckOut">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />

                        </svg>

                        Check Out

                    </button>

                    @elseif($checkOutTime)

                    <button type="button" class="btn-action btn-primary" disabled>

                        ✓ Checked Out

                    </button>

                    @else

                    <button type="button" class="btn-action btn-primary" disabled>

                        Not Checked In

                    </button>

                    @endif


                    <button type="button" class="btn-action btn-secondary" id="btnViewAttendance">

                        View Attendance

                    </button>

                </div>

            </div>

            @endcan

        </div>

        @endcanany

        @endif


        {{-- ============================================================
             TODAY'S SUMMARY
        ============================================================= --}}
        @if(!$isAdminDashboard)

        @can('dashboard.summary')

        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <div class="section-title-wrap">

                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            Today's Summary
                        </h2>

                    </div>

                    <p class="section-subtitle">
                        Your activity summary for today.
                    </p>

                </div>

            </div>


            <div class="summary-grid">

                <div class="summary-item">

                    <span class="summary-label">
                        Orders Taken
                    </span>

                    <span class="summary-value">
                        {{ $summary['ordersTaken'] ?? 0 }}
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Orders Completed
                    </span>

                    <span class="summary-value">
                        {{ $summary['ordersCompleted'] ?? 0 }}
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Pending Orders
                    </span>

                    <span class="summary-value">
                        {{ $summary['pendingOrders'] ?? 0 }}
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Customers Visited
                    </span>

                    <span class="summary-value">
                        {{ $summary['customersVisited'] ?? 0 }}
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Reports Submitted
                    </span>

                    <span class="summary-value">
                        {{ $summary['reportsSubmitted'] ?? 0 }}
                    </span>

                </div>

            </div>

        </div>

        @endcan

        @endif


        {{-- ============================================================
             SALES OVERVIEW
        ============================================================= --}}
        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <div class="section-title-wrap">

                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            Sales Overview
                        </h2>

                    </div>

                    <p class="section-subtitle">

                        @if($isAdminDashboard)

                        Complete sales performance across the organisation.

                        @else

                        Sales performance within your assigned team.

                        @endif

                    </p>

                </div>

            </div>


            <div class="sales-grid">

                {{-- Total Customers --}}
                <div class="dashboard-card sales-card">

                    <div class="sales-icon customers">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                     M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                     M7 20H2v-2a3 3 0 015.356-1.857
                                     M7 20v-2c0-.656.126-1.283.356-1.857
                                     m0 0a5.002 5.002 0 019.288 0
                                     M15 7a3 3 0 11-6 0 3 3 0 016 0z" />

                        </svg>

                    </div>

                    <div>

                        <span class="sales-label">
                            Total Customers
                        </span>

                        <span class="sales-value">
                            {{ number_format($totalCustomers) }}
                        </span>

                        <div class="sales-caption">
                            Customer base
                        </div>

                    </div>

                </div>


                {{-- Today's Sales --}}
                <div class="dashboard-card sales-card">

                    <div class="sales-icon today">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                     3 .895 3 2-1.343 2-3 2
                                     m0-10V6m0 12v-2
                                     m9-4a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                    </div>

                    <div>

                        <span class="sales-label">
                            Today's Sales
                        </span>

                        <span class="sales-value">
                            ₹{{ number_format($todaysSalesValue, 2) }}
                        </span>

                        <div class="sales-caption">
                            Sales generated today
                        </div>

                    </div>

                </div>


                {{-- Total Sales --}}
                <div class="dashboard-card sales-card">

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
                            ₹{{ number_format($totalSalesValue, 2) }}
                        </span>

                        <div class="sales-caption">
                            Overall sales value
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             ORDER LIFECYCLE / OVERVIEW
        ============================================================= --}}
        @if($isAdminDashboard)

        {{-- ========================================================
                 ADMIN ORDER LIFECYCLE
            ========================================================= --}}

        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <div class="section-title-wrap">

                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            Order Lifecycle
                        </h2>

                    </div>

                    <p class="section-subtitle">
                        Complete order status across the organisation.
                    </p>

                </div>

            </div>


            <div class="admin-order-grid">

                {{-- Total --}}
                <div class="dashboard-card order-card total-order-card">

                    <div class="order-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12
                                         a2 2 0 002 2h10a2 2 0 002-2V7
                                         a2 2 0 00-2-2h-2
                                         M9 5a3 3 0 016 0" />

                        </svg>

                    </div>

                    <div class="order-info">

                        <span class="order-label">
                            Total Orders
                        </span>

                        <span class="order-count">
                            {{ $totalOrders }}
                        </span>

                    </div>

                </div>


                {{-- Pending --}}
                <div class="dashboard-card order-card pending">

                    <div class="order-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                                         9 9 0 0118 0z" />

                        </svg>

                    </div>

                    <div class="order-info">

                        <span class="order-label">
                            Pending
                        </span>

                        <span class="order-count">
                            {{ $pendingOrders }}
                        </span>

                    </div>

                </div>


                {{-- Approved --}}
                <div class="dashboard-card order-card approved">

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
                            {{ $approvedOrders }}
                        </span>

                    </div>

                </div>


                {{-- Dispatched --}}
                <div class="dashboard-card order-card dispatched">

                    <div class="order-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h11v10H3V7zm11 3h4l3 3v4h-7v-7z" />

                        </svg>

                    </div>

                    <div class="order-info">

                        <span class="order-label">
                            Dispatched
                        </span>

                        <span class="order-count">
                            {{ $dispatchedOrders }}
                        </span>

                    </div>

                </div>


                {{-- Shipped --}}
                <div class="dashboard-card order-card shipped">

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
                            {{ $shippedOrders }}
                        </span>

                    </div>

                </div>


                {{-- Delivered --}}
                <div class="dashboard-card order-card delivered">

                    <div class="order-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />

                        </svg>

                    </div>

                    <div class="order-info">

                        <span class="order-label">
                            Delivered
                        </span>

                        <span class="order-count">
                            {{ $deliveredOrders }}
                        </span>

                    </div>

                </div>


                {{-- Completed --}}
                <div class="dashboard-card order-card completed">

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
                            {{ $completedOrders }}
                        </span>

                    </div>

                </div>


                {{-- Returned --}}
                <div class="dashboard-card order-card returned">

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
                            {{ $returnedOrders }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

        @else

        {{-- ========================================================
                 STAFF ORDER OVERVIEW
            ========================================================= --}}

        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <div class="section-title-wrap">

                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            Order Overview
                        </h2>

                    </div>

                    <p class="section-subtitle">
                        Current order status for your assigned orders.
                    </p>

                </div>

            </div>


            <div class="staff-order-grid">

                {{-- Pending --}}
                <div class="dashboard-card order-card pending">

                    <div class="order-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                                         9 9 0 0118 0z" />

                        </svg>

                    </div>

                    <div class="order-info">

                        <span class="order-label">
                            Pending
                        </span>

                        <span class="order-count">
                            {{ $pendingOrders }}
                        </span>

                    </div>

                </div>


                {{-- Approved --}}
                <div class="dashboard-card order-card approved">

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
                            {{ $approvedOrders }}
                        </span>

                    </div>

                </div>


                {{-- Dispatched --}}
                <div class="dashboard-card order-card dispatched">

                    <div class="order-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h11v10H3V7zm11 3h4l3 3v4h-7v-7z" />

                        </svg>

                    </div>

                    <div class="order-info">

                        <span class="order-label">
                            Dispatched
                        </span>

                        <span class="order-count">
                            {{ $dispatchedOrders }}
                        </span>

                    </div>

                </div>


                {{-- Delivered --}}
                <div class="dashboard-card order-card delivered">

                    <div class="order-icon">

                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />

                        </svg>

                    </div>

                    <div class="order-info">

                        <span class="order-label">
                            Delivered
                        </span>

                        <span class="order-count">
                            {{ $deliveredOrders }}
                        </span>

                    </div>

                </div>


                {{-- Returned --}}
                <div class="dashboard-card order-card returned">

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
                            {{ $returnedOrders }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

        @endif

        {{-- ============================================================
     PAYMENT OVERVIEW
============================================================ --}}
        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <div class="section-title-wrap">
                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            Payment Overview
                        </h2>
                    </div>

                    <p class="section-subtitle">
                        Payment status grouped by mode of payment.
                    </p>

                </div>

            </div>


            <div class="payment-grid">

                @forelse($paymentOverview as $mode => $payment)

                <div class="dashboard-card payment-card">

                    {{-- Payment Mode --}}
                    <div class="payment-card-header">

                        <div class="payment-icon">

                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 7h20M5 11h2m-2 4h4m9-4h1m-1 4h1
                                     M4 5h16a2 2 0 012 2v10a2 2 0 01-2 2H4
                                     a2 2 0 01-2-2V7a2 2 0 012-2z" />

                            </svg>

                        </div>

                        <div class="payment-mode-info">

                            <span class="payment-mode">
                                {{ $mode }}
                            </span>

                            <span class="payment-total">
                                {{ number_format($payment['total']) }}
                                {{ $payment['total'] == 1 ? 'Order' : 'Orders' }}
                            </span>

                        </div>

                    </div>


                    {{-- Payment Status --}}
                    <div class="payment-status-row">

                        {{-- Pending --}}
                        <div class="payment-status pending-status">

                            <div>
                                <span class="payment-status-label">
                                    Pending
                                </span>
                            </div>

                            <strong>
                                {{ number_format($payment['pending']) }}
                            </strong>

                        </div>


                        {{-- Paid --}}
                        <div class="payment-status paid-status">

                            <div>
                                <span class="payment-status-label">
                                    Paid
                                </span>
                            </div>

                            <strong>
                                {{ number_format($payment['paid']) }}
                            </strong>

                        </div>

                    </div>

                </div>

                @empty

                <div class="dashboard-card payment-empty">

                    <span>No payment data available.</span>

                </div>

                @endforelse

            </div>

        </div>


    </div>

</section>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const btnCheckOut = document.getElementById('btnCheckOut');

    if (!btnCheckOut) {
        return;
    }

    btnCheckOut.addEventListener('click', function() {

        /*
         * Keep your actual checkout AJAX/API logic here.
         *
         * The previous JavaScript only changed the UI and did not
         * actually save checkout time to the database.
         */

        console.log('Check out clicked');

    });

});
</script>
@endpush