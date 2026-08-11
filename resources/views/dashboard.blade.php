@extends('layouts.app')

@push('styles')

<style>
:root {
    --primary-color: #4f46e5;
    --primary-light: #e0e7ff;

    --bg-page: #f8fafc;
    --bg-card: #ffffff;
    --border-color: #f1f5f9;
    --border-color-hover: #e2e8f0;

    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-muted: #94a3b8;

    --success-bg: #dcfce7;
    --success-text: #15803d;
    --danger-bg: #fee2e2;
    --danger-text: #b91c1c;
    --warning-bg: #fef3c7;
    --warning-text: #b45309;

    --radius-xl: 20px;
    --radius-lg: 16px;
    --radius-md: 12px;
    --radius-sm: 8px;

    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -4px rgba(0, 0, 0, 0.04);

    --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.content-wrapper {
    padding: 1.5rem;
    max-width: 1600px;
    margin: 0 auto;
    background-color: var(--bg-page);
    min-height: 100vh;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.welcome-header {
    margin-bottom: 2rem;
}

.welcome-header h1 {
    font-size: 1.875rem;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -0.025em;
    margin-bottom: 0.25rem;
}

.welcome-header p {
    font-size: 0.95rem;
    color: var(--text-secondary);
}

/* 3x3 Grid Layout */

.stats-grid-3x3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}


/* Base Stat Card Styles */
.stat-card {
    position: relative;
    overflow: hidden;
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: #ffffff;
    background: linear-gradient(135deg, #5A8D3A, #074E30);
    min-height: 150px;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #5A8D3A, #074E30);
    pointer-events: none;
}

.stat-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 180px;
    height: 180px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.06) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.12), 0 8px 12px -6px rgba(0, 0, 0, 0.12);
}

/* Custom Gradients for Cards */
.card-sales {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
}

.card-employees {
    background: linear-gradient(135deg, #0d9488 0%, #2563eb 100%);
}

.card-stores {
    background: linear-gradient(135deg, #172554 0%, #1e40af 100%);
}

.card-products {
    background: linear-gradient(135deg, #0f172a 0%, #6366f1 100%);
}

.card-incentives {
    background: linear-gradient(135deg, #4c1d95 0%, #b5179e 100%);
}

.card-vanitham-sales {
    background: linear-gradient(135deg, #1e1b4b 0%, #db2777 100%);
}

.card-centreal-sales {
    background: linear-gradient(135deg, #0f172a 0%, #0ea5e9 100%);
}

.card-vanitham-incentives {
    background: linear-gradient(135deg, #312e81 0%, #a855f7 100%);
}

.card-centreal-incentives {
    background: linear-gradient(135deg, #115e59 0%, #2563eb 100%);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    position: relative;
    z-index: 2;
}

.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    backdrop-filter: blur(4px);
}

.stat-icon svg,
.stat-icon i {
    width: 20px;
    height: 20px;
}

.stat-content {
    margin-top: auto;
    position: relative;
    z-index: 2;
}

.stat-value {
    font-size: 29px;
    font-weight: 800;
    color: #ff000a;
    line-height: 1.2;
}

.stat-label {
    color: rgb(255 255 255);
    font-size: 18px;
    font-weight: 500;
    margin-top: 4px;
    text-transform: capitalize;
}

.stat-trend {
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 4px 8px;
    border-radius: 9999px;
    border: 1px solid transparent;
    backdrop-filter: blur(4px);
}

.trend-up {
    color: #34d399;
    /* Emerald 400 */
    background: rgba(52, 211, 153, 0.15);
    border-color: rgba(52, 211, 153, 0.2);
}

.trend-down {
    color: #f87171;
    /* Red 400 */
    background: rgba(248, 113, 113, 0.15);
    border-color: rgba(248, 113, 113, 0.2);
}

.trend-neutral {
    color: #e2e8f0;
    /* Slate 200 */
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.15);
}

/* Custom Product Card Subsections */
.product-badge {
    font-size: 0.7rem;
    font-weight: 700;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.15);
    padding: 3px 8px;
    border-radius: 9999px;
}

.product-stats-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.product-stat-item {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.product-stat-value {
    font-size: 1.35rem;
    font-weight: 800;
}

.product-stat-value.text-success {
    color: #34d399;
    /* Emerald 400 */
}

.product-stat-value.text-muted {
    color: rgba(255, 255, 255, 0.6);
}

.product-stat-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.7);
    margin-top: 2px;
}

.product-stat-divider {
    width: 1px;
    height: 28px;
    background: rgba(255, 255, 255, 0.25);
    margin: 0 0.75rem;
}

/* Main Layout Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2.1fr 1.2fr;
    gap: 1.5rem;
    align-items: start;
}

.dashboard-main-col,
.dashboard-side-col {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.data-card {
    background: var(--bg-card);
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.data-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--border-color-hover);
}

.card-title-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}

.card-title-header h3 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-primary);
}

.view-all {
    font-size: 0.85rem;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.view-all:hover {
    color: #3730a3;
    text-decoration: underline;
}

/* Tables styling */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.custom-table th {
    text-align: left;
    padding: 0.75rem 1rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    color: var(--text-secondary);
    font-weight: 600;
    letter-spacing: 0.05em;
    background: #f8fafc;
    border-bottom: 1px solid var(--border-color);
}

.custom-table th:first-child {
    border-top-left-radius: 8px;
}

.custom-table th:last-child {
    border-top-right-radius: 8px;
}

.custom-table td {
    padding: 1rem;
    font-size: 0.85rem;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.custom-table tr:last-child td {
    border-bottom: none;
}

.custom-table tr:hover td {
    background: #f8fafc;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-success {
    background: var(--success-bg);
    color: var(--success-text);
}

.status-warning {
    background: var(--warning-bg);
    color: var(--warning-text);
}

.status-danger {
    background: var(--danger-bg);
    color: var(--danger-text);
}

.status-pending {
    background: #e2e8f0;
    color: #475569;
}

/* Leaderboard Performer Card Styles */
.performer-list {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
}

.performer-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 1rem;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    box-shadow: var(--shadow-sm);
}

.performer-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--border-color-hover);
}

.performer-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 0.5rem;
}

.role-pill {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Designation Role Badges */
.badge-ca {
    background: #eff6ff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.badge-csa {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.badge-sm {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #ffd8a8;
}

.badge-cluster {
    background: #faf5ff;
    color: #6b21a8;
    border: 1px solid #e9d5ff;
}

.performer-incentive-badge {
    display: flex;
    align-items: center;
    gap: 4px;
}

.incentive-label {
    font-size: 0.65rem;
    color: var(--text-muted);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.025em;
}

.incentive-amount {
    font-size: 0.95rem;
    font-weight: 700;
    color: #10b981;
}

.performer-card-body {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
    /* critical for ellipsis to work */
}

.performer-card-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 0 0 1px var(--border-color-hover);
    flex-shrink: 0;
}

.performer-card-info {
    min-width: 0;
    flex: 1;
}

.performer-card-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.performer-card-username {
    font-size: 0.75rem;
    color: var(--text-muted);
    display: inline-block;
    margin-top: 1px;
}

.performer-card-meta {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-top: 4px;
    min-width: 0;
}

.performer-card-designation {
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100px;
}

.performer-card-dot {
    color: var(--text-muted);
}

.performer-card-store {
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Responsiveness Media Queries */
@media screen and (max-width: 1400px) {
    .stats-grid-3x3 {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media screen and (max-width: 1200px) {
    .stats-grid-3x3 {
        grid-template-columns: repeat(2, 1fr);
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media screen and (max-width: 768px) {
    .stats-grid-3x3 {
        grid-template-columns: 1fr;
    }
}

@media screen and (max-width: 576px) {
    .performer-card-body {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .performer-card-avatar {
        width: 38px;
        height: 38px;
    }

    /* .data-card {
        align-self: start;
    } */
}
</style>
@endpush

@section('content')
<section class="content-wrapper">
    <div class="welcome-header">
        <h1>Dashboard</h1>
    </div>

    <!-- 3x3 Stats Grid -->
    <div class="stats-grid-3x3">


        <!-- Employee Card -->
        @can('dashboard.employees-card')
        <div class="stat-card card-employees">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="users"></i></div>
                <span class="stat-trend
                    @if($growthPercentageEmp > 0) trend-up
                    @elseif($growthPercentageEmp < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageEmp > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageEmp), 2) }}%
                    @elseif($growthPercentageEmp < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageEmp), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageEmp), 2) }}%
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalEmployees) }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        @endcan


        <!-- Store Card -->
        @can('dashboard.stores-card')
        <div class="stat-card card-stores">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="store"></i></div>
                <span class="stat-trend
                    @if($growthPercentageStores > 0) trend-up
                    @elseif($growthPercentageStores < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageStores > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageStores), 2) }}%
                    @elseif($growthPercentageStores < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageStores), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageStores), 2) }}%
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalActiveStores) }}</div>
                <div class="stat-label">Active Stores</div>
            </div>
        </div>
        @endcan
        <!-- Product Card -->
        @can('dashboard.products-card')
        <div class="stat-card card-products">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="package"></i></div>
                <span class="product-badge">Catalog</span>
            </div>
            <div class="product-stats-container">
                <div class="product-stat-item">
                    <span style="    color: #1cf21c !important;"
                        class="product-stat-value text-success">{{ number_format($totalActiveProducts) }}</span>
                    <span class="product-stat-label">Active</span>
                </div>
                <div class="product-stat-divider"></div>
                <div class="product-stat-item">
                    <span style="    color: red !important;"
                        class="product-stat-value text-muted">{{ number_format($totalInactiveProducts) }}</span>
                    <span class="product-stat-label">Inactive</span>
                </div>
            </div>
            <div class="stat-label">Products</div>
        </div>
        @endcan
        <!-- Sales Card -->
        @can('dashboard.sales-card')
        <div class="stat-card card-sales">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="shopping-cart"></i></div>
                <span class="stat-trend
                @if(isset($growthPercentageSales))
                    @if($growthPercentageSales > 0) trend-up
                    @elseif($growthPercentageSales < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageSales > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageSales), 2) }}%
                    @elseif($growthPercentageSales < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageSales), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageSales), 2) }}%
                        @endif
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($totalSales, 2) }}</div>
                <div class="stat-label">Total Sales</div>
            </div>
        </div>
        @endcan

        <!-- Incentive Card -->
        @can('dashboard.incentives-card')
        <div class="stat-card card-incentives">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="sparkles"></i></div>
                <span class="stat-trend
                    @if($growthPercentageIncentives > 0) trend-up
                    @elseif($growthPercentageIncentives < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageIncentives > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageIncentives), 2) }}%
                    @elseif($growthPercentageIncentives < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageIncentives), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageIncentives), 2) }}%
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($totalIncentives, 2) }}</div>
                <div class="stat-label">Total Incentives</div>
            </div>
        </div>
        @endcan

        <!-- Vanitham Operations Sales -->
        @can('dashboard.vanitham-sales-card')
        <div class="stat-card card-vanitham-sales">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="building-2"></i></div>
                <span class="stat-trend
                    @if($growthPercentageVanitham > 0) trend-up
                    @elseif($growthPercentageVanitham < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageVanitham > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageVanitham), 2) }}%
                    @elseif($growthPercentageVanitham < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageVanitham), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageVanitham), 2) }}%
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($currentDayVanithamSales, 2) }}</div>
                <div class="stat-label">Vanitham Operations Sales</div>
            </div>
        </div>
        @endcan

        <!-- Centreal Operations Sales -->
        @can('dashboard.centreal-sales-card')
        <div class="stat-card card-centreal-sales">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="building-2"></i></div>
                <span class="stat-trend
                    @if($growthPercentageCentreal > 0) trend-up
                    @elseif($growthPercentageCentreal < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageCentreal > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageCentreal), 2) }}%
                    @elseif($growthPercentageCentreal < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageCentreal), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageCentreal), 2) }}%
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($currentDayCentrealSales, 2) }}</div>
                <div class="stat-label">Centreal Operations Sales</div>
            </div>
        </div>
        @endcan

        <!-- Vanitham Operations Incentives -->
        @can('dashboard.vanitham-incentives-card')
        <div class="stat-card card-vanitham-incentives">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="building-2"></i></div>
                <span class="stat-trend
                    @if($growthPercentageVanithamIncentives > 0) trend-up
                    @elseif($growthPercentageVanithamIncentives < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageVanithamIncentives > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageVanithamIncentives), 2) }}%
                    @elseif($growthPercentageVanithamIncentives < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageVanithamIncentives), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageVanithamIncentives), 2) }}%
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($currentDayVanithamIncentives, 2) }}</div>
                <div class="stat-label">Vanitham Operations Incentives</div>
            </div>
        </div>
        @endcan

        <!-- Centreal Operations Incentives -->
        @can('dashboard.centreal-incentives-card')
        <div class="stat-card card-centreal-incentives">
            <div class="stat-header">
                <div class="stat-icon"><i data-lucide="building-2"></i></div>
                <span class="stat-trend
                    @if($growthPercentageCentrealIncentives > 0) trend-up
                    @elseif($growthPercentageCentrealIncentives < 0) trend-down
                    @else trend-neutral
                    @endif">
                    @if($growthPercentageCentrealIncentives > 0)
                    <i data-lucide="arrow-up-right"></i>
                    {{ number_format(abs($growthPercentageCentrealIncentives), 2) }}%
                    @elseif($growthPercentageCentrealIncentives < 0) <i data-lucide="arrow-down-right"></i>
                        {{ number_format(abs($growthPercentageCentrealIncentives), 2) }}%
                        @else
                        <i data-lucide="minus"></i>
                        {{ number_format(abs($growthPercentageCentrealIncentives), 2) }}%
                        @endif
                </span>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($currentDayCentrealIncentives, 2) }}</div>
                <div class="stat-label">Centreal Operations Incentives</div>
            </div>
        </div>
        @endcan
    </div>

    <!-- Main Grid Section -->
    <div class="dashboard-grid">
        <!-- Tables Column (Left) -->
        <div class="dashboard-main-col">
            <!-- Recent Sales Table -->
            @can('dashboard.recent-sales-card')
            <div class="data-card">
                <div class="card-title-header">
                    <h3>Recent Sales Performance</h3>
                    <a href="{{ route('view.report') }}" class="view-all">View Report</a>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Store</th>
                                <th>Employee</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td>{{ $sale->store->c_store_name ?? 'N/A' }}</td>
                                <td>{{ $sale->employee->c_employee_name ?? 'N/A' }}</td>
                                <td>{{ $sale->product->c_product_name ?? 'N/A' }}</td>
                                <td>₹{{ number_format($sale->n_quantity * $sale->n_sold_price, 2) }}</td>
                                <td>
                                    @php
                                    $status = $sale->status ?? 'draft';
                                    @endphp

                                    @if($status == 'verified')
                                    <span class="status-badge status-success">Verified</span>
                                    @elseif($status == 'review')
                                    <span class="status-badge status-warning">Review</span>
                                    @elseif($status == 'rejected')
                                    <span class="status-badge status-danger">Rejected</span>
                                    @else
                                    <span class="status-badge status-pending">Draft</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No recent sales data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <!-- Top Selling Stores -->
            @can('dashboard.top-stores-card')
            <div class="data-card">
                <div class="card-title-header">
                    <h3>Top Selling Stores</h3>
                    <a href="{{ route ('view.store.report') }}" class="view-all">View Report</a>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Store</th>
                                <th>Store Code</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topStores as $store)
                            <tr>
                                <td>{{ $store->c_store_name ?? 'N/A' }}</td>
                                <td>{{ $store->c_store_code ?? 'N/A' }}</td>
                                <td>₹{{ number_format($store->total_sales, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No top-selling stores found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <!-- Stores with Unprocessed Sales -->
            @can('dashboard.pending-sales-card')
            <div class="data-card">
                <div class="card-title-header">
                    <h3>Stores with Unprocessed Sales</h3>
                    <a href="#" class="view-all">View Report</a>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Store Code</th>
                                <th>Store</th>
                                <th>Sales Pending</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingSalesByStore as $pendingSale)
                            <tr>
                                <td>{{ $pendingSale->store->c_store_code ?? 'N/A' }}</td>
                                <td>{{ $pendingSale->store->c_store_name ?? 'N/A' }}</td>
                                <td>{{ $pendingSale->total_sales_pending ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No unprocessed sales available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan
        </div>

        <!-- Performers Column (Right) -->
        <div class="dashboard-side-col">
            <!-- Top Performers Vanitham -->
            @can('dashboard.top-vanitham-performers-card')
            <div class="data-card">
                <div class="card-title-header">
                    <h3>Top Performers Vanitham</h3>
                    <i data-lucide="more-horizontal" style="cursor: pointer; color: var(--text-muted);"></i>
                </div>

                <div class="performer-list">
                    <!-- CA -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-ca">C&A</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topVanithamCA->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topVanithamCA?->profile_path)
                                ? config('app.employee_app_url') . '/storage/' . $topVanithamCA->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topVanithamCA?->c_employee_name ?? 'NA') . '&background=faf5ff&color=9333ea' }}"
                                class="performer-card-avatar" alt="{{ $topVanithamCA?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topVanithamCA->c_employee_name ?? 'No Top C&A' }}">
                                    {{ $topVanithamCA->c_employee_name ?? 'No Top C&A' }}
                                </h4>
                                <span class="performer-card-username">({{ $topVanithamCA->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topVanithamCA->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topVanithamCA->c_store_name ?? 'No Data Available Today' }}">
                                        {{ $topVanithamCA->c_store_name ?? 'No Data Available Today' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CSA -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-csa">CSA</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topVanithamCSA->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topVanithamCSA?->profile_path)
                                ? config('app.employee_app_url') . '/storage/' . $topVanithamCSA->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topVanithamCSA?->c_employee_name ?? 'NA') . '&background=faf5ff&color=9333ea' }}"
                                class="performer-card-avatar" alt="{{ $topVanithamCSA?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topVanithamCSA->c_employee_name ?? 'No Top CSA' }}">
                                    {{ $topVanithamCSA->c_employee_name ?? 'No Top CSA' }}
                                </h4>
                                <span
                                    class="performer-card-username">({{ $topVanithamCSA->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topVanithamCSA->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topVanithamCSA->c_store_name ?? 'No Data Available Today' }}">
                                        {{ $topVanithamCSA->c_store_name ?? 'No Data Available Today' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SM -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-sm">SM</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topVanithamSM->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topVanithamSM?->profile_path)
                                ? config('app.employee_app_url') . '/storage/' . $topVanithamSM->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topVanithamSM?->c_employee_name ?? 'NA') . '&background=faf5ff&color=9333ea' }}"
                                class="performer-card-avatar" alt="{{ $topVanithamSM?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topVanithamSM->c_employee_name ?? 'No Top SM' }}">
                                    {{ $topVanithamSM->c_employee_name ?? 'No Top SM' }}
                                </h4>
                                <span class="performer-card-username">({{ $topVanithamSM->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topVanithamSM->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topVanithamSM->c_store_name ?? 'No Data Available Today' }}">
                                        {{ $topVanithamSM->c_store_name ?? 'No Data Available Today' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cluster -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-cluster">Cluster</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topVanithamCluster->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topVanithamCluster?->profile_path)
                                ? config('app.employee_app_url') . '/storage/' . $topVanithamCluster->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topVanithamCluster?->c_employee_name ?? 'NA') . '&background=faf5ff&color=9333ea' }}"
                                class="performer-card-avatar"
                                alt="{{ $topVanithamCluster?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topVanithamCluster->c_employee_name ?? 'No Top Cluster' }}">
                                    {{ $topVanithamCluster->c_employee_name ?? 'No Top Cluster' }}
                                </h4>
                                <span
                                    class="performer-card-username">({{ $topVanithamCluster->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topVanithamCluster->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topVanithamCluster->c_store_name ?? 'Multiple Stores' }}">
                                        {{ $topVanithamCluster->c_store_name ?? 'Multiple Stores' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Top Performers Centreal -->
            @can('dashboard.top-centreal-performers-card')
            <div class="data-card">
                <div class="card-title-header">
                    <h3>Top Performers Centreal</h3>
                    <i data-lucide="more-horizontal" style="cursor: pointer; color: var(--text-muted);"></i>
                </div>

                <div class="performer-list">
                    <!-- CA -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-ca">C&A</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topCentrealCA->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topCentrealCA?->profile_path)
                                ? config('app.employee_app_url') . asset('/storage/') . $topCentrealCA->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topCentrealCA?->c_employee_name ?? 'NA') . '&background=eff6ff&color=2563eb' }}"
                                class="performer-card-avatar" alt="{{ $topCentrealCA?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topCentrealCA->c_employee_name ?? 'No Top C&A' }}">
                                    {{ $topCentrealCA->c_employee_name ?? 'No Top C&A' }}
                                </h4>
                                <span class="performer-card-username">({{ $topCentrealCA->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topCentrealCA->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topCentrealCA->c_store_name ?? 'No Data Available Today' }}">
                                        {{ $topCentrealCA->c_store_name ?? 'No Data Available Today' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CSA -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-csa">CSA</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topCentrealCSA->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topCentrealCSA?->profile_path)
                                ? config('app.employee_app_url') . '/storage/' . $topCentrealCSA->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topCentrealCSA?->c_employee_name ?? 'NA') . '&background=eff6ff&color=2563eb' }}"
                                class="performer-card-avatar" alt="{{ $topCentrealCSA?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topCentrealCSA->c_employee_name ?? 'No Top CSA' }}">
                                    {{ $topCentrealCSA->c_employee_name ?? 'No Top CSA' }}
                                </h4>
                                <span
                                    class="performer-card-username">({{ $topCentrealCSA->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topCentrealCSA->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topCentrealCSA->c_store_name ?? 'No Data Available Today' }}">
                                        {{ $topCentrealCSA->c_store_name ?? 'No Data Available Today' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SM -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-sm">SM</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topCentrealSM->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topCentrealSM?->profile_path)
                                ? config('app.employee_app_url') . '/storage/' . $topCentrealSM->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topCentrealSM?->c_employee_name ?? 'NA') . '&background=eff6ff&color=2563eb' }}"
                                class="performer-card-avatar" alt="{{ $topCentrealSM?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topCentrealSM->c_employee_name ?? 'No Top SM' }}">
                                    {{ $topCentrealSM->c_employee_name ?? 'No Top SM' }}
                                </h4>
                                <span class="performer-card-username">({{ $topCentrealSM->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topCentrealSM->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topCentrealSM->c_store_name ?? 'No Data Available Today' }}">
                                        {{ $topCentrealSM->c_store_name ?? 'No Data Available Today' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cluster -->
                    <div class="performer-card">
                        <div class="performer-card-header">
                            <span class="role-pill badge-cluster">Cluster</span>
                            <div class="performer-incentive-badge">
                                <span class="incentive-label">Incentive</span>
                                <span
                                    class="incentive-amount">₹{{ number_format($topCentrealCluster->total_incentive ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="performer-card-body">
                            <img src="{{ !empty($topCentrealCluster?->profile_path)
                                ? config('app.employee_app_url') . '/storage/' . $topCentrealCluster->profile_path
                                : 'https://ui-avatars.com/api/?name=' . urlencode($topCentrealCluster?->c_employee_name ?? 'NA') . '&background=eff6ff&color=2563eb' }}"
                                class="performer-card-avatar"
                                alt="{{ $topCentrealCluster?->c_employee_name ?? 'User' }}">
                            <div class="performer-card-info">
                                <h4 class="performer-card-name"
                                    title="{{ $topCentrealCluster->c_employee_name ?? 'No Top Cluster' }}">
                                    {{ $topCentrealCluster->c_employee_name ?? 'No Top Cluster' }}
                                </h4>
                                <span
                                    class="performer-card-username">({{ $topCentrealCluster->c_username ?? 'N/A' }})</span>
                                <div class="performer-card-meta">
                                    <span
                                        class="performer-card-designation">{{ $topCentrealCluster->c_designation_name ?? 'N/A' }}</span>
                                    <span class="performer-card-dot">•</span>
                                    <span class="performer-card-store"
                                        title="{{ $topCentrealCluster->c_store_name ?? 'Multiple Stores' }}">
                                        {{ $topCentrealCluster->c_store_name ?? 'Multiple Stores' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
</section>


@endsection
