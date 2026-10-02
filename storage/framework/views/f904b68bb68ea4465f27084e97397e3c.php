<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title — same format as the HR shell: "Page · SPC Universal" -->
    <?php
    $spcTopTitle = \Illuminate\Support\Str::of(str_replace(['.', '-', '_'], ' ', optional(request()->route())->getName()
    ?? ''))
    ->explode(' ')->reject(fn ($w) => in_array($w, ['index', 'admin', 'hr', 'show', 'edit', 'create', 'store', 'update',
    'destroy']))
    ->map(fn ($w) => ucfirst($w))->implode(' ');
    ?>
    <title>
        <?php if (! empty(trim($__env->yieldContent('topbarTitle')))): ?><?php echo $__env->yieldContent('topbarTitle'); ?><?php elseif(View::hasSection('title')): ?><?php echo $__env->yieldContent('title'); ?><?php else: ?><?php echo e($spcTopTitle ?: 'Dashboard'); ?><?php endif; ?>
        · SPC Universal</title>
    <!-- Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Central Bazaar Incentive Admin" />
    <meta name="author" content="" />
    <meta name="keywords" content="Incentive" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <!-- Google Fonts: Kanit (headings) + Outfit (body) — same pairing as the HR shell -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('dist/images/logos/fav.png')); ?>" />
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="<?php echo e(asset('dist/css/style.min.css')); ?>" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <?php echo $__env->yieldPushContent('styles'); ?>


    <style>
    /* ============================================================
       SPC unified theme — "Evergreen"
       Same palette + Kanit/Outfit pairing as the HR shell so every
       page in the app (admin, sales, HR) feels like one product.
       ============================================================ */
    :root {
        --spc-brand: #4E7A33;
        --spc-brand-strong: #0E5239;
        --spc-brand-ink: #1F3D14;
        --spc-brand-bright: #5E8D3D;
        --spc-brand-glow: rgba(94, 141, 61, .35);
        --spc-brand-soft: #E4F3EB;
        --spc-brand-softer: #F2F9F5;
        --spc-grad: linear-gradient(135deg, #5E8D3D, #1F5C2E);
        --spc-grad-hero: linear-gradient(135deg, #4E7A33 0%, #1F3D14 55%, #0F2B14 100%);
        --spc-ink: #22352C;
        --spc-muted: #61756B;
        --spc-line: rgba(18, 58, 40, .13);
        --spc-line-soft: rgba(18, 58, 40, .07);
        --spc-radius: 16px;
        --spc-radius-sm: 12px;
        --spc-shadow: 0 14px 34px -16px rgba(10, 61, 44, .28);
        --spc-shadow-lg: 0 28px 60px -24px rgba(8, 48, 31, .4);
        --spc-ok: #15803D;
        --spc-ok-soft: #DCF3E4;
        --spc-warn: #B45309;
        --spc-warn-soft: #FCF0D8;
        --spc-bad: #C03434;
        --spc-bad-soft: #FBE7E4;
        --spc-nav-h: 64px;
        --font-head: 'Kanit', sans-serif;
        --font-body: 'Outfit', sans-serif;
    }

    body {
        font-family: var(--font-body);
        color: var(--spc-ink);
        font-size: 14.5px;
        background:
            linear-gradient(45deg, rgba(203, 255, 205, .32), transparent 46%),
            radial-gradient(1000px 460px at 90% -10%, rgba(94, 141, 61, 0.12), transparent 62%),
            radial-gradient(760px 420px at -8% 108%, rgba(20, 108, 78, 0.1), transparent 58%),
            #F0F5F1 !important;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .card-title,
    .page-main-title,
    .font-head {
        font-family: var(--font-head) !important;
        letter-spacing: .01em;
        color: var(--spc-brand-ink);
    }

    .card-title,
    .page-main-title {
        background: linear-gradient(45deg, #0E5239, #5E8D3D) !important;
        -webkit-background-clip: text !important;
        background-clip: text !important;
        font-weight: 600 !important;
    }

    a,
    button,
    .form-control,
    .form-select,
    input,
    select,
    textarea,
    label {
        font-family: var(--font-body);
    }

    /* Buttons / primary actions → SPC green gradient (white text always) */
    .buttonSpc,
    .btn-filter,
    .btn-creative-filter,
    .btn-primary {
        background: var(--spc-grad) !important;
        border: none !important;
        color: #fff !important;
        box-shadow: 0 10px 20px -10px var(--spc-brand-glow);
        transition: filter .15s, transform .1s;
    }

    .buttonSpc i,
    .buttonSpc a,
    .btn-primary i,
    .btn-filter i,
    .btn-creative-filter i {
        color: #fff;
    }

    .buttonSpc:hover,
    .btn-filter:hover,
    .btn-primary:hover,
    .btn-creative-filter:hover {
        filter: brightness(1.08);
        color: #fff !important;
    }

    .buttonSpc:active,
    .btn-primary:active {
        transform: translateY(1px);
    }

    /* Any button/link inside these buttons stays white too */
    .buttonSpc *,
    .btn-primary * {
        color: inherit;
    }

    /* Cards → mint-washed surface with green top accent (replaces blue) */
    .card {
        border-radius: var(--spc-radius);
        background: linear-gradient(45deg, rgba(203, 255, 205, .28), transparent 55%), #fff;
    }

    .card.w-100.position-relative.overflow-hidden {
        background: linear-gradient(45deg, rgba(203, 255, 205, .28), transparent 55%), #fff;
        border-radius: var(--spc-radius);
        box-shadow: var(--spc-shadow);
        backdrop-filter: none;
        border: 1px solid var(--spc-line);
        border-top: 3px solid var(--spc-brand-bright);
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }

    /* floating dashed ring + lift, same as HR stat tiles */
    .card.w-100.position-relative.overflow-hidden::after {
        content: "";
        position: absolute;
        right: -16px;
        bottom: -20px;
        width: 76px;
        height: 76px;
        border-radius: 50%;
        border: 1.5px dashed rgba(94, 141, 61, .3);
        opacity: .75;
        transition: transform .3s;
        pointer-events: none;
    }

    .card.w-100.position-relative.overflow-hidden:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 40px -18px rgba(8, 48, 31, .42);
        border-color: rgba(94, 141, 61, .45);
    }

    .card.w-100.position-relative.overflow-hidden:hover::after {
        transform: rotate(22deg) scale(1.1);
    }

    .filter-card-wrapper {
        border-radius: var(--spc-radius);
        background: linear-gradient(45deg, rgba(203, 255, 205, .28), transparent 55%), #fff;
    }

    /* Tables → green gradient header, softer body text.
       White text rides along with the forced gradient so page-level
       dark-on-light header styles can never render invisibly on it. */
    table th,
    th.border-bottom-0 h6 {
        background: linear-gradient(135deg, #5E8D3D, #1F5C2E) !important;
        color: #fff !important;
    }

    /* ===== SPC unified table format — identical on every page ===== */
    table th {
        color: #fff !important;
    }

    .btn-outline-primary,
    .btn-outline-secondary {
        border: 1.5px solid rgba(94, 141, 61, .45) !important;
        color: var(--spc-brand-strong) !important;
        background: #fff !important;
        border-radius: 12px !important;
        font-weight: 600;
    }

    .btn-outline-primary:hover,
    .btn-outline-secondary:hover {
        background: var(--spc-brand-softer) !important;
        border-color: var(--spc-brand) !important;
        color: var(--spc-brand-strong) !important;
    }

    table.table {
        margin-bottom: 0;
    }

    table.table thead th {
        background: linear-gradient(135deg, #5E8D3D, #1F5C2E) !important;
        color: #fff !important;
        font-family: var(--font-body);
        font-size: 10.5px !important;
        font-weight: 700 !important;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 12px 14px !important;
        border: none !important;
        white-space: nowrap;
    }

    table.table thead th:first-child {
        border-radius: 12px 0 0 12px;
    }

    table.table thead th:last-child {
        border-radius: 0 12px 12px 0;
    }

    table.table tbody td {
        padding: 12px 14px !important;
        color: var(--spc-ink) !important;
        font-weight: 500;
        border: none !important;
        border-bottom: 1px solid var(--spc-line-soft) !important;
        vertical-align: middle;
    }

    table.table tbody tr:last-child td {
        border-bottom: none !important;
    }

    table.table tbody tr {
        transition: background .15s;
    }

    table.table tbody tr:hover {
        background: #F4FAF7 !important;
    }

    table.table tbody tr:hover>* {
        --bs-table-bg-state: transparent;
    }

    .table-responsive {
        border-radius: 14px;
    }

    table.table .badge {
        border-radius: 99px;
        font-weight: 700;
        padding: 5px 11px;
        font-size: 10.5px;
    }

    table.table .badge.bg-success {
        background: #DCF3E4 !important;
        color: #116A38 !important;
    }

    table.table .badge.bg-warning {
        background: #FCF0D8 !important;
        color: #8A5A10 !important;
    }

    table.table .badge.bg-danger {
        background: #FBE7E4 !important;
        color: #942B2B !important;
    }

    table.table .badge.bg-info {
        background: #E3F0FA !important;
        color: #1D6FA5 !important;
    }

    table.table .badge.bg-secondary {
        background: #EDF3EF !important;
        color: #52645B !important;
    }

    table th {
        border: none !important;
    }

    table td {
        color: var(--spc-ink) !important;
        font-weight: 500;
    }

    table .text-muted {
        color: var(--spc-muted) !important;
        font-weight: 600;
    }

    table .text-success {
        color: #0E8A52 !important;
        font-weight: 700 !important;
    }

    .table>:not(caption)>*>* {
        border: 1px solid var(--spc-line-soft) !important;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-bg-state: #F4FAF7 !important;
    }

    /* Modals + pagination follow the green identity */
    .modal-header {
        background: var(--spc-grad) !important;
    }

    .active>.page-link,
    .page-link.active {
        background-color: var(--spc-brand) !important;
        border-color: var(--spc-brand) !important;
    }

    .page-link {
        color: var(--spc-brand);
    }

    .page-link:hover {
        color: var(--spc-brand-strong);
        background: var(--spc-brand-softer);
    }

    /* Form controls inherit the brand focus ring */
    .form-control:focus,
    .form-select:focus,
    .styled-select:focus {
        border-color: var(--spc-brand-bright);
        box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .14) !important;
    }

    .icon-box {
        background: var(--spc-brand-soft) !important;
        color: var(--spc-brand) !important;
    }

    /* ============ Sidebar — "Evergreen mint" (identical to the HR shell) ============ */
    body #main-wrapper aside.left-sidebar,
    aside.left-sidebar {
        background:
            linear-gradient(45deg, #CBFFCD, transparent 62%),
            radial-gradient(420px 320px at -20% 108%, rgba(94, 141, 61, .14), transparent 55%),
            linear-gradient(175deg, #FFFFFF 0%, #F4FBF6 60%, #ECF7F0 100%) !important;
        background-color: #F4FBF6 !important;
        border-right: 1px solid rgba(18, 58, 40, .1);
        box-shadow: none;
    }

    /* Scroll container spacing — mirrors the HR shell (.spc-nav) */
    .left-sidebar .scroll-sidebar {
        padding: 4px 16px 28px !important;
    }

    .sidebar-nav ul .nav-small-cap {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 22px 14px 8px;
        padding: 0;
    }

    .sidebar-nav ul .nav-small-cap .hide-menu {
        font-family: var(--font-body);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #3E8A66;
    }

    .sidebar-nav ul .nav-small-cap::after {
        content: "";
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, rgba(94, 141, 61, .35), transparent);
    }

    .sidebar-nav ul .nav-small-cap-icon {
        display: none;
    }

    .sidebar-nav ul .sidebar-item {
        margin: 2px 10px;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link {
        border-radius: 12px;
        margin: 0;
        padding: 10px 12px;
        font-family: var(--font-body);
        font-size: 13.5px;
        font-weight: 500;
        color: #3D5247;
        line-height: 1.3;
        gap: 12px;
        align-items: center;
        white-space: nowrap;
        transition: background .15s, color .15s, box-shadow .15s;
        position: relative;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link i,
    .sidebar-nav ul .sidebar-item .sidebar-link svg {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
        transition: transform .16s;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover {
        background: rgba(94, 141, 61, .1);
        color: var(--spc-brand-strong);
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover i {
        transform: translateY(-1px) scale(1.06);
    }

    /* Selected page → floating green gradient pill with glow */
    .sidebar-nav ul .sidebar-item.selected>.sidebar-link,
    .sidebar-nav ul .sidebar-item.selected>.sidebar-link.active,
    .sidebar-nav ul .sidebar-item>.sidebar-link.active {
        background: var(--spc-grad) !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 10px 20px -8px rgba(14, 107, 75, .55), inset 0 1.5px 0 rgba(255, 255, 255, .25);
    }

    .sidebar-nav ul .sidebar-item.selected>.sidebar-link i,
    .sidebar-nav ul .sidebar-item>.sidebar-link.active i {
        color: #fff;
    }

    .sidebar-nav ul .sidebar-item.selected>.sidebar-link::before,
    .sidebar-nav ul .sidebar-item>.sidebar-link.active::before {
        content: "";
        position: absolute;
        left: -10px;
        top: 22%;
        bottom: 22%;
        width: 4px;
        border-radius: 0 4px 4px 0;
        background: #17A673;
        box-shadow: 0 0 12px rgba(23, 166, 115, .8);
    }

    /* Keep mini-sidebar mode tidy */
    @media screen and (min-width:992px) {
        #main-wrapper[data-sidebartype=mini-sidebar] .sidebar-nav ul .sidebar-item {
            margin: 2px 4px;
        }

        #main-wrapper[data-sidebartype=mini-sidebar] .nav-small-cap::after {
            display: none;
        }
    }

    /* ============ Topbar — same format as the HR shell ============ */
    /* Sticky in-flow (like the HR shell) instead of the theme's fixed
       positioning, which drifts in this LTR layout and overlaps content */
    header.app-header {
        position: sticky !important;
        top: 0;
        width: 100% !important;
        z-index: 50;
        background: rgba(255, 255, 255, .85) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--spc-line) !important;
        box-shadow: none !important;
    }

    header.app-header .navbar {
        min-height: 68px;
        height: 68px;
        padding: 0 34px;
        gap: 14px;
        flex-wrap: nowrap;
    }

    /* Left cluster: hamburger + eyebrow/page title (HR format) */
    .topbar-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        flex: 1;
    }

    .topbar-title {
        min-width: 0;
    }

    .topbar-title .eyebrow {
        font-family: var(--font-body);
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: var(--spc-brand-bright);
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .topbar-title .eyebrow::before {
        content: "";
        width: 16px;
        height: 2px;
        border-radius: 2px;
        background: var(--spc-brand-bright);
    }

    .topbar-title h1 {
        margin: 0;
        font-family: var(--font-head);
        font-size: 20px;
        font-weight: 600;
        letter-spacing: .01em;
        color: var(--spc-brand-ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width:767.98px) {
        .topbar-title h1 {
            font-size: 16px;
        }

        .topbar-title .eyebrow {
            font-size: 8.5px;
        }
    }

    /* Hamburger / bell become 40px rounded tiles like HR — but let each
       item size to its content (height auto) with a small gap between */
    header.app-header .navbar-nav {
        gap: 12px;
        align-items: center;
    }

    header.app-header .navbar-nav .nav-item .nav-link {
        height: 40px !important;
        width: 40px !important;
        line-height: 1;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px !important;
        border: 1px solid var(--spc-line);
        background: #fff;
        color: var(--spc-muted);
        transition: all .16s;
        box-shadow: 0 1px 2px rgba(10, 61, 44, .05);
    }

    header.app-header .nav-link:hover,
    header.app-header .nav-link:focus {
        border-color: var(--spc-brand-bright);
        color: var(--spc-brand);
        background: var(--spc-brand-softer);
        box-shadow: 0 6px 14px -8px var(--spc-brand-glow);
    }

    header.app-header .nav-link .badge {
        top: 2px !important;
        right: -2px !important;
        border: 2px solid #fff;
    }

    /* User chip next to the avatar — name + role, HR style */
    .topbar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 4.5px 13px 4.5px 5.5px;
        border-radius: 13px;
        border: 1px solid var(--spc-line);
        background: #fff;
        box-shadow: 0 1px 2px rgba(10, 61, 44, .05);
        transition: all .16s;
    }

    .topbar-user:hover,
    .topbar-user.show {
        border-color: var(--spc-brand-bright);
        background: var(--spc-brand-softer);
        box-shadow: 0 6px 14px -8px var(--spc-brand-glow);
    }

    .topbar-user .topbar-avatar {
        width: 37px;
        height: 37px;
        border-radius: 12px;
        flex-shrink: 0;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        font-family: var(--font-head);
        background: linear-gradient(135deg, #7CA243, #0C6B4B);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 14px -6px var(--spc-brand-glow), inset 0 1.5px 0 rgba(255, 255, 255, .3);
    }

    .topbar-who {
        display: flex;
        flex-direction: column;
        line-height: 1.3;
        text-align: left;
    }

    .topbar-who b {
        font-family: var(--font-head);
        font-size: 13.5px;
        font-weight: 500;
        color: var(--spc-brand-ink);
        white-space: nowrap;
    }

    .topbar-who span {
        font-size: 11.5px;
        color: var(--spc-muted);
        white-space: nowrap;
    }

    /* Dropdown panels match the HR bell / user panel */
    header.app-header .dropdown-menu {
        border: 1px solid var(--spc-line);
        border-radius: 16px;
        box-shadow: var(--spc-shadow-lg);
        padding: 10px;
    }

    /* User dropdown panel — identical to the HR user panel */
    header.app-header .user-panel {
        width: 284px;
    }

    .user-panel-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 8px 13px;
        border-bottom: 1px solid var(--spc-line-soft);
        margin-bottom: 6px;
    }

    .user-panel-head .topbar-avatar {
        width: 42px;
        height: 42px;
        font-size: 15px;
    }

    .user-panel-head b {
        display: block;
        font-family: var(--font-head);
        font-size: 14px;
        color: var(--spc-brand-ink);
    }

    .user-panel-email {
        font-size: 12.5px;
        color: var(--spc-muted);
        word-break: break-all;
    }

    .user-panel-detail {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 6.5px 9px;
        font-size: 12.5px;
    }

    .user-panel-detail span:first-child {
        color: var(--spc-muted);
    }

    .user-panel-detail span:last-child {
        font-weight: 500;
        text-align: right;
        color: var(--spc-brand-ink);
    }

    .user-panel-link {
        display: block;
        padding: 10px 9px;
        border-radius: 9px;
        font-size: 13px;
        color: var(--spc-ink);
    }

    .user-panel-link:hover {
        background: var(--spc-brand-softer);
        color: var(--spc-ink);
    }

    .user-panel-link i {
        margin-right: 8px;
        color: var(--spc-brand);
    }

    .user-panel-logout {
        margin: 0;
        padding: 4px 9px 2px;
    }

    .user-panel-signout {
        width: 100%;
        background: #fff;
        border: 1px solid var(--spc-line);
        color: var(--spc-ink);
        padding: 10.5px 19px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        font-family: var(--font-body);
        transition: all .15s;
    }

    .user-panel-signout:hover {
        border-color: var(--spc-brand-bright);
        color: var(--spc-brand);
    }

    .user-panel-signout i {
        margin-right: 8px;
        color: var(--spc-bad);
    }

    @media (max-width:767.98px) {
        .topbar-who {
            display: none;
        }

        .topbar-user {
            padding: 4px;
        }

        header.app-header .navbar {
            padding: 0 18px;
            height: 62px;
            min-height: 62px;
        }
    }

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

    /* (legacy sidebar padding removed — unified sidebar handles spacing) */

    /* (legacy button/modal colors removed — unified green theme handles them) */

    header.app-header {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0;
    }

    /* (legacy sidebar-link rules removed — the unified "Evergreen mint"
       sidebar styles earlier in this file are the single source of truth,
       matching the HR shell exactly) */

    .body-wrapper>.container-fluid {
        max-width: 100%;
        height: auto;
        min-height: 90vh;
    }

    .card-title {
        /* unified green gradient text (see .card-title rule above) */
        font-size: 23px;
        font-weight: 800 !important;
    }

    /* (legacy card/table styles removed — the unified "Evergreen mint"
       rules earlier in this file are the single source of truth) */

    table th,
    th.border-bottom-0 h6 {
        /* handled by the unified green gradient header above */

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
        /* unified green gradient text (see .page-main-title rule above) */
        font-size: 23px;
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
        border: none;
        /* borderless pill search */
        box-shadow: none !important;
        outline: none;
        background: transparent;
        color: #437ccb;
        font-weight: 500;
        font-size: 15px;
    }

    #employee_search:focus {
        box-shadow: none !important;
        outline: none;
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

                        
                        <?php if($parent->route_name && ! \Illuminate\Support\Facades\Route::has($parent->route_name)
                        && $parent->children->count() == 0) continue; ?>

                        
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

                        <?php if(! $child->route_name || ! \Illuminate\Support\Facades\Route::has($child->route_name)) continue; ?>

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
            <!--  Header Start — same format as the HR topbar -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="topbar-left">
                        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                            <i class="ti ti-menu-2"></i>
                        </a>
                        <div class="topbar-title">
                            <div class="eyebrow"><?php echo $__env->yieldContent('eyebrow', 'SPC Portal'); ?></div>
                            <h1><?php echo $__env->yieldContent('topbarTitle', $spcTopTitle ?: 'Dashboard'); ?></h1>
                        </div>
                    </div>

                    <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="p-2">
                            <i class="ti ti-dots fs-7"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <div class="d-flex align-products-center justify-content-end">
                            <ul class="navbar-nav flex-row align-products-center">
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link position-relative" href="javascript:void(0)" id="notifDrop"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-bell fs-6"></i>
                                        <?php if(($navUnreadCount ?? 0) > 0): ?>
                                        <span class="badge bg-danger rounded-pill position-absolute"
                                            style="top:2px;right:-2px;font-size:9px;padding:3px 5px;"><?php echo e($navUnreadCount > 9 ? '9+' : $navUnreadCount); ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                        style="width:320px;max-height:380px;overflow-y:auto;"
                                        aria-labelledby="notifDrop">
                                        <div class="px-3 py-2 border-bottom">
                                            <h6 class="mb-0 fw-semibold">Notifications</h6>
                                        </div>
                                        <?php $__empty_1 = true; $__currentLoopData = ($navNotifications ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <form method="POST" action="<?php echo e($n['read_route']); ?>" class="m-0">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                class="dropdown-item d-flex flex-column align-items-start py-2 <?php echo e($n['read'] ? '' : 'bg-light'); ?>"
                                                style="white-space:normal;">
                                                <span class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="badge <?php echo e(match($n['category']) {
                                                            'hr' => 'bg-info',
                                                            'order' => 'bg-warning text-dark',
                                                            'sales' => 'bg-success',
                                                            default => 'bg-secondary',
                                                        }); ?>"
                                                        style="font-size:9px;"><?php echo e(ucfirst($n['category'])); ?></span>
                                                    <?php if(!$n['read']): ?><span class="text-primary"
                                                        style="font-size:16px;line-height:0;">&bull;</span><?php endif; ?>
                                                </span>
                                                <span class="small text-dark"><?php echo e($n['message']); ?></span>
                                                <span class="text-muted"
                                                    style="font-size:11px;"><?php echo e(\Illuminate\Support\Carbon::parse($n['created_at'])->diffForHumans()); ?></span>
                                            </button>
                                        </form>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="px-3 py-4 text-center text-muted small">No notifications yet.</div>
                                        <?php endif; ?>
                                        <div class="px-3 py-2 border-top text-center">
                                            <a href="<?php echo e(route('notifications.index')); ?>" class="small">View all</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <div class="topbar-user" id="drop1" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <?php $spcName = trim(Auth::user()->name ?? ''); ?>
                                        <span
                                            class="topbar-avatar"><?php echo e(strtoupper(substr($spcName ?: '?', 0, 1))); ?><?php echo e(strtoupper(substr(strstr($spcName, ' ') ?: '', 1, 1))); ?></span>
                                        <div class="topbar-who">
                                            <b><?php echo e(Auth::user()->name); ?></b>
                                            <span><?php echo e($navProfile['role'] ?? 'User'); ?></span>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up user-panel"
                                        aria-labelledby="drop1">
                                        <div class="user-panel-head">
                                            <span
                                                class="topbar-avatar"><?php echo e(strtoupper(substr($spcName ?: '?', 0, 1))); ?><?php echo e(strtoupper(substr(strstr($spcName, ' ') ?: '', 1, 1))); ?></span>
                                            <div>
                                                <b><?php echo e(Auth::user()->name); ?></b>
                                                <div class="user-panel-email"><?php echo e(Auth::user()->email); ?></div>
                                            </div>
                                        </div>
                                        <div class="user-panel-detail">
                                            <span>Role</span><span><?php echo e($navProfile['role'] ?? 'User'); ?></span>
                                        </div>
                                        <?php if($navProfile ?? null): ?>
                                        <div class="user-panel-detail">
                                            <span>Employee code</span><span><?php echo e($navProfile['code'] ?? '—'); ?></span>
                                        </div>
                                        <div class="user-panel-detail">
                                            <span>Department</span><span><?php echo e($navProfile['department'] ?? '—'); ?></span>
                                        </div>
                                        <div class="user-panel-detail">
                                            <span>Designation</span><span><?php echo e($navProfile['designation'] ?? '—'); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('hr.profile.index')); ?>" class="user-panel-link"><i
                                                class="fa-regular fa-user"></i>View full profile</a>
                                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="user-panel-logout">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="user-panel-signout"><i
                                                    class="fa-solid fa-arrow-right-from-bracket"></i>Sign out</button>
                                        </form>
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
">Copyright © 2026 All Rights Reserved.

            </p>
        </div>
    </div>



    <!-- Mobile bottom navigation dock (visible below 992px) -->
    <?php echo $__env->make('partials.mobile-bottom-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
    @media screen and (max-width:767px) {

        .form-control,
        .form-select {
            width: stretch;
            min-width: 100%;
        }
    }

    /* ============ Mobile: tables become user-friendly cards ============ */
    @media (max-width:767.98px) {

        /* Prevent iOS auto-zoom when focusing inputs */
        input,
        select,
        textarea {
            font-size: 16px;
        }

        /* Bootstrap buttons → SPC mint family (kills stray blues in filter rows) */
        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-secondary,
        .btn-info,
        .btn-outline-info {
            background: #fff !important;
            border: 1.5px solid rgba(94, 141, 61, .45) !important;
            color: var(--spc-brand-strong) !important;
            border-radius: 12px !important;
            font-weight: 600;
        }

        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-secondary:hover,
        .btn-info:hover,
        .btn-outline-info:hover {
            background: var(--spc-brand-softer) !important;
            border-color: var(--spc-brand) !important;
        }

        /* ---- Card-mode data tables ----
         Each row becomes a stacked card; the thead is visually hidden and
         every td gets its column name via data-label (auto-injected by JS). */
        table.table thead {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0 0 0 0);
            white-space: nowrap;
        }

        table.table,
        table.table tbody {
            display: block;
            width: 100%;
        }

        table.table tr {
            display: block;
            width: 100%;
            background:
                linear-gradient(45deg, rgba(203, 255, 205, .25), transparent 60%), #fff;
            border: 1px solid var(--spc-line);
            border-radius: 16px;
            box-shadow: 0 10px 24px -14px rgba(8, 48, 31, .35);
            margin: 0 0 12px;
            overflow: hidden;
        }

        table.table tr:hover>* {
            --bs-table-bg-state: transparent;
        }

        table.table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            width: 100%;
            border: none !important;
            text-align: right;
            padding: 9px 14px;
            font-weight: 600;
            color: var(--spc-ink);
            border-bottom: 1px dashed var(--spc-line-soft) !important;
            /* Let long values wrap instead of being clipped by the card */
            white-space: normal !important;
            min-width: 0;
            overflow-wrap: anywhere;
            word-break: break-word;
            line-height: 1.45;
        }

        table.table tr>td:last-child {
            border-bottom: none !important;
        }

        table.table td::before {
            content: attr(data-label);
            flex-shrink: 0;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--spc-muted);
        }

        /* Inner elements (badges, spans, inputs) must shrink + wrap too */
        table.table td>* {
            min-width: 0;
            max-width: 100%;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /* Inner elements (badges, spans, inputs) must be allowed to shrink + wrap */
        table.table td>* {
            min-width: 0;
            max-width: 100%;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        table.table td:empty {
            display: none;
        }

        /* Action buttons stretch full-width at the card foot */
        table.table td .btn {
            min-width: 96px;
        }

        table.table td[colspan] {
            display: block;
            text-align: center;
            padding: 22px 14px;
            color: var(--spc-muted);
        }

        table.table td[colspan]::before {
            content: none;
        }

        /* Cards with their own horizontal scroll keep normal tables instead */
        .keep-table-scroll table.table thead {
            position: static;
            width: auto;
            height: auto;
            clip: auto;
        }

        .keep-table-scroll table.table,
        .keep-table-scroll table.table tbody,
        .keep-table-scroll table.table tr,
        .keep-table-scroll table.table td {
            display: revert;
            width: auto;
            border-collapse: collapse;
        }

        .keep-table-scroll table.table td::before {
            content: none;
        }

        .keep-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }

    /* ============ Mobile bottom nav + phone polish ============ */
    .spc-mnav {
        display: none;
    }

    @media (max-width:991.98px) {
        .spc-mnav {
            display: block;
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1040;
            padding: 0 12px calc(10px + env(safe-area-inset-bottom, 0px));
            pointer-events: none;
        }

        .spc-mnav-inner {
            pointer-events: auto;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 2px;
            max-width: 520px;
            margin: 0 auto;
            background: linear-gradient(160deg, rgba(255, 255, 255, .96), rgba(255, 255, 255, .9));
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(18, 58, 40, .12);
            border-radius: 20px;
            box-shadow: 0 18px 42px -14px rgba(8, 48, 31, .45);
            padding: 8px 6px;
        }

        .spc-mnav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 6px 2px 4px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--spc-muted);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .02em;
            transition: color .15s, background .15s;
            position: relative;
        }

        .spc-mnav a i {
            font-size: 16px;
            line-height: 1;
        }

        .spc-mnav a:active {
            transform: scale(.94);
        }

        .spc-mnav a:hover {
            color: var(--spc-brand);
        }

        .spc-mnav a.active {
            color: var(--spc-brand-strong);
        }

        .spc-mnav a.active::before {
            content: "";
            position: absolute;
            top: 0;
            left: 22%;
            right: 22%;
            height: 3px;
            border-radius: 0 0 4px 4px;
            background: linear-gradient(90deg, #7CA243, #1F5C2E);
        }

        .spc-mnav a.active i {
            color: #fff;
            background: linear-gradient(135deg, #7CA243, #1F5C2E);
            width: 30px;
            height: 30px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            box-shadow: 0 8px 16px -8px var(--spc-brand-glow);
        }

        /* Raised center action (Orders) */
        .spc-mnav .mnav-raise {
            margin-top: -22px;
        }

        .spc-mnav .mnav-raise .mnav-ico {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #7CA243, #1F5C2E);
            color: #fff;
            font-size: 17px;
            background-size: 180% 180%;
            animation: mnavShift 4.5s ease-in-out infinite;
            box-shadow: 0 14px 26px -10px rgba(8, 48, 31, .55), inset 0 1.5px 0 rgba(255, 255, 255, .35);
            border: 3px solid #F0F5F1;
        }

        @keyframes mnavShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        @media (prefers-reduced-motion:reduce) {
            .spc-mnav .mnav-raise .mnav-ico {
                animation: none;
            }
        }

        .spc-mnav .mnav-raise.active .mnav-ico {
            filter: brightness(1.1);
        }

        .spc-mnav .mnav-raise span:last-child {
            margin-top: 2px;
        }

        /* Keep page content clear of the dock */
        .body-wrapper {
            padding-bottom: calc(78px + env(safe-area-inset-bottom, 0px));
        }

        /* The old footer sits behind the dock — shorten it on phones */
        .body-wrapper>p {
            display: none;
        }
    }
    </style>





    <!-- Import Js Files -->
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo e(asset('dist/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/libs/simplebar/dist/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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

    
    <script>
    (function() {
        document.querySelectorAll('table.table').forEach(function(tbl) {
            if (tbl.dataset.labelsDone) return;
            tbl.dataset.labelsDone = '1';
            var heads = tbl.querySelectorAll('thead th');
            if (!heads.length) return;
            var names = Array.prototype.map.call(heads, function(th) {
                return (th.textContent || '').trim().replace(/\s+/g, ' ');
            });
            tbl.querySelectorAll('tbody tr').forEach(function(tr) {
                Array.prototype.forEach.call(tr.children, function(td, i) {
                    if (!td.hasAttribute('data-label') && names[i]) {
                        td.setAttribute('data-label', names[i]);
                    }
                });
            });
        });
    })();
    </script>


    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/layouts/app.blade.php ENDPATH**/ ?>