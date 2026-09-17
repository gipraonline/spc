<?php $__env->startPush('styles'); ?>
<style>
:root {
    --brand: #0f5132;
    --brand-dark: #083b25;
    --brand-mid: #087a4d;
    --primary: #059669;
    --primary-light: #ecfdf5;

    --text: #14251c;
    --text-soft: #30443a;
    --muted: #708078;
    --muted-light: #9aa8a1;

    --background: #f4f7f5;
    --surface: #ffffff;
    --surface-soft: #f8faf9;

    --border: #e4ebe7;
    --border-light: #edf2ef;

    --orange: #ea580c;
    --orange-light: #fff7ed;

    --blue: #0284c7;
    --blue-light: #eff8ff;

    --purple: #7c3aed;
    --purple-light: #f5f3ff;

    --red: #dc2626;
    --red-light: #fef2f2;

    --green: #16a34a;
    --green-light: #ecfdf5;

    --shadow-xs: 0 1px 3px rgba(15, 81, 50, .035);
    --shadow-sm: 0 5px 18px rgba(15, 81, 50, .055);
    --shadow-md: 0 14px 34px rgba(15, 81, 50, .09);

    --radius: 18px;
}


/* =========================================================
   BASE
========================================================= */

* {
    box-sizing: border-box;
}

.content-wrapper {
    min-height: 100vh;
    padding: 28px;
    background:
        radial-gradient(circle at 100% 0%,
            rgba(5, 150, 105, .075),
            transparent 27%),
        radial-gradient(circle at 0% 45%,
            rgba(15, 81, 50, .035),
            transparent 25%),
        var(--background);
    color: var(--text);
}

.dashboard-container {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
}


/* =========================================================
   LINKS
========================================================= */

.card-link {
    display: block;
    color: inherit !important;
    text-decoration: none !important;
}

.card-link:hover,
.card-link:focus,
.card-link:active {
    color: inherit !important;
    text-decoration: none !important;
}

.clickable-card {
    cursor: pointer;
}


/* =========================================================
   HEADER / HERO
========================================================= */

.dashboard-header {
    position: relative;
    overflow: hidden;

    display: flex;
    justify-content: space-between;
    align-items: center;

    min-height: 150px;
    padding: 30px 32px;
    margin-bottom: 28px;

    border-radius: 24px;

    background:
        radial-gradient(circle at 88% 18%,
            rgba(255, 255, 255, .16),
            transparent 24%),
        radial-gradient(circle at 72% 100%,
            rgba(255, 255, 255, .07),
            transparent 25%),
        linear-gradient(135deg, #5A8D3A, #074E30);

    box-shadow:
        0 18px 42px rgba(15, 81, 50, .16);
}

.dashboard-header::before {
    content: "";
    position: absolute;

    width: 240px;
    height: 240px;

    right: -90px;
    top: -130px;

    border: 1px solid rgba(255, 255, 255, .10);
    border-radius: 50%;
}

.dashboard-header::after {
    content: "";
    position: absolute;

    width: 170px;
    height: 170px;

    right: 65px;
    bottom: -125px;

    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 50%;
}

.welcome-area {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;
    gap: 17px;
}

.welcome-avatar {
    width: 62px;
    height: 62px;
    min-width: 62px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid rgba(255, 255, 255, .22);
    border-radius: 18px;

    background: rgba(255, 255, 255, .13);
    color: #fff;

    font-size: 22px;
    font-weight: 800;

    box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, .12),
        0 8px 22px rgba(0, 0, 0, .08);

    backdrop-filter: blur(8px);
}

.dashboard-header h1 {
    margin: 0 0 7px;

    color: #fff;

    font-size: 28px;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -.6px;
}

.dashboard-header p {
    margin: 0;

    color: rgba(255, 255, 255, .72);

    font-size: 13px;
    line-height: 1.6;
}

.dashboard-date {
    position: relative;
    z-index: 2;

    display: inline-flex;
    align-items: center;
    gap: 9px;

    padding: 11px 15px;

    border: 1px solid rgba(255, 255, 255, .18);
    border-radius: 12px;

    background: rgba(255, 255, 255, .10);
    color: rgba(255, 255, 255, .92);

    font-size: 12px;
    font-weight: 700;

    backdrop-filter: blur(10px);
}

.dashboard-date::before {
    content: "";
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: #86efac;
    box-shadow: 0 0 0 4px rgba(134, 239, 172, .10);
}


/* =========================================================
   COMMON SECTION
========================================================= */

.dashboard-section {
    margin-bottom: 32px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;

    margin-bottom: 15px;
}

.section-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-indicator {
    width: 4px;
    height: 23px;

    border-radius: 5px;

    background: linear-gradient(to bottom,
            var(--brand),
            var(--primary));
}

.section-title {
    margin: 0;

    color: var(--text);

    font-size: 19px;
    line-height: 1.2;
    font-weight: 800;
    letter-spacing: -.25px;
}

.section-subtitle {
    margin: 4px 0 0 14px;

    color: var(--muted);

    font-size: 11px;
    line-height: 1.5;
}


/* =========================================================
   COMMON CARD
========================================================= */

.dashboard-card {
    position: relative;

    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);

    box-shadow: var(--shadow-xs);

    transition:
        transform .22s ease,
        box-shadow .22s ease,
        border-color .22s ease;
}

.dashboard-card:hover {
    border-color: #d5e2dc;
    box-shadow: var(--shadow-md);
}

.clickable-card:hover {
    transform: translateY(-4px);
}

/* =========================================================
   ENHANCED CARD COLORS
========================================================= */

.dashboard-card {
    background:
        linear-gradient(145deg, #ffffff 0%, #fbfdfc 100%);
    border: 1px solid #dfe9e4;
    box-shadow:
        0 3px 12px rgba(15, 81, 50, .045),
        0 1px 2px rgba(15, 81, 50, .025);

    transition:
        transform .22s ease,
        box-shadow .22s ease,
        border-color .22s ease,
        background .22s ease;
}

.dashboard-card:hover {
    border-color: #c9dcd3;
    box-shadow:
        0 14px 30px rgba(15, 81, 50, .10),
        0 3px 8px rgba(15, 81, 50, .04);
}


/* =========================================================
   ATTENDANCE CARD COLORS
========================================================= */

.attendance-card {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(5, 150, 105, .09),
            transparent 38%),
        linear-gradient(145deg, #ffffff, #f8fcfa);
}

.attendance-card:nth-child(1) {
    border-left: 4px solid #059669;
}

.attendance-card:nth-child(2) {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(234, 88, 12, .09),
            transparent 38%),
        linear-gradient(145deg, #ffffff, #fffaf6);

    border-left: 4px solid #ea580c;
}

.attendance-card:nth-child(3) {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(124, 58, 237, .08),
            transparent 38%),
        linear-gradient(145deg, #ffffff, #faf9ff);

    border-left: 4px solid #7c3aed;
}


/* =========================================================
   SALES CARD COLORS
========================================================= */

.sales-card {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(5, 150, 105, .075),
            transparent 42%),
        linear-gradient(145deg, #ffffff, #f8fcfa);
}

.sales-grid .sales-card:nth-child(2) {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(234, 88, 12, .075),
            transparent 42%),
        linear-gradient(145deg, #ffffff, #fffaf6);
}

.sales-grid .sales-card:nth-child(3) {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(2, 132, 199, .075),
            transparent 42%),
        linear-gradient(145deg, #ffffff, #f7fbfe);
}


/* =========================================================
   ORDER CARDS
========================================================= */

.order-card {
    background:
        linear-gradient(145deg, #ffffff 0%, #fafcfb 100%);
}

.order-card.pending {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(234, 88, 12, .08),
            transparent 40%),
        linear-gradient(145deg, #ffffff, #fffaf6);
}

.order-card.approved {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(5, 150, 105, .08),
            transparent 40%),
        linear-gradient(145deg, #ffffff, #f7fcfa);
}

.order-card.dispatched {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(2, 132, 199, .08),
            transparent 40%),
        linear-gradient(145deg, #ffffff, #f7fbfe);
}

.order-card.shipped {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(124, 58, 237, .08),
            transparent 40%),
        linear-gradient(145deg, #ffffff, #faf9ff);
}

.order-card.delivered {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(22, 163, 74, .08),
            transparent 40%),
        linear-gradient(145deg, #ffffff, #f7fcf8);
}

.order-card.completed {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(4, 120, 87, .09),
            transparent 40%),
        linear-gradient(145deg, #ffffff, #f6fcf9);
}

.order-card.returned {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(220, 38, 38, .075),
            transparent 40%),
        linear-gradient(145deg, #ffffff, #fff9f9);
}


/* =========================================================
   PAYMENT CARDS
========================================================= */

.payment-card {
    background:
        radial-gradient(circle at 100% 100%,
            rgba(2, 132, 199, .07),
            transparent 42%),
        linear-gradient(145deg, #ffffff, #f8fbfd);

    border-top: 3px solid #0284c7;
}

.payment-card:nth-child(2) {
    border-top-color: #059669;
}

.payment-card:nth-child(3) {
    border-top-color: #7c3aed;
}

.payment-card:nth-child(4) {
    border-top-color: #ea580c;
}


/* =========================================================
   ICONS — MORE DEPTH
========================================================= */

.attendance-icon,
.sales-icon,
.order-icon,
.payment-icon {
    box-shadow:
        inset 0 1px 2px rgba(255, 255, 255, .75),
        0 5px 12px rgba(15, 81, 50, .055);

    border: 1px solid rgba(255, 255, 255, .65);
}


/* =========================================================
   BETTER HOVER
========================================================= */

.attendance-card:hover,
.sales-card:hover,
.order-card:hover,
.payment-card:hover {
    transform: translateY(-4px);
}

.attendance-card:hover .attendance-icon,
.sales-card:hover .sales-icon,
.order-card:hover .order-icon {
    transform: scale(1.04);
    transition: transform .22s ease;
}


/* =========================================================
   ATTENDANCE
========================================================= */

.attendance-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;

    margin-bottom: 34px;
}

.attendance-card {
    min-height: 124px;

    display: flex;
    align-items: center;
    gap: 17px;

    padding: 22px;

    overflow: hidden;
}

.attendance-card::after {
    content: "";

    position: absolute;

    width: 100px;
    height: 100px;

    right: -48px;
    bottom: -52px;

    border-radius: 50%;

    background: rgba(5, 150, 105, .035);
}

.attendance-icon {
    width: 56px;
    height: 56px;
    min-width: 56px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;
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
    position: relative;
    z-index: 1;

    min-width: 0;

    display: flex;
    flex-direction: column;
    gap: 4px;
}

.attendance-label {
    color: var(--muted);

    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .45px;
}

.attendance-value {
    font-size: 25px;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -.5px;
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
    font-weight: 500;
}

.attendance-subtext.orange {
    color: var(--orange);
}


/* =========================================================
   SALES / KPI
========================================================= */

.sales-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.sales-grid.super-admin-sales-grid {
    grid-template-columns: minmax(280px, 420px);
}

.sales-card {
    position: relative;
    overflow: hidden;

    min-height: 140px;

    display: flex;
    align-items: center;
    gap: 18px;

    padding: 24px;
}

.sales-card::after {
    content: "";

    position: absolute;

    width: 150px;
    height: 150px;

    right: -75px;
    bottom: -78px;

    border-radius: 50%;

    background: rgba(5, 150, 105, .035);

    pointer-events: none;
}

.sales-icon {
    position: relative;
    z-index: 1;

    width: 58px;
    height: 58px;
    min-width: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 17px;
}

.sales-icon svg {
    width: 26px;
    height: 26px;
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

.sales-card>div:last-child {
    position: relative;
    z-index: 1;
}

.sales-label {
    display: block;

    margin-bottom: 6px;

    color: var(--muted);

    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.sales-value {
    display: block;

    color: var(--text);

    font-size: 28px;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -.7px;
}

.sales-caption {
    display: flex;
    align-items: center;
    gap: 5px;

    margin-top: 8px;

    color: var(--muted-light);

    font-size: 10px;
    font-weight: 600;
}

.sales-caption::after {
    content: "→";

    font-size: 12px;

    transition: transform .2s ease;
}

.sales-card:hover .sales-caption::after {
    transform: translateX(3px);
}


/* =========================================================
   ORDER LIFECYCLE
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

    min-height: 108px;

    display: flex;
    align-items: center;
    gap: 14px;

    padding: 19px;
}

.order-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 14px;
    bottom: 14px;

    width: 3px;

    border-radius: 0 6px 6px 0;
}

.order-card::after {
    content: "";

    position: absolute;

    width: 80px;
    height: 80px;

    right: -40px;
    bottom: -45px;

    border-radius: 50%;

    opacity: .45;
}

.order-icon {
    position: relative;
    z-index: 1;

    width: 47px;
    height: 47px;
    min-width: 47px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;
}

.order-icon svg {
    width: 22px;
    height: 22px;
}

.order-info {
    position: relative;
    z-index: 1;

    display: flex;
    flex-direction: column;
    gap: 5px;
}

.order-label {
    color: var(--muted);

    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .45px;
}

.order-count {
    color: var(--text);

    font-size: 26px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -.5px;
}


/* =========================================================
   ORDER STATUS COLORS
========================================================= */

.pending .order-icon {
    background: var(--orange-light);
    color: var(--orange);
}

.pending::before {
    background: var(--orange);
}

.pending::after {
    background: var(--orange-light);
}

.approved .order-icon {
    background: var(--primary-light);
    color: var(--primary);
}

.approved::before {
    background: var(--primary);
}

.approved::after {
    background: var(--primary-light);
}

.dispatched .order-icon {
    background: var(--blue-light);
    color: var(--blue);
}

.dispatched::before {
    background: var(--blue);
}

.dispatched::after {
    background: var(--blue-light);
}

.shipped .order-icon {
    background: var(--purple-light);
    color: var(--purple);
}

.shipped::before {
    background: var(--purple);
}

.shipped::after {
    background: var(--purple-light);
}

.delivered .order-icon {
    background: #ecfdf5;
    color: #16a34a;
}

.delivered::before {
    background: #16a34a;
}

.delivered::after {
    background: #ecfdf5;
}

.completed .order-icon {
    background: #dff8ec;
    color: #047857;
}

.completed::before {
    background: #047857;
}

.completed::after {
    background: #dff8ec;
}

.returned .order-icon {
    background: var(--red-light);
    color: var(--red);
}

.returned::before {
    background: var(--red);
}

.returned::after {
    background: var(--red-light);
}


/* =========================================================
   TOTAL ORDERS - FEATURE CARD
========================================================= */

.total-order-card {
    background:
        radial-gradient(circle at 90% 10%,
            rgba(255, 255, 255, .13),
            transparent 28%),
        linear-gradient(135deg, #5A8D3A, #074E30);

    border: none;

    box-shadow:
        0 10px 28px rgba(15, 81, 50, .16);
}

.total-order-card:hover {
    border-color: transparent;

    box-shadow:
        0 17px 35px rgba(15, 81, 50, .20);
}

.total-order-card::before {
    display: none;
}

.total-order-card::after {
    width: 130px;
    height: 130px;

    right: -65px;
    bottom: -68px;

    background: rgba(255, 255, 255, .06);
}

.total-order-card .order-icon {
    background: rgba(255, 255, 255, .13);
    color: #fff;

    border: 1px solid rgba(255, 255, 255, .10);
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
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    align-items: stretch;
}

.payment-card {
    min-width: 0;
    height: 100%;
    padding: 19px;
    display: flex;
    flex-direction: column;
}

.payment-card:hover {
    transform: translateY(-3px);
}

.payment-card-header {
    display: flex;
    align-items: center;
    gap: 12px;

    min-height: 62px;
    margin-bottom: 17px;
    padding-bottom: 15px;

    border-bottom: 1px solid var(--border-light);
}

.payment-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background: var(--blue-light);
    color: var(--blue);
}

.payment-icon svg {
    width: 21px;
    height: 21px;
}

.payment-mode {
    display: block;

    color: var(--text);

    font-size: 14px;
    font-weight: 800;

    text-transform: capitalize;
}

.payment-total {
    display: block;

    margin-top: 3px;

    color: var(--muted);

    font-size: 10px;
    font-weight: 600;
}

.payment-status-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;

    margin-top: auto;
}

.payment-status-link {
    display: block;
    min-width: 0;

    color: inherit !important;
    text-decoration: none !important;
}

.payment-status-disabled {
    display: block;
    min-width: 0;

    color: inherit;
    text-decoration: none;
}

.payment-status {
    min-height: 55px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 10px 12px;

    border-radius: 12px;

    transition:
        transform .2s ease,
        filter .2s ease;
}

.payment-status-link .payment-status:hover {
    transform: translateY(-2px);
    filter: brightness(.98);
}

.payment-status-label {
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.payment-status strong {
    font-size: 19px;
    line-height: 1;
    font-weight: 800;
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
    padding: 30px;

    color: var(--muted);

    text-align: center;
    font-size: 13px;
}


/* =========================================================
   PAYMENT — NON CLICKABLE USERS
========================================================= */

.payment-card-disabled .payment-card-header {
    cursor: default;
}

.payment-card-disabled .payment-status {
    cursor: default;
}

.payment-card-disabled:hover {
    transform: none;
}

@media (max-width: 1250px) {

    .payment-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 950px) {

    .payment-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 650px) {

    .payment-grid {
        grid-template-columns: 1fr;
        gap: 11px;
    }

    .payment-card {
        padding: 16px;
    }
}


/* =========================================================
   FOCUS ACCESSIBILITY
========================================================= */

.card-link:focus-visible {
    outline: 3px solid rgba(5, 150, 105, .22);
    outline-offset: 4px;
    border-radius: 18px;
}


/* =========================================================
   RESPONSIVE — LARGE TABLET
========================================================= */

@media (max-width: 1250px) {

    .admin-order-grid,
    .staff-order-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .payment-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}


/* =========================================================
   RESPONSIVE — TABLET
========================================================= */

@media (max-width: 950px) {

    .content-wrapper {
        padding: 22px;
    }

    .dashboard-header {
        min-height: 140px;
        padding: 25px;
    }

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


/* =========================================================
   RESPONSIVE — MOBILE
========================================================= */

@media (max-width: 650px) {

    .content-wrapper {
        padding: 13px;
    }

    .dashboard-header {
        min-height: auto;

        align-items: flex-start;

        padding: 22px 19px;

        margin-bottom: 22px;

        border-radius: 19px;
    }

    .welcome-area {
        gap: 12px;
    }

    .welcome-avatar {
        width: 48px;
        height: 48px;
        min-width: 48px;

        border-radius: 14px;

        font-size: 18px;
    }

    .dashboard-header h1 {
        font-size: 21px;
        letter-spacing: -.4px;
    }

    .dashboard-header p {
        max-width: 230px;

        font-size: 11px;
    }

    .dashboard-date {
        display: none;
    }

    .dashboard-section {
        margin-bottom: 25px;
    }

    .section-header {
        display: block;

        margin-bottom: 12px;
    }

    .section-title {
        font-size: 17px;
    }

    .section-subtitle {
        margin-top: 5px;
    }

    .attendance-grid,
    .sales-grid,
    .admin-order-grid,
    .staff-order-grid,
    .payment-grid {
        grid-template-columns: 1fr;
        gap: 11px;
    }

    .attendance-grid {
        margin-bottom: 27px;
    }

    .attendance-card {
        min-height: 104px;
        padding: 17px;
    }

    .attendance-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 14px;
    }

    .attendance-value {
        font-size: 22px;
    }

    .sales-card {
        min-height: 120px;
        padding: 19px;
    }

    .sales-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 15px;
    }

    .sales-value {
        font-size: 25px;
    }

    .order-card {
        min-height: 90px;
        padding: 16px;
    }

    .order-count {
        font-size: 24px;
    }

    .payment-card {
        padding: 16px;
    }
}

/* =========================================================
   SALES TREND — CLEAN ALIGNED LAYOUT
========================================================= */

.sales-trend-section {
    position: relative;
    overflow: hidden;

    margin-top: 14px;
    margin-bottom: 16px;

    padding: 20px 22px 18px;

    background: linear-gradient(145deg,
            #ffffff 0%,
            #f9fcfa 100%);

    border: 1px solid #dfe9e4;
    border-radius: 18px;

    box-shadow:
        0 4px 14px rgba(15, 81, 50, .045),
        0 1px 3px rgba(15, 81, 50, .025);
}


/* =========================================================
   HEADER
========================================================= */

.sales-trend-header {
    position: relative;
    z-index: 1;

    display: flex;
    align-items: center;
    justify-content: space-between;

    width: 100%;

    gap: 16px;

    margin-bottom: 16px;
}

.sales-trend-header>div:first-child {
    min-width: 0;
    flex: 1;
}

.sales-trend-header h3 {
    margin: 0;

    color: #173c32;

    font-size: 15px;
    line-height: 1.25;
    font-weight: 800;

    letter-spacing: -.2px;
}

.sales-trend-header p {
    margin: 5px 0 0;

    color: #81908b;

    font-size: 10px;
    line-height: 1.4;
}


/* =========================================================
   TOTAL
========================================================= */

.sales-trend-summary {
    flex: 0 0 auto;

    min-width: 125px;

    padding: 8px 12px;

    background: #f6fbf8;

    border: 1px solid #dfece6;
    border-radius: 11px;

    text-align: right;
}

.sales-trend-summary span {
    display: block;

    color: #81908b;

    font-size: 8px;
    line-height: 1.2;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .6px;
}

.sales-trend-summary strong {
    display: block;

    margin-top: 3px;

    color: #087a4d;

    font-size: 15px;
    line-height: 1.2;
    font-weight: 800;

    letter-spacing: -.2px;
}


/* =========================================================
   CHART
========================================================= */

.sales-chart-wrapper {
    position: relative;

    width: 100%;
    height: 225px;

    margin: 0;
    padding: 0;
}

.sales-chart-wrapper canvas {
    display: block;

    width: 100% !important;
    height: 100% !important;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 650px) {

    .sales-trend-section {
        margin-top: 12px;
        margin-bottom: 16px;

        padding: 17px 15px 15px;

        border-radius: 16px;
    }

    .sales-trend-header {
        align-items: center;

        gap: 10px;

        margin-bottom: 13px;
    }

    .sales-trend-header h3 {
        font-size: 14px;
    }

    .sales-trend-header p {
        margin-top: 4px;

        max-width: 190px;

        font-size: 9px;
    }

    .sales-trend-summary {
        min-width: 105px;

        padding: 7px 9px;
    }

    .sales-trend-summary strong {
        font-size: 13px;
    }

    .sales-chart-wrapper {
        height: 205px;
    }
}


/* =========================================================
   VERY SMALL DEVICES
========================================================= */

@media (max-width: 380px) {

    .sales-trend-section {
        padding: 15px 12px 13px;
    }

    .sales-trend-header {
        gap: 8px;

        margin-bottom: 11px;
    }

    .sales-trend-header p {
        max-width: 145px;
    }

    .sales-trend-summary {
        min-width: 92px;

        padding: 6px 8px;
    }

    .sales-trend-summary span {
        font-size: 7px;
    }

    .sales-trend-summary strong {
        font-size: 12px;
    }

    .sales-chart-wrapper {
        height: 190px;
    }
}


/* =========================================================
   VERY SMALL DEVICES
========================================================= */

@media (max-width: 380px) {

    .sales-trend-section {
        padding: 15px 12px 12px;
    }

    .sales-trend-header {
        gap: 9px;
    }

    .sales-trend-header p {
        max-width: 155px;
    }

    .sales-trend-summary {
        min-width: 92px;

        padding: 6px 8px;
    }

    .sales-trend-summary span {
        font-size: 7px;
    }

    .sales-trend-summary strong {
        font-size: 12px;
    }

    .sales-chart-wrapper {
        height: 175px;
    }
}

/* =========================================================
   VERY SMALL DEVICES
========================================================= */

@media (max-width: 380px) {

    .content-wrapper {
        padding: 10px;
    }

    .dashboard-header {
        padding: 19px 16px;
    }

    .dashboard-header h1 {
        font-size: 19px;
    }

    .dashboard-header p {
        font-size: 10px;
    }

    .attendance-card,
    .sales-card,
    .order-card {
        padding: 15px;
    }

    .attendance-icon,
    .sales-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
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

                    <span class="attendance-subtext orange  <?php echo e($checkOutTime ? 'orange' : ''); ?>">
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

                <div>

                    <div class="section-title-wrap">

                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            <?php echo e($isSuperAdmin ? 'Customer Overview' : 'Sales Overview'); ?>

                        </h2>

                    </div>

                    <p class="section-subtitle">
                        <?php echo e($isSuperAdmin
                            ? 'View customer information.'
                            : 'Click a card to view related information.'); ?>

                    </p>

                </div>

            </div>


            <div class="sales-grid <?php echo e($isSuperAdmin ? 'super-admin-sales-grid' : ''); ?>">


                

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


                <?php if(!$isSuperAdmin): ?>

                

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

                <?php endif; ?>

            </div>

        </div>

        
        <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.view')): ?> -->
        <!-- 
        <div class="sales-trend-section">

            <div class="sales-trend-header">

                <div>
                    <h3>Sales Trend</h3>

                    <p>Daily sales performance for the last 7 days</p>
                </div>

                <div class="sales-trend-summary">

                    <span>Total</span>

                    <strong>
                        ₹<?php echo e(number_format($totalSalesValue, 2)); ?>

                    </strong>

                </div>

            </div>

            <div class="sales-chart-wrapper">
                <canvas id="salesTrendChart"></canvas>
            </div>

        </div> -->

        <!-- <?php endif; ?> -->


        

        <div class="dashboard-section">

            <div class="section-header">

                <div>

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

                <div>

                    <div class="section-title-wrap">

                        <span class="section-indicator"></span>

                        <h2 class="section-title">
                            Payment Overview
                        </h2>

                    </div>

                    <p class="section-subtitle">
                        <?php echo e(($isSuperAdmin || $isGipraAdmin)
                    ? 'Click payment status to view corresponding orders.'
                    : 'Payment information overview.'); ?>

                    </p>

                </div>

            </div>


            <div class="payment-grid">

                <?php $__empty_1 = true; $__currentLoopData = $paymentOverview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <div class="dashboard-card payment-card
                <?php echo e(!($isSuperAdmin || $isGipraAdmin) ? 'payment-card-disabled' : ''); ?>">


                    

                    <?php if($isSuperAdmin || $isGipraAdmin): ?>

                    <a href="<?php echo e(route('admin.payment-management.index', [
                        'payment_mode' => $mode
                    ])); ?>" class="card-link">

                        <?php endif; ?>


                        <div class="payment-card-header
                            <?php echo e(($isSuperAdmin || $isGipraAdmin) ? 'clickable-card' : ''); ?>">

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


                        <?php if($isSuperAdmin || $isGipraAdmin): ?>

                    </a>

                    <?php endif; ?>


                    

                    <div class="payment-status-row">


                        

                        <?php if($isSuperAdmin || $isGipraAdmin): ?>

                        <a href="<?php echo e(route('admin.payment-management.index', [
                            'payment_mode' => $mode,
                            'payment_status' => 'pending'
                        ])); ?>" class="payment-status-link">

                            <?php else: ?>

                            <div class="payment-status-disabled">

                                <?php endif; ?>


                                <div class="payment-status pending-status">

                                    <span class="payment-status-label">
                                        Pending
                                    </span>

                                    <strong>
                                        <?php echo e(number_format($payment['pending'])); ?>

                                    </strong>

                                </div>


                                <?php if($isSuperAdmin || $isGipraAdmin): ?>

                        </a>

                        <?php else: ?>

                    </div>

                    <?php endif; ?>


                    

                    <?php if($isSuperAdmin || $isGipraAdmin): ?>

                    <a href="<?php echo e(route('admin.payment-management.index', [
                            'payment_mode' => $mode,
                            'payment_status' => 'paid'
                        ])); ?>" class="payment-status-link">

                        <?php else: ?>

                        <div class="payment-status-disabled">

                            <?php endif; ?>


                            <div class="payment-status paid-status">

                                <span class="payment-status-label">
                                    Paid
                                </span>

                                <strong>
                                    <?php echo e(number_format($payment['paid'])); ?>

                                </strong>

                            </div>


                            <?php if($isSuperAdmin || $isGipraAdmin): ?>

                    </a>

                    <?php else: ?>

                </div>

                <?php endif; ?>

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
<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const labels = <?php echo json_encode($salesTrendLabels, 15, 512) ?>;
    const values = <?php echo json_encode($salesTrendValues, 15, 512) ?>;

    const canvas = document.getElementById('salesTrendChart');

    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext('2d');

    const numericValues = values.map(function(value) {
        return Number(value) || 0;
    });


    /* =========================================================
       EMPTY / NO DATA
    ========================================================= */

    const hasSales = numericValues.some(function(value) {
        return value > 0;
    });


    /* =========================================================
       GRADIENT
    ========================================================= */

    const gradient = ctx.createLinearGradient(0, 0, 0, 230);

    gradient.addColorStop(
        0,
        'rgba(5, 150, 105, 0.24)'
    );

    gradient.addColorStop(
        0.55,
        'rgba(5, 150, 105, 0.08)'
    );

    gradient.addColorStop(
        1,
        'rgba(5, 150, 105, 0)'
    );


    /* =========================================================
       BEST DAY
    ========================================================= */

    const maxValue = numericValues.length ?
        Math.max(...numericValues) :
        0;

    const maxIndex = numericValues.length ?
        numericValues.indexOf(maxValue) :
        -1;


    /* =========================================================
       CROSSHAIR PLUGIN
    ========================================================= */

    const salesCrosshair = {

        id: 'salesCrosshair',

        afterDraw: function(chart) {

            if (!chart.tooltip || !chart.tooltip.getActiveElements().length) {
                return;
            }

            const activePoint =
                chart.tooltip.getActiveElements()[0];

            if (!activePoint) {
                return;
            }

            const x = activePoint.element.x;

            const chartArea = chart.chartArea;

            const context = chart.ctx;

            context.save();

            context.beginPath();

            context.moveTo(x, chartArea.top);

            context.lineTo(x, chartArea.bottom);

            context.lineWidth = 1;

            context.setLineDash([4, 4]);

            context.strokeStyle =
                'rgba(5, 150, 105, 0.22)';

            context.stroke();

            context.restore();
        }
    };


    /* =========================================================
       BEST DAY LABEL + PEAK GLOW
    ========================================================= */

    const salesPeakPlugin = {

        id: 'salesPeakPlugin',

        afterDatasetsDraw: function(chart) {

            if (
                !hasSales ||
                maxIndex < 0 ||
                maxValue <= 0
            ) {
                return;
            }

            const meta = chart.getDatasetMeta(0);

            const point = meta.data[maxIndex];

            if (!point) {
                return;
            }

            const context = chart.ctx;

            context.save();


            /* -------------------------------------------------
               Peak glow
            ------------------------------------------------- */

            context.beginPath();

            context.arc(
                point.x,
                point.y,
                9,
                0,
                Math.PI * 2
            );

            context.fillStyle =
                'rgba(5, 150, 105, 0.10)';

            context.fill();


            /* -------------------------------------------------
               Best Day badge
            ------------------------------------------------- */

            const text = 'Best Day';

            context.font = '700 9px Arial';

            const textWidth =
                context.measureText(text).width;

            const paddingX = 8;

            const boxWidth =
                textWidth + (paddingX * 2);

            const boxHeight = 19;

            let x =
                point.x - (boxWidth / 2);

            let y =
                point.y - 32;


            /* Keep badge inside chart */

            if (x < 5) {
                x = 5;
            }

            if (x + boxWidth > chart.width - 5) {
                x = chart.width - boxWidth - 5;
            }

            if (y < 5) {
                y = point.y + 14;
            }


            /* Badge */

            context.beginPath();

            if (typeof context.roundRect === 'function') {

                context.roundRect(
                    x,
                    y,
                    boxWidth,
                    boxHeight,
                    6
                );

            } else {

                context.rect(
                    x,
                    y,
                    boxWidth,
                    boxHeight
                );
            }

            context.fillStyle = '#087a4d';

            context.fill();


            /* Badge text */

            context.fillStyle = '#ffffff';

            context.textAlign = 'center';

            context.textBaseline = 'middle';

            context.fillText(
                text,
                x + (boxWidth / 2),
                y + (boxHeight / 2)
            );

            context.restore();
        }
    };


    /* =========================================================
       CREATE CHART
    ========================================================= */

    new Chart(ctx, {

        type: 'line',

        data: {

            labels: labels,

            datasets: [{

                label: 'Sales',

                data: numericValues,

                borderColor: '#059669',

                backgroundColor: gradient,

                borderWidth: 3,

                fill: true,

                tension: 0.38,

                cubicInterpolationMode: 'monotone',

                pointRadius: function(context) {

                    return context.dataIndex === maxIndex ?
                        5 :
                        3;
                },

                pointHoverRadius: 7,

                pointBackgroundColor: '#ffffff',

                pointBorderColor: '#059669',

                pointBorderWidth: 2,

                pointHoverBackgroundColor: '#059669',

                pointHoverBorderColor: '#ffffff',

                pointHoverBorderWidth: 3
            }]
        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            /* -------------------------------------------------
               Interaction
            ------------------------------------------------- */

            interaction: {

                intersect: false,

                mode: 'index'
            },


            /* -------------------------------------------------
               Animation
            ------------------------------------------------- */

            animation: {

                duration: 1000,

                easing: 'easeOutQuart'
            },


            /* -------------------------------------------------
               Plugins
            ------------------------------------------------- */

            plugins: {

                legend: {

                    display: false
                },


                tooltip: {

                    displayColors: false,

                    backgroundColor: '#173c32',

                    titleColor: '#ffffff',

                    bodyColor: '#dff7ec',

                    titleFont: {

                        size: 11,

                        weight: '700'
                    },

                    bodyFont: {

                        size: 12,

                        weight: '700'
                    },

                    padding: 11,

                    cornerRadius: 10,

                    caretSize: 6,

                    callbacks: {

                        title: function(items) {

                            if (!items.length) {
                                return '';
                            }

                            return items[0].label;
                        },


                        label: function(context) {

                            return '₹' +
                                Number(context.raw)
                                .toLocaleString(
                                    'en-IN', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }
                                );
                        }
                    }
                }
            },


            /* -------------------------------------------------
               X AXIS
            ------------------------------------------------- */

            scales: {

                x: {

                    border: {

                        display: false
                    },

                    grid: {

                        display: false
                    },

                    ticks: {

                        color: '#81908b',

                        font: {

                            size: 9,

                            weight: '600'
                        },

                        padding: 7,

                        maxRotation: 0,

                        minRotation: 0
                    }
                },


                /* -------------------------------------------------
                   Y AXIS
                ------------------------------------------------- */

                y: {

                    beginAtZero: true,

                    border: {

                        display: false
                    },

                    grid: {

                        color: 'rgba(15, 81, 50, 0.07)',

                        drawTicks: false,

                        lineWidth: 1
                    },

                    ticks: {

                        color: '#81908b',

                        padding: 8,

                        maxTicksLimit: 5,

                        font: {

                            size: 9,

                            weight: '500'
                        },

                        callback: function(value) {

                            const number =
                                Number(value);


                            if (number >= 100000) {

                                return '₹' +
                                    (number / 100000)
                                    .toFixed(1) +
                                    'L';
                            }


                            if (number >= 1000) {

                                return '₹' +
                                    (number / 1000)
                                    .toFixed(1) +
                                    'K';
                            }


                            return '₹' +
                                number.toLocaleString(
                                    'en-IN'
                                );
                        }
                    }
                }
            }
        },


        /* =====================================================
           CUSTOM PLUGINS
        ===================================================== */

        plugins: [

            salesCrosshair,

            salesPeakPlugin
        ]
    });


    /* =========================================================
       NO DATA MESSAGE
    ========================================================= */

    if (!hasSales) {

        const wrapper =
            document.querySelector(
                '.sales-chart-wrapper'
            );

        if (wrapper) {

            const message =
                document.createElement('div');

            message.style.position = 'absolute';
            message.style.left = '50%';
            message.style.top = '50%';
            message.style.transform =
                'translate(-50%, -50%)';
            message.style.padding =
                '9px 14px';
            message.style.border =
                '1px solid #dfe9e4';
            message.style.borderRadius =
                '10px';
            message.style.background =
                '#f8fbf9';
            message.style.color =
                '#81908b';
            message.style.fontSize =
                '10px';
            message.style.fontWeight =
                '600';
            message.style.pointerEvents =
                'none';
            message.textContent =
                'No sales data available';

            wrapper.appendChild(message);
        }
    }

});
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\spc\resources\views/dashboard.blade.php ENDPATH**/ ?>