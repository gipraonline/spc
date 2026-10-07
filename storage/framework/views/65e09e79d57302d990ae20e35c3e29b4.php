<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
/* =====================================================================
       SALES ORDER FORM — SPC "Evergreen" edition
       Palette: olive #5E8D3D · forest #1F5C2E · leaf #7CA243
                accents #A8CB6A/#C2DC96 · mint wash #CBFFCD (45deg)
       ===================================================================== */
.so-wrap {
    --so-olive: #5E8D3D;
    --so-forest: #1F5C2E;
    --so-leaf: #7CA243;
    --so-acc: #A8CB6A;
    --so-acc2: #C2DC96;
    --so-ink: #123A28;
    --so-mut: #5F7A6C;
    --so-line: rgba(18, 58, 40, .1);
    --so-grad: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    --so-wash: linear-gradient(45deg, #CBFFCD, transparent 60%);
    font-family: var(--font-body, 'Outfit', sans-serif);
}

.so-wrap h1,
.so-wrap h2,
.so-wrap h5,
.so-wrap h6,
.so-wrap .card-title,
.so-wrap .section-title,
.so-wrap .form-section-header,
.so-wrap .modal-title {
    font-family: var(--font-head, 'Kanit', sans-serif);
}

/* ---------- Page hero ---------- */
.so-hero {
    position: relative;
    overflow: hidden;
    border-radius: 22px;
    background:
        radial-gradient(340px 240px at 88% -12%, rgba(255, 255, 255, .16), transparent 60%),
        radial-gradient(420px 320px at -14% 116%, rgba(203, 255, 205, .34), transparent 55%),
        var(--so-grad);
    color: #fff;
    padding: 24px 28px;
    margin-bottom: 18px;
    box-shadow: 0 18px 40px -18px rgba(31, 92, 46, .55);
}

.so-hero::before {
    content: "";
    position: absolute;
    right: -70px;
    top: -70px;
    width: 230px;
    height: 230px;
    border: 2px dashed rgba(255, 255, 255, .22);
    border-radius: 50%;
}

.so-hero::after {
    content: "";
    position: absolute;
    right: -34px;
    top: -34px;
    width: 150px;
    height: 150px;
    border: 2px solid rgba(255, 255, 255, .16);
    border-radius: 50%;
}

.so-hero .fl-wave {
    position: absolute;
    right: 26px;
    bottom: 14px;
    font-size: 60px;
    color: rgba(203, 255, 205, .28);
    transform: rotate(-8deg);
    pointer-events: none;
}

.so-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-body, 'Outfit', sans-serif);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: #D8F5C8;
    background: rgba(255, 255, 255, .12);
    border: 1px solid rgba(255, 255, 255, .22);
    border-radius: 999px;
    padding: 5px 12px;
}

.so-eyebrow::before {
    content: "";
    width: 16px;
    height: 2px;
    border-radius: 2px;
    background: #D8F5C8;
}

.so-hero-title {
    font-size: 24px;
    font-weight: 600;
    color: #fff;
    margin: 10px 0 3px;
    line-height: 1.15;
}

.so-hero-sub {
    color: #D9EEDC;
    font-size: 13px;
    margin: 0;
}

.so-steps {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 14px;
    position: relative;
    z-index: 1;
}

.so-steps .st {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, .12);
    border: 1px solid rgba(255, 255, 255, .22);
    color: #EAF7E2;
    border-radius: 999px;
    padding: 6px 14px;
    font-size: 11.5px;
    font-weight: 600;
}

.so-steps .st b {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    color: var(--so-forest);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-family: var(--font-head, 'Kanit', sans-serif);
}

/* ---------- Alerts ---------- */
.so-alert {
    display: flex;
    gap: 11px;
    align-items: flex-start;
    border-radius: 14px;
    padding: 13px 16px;
    font-size: 13.5px;
    margin-bottom: 16px;
    border: 1px solid transparent;
}

.so-alert i {
    font-size: 19px;
    margin-top: 1px;
}

.so-alert.ok {
    background: #F1FBEE;
    border-color: #D6EDCB;
    color: #2C5B34;
}

.so-alert.err {
    background: #FDF1F0;
    border-color: #F3D3CF;
    color: #8C3B32;
}

.so-alert ul {
    margin: 6px 0 0;
    padding-left: 18px;
}

/* ---------- Cards & sections ---------- */
.so-wrap .card {
    border-radius: 18px;
    border: 1px solid var(--so-line);
    box-shadow: 0 6px 20px -12px rgba(18, 58, 40, .18);
    background: #fff;
}

.so-wrap .card-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--so-ink);
}

.so-section {
    border: 1px solid var(--so-line) !important;
    border-radius: 18px !important;
    padding: 22px !important;
    margin-bottom: 20px !important;
    background: #fff !important;
    box-shadow: 0 6px 20px -14px rgba(18, 58, 40, .16);
    position: relative;
    overflow: hidden;
}

.so-section::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    height: 4px;
    background: var(--so-grad);
    opacity: .85;
}

.so-section>.section-title,
.so-section>.form-section-header {
    display: flex;
    align-items: center;
    gap: 11px;
    font-size: 15.5px;
    font-weight: 600;
    color: var(--so-ink);
    border-bottom: 1px solid var(--so-line);
    padding-bottom: 12px;
    margin-bottom: 18px;
}

.so-section>.section-title .t-ic,
.so-section>.form-section-header .t-ic {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--so-wash), #EAF6E6;
    color: var(--so-olive);
    font-size: 17px;
    border: 1px solid #E2F1D9;
}

.so-section .form-section-header:not(.section-title) {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .04em;
    color: var(--so-olive);
    text-transform: uppercase;
    margin: 6px 0 14px;
}

.so-section .form-section-header:not(.section-title)::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, rgba(94, 141, 61, .3), transparent);
}

/* ---------- Labels & inputs ---------- */
.so-wrap .form-label {
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--so-mut);
    margin-bottom: 7px;
}

.so-wrap .form-control,
.so-wrap .form-select {
    border: 1.5px solid #E3EDE3;
    border-radius: 12px;
    min-height: 44px;
    padding: 9px 14px;
    font-size: 14px;
    color: #2F4A3C;
    font-family: var(--font-body, 'Outfit', sans-serif);
    transition: border-color .15s, box-shadow .15s;
}

.so-wrap .form-control:focus,
.so-wrap .form-select:focus {
    border-color: var(--so-leaf);
    box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .14);
    outline: none;
}

.so-wrap .form-control::placeholder {
    color: #A3B8AC;
    opacity: 1;
}

.so-wrap .order-number,
.so-wrap .advisor-highlight {
    background: var(--so-wash), #F4FBF3 !important;
    color: var(--so-forest) !important;
    font-weight: 700;
    border-color: #D6EDCB !important;
    font-family: var(--font-head, 'Kanit', sans-serif);
    letter-spacing: .03em;
}

.so-wrap input[readonly],
.so-wrap select:disabled {
    background-color: #F7FAF6;
    cursor: not-allowed;
}

/* ---------- Payment / order-type selectable chips ---------- */
.payment-option,
.order-status-option {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    border: 1.5px solid #E3EDE3;
    border-radius: 14px;
    padding: 11px 18px;
    margin: 0 10px 10px 0;
    cursor: pointer;
    background: #fff;
    transition: all .16s;
    font-size: 13.5px;
    font-weight: 600;
    color: #3D5247;
}

.payment-option:hover,
.order-status-option:hover {
    background: #F6FBF3;
    border-color: var(--so-leaf);
    transform: translateY(-1px);
}

.payment-option:has(input:checked) {
    background: var(--so-wash), #F1F9EC;
    border-color: var(--so-olive);
    box-shadow: 0 8px 18px -10px rgba(94, 141, 61, .55);
}

.payment-option input[type="radio"],
.order-status-option input[type="radio"] {
    margin: 0;
    accent-color: var(--so-olive);
    width: 16px;
    height: 16px;
}

.payment-option input[type="radio"]:checked+label,
.order-status-option input[type="radio"]:checked+label {
    color: var(--so-forest);
    font-weight: 700;
}

.payment-option label {
    cursor: pointer;
}

.payment-option i {
    color: var(--so-olive);
    font-size: 16px;
}

/* ---------- Buttons ---------- */
.so-wrap .buttonSpc,
.so-wrap #addRow,
.so-wrap #btn_create {
    background: var(--so-grad) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 13px !important;
    padding: 11px 24px !important;
    font-weight: 600 !important;
    font-size: 13.5px !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 10px 22px -10px rgba(31, 92, 46, .6) !important;
    transition: all .16s !important;
}

.so-wrap .buttonSpc:hover,
.so-wrap #addRow:hover,
.so-wrap #btn_create:hover {
    transform: translateY(-1.5px);
    box-shadow: 0 14px 26px -10px rgba(31, 92, 46, .7) !important;
    filter: brightness(1.07);
}

.so-wrap .btn-outline-secondary {
    border: 1.5px solid #DCEDD2 !important;
    color: var(--so-olive) !important;
    border-radius: 13px !important;
    padding: 10px 22px !important;
    font-weight: 600 !important;
    background: #fff;
}

.so-wrap .btn-outline-secondary:hover {
    background: #F4FAF0 !important;
    border-color: var(--so-leaf) !important;
    color: var(--so-forest) !important;
}

/* ---------- Product table ---------- */
.so-wrap #productTable {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--so-line);
}

.so-wrap #productTable thead th {
    background: var(--so-grad) !important;
    color: #fff !important;
    font-size: 10.5px;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-weight: 700;
    padding: 13px 14px;
    border: none !important;
    white-space: nowrap;
}

.so-wrap #productTable thead th:first-child {
    border-top-left-radius: 0;
}

.so-wrap #productTable tbody td {
    padding: 10px 10px;
    vertical-align: middle;
    border-bottom: 1px solid #EEF4EA;
    background: #fff;
}

.so-wrap #productTable tbody tr:hover td {
    background: #F8FCF5;
}

.so-wrap #productTable tbody td input,
.so-wrap #productTable tbody td select {
    border-radius: 10px;
    font-size: 13px;
    min-height: 38px;
}

.so-wrap #productTable tbody td input[readonly] {
    background: #F4F8F2;
    border-color: #E7F0E2;
    color: #5F7A6C;
}

.so-wrap .removeRow {
    background: #FDEEEC !important;
    color: #C4574A !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 7px 11px !important;
    transition: all .15s;
}

.so-wrap .removeRow:hover {
    background: #C4574A !important;
    color: #fff !important;
    transform: rotate(6deg) scale(1.05);
}

.so-wrap .btn-danger.btn-sm {
    background: #FDEEEC !important;
    color: #C4574A !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 600;
    transition: all .15s;
}

.so-wrap .btn-danger.btn-sm:hover {
    background: #C4574A !important;
    color: #fff !important;
}

/* ---------- Summary (bill card) ---------- */
.so-bill {
    border: 1.5px solid #DCEBD5;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 14px 30px -18px rgba(31, 92, 46, .4);
}

.so-bill .b-head {
    background: var(--so-wash), #F3FAF0;
    border-bottom: 1px dashed #CFE3C4;
    padding: 13px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-head, 'Kanit', sans-serif);
    font-weight: 600;
    color: var(--so-forest);
    font-size: 14.5px;
}

.so-bill .b-head i {
    color: var(--so-olive);
    font-size: 17px;
}

.so-bill .b-body {
    padding: 16px 18px;
}

.so-bill .summary-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 11px;
}

.so-bill .summary-label {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--so-mut);
    flex: 1;
}

.so-bill .summary-input {
    width: 150px;
    text-align: right;
    font-weight: 700;
    background: #FBFDF9 !important;
    border-color: #E7F0E2 !important;
    color: var(--so-forest) !important;
    font-family: var(--font-head, 'Kanit', sans-serif);
}

.so-bill .summary-line.highlight-green {
    background: var(--so-wash), #F1F9EC;
    border: 1px solid #D6EDCB;
    border-radius: 13px;
    padding: 11px 12px;
    margin-top: 10px;
    margin-bottom: 0;
}

.so-bill .summary-line.highlight-green .summary-label {
    color: var(--so-forest);
    font-size: 13px;
}

.so-bill .summary-line.highlight-green .summary-input {
    color: var(--so-forest) !important;
    font-size: 17px;
    font-weight: 700;
    background: #fff !important;
    border-color: #D6EDCB !important;
}

/* ---------- Customer toggle ---------- */
.customer-toggle {
    display: flex;
    width: 480px;
    max-width: 100%;
    padding: 7px;
    background: #F1F7EE;
    border: 1px solid #DCEBD5;
    border-radius: 18px;
    margin: 0 auto 20px;
}

.customer-toggle .toggle-btn {
    flex: 1;
    margin: 0;
    padding: 12px 22px;
    text-align: center;
    cursor: pointer;
    border-radius: 12px;
    color: var(--so-mut);
    font-size: 14px;
    font-weight: 600;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
}

.customer-toggle .btn-check:checked+.toggle-btn {
    background: var(--so-grad);
    color: #fff;
    box-shadow: 0 8px 18px -8px rgba(31, 92, 46, .6);
}

.customer-toggle .toggle-btn i {
    font-size: 16px;
}

/* ---------- Lookup card ---------- */
.so-lookup {
    border: 1.5px dashed #CFE3C4;
    border-radius: 18px;
    background: var(--so-wash), #FBFDF8;
    margin-bottom: 20px;
    overflow: hidden;
}

.so-lookup .card-header {
    background: transparent;
    border-bottom: 1px dashed #CFE3C4;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-head, 'Kanit', sans-serif);
    font-weight: 600;
    color: var(--so-forest);
    font-size: 14px;
}

.so-lookup .card-header i {
    color: var(--so-olive);
    font-size: 17px;
}

.so-lookup .card-body {
    padding: 18px 20px;
}

/* ---------- Modals ---------- */
.so-wrap .modal-content {
    border: 0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 24px 60px -20px rgba(12, 40, 24, .45);
}

.so-wrap .modal-header {
    padding: 17px 22px;
    border: none;
    color: #fff;
    background: radial-gradient(300px 160px at 90% -20%, rgba(255, 255, 255, .18), transparent 60%), var(--so-grad);
}

.so-wrap .modal-title {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
}

.so-wrap .modal-body {
    padding: 22px;
}

.so-wrap .modal-footer {
    padding: 14px 22px;
    border-top: 1px solid var(--so-line);
    background: #FBFDF9;
    gap: 10px;
}

/* ---------- Table & responsive fixes ---------- */
.tablescrolll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 16px;
}

#productTable tbody td input,
#productTable tbody td select {
    min-width: 100px;
}

#productTable tbody td select.category-select {
    min-width: 155px;
}

#productTable tbody td select.subcategory-select {
    min-width: 185px;
}

#productTable tbody td select.product-select {
    min-width: 220px;
}

#productTable tbody td select.attribute-select {
    min-width: 150px;
}

/* ---------- Product rows: compact grid cards (desktop / tablet) – no sideways scrolling ---------- */
@media screen and (min-width:768px) {
    .so-wrap .tablescrolll {
        overflow: visible;
    }

    .so-wrap #productTable,
    .so-wrap #productTable tbody {
        display: block;
        width: 100%;
        border: 0;
    }

    .so-wrap #productTable thead {
        display: none;
    }

    .so-wrap #productTable tbody tr {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 12px 14px;
        margin: 0 0 16px;
        padding: 16px 18px;
        border: 1.5px solid var(--so-line);
        border-radius: 16px;
        background: #FBFDF9;
        box-shadow: 0 6px 16px -12px rgba(18, 58, 40, .25);
    }

    .so-wrap #productTable tbody td {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 0;
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
        text-align: left;
    }

    .so-wrap #productTable tbody td::before {
        content: attr(data-label);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--so-mut);
    }

    .so-wrap #productTable tbody td input,
    .so-wrap #productTable tbody td select,
    .so-wrap #productTable tbody td select.category-select,
    .so-wrap #productTable tbody td select.subcategory-select,
    .so-wrap #productTable tbody td select.product-select,
    .so-wrap #productTable tbody td select.attribute-select {
        width: 100%;
        min-width: 0;
    }

    /* ---- Aligned 4-column grid: every row uses the same 4 equal columns ---- */
    .so-wrap #productTable tbody {
        counter-reset: prow;
    }

    .so-wrap #productTable tbody tr {
        counter-increment: prow;
        row-gap: 14px;
    }

    /* Card header: "Product 1" + remove button */
    .so-wrap #productTable tbody tr::before {
        content: "Product " counter(prow);
        grid-column: 1 / span 9;
        grid-row: 1;
        align-self: center;
        font-size: 13px;
        font-weight: 700;
        color: #1F5C2E;
        padding-bottom: 10px;
        border-bottom: 1px dashed #DCEBD5;
        margin-bottom: 2px;
    }

    .so-wrap #productTable tbody td:nth-child(13) {
        grid-column: 10 / span 3;
        grid-row: 1;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;
        padding-bottom: 10px !important;
        border-bottom: 1px dashed #DCEBD5 !important;
        margin-bottom: 2px;
    }

    .so-wrap #productTable tbody td:nth-child(13)::before {
        content: "";
        display: none;
    }

    /* Row A – what is being sold */
    .so-wrap #productTable tbody td:nth-child(1) { grid-column: 1 / span 3; grid-row: 2; }
    .so-wrap #productTable tbody td:nth-child(2) { grid-column: 4 / span 3; grid-row: 2; }
    .so-wrap #productTable tbody td:nth-child(3) { grid-column: 7 / span 3; grid-row: 2; }
    .so-wrap #productTable tbody td:nth-child(4) { grid-column: 10 / span 3; grid-row: 2; }

    /* Row B – HSN, price, quantity, discount */
    .so-wrap #productTable tbody td:nth-child(5) { grid-column: 1 / span 3; grid-row: 3; }
    .so-wrap #productTable tbody td:nth-child(6) { grid-column: 4 / span 3; grid-row: 3; }
    .so-wrap #productTable tbody td:nth-child(7) { grid-column: 7 / span 3; grid-row: 3; }
    .so-wrap #productTable tbody td:nth-child(8) { grid-column: 10 / span 3; grid-row: 3; }

    /* Row C – calculated amounts */
    .so-wrap #productTable tbody td:nth-child(9)  { grid-column: 1 / span 3; grid-row: 4; }
    .so-wrap #productTable tbody td:nth-child(10) { grid-column: 4 / span 3; grid-row: 4; }
    .so-wrap #productTable tbody td:nth-child(11) { grid-column: 7 / span 3; grid-row: 4; }
    .so-wrap #productTable tbody td:nth-child(12) { grid-column: 10 / span 3; grid-row: 4; }

    /* Auto-filled / calculated fields look muted, editable ones stay white */
    .so-wrap #productTable tbody td input[readonly],
    .so-wrap #productTable tbody td select[disabled] {
        background: #F1F7EE !important;
        color: #4B6656;
        cursor: not-allowed;
    }

    .so-wrap #productTable tbody td input.qty,
    .so-wrap #productTable tbody td input.discount {
        background: #fff;
        border-color: #A9CD94;
    }

    /* Total stands out */
    .so-wrap #productTable tbody td:nth-child(12) input {
        font-weight: 700;
        font-size: 15px;
        color: #1F5C2E;
        background: #E4F5DC !important;
        border-color: #A9CD94;
    }

    .so-wrap #productTable tbody td:nth-child(12)::before {
        color: #1F5C2E;
    }
}

@media screen and (max-width:767px) {
    .so-hero {
        padding: 20px 18px;
        border-radius: 18px;
    }

    .so-hero-title {
        font-size: 20px;
    }

    .so-hero .fl-wave {
        font-size: 42px;
        bottom: 10px;
        right: 12px;
    }

    .so-section {
        padding: 16px !important;
    }

    .summary-line {
        flex-wrap: wrap;
    }

    .text-end {
        text-align: left !important;
    }

    .section-title,
    .form-section-header {
        flex-wrap: wrap;
    }

    .customer-toggle {
        width: 100%;
    }

    .customer-toggle .toggle-btn {
        padding: 11px 10px;
        font-size: 13px;
    }

    .payment-option,
    .order-status-option {
        width: 100%;
        justify-content: flex-start;
    }

    .so-steps .st {
        font-size: 10.5px;
        padding: 5px 10px;
    }

    /* Product table → stacked cards */
    .so-wrap #productTable thead {
        display: none;
    }

    .so-wrap #productTable tbody tr {
        display: block;
        margin: 0 0 14px;
        border: 1.5px solid var(--so-line);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 16px -10px rgba(18, 58, 40, .2);
    }

    .so-wrap #productTable tbody tr.existing-product-row td:first-child,
    .so-wrap #productTable tbody tr.new-product-row td:first-child {
        background: var(--so-wash), #F3FAF0;
        border-bottom: 1px dashed #DCEBD5;
    }

    .so-wrap #productTable tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #F2F7EF;
        padding: 9px 13px;
        text-align: right;
    }

    .so-wrap #productTable tbody td::before {
        content: attr(data-label);
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--so-mut);
        text-align: left;
    }

    .so-wrap #productTable tbody td input,
    .so-wrap #productTable tbody td select {
        flex: 1;
        max-width: 62%;
    }

    .so-wrap #productTable tbody td.text-center {
        justify-content: flex-end;
    }

    .so-wrap #productTable tbody td.text-center::before {
        content: "";
    }
}
</style>

<style>
/* Legacy overrides kept: modal z-index fixes (rest moved into the Evergreen block above) */
#approveModal {
    z-index: 1060 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\Crypt;
?>

<div class="so-wrap">

    
    <div class="so-hero">
        <i class="fa fa-cart-plus fl-wave"></i>
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <span class="so-eyebrow">SPC Portal · Sales</span>
                <h2 class="so-hero-title"><?php echo e(isset($sale->n_sl_no) ? 'Edit Sales Order' : 'New Sales Order'); ?> 🧾</h2>
                <p class="so-hero-sub">Fill the sections top to bottom — products, customer, payment — and submit.</p>
                <div class="so-steps">
                    <span class="st"><b>1</b> Order Info</span>
                    <span class="st"><b>2</b> Products</span>
                    <span class="st"><b>3</b> Customer</span>
                    <span class="st"><b>4</b> Payment</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card w-100 position-relative overflow-hidden mb-4 border-0"
        style="box-shadow:none;background:transparent;">
        <div class="card-body p-0">

            <?php if($errors->any()): ?>
            <div class="so-alert err">
                <i class="ti ti-alert-circle"></i>
                <div>
                    <strong>Please check the following:</strong>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="so-alert err">
                <i class="ti ti-alert-circle"></i>
                <div><?php echo e(session('error')); ?></div>
            </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
            <div class="so-alert ok">
                <i class="ti ti-circle-check"></i>
                <div><?php echo e(session('success')); ?></div>
            </div>
            <?php endif; ?>

            <?php
            $isTelecallerRoute = request()->routeIs('admin.telecallers.*');
            $isEditing = isset($sale) && $sale->n_sl_no && (!isset($viewmode) || $viewmode != 'on');
            $formAction = $isEditing
                ? route($isTelecallerRoute ? 'admin.telecallers.update' : 'admin.salesorders.update')
                : route($isTelecallerRoute ? 'admin.telecallers.store' : 'admin.salesorders.store');
            ?>
            <form method="POST" id="frm_create" action="<?php echo e($formAction); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php if($isEditing): ?>
                <?php echo method_field('PUT'); ?>
                <?php endif; ?>

                <input type="hidden" name="id" class="form-control" value="<?php echo e(isset($sale) ? $sale->n_sl_no : ''); ?>">

                <!-- Section 1: Order Information -->
                <div class="form-section so-section mb-4">
                    <div class="section-title mb-3">
                        <span class="t-ic"><i class="ti ti-file-invoice"></i></span>
                        Order Information
                    </div>

                    <!-- Row 1: Date & Booklet Serial No -->
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="d_date" class="form-control mandatory"
                                data-message="Please Select a Date"
                                value="<?php echo e(old('d_date', isset($sale) && $sale->d_date ? $sale->d_date->format('Y-m-d') : date('Y-m-d'))); ?>"
                                <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>
                            <div class="text-danger mt-1 fs-2"><?php $__errorArgs = ['d_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        </div>

                        <?php if(isset($isFarmCareAdvisor) && $isFarmCareAdvisor==true ): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Booklet Serial No *</label>
                            <div class="position-relative">
                                <input type="text" name="c_order_no" placeholder="BK-2026-0417"
                                    class="form-control order-number fw-bold text-success mandatory"
                                    data-message="Please Enter Booklet Serial No"
                                    value="<?php echo e(old('c_order_no', isset($sale->c_order_no) ? $sale->c_order_no : '')); ?>"
                                    <?php echo e(isset($viewmode) && $viewmode=='on' ? 'readonly' : ''); ?>>
                                <div class="text-danger mt-1 fs-2"></div>
                            </div>
                            <?php $__errorArgs = ['c_order_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <?php endif; ?>
                    </div>


                    <!-- Row 2: Farm Care Advisor & Booklet Proof -->
                    <?php if(
                    (!isset($isTelecaller) || $isTelecaller == false) &&
                    (!isset($isFarmCareOfficer) || $isFarmCareOfficer == false) &&
                    (!isset($isOfficeAdmin) || $isOfficeAdmin == false)
                    ): ?>
                    <div class="row g-3">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Farm Care Advisor *</label>
                            <?php if($isFarmCareAdvisor): ?>
                            <input type="hidden" name="farm_care_advisor_id" class="form-control advisor-highlight"
                                value="<?php echo e(auth()->user()->n_employee_id); ?>" readonly>
                            <input type="text" class="form-control advisor-highlight"
                                value="<?php echo e(auth()->user()->c_name); ?>" readonly>
                            <?php else: ?>
                            <select name="farm_care_advisor_id" class="form-control"
                                data-message="Please Enter Farm Care Advisor">
                                <option value="">Select Farm Care Adviser</option>
                                <?php if(isset($employees)): ?>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->n_employee_id); ?>"
                                    <?php echo e(old('farm_care_advisor_id', $sale->farm_care_advisor_id ?? '') == $employee->n_employee_id ? 'selected' : ''); ?>>
                                    <?php echo e($employee->c_employee_name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <div class="text-danger mt-1 fs-2"><?php $__errorArgs = ['farm_care_advisor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Sales Order Booklet Proof
                                <?php if(!isset($sale) || !$sale->booklet_image): ?>
                                <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>

                            <input type="file" name="booklet_image" id="booklet_image" class="form-control"
                                accept="image/*" data-message="Please Enter Booklet Proof">
                            <input type="hidden" name="remove_booklet_image" id="remove_booklet_image" value="0">
                            <div class="text-danger mt-1 fs-2"><?php $__errorArgs = ['booklet_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>

                            <div class="mt-3" id="booklet_image_preview_container">
                                <img id="booklet_image_preview"
                                    src="<?php echo e(isset($sale) && $sale->booklet_image ? route('admin.salesorders.proof', ['type' => 'booklet_images', 'filename' => $sale->booklet_image]) : ''); ?>"
                                    alt="Booklet Proof Preview" class="img-thumbnail"
                                    style="<?php echo e(isset($sale) && $sale->booklet_image ? '' : 'display:none;'); ?> width:50px; height:50px; object-fit:cover;">

                                <?php if(isset($sale) && $sale->booklet_image): ?>
                                <br>
                                <button type="button" id="remove_booklet_image_btn" class="btn btn-danger btn-sm mt-2">
                                    Remove Image
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                    <?php endif; ?>
                </div>

                <!-- Section 2: Product Details (Hierarchical Category -> Subcategory -> Product -> Attributes) -->
                <div class="form-section so-section mb-4">
                    <div class="section-title d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="t-ic"><i class="ti ti-shopping-cart"></i></span>
                            Product Details *
                        </div>

                        <?php if(!isset($viewmode) || $viewmode=='off'): ?>
                        <button type="button" class="btn buttonSpc btn-sm" id="addRow">
                            <i class="ti ti-plus"></i>
                            Add Product
                        </button>
                        <?php endif; ?>
                    </div>

                    <div class="tablescrolll">
                        <table class="table table-bordered table-responsive align-middle" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 170px;">Category *</th>
                                    <th style="min-width: 195px;">Sub Category</th>
                                    <th style="min-width: 220px;">Product *</th>
                                    <th style="min-width: 155px;">Attribute / Pack Size</th>
                                    <th style="min-width: 110px;">HSN Code</th>
                                    <th style="min-width: 110px;">Price (Excl. GST)</th>
                                    <th style="min-width: 90px;">Quantity *</th>
                                    <th style="min-width: 100px;">Discount</th>
                                    <th style="min-width: 80px;">GST %</th>
                                    <th style="min-width: 110px;">GST Amount</th>
                                    <th style="min-width: 120px;">Taxable Amount</th>
                                    <th style="min-width: 120px;">Total (MRP)</th>
                                    <th style="min-width: 60px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /*
                                 | Build the product rows from (a) the submitted input after a failed
                                 | validation, otherwise (b) the saved order lines. Names shown next to the
                                 | hidden ids are looked up so nothing is lost when the page re-renders.
                                 */
                                $productRows = [];

                                if (is_array(old('products'))) {
                                    $oldRows = collect(old('products'));
                                    $catIds = $oldRows->pluck('n_category_id')
                                        ->merge($oldRows->pluck('n_sub_category_id'))
                                        ->filter()->unique()->values();
                                    $catNames = $catIds->isNotEmpty()
                                        ? \App\Models\CategoryMaster::whereIn('n_category_id', $catIds)->pluck('c_category_name', 'n_category_id')
                                        : collect();
                                    $prodIds = $oldRows->pluck('product_id')->filter()->unique()->values();
                                    $prodNames = $prodIds->isNotEmpty()
                                        ? \App\Models\ProductMaster::whereIn('n_product_id', $prodIds)->pluck('c_product_name', 'n_product_id')
                                        : collect();

                                    foreach (old('products') as $rk => $r) {
                                        $productRows[$rk] = [
                                            'n_category_id' => $r['n_category_id'] ?? '',
                                            'category_name' => $catNames[$r['n_category_id'] ?? 0] ?? '',
                                            'n_sub_category_id' => $r['n_sub_category_id'] ?? '',
                                            'sub_category_name' => $catNames[$r['n_sub_category_id'] ?? 0] ?? '',
                                            'product_id' => $r['product_id'] ?? '',
                                            'product_name' => $prodNames[$r['product_id'] ?? 0] ?? ($r['c_product_name'] ?? ''),
                                            'c_unit' => $r['c_unit'] ?? '',
                                            'c_hsn_code' => $r['c_hsn_code'] ?? '',
                                            'product_price' => $r['product_price'] ?? '0.00',
                                            'qty' => $r['qty'] ?? 1,
                                            'discount' => $r['discount'] ?? '0.00',
                                            'n_gst_percentage' => $r['n_gst_percentage'] ?? 0,
                                            'gst_amount' => $r['gst_amount'] ?? '0.00',
                                            'discounted_price' => $r['discounted_price'] ?? '0.00',
                                            'product_total' => $r['product_total'] ?? '0.00',
                                        ];
                                    }
                                } elseif (isset($sale->orderProducts)) {
                                    foreach ($sale->orderProducts as $rk => $val) {
                                        $productRows[$rk] = [
                                            'n_category_id' => $val->n_category_id,
                                            'category_name' => $val->category?->c_category_name,
                                            'n_sub_category_id' => $val->n_sub_category_id,
                                            'sub_category_name' => $val->subCategory?->c_category_name,
                                            'product_id' => $val->product_id,
                                            'product_name' => $val->product?->c_product_name,
                                            'c_unit' => $val->c_unit ?: ($val->product?->c_unit),
                                            'c_hsn_code' => $val->c_hsn_code,
                                            'product_price' => $val->product_price ?? '0.00',
                                            'qty' => $val->qty ?? 1,
                                            'discount' => $val->discount ?? '0.00',
                                            'n_gst_percentage' => $val->n_gst_percentage ?? 0,
                                            'gst_amount' => $val->gst_amount ?? '0.00',
                                            'discounted_price' => $val->discounted_price ?? '0.00',
                                            'product_total' => $val->product_total ?? '0.00',
                                        ];
                                    }
                                }
                                ?>

                                <?php $__currentLoopData = $productRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="existing-product-row">
                                    <!-- Category -->
                                    <td>
                                        <input type="hidden" name="products[<?php echo e($key); ?>][n_category_id]"
                                            value="<?php echo e($row['n_category_id']); ?>">
                                        <input type="text" class="form-control" value="<?php echo e($row['category_name']); ?>"
                                            readonly>
                                    </td>

                                    <!-- Sub Category -->
                                    <td>
                                        <input type="hidden" name="products[<?php echo e($key); ?>][n_sub_category_id]"
                                            value="<?php echo e($row['n_sub_category_id']); ?>">
                                        <input type="text" class="form-control"
                                            value="<?php echo e($row['sub_category_name']); ?>" readonly>
                                    </td>

                                    <!-- Product -->
                                    <td>
                                        <input type="hidden" name="products[<?php echo e($key); ?>][product_id]"
                                            class="product-select" value="<?php echo e($row['product_id']); ?>">
                                        <input type="text" class="form-control" value="<?php echo e($row['product_name']); ?>"
                                            readonly>
                                    </td>

                                    <!-- Attribute / Pack Size -->
                                    <td>
                                        <input type="hidden" name="products[<?php echo e($key); ?>][c_unit]"
                                            value="<?php echo e($row['c_unit']); ?>">
                                        <input type="text" class="form-control" value="<?php echo e($row['c_unit']); ?>" readonly>
                                    </td>

                                    <!-- HSN Code -->
                                    <td>
                                        <input type="text" name="products[<?php echo e($key); ?>][c_hsn_code]"
                                            class="form-control c_hsn_code" value="<?php echo e($row['c_hsn_code']); ?>" readonly>
                                    </td>

                                    <!-- Price -->
                                    <td>
                                        <input type="text" name="products[<?php echo e($key); ?>][product_price]"
                                            class="form-control price" value="<?php echo e($row['product_price']); ?>" readonly>
                                    </td>

                                    <!-- Quantity -->
                                    <td>
                                        <input type="number" name="products[<?php echo e($key); ?>][qty]" class="form-control qty"
                                            value="<?php echo e($row['qty']); ?>" min="1">
                                    </td>

                                    <!-- Discount -->
                                    <td>
                                        <input type="number" name="products[<?php echo e($key); ?>][discount]"
                                            class="form-control discount" value="<?php echo e($row['discount']); ?>" step="0.01"
                                            min="0">
                                    </td>

                                    <!-- GST % -->
                                    <td>
                                        <input type="number" name="products[<?php echo e($key); ?>][n_gst_percentage]"
                                            class="form-control gst_percentage" value="<?php echo e($row['n_gst_percentage']); ?>"
                                            step="0.01" readonly>
                                    </td>

                                    <!-- GST Amount -->
                                    <td>
                                        <input type="text" name="products[<?php echo e($key); ?>][gst_amount]"
                                            class="form-control gst_amount" value="<?php echo e($row['gst_amount']); ?>" readonly>
                                    </td>

                                    <!-- Taxable / Discounted Price -->
                                    <td>
                                        <input type="text" name="products[<?php echo e($key); ?>][discounted_price]"
                                            class="form-control discounted_price" value="<?php echo e($row['discounted_price']); ?>"
                                            readonly>
                                    </td>

                                    <!-- Total (MRP) -->
                                    <td>
                                        <input type="text" name="products[<?php echo e($key); ?>][product_total]"
                                            class="form-control total" value="<?php echo e($row['product_total']); ?>" readonly>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm removeRow">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $__errorArgs = ['products'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <!-- Product Details Summary Box (Bill Card) -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-6 col-lg-5">
                            <div class="so-bill">
                                <div class="b-head">
                                    <i class="ti ti-receipt"></i> Order Summary
                                </div>
                                <div class="b-body">
                                    <div class="summary-line">
                                        <span class="summary-label">Total Sales Amount</span>
                                        <input type="text" name="n_total_sales_amount"
                                            class="form-control summary-input text-end" id="summaryTotalSales"
                                            value="<?php echo e(old('n_total_sales_amount', $sale->n_total_sales_amount ?? '0.00')); ?>"
                                            readonly>
                                    </div>

                                    <div class="summary-line">
                                        <span class="summary-label">Total GST</span>
                                        <input type="number" name="n_total_gst"
                                            class="form-control summary-input text-end" id="summaryGstAmount"
                                            value="<?php echo e(old('n_total_gst', $sale->n_total_gst ?? '0.00')); ?>" step="0.01"
                                            min="0" readonly>
                                    </div>

                                    <div class="summary-line">
                                        <span class="summary-label">Total Discount</span>
                                        <input type="text" name="n_product_discount_total"
                                            class="form-control summary-input text-end" id="summaryTotalDiscount"
                                            value="<?php echo e(old('n_product_discount_total', $sale->n_product_discount_total ?? '0.00')); ?>"
                                            readonly>
                                    </div>

                                    <div class="summary-line highlight-green">
                                        <span class="summary-label fw-bold">Net Sales Amount</span>
                                        <input type="text" name="n_net_sales_amount"
                                            class="form-control summary-input text-end fw-bold text-success"
                                            id="summaryNetSales"
                                            value="<?php echo e(old('n_net_sales_amount', $sale->n_net_sales_amount ?? '0.00')); ?>"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Type Selection -->
                <div class="customer-toggle mb-4">
                    <input type="radio" class="btn-check" name="c_customer_type" id="newCustomer" value="new"
                        <?php echo e(old('c_customer_type', $sale->c_customer_type ?? 'new') != 'existing' ? 'checked' : ''); ?>>
                    <label class="toggle-btn new" for="newCustomer"><i class="ti ti-user-plus"></i> New Customer</label>

                    <input type="radio" class="btn-check" name="c_customer_type" id="existingCustomer" value="existing"
                        <?php echo e(old('c_customer_type', $sale->c_customer_type ?? 'new') == 'existing' ? 'checked' : ''); ?>>
                    <label class="toggle-btn existing" for="existingCustomer"><i class="ti ti-user-search"></i> Existing
                        Customer</label>
                </div>

                <!-- Existing Customer Lookup -->
                <div class="card so-lookup mb-4 d-none" id="lookupCard">
                    <div class="card-header">
                        <i class="ti ti-user-search"></i> Find an Existing Customer
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mobile Number</label>
                                <input type="text" id="lookupMobile"
                                    value="<?php echo e(old('n_mobile', isset($sale) ? $sale->customer?->n_mobile : '')); ?>"
                                    class="form-control" placeholder="Enter 10-digit Mobile Number">
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="lookupBtn" class="btn buttonSpc w-100">
                                    <i class="ti ti-search me-1"></i> Find Customer
                                </button>
                            </div>
                            <div class="col-md-3">
                                <small id="lookupMessage" class="fw-semibold"
                                    style="color:var(--so-olive, #5E8D3D);"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Customer Information -->
                <div class="border rounded so-section p-4 mb-4">
                    <div class="form-section-header section-title mb-3">
                        <span class="t-ic"><i class="ti ti-user"></i></span> Customer Information
                    </div>

                    <input type="hidden" name="n_customer_id" id="n_customer_id" class="form-control customer-id"
                        value="<?php echo e(old('n_customer_id', isset($sale) ? $sale->customer?->n_customer_id : '')); ?>">
                    <?php $__errorArgs = ['n_customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 mb-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Customer Code</label>
                            <input type="text" name="c_customer_code" id="c_customer_code"
                                class="form-control customer-code"
                                value="<?php echo e($customerCode ?? (isset($sale) ? $sale->customer?->c_customer_code : '')); ?>"
                                readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Customer Name *</label>
                            <input type="text" name="c_customer_name" id="c_customer_name"
                                value="<?php echo e(old('c_customer_name', isset($sale) ? $sale->customer?->c_customer_name : '')); ?>"
                                class="form-control c_customer_name mandatory" placeholder="Customer Name">
                            <?php $__errorArgs = ['c_customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mobile Number *</label>
                            <input type="text" maxlength="10" name="n_mobile" id="n_mobile"
                                value="<?php echo e(old('n_mobile', isset($sale) ? $sale->customer?->n_mobile : '')); ?>"
                                class="form-control mandatory" placeholder="10 Digit Mobile Number">
                            <?php $__errorArgs = ['n_mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">WhatsApp Number *</label>
                            <input type="text" maxlength="10" name="n_whatsapp" id="n_whatsapp"
                                value="<?php echo e(old('n_whatsapp', isset($sale) ? $sale->customer?->n_whatsapp : '')); ?>"
                                class="form-control" placeholder="WhatsApp Number">
                            <?php $__errorArgs = ['n_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Email *</label>
                            <input type="email" name="c_email" id="c_email"
                                value="<?php echo e(old('c_email', isset($sale) ? $sale->customer?->c_email : '')); ?>"
                                class="form-control" placeholder="example@domain.com">
                            <?php $__errorArgs = ['c_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div> <!-- Address Details -->
                    <div class="form-section-header">
                        <i class="ti ti-map-pin"></i> Address Details
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label for="c_address" class="form-label">Address *</label>
                            <textarea id="c_address" name="c_address" rows="3" class="form-control"
                                placeholder="Enter Customer Address"><?php echo e(old('c_address', isset($sale) ? $sale->customer?->c_address : '')); ?></textarea>
                            <?php $__errorArgs = ['c_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="c_post_office" class="form-label">
                                Post Office *
                            </label>
                            <input type="text" id="c_post_office" name="c_post_office"
                                value="<?php echo e(old('c_post_office',isset($sale) ? $sale->customer?->c_post_office : '')); ?>"
                                class="form-control" placeholder="Post Office">

                            <?php $__errorArgs = ['c_post_office'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        <div class="col-md-4">
                            <label for="n_state_id" class="form-label">State *</label>
                            <select name="customer_state_id" id="n_state_id" class="form-select">
                                <option value="">Select State</option>
                                <?php if(isset($states)): ?>
                                <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($state->n_state_id); ?>" data-id="<?php echo e($state->n_state_id); ?>"
                                    <?php echo e(old('customer_state_id', isset($sale) ? $sale->customer?->n_state_id : '') == $state->n_state_id ? 'selected' : ''); ?>>
                                    <?php echo e($state->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['customer_state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="n_district_id" class="form-label">District *</label>
                            <select name="customer_district_id" id="n_district_id" class="form-select">
                                <option value="">Select District</option>
                                <?php if(isset($districts)): ?>
                                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($district->id); ?>"
                                    <?php echo e(old('customer_district_id', isset($sale) ? $sale->customer?->n_district_id : '') == $district->id ? 'selected' : ''); ?>>
                                    <?php echo e($district->district_name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['customer_district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="c_thaluk" class="form-label">
                                Thaluk *
                            </label>
                            <input type="text" id="c_thaluk" name="c_thaluk"
                                value="<?php echo e(old('c_thaluk',isset($sale) ? $sale->customer?->c_thaluk : '')); ?>"
                                class="form-control" placeholder="Thaluk">

                            <?php $__errorArgs = ['c_thaluk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        </div>

                        <div class="col-md-4">
                            <label for="c_pincode" class="form-label">Pincode *</label>
                            <input type="text" id="c_pincode" name="c_pincode" maxlength="6"
                                value="<?php echo e(old('c_pincode', isset($sale) ? $sale->customer?->c_pincode : '')); ?>"
                                class="form-control" placeholder="Pincode">
                            <?php $__errorArgs = ['c_pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-12">
                            <div class="form-label mb-2"><i class="ti ti-current-location"></i> Location (from address)</div>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="so_latitude" class="form-label">Latitude</label>
                                    <input type="text" id="so_latitude" name="latitude"
                                        value="<?php echo e(old('latitude', isset($sale) ? $sale->latitude : '')); ?>"
                                        class="form-control" maxlength="20" placeholder="Latitude" inputmode="decimal">
                                    <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-3">
                                    <label for="so_longitude" class="form-label">Longitude</label>
                                    <input type="text" id="so_longitude" name="longitude"
                                        value="<?php echo e(old('longitude', isset($sale) ? $sale->longitude : '')); ?>"
                                        class="form-control" maxlength="20" placeholder="Longitude" inputmode="decimal">
                                    <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6 d-flex flex-wrap gap-2">
                                    <button type="button" id="soGetLocationBtn" class="btn buttonSpc">
                                        <i class="ti ti-map-pin-search"></i> Get Location from Address
                                    </button>
                                    <button type="button" id="soToggleMapBtn" class="btn btn-outline-secondary">
                                        <i class="ti ti-map-pin"></i> Select on Map
                                    </button>
                                </div>
                            </div>
                            <div id="soLocationStatus" class="mt-2 small text-muted">
                                Fill in the address, state and district, then click "Get Location from Address". You can drag the pin or click the map to fine-tune it.
                            </div>
                            <div id="soLocationMap" style="display:none;height:340px;border-radius:12px;margin-top:12px;border:1px solid #dfe5e1;"></div>
                        </div>
                    </div> <!-- Customer Status -->
                    <div class="form-section-header">
                        <i class="ti ti-checkup-list"></i> Customer Status
                    </div>

                    <div class="row g-4 mb-2">
                        <div class="col-md-4">
                            <label for="c_status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select id="c_status" name="c_status" class="form-select mandatory">
                                <option value="">Select Status</option>
                                <option value="Y"
                                    <?php echo e(old('c_status', isset($sale) ? ($sale->customer?->c_status ?? 'Y') : 'Y') == 'Y' ? 'selected' : ''); ?>>
                                    Active</option>
                                <option value="N"
                                    <?php echo e(old('c_status', isset($sale) ? $sale->customer?->c_status : '') == 'N' ? 'selected' : ''); ?>>
                                    Inactive</option>
                            </select>
                            <?php $__errorArgs = ['c_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Payment Details -->
                <div class="form-box so-section mb-4">
                    <div class="form-section-header section-title mb-3">
                        <span class="t-ic"><i class="ti ti-credit-card"></i></span> Payment Details
                    </div>

                    <div class="row mb-4 align-items-center">
                        <label class="col-md-3 col-form-label fw-semibold">Mode of Payment *</label>

                        <div class="col-md-9 d-flex flex-wrap">
                            <div class="payment-option">
                                <input class="form-check-input mandatory mode_of_payment" type="radio"
                                    name="c_mode_of_payment" id="cod" value="Cash on Delivery"
                                    data-message="Please Choose a Payment Mode"
                                    <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Cash on Delivery" ? 'checked' : ''); ?>>
                                <label for="cod" class="mb-0">
                                    <i class="ti ti-truck"></i> Cash on Delivery
                                </label>
                            </div>

                            <?php if(isset($isTelecaller) && $isTelecaller==false): ?>
                            <div class="payment-option">
                                <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment"
                                    id="upi" value="UPI"
                                    <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "UPI" ? 'checked' : ''); ?>>
                                <label for="upi" class="mb-0">
                                    <i class="ti ti-brand-google-pay"></i> UPI
                                </label>
                            </div>

                            <div class="payment-option">
                                <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment"
                                    id="bkd" value="Bank Deposit"
                                    <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Bank Deposit" ? 'checked' : ''); ?>>
                                <label for="bkd" class="mb-0">
                                    <i class="ti ti-building-bank"></i> Bank Deposit
                                </label>
                            </div>
                            <?php endif; ?>

                            <div class="payment-option">
                                <input class="form-check-input mode_of_payment" type="radio" name="c_mode_of_payment"
                                    id="pf" value="Paid to Franchise"
                                    <?php echo e(old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Paid to Franchise" ? 'checked' : ''); ?>>
                                <label for="pf" class="mb-0">
                                    <i class="ti ti-cash"></i> Paid to Franchise
                                </label>
                            </div>
                            <div class="text-danger mt-1 fs-2" id="payment_mode_error"><?php $__errorArgs = ['c_mode_of_payment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        </div>
                    </div>

                    <div class="row g-4 mt-1" id="ps">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Payment Status</label>
                            <select name="payment_status" id="payment_status"
                                data-message="Please Select Payment Status" class="form-select">
                                <option value="">Select Status</option>
                                <option value="pending"
                                    <?php echo e(old('payment_status', $sale->payment_status ?? '') == "pending" ? 'selected' : ''); ?>>
                                    Pending</option>
                                <option value="paid"
                                    <?php echo e(old('payment_status', $sale->payment_status ?? '') == "paid" ? 'selected' : ''); ?>>
                                    Paid</option>
                            </select>
                            <div class="text-danger mt-1 fs-2"><?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        </div>
                    </div>

                    <!-- Payment Details Extra Fields -->
                    <div class="row g-4 mt-1" id="paymet-proofs">
                        <div class="col-md-4">
                            <label class="form-label">Amount to Pay *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-success fw-bold">₹</span>
                                <input type="text" name="n_amount_to_pay" data-message="Please Enter Amount to Pay"
                                    id="n_amount_to_pay" class="form-control fw-bold text-success"
                                    value="<?php echo e(old('n_amount_to_pay', $sale->n_amount_to_pay ?? '')); ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Transaction ID *</label>
                            <input type="text" id="c_transaction_id" name="c_transaction_id"
                                value="<?php echo e(old('c_transaction_id', $sale->c_transaction_id ?? '')); ?>"
                                data-message="Please Enter Transaction id" class="form-control"
                                placeholder="Enter Transaction / UTR / Reference No">
                            <div class="text-danger mt-1 fs-2"><?php $__errorArgs = ['c_transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Transaction Proof
                                <?php if(!isset($sale) || !$sale->payment_image): ?>
                                <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>

                            <input type="file" id="payment_image" name="payment_image"
                                data-message="Please Enter Transaction Proof" class="form-control" accept="image/*">
                            <input type="hidden" name="remove_payment_image" id="remove_payment_image" value="0">
                            <div class="text-danger mt-1 fs-2"><?php $__errorArgs = ['payment_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>

                            <div class="mt-3" id="payment_preview_container">
                                <img id="payment_image_preview"
                                    src="<?php echo e(isset($sale) && $sale->payment_image ? route('admin.salesorders.proof', ['type' => 'payment_images', 'filename' => $sale->payment_image]) : ''); ?>"
                                    alt="Transaction Proof Preview" class="img-thumbnail"
                                    style="<?php echo e(isset($sale) && $sale->payment_image ? '' : 'display:none;'); ?> width:50px; height:50px; object-fit:cover;">

                                <?php if(isset($sale) && $sale->payment_image): ?>
                                <br>
                                <button type="button" id="remove_payment_image_btn" class="btn btn-danger btn-sm mt-2">
                                    Remove Image
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Franchise / Company Details Section -->
                <div class="form-box so-section mb-4" id="franchise-details">
                    <?php if((isset($isAdmin) && $isAdmin==true) || (isset($isOfficeAdmin) && $isOfficeAdmin==true)): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Order Type <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input mandatory" type="radio" name="order_type"
                                        id="company" value="company"
                                        <?php echo e(old('order_type', $sale->order_type ?? '') == 'company' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="company">Company</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input mandatory" type="radio" name="order_type"
                                        id="franchise_type" value="franchise"
                                        <?php echo e(old('order_type', $sale->order_type ?? '') == 'franchise' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="franchise_type">Franchise</label>
                                </div>
                            </div>
                            <?php $__errorArgs = ['order_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div id="franchise-location-details">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select class="form-select mandatory" id="franchise_state" name="n_state_id"
                                    data-message="Please Select State">
                                    <option value="">Select State</option>
                                    <?php if(isset($states)): ?>
                                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($state->n_state_id); ?>"
                                        <?php echo e(old('n_state_id', $sale->n_state_id ?? '') == $state->n_state_id ? 'selected' : ''); ?>>
                                        <?php echo e($state->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['n_state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select class="form-select" id="franchise_district" name="n_district_id"
                                    data-message="Please Select District"
                                    <?php echo e(isset($viewmode) && $viewmode == 'on' ? 'disabled' : ''); ?>>
                                    <option value="">Select District</option>
                                    <?php
                                    $fState = old('n_state_id', $sale->n_state_id ?? null);
                                    $fDistrict = old('n_district_id', $sale->n_district_id ?? null);
                                    ?>
                                    <?php if($fState): ?>
                                    <?php
                                    $franchiseDistricts = \App\Models\District::where('state_id', $fState)->get();
                                    ?>
                                    <?php $__currentLoopData = $franchiseDistricts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($district->id); ?>"
                                        <?php echo e(old('n_district_id', $sale->n_district_id ?? '') == $district->id ? 'selected' : ''); ?>>
                                        <?php echo e($district->district_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['n_district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Panchayath</label>
                                <select class="form-select" id="franchise_panchayath" name="n_panchayath_id">
                                    <option value="">Select Panchayath</option>
                                    <?php if($fDistrict): ?>
                                    <?php
                                    $franchisePanchayaths = \App\Models\Panchayath::where('district_id', $fDistrict)->get();
                                    ?>
                                    <?php $__currentLoopData = $franchisePanchayaths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $panchayath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($panchayath->id); ?>"
                                        <?php echo e(old('n_panchayath_id', $sale->n_panchayath_id ?? '') == $panchayath->id ? 'selected' : ''); ?>>
                                        <?php echo e($panchayath->panchayath_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nearest Franchise <span class="text-danger">*</span></label>
                                <select class="form-select mandatory" id="franchise" name="nearest_franchise_id"
                                    data-message="Please Select Nearest Franchise">
                                    <option value="">Select Franchise</option>
                                    <?php if(isset($franchises)): ?>
                                    <?php $__currentLoopData = $franchises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $franchise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($franchise->n_store_id); ?>"
                                        <?php echo e(old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : ''); ?>>
                                        <?php echo e($franchise->c_store_name); ?> (<?php echo e($franchise->c_store_code); ?>)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['nearest_franchise_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div id="soFranchiseHint" class="mt-1 small text-muted"></div>
                                <div id="soFranchiseRank" class="mt-2" style="display:none;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 d-flex gap-2 flex-wrap">
                    <?php if(isset($viewmode) && $viewmode=="on"): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-orders.approval')): ?>
                    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc"
                        data-bs-toggle="modal" data-bs-target="#approveModal" data-bs-dismiss="modal"
                        data-id="<?php echo e(Crypt::encryptString(isset($sale) && $sale->n_sl_no ? $sale->n_sl_no : '')); ?>">
                        Approve
                    </button>
                    <?php endif; ?>

                    <?php if(isset($sale) && $sale->n_sl_no): ?>
                    <a href="<?php echo e(route('admin.invoice-orders.preview', $sale->n_sl_no)); ?>" class="btn mt-1 buttonSpc">
                        Order Summary Preview
                    </a>
                    <?php if(strtolower($sale->approval?->status ?? '') === 'approved'): ?>
                    <a href="<?php echo e(route('admin.invoice.download', $sale->n_sl_no)); ?>">
                        <button type="button" class="btn buttonSpc" style="height:61px;margin-top: 4px;">Generate
                            Invoice</button>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php else: ?>
                    <button type="button" class="btn buttonSpc" style="width:150px;position:relative;"
                        id="btn_create"><?php echo e(isset($sale->n_sl_no) ? 'Update' : 'Create'); ?></button>
                    <a href="<?php echo e(route('admin.salesorders.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Approval Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="approveForm" action="<?php echo e(route('admin.salesorders.approval.save')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="modal-header" style="background: linear-gradient(135deg, #5E8D3D, #1F5C2E);">
                        <h5 class="modal-title text-white" id="approveModalLabel">Approval</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="sales_id" id="sales_id"
                            value="<?php echo e(Crypt::encryptString(isset($sale) && $sale->n_sl_no ? $sale->n_sl_no : '')); ?>">

                        <div class="mb-3">
                            <label class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="remarks" id="approval_remarks" rows="3"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Approval Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="approval_status" required>
                                <option value="">Select Status</option>
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn buttonSpc" id="approvalSubmit">Submit</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
    $(document).ready(function() {
        console.log('Sales Order JS loaded with Category & Attribute flow');

        let rowIndex = 0;
        $('#productTable tbody tr').each(function() {
            const m = ($(this).find('[name^="products["]').first().attr('name') || '').match(/^products\[(\d+)\]/);
            if (m) rowIndex = Math.max(rowIndex, parseInt(m[1], 10) + 1);
        });

        /*
        |--------------------------------------------------------------------------
        | Inject mobile card labels from thead (harmless on desktop)
        |--------------------------------------------------------------------------
        */
        function soLabelRows() {
            const labels = $('#productTable thead th').map(function() {
                return $(this).text().replace(/\*/g, '').trim();
            }).get();
            $('#productTable tbody tr').each(function() {
                $(this).children('td').each(function(i) {
                    $(this).attr('data-label', labels[i] || '');
                });
            });
        }
        soLabelRows();

        /*
        |--------------------------------------------------------------------------
        | Helper to Normalize Category Value to Catalog Key
        |--------------------------------------------------------------------------
        */
        function resolveCategoryKey(catVal) {
            if (!catVal) return '';
            let lower = String(catVal).toLowerCase().trim();
            if (lower.indexOf('organ') !== -1 || lower === '1') {
                return 'organics';
            }
            if (lower.indexOf('plant') !== -1 || lower.indexOf('garden') !== -1 || lower.indexOf('foliage') !==
                -1 || lower === '6') {
                return 'garden_plants';
            }
            return catVal;
        }

        /*
        |--------------------------------------------------------------------------
        | Add New Product Row
        |--------------------------------------------------------------------------
        */
        $('#addRow').on('click', function() {
            let row = `
            <tr class="new-product-row">
                <!-- 1. Category -->
                <td>

                    <select name="products[${rowIndex}][n_category_id]" class="form-select category-select mandatory" data-message="Please Select Category">
                        <option value="">Select Category First</option>
                            <?php $__currentLoopData = $productCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <option value="<?php echo e($category->n_category_id); ?>" data-categoryCode="<?php echo e($category->c_category_code); ?>">
                                            <?php echo e($category->c_category_name); ?>

                                        </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>

                <!-- 2. Sub Category -->
                <td>
                    <select name="products[${rowIndex}][n_sub_category_id]" class="form-select subcategory-select" disabled>
                        <option value="">Select Sub Category </option>
                    </select>
                </td>

                <!-- 3. Product -->
                <td>
                    <select name="products[${rowIndex}][c_product_name]" class="form-select product-select mandatory" data-message="Please Select Product" disabled>
                        <option value="">Select Category First</option>
                    </select>
                </td>

                <!-- 4. Attribute / Pack Size -->
                <td>
                    <select name="products[${rowIndex}][c_unit]" class="form-select packSize-select" data-message="Please Select Pack Size" disabled>
                        <option value="">Select Product First</option>

                    </select>
                    <input type="hidden" class="n_product_id" name="products[${rowIndex}][product_id]" value=''>
                </td>

                <!-- 5. HSN Code -->
                <td>
                    <input type="text" name="products[${rowIndex}][c_hsn_code]" class="form-control c_hsn_code" value="" readonly>
                </td>

                <!-- 6. Price (Excl GST) -->
                <td>
                    <input type="text" name="products[${rowIndex}][product_price]" class="form-control price" value="0.00" readonly>
                </td>

                <!-- 7. Quantity -->
                <td>
                    <input type="number" name="products[${rowIndex}][qty]" class="form-control qty" value="1" min="1">
                </td>


                <!-- 9. Discount -->
                <td>
                    <input type="number" name="products[${rowIndex}][discount]" class="form-control discount" value="0.00" step="0.01" min="0">
                </td>

                <!-- 10. GST % -->
                <td>
                    <input type="number" name="products[${rowIndex}][n_gst_percentage]" class="form-control gst_percentage" value="0.00" step="0.01" readonly>
                </td>

                <!-- 11. GST Amount -->
                <td>
                    <input type="text" name="products[${rowIndex}][gst_amount]" class="form-control gst_amount" value="0.00" readonly>
                </td>

                <!-- 12. Discounted / Taxable Price -->
                <td>
                    <input type="text" name="products[${rowIndex}][discounted_price]" class="form-control discounted_price" value="0.00" readonly>
                </td>

                <!-- 13. Total (MRP) -->
                <td>
                    <input type="text" name="products[${rowIndex}][product_total]" class="form-control total" value="0.00" readonly>
                </td>

                <!-- 14. Action -->
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm removeRow">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;

            $('#productTable tbody').append(row);
            rowIndex++;
            soLabelRows();
        });


        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | CATEGORY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '.category-select', function() {

                let row = $(this).closest('.new-product-row');

                let categoryId = $(this).val();

                let subCategory = row.find('.subcategory-select');

                let product = row.find('.product-select');
                let packSize = row.find('.packSize-select');

                // Reset dependent dropdowns
                subCategory.html(
                    '<option value="">Select Category First</option>'
                );

                product.html(
                    '<option value="">Select Sub Category First</option>'
                );

                packSize.html(
                    '<option value="">Select Product First</option>'
                );

                if (!categoryId) {
                    return;
                }


                let url =
                    "<?php echo e(route('admin.get.product.subcategories', ['categoryId' => ':categoryId'])); ?>";
                url = url.replace(':categoryId', categoryId);

                $.ajax({
                    url: url,
                    type: 'GET',

                    success: function(data) {



                        subCategory.empty();

                        subCategory.append(
                            $('<option>', {
                                value: '',
                                text: 'Select Sub Category',

                            })
                        );

                        $.each(data.subcategories, function(index, item) {

                            subCategory.append(
                                $('<option>', {
                                    value: item.n_category_id,
                                    text: item.c_category_name,

                                })
                            );
                            // Enable subcategory dropdown
                            subCategory.prop('disabled', false);
                        });

                    },

                    error: function(xhr) {
                        console.log('Sub Category Error:', xhr.responseText);
                    }
                });
            });


            /*
            |--------------------------------------------------------------------------
            | SUB CATEGORY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '.subcategory-select', function() {

                let row = $(this).closest('.new-product-row');

                let subCategoryId = $(this).val();

                let product = row.find('.product-select');
                let packSize = row.find('.packSize-select');

                // Reset product and attribute
                product.html(
                    '<option value="">Select Product</option>'
                );

                packSize.html(
                    '<option value="">Select Product First</option>'
                );

                if (!subCategoryId) {
                    return;
                }

                let url =
                    "<?php echo e(route('admin.get.products', ['subCategoryId' => ':subCategoryId'])); ?>";
                url = url.replace(':subCategoryId', subCategoryId);

                $.ajax({

                    url: url,
                    type: 'GET',

                    success: function(data) {
                        product.empty();

                        product.append(
                            $('<option>', {
                                value: '',
                                text: 'Select Product',

                            })
                        );

                        $.each(data.products, function(index, item) {

                            product.append(
                                $('<option>', {
                                    value: item.n_product_id,
                                    text: item.c_product_name
                                })
                            );

                            // Enable product dropdown
                            product.prop('disabled', false);

                        });

                    },

                    error: function(xhr) {
                        console.log('Product Error:', xhr.responseText);
                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | PRODUCT CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '.product-select', function() {


                let row = $(this).closest('.new-product-row');
                let categoryCode = row.find(".category-select").find(':selected').attr(
                    "data-categoryCode");
                let productId = $(this).val();

                let productName = $(this).find(':selected').text();

                let packSize = row.find('.packSize-select');

                packSize.html(
                    '<option value="">Select Attribute / Pack Size</option>'
                );

                if (!productId) {
                    return;
                }

                if (String(categoryCode).toUpperCase() === "PLANTS") {

                    // Plant products have no pack size / attribute -> not applicable
                    packSize.html('<option value="">Not applicable</option>')
                        .val('')
                        .prop('disabled', true);

                    let url =
                        "<?php echo e(route('admin.get.attributesFromProductname',['productId' => ':productId'])); ?>";
                    url = url.replace(':productId', productId);

                    $.ajax({

                        url: url,
                        type: 'GET',

                        success: function(data) {

                            // Example:
                            // Set HSN
                            row.find('.c_hsn_code').val(data.c_hsn_code);
                            //set gst percentage
                            row.find('.gst_percentage').val(data.n_gst_percentage);
                            row.find('.n_product_id').val(data.n_product_id);

                            globalmrp = data.n_mrp;
                            globalGstPercentage = data.n_gst_percentage

                            calculateRowWithMrp(row, data.n_mrp, data
                                .n_gst_percentage);

                        },

                        error: function(xhr) {
                            console.log(
                                'Attribute Details Error:',
                                xhr.responseText
                            );
                        }

                    });

                } else {

                    let url =
                        "<?php echo e(route('admin.get.product.packSize',['productName' => ':productName'])); ?>";
                    url = url.replace(':productName', productName);

                    $.ajax({

                        url: url,
                        type: 'GET',

                        success: function(data) {
                            packSize.empty();

                            packSize.append(
                                $('<option>', {
                                    value: '',
                                    text: 'Select Pack Size',
                                })
                            );
                            $.each(data.units, function(index, item) {

                                packSize.append(
                                    $('<option>', {
                                        value: item.c_unit,
                                        text: item.c_unit
                                    })
                                );

                                // Enable packSize dropdown
                                packSize.prop('disabled', false);

                            });

                        },

                        error: function(xhr) {
                            console.log(
                                'Pack Size Error:',
                                xhr.responseText
                            );
                        }

                    });
                }

            });


            /*
            |--------------------------------------------------------------------------
            | ATTRIBUTE / PACK SIZE CHANGE
            |--------------------------------------------------------------------------
            */

            let globalmrp = 0;
            let globalGstPercentage = '';

            $(document).on('change', '.packSize-select', function() {

                let row = $(this).closest('.new-product-row');
                let productId = row.find(".product-select").find(':selected').val();
                let productName = row.find(".product-select").find(':selected').text();
                let packSize = $(this).val();

                if (!packSize) {
                    return;
                }

                let url =
                    "<?php echo e(route('admin.get.product.attributes',['productName' => ':productName','packSize'=>':packSize'])); ?>";
                url = url.replace(':productName', productName);
                url = url.replace(':packSize', packSize);

                $.ajax({

                    url: url,
                    type: 'GET',

                    success: function(data) {

                        // Example:
                        // Set HSN
                        row.find('.c_hsn_code').val(data.c_hsn_code);
                        //set gst percentage
                        row.find('.gst_percentage').val(data.n_gst_percentage);
                        row.find('.n_product_id').val(data.n_product_id);

                        globalmrp = data.n_mrp;
                        globalGstPercentage = data.n_gst_percentage

                        calculateRowWithMrp(row, data.n_mrp, data.n_gst_percentage);

                    },

                    error: function(xhr) {
                        console.log(
                            'Attribute Details Error:',
                            xhr.responseText
                        );
                    }

                });

            });

            /*
            |--------------------------------------------------------------------------
            | Quantity / Discount Change Event
            |--------------------------------------------------------------------------
            */

            $(document).on('input change', '.qty, .discount', function() {

                let row = $(this).closest('.new-product-row');

                calculateRowWithMrp(
                    row,
                    parseFloat(globalmrp) || 0,
                    parseFloat(globalGstPercentage) || 0
                );
                if ($(this).closest('.existing-product-row').length) {
                    calculateExistingRow($(this).closest('.existing-product-row'));
                    calculateSummary();
                }

            });
            /* $(document).on('input', '.qty, .discount', function () {

                const input = this;
                const row = $(input).closest('.new-product-row');

                clearTimeout(row.data('calculationTimer'));

                const timer = setTimeout(function () {

                    calculateRowWithMrp(
                        row,
                        parseFloat(globalmrp) || 0,
                        parseFloat(globalGstPercentage) || 0
                    );

                }, 200);

                row.data('calculationTimer', timer);
            });


            $(document).on('change', '.qty, .discount', function () {

                const row = $(this).closest('.new-product-row');

                clearTimeout(row.data('calculationTimer'));

                calculateRowWithMrp(
                    row,
                    parseFloat(globalmrp) || 0,
                    parseFloat(globalGstPercentage) || 0
                );

            }); */
        });
        // /*
        // |--------------------------------------------------------------------------
        // | Subcategory Selection Change
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.subcategory-select', function () {
        //     let row = $(this).closest('tr');
        //     let catKey = resolveCategoryKey(row.find('.category-select').val());
        //     let subCatVal = $(this).val();

        //     let productSelect = row.find('.product-select');
        //     let attrSelect = row.find('.attribute-select');

        //     productSelect.empty();
        //     attrSelect.empty().prop('disabled', true);
        //     clearRowPricing(row);

        //     if (!catKey || !subCatVal || subCatVal === 'NA') {
        //         productSelect.html('<option value="">Select Sub Category First</option>').prop('disabled', true);
        //         return;
        //     }

        //     let catData = productCatalog[catKey];
        //     if (catData && catData.subcategories && catData.subcategories[subCatVal]) {
        //         productSelect.prop('disabled', false);
        //         productSelect.append('<option value="">Select Product</option>');

        //         catData.subcategories[subCatVal].forEach(function (p) {
        //             let opt = $(`<option value="${p.id}">${p.name} ${p.code ? '(' + p.code + ')' : ''}</option>`);
        //             opt.data('product-info', p);
        //             productSelect.append(opt);
        //         });
        //     }
        // });

        // /*
        // |--------------------------------------------------------------------------
        // | Product Selection Change -> Populates Attributes (Pack Sizes / Variants)
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.product-select', function () {
        //     let row = $(this).closest('tr');
        //     let selectedOption = $(this).find(':selected');
        //     let productInfo = selectedOption.data('product-info');

        //     let attrSelect = row.find('.attribute-select');
        //     attrSelect.empty();
        //     clearRowPricing(row);

        //     if (!productInfo || !productInfo.attributes || productInfo.attributes.length === 0) {
        //         attrSelect.html('<option value="">No Attributes Available</option>').prop('disabled', true);
        //         return;
        //     }

        //     attrSelect.prop('disabled', false);

        //     if (productInfo.attributes.length > 1) {
        //         attrSelect.append('<option value="">Select Pack Size / Attribute</option>');
        //     }

        //     productInfo.attributes.forEach(function (attr) {
        //         let opt = $(`<option value="${attr.name}">${attr.name} - ₹${attr.mrp.toFixed(2)}</option>`);
        //         opt.attr('data-price', attr.mrp);
        //         opt.attr('data-unit', attr.unit || '');
        //         opt.attr('data-gst', productInfo.gst || 0);
        //         opt.attr('data-hsn-code', productInfo.hsn || '');
        //         attrSelect.append(opt);
        //     });

        //     // Automatically select if single attribute (e.g., plants or 1 NOS)
        //     if (productInfo.attributes.length === 1) {
        //         attrSelect.val(productInfo.attributes[0].name).trigger('change');
        //     }
        // });

        // /*
        // |--------------------------------------------------------------------------
        // | Attribute Selection Change -> Calculates Pricing and Totals
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.attribute-select', function () {
        //     let row = $(this).closest('tr');
        //     let selectedOption = $(this).find(':selected');

        //     if (!selectedOption.val()) {
        //         clearRowPricing(row);
        //         calculateSummary();
        //         return;
        //     }

        //     let mrp = parseFloat(selectedOption.attr('data-price')) || 0;
        //     let gstPercentage = parseFloat(selectedOption.attr('data-gst')) || 0;
        //     let hsnCode = selectedOption.attr('data-hsn-code') || '';
        //     let unit = selectedOption.attr('data-unit') || '';

        //     row.find('.c_hsn_code').val(hsnCode);
        //     row.find('.c_unit').val(unit);
        //     row.find('.gst_percentage').val(gstPercentage.toFixed(2));

        //     calculateRowWithMrp(row, mrp, gstPercentage);
        // });


        /*
        |--------------------------------------------------------------------------
        | Calculation Formula (MRP Includes GST)
        |--------------------------------------------------------------------------
        */
        function calculateRowWithMrp(row, mrp, gstPercentage) {

            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;

            if (qty < 0) qty = 0;
            if (discount < 0) discount = 0;

            let price = 0;
            let grossAmount = 0;
            let taxableAmount = 0;
            let gstAmount = 0;
            let lineTotal = 0;

            if (mrp > 0) {
                // Exclusive price
                price = mrp / (1 + (gstPercentage / 100));

                // Price × Quantity
                grossAmount = price * qty;

                // Taxable Amount
                taxableAmount = grossAmount - discount;
                if (taxableAmount < 0) taxableAmount = 0;

                // GST Amount
                gstAmount = (taxableAmount * gstPercentage) / 100;

                // Line Total
                lineTotal = taxableAmount + gstAmount;
            }

            row.find('.price').val(price.toFixed(2));
            row.find('.gst_amount').val(gstAmount.toFixed(2));
            row.find('.discounted_price').val(taxableAmount.toFixed(2));
            row.find('.total').val(lineTotal.toFixed(2));

            calculateSummary();
        }

        function calculateExistingRow(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;
            let gstPercentage = parseFloat(row.find('.gst_percentage').val()) || 0;

            if (qty < 0) qty = 0;
            if (discount < 0) discount = 0;

            let grossAmount = price * qty;
            let taxableAmount = grossAmount - discount;
            if (taxableAmount < 0) taxableAmount = 0;

            let gstAmount = (taxableAmount * gstPercentage) / 100;
            let lineTotal = taxableAmount + gstAmount;

            row.find('.gst_amount').val(gstAmount.toFixed(2));
            row.find('.discounted_price').val(taxableAmount.toFixed(2));
            row.find('.total').val(lineTotal.toFixed(2));
        }

        function clearRowPricing(row) {
            row.find('.c_hsn_code').val('');
            row.find('.price').val('0.00');
            row.find('.c_unit').val('');
            row.find('.discount').val('0.00');
            row.find('.gst_percentage').val('0.00');
            row.find('.gst_amount').val('0.00');
            row.find('.discounted_price').val('0.00');
            row.find('.total').val('0.00');
        }

        /*
        |--------------------------------------------------------------------------
        | Summary Totals
        |--------------------------------------------------------------------------
        */
        /*  function calculateSummary() {
             let totalSales = 0;
             let totalDiscount = 0;
             let totalTaxable = 0;
             let totalGst = 0;

             $('#productTable tbody tr').each(function () {
                 let row = $(this);
                 let price = parseFloat(row.find('.price').val()) || 0;
                 let qty = parseFloat(row.find('.qty').val()) || 0;
                 let discount = parseFloat(row.find('.discount').val()) || 0;
                 let gstAmount = parseFloat(row.find('.gst_amount').val()) || 0;
                 let taxable = parseFloat(row.find('.discounted_price').val()) || 0;

                 let gross = price * qty;

                 totalSales += gross;
                 totalDiscount += discount;
                 totalTaxable += taxable;
                 totalGst += gstAmount;
             });

             let netSalesAmount = totalTaxable + totalGst;

             $('#summaryTotalSales').val(totalSales.toFixed(2));
             $('#summaryTotalDiscount').val(totalDiscount.toFixed(2));
             $('#summaryGstAmount').val(totalGst.toFixed(2));
             $('#summaryNetSales').val(netSalesAmount.toFixed(2));
             $('#n_amount_to_pay').val(netSalesAmount.toFixed(2));
         } */

        function calculateSummary() {
            let totalSales = 0;
            let totalDiscount = 0;
            let totalTaxable = 0;
            let totalGst = 0;

            $('#productTable tbody tr').each(function() {

                let row = $(this);

                let price = parseFloat(row.find('.price').val()) || 0;
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let discount = parseFloat(row.find('.discount').val()) || 0;
                let gstAmount = parseFloat(row.find('.gst_amount').val()) || 0;

                let gross = price * qty;

                totalSales += gross;
                totalDiscount += discount;
                totalGst += gstAmount;

                // Taxable amount after discount
                totalTaxable += Math.max(gross - discount, 0);
            });

            // Net = Taxable + GST
            let netSalesAmount = totalTaxable + totalGst;

            $('#summaryTotalSales').val(totalSales.toFixed(2));
            $('#summaryTotalDiscount').val(totalDiscount.toFixed(2));
            $('#summaryGstAmount').val(totalGst.toFixed(2));
            $('#summaryNetSales').val(netSalesAmount.toFixed(2));
            $('#n_amount_to_pay').val(netSalesAmount.toFixed(2));
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Product Row
        |--------------------------------------------------------------------------
        */
        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
            calculateSummary();
        });

        /*
        |--------------------------------------------------------------------------
        | Payment Mode Handling
        |--------------------------------------------------------------------------
        */
        $('.mode_of_payment').on('change', function() {
            handlePaymentMode();
        });

        const hasStoredPaymentImage = <?php echo e(isset($sale) && $sale->payment_image ? 'true' : 'false'); ?>;

        function handlePaymentMode() {
            let paymentMode = $('.mode_of_payment:checked').val();
            // A stored proof is kept unless the user removed it
            const needsProofFile = !hasStoredPaymentImage || $('#remove_payment_image').val() === '1';

            if (!paymentMode) {
                $('#paymet-proofs').hide();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').removeClass('mandatory');
                $('#payment_image').removeClass('mandatory');
                return;
            }

            if (paymentMode === 'Paid to Franchise' || paymentMode === 'Cash on Delivery') {
                $('#paymet-proofs').hide();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').removeClass('mandatory');
                $('#payment_image').removeClass('mandatory');
            } else {
                $('#paymet-proofs').show();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').addClass('mandatory');
                $('#payment_image').toggleClass('mandatory', needsProofFile);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Franchise Location Cascading (State -> District -> Panchayath -> Store)
        |--------------------------------------------------------------------------
        */
        $('#franchise_state').on('change', function() {
            let stateId = $(this).val();

            $('#franchise_district').html('<option value="">Loading...</option>');
            $('#franchise_panchayath').html('<option value="">Select Panchayath</option>');
            $('#franchise').html('<option value="">Select Franchise</option>');

            if (!stateId) {
                $('#franchise_district').html('<option value="">Select District</option>');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.filterDistrict')); ?>",
                data: {
                    state: stateId
                },
                dataType: 'json',
                success: function(response) {
                    $('#franchise_district').html(
                        '<option value="">Select District</option>');
                    if (response.districts) {
                        $.each(response.districts, function(index, district) {
                            $('#franchise_district').append(
                                `<option value="${district.id}">${district.district_name}</option>`
                            );
                        });
                    }
                },
                error: function() {
                    $('#franchise_district').html(
                        '<option value="">Unable to load districts</option>');
                }
            });
        });

        $('#franchise_district').on('change', function() {
            let districtId = $(this).val();

            $('#franchise_panchayath').html('<option value="">Loading...</option>');
            $('#franchise').html('<option value="">Select Franchise</option>');

            if (!districtId) {
                $('#franchise_panchayath').html('<option value="">Select Panchayath</option>');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.filterPanchayath')); ?>",
                data: {
                    district: districtId
                },
                dataType: 'json',
                success: function(response) {
                    $('#franchise_panchayath').html(
                        '<option value="">Select Panchayath</option>');
                    if (response.panchayaths && response.panchayaths.length > 0) {
                        $.each(response.panchayaths, function(index, panchayat) {
                            $('#franchise_panchayath').append(
                                `<option value="${panchayat.id}">${panchayat.panchayath_name}</option>`
                            );
                        });
                    } else {
                        $('#franchise_panchayath').html(
                            '<option value="">No Panchayaths Found</option>');
                    }
                },
                error: function() {
                    $('#franchise_panchayath').html(
                        '<option value="">Unable to load Panchayaths</option>');
                }
            });
        });

        $('#franchise_panchayath').on('change', function() {
            const panchayathId = $(this).val();
            if (!panchayathId) {
                $('#franchise').html('<option value="">Select Franchise</option>');
                return;
            }
            findNearestFranchise(panchayathId);
        });

        function findNearestFranchise(panchayathId) {
            $('#franchise').html('<option value="">Finding franchise...</option>');

            fetch("<?php echo e(route('admin.franchise.nearest')); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        panchayath_id: panchayathId
                    })
                })
                .then(res => res.json())
                .then(function(data) {
                    $('#franchise').html('<option value="">Select Franchise</option>');
                    if (!data.success) {
                        $('#franchise').html('<option value="">No Franchise Found</option>');
                        return;
                    }

                    let franchises = Array.isArray(data.franchises) ? data.franchises : (data.franchises ? [
                        data.franchises
                    ] : []);
                    if (franchises.length === 0) {
                        $('#franchise').html('<option value="">No Franchise Found</option>');
                        return;
                    }

                    franchises.forEach(function(f) {
                        $('#franchise').append(
                            `<option value="${f.n_store_id}">${f.c_store_name} ${f.c_store_code ? '(' + f.c_store_code + ')' : ''}</option>`
                        );
                    });

                    $('#franchise').val(franchises[0].n_store_id);
                })
                .catch(function() {
                    $('#franchise').html('<option value="">Unable to find franchise</option>');
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Order Type (Company vs Franchise)
        |--------------------------------------------------------------------------
        */
        function toggleOrderType() {
            const orderType = $('input[name="order_type"]:checked').val();
            if (orderType === 'franchise') {
                $('#franchise-location-details').show();
                $('#franchise_state, #franchise_district, #franchise_panchayath, #franchise').addClass(
                    'mandatory');
            } else if (orderType === 'company') {
                $('#franchise-location-details').hide();
                $('#franchise_state, #franchise_district, #franchise_panchayath, #franchise').removeClass(
                    'mandatory');
            }
        }

        $('input[name="order_type"]').on('change', toggleOrderType);

        /*
        |--------------------------------------------------------------------------
        | Image Upload Preview Helper
        |--------------------------------------------------------------------------
        */
        function setupImageUpload(inputId, previewId, containerId, removeInputId, removeButtonId) {
            $(document).on('change', '#' + inputId, function(event) {
                const file = event.target.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    $(this).val('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result).show();
                    $('#' + removeInputId).val('0');

                    if ($('#' + removeButtonId).length === 0) {
                        $('#' + containerId).append(
                            `<br><button type="button" id="${removeButtonId}" class="btn btn-danger btn-sm mt-2">Remove Image</button>`
                        );
                    } else {
                        $('#' + removeButtonId).show();
                    }
                };
                reader.readAsDataURL(file);
            });

            $(document).on('click', '#' + removeButtonId, function() {
                $('#' + inputId).val('');
                $('#' + previewId).attr('src', '').hide();
                $('#' + removeInputId).val('1');
                $(this).hide();
                if (typeof handlePaymentMode === 'function') handlePaymentMode();
            });
        }

        setupImageUpload('payment_image', 'payment_image_preview', 'payment_preview_container',
            'remove_payment_image', 'remove_payment_image_btn');
        setupImageUpload('booklet_image', 'booklet_image_preview', 'booklet_image_preview_container',
            'remove_booklet_image', 'remove_booklet_image_btn');

        /*
    |--------------------------------------------------------------------------
    | View Mode
    |--------------------------------------------------------------------------
    */
        var viewmode = "<?php echo e($viewmode ?? 'off'); ?>";
        if (viewmode === 'on') {
            $('#frm_create input:not([type="hidden"]):not([type="button"]):not([type="submit"])').prop(
                'readonly', true);
            $('#frm_create textarea').prop('readonly', true);
            $('#frm_create select, #frm_create input[type="radio"], #frm_create input[type="checkbox"], #frm_create input[type="file"], #addRow, .removeRow')
                .prop('disabled', true);
        }

        // Initialize Page
        calculateSummary();
        toggleOrderType();
        handlePaymentMode();
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Toggle & Mobile Lookup
    |--------------------------------------------------------------------------
    */
    document.addEventListener('DOMContentLoaded', function() {
        const lookupCard = document.getElementById('lookupCard');
        const newCustomer = document.getElementById('newCustomer');
        const existingCustomer = document.getElementById('existingCustomer');

        function toggleCustomerType() {
            const selected = document.querySelector('input[name="c_customer_type"]:checked');
            if (!selected || !lookupCard) return;

            if (selected.value === 'existing') {
                lookupCard.classList.remove('d-none');
            } else {
                lookupCard.classList.add('d-none');
                $("#c_customer_code").val(
                    "<?php echo e($customerCode ?? (isset($sale) ? $sale->customer?->c_customer_code : '')); ?>");
            }
        }

        if (newCustomer) newCustomer.addEventListener('change', toggleCustomerType);
        if (existingCustomer) existingCustomer.addEventListener('change', toggleCustomerType);
        toggleCustomerType();

        const lookupBtn = document.getElementById('lookupBtn');
        if (lookupBtn) {
            lookupBtn.addEventListener('click', function() {
                const mobile = document.getElementById('lookupMobile').value.trim();
                if (!/^[0-9]{10}$/.test(mobile)) {
                    alert('Please enter a valid 10-digit mobile number.');
                    return;
                }

                fetch("<?php echo e(route('admin.leads.existingCustomer')); ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                        },
                        body: JSON.stringify({
                            mobile: mobile
                        })
                    })
                    .then(res => res.json())
                    .then(function(data) {
                        if (data.status === true && data.customer) {
                            $("#n_customer_id").val(data.customer.n_customer_id);
                            $("#c_customer_code").val(data.customer.c_customer_code);
                            $(".c_customer_name").val(data.customer.c_customer_name);
                            $('[name="n_whatsapp"]').val(data.customer.n_whatsapp || '');
                            $('[name="n_mobile"]').val(data.customer.n_mobile || '');
                            $('[name="c_email"]').val(data.customer.c_email || '');
                            $('[name="c_address"]').val(data.customer.c_address || '');
                            $('[name="c_pincode"]').val(data.customer.c_pincode || '');

                            if (data.customer.n_state_id) {
                                $('#n_state_id').val(data.customer.n_state_id);
                                districtFilter(data.customer.n_state_id, data.customer
                                    .n_district_id);
                            }
                            $('#lookupMessage').text('Customer loaded successfully!');
                        } else {
                            alert('Customer not found.');
                        }
                    })
                    .catch(function() {
                        alert('Unable to find customer. Please try again.');
                    });
            });
        }

        function districtFilter(state, selectedDistrict = null) {
            if (!state) return;
            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.filterDistrict')); ?>",
                data: {
                    state: state
                },
                dataType: 'json',
                success: function(data) {
                    $('#n_district_id').empty().append('<option value="">Select District</option>');
                    if (data.districts) {
                        $.each(data.districts, function(index, d) {
                            $('#n_district_id').append(
                                `<option value="${d.id}">${d.district_name}</option>`);
                        });
                        if (selectedDistrict) {
                            $('#n_district_id').val(selectedDistrict);
                        }
                    }
                }
            });
        }
    });
    </script>
    <script>
    /*
    |--------------------------------------------------------------------------
    | Order location: address -> latitude / longitude (OpenStreetMap Nominatim)
    | Same approach as Franchise > Add: try the most specific address first and
    | fall back to broader areas; the user confirms / adjusts the pin on a map.
    |--------------------------------------------------------------------------
    */
    $(function() {
        const $lat = $('#so_latitude'), $lng = $('#so_longitude'), $status = $('#soLocationStatus');
        const $btn = $('#soGetLocationBtn'), $mapBox = $('#soLocationMap');
        const BTN_HTML = '<i class="ti ti-map-pin-search"></i> Get Location from Address';
        let map = null, marker = null;

        function say(type, html) {
            $status.removeClass('text-muted text-success text-danger text-warning')
                .addClass('text-' + type).html(html);
        }

        function validCoords(lat, lng) {
            return isFinite(lat) && isFinite(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180
                && $lat.val().toString().trim() !== '' && $lng.val().toString().trim() !== '';
        }

        function ensureMap(lat, lng, zoom) {
            $mapBox.show();
            if (!map) {
                map = L.map('soLocationMap').setView([lat, lng], zoom);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                map.on('click', function(e) {
                    setLocation(e.latlng.lat, e.latlng.lng, 'manual');
                });
            } else {
                map.setView([lat, lng], zoom);
            }
            setTimeout(function() { map.invalidateSize(); }, 250);
        }

        function placeMarker(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
                return;
            }
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function() {
                const p = marker.getLatLng();
                setLocation(p.lat, p.lng, 'manual');
            });
        }

        // source: 'manual' | 'exact' | 'approx'
        /*
        | Nearest franchise autofill.
        | Picks the closest active franchise (Haversine distance) to the order location and fills
        | Order Type (if empty), State, District, Panchayath and Nearest Franchise from it - only
        | when it is within NEAREST_FRANCHISE_MAX_KM. Every field stays editable: a franchise chosen
        | by hand is never overwritten, and neither is an Order Type / location the user set by hand.
        | Values are set without firing the State/District/Panchayath change handlers, because those
        | reset the franchise list.
        */
        const NEAREST_FRANCHISE_MAX_KM = <?php echo e((float) config('spc.nearest_franchise_max_km', 50)); ?>;
        const FRANCHISES = <?php echo json_encode(collect($franchises ?? [])->map(function ($f) {
            return [
                'id' => $f->n_store_id,
                'name' => trim(($f->c_store_name ?? '') . ($f->c_store_code ? ' (' . $f->c_store_code . ')' : '')),
                'lat' => $f->latitude !== null ? (float) $f->latitude : null,
                'lng' => $f->longitude !== null ? (float) $f->longitude : null,
                'state' => $f->n_state_id,
                'district' => $f->n_district_id,
                'panchayath' => $f->n_panchayath_id,
            ];
        })->values()); ?>;
        const $franchise = $('#franchise'), $fHint = $('#soFranchiseHint');
        const $fState = $('#franchise_state'), $fDistrict = $('#franchise_district'),
            $fPanchayath = $('#franchise_panchayath');
        let franchiseAutoSet = false;   // current franchise value was set by this feature
        let franchiseManual = false;    // user picked a franchise by hand
        let locationManual = false;     // user picked state/district/panchayath by hand
        let orderTypeAutoSet = false;   // Order Type was set by this feature
        let autoRun = 0;                // ignores stale async results when the pin moves quickly

        function haversineKm(lat1, lon1, lat2, lon2) {
            const R = 6371, rad = Math.PI / 180;
            const dLat = (lat2 - lat1) * rad, dLon = (lon2 - lon1) * rad;
            const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1 * rad) * Math.cos(lat2 * rad) * Math.sin(dLon / 2) ** 2;
            return 2 * R * Math.asin(Math.sqrt(a));
        }

        function nearestFranchise(lat, lng) {
            let best = null;
            FRANCHISES.forEach(function(f) {
                if (f.lat === null || f.lng === null || !isFinite(f.lat) || !isFinite(f.lng)) { return; }
                const d = haversineKm(lat, lng, f.lat, f.lng);
                if (!best || d < best.km) { best = { f: f, km: d }; }
            });
            return best;
        }

        function fillOptions($sel, items, idKey, textKey, placeholder) {
            $sel.empty().append('<option value="">' + placeholder + '</option>');
            (items || []).forEach(function(x) {
                $sel.append($('<option>').val(x[idKey]).text(x[textKey]));
            });
        }

        // State -> District -> Panchayath, populated straight from the franchise record
        function fillFranchiseLocation(f, run) {
            if (locationManual || !f.state) { return; }
            $fState.val(String(f.state));
            if (!f.district) { return; }
            $.getJSON("<?php echo e(route('admin.filterDistrict')); ?>", { state: f.state }).done(function(res) {
                if (run !== autoRun || locationManual) { return; }
                fillOptions($fDistrict, res.districts, 'id', 'district_name', 'Select District');
                $fDistrict.val(String(f.district));
                if (!f.panchayath) { return; }
                $.getJSON("<?php echo e(route('admin.filterPanchayath')); ?>", { district: f.district }).done(function(r2) {
                    if (run !== autoRun || locationManual) { return; }
                    fillOptions($fPanchayath, r2.panchayaths, 'id', 'panchayath_name', 'Select Panchayath');
                    $fPanchayath.val(String(f.panchayath));
                });
            });
        }


        /*
        | Ranked suggestions: the closest franchises with their distance, one click to use.
        | The closest is still auto-selected below; this just lets the user see and pick others.
        */
        const $rank = $('#soFranchiseRank');

        function rankFranchises(lat, lng, limit) {
            const out = [];
            FRANCHISES.forEach(function(f) {
                if (f.lat === null || f.lng === null || !isFinite(f.lat) || !isFinite(f.lng)) { return; }
                out.push({ f: f, km: haversineKm(lat, lng, f.lat, f.lng) });
            });
            out.sort(function(a, b) { return a.km - b.km; });
            return out.slice(0, limit);
        }

        function renderRank(lat, lng) {
            const list = rankFranchises(lat, lng, 3);
            if (!list.length) { $rank.hide().empty(); return; }
            const selected = String($franchise.val() || '');
            $rank.empty().show();
            $rank.append('<div class="small fw-semibold mb-1">Nearest franchises</div>');
            list.forEach(function(x, i) {
                const far = x.km > NEAREST_FRANCHISE_MAX_KM;
                const isSel = selected === String(x.f.id);
                const $row = $('<div class="d-flex justify-content-between align-items-center border rounded px-2 py-1 mb-1"></div>')
                    .css(isSel ? { borderColor: '#2f7d4f', background: 'rgba(47,125,79,.08)' } : {});
                const $label = $('<div class="small"></div>')
                    .append($('<span class="fw-semibold"></span>').text(x.f.name))
                    .append($('<span class="text-muted ms-2"></span>').text(x.km.toFixed(1) + ' km'));
                if (i === 0) { $label.append(' <span class="badge bg-success ms-1">Closest</span>'); }
                if (far) { $label.append(' <span class="badge bg-warning text-dark ms-1">Far</span>'); }
                const $btn = $('<button type="button" class="btn btn-sm btn-outline-success"></button>')
                    .text(isSel ? 'Selected' : 'Use').prop('disabled', isSel)
                    .on('click', function() { chooseFranchise(x.f, x.km); });
                $row.append($label, $btn);
                $rank.append($row);
            });
        }

        function chooseFranchise(f, km) {
            if (!$franchise.find('option[value="' + f.id + '"]').length) {
                $franchise.append($('<option>').val(f.id).text(f.name));
            }
            $franchise.val(String(f.id)).trigger('change');   // counts as a manual choice
            if (!$('input[name="order_type"]:checked').length && $('#franchise_type').length) {
                $('#franchise_type').prop('checked', true).trigger('change');
            }
            fillFranchiseLocation(f, ++autoRun);
            $fHint.removeClass('text-muted text-warning').addClass('text-success')
                .text('Selected ' + f.name + ' (' + km.toFixed(1) + ' km away).');
            renderRank(parseFloat($lat.val()), parseFloat($lng.val()));
        }

        function autoFillFranchise(lat, lng) {
            if (franchiseManual) { renderRank(lat, lng); return; }   // respect manual choice
            const run = ++autoRun;
            const best = nearestFranchise(lat, lng);
            if (best && best.km <= NEAREST_FRANCHISE_MAX_KM) {
                const f = best.f;
                if (!$franchise.find('option[value="' + f.id + '"]').length) {
                    $franchise.append($('<option>').val(f.id).text(f.name));
                }
                $franchise.val(String(f.id)).trigger('change.auto');
                franchiseAutoSet = true;

                // Order Type: only when nothing is chosen yet
                if (!$('input[name="order_type"]:checked').length && $('#franchise_type').length) {
                    $('#franchise_type').prop('checked', true).trigger('change');
                    orderTypeAutoSet = true;
                }

                fillFranchiseLocation(f, run);
                $fHint.removeClass('text-muted text-warning').addClass('text-success')
                    .text('Auto-selected nearest franchise (' + best.km.toFixed(1) +
                        ' km away) and filled its state, district and panchayath. You can change them manually.');
                renderRank(lat, lng);
            } else {
                if (franchiseAutoSet) { $franchise.val(''); }
                franchiseAutoSet = false;
                $fHint.removeClass('text-muted text-success').addClass('text-warning')
                    .text('No franchise found within ' + NEAREST_FRANCHISE_MAX_KM + ' km. Please select one manually.');
                renderRank(lat, lng);
            }
        }

        // Any hand-made selection locks out autofill for that field
        $franchise.on('change', function(e) {
            if (e.namespace === 'auto') { return; }
            franchiseAutoSet = false;
            franchiseManual = $franchise.val() !== '';
            if (franchiseManual) { $fHint.text(''); }
        });
        $fState.add($fDistrict).add($fPanchayath).on('change', function() { locationManual = true; });
        $('input[name="order_type"]').on('change', function(e) {
            if (!e.isTrigger) { orderTypeAutoSet = false; }
        });

        // Values already saved on the order (edit page / validation error) count as chosen
        if ($franchise.val()) { franchiseManual = true; }
        if ($fState.val() || $fDistrict.val() || $fPanchayath.val()) { locationManual = true; }

        // Edit page / validation error: show the ranking for the saved location
        (function() {
            const lat0 = parseFloat($lat.val()), lng0 = parseFloat($lng.val());
            if (validCoords(lat0, lng0)) { renderRank(lat0, lng0); }
        })();

        function setLocation(lat, lng, source, note) {
            lat = parseFloat(lat);
            lng = parseFloat(lng);
            $lat.val(lat.toFixed(7));
            $lng.val(lng.toFixed(7));
            ensureMap(lat, lng, source === 'approx' ? 14 : 16);
            placeMarker(lat, lng);
            autoFillFranchise(lat, lng);
            if (source === 'manual') {
                say('success', '&#10003; Location selected on the map.');
            } else if (source === 'approx') {
                say('warning', '&#9888; Approximate location (' + note + '). Please drag the pin to the exact spot.');
            } else {
                say('success', '&#10003; Location found. Please verify the pin.');
            }
        }

        function geocode(query) {
            return $.ajax({
                url: 'https://nominatim.openstreetmap.org/search',
                type: 'GET',
                dataType: 'json',
                data: { q: query, format: 'json', limit: 1, countrycodes: 'in' }
            });
        }

        function selectedText(sel) {
            const $o = $(sel + ' option:selected');
            const t = $o.length && $o.val() ? $o.text().trim() : '';
            return t;
        }

        $btn.on('click', function() {
            const address = $('#c_address').val().trim();
            const postOffice = $('#c_post_office').val().trim();
            const thaluk = $('#c_thaluk').val().trim();
            const pincode = $('#c_pincode').val().trim();
            const state = selectedText('#n_state_id');
            const district = selectedText('#n_district_id');

            if (!address) { say('danger', 'Please enter the address first.'); $('#c_address').focus(); return; }
            if (!state) { say('danger', 'Please select a state.'); $('#n_state_id').focus(); return; }
            if (!district) { say('danger', 'Please select a district.'); $('#n_district_id').focus(); return; }

            const join = (...parts) => parts.filter(Boolean).join(', ');
            const pin = /^\d{6}$/.test(pincode) ? pincode : '';

            // most specific -> least specific; label is shown when a fallback is used
            const attempts = [
                { q: join(address, postOffice, thaluk, district, state, pin, 'India'), label: null },
                { q: join(address, thaluk, district, state, 'India'), label: null },
                { q: join(postOffice, thaluk, district, state, pin, 'India'), label: 'matched by post office / taluk' },
                pin ? { q: join(pin, 'India'), label: 'matched by pincode ' + pin } : null,
                { q: join(thaluk, district, state, 'India'), label: 'matched by taluk' },
                { q: join(district, state, 'India'), label: 'matched by district only' }
            ].filter(Boolean).filter(function(a, i, arr) {
                return arr.findIndex(b => b.q === a.q) === i;
            });

            $btn.prop('disabled', true).html('<i class="ti ti-loader-2"></i> Searching...');
            say('muted', 'Finding location...');

            function finish() { $btn.prop('disabled', false).html(BTN_HTML); }

            function tryAt(i) {
                if (i >= attempts.length) {
                    finish();
                    say('danger', 'Location not found. Please check the address, or click "Select on Map" to pin it manually.');
                    return;
                }
                geocode(attempts[i].q).done(function(res) {
                    if (res && res.length) {
                        finish();
                        setLocation(res[0].lat, res[0].lon, attempts[i].label ? 'approx' : 'exact', attempts[i].label);
                    } else {
                        // Nominatim allows ~1 request/second
                        setTimeout(function() { tryAt(i + 1); }, 1100);
                    }
                }).fail(function() {
                    finish();
                    say('danger', 'Could not reach the location service. Check your connection, or click "Select on Map" to pin it manually.');
                });
            }
            tryAt(0);
        });

        $('#soToggleMapBtn').on('click', function() {
            if ($mapBox.is(':visible') && map) {
                $mapBox.hide();
                return;
            }
            const lat = parseFloat($lat.val()), lng = parseFloat($lng.val());
            if (validCoords(lat, lng)) {
                ensureMap(lat, lng, 16);
                placeMarker(lat, lng);
            } else {
                ensureMap(10.8505, 76.2711, 8); // Kerala, same default as the franchise form
                say('muted', 'Click on the map to drop a pin.');
            }
        });

        // Typed / pasted coordinates move the pin
        $lat.add($lng).on('change', function() {
            const lat = parseFloat($lat.val()), lng = parseFloat($lng.val());
            if ($lat.val().trim() === '' && $lng.val().trim() === '') { return; }
            if (!validCoords(lat, lng)) {
                say('danger', 'Enter a valid latitude (-90 to 90) and longitude (-180 to 180).');
                return;
            }
            setLocation(lat, lng, 'manual');
        });

        // Address edited after a pin was set -> remind, don't silently keep a stale pin
        $('#c_address, #c_post_office, #c_thaluk, #c_pincode, #n_state_id, #n_district_id').on('change', function() {
            if ($lat.val().trim() !== '') {
                say('warning', 'Address changed. Click "Get Location from Address" to refresh the location.');
            }
        });

        // Edit page / validation error: show the saved pin
        const l0 = parseFloat($lat.val()), g0 = parseFloat($lng.val());
        if (validCoords(l0, g0)) {
            ensureMap(l0, g0, 16);
            placeMarker(l0, g0);
            say('muted', 'Saved location shown. Change the address and click "Get Location from Address" to update it.');
        }
    });
    </script>
    <?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/admin/sales/create.blade.php ENDPATH**/ ?>