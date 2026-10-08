@extends('layouts.app')

@push('styles')
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

/* View Details: <fieldset disabled> keeps fields read-only; make them look like plain read-only values */
.so-wrap fieldset.so-fieldset {
    border: 0;
    padding: 0;
    margin: 0;
    min-width: 0;
}

.so-wrap fieldset.so-fieldset:disabled .form-control,
.so-wrap fieldset.so-fieldset:disabled .form-select,
.so-wrap fieldset.so-fieldset:disabled textarea {
    background-color: #F7FAF6;
    color: #2F4A3C;
    -webkit-text-fill-color: #2F4A3C;
    opacity: 1;
    cursor: default;
}

.so-wrap fieldset.so-fieldset:disabled .payment-option,
.so-wrap fieldset.so-fieldset:disabled .customer-toggle .toggle-btn {
    cursor: default;
}

.so-wrap fieldset.so-fieldset:disabled .payment-option:hover {
    transform: none;
    background: #fff;
}

/* Payment / Order-type visibility - pure CSS so it works on Add, Edit and View Details
   even if a script on the page fails. */
#frm_create:has(input[name="c_mode_of_payment"][value="Cash on Delivery"]:checked) #paymet-proofs,
#frm_create:has(input[name="c_mode_of_payment"][value="Paid to Franchise"]:checked) #paymet-proofs,
#frm_create:has(input[name="c_mode_of_payment"]:not(:checked)):not(:has(input[name="c_mode_of_payment"]:checked)) #paymet-proofs,
#frm_create:has(input[name="order_type"][value="company"]:checked) #franchise-location-details {
    display: none !important;
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
        content: "Product "counter(prow);
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
    .so-wrap #productTable tbody td:nth-child(1) {
        grid-column: 1 / span 3;
        grid-row: 2;
    }

    .so-wrap #productTable tbody td:nth-child(2) {
        grid-column: 4 / span 3;
        grid-row: 2;
    }

    .so-wrap #productTable tbody td:nth-child(3) {
        grid-column: 7 / span 3;
        grid-row: 2;
    }

    .so-wrap #productTable tbody td:nth-child(4) {
        grid-column: 10 / span 3;
        grid-row: 2;
    }

    /* Row B – HSN, price, quantity, discount */
    .so-wrap #productTable tbody td:nth-child(5) {
        grid-column: 1 / span 3;
        grid-row: 3;
    }

    .so-wrap #productTable tbody td:nth-child(6) {
        grid-column: 4 / span 3;
        grid-row: 3;
    }

    .so-wrap #productTable tbody td:nth-child(7) {
        grid-column: 7 / span 3;
        grid-row: 3;
    }

    .so-wrap #productTable tbody td:nth-child(8) {
        grid-column: 10 / span 3;
        grid-row: 3;
    }

    /* Row C – calculated amounts */
    .so-wrap #productTable tbody td:nth-child(9) {
        grid-column: 1 / span 3;
        grid-row: 4;
    }

    .so-wrap #productTable tbody td:nth-child(10) {
        grid-column: 4 / span 3;
        grid-row: 4;
    }

    .so-wrap #productTable tbody td:nth-child(11) {
        grid-column: 7 / span 3;
        grid-row: 4;
    }

    .so-wrap #productTable tbody td:nth-child(12) {
        grid-column: 10 / span 3;
        grid-row: 4;
    }

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
@endpush

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

<div class="so-wrap">

    {{-- ===================== HERO ====================== --}}
    <div class="so-hero">
        <i class="fa fa-cart-plus fl-wave"></i>
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <span class="so-eyebrow">SPC Portal · Sales</span>
                <h2 class="so-hero-title">{{isset($sale->n_sl_no) ? 'Edit Sales Order' : 'New Sales Order'}} 🧾</h2>
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

            @if ($errors->any())
            <div class="so-alert err">
                <i class="ti ti-alert-circle"></i>
                <div>
                    <strong>Please check the following:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="so-alert err">
                <i class="ti ti-alert-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
            @endif

            @if(session('success'))
            <div class="so-alert ok">
                <i class="ti ti-circle-check"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            @php
            $isTelecallerRoute = request()->routeIs('admin.telecallers.*');
            $isEditing = isset($sale) && $sale->n_sl_no && (!isset($viewmode) || $viewmode != 'on');
            $formAction = $isEditing
            ? route($isTelecallerRoute ? 'admin.telecallers.update' : 'admin.salesorders.update')
            : route($isTelecallerRoute ? 'admin.telecallers.store' : 'admin.salesorders.store');
            @endphp
            <form method="POST" id="frm_create" action="{{ $formAction }}" enctype="multipart/form-data">
                @csrf
                @if($isEditing)
                @method('PUT')
                @endif

                <input type="hidden" name="id" class="form-control" value="{{isset($sale) ? $sale->n_sl_no : ''}}">

                {{-- View Details: the browser itself disables every field inside this fieldset (no JS needed).
                     Add / Edit: not disabled, nothing changes. Action buttons are outside it on purpose. --}}
                <fieldset class="so-fieldset" {{ (isset($viewmode) && $viewmode == 'on') ? 'disabled' : '' }}>

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
                                    value="{{ old('d_date', isset($sale) && $sale->d_date ? $sale->d_date->format('Y-m-d') : date('Y-m-d')) }}"
                                    {{isset($viewmode) && $viewmode=='on' ? 'readonly' : '' }}>
                                <div class="text-danger mt-1 fs-2">@error('d_date'){{ $message }}@enderror</div>
                            </div>

                            @php
                            // ---- Booklet rules by role -------------------------------------------------
                            // FCA : own sales, booklet serial + proof (editable)
                            // FCO : own sales, booklet serial + proof (editable) - advisor = the FCO
                            // TC / Office Admin : no booklet; Office Admin / FCO can still SEE the
                            // advisor, booklet serial and proof of an FCA sale (read-only).
                            $__me = (int) auth()->user()->n_employee_id;
                            $__isFca = !empty($isFarmCareAdvisor);
                            $__isFco = !empty($isFarmCareOfficer);
                            $__isOA = !empty($isOfficeAdmin);
                            $__isTc = !empty($isTelecaller);
                            $__fcoOwns = $__isFco && (!isset($sale) || (int) $sale->created_by === $__me);
                            $__bookletUser = $__isFca || $__fcoOwns;
                            $__legacyRow2 = !$__isTc && !$__isFco && !$__isOA; // admins & other roles (unchanged)
                            $__hasBookletData = isset($sale) && ($sale->booklet_image || $sale->farm_care_advisor_id);
                            $__autoNo = isset($sale) && preg_match('/^(TL|FS|OA|FCO)-\d+$/', (string)
                            $sale->c_order_no);
                            $__showRow2 = $__bookletUser || $__legacyRow2 || (!$__isTc && $__hasBookletData);
                            $__row2Editable = $__bookletUser || $__legacyRow2;
                            $__showSerial = $__bookletUser || (!$__isTc && $__hasBookletData && !$__autoNo &&
                            !$__legacyRow2);
                            $__serialEditable = $__bookletUser;
                            @endphp

                            @if($__showSerial)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Booklet Serial No @if($__serialEditable)*@endif</label>
                                <div class="position-relative">
                                    <input type="text" name="c_order_no" placeholder="BK-2026-0417"
                                        class="form-control order-number fw-bold text-success {{ $__serialEditable ? 'mandatory' : '' }}"
                                        data-message="Please Enter Booklet Serial No"
                                        value="{{ old('c_order_no', isset($sale->c_order_no) ? $sale->c_order_no : '') }}"
                                        {{ (isset($viewmode) && $viewmode=='on') || !$__serialEditable ? 'readonly' : '' }}>
                                    <div class="text-danger mt-1 fs-2"></div>
                                </div>
                                @error('c_order_no')
                                <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>


                        <!-- Row 2: Farm Care Advisor & Booklet Proof -->
                        @if($__showRow2)
                        <div class="row g-3">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Farm Care Advisor *</label>
                                @if($isFarmCareAdvisor || $__fcoOwns)
                                <input type="hidden" name="farm_care_advisor_id" class="form-control advisor-highlight"
                                    value="{{ auth()->user()->n_employee_id }}" readonly>
                                <input type="text" class="form-control advisor-highlight"
                                    value="{{ auth()->user()->c_name }}" readonly>
                                @elseif(!$__row2Editable)
                                {{-- Office Admin / FCO viewing someone else's sale: read-only, never posted --}}
                                <input type="text" class="form-control advisor-highlight"
                                    value="{{ optional($sale->employee)->c_employee_name }}" readonly>
                                @else
                                <select name="farm_care_advisor_id" class="form-control"
                                    data-message="Please Enter Farm Care Advisor">
                                    <option value="">Select Farm Care Adviser</option>
                                    @if(isset($employees))
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->n_employee_id }}"
                                        {{ old('farm_care_advisor_id', $sale->farm_care_advisor_id ?? '') == $employee->n_employee_id ? 'selected' : '' }}>
                                        {{ $employee->c_employee_name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="text-danger mt-1 fs-2">@error('farm_care_advisor_id'){{ $message }}@enderror
                                </div>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Sales Order Booklet Proof
                                    @if($__row2Editable && (!isset($sale) || !$sale->booklet_image))
                                    <span class="text-danger">*</span>
                                    @endif
                                </label>

                                @if($__row2Editable)
                                <input type="file" name="booklet_image" id="booklet_image" class="form-control"
                                    accept="image/*" data-message="Please Enter Booklet Proof">
                                <div class="text-danger mt-1 fs-2">@error('booklet_image'){{ $message }}@enderror</div>
                                @endif

                                {{-- Booklet proof is view-only: it can be replaced by uploading a new file but never removed --}}
                                <div class="mt-3" id="booklet_image_preview_container">
                                    @php
                                    $__bookletUrl = isset($sale) && $sale->booklet_image
                                    ? route('admin.salesorders.proof', ['type' => 'booklet_images', 'filename' =>
                                    $sale->booklet_image])
                                    : '';
                                    @endphp
                                    <a id="booklet_image_link" href="{{ $__bookletUrl ?: '#' }}" target="_blank"
                                        rel="noopener" title="Click to open the full booklet image"
                                        style="{{ $__bookletUrl ? '' : 'display:none;' }}">
                                        <img id="booklet_image_preview" src="{{ $__bookletUrl }}" alt="Booklet Proof"
                                            class="img-thumbnail"
                                            style="max-width:100%; width:220px; max-height:260px; object-fit:contain; background:#fff; cursor:zoom-in;">
                                    </a>
                                    <div id="booklet_image_view_text" class="mt-1"
                                        style="{{ $__bookletUrl ? '' : 'display:none;' }}">
                                        <a href="{{ $__bookletUrl ?: '#' }}" id="booklet_image_view_link"
                                            target="_blank" rel="noopener" class="small fw-semibold text-success">
                                            <i class="ti ti-external-link"></i> View full booklet
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @endif
                    </div>

                    <!-- Section 2: Product Details (Hierarchical Category -> Subcategory -> Product -> Attributes) -->
                    <div class="form-section so-section mb-4">
                        <div class="section-title d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="t-ic"><i class="ti ti-shopping-cart"></i></span>
                                Product Details *
                            </div>

                            @if(!isset($viewmode) || $viewmode=='off')
                            <button type="button" class="btn buttonSpc btn-sm" id="addRow">
                                <i class="ti ti-plus"></i>
                                Add Product
                            </button>
                            @endif
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
                                    @php
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
                                    ? \App\Models\CategoryMaster::whereIn('n_category_id',
                                    $catIds)->pluck('c_category_name', 'n_category_id')
                                    : collect();
                                    $prodIds = $oldRows->pluck('product_id')->filter()->unique()->values();
                                    $prodNames = $prodIds->isNotEmpty()
                                    ? \App\Models\ProductMaster::whereIn('n_product_id',
                                    $prodIds)->pluck('c_product_name', 'n_product_id')
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
                                    @endphp

                                    @foreach($productRows as $key => $row)
                                    <tr class="existing-product-row">
                                        <!-- Category -->
                                        <td>
                                            <input type="hidden" name="products[{{ $key }}][n_category_id]"
                                                value="{{ $row['n_category_id'] }}">
                                            <input type="text" class="form-control" value="{{ $row['category_name'] }}"
                                                readonly>
                                        </td>

                                        <!-- Sub Category -->
                                        <td>
                                            <input type="hidden" name="products[{{ $key }}][n_sub_category_id]"
                                                value="{{ $row['n_sub_category_id'] }}">
                                            <input type="text" class="form-control"
                                                value="{{ $row['sub_category_name'] }}" readonly>
                                        </td>

                                        <!-- Product -->
                                        <td>
                                            <input type="hidden" name="products[{{ $key }}][product_id]"
                                                class="product-select" value="{{ $row['product_id'] }}">
                                            <input type="text" class="form-control" value="{{ $row['product_name'] }}"
                                                readonly>
                                        </td>

                                        <!-- Attribute / Pack Size -->
                                        <td>
                                            <input type="hidden" name="products[{{ $key }}][c_unit]"
                                                value="{{ $row['c_unit'] }}">
                                            <input type="text" class="form-control" value="{{ $row['c_unit'] }}"
                                                readonly>
                                        </td>

                                        <!-- HSN Code -->
                                        <td>
                                            <input type="text" name="products[{{ $key }}][c_hsn_code]"
                                                class="form-control c_hsn_code" value="{{ $row['c_hsn_code'] }}"
                                                readonly>
                                        </td>

                                        <!-- Price -->
                                        <td>
                                            <input type="text" name="products[{{ $key }}][product_price]"
                                                class="form-control price" value="{{ $row['product_price'] }}" readonly>
                                        </td>

                                        <!-- Quantity -->
                                        <td>
                                            <input type="number" name="products[{{ $key }}][qty]"
                                                class="form-control qty" value="{{ $row['qty'] }}" min="1">
                                        </td>

                                        <!-- Discount -->
                                        <td>
                                            <input type="number" name="products[{{ $key }}][discount]"
                                                class="form-control discount" value="{{ $row['discount'] }}" step="0.01"
                                                min="0">
                                        </td>

                                        <!-- GST % -->
                                        <td>
                                            <input type="number" name="products[{{ $key }}][n_gst_percentage]"
                                                class="form-control gst_percentage"
                                                value="{{ $row['n_gst_percentage'] }}" step="0.01" readonly>
                                        </td>

                                        <!-- GST Amount -->
                                        <td>
                                            <input type="text" name="products[{{ $key }}][gst_amount]"
                                                class="form-control gst_amount" value="{{ $row['gst_amount'] }}"
                                                readonly>
                                        </td>

                                        <!-- Taxable / Discounted Price -->
                                        <td>
                                            <input type="text" name="products[{{ $key }}][discounted_price]"
                                                class="form-control discounted_price"
                                                value="{{ $row['discounted_price'] }}" readonly>
                                        </td>

                                        <!-- Total (MRP) -->
                                        <td>
                                            <input type="text" name="products[{{ $key }}][product_total]"
                                                class="form-control total" value="{{ $row['product_total'] }}" readonly>
                                        </td>

                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @error('products')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror

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
                                                value="{{ old('n_total_sales_amount', $sale->n_total_sales_amount ?? '0.00') }}"
                                                readonly>
                                        </div>

                                        <div class="summary-line">
                                            <span class="summary-label">Total GST</span>
                                            <input type="number" name="n_total_gst"
                                                class="form-control summary-input text-end" id="summaryGstAmount"
                                                value="{{ old('n_total_gst', $sale->n_total_gst ?? '0.00') }}"
                                                step="0.01" min="0" readonly>
                                        </div>

                                        <div class="summary-line">
                                            <span class="summary-label">Total Discount</span>
                                            <input type="text" name="n_product_discount_total"
                                                class="form-control summary-input text-end" id="summaryTotalDiscount"
                                                value="{{ old('n_product_discount_total', $sale->n_product_discount_total ?? '0.00') }}"
                                                readonly>
                                        </div>

                                        <div class="summary-line highlight-green">
                                            <span class="summary-label fw-bold">Net Sales Amount</span>
                                            <input type="text" name="n_net_sales_amount"
                                                class="form-control summary-input text-end fw-bold text-success"
                                                id="summaryNetSales"
                                                value="{{ old('n_net_sales_amount', $sale->n_net_sales_amount ?? '0.00') }}"
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
                            {{ old('c_customer_type', $sale->c_customer_type ?? 'new') != 'existing' ? 'checked' : '' }}>
                        <label class="toggle-btn new" for="newCustomer"><i class="ti ti-user-plus"></i> New
                            Customer</label>

                        <input type="radio" class="btn-check" name="c_customer_type" id="existingCustomer"
                            value="existing"
                            {{ old('c_customer_type', $sale->c_customer_type ?? 'new') == 'existing' ? 'checked' : '' }}>
                        <label class="toggle-btn existing" for="existingCustomer"><i class="ti ti-user-search"></i>
                            Existing
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
                                        value="{{ old('n_mobile', isset($sale) ? $sale->customer?->n_mobile : '') }}"
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
                            value="{{ old('n_customer_id', isset($sale) ? $sale->customer?->n_customer_id : '') }}">
                        @error('n_customer_id')
                        <div class="text-danger mt-1 mb-2">{{ $message }}</div>
                        @enderror

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Customer Code</label>
                                <input type="text" name="c_customer_code" id="c_customer_code"
                                    class="form-control customer-code"
                                    value="{{ $customerCode ?? (isset($sale) ? $sale->customer?->c_customer_code : '') }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" name="c_customer_name" id="c_customer_name"
                                    value="{{ old('c_customer_name', isset($sale) ? $sale->customer?->c_customer_name : '') }}"
                                    class="form-control c_customer_name mandatory" placeholder="Customer Name">
                                @error('c_customer_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mobile Number *</label>
                                <input type="text" maxlength="10" name="n_mobile" id="n_mobile"
                                    value="{{ old('n_mobile', isset($sale) ? $sale->customer?->n_mobile : '') }}"
                                    class="form-control mandatory" placeholder="10 Digit Mobile Number">
                                @error('n_mobile')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">WhatsApp Number *</label>
                                <input type="text" maxlength="10" name="n_whatsapp" id="n_whatsapp"
                                    value="{{ old('n_whatsapp', isset($sale) ? $sale->customer?->n_whatsapp : '') }}"
                                    class="form-control" placeholder="WhatsApp Number">
                                @error('n_whatsapp')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Email *</label>
                                <input type="email" name="c_email" id="c_email"
                                    value="{{ old('c_email', isset($sale) ? $sale->customer?->c_email : '') }}"
                                    class="form-control" placeholder="example@domain.com">
                                @error('c_email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> <!-- Address Details -->
                        <div class="form-section-header">
                            <i class="ti ti-map-pin"></i> Address Details
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="c_address" class="form-label">Address *</label>
                                <textarea id="c_address" name="c_address" rows="3" class="form-control"
                                    placeholder="Enter Customer Address">{{ old('c_address', isset($sale) ? $sale->customer?->c_address : '') }}</textarea>
                                @error('c_address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="c_post_office" class="form-label">
                                    Post Office *
                                </label>
                                <input type="text" id="c_post_office" name="c_post_office"
                                    value="{{ old('c_post_office',isset($sale) ? $sale->customer?->c_post_office : '')}}"
                                    class="form-control" placeholder="Post Office">

                                @error('c_post_office')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-4">
                                <label for="n_state_id" class="form-label">State *</label>
                                <select name="customer_state_id" id="n_state_id" class="form-select">
                                    <option value="">Select State</option>
                                    @if(isset($states))
                                    @foreach($states as $state)
                                    <option value="{{ $state->n_state_id }}" data-id="{{ $state->n_state_id }}"
                                        {{ old('customer_state_id', isset($sale) ? $sale->customer?->n_state_id : '') == $state->n_state_id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('customer_state_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="n_district_id" class="form-label">District *</label>
                                <select name="customer_district_id" id="n_district_id" class="form-select">
                                    <option value="">Select District</option>
                                    @if(isset($districts))
                                    @foreach($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('customer_district_id', isset($sale) ? $sale->customer?->n_district_id : '') == $district->id ? 'selected' : '' }}>
                                        {{ $district->district_name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('customer_district_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="c_thaluk" class="form-label">
                                    Thaluk *
                                </label>
                                <input type="text" id="c_thaluk" name="c_thaluk"
                                    value="{{ old('c_thaluk',isset($sale) ? $sale->customer?->c_thaluk : '') }}"
                                    class="form-control" placeholder="Thaluk">

                                @error('c_thaluk')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-4">
                                <label for="c_pincode" class="form-label">Pincode *</label>
                                <input type="text" id="c_pincode" name="c_pincode" maxlength="6"
                                    value="{{ old('c_pincode', isset($sale) ? $sale->customer?->c_pincode : '') }}"
                                    class="form-control" placeholder="Pincode">
                                @error('c_pincode')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Order location: filled from the address above, same approach as Franchise > Add --}}
                            <div class="col-md-12">
                                <div class="form-label mb-2"><i class="ti ti-current-location"></i> Location (from
                                    address)</div>
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="so_latitude" class="form-label">Latitude</label>
                                        <input type="text" id="so_latitude" name="latitude"
                                            value="{{ old('latitude', isset($sale) ? $sale->latitude : '') }}"
                                            class="form-control" maxlength="20" placeholder="Latitude"
                                            inputmode="decimal">
                                        @error('latitude')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="so_longitude" class="form-label">Longitude</label>
                                        <input type="text" id="so_longitude" name="longitude"
                                            value="{{ old('longitude', isset($sale) ? $sale->longitude : '') }}"
                                            class="form-control" maxlength="20" placeholder="Longitude"
                                            inputmode="decimal">
                                        @error('longitude')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
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
                                    Fill in the address, state and district, then click "Get Location from Address". You
                                    can drag the pin or click the map to fine-tune it.
                                </div>
                                <div id="soLocationMap"
                                    style="display:none;height:340px;border-radius:12px;margin-top:12px;border:1px solid #dfe5e1;">
                                </div>
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
                                        {{ old('c_status', isset($sale) ? ($sale->customer?->c_status ?? 'Y') : 'Y') == 'Y' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="N"
                                        {{ old('c_status', isset($sale) ? $sale->customer?->c_status : '') == 'N' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                @error('c_status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
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
                                        {{ old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Cash on Delivery" ? 'checked' : '' }}>
                                    <label for="cod" class="mb-0">
                                        <i class="ti ti-truck"></i> Cash on Delivery
                                    </label>
                                </div>

                                @if(isset($isTelecaller) && $isTelecaller==false)
                                <div class="payment-option">
                                    <input class="form-check-input mode_of_payment" type="radio"
                                        name="c_mode_of_payment" id="upi" value="UPI"
                                        {{ old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "UPI" ? 'checked' : '' }}>
                                    <label for="upi" class="mb-0">
                                        <i class="ti ti-brand-google-pay"></i> UPI
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input class="form-check-input mode_of_payment" type="radio"
                                        name="c_mode_of_payment" id="bkd" value="Bank Deposit"
                                        {{ old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Bank Deposit" ? 'checked' : '' }}>
                                    <label for="bkd" class="mb-0">
                                        <i class="ti ti-building-bank"></i> Bank Deposit
                                    </label>
                                </div>
                                @endif

                                <div class="payment-option">
                                    <input class="form-check-input mode_of_payment" type="radio"
                                        name="c_mode_of_payment" id="pf" value="Paid to Franchise"
                                        {{ old('c_mode_of_payment', $sale->c_mode_of_payment ?? '') == "Paid to Franchise" ? 'checked' : '' }}>
                                    <label for="pf" class="mb-0">
                                        <i class="ti ti-cash"></i> Paid to Franchise
                                    </label>
                                </div>
                                <div class="text-danger mt-1 fs-2" id="payment_mode_error">
                                    @error('c_mode_of_payment'){{ $message }}@enderror</div>
                            </div>
                        </div>

                        <div class="row g-4 mt-1" id="ps">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Payment Status</label>
                                <select name="payment_status" id="payment_status"
                                    data-message="Please Select Payment Status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="pending"
                                        {{ old('payment_status', $sale->payment_status ?? '') == "pending" ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="paid"
                                        {{ old('payment_status', $sale->payment_status ?? '') == "paid" ? 'selected' : '' }}>
                                        Paid</option>
                                </select>
                                <div class="text-danger mt-1 fs-2">@error('payment_status'){{ $message }}@enderror</div>
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
                                        value="{{ old('n_amount_to_pay', $sale->n_amount_to_pay ?? '') }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Transaction ID *</label>
                                <input type="text" id="c_transaction_id" name="c_transaction_id"
                                    value="{{ old('c_transaction_id', $sale->c_transaction_id ?? '') }}"
                                    data-message="Please Enter Transaction id" class="form-control"
                                    placeholder="Enter Transaction / UTR / Reference No">
                                <div class="text-danger mt-1 fs-2">@error('c_transaction_id'){{ $message }}@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Transaction Proof
                                    @if(!isset($sale) || !$sale->payment_image)
                                    <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <input type="file" id="payment_image" name="payment_image"
                                    data-message="Please Enter Transaction Proof" class="form-control" accept="image/*">
                                <input type="hidden" name="remove_payment_image" id="remove_payment_image" value="0">
                                <div class="text-danger mt-1 fs-2">@error('payment_image'){{ $message }}@enderror</div>

                                <div class="mt-3" id="payment_preview_container">
                                    <img id="payment_image_preview"
                                        src="{{ isset($sale) && $sale->payment_image ? route('admin.salesorders.proof', ['type' => 'payment_images', 'filename' => $sale->payment_image]) : '' }}"
                                        alt="Transaction Proof Preview" class="img-thumbnail"
                                        style="{{ isset($sale) && $sale->payment_image ? '' : 'display:none;' }} width:50px; height:50px; object-fit:cover;">

                                    @if(isset($sale) && $sale->payment_image)
                                    <br>
                                    <button type="button" id="remove_payment_image_btn"
                                        class="btn btn-danger btn-sm mt-2">
                                        Remove Image
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Franchise / Company Details Section -->
                    <div class="form-box so-section mb-4" id="franchise-details">
                        @if((isset($isAdmin) && $isAdmin==true) || (isset($isOfficeAdmin) && $isOfficeAdmin==true))
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Order Type <span class="text-danger">*</span></label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input mandatory" type="radio" name="order_type"
                                            id="company" value="company"
                                            {{ old('order_type', $sale->order_type ?? '') == 'company' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="company">Company</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input mandatory" type="radio" name="order_type"
                                            id="franchise_type" value="franchise"
                                            {{ old('order_type', $sale->order_type ?? '') == 'franchise' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="franchise_type">Franchise</label>
                                    </div>
                                </div>
                                @error('order_type')
                                <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div id="franchise-location-details">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nearest Franchise <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select mandatory" id="franchise" name="nearest_franchise_id"
                                        data-message="Please Select Nearest Franchise"
                                        {{ isset($viewmode) && $viewmode == 'on' ? 'disabled' : '' }}>
                                        <option value="">Select Franchise</option>
                                        @if(isset($franchises))
                                        @foreach($franchises as $franchise)
                                        <option value="{{ $franchise->n_store_id }}"
                                            {{ old('nearest_franchise_id', $sale->nearest_franchise_id ?? '') == $franchise->n_store_id ? 'selected' : '' }}>
                                            {{ $franchise->c_store_name }} ({{ $franchise->c_store_code }})
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('nearest_franchise_id')
                                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                                    @enderror
                                    @if(!(isset($viewmode) && $viewmode == 'on'))
                                    <div id="soFranchiseHint" class="mt-1 small text-muted"></div>
                                    <div id="soFranchiseRank" class="mt-2" style="display:none;"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <!-- Action Buttons -->
                <div class="mt-4 d-flex gap-2 flex-wrap">
                    @if(isset($viewmode) && $viewmode=="on")
                    @can('sales-orders.approval')
                    <button type="button" style="width:150px;position:relative;" class="btn mt-1 buttonSpc"
                        data-bs-toggle="modal" data-bs-target="#approveModal" data-bs-dismiss="modal"
                        data-id="{{ Crypt::encryptString(isset($sale) && $sale->n_sl_no ? $sale->n_sl_no : '') }}">
                        Approve
                    </button>
                    @endcan

                    @if(isset($sale) && $sale->n_sl_no)
                    <a href="{{ route('admin.invoice-orders.preview', $sale->n_sl_no) }}" class="btn mt-1 buttonSpc">
                        Order Summary Preview
                    </a>
                    @if(!empty($isOfficeAdmin) && strtolower($sale->approval?->status ?? '') === 'approved')
                    <a href="{{ route('admin.invoice.download', $sale->n_sl_no) }}">
                        <button type="button" class="btn buttonSpc" style="height:61px;margin-top: 4px;">Generate
                            Invoice</button>
                    </a>
                    @endif
                    @endif
                    @else
                    <button type="button" class="btn buttonSpc" style="width:150px;position:relative;"
                        id="btn_create">{{isset($sale->n_sl_no) ? 'Update' : 'Create'}}</button>
                    <a href="{{ route('admin.salesorders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Approval Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="approveForm" action="{{ route('admin.salesorders.approval.save') }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header" style="background: linear-gradient(135deg, #5E8D3D, #1F5C2E);">
                        <h5 class="modal-title text-white" id="approveModalLabel">Approval</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="sales_id" id="sales_id"
                            value="{{ Crypt::encryptString(isset($sale) && $sale->n_sl_no ? $sale->n_sl_no : '') }}">

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
    @php
    $soFranchisesJson = json_encode(collect($franchises ?? [])->map(function ($f) {
        return [
            'id' => $f->n_store_id,
            'name' => trim(($f->c_store_name ?? '') . ($f->c_store_code ? ' (' . $f->c_store_code . ')' : '')),
            'lat' => $f->latitude !== null ? (float) $f->latitude : null,
            'lng' => $f->longitude !== null ? (float) $f->longitude : null,
            'state' => $f->n_state_id,
            'district' => $f->n_district_id,
            'panchayath' => $f->n_panchayath_id,
        ];
    })->values());
    $soIsView = (isset($viewmode) && $viewmode == 'on') ? 'true' : 'false';
    $soHasPayImg = (isset($sale) && $sale->payment_image) ? 'true' : 'false';
    $soCustomerCode = $customerCode ?? (isset($sale) ? $sale->customer?->c_customer_code : '');
@endphp
<div id="soJsConfig" hidden
    data-url-subcategories="{{ route('admin.get.product.subcategories', ['categoryId' => ':categoryId']) }}"
    data-url-products="{{ route('admin.get.products', ['subCategoryId' => ':subCategoryId']) }}"
    data-url-attr-from-product="{{ route('admin.get.attributesFromProductname', ['productId' => ':productId']) }}"
    data-url-pack-size="{{ route('admin.get.product.packSize', ['productName' => ':productName']) }}"
    data-url-attributes="{{ route('admin.get.product.attributes', ['productName' => ':productName', 'packSize' => ':packSize']) }}"
    data-url-district="{{ route('admin.filterDistrict') }}"
    data-url-panchayath="{{ route('admin.filterPanchayath') }}"
    data-url-nearest="{{ route('admin.franchise.nearest') }}"
    data-url-existing-customer="{{ route('admin.leads.existingCustomer') }}"
    data-csrf="{{ csrf_token() }}"
    data-viewmode="{{ $viewmode ?? 'off' }}"
    data-customer-code="{{ $soCustomerCode }}"
    data-has-payment-image="{{ $soHasPayImg }}"
    data-is-view="{{ $soIsView }}"
    data-nearest-max-km="{{ (float) config('spc.nearest_franchise_max_km', 50) }}"
    data-franchises="{{ $soFranchisesJson }}"></div>
<select id="soCategoryOptions" hidden>
    @foreach($productCategories as $category)
        <option value="{{ $category->n_category_id }}" data-categoryCode="{{ $category->c_category_code }}">{{ $category->c_category_name }}</option>
    @endforeach
</select>
@endsection

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    
    <script src="{{ asset('js/sales-order-create.js') }}?v={{ @filemtime(public_path('js/sales-order-create.js')) }}"></script>
    @endpush