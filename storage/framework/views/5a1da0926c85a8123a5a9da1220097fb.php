<?php $__env->startPush('styles'); ?>
<style>
:root {
    --u-brand: #4E7A33;
    --u-brand-dark: #1F5C2E;
    --u-brand-mid: #5E8D3D;
    --u-hr: #0E7490;
    --u-hr-light: #eff8ff;
    --u-text: #22352C;
    --u-muted: #61756B;
    --u-surface: #ffffff;
    --u-border: #dde8e1;
    --u-radius: 16px;
    --u-shadow: 0 5px 18px rgba(15, 81, 50, .08);
}

.u-hero {
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(420px 200px at 92% -20%, rgba(124, 162, 67, .38), transparent 60%),
        radial-gradient(360px 220px at -6% 120%, rgba(124, 162, 67, .22), transparent 55%),
        linear-gradient(135deg, #4E7A33, var(--u-brand-dark));
    border-radius: 20px;
    padding: 26px 30px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 18px;
    box-shadow: 0 28px 60px -24px rgba(8, 48, 31, .45);
}

/* floating deco rings + seedling watermark — matches the HR hero language */
.u-hero::before {
    content: '';
    position: absolute;
    top: -70px;
    right: 110px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    border: 1.5px solid rgba(255, 255, 255, .09);
    pointer-events: none;
}

.u-hero::after {
    content: '\f4d8';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    right: 26px;
    bottom: -34px;
    font-size: 110px;
    color: rgba(124, 162, 67, .13);
    pointer-events: none;
}

.u-hero h2 {
    margin: 0 0 4px;
    font-weight: 600;
    font-size: 23px;
    font-family: 'Kanit', 'Outfit', sans-serif;
    letter-spacing: .01em;
    position: relative;
    z-index: 1;
    color: #fff; /* beat the layout's global dark heading color */
}

.u-hero h2 .u-wave {
    display: inline-block;
    transform-origin: 70% 70%;
    animation: uWave 2.6s ease-in-out infinite;
}

@keyframes uWave {

    0%,
    60%,
    100% {
        transform: rotate(0);
    }

    10%,
    30% {
        transform: rotate(14deg);
    }

    20%,
    40% {
        transform: rotate(-8deg);
    }
}

.u-hero p {
    margin: 0;
    opacity: .85;
    font-size: 13px;
    position: relative;
    z-index: 1;
    color: #CDE9DC;
}

.u-hero-date {
    background: rgba(255, 255, 255, .1);
    border: 1px solid rgba(255, 255, 255, .16);
    backdrop-filter: blur(6px);
    border-radius: 12px;
    padding: 9px 16px;
    font-size: 12.5px;
    font-weight: 600;
    white-space: nowrap;
    position: relative;
    z-index: 1;
}

@media (prefers-reduced-motion: reduce) {

    .u-hero h2 .u-wave,
    .u-hero::after {
        animation: none;
    }
}

/* Quick actions strip — right under the hero, first thing scanned */
.u-qa-strip {
    background: var(--u-surface);
    border: 1px solid var(--u-border);
    border-radius: 14px;
    box-shadow: var(--u-shadow);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.u-qa-strip .u-qa-eyebrow {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--u-muted);
    margin-right: 4px;
}

.u-qa-btn {
    border: 1px solid var(--u-border);
    border-radius: 13px;
    padding: 8px 15px 8px 9px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--u-text);
    text-decoration: none;
    background:
        linear-gradient(45deg, rgba(203, 255, 205, .4), transparent 60%),
        #fff;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    white-space: nowrap;
    transition: transform .18s, box-shadow .18s, border-color .18s, color .18s;
}

/* icon inside becomes a gradient tile */
.u-qa-btn i {
    width: 28px;
    height: 28px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    background: var(--u-brand-light, #E4F3EB);
    background: #E4F3EB;
    color: var(--u-brand);
    transition: all .18s;
}

.u-qa-btn:hover {
    transform: translateY(-3px);
    color: var(--u-brand-dark);
    border-color: rgba(94, 141, 61, .5);
    box-shadow: 0 14px 26px -12px rgba(8, 48, 31, .4);
    text-decoration: none;
}

.u-qa-btn:hover i {
    background: linear-gradient(135deg, #7CA243, #1F5C2E);
    color: #fff;
    transform: rotate(-7deg) scale(1.08);
    box-shadow: 0 8px 14px -6px rgba(14, 107, 75, .55);
}

.u-qa-btn.hr:hover {
    color: #1D6FA5;
    border-color: rgba(79, 163, 224, .5);
}

.u-qa-btn.hr:hover i {
    background: linear-gradient(135deg, #4FA3E0, #1D6FA5);
    color: #fff;
    box-shadow: 0 8px 14px -6px rgba(29, 111, 165, .55);
}

.u-qa-divider {
    width: 1px;
    align-self: stretch;
    background: var(--u-border);
    margin: 0 2px;
}

.u-section {
    margin-bottom: 26px;
}

.u-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 6px;
}

.u-section-head h3 {
    font-size: 16px;
    font-weight: 800;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--u-text);
}

.u-section-head h3 i {
    color: var(--u-brand);
}

.u-section-head .u-sub {
    font-size: 12px;
    color: var(--u-muted);
}

.u-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--u-brand);
    margin-bottom: 10px;
}

/* trailing divider line — same motif as sidebar captions */
.u-eyebrow::after {
    content: '';
    width: 64px;
    height: 1.5px;
    border-radius: 2px;
    background: linear-gradient(90deg, rgba(94, 141, 61, .45), transparent);
    margin-left: 4px;
}

.u-eyebrow.hr {
    color: var(--u-hr);
}

.u-eyebrow.hr::after {
    background: linear-gradient(90deg, rgba(79, 163, 224, .45), transparent);
}

.u-eyebrow .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.u-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 14px;
}

.u-kpi-card {
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(45deg, rgba(203, 255, 205, .45), transparent 55%),
        var(--u-surface);
    border: 1px solid var(--u-border);
    border-radius: 16px;
    box-shadow: var(--u-shadow);
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    border-top: 3px solid var(--u-brand);
    transition: transform .2s, box-shadow .2s, border-color .2s;
}

/* floating dashed deco ring — same motif as HR stat tiles */
.u-kpi-card::after {
    content: '';
    position: absolute;
    right: -16px;
    bottom: -20px;
    width: 72px;
    height: 72px;
    border-radius: 50%;
    border: 1.5px dashed rgba(94, 141, 61, .32);
    opacity: .75;
    transition: transform .3s;
    pointer-events: none;
}

.u-kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 22px 40px -18px rgba(8, 48, 31, .4);
    border-color: rgba(94, 141, 61, .45);
}

.u-kpi-card:hover::after {
    transform: rotate(22deg) scale(1.1);
}

.u-kpi-card.hr-kpi {
    border-top-color: var(--u-hr);
}

.u-kpi-ico {
    width: 44px;
    height: 44px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: linear-gradient(135deg, #7CA243, #1F5C2E);
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 10px 18px -8px rgba(14, 107, 75, .55), inset 0 1.5px 0 rgba(255, 255, 255, .3);
    transition: transform .2s;
}

.u-kpi-card:hover .u-kpi-ico {
    transform: rotate(-6deg) scale(1.07);
}

.u-kpi-card.hr-kpi .u-kpi-ico {
    background: linear-gradient(135deg, #4FA3E0, #1D6FA5);
    box-shadow: 0 10px 18px -8px rgba(29, 111, 165, .5), inset 0 1.5px 0 rgba(255, 255, 255, .3);
}

.u-kpi-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--u-muted);
    letter-spacing: .3px;
}

.u-kpi-value {
    font-size: 19px;
    font-weight: 800;
    color: var(--u-text);
    margin-top: 2px;
}

.u-card {
    background: var(--u-surface);
    border: 1px solid var(--u-border);
    border-radius: var(--u-radius);
    box-shadow: var(--u-shadow);
    padding: 18px 20px;
}

.u-chart-row {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 18px;
}

@media (max-width: 991px) {
    .u-chart-row {
        grid-template-columns: 1fr;
    }
}

.u-chart-wrap {
    position: relative;
    height: 250px;
}

.u-order-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
}

.u-order-card {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    padding: 13px 15px;
    color: var(--u-text);
    display: flex;
    flex-direction: column;
    gap: 3px;
    background:
        linear-gradient(45deg, rgba(203, 255, 205, .35), transparent 60%),
        #fff;
    border: 1px solid #dde8e1;
    box-shadow: 0 5px 18px rgba(15, 81, 50, .08);
    transition: transform .2s, box-shadow .2s, border-color .2s;
}

/* colored accent bar echoing the status color */
.u-order-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--oc, #5E8D3D);
    opacity: .9;
}

/* floating icon chip, top-right */
.u-order-card .i {
    position: absolute;
    right: 12px;
    top: 12px;
    width: 34px;
    height: 34px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #fff;
    background: var(--oc, #5E8D3D);
    box-shadow: 0 8px 14px -8px rgba(8, 48, 31, .5), inset 0 1.5px 0 rgba(255, 255, 255, .3);
    transition: transform .2s;
}

.u-order-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 36px -16px rgba(8, 48, 31, .38);
    border-color: rgba(94, 141, 61, .45);
}

.u-order-card:hover .i {
    transform: rotate(-7deg) scale(1.1);
}

.u-order-card .n {
    font-size: 21px;
    font-weight: 800;
    font-family: 'Kanit', 'Outfit', sans-serif;
    color: var(--u-brand-dark);
    margin-right: 34px;
}

.u-order-card .l {
    font-size: 10px;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: .07em;
    color: var(--u-muted);
}

/* per-status accent colors (used by --oc) */
.u-order-total { --oc: #1F5C2E; }
.u-order-pending { --oc: #C07E08; }
.u-order-approved { --oc: #12805C; }
.u-order-dispatched { --oc: #1D6FA5; }
.u-order-shipped { --oc: #6D3FBF; }
.u-order-delivered { --oc: #0F8A6D; }
.u-order-completed { --oc: #4E7A33; }
.u-order-returned { --oc: #C03434; }

/* (per-status gradient fills removed — the light widget design with
   the --oc accent color handles status colors now, keeping text readable) */

.u-payment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
}

.u-payment-card {
    border: 1px solid var(--u-border);
    border-radius: 14px;
    overflow: hidden;
    transition: box-shadow .15s ease, transform .15s ease;
}

.u-payment-card:hover {
    box-shadow: var(--u-shadow);
    transform: translateY(-1px);
}

.u-payment-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    text-decoration: none;
    border-bottom: 1px solid var(--u-border);
    background: #fafcfb;
}

.u-payment-head:hover {
    text-decoration: none;
    background: #f1f8f4;
}

.u-payment-ico {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    background: var(--u-hr-light);
    color: var(--u-hr);
}

.u-payment-title {
    min-width: 0;
    flex: 1;
}

.u-payment-title .mode {
    font-weight: 700;
    font-size: 12.5px;
    color: var(--u-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.u-payment-title .count {
    font-size: 10.5px;
    color: var(--u-muted);
}

.u-payment-head .chevron {
    color: var(--u-muted);
    font-size: 11px;
    flex-shrink: 0;
}

.u-payment-bar {
    height: 6px;
    background: #fde68a;
    display: flex;
}

.u-payment-bar .paid-fill {
    background: linear-gradient(90deg, #A8CB6A, #7CA243);
    height: 100%;
}

.u-payment-stats {
    display: flex;
}

.u-payment-stat {
    flex: 1;
    padding: 10px 14px;
    text-decoration: none;
    display: block;
    border-right: 1px solid var(--u-border);
}

.u-payment-stat:last-child {
    border-right: none;
}

.u-payment-stat:hover {
    background: #f8faf9;
    text-decoration: none;
}

.u-payment-stat .l {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.u-payment-stat .v {
    font-size: 17px;
    font-weight: 800;
    margin-top: 2px;
}

.u-payment-stat.pending .l {
    color: #b45309;
}

.u-payment-stat.pending .v {
    color: #d97706;
}

.u-payment-stat.paid .l {
    color: #1F5C2E;
}

.u-payment-stat.paid .v {
    color: #7CA243;
}

/* Right-hand "at a glance" rail */
.u-rail {
    position: sticky;
    top: 16px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.u-rail-card {
    background: var(--u-surface);
    border: 1px solid var(--u-border);
    border-radius: var(--u-radius);
    box-shadow: var(--u-shadow);
    padding: 16px 18px;
}

.u-rail-card h4 {
    font-size: 13px;
    font-weight: 800;
    margin: 0 0 12px;
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--u-text);
}

.u-rail-card h4 i {
    color: var(--u-hr);
    font-size: 12px;
}

.u-rail-badge {
    margin-left: auto;
    background: var(--u-hr-light);
    color: var(--u-hr);
    font-size: 10.5px;
    font-weight: 800;
    padding: 2px 9px;
    border-radius: 20px;
}

.u-bar-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 12px;
}

.u-bar-row:last-child {
    margin-bottom: 0;
}

.u-bar-label {
    width: 95px;
    flex-shrink: 0;
    color: var(--u-text);
    font-weight: 600;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.u-bar-track {
    flex: 1;
    height: 7px;
    border-radius: 6px;
    background: #eef2ef;
    overflow: hidden;
}

.u-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--u-hr), #0891b2);
    border-radius: 6px;
}

.u-bar-value {
    width: 22px;
    text-align: right;
    font-weight: 700;
    color: var(--u-text);
}

.u-approval-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 9px 0;
    border-bottom: 1px solid var(--u-border);
    font-size: 12px;
}

.u-approval-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.u-approval-row:first-child {
    padding-top: 0;
}

.u-av {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--u-hr-light);
    color: var(--u-hr);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 10.5px;
    flex-shrink: 0;
}

.u-approval-name {
    font-weight: 700;
    color: var(--u-text);
}

.u-approval-detail {
    color: var(--u-muted);
    font-size: 11px;
}

.u-approval-info {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 0;
}

.u-approval-info>div {
    min-width: 0;
}

.u-approval-name,
.u-approval-detail {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.u-review-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.u-review-actions form {
    margin: 0;
}

.u-review-btn {
    font-size: 10.5px;
    font-weight: 700;
    border-radius: 7px;
    padding: 5px 9px;
    border: 1px solid var(--u-border);
    background: #fff;
    color: var(--u-text);
    cursor: pointer;
}

.u-review-btn.approve {
    color: #7CA243;
    border-color: #a7f3d0;
}

.u-review-btn.approve:hover {
    background: #ecfdf5;
}

.u-review-btn.reject {
    color: #dc2626;
    border-color: #fecaca;
}

.u-review-btn.reject:hover {
    background: #fef2f2;
}

.u-holiday-inline {
    display: flex;
    align-items: center;
    gap: 12px;
}

.u-holiday-inline .ico {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--u-hr-light);
    color: var(--u-hr);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.u-holiday-inline .name {
    font-size: 13.5px;
    font-weight: 800;
    color: var(--u-text);
}

.u-holiday-inline .meta {
    font-size: 11px;
    color: var(--u-muted);
    margin-top: 1px;
}

.u-mini-kpi-row {
    display: flex;
    gap: 10px;
}

.u-mini-kpi {
    flex: 1;
    text-align: center;
    background: var(--u-hr-light);
    border-radius: 12px;
    padding: 12px 8px;
}

.u-mini-kpi .v {
    font-size: 16px;
    font-weight: 800;
    color: var(--u-hr);
}

.u-mini-kpi .l {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--u-muted);
    margin-top: 2px;
}

.u-empty {
    text-align: center;
    padding: 20px 10px;
    color: var(--u-muted);
    font-size: 12px;
}

/* Notices ticker — same spot as the HR dashboard: right under the hero */
.u-ticker {
    background: var(--u-surface);
    border: 1px solid var(--u-border);
    border-radius: 14px;
    box-shadow: var(--u-shadow);
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.u-ticker-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--u-brand);
    flex-shrink: 0;
}

.u-ticker-items {
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
    flex: 1;
    min-width: 0;
}

.u-ticker-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--u-text);
    white-space: nowrap;
}

.u-ticker-item b {
    font-weight: 700;
}

.u-ticker-item .t-date {
    color: var(--u-muted);
    font-size: 11px;
}

.u-ticker-link {
    font-size: 11.5px;
    font-weight: 700;
    color: var(--u-brand);
    text-decoration: none;
    flex-shrink: 0;
    margin-left: auto;
}

.u-ticker-link:hover {
    text-decoration: underline;
}

/* Check-in / check-out — top of hero, mirrors the HR dashboard's control */
.u-hero-check {
    margin-top: 10px;
}

.u-ci-btn {
    border: none;
    border-radius: 10px;
    padding: 9px 16px;
    font-size: 12.5px;
    font-weight: 700;
    background: #fff;
    color: var(--u-brand-dark);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.u-ci-btn.out {
    background: #fde68a;
    color: #92400e;
}

.u-ci-done {
    color: #fff;
    font-size: 12.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    opacity: .95;
}

.u-ci-status {
    color: #fff;
    font-size: 12.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    opacity: .95;
}

.u-ci-status a {
    color: #fff;
    text-decoration: underline;
    font-weight: 700;
}

.u-ci-pulse {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #34d399;
    display: inline-block;
}

/* ============================================================
   Entrance choreography — page feels alive on load
   ============================================================ */
@keyframes uRise {
    from {
        opacity: 0;
        transform: translateY(16px);
    }

    to {
        opacity: 1;
        transform: none;
    }
}

.u-hero,
.u-ticker,
.u-qa-strip {
    animation: uRise .5s ease backwards;
}

.u-ticker {
    animation-delay: .08s;
}

.u-qa-strip {
    animation-delay: .14s;
}

.u-eyebrow {
    animation: uRise .45s ease backwards;
}

.u-kpi-card {
    animation: uRise .5s ease backwards;
}

/* stagger the KPI cards left-to-right */
.u-kpi-grid .u-kpi-card:nth-child(1) { animation-delay: .18s; }
.u-kpi-grid .u-kpi-card:nth-child(2) { animation-delay: .24s; }
.u-kpi-grid .u-kpi-card:nth-child(3) { animation-delay: .3s; }
.u-kpi-grid .u-kpi-card:nth-child(4) { animation-delay: .36s; }
.u-kpi-grid .u-kpi-card:nth-child(5) { animation-delay: .42s; }
.u-kpi-grid .u-kpi-card:nth-child(6) { animation-delay: .48s; }

.u-card {
    animation: uRise .55s ease .3s backwards;
}

/* chart / donut cards get the mint wash + ring motif too */
.u-card {
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(45deg, rgba(203, 255, 205, .3), transparent 55%),
        var(--u-surface);
}

.u-card::after {
    content: '';
    position: absolute;
    right: -22px;
    top: -22px;
    width: 88px;
    height: 88px;
    border-radius: 50%;
    border: 1.5px dashed rgba(94, 141, 61, .28);
    opacity: .8;
    pointer-events: none;
}

@media (prefers-reduced-motion: reduce) {

    .u-hero,
    .u-ticker,
    .u-qa-strip,
    .u-eyebrow,
    .u-kpi-card,
    .u-card {
        animation: none;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    /* Count-up numbers — pure sugar: falls back to the server-rendered
       value if JS is off, and respects reduced motion. */
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!reduce && 'IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                io.unobserve(e.target);
                countUp(e.target);
            });
        }, { threshold: .4 });

        document.querySelectorAll('.u-kpi-value').forEach(function (el) {
            io.observe(el);
        });
    }

    function countUp(el) {
        var raw = (el.textContent || '').trim();
        /* Match ₹1,234.56 / 1,234 / 0 / 4 style values; skip mixed text like "0 / 4" */
        var m = raw.match(/^(₹)?([\d,]+(?:\.\d+)?)(.*)$/);
        if (!m || /\//.test(raw)) return;
        var sym = m[1] || '';
        var end = parseFloat(m[2].replace(/,/g, ''));
        if (!isFinite(end) || end === 0) return;
        var suffix = m[3] || '';
        var decimals = (m[2].indexOf('.') >= 0) ? 2 : 0;
        var dur = 900, t0 = null;

        function frame(t) {
            if (!t0) t0 = t;
            var p = Math.min((t - t0) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            var val = end * eased;
            el.textContent = sym + val.toLocaleString('en-IN', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }) + suffix;
            if (p < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    }
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
// Per-designation card visibility (Admin > Designations > Edit).
// null = unrestricted; array = only these card keys are shown.
$visibleCards = $visibleCards ?? null;
$show = fn (string $key): bool => $visibleCards === null || in_array($key, $visibleCards, true);

$showQaSales = $show('qa_sales_orders');
$showQaCustomers = $show('qa_customers');
$showQaHr = $hasHrAccess && $show('hr_quick_actions');
$showQaStrip = $showQaSales || $showQaCustomers || $showQaHr;

$showKpiSales = $show('kpi_customers') || $show('kpi_todays_sales') || $show('kpi_total_sales');
$showKpiHr = $hasHrAccess && $show('hr_kpis') && (isset($hrKpis) || isset($myHrSnapshot));
$showKpiSection = $showKpiSales || $showKpiHr;

$showMain = $show('order_lifecycle') || $show('payment_overview');
$showRailApprovals = $show('hr_pending_approvals') && isset($hrPendingApprovals);
$showRailDistribution = $show('hr_distribution') && isset($deptDistribution);
$showRailSnapshot = $show('hr_snapshot') && !isset($hrPendingApprovals) && !isset($deptDistribution) && isset($myHrSnapshot);
$showRailHoliday = $show('hr_next_holiday') && !empty($upcomingHoliday);
$showRail = $hasHrAccess && ($showRailApprovals || $showRailDistribution || $showRailSnapshot || $showRailHoliday);
?>
<?php
$hour = (int) now()->format('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening' );
    $firstName=explode(' ', $user->name ?? ' there')[0]; ?>  <div class="u-hero">
    <div>
        <h2><?php echo e($greeting); ?>, <?php echo e($firstName); ?> <i class="fa-solid fa-seedling u-wave" style="font-size:15px;"></i></h2>
        <p>
            <?php if($hasHrAccess): ?>
            Sales, order, payment and HR lifecycle — all in one view.
            <?php else: ?>
            Complete sales, order and payment lifecycle overview.
            <?php endif; ?>
        </p>

        
        <?php if($hrAttendanceEligible ?? false): ?>
        <?php $in = $hrTodayAttendance->check_in ?? null; $out = $hrTodayAttendance->check_out ?? null; ?>
        <div class="u-hero-check">
            <?php if($in && $out): ?>
            <span class="u-ci-done"><i class="fa-solid fa-circle-check"></i>Checked in
                <?php echo e(\Illuminate\Support\Carbon::parse($in)->format('h:i A')); ?> &middot; Out
                <?php echo e(\Illuminate\Support\Carbon::parse($out)->format('h:i A')); ?></span>
            <?php else: ?>
            <form method="POST" action="<?php echo e($out ? route('hr.attendance.check-out') : route('hr.attendance.check-in')); ?>"
                style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="u-ci-btn <?php echo e($in ? 'out' : ''); ?>">
                    <i class="fa-solid <?php echo e($in ? 'fa-right-from-bracket' : 'fa-fingerprint'); ?>"></i>
                    <?php echo e($in ? 'Check out' : 'Check in'); ?>

                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php elseif($canFieldLogAttendance ?? false): ?>
        <div class="u-hero-check">
            <span class="u-ci-status">
                <span class="u-ci-pulse"></span>
                <?php if($checkInTime && $checkOutTime): ?>
                Field day complete &middot; In <?php echo e(\Illuminate\Support\Carbon::parse($checkInTime)->format('h:i A')); ?>

                &middot; Out <?php echo e(\Illuminate\Support\Carbon::parse($checkOutTime)->format('h:i A')); ?>

                <?php elseif($checkInTime): ?>
                On the field since <?php echo e(\Illuminate\Support\Carbon::parse($checkInTime)->format('h:i A')); ?>

                <?php else: ?>
                Not checked in today
                <?php endif; ?>
                &middot; <a
                    href="<?php echo e(route('admin.field-log.index')); ?>"><?php echo e($checkInTime && !$checkOutTime ? 'Check out' : ($checkInTime ? 'View' : 'Check in')); ?>

                    on Field Activity</a>
            </span>
        </div>
        <?php endif; ?>
    </div>
    <div class="u-hero-date"><i class="fa-regular fa-clock"
            style="margin-right:6px;"></i><?php echo e(now()->format('l, d M Y')); ?></div>
    </div>

    
    <?php if($hasHrAccess && $show('hr_notices') && isset($tickerAnnouncements) && $tickerAnnouncements->isNotEmpty()): ?>
    <div class="u-ticker">
        <span class="u-ticker-label"><i class="fa-solid fa-bullhorn"></i> Notices</span>
        <div class="u-ticker-items">
            <?php $__currentLoopData = $tickerAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="u-ticker-item"><i class="fa-solid fa-circle"
                    style="font-size:5px;"></i><b><?php echo e($ta->title); ?></b><span
                    class="t-date"><?php echo e(\Illuminate\Support\Carbon::parse($ta->published_at ?? $ta->created_at)->format('d M')); ?></span></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <a href="<?php echo e(route('hr.announcements.index')); ?>" class="u-ticker-link">All notices <i
                class="fa-solid fa-chevron-right"></i></a>
    </div>
    <?php endif; ?>

    
    <?php if($showQaStrip): ?>
    <div class="u-qa-strip">
        <span class="u-qa-eyebrow">Quick actions</span>
        <?php if($showQaSales): ?>
        <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="u-qa-btn"><i class="fa-solid fa-cart-shopping"></i>
            Sales Orders</a>
        <?php endif; ?>
        <?php if($showQaCustomers): ?>
        <a href="<?php echo e(route('admin.customers.index')); ?>" class="u-qa-btn"><i class="fa-solid fa-users"></i> Customers</a>
        <?php endif; ?>
        <?php if($showQaHr): ?>
        <?php if($showQaSales || $showQaCustomers): ?>
        <div class="u-qa-divider"></div>
        <?php endif; ?>
        <?php if(isset($quickActionsHr)): ?>
        <?php $__currentLoopData = $quickActionsHr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($qa['url']); ?>" class="u-qa-btn hr"><i class="fa-solid fa-arrow-up-right-from-square"></i>
            <?php echo e($qa['label']); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <a href="<?php echo e(route('hr.leave.index')); ?>" class="u-qa-btn hr"><i class="fa-solid fa-plane-departure"></i> Apply
            for Leave</a>
        <a href="<?php echo e(route('hr.wfh.index')); ?>" class="u-qa-btn hr"><i class="fa-solid fa-house-laptop"></i> Request
            WFH</a>
        <a href="<?php echo e(route('hr.payroll.index')); ?>" class="u-qa-btn hr"><i class="fa-solid fa-file-invoice-dollar"></i>
            View Payslip</a>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($showKpiSection): ?>
    <div class="u-section">
        <?php if($showKpiSales): ?>
        <div class="u-eyebrow"><span class="dot"></span> Sales at a glance</div>
        <div class="u-kpi-grid">
            <?php if($show('kpi_customers')): ?>
            <div class="u-kpi-card">
                <div class="u-kpi-ico"><i class="fa-solid fa-users"></i></div>
                <div>
                    <div class="u-kpi-label">Total Customers</div>
                    <div class="u-kpi-value"><?php echo e(number_format($totalCustomers)); ?></div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($show('kpi_todays_sales')): ?>
            <div class="u-kpi-card">
                <div class="u-kpi-ico"><i class="fa-solid fa-calendar-day"></i></div>
                <div>
                    <div class="u-kpi-label">Today's Sales</div>
                    <div class="u-kpi-value">₹<?php echo e(number_format($todaysSalesValue, 2)); ?></div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($show('kpi_total_sales')): ?>
            <div class="u-kpi-card">
                <div class="u-kpi-ico"><i class="fa-solid fa-chart-line"></i></div>
                <div>
                    <div class="u-kpi-label">Total Sales</div>
                    <div class="u-kpi-value">₹<?php echo e(number_format($totalSalesValue, 2)); ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if($showKpiHr && isset($hrKpis)): ?>
        <div class="u-eyebrow hr" style="<?php echo e($showKpiSales ? 'margin-top:18px;' : ''); ?>"><span class="dot"></span> HR at a glance</div>
        <div class="u-kpi-grid">
            <?php $__currentLoopData = $hrKpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="u-kpi-card hr-kpi">
                <div class="u-kpi-ico"><i class="<?php echo e($kpi['icon']); ?>"></i></div>
                <div>
                    <div class="u-kpi-label"><?php echo e($kpi['label']); ?></div>
                    <div class="u-kpi-value"><?php echo e($kpi['value']); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php elseif($showKpiHr && isset($myHrSnapshot)): ?>
        <div class="u-eyebrow hr" style="<?php echo e($showKpiSales ? 'margin-top:18px;' : ''); ?>"><span class="dot"></span> My HR at a glance</div>
        <div class="u-kpi-grid">
            <div class="u-kpi-card hr-kpi">
                <div class="u-kpi-ico"><i class="fa-solid fa-user-check"></i></div>
                <div>
                    <div class="u-kpi-label"><?php echo e($myHrSnapshot['attendanceLabel'] ?? 'My Attendance (this month)'); ?>

                    </div>
                    <div class="u-kpi-value"><?php echo e($myHrSnapshot['attendance']); ?></div>
                </div>
            </div>
            <div class="u-kpi-card hr-kpi">
                <div class="u-kpi-ico"><i class="fa-solid fa-plane-departure"></i></div>
                <div>
                    <div class="u-kpi-label">My Leave Balance</div>
                    <div class="u-kpi-value"><?php echo e($myHrSnapshot['leaveBalance']); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($show('sales_graphs')): ?>
    <div class="u-section">
        <div class="u-section-head">
            <h3><i class="fa-solid fa-chart-column"></i> Sales Graphs</h3>
            <span class="u-sub">Last 7 days &middot; order mix</span>
        </div>
        <div class="u-chart-row">
            <div class="u-card">
                <div class="u-chart-wrap"><canvas id="uSalesTrendChart"></canvas></div>
            </div>
            <div class="u-card">
                <div class="u-chart-wrap"><canvas id="uOrderStatusChart"></canvas></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($showMain || $showRail): ?>
    <div class="row g-4">
        <?php if($showMain): ?>
        <div class="<?php echo e($showRail ? 'col-lg-8' : 'col-12'); ?>">

            
            <?php if($show('order_lifecycle')): ?>
            <div class="u-section">
                <div class="u-section-head">
                    <h3><i class="fa-solid fa-truck-fast"></i> Order Lifecycle</h3>
                    <span class="u-sub">Click a status to view corresponding orders</span>
                </div>
                <div class="u-order-grid">
                    <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="text-decoration-none">
                        <div class="u-order-card u-order-total"><span class="i"><i class="fa-solid fa-layer-group"></i></span><span
                                class="n"><?php echo e(number_format($totalOrders)); ?></span><span class="l">Total Orders</span>
                        </div>
                    </a>
                    <a href="<?php echo e(route('admin.salesorders.index', ['status' => 'pending'])); ?>"
                        class="text-decoration-none">
                        <div class="u-order-card u-order-pending"><span class="i"><i class="fa-solid fa-hourglass-half"></i></span><span class="n"><?php echo e($pendingOrders); ?></span><span
                                class="l">Pending</span></div>
                    </a>
                    <a href="<?php echo e(route('admin.salesorders.index', ['status' => 'approved'])); ?>"
                        class="text-decoration-none">
                        <div class="u-order-card u-order-approved"><span class="i"><i class="fa-solid fa-circle-check"></i></span><span class="n"><?php echo e($approvedOrders); ?></span><span
                                class="l">Approved</span></div>
                    </a>
                    <a href="<?php echo e(route('admin.salesorders.index', ['status' => 'dispatched'])); ?>"
                        class="text-decoration-none">
                        <div class="u-order-card u-order-dispatched"><span class="i"><i class="fa-solid fa-box-open"></i></span><span class="n"><?php echo e($dispatchedOrders); ?></span><span
                                class="l">Dispatched</span></div>
                    </a>
                    <a href="<?php echo e(route('admin.salesorders.index', ['status' => 'shipped'])); ?>"
                        class="text-decoration-none">
                        <div class="u-order-card u-order-shipped"><span class="i"><i class="fa-solid fa-truck"></i></span><span class="n"><?php echo e($shippedOrders); ?></span><span
                                class="l">Shipped</span></div>
                    </a>
                    <a href="<?php echo e(route('admin.salesorders.index', ['status' => 'delivered'])); ?>"
                        class="text-decoration-none">
                        <div class="u-order-card u-order-delivered"><span class="i"><i class="fa-solid fa-house-circle-check"></i></span><span class="n"><?php echo e($deliveredOrders); ?></span><span
                                class="l">Delivered</span></div>
                    </a>
                    <a href="<?php echo e(route('admin.salesorders.index', ['status' => 'completed'])); ?>"
                        class="text-decoration-none">
                        <div class="u-order-card u-order-completed"><span class="i"><i class="fa-solid fa-flag-checkered"></i></span><span class="n"><?php echo e($completedOrders); ?></span><span
                                class="l">Completed</span></div>
                    </a>
                    <a href="<?php echo e(route('admin.salesorders.index', ['status' => 'returned'])); ?>"
                        class="text-decoration-none">
                        <div class="u-order-card u-order-returned"><span class="i"><i class="fa-solid fa-rotate-left"></i></span><span class="n"><?php echo e($returnedOrders); ?></span><span
                                class="l">Returned</span></div>
                    </a>
                </div>
            </div>

            <?php endif; ?>

            
            <?php if($show('payment_overview')): ?>
            <div class="u-section" style="margin-bottom:0;">
                <div class="u-section-head">
                    <h3><i class="fa-solid fa-money-check-dollar"></i> Payment Overview</h3>
                    <span class="u-sub">Click a mode or a status to view corresponding orders</span>
                </div>
                <div class="u-payment-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $paymentOverview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => $counts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                    $modeIcon = match(true) {
                    str_contains(strtolower($mode), 'bank') => 'fa-solid fa-building-columns',
                    str_contains(strtolower($mode), 'cash') => 'fa-solid fa-money-bill-wave',
                    str_contains(strtolower($mode), 'franchise') => 'fa-solid fa-store',
                    str_contains(strtolower($mode), 'upi') => 'fa-solid fa-mobile-screen-button',
                    default => 'fa-solid fa-wallet',
                    };
                    $paidPct = $counts['total'] > 0 ? round($counts['paid'] / $counts['total'] * 100) : 0;
                    ?>
                    <div class="u-payment-card">
                        <a href="<?php echo e(route('admin.salesorders.index', ['c_mode_of_payment' => $mode])); ?>"
                            class="u-payment-head">
                            <div class="u-payment-ico"><i class="<?php echo e($modeIcon); ?>"></i></div>
                            <div class="u-payment-title">
                                <div class="mode"><?php echo e($mode); ?></div>
                                <div class="count"><?php echo e($counts['total']); ?> order<?php echo e($counts['total'] == 1 ? '' : 's'); ?>

                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right chevron"></i>
                        </a>
                        <div class="u-payment-bar">
                            <div class="paid-fill" style="width:<?php echo e($paidPct); ?>%;"></div>
                        </div>
                        <div class="u-payment-stats">
                            <a href="<?php echo e(route('admin.salesorders.index', ['c_mode_of_payment' => $mode, 'payment_status' => 'pending'])); ?>"
                                class="u-payment-stat pending">
                                <div class="l"><i class="fa-solid fa-hourglass-half"></i> Pending</div>
                                <div class="v"><?php echo e($counts['pending']); ?></div>
                            </a>
                            <a href="<?php echo e(route('admin.salesorders.index', ['c_mode_of_payment' => $mode, 'payment_status' => 'paid'])); ?>"
                                class="u-payment-stat paid">
                                <div class="l"><i class="fa-solid fa-circle-check"></i> Paid</div>
                                <div class="v"><?php echo e($counts['paid']); ?></div>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="u-empty">No payment data yet.</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
        <?php endif; ?>

        
        <?php if($showRail): ?>
        <div class="<?php echo e($showMain ? 'col-lg-4' : 'col-12'); ?>">
            <div class="u-rail">

                <?php if($showRailApprovals): ?>
                <div class="u-rail-card">
                    <h4><i class="fa-solid fa-clipboard-check"></i> Pending Approvals <span
                            class="u-rail-badge"><?php echo e($hrPendingApprovals->count()); ?></span></h4>
                    <?php $__empty_1 = true; $__currentLoopData = $hrPendingApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="u-approval-row">
                        <div class="u-approval-info">
                            <div class="u-av"><?php echo e(strtoupper(substr($p['employee'], 0, 1))); ?></div>
                            <div>
                                <div class="u-approval-name"><?php echo e($p['employee']); ?></div>
                                <div class="u-approval-detail"><?php echo e($p['detail']); ?></div>
                            </div>
                        </div>
                        <div class="u-review-actions">
                            <form method="POST" action="<?php echo e($p['route']); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="u-review-btn approve">Approve</button>
                            </form>
                            <form method="POST" action="<?php echo e($p['route']); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="u-review-btn reject">Reject</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="u-empty" style="padding:8px 0;"><i class="fa-solid fa-circle-check"
                            style="display:block;font-size:18px;margin-bottom:4px;color:var(--u-brand);"></i>All caught
                        up.</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if($showRailDistribution): ?>
                <div class="u-rail-card">
                    <h4><i class="fa-solid fa-chart-pie"></i> Employee Distribution</h4>
                    <?php $__empty_1 = true; $__currentLoopData = $deptDistribution['departments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="u-bar-row">
                        <span class="u-bar-label"><?php echo e($d->name); ?></span>
                        <div class="u-bar-track">
                            <div class="u-bar-fill"
                                style="width:<?php echo e(round($d->employees_count / $deptDistribution['max'] * 100)); ?>%;"></div>
                        </div>
                        <span class="u-bar-value"><?php echo e($d->employees_count); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="u-empty">No department data yet.</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if($showRailSnapshot): ?>
                <div class="u-rail-card">
                    <h4><i class="fa-solid fa-id-badge"></i> My HR Snapshot</h4>
                    <div class="u-mini-kpi-row">
                        <div class="u-mini-kpi">
                            <div class="v"><?php echo e($myHrSnapshot['attendance']); ?></div>
                            <div class="l">
                                <?php echo e(isset($myHrSnapshot['attendanceLabel']) && str_contains($myHrSnapshot['attendanceLabel'], 'Field Log') ? 'Field Log' : 'Attendance'); ?>

                            </div>
                        </div>
                        <div class="u-mini-kpi">
                            <div class="v"><?php echo e($myHrSnapshot['leaveBalance']); ?></div>
                            <div class="l">Leave Left</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($showRailHoliday): ?>
                <div class="u-rail-card">
                    <h4><i class="fa-solid fa-umbrella-beach"></i> Next Holiday</h4>
                    <div class="u-holiday-inline">
                        <div class="ico"><i class="fa-solid fa-umbrella-beach"></i></div>
                        <div>
                            <div class="name"><?php echo e($upcomingHoliday->name); ?></div>
                            <div class="meta">
                                <?php echo e(\Illuminate\Support\Carbon::parse($upcomingHoliday->holiday_date)->format('d M Y (D)')); ?>

                                &middot; in <?php echo e(now()->startOfDay()->diffInDays($upcomingHoliday->holiday_date)); ?> days
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php $__env->stopSection(); ?>

    
    <?php if(($visibleCards ?? null) === null || in_array('sales_graphs', $visibleCards, true)): ?>
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales trend — 7 day line chart
        var trendEl = document.getElementById('uSalesTrendChart');
        if (trendEl) new Chart(trendEl, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($salesTrendLabels, 15, 512) ?>,
                datasets: [{
                    label: 'Sales (₹)',
                    data: <?php echo json_encode($salesTrendValues, 15, 512) ?>,
                    borderColor: '#5E8D3D',
                    backgroundColor: 'rgba(15,81,50,0.08)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#5E8D3D',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Sales Trend (Last 7 Days)',
                        font: {
                            size: 13,
                            weight: '700'
                        },
                        color: '#14251c'
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => '₹' + v
                        }
                    },
                },
            },
        });

        // Order status — doughnut chart
        var mixEl = document.getElementById('uOrderStatusChart');
        if (mixEl) new Chart(mixEl, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($orderStatusChart['labels'], 15, 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($orderStatusChart['values'], 15, 512) ?>,
                    backgroundColor: ['#f59e0b', '#A8CB6A', '#3b82f6', '#8b5cf6', '#14b8a6',
                        '#0ea5e9', '#ef4444'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 11
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Order Status Mix',
                        font: {
                            size: 13,
                            weight: '700'
                        },
                        color: '#14251c'
                    },
                },
            },
        });
    });
    </script>
    <?php $__env->stopPush(); ?>
    <?php endif; ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/dashboard-unified.blade.php ENDPATH**/ ?>