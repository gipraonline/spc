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

    <!-- PWA: manifest + install/theme metadata (auto "Add to Home Screen") -->
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <meta name="theme-color" content="#166534">
    <link rel="apple-touch-icon" href="<?php echo e(asset('icons/apple-touch-icon.png')); ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SPC">

    <!-- PWA: capture the native install prompt as early as possible -->
    <script>
        window.__spcDeferredPrompt = null;
        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            window.__spcDeferredPrompt = e;
            window.dispatchEvent(new Event('spc:install-ready'));
        });
        window.addEventListener('appinstalled', function () {
            window.__spcDeferredPrompt = null;
            try { localStorage.setItem('spc-install-done', '1'); } catch (err) {}
        });
    </script>
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Spc" />
    <meta name="author" content="" />
    <meta name="keywords" content="Incentive" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <!-- Google Fonts: Kanit (headings) + Outfit (body) — same pairing as the HR shell -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
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
        --spc-brand: #17694a;
        --spc-brand-strong: #0e3d26;
        --spc-brand-ink: #08301e;
        --spc-brand-bright: #1b6b49;
        --spc-brand-glow: rgba(14, 61, 38, .35);
        --spc-brand-soft: #e7efe9;
        --spc-brand-softer: #f4f7f4;
        --spc-grad: linear-gradient(135deg, #1b6b49, #0e3d26);
        --spc-grad-hero: linear-gradient(135deg, #17694a 0%, #08301e 55%, #0F2B14 100%);
        --spc-ink: #101a14;
        --spc-muted: #6e7a71;
        --spc-line: rgba(14, 61, 38, .13);
        --spc-line-soft: rgba(14, 61, 38, .07);
        --spc-radius: 16px;
        --spc-radius-sm: 12px;
        --spc-shadow: 0 14px 34px -16px rgba(8, 46, 29, .28);
        --spc-shadow-lg: 0 28px 60px -24px rgba(8, 46, 29, .4);
        --spc-ok: #15803D;
        --spc-ok-soft: #dff0e6;
        --spc-warn: #B45309;
        --spc-warn-soft: #FCF0D8;
        --spc-bad: #C03434;
        --spc-bad-soft: #FBE7E4;
        --spc-nav-h: 64px;
        --font-head: 'Plus Jakarta Sans', sans-serif;
        --font-body: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        font-family: var(--font-body);
        color: var(--spc-ink);
        font-size: 14.5px;
        background:
            linear-gradient(45deg, rgba(227, 239, 231, .32), transparent 46%),
            radial-gradient(1000px 460px at 90% -10%, rgba(14, 61, 38, 0.12), transparent 62%),
            radial-gradient(760px 420px at -8% 108%, rgba(10, 84, 58, 0.1), transparent 58%),
            #eef2ee !important;
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
        background: linear-gradient(45deg, #0e3d26, #1b6b49) !important;
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
        background: linear-gradient(45deg, rgba(227, 239, 231, .28), transparent 55%), #fff;
    }

    .card.w-100.position-relative.overflow-hidden {
        background: linear-gradient(45deg, rgba(227, 239, 231, .28), transparent 55%), #fff;
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
        border: 1.5px dashed rgba(14, 61, 38, .3);
        opacity: .75;
        transition: transform .3s;
        pointer-events: none;
    }

    .card.w-100.position-relative.overflow-hidden:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 40px -18px rgba(8, 46, 29, .42);
        border-color: rgba(14, 61, 38, .45);
    }

    .card.w-100.position-relative.overflow-hidden:hover::after {
        transform: rotate(22deg) scale(1.1);
    }

    .filter-card-wrapper {
        border-radius: var(--spc-radius);
        background: linear-gradient(45deg, rgba(227, 239, 231, .28), transparent 55%), #fff;
    }

    /* Tables → green gradient header, softer body text.
       White text rides along with the forced gradient so page-level
       dark-on-light header styles can never render invisibly on it. */
    table th,
    th.border-bottom-0 h6 {
        background: linear-gradient(135deg, #1b6b49, #0e3d26) !important;
        color: #fff !important;
    }

    /* ===== SPC unified table format — identical on every page ===== */
    table th {
        color: #fff !important;
    }

    .btn-outline-primary,
    .btn-outline-secondary {
        border: 1.5px solid rgba(14, 61, 38, .45) !important;
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
        background: linear-gradient(135deg, #1b6b49, #0e3d26) !important;
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
        background: #f4f8f4 !important;
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
        background: #dff0e6 !important;
        color: #0e6b43 !important;
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
        color: #035055 !important;
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
        --bs-table-bg-state: #f4f8f4 !important;
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
        box-shadow: 0 0 0 3.5px rgba(14, 61, 38, .14) !important;
    }

    .icon-box {
        background: var(--spc-brand-soft) !important;
        color: var(--spc-brand) !important;
    }

    /* ============ Sidebar — "Evergreen mint" (identical to the HR shell) ============ */
    body #main-wrapper aside.left-sidebar,
    aside.left-sidebar {
        background:
            linear-gradient(45deg, #e3efe7, transparent 62%),
            radial-gradient(420px 320px at -20% 108%, rgba(14, 61, 38, .14), transparent 55%),
            linear-gradient(175deg, #FFFFFF 0%, #F4FBF6 60%, #ECF7F0 100%) !important;
        background-color: #F4FBF6 !important;
        border-right: 1px solid rgba(14, 61, 38, .1);
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
        background: linear-gradient(90deg, rgba(14, 61, 38, .35), transparent);
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
        background: rgba(14, 61, 38, .1);
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
        box-shadow: 0 1px 2px rgba(8, 46, 29, .05);
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
        box-shadow: 0 1px 2px rgba(8, 46, 29, .05);
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
        background: linear-gradient(135deg, #2f8a63, #0e3d26);
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
        border: 1px solid #e4eae4;
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
        background: #eef4f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1b6b49;
    }

    /* LABEL */
    .custom-filter-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #6b756d;
    }

    /* INPUTS */
    .styled-select {
        height: 46px;
        border-radius: 12px;
        border: 1px solid #dbe3db;
        font-size: 14px;
        font-weight: 500;
    }

    .styled-select:focus {
        border-color: #1b6b49;
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
        background: linear-gradient(90deg, #17694a 0%, #1b6b49 100%);
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

        background: linear-gradient(135deg, #17694a 0%, #17694a 100%);
        border: none;

        transition: all 0.2s ease-in-out;
    }

    .btn-creative-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(93, 135, 255, 0.25);
    }

    body {
        background-color: #eef2ee;
    }

    /* (legacy sidebar padding removed — unified sidebar handles spacing) */

    /* (legacy button/modal colors removed — unified green theme handles them) */

    header.app-header {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e9e2;
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
        --bs-table-bg-state: #eef2ee;
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
        background-color: #0e3d26;
        border-color: #0e3d26;
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
        background: #0e3d26;
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
        color: #4a544c;

    }

    .card-title-custom {
        font-weight: 700;
        color: #1b3a2c;
    }

    .trend-neutral {
        color: #46514a;
        background: #e2e9e2;
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
        color: #17694a;
        font-weight: 500;
        font-size: 15px;
    }


.employee-page{
    padding: 0 !important;
}

    #employee_search:focus {
        box-shadow: none !important;
        outline: none;
    }
    </style>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/spc-theme.css')); ?>?v=23">
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
                        <a class="topbar-logo" href="<?php echo e(route('dashboard')); ?>" aria-label="SPC Universal home">
                            <img src="<?php echo e(asset('dist/images/logos/spclogo.png')); ?>" alt="SPC">
                        </a>
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
                                        <?php
                                            $spcName = trim(Auth::user()->name ?? '');
                                            // Show the designation; fall back to the role when there is none.
                                            $navTitle = $navProfile['designation'] ?? null;
                                            $navTitleLabel = $navTitle ? 'Designation' : 'Role';
                                            $navTitle = $navTitle ?: ($navProfile['role'] ?? 'User');
                                        ?>
                                        <span
                                            class="topbar-avatar"><?php echo e(strtoupper(substr($spcName ?: '?', 0, 1))); ?><?php echo e(strtoupper(substr(strstr($spcName, ' ') ?: '', 1, 1))); ?></span>
                                        <div class="topbar-who">
                                            <b><?php echo e(Auth::user()->name); ?></b>
                                            <span><?php echo e($navTitle); ?></span>
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
                                        <?php if($navProfile ?? null): ?>
                                        <div class="user-panel-detail">
                                            <span>Employee code</span><span><?php echo e($navProfile['code'] ?? '—'); ?></span>
                                        </div>
                                        <div class="user-panel-detail">
                                            <span>Department</span><span><?php echo e($navProfile['department'] ?? '—'); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="user-panel-detail">
                                            <span><?php echo e($navTitleLabel); ?></span><span><?php echo e($navTitle); ?></span>
                                        </div>
                                        <?php if (! (Auth::user()->isAssociate())): ?>
                                        <a href="<?php echo e(route('hr.profile.index')); ?>" class="user-panel-link"><i
                                                class="fa-regular fa-user"></i>View full profile</a>
                                        <?php endif; ?>
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
    color: #0e3d26;
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
            border: 1.5px solid rgba(14, 61, 38, .45) !important;
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
            /* record-card row: wrapping flex so the rich header (code ·
               identity · status) can share one line above the field cells
               (matches the record-card block in spc-theme.css) */
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            position: relative;
            width: 100%;
            background:
                linear-gradient(45deg, rgba(227, 239, 231, .25), transparent 60%), #fff;
            border: 1px solid var(--spc-line);
            border-radius: 16px;
            box-shadow: 0 10px 24px -14px rgba(8, 46, 29, .35);
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
            border: 1px solid rgba(14, 61, 38, .12);
            border-radius: 20px;
            box-shadow: 0 18px 42px -14px rgba(8, 46, 29, .45);
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
            background: linear-gradient(90deg, #2f8a63, #0e3d26);
        }

        .spc-mnav a.active i {
            color: #fff;
            background: linear-gradient(135deg, #2f8a63, #0e3d26);
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
            background: linear-gradient(135deg, #2f8a63, #0e3d26);
            color: #fff;
            font-size: 17px;
            background-size: 180% 180%;
            animation: mnavShift 4.5s ease-in-out infinite;
            box-shadow: 0 14px 26px -10px rgba(8, 46, 29, .55), inset 0 1.5px 0 rgba(255, 255, 255, .35);
            border: 3px solid #eef2ee;
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
        document.querySelectorAll('table').forEach(function(tbl) {
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

    
    <script>
    (function() {
        function shrinkKpis() {
            document.querySelectorAll('.u-kpi-value').forEach(function(el) {
                var n = (el.textContent || '').trim().length;
                if (n > 11) {
                    el.classList.add('sz-xs');
                    el.classList.remove('sz-sm');
                } else if (n > 8) {
                    el.classList.add('sz-sm');
                    el.classList.remove('sz-xs');
                }
            });
        }
        shrinkKpis();
        // Charts / AJAX can replace values after load — watch for it.
        if (!window.__spcKpiWatch) {
            window.__spcKpiWatch = true;
            new MutationObserver(shrinkKpis).observe(document.body, {
                childList: true,
                subtree: true,
                characterData: true
            });
        }
    })();
    </script>


    <!-- PWA: mobile-only "Install SPC App" bottom sheet — uses the native
         prompt when the browser offers one, platform instructions otherwise. -->
    <style>
        .spc-install-sheet {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            visibility: hidden;
            pointer-events: none;
        }

        .spc-install-sheet.is-open {
            visibility: visible;
            pointer-events: auto;
        }

        .spc-install-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(9, 26, 17, .55);
            -webkit-backdrop-filter: blur(3px);
            backdrop-filter: blur(3px);
            opacity: 0;
            transition: opacity .3s ease;
        }

        .spc-install-sheet.is-open .spc-install-backdrop {
            opacity: 1;
        }

        .spc-install-card {
            position: relative;
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 26px 26px 0 0;
            padding: 26px 22px calc(22px + env(safe-area-inset-bottom, 0px));
            box-shadow: 0 -18px 50px rgba(9, 26, 17, .3);
            transform: translateY(110%);
            transition: transform .38s cubic-bezier(.16, 1, .3, 1);
            text-align: center;
        }

        .spc-install-sheet.is-open .spc-install-card {
            transform: translateY(0);
        }

        .spc-install-x {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: #eef3ef;
            color: #47614f;
            font-size: 17px;
            line-height: 1;
            cursor: pointer;
        }

        .spc-install-badge {
            width: 70px;
            height: 70px;
            margin: 0 auto 14px;
            padding: 4px;
            border-radius: 21px;
            background: radial-gradient(110% 100% at 100% 0%, rgba(255, 255, 255, .16), transparent 60%), linear-gradient(160deg, #12492e, #0b3320);
            box-shadow: 0 12px 30px rgba(18, 73, 46, .38);
        }

        .spc-install-badge img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 17px;
            background: #fff;
        }

        .spc-install-card h3 {
            margin: 0 0 6px;
            font-size: 21px;
            font-weight: 800;
            color: #0d2b1c;
        }

        .spc-install-sub {
            margin: 0 auto 18px;
            max-width: 300px;
            font-size: 14px;
            line-height: 1.5;
            color: #5d6f64;
        }

        .spc-install-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: 16px;
            background: radial-gradient(110% 100% at 100% 0%, rgba(255, 255, 255, .16), transparent 60%), linear-gradient(160deg, #12492e, #0b3320);
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(18, 73, 46, .32);
            transition: transform .12s ease;
        }

        .spc-install-btn:active {
            transform: scale(.98);
        }

        .spc-install-later {
            width: 100%;
            margin-top: 8px;
            min-height: 44px;
            border: 0;
            background: transparent;
            color: #6b7d72;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .spc-install-steps {
            text-align: left;
            background: #f2f7f3;
            border: 1px solid #dde9e0;
            border-radius: 18px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }

        .spc-install-steps ol {
            margin: 0;
            padding: 0;
            list-style: none;
            counter-reset: spc-step;
        }

        .spc-install-steps li {
            counter-increment: spc-step;
            position: relative;
            padding: 9px 0 9px 40px;
            font-size: 14.5px;
            line-height: 1.45;
            color: #31473a;
        }

        .spc-install-steps li + li {
            border-top: 1px dashed #d8e4db;
        }

        .spc-install-steps li::before {
            content: counter(spc-step);
            position: absolute;
            left: 0;
            top: 8px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(160deg, #12492e, #0b3320);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spc-install-steps li i {
            color: #166534;
            margin-right: 6px;
        }

        /* Variant toggles rely on the hidden attribute — author display
           rules would otherwise override it. */
        [hidden] {
            display: none !important;
        }

        /* Phones only — never on desktop */
        @media (min-width: 768px) {
            .spc-install-sheet {
                display: none !important;
            }
        }
    </style>

    <div class="spc-install-sheet" id="spcInstallSheet" role="dialog" aria-modal="true"
        aria-labelledby="spcInstallTitle" aria-hidden="true">
        <div class="spc-install-backdrop" data-install-dismiss></div>
        <div class="spc-install-card">
            <button type="button" class="spc-install-x" data-install-dismiss aria-label="Close">&times;</button>
            <div class="spc-install-badge"><img src="<?php echo e(asset('icons/icon-192.png')); ?>" alt="SPC app icon"></div>
            <h3 id="spcInstallTitle">Install SPC App</h3>
            <p class="spc-install-sub">Get quick access to our website directly from your home screen.</p>

            <div id="spcInstallNative" hidden>
                <button type="button" class="spc-install-btn" id="spcInstallBtn">
                    <i class="fa-solid fa-plus"></i> Add to Home Screen
                </button>
            </div>

            <div class="spc-install-steps" id="spcInstallSteps" hidden>
                <ol id="spcInstallStepsList"></ol>
            </div>

            <button type="button" class="spc-install-btn" id="spcInstallGotIt" hidden>Got it</button>
            <button type="button" class="spc-install-later" data-install-dismiss>Maybe later</button>
        </div>
    </div>

    <script>
        (function () {
            var SHEET = 'spc-install-sheet-shown';
            var DONE = 'spc-install-done';
            var MONTH = 30 * 24 * 60 * 60 * 1000;
            var sheet = document.getElementById('spcInstallSheet');
            if (!sheet) return;

            function store(key, val) { try { localStorage.setItem(key, val); } catch (e) {} }
            function read(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }

            // Phones only; never nag when already installed or recently asked.
            var standalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone === true;
            var mobile = window.matchMedia('(max-width: 767.98px)').matches;
            var lastShown = parseInt(read(SHEET) || '0', 10) || 0;
            if (!mobile || read(DONE) || standalone || Date.now() - lastShown < MONTH) return;

            var ua = navigator.userAgent;
            var isIOS = /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            var isAndroid = /Android/.test(ua);

            var nativeWrap = document.getElementById('spcInstallNative');
            var steps = document.getElementById('spcInstallSteps');
            var stepsList = document.getElementById('spcInstallStepsList');
            var gotIt = document.getElementById('spcInstallGotIt');
            var btn = document.getElementById('spcInstallBtn');

            var STEPS = {
                ios: [
                    ['fa-arrow-up-from-bracket', 'Tap the <strong>Share</strong> button in the Safari toolbar'],
                    ['fa-plus-square', 'Choose <strong>Add to Home Screen</strong> from the list'],
                    ['fa-check', 'Tap <strong>Add</strong> — the SPC icon appears on your home screen']
                ],
                android: [
                    ['fa-ellipsis-vertical', 'Tap the <strong>⋮ menu</strong> in the corner of Chrome'],
                    ['fa-download', 'Choose <strong>Install app</strong> or <strong>Add to Home screen</strong>'],
                    ['fa-check', 'Tap <strong>Install</strong> — done!']
                ],
                generic: [
                    ['fa-ellipsis-vertical', 'Open your browser\'s <strong>menu</strong>'],
                    ['fa-plus', 'Choose <strong>Add to Home screen</strong>'],
                    ['fa-check', 'Confirm — the SPC icon appears on your home screen']
                ]
            };

            function open(variant) {
                store(SHEET, String(Date.now())); // at most once a month
                if (variant === 'native') {
                    nativeWrap.hidden = false;
                    steps.hidden = true;
                    gotIt.hidden = true;
                } else {
                    nativeWrap.hidden = true;
                    var rows = isIOS ? STEPS.ios : (isAndroid ? STEPS.android : STEPS.generic);
                    stepsList.innerHTML = rows.map(function (r) {
                        return '<li><i class="fa-solid ' + r[0] + '"></i>' + r[1] + '</li>';
                    }).join('');
                    steps.hidden = false;
                    gotIt.hidden = false;
                }
                sheet.classList.add('is-open');
                sheet.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function close() {
                sheet.classList.remove('is-open');
                sheet.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            Array.prototype.forEach.call(sheet.querySelectorAll('[data-install-dismiss]'), function (el) {
                el.addEventListener('click', close);
            });
            gotIt.addEventListener('click', close);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sheet.classList.contains('is-open')) close();
            });

            // Native path: hand the captured prompt to Chrome and honour the choice.
            btn.addEventListener('click', function () {
                var promptEvent = window.__spcDeferredPrompt;
                close();
                if (!promptEvent) return;
                window.__spcDeferredPrompt = null;
                promptEvent.prompt();
                promptEvent.userChoice.then(function (choice) {
                    if (choice && choice.outcome === 'accepted') store(DONE, '1');
                });
            });

            var shown = false;
            function show(variant) {
                if (shown) return;
                shown = true;
                open(variant);
            }

            // Give the native prompt a moment to arrive, then fall back to
            // the how-to steps (iPhone Safari never fires the event).
            if (window.__spcDeferredPrompt) {
                show('native');
            } else {
                window.addEventListener('spc:install-ready', function () { show('native'); }, { once: true });
                setTimeout(function () { show(window.__spcDeferredPrompt ? 'native' : 'steps'); }, 3500);
            }
        })();
    </script>

    <!-- PWA: register the service worker (caching + installability) -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('<?php echo e(asset('service-worker.js')); ?>?v=1');
            });
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/layouts/app.blade.php ENDPATH**/ ?>