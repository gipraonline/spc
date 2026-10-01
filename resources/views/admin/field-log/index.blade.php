@extends('layouts.app')

@section('topbarTitle', 'Field Log')

@section('content')

<style>
/* =====================================================================
   FIELD LOG — SPC "Evergreen" edition
   Palette: olive #5E8D3D · forest #1F5C2E · leaf #7CA243
            accents #A8CB6A/#C2DC96 · mint wash #CBFFCD (45deg)
   ===================================================================== */
.spc-fl {
    --fl-olive: #5E8D3D;
    --fl-forest: #1F5C2E;
    --fl-leaf: #7CA243;
    --fl-acc: #A8CB6A;
    --fl-acc2: #C2DC96;
    --fl-ink: #123A28;
    --fl-mut: #5F7A6C;
    --fl-line: rgba(18, 58, 40, .1);
    --fl-grad: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    --fl-wash: linear-gradient(45deg, #CBFFCD, transparent 60%);
    font-family: var(--font-body, 'Outfit', sans-serif);
}

.spc-fl h1,
.spc-fl h2,
.spc-fl h3,
.spc-fl h5,
.spc-fl h6,
.spc-fl .fl-hero-title,
.spc-fl .fl-tile-num,
.spc-fl .fl-ring-val,
.spc-fl .fl-task-name,
.spc-fl .modal-title,
.spc-fl .fl-step-num {
    font-family: var(--font-head, 'Kanit', sans-serif);
}

/* ---------- Hero ---------- */
.fl-hero {
    position: relative;
    overflow: hidden;
    border-radius: 22px;
    background:
        radial-gradient(340px 240px at 88% -12%, rgba(255, 255, 255, .16), transparent 60%),
        radial-gradient(420px 320px at -14% 116%, rgba(203, 255, 205, .34), transparent 55%),
        var(--fl-grad);
    color: #fff;
    padding: 26px 28px;
    margin-bottom: 18px;
    box-shadow: 0 18px 40px -18px rgba(31, 92, 46, .55);
}

.fl-hero::before {
    content: "";
    position: absolute;
    right: -70px;
    top: -70px;
    width: 230px;
    height: 230px;
    border: 2px dashed rgba(255, 255, 255, .22);
    border-radius: 50%;
}

.fl-hero::after {
    content: "";
    position: absolute;
    right: -34px;
    top: -34px;
    width: 150px;
    height: 150px;
    border: 2px solid rgba(255, 255, 255, .16);
    border-radius: 50%;
}

.fl-hero .fl-wave {
    position: absolute;
    right: 26px;
    bottom: 18px;
    font-size: 64px;
    color: rgba(203, 255, 205, .28);
    transform: rotate(-8deg);
    pointer-events: none;
}

.fl-eyebrow {
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

.fl-eyebrow::before {
    content: "";
    width: 16px;
    height: 2px;
    border-radius: 2px;
    background: #D8F5C8;
}

.fl-hero-title {
    font-size: 26px;
    font-weight: 600;
    color: #fff;
    margin: 10px 0 4px;
    line-height: 1.15;
}

.fl-hero-sub {
    color: #D9EEDC;
    font-size: 13.5px;
    margin: 0;
}

.fl-clockchip {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin-top: 14px;
    background: rgba(255, 255, 255, .13);
    border: 1px solid rgba(255, 255, 255, .22);
    border-radius: 14px;
    padding: 9px 15px;
    backdrop-filter: blur(6px);
}

.fl-clockchip i {
    font-size: 17px;
    color: #D8F5C8;
}

.fl-clockchip b {
    font-family: var(--font-head, 'Kanit', sans-serif);
    font-size: 15px;
    font-weight: 600;
    letter-spacing: .02em;
}

.fl-clockchip span {
    font-size: 11.5px;
    color: #D9EEDC;
}

/* Status pill with pulse dot */
.fl-status {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: #fff;
    color: var(--fl-forest);
    font-weight: 700;
    font-size: 12.5px;
    letter-spacing: .02em;
    border-radius: 999px;
    padding: 9px 16px;
    box-shadow: 0 10px 22px -10px rgba(0, 0, 0, .35);
    white-space: nowrap;
}

.fl-status .dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}

.fl-status .dot.live {
    background: var(--fl-olive);
    box-shadow: 0 0 0 0 rgba(94, 141, 61, .5);
    animation: flPulse 1.8s infinite;
}

.fl-status .dot.warn {
    background: #E8A13D;
}

.fl-status .dot.off {
    background: #9AA89F;
}

@keyframes flPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(94, 141, 61, .45);
    }

    70% {
        box-shadow: 0 0 0 9px rgba(94, 141, 61, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(94, 141, 61, 0);
    }
}

@media (prefers-reduced-motion:reduce) {
    .fl-status .dot.live {
        animation: none;
    }
}

/* ---------- Alerts ---------- */
.fl-alert {
    display: flex;
    gap: 11px;
    align-items: flex-start;
    border-radius: 14px;
    padding: 13px 16px;
    font-size: 13.5px;
    margin-bottom: 16px;
    border: 1px solid transparent;
}

.fl-alert i {
    font-size: 19px;
    margin-top: 1px;
}

.fl-alert.ok {
    background: #F1FBEE;
    border-color: #D6EDCB;
    color: #2C5B34;
}

.fl-alert.err {
    background: #FDF1F0;
    border-color: #F3D3CF;
    color: #8C3B32;
}

.fl-alert ul {
    margin: 6px 0 0;
    padding-left: 18px;
}

/* ---------- Cards ---------- */
.fl-card {
    background: #fff;
    border: 1px solid var(--fl-line);
    border-radius: 18px;
    box-shadow: 0 6px 20px -12px rgba(18, 58, 40, .18);
    overflow: hidden;
    margin-bottom: 18px;
}

.fl-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 20px;
    border-bottom: 1px solid var(--fl-line);
    background: linear-gradient(180deg, #FBFEF9, #F5FBF3);
}

.fl-card-head h5 {
    font-size: 15.5px;
    font-weight: 600;
    color: var(--fl-ink);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.fl-card-head .h-ic {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--fl-wash), #EAF6E6;
    color: var(--fl-olive);
    font-size: 16px;
    border: 1px solid #E2F1D9;
}

.fl-card-head small {
    display: block;
    color: var(--fl-mut);
    font-size: 12px;
    margin-top: 2px;
}

.fl-card-body {
    padding: 20px;
}

/* ---------- Form fields ---------- */
.fl-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--fl-mut);
    margin-bottom: 7px;
    display: flex;
    align-items: center;
    gap: 7px;
}

.fl-label i {
    font-size: 14px;
    color: var(--fl-leaf);
}

.spc-fl .form-control,
.spc-fl .form-select {
    border-radius: 12px;
    border: 1.5px solid #E3EDE3;
    min-height: 46px;
    font-size: 14px;
    font-family: var(--font-body, 'Outfit', sans-serif);
}

.spc-fl textarea.form-control {
    min-height: auto;
}

.spc-fl .form-control:focus,
.spc-fl .form-select:focus {
    border-color: var(--fl-leaf);
    box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .14);
}

.fl-readonly {
    background: linear-gradient(180deg, #F7FBF4, #EFF7EC) !important;
    color: var(--fl-forest) !important;
    font-weight: 600;
}

/* ---------- Step numbers on check-in card ---------- */
.fl-step {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 18px;
}

.fl-step:last-child {
    margin-bottom: 0;
}

.fl-step-num {
    width: 30px;
    height: 30px;
    border-radius: 10px;
    flex-shrink: 0;
    margin-top: 2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--fl-grad);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 6px 12px -6px rgba(31, 92, 46, .5);
}

/* ---------- Task input rows ---------- */
.fl-taskrow {
    display: flex;
    gap: 10px;
    align-items: center;
    background: #FAFDF8;
    border: 1.5px dashed #DCEBD5;
    border-radius: 14px;
    padding: 9px 10px;
    margin-bottom: 10px;
    transition: border-color .15s, background .15s;
}

.fl-taskrow:focus-within {
    border-color: var(--fl-leaf);
    background: #fff;
    box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .1);
}

.fl-taskrow .form-control {
    border: none;
    background: transparent;
    min-height: 40px;
    box-shadow: none !important;
}

.fl-taskrow .fl-tnum {
    width: 26px;
    height: 26px;
    border-radius: 9px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #EDF6E7;
    color: var(--fl-olive);
    font-size: 11.5px;
    font-weight: 700;
    font-family: var(--font-head, 'Kanit', sans-serif);
}

.fl-taskrow .fl-tdel {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    flex-shrink: 0;
    border: none;
    background: #FDEEEC;
    color: #C4574A;
    font-size: 17px;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
}

.fl-taskrow .fl-tdel:hover {
    background: #C4574A;
    color: #fff;
    transform: rotate(90deg);
}

/* ---------- Buttons ---------- */
.spc-fl .btn-fl {
    background: var(--fl-grad) !important;
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

.spc-fl .btn-fl:hover {
    transform: translateY(-1.5px);
    box-shadow: 0 14px 26px -10px rgba(31, 92, 46, .7) !important;
    filter: brightness(1.06);
}

.spc-fl .btn-fl.big {
    padding: 13px 30px !important;
    font-size: 14.5px !important;
    border-radius: 15px !important;
}

.spc-fl .btn-ghost {
    background: #fff !important;
    color: var(--fl-olive) !important;
    border: 1.5px solid #DCEDD2 !important;
    border-radius: 13px !important;
    padding: 10px 22px !important;
    font-weight: 600 !important;
    font-size: 13.5px !important;
    transition: all .16s !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.spc-fl .btn-ghost:hover {
    background: #F4FAF0 !important;
    border-color: var(--fl-leaf) !important;
}

.spc-fl .btn-ghost:disabled {
    opacity: .5;
    cursor: not-allowed;
}

/* ---------- Summary tiles ---------- */
.fl-tiles {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

.fl-tile {
    position: relative;
    overflow: hidden;
    background: #fff;
    border: 1px solid var(--fl-line);
    border-radius: 16px;
    padding: 15px 16px;
    transition: all .18s;
}

.fl-tile:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 26px -14px rgba(18, 58, 40, .28);
}

.fl-tile::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--tc, var(--fl-olive));
    border-radius: 0 4px 4px 0;
}

.fl-tile::after {
    content: "";
    position: absolute;
    right: -26px;
    top: -26px;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: var(--fl-wash);
    opacity: .8;
}

.fl-tile .t-ic {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--tc, var(--fl-olive)), var(--fl-forest));
    color: #fff;
    font-size: 16px;
    box-shadow: 0 8px 14px -8px var(--tc, var(--fl-olive));
}

.fl-tile .t-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--fl-mut);
    position: relative;
    z-index: 1;
}

.fl-tile .t-val {
    font-size: 16px;
    font-weight: 600;
    color: var(--fl-ink);
    margin-top: 3px;
    position: relative;
    z-index: 1;
    line-height: 1.3;
}

@media (max-width:767.98px) {
    .fl-tiles {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* ---------- Task summary chips ---------- */
.fl-chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.fl-chip {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #fff;
    border: 1px solid var(--fl-line);
    border-radius: 999px;
    padding: 6px 13px;
    font-size: 11.5px;
    font-weight: 700;
    color: var(--fl-ink);
}

.fl-chip .d {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--dc, var(--fl-olive));
}

.fl-chip .d.done {
    background: var(--fl-olive);
    box-shadow: 0 0 0 3px rgba(94, 141, 61, .15);
}

.fl-chip .d.prog {
    background: #3D8AC0;
    box-shadow: 0 0 0 3px rgba(61, 138, 192, .15);
}

.fl-chip .d.pend {
    background: #E8A13D;
    box-shadow: 0 0 0 3px rgba(232, 161, 61, .18);
}

/* ---------- Task table ---------- */
.spc-fl .fl-table {
    margin: 0;
}

.spc-fl .fl-table thead th {
    background: var(--fl-grad) !important;
    color: #fff !important;
    font-size: 10.5px;
    text-transform: uppercase;
    letter-spacing: .09em;
    font-weight: 700;
    padding: 13px 16px;
    white-space: nowrap;
    border: none;
}

.spc-fl .fl-table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    font-size: 13.5px;
    color: #41564B;
    border-color: #EEF4EA;
}

.spc-fl .fl-table tbody tr {
    transition: background .14s;
}

.spc-fl .fl-table tbody tr:hover {
    background: #F8FCF5;
}

.fl-tno {
    width: 30px;
    height: 30px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #EDF6E7;
    color: var(--fl-olive);
    font-size: 12px;
    font-weight: 700;
    font-family: var(--font-head, 'Kanit', sans-serif);
}

.fl-task-name {
    color: var(--fl-ink);
    font-weight: 600;
    font-size: 13.5px;
}

/* Status pills */
.fl-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border-radius: 999px;
    padding: 5.5px 12px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .02em;
}

.fl-pill .d {
    width: 6.5px;
    height: 6.5px;
    border-radius: 50%;
}

.fl-pill.done {
    background: #EAF6E6;
    color: #2C5B34;
    border: 1px solid #D6EDCB;
}

.fl-pill.done .d {
    background: var(--fl-olive);
}

.fl-pill.prog {
    background: #EAF4FB;
    color: #25628E;
    border: 1px solid #CFE6F4;
}

.fl-pill.prog .d {
    background: #3D8AC0;
}

.fl-pill.pend {
    background: #FDF4E4;
    color: #8A6116;
    border: 1px solid #F2E2C2;
}

.fl-pill.pend .d {
    background: #E8A13D;
}

.fl-pill.out {
    background: #F0F2F1;
    color: #5A6B60;
    border: 1px solid #E0E6E2;
}

.fl-pill.out .d {
    background: #9AA89F;
}

.spc-fl .editTaskBtn {
    border: 1.5px solid #DCEDD2;
    background: #fff;
    color: var(--fl-olive);
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
    padding: 6.5px 14px;
    transition: all .15s;
}

.spc-fl .editTaskBtn:hover {
    background: var(--fl-grad);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 8px 16px -8px rgba(31, 92, 46, .6);
}

.spc-fl .editTaskBtn:disabled {
    opacity: .45;
    cursor: not-allowed;
}

/* Remark hint chip */
.fl-remark-none {
    color: #9FB4A7;
    font-size: 12.5px;
}

/* ---------- Empty state ---------- */
.fl-empty {
    text-align: center;
    padding: 48px 20px !important;
}

.fl-empty .e-ic {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    margin: 0 auto 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    background: var(--fl-wash), #EFF8EA;
    color: var(--fl-olive);
    border: 1.5px dashed #D6EDCB;
}

.fl-empty b {
    font-family: var(--font-head, 'Kanit', sans-serif);
    font-size: 15px;
    color: var(--fl-ink);
    display: block;
}

.fl-empty small {
    color: var(--fl-mut);
    font-size: 12.5px;
}

/* ---------- Progress ---------- */
.fl-prog {
    display: flex;
    align-items: center;
    gap: 26px;
    flex-wrap: wrap;
}

.fl-ring {
    --p: 0;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    flex-shrink: 0;
    position: relative;
    background: conic-gradient(var(--fl-olive) calc(var(--p)*1%), #EAF3E6 0);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 0 0 1px var(--fl-line), 0 12px 24px -14px rgba(31, 92, 46, .5);
}

.fl-ring::before {
    content: "";
    position: absolute;
    inset: 11px;
    border-radius: 50%;
    background: #fff;
}

.fl-ring-val {
    position: relative;
    z-index: 1;
    text-align: center;
    line-height: 1;
}

.fl-ring-val b {
    display: block;
    font-size: 24px;
    font-weight: 600;
    color: var(--fl-forest);
}

.fl-ring-val span {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--fl-mut);
}

.fl-prog-info {
    flex: 1;
    min-width: 220px;
}

.fl-prog-info .bar {
    height: 14px;
    border-radius: 999px;
    background: #EDF4E9;
    overflow: hidden;
    margin: 10px 0 12px;
}

.fl-prog-info .bar i {
    display: block;
    height: 100%;
    border-radius: 999px;
    width: var(--p);
    background: linear-gradient(90deg, var(--fl-leaf), var(--fl-olive) 60%, var(--fl-forest));
    transition: width 1s cubic-bezier(.22, 1, .36, 1);
}

.fl-minichips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* ---------- Checkout states ---------- */
.fl-co-state {
    display: flex;
    gap: 13px;
    align-items: flex-start;
    border-radius: 15px;
    padding: 16px 18px;
    font-size: 13.5px;
    line-height: 1.65;
}

.fl-co-state .ic {
    width: 42px;
    height: 42px;
    border-radius: 13px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.fl-co-state.ok {
    background: #F1FBEE;
    border: 1px solid #D6EDCB;
    color: #2C5B34;
}

.fl-co-state.ok .ic {
    background: var(--fl-grad);
    color: #fff;
}

.fl-co-state.warn {
    background: #FDF7EA;
    border: 1px solid #F0E3C4;
    color: #7A5A14;
}

.fl-co-state.warn .ic {
    background: linear-gradient(135deg, #E8A13D, #C9822A);
    color: #fff;
}

.fl-co-state.off {
    background: #F2F5F3;
    border: 1px solid #E0E6E2;
    color: #52655B;
}

.fl-co-state.off .ic {
    background: linear-gradient(135deg, #7C8B81, #52655B);
    color: #fff;
}

.fl-co-state b {
    font-family: var(--font-head, 'Kanit', sans-serif);
}

.fl-co-cta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 16px;
    background: var(--fl-wash), #F6FBF3;
    border: 1px solid #E2F1D9;
    border-radius: 15px;
    padding: 15px 18px;
}

.fl-co-cta b {
    font-family: var(--font-head, 'Kanit', sans-serif);
    font-size: 14px;
    color: var(--fl-ink);
    display: block;
}

.fl-co-cta small {
    color: var(--fl-mut);
    font-size: 12px;
}

/* ---------- Modals ---------- */
.spc-fl .modal-content {
    border: 0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 24px 60px -20px rgba(12, 40, 24, .45);
}

.spc-fl .modal-header {
    padding: 18px 22px;
    border: none;
    color: #fff;
    position: relative;
    overflow: hidden;
    background: radial-gradient(300px 160px at 90% -20%, rgba(255, 255, 255, .18), transparent 60%), var(--fl-grad);
}

.spc-fl .modal-header .m-ic {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .16);
    border: 1px solid rgba(255, 255, 255, .25);
    color: #fff;
    font-size: 17px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.spc-fl .modal-title {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    line-height: 1.2;
}

.spc-fl .modal-header small {
    color: #D9EEDC;
    font-size: 11.5px;
}

.spc-fl .modal-header .m-close {
    background: rgba(255, 255, 255, .14);
    border: 1px solid rgba(255, 255, 255, .25);
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 10px;
    font-size: 16px;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background .15s;
}

.spc-fl .modal-header .m-close:hover {
    background: rgba(255, 255, 255, .28);
    color: #fff;
}

.spc-fl .modal-body {
    padding: 22px;
}

.spc-fl .modal-footer {
    padding: 14px 22px;
    border-top: 1px solid var(--fl-line);
    background: #FBFDF9;
    gap: 10px;
}

/* ---------- Checkout summary strip ---------- */
.fl-sumstrip {
    display: flex;
    border: 1px solid var(--fl-line);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 18px;
}

.fl-sumstrip>div {
    flex: 1;
    text-align: center;
    padding: 13px 8px;
    border-right: 1px solid var(--fl-line);
    background: #FBFDF9;
}

.fl-sumstrip>div:last-child {
    border-right: none;
}

.fl-sumstrip .sl {
    display: block;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--fl-mut);
}

.fl-sumstrip .sv {
    display: block;
    margin-top: 4px;
    font-family: var(--font-head, 'Kanit', sans-serif);
    font-size: 19px;
    font-weight: 600;
    color: var(--fl-forest);
}

.fl-sumstrip .sv.g {
    color: var(--fl-olive);
}

/* ---------- Mobile ---------- */
@media (max-width:767.98px) {
    .fl-hero {
        padding: 20px 18px;
        border-radius: 18px;
    }

    .fl-hero-title {
        font-size: 21px;
    }

    .fl-hero .fl-wave {
        font-size: 44px;
        bottom: 12px;
        right: 14px;
    }

    .fl-card-body {
        padding: 16px;
    }

    .fl-co-cta {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }

    .spc-fl .btn-fl,
    .spc-fl .btn-ghost {
        width: 100%;
        justify-content: center;
    }

    /* Table → stacked cards */
    .spc-fl .fl-table thead {
        display: none;
    }

    .spc-fl .fl-table tbody tr {
        display: block;
        margin: 0 12px 12px;
        border: 1px solid var(--fl-line);
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        padding: 6px 0;
    }

    .spc-fl .fl-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        border: none;
        padding: 9px 14px;
        text-align: right;
    }

    .spc-fl .fl-table tbody td::before {
        content: attr(data-label);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--fl-mut);
        text-align: left;
    }

    .spc-fl .fl-table tbody td:first-child {
        justify-content: flex-start;
    }

    .spc-fl .fl-table tbody td:first-child::before {
        content: "";
    }

    .fl-sumstrip {
        flex-direction: column;
    }

    .fl-sumstrip>div {
        border-right: none;
        border-bottom: 1px solid var(--fl-line);
    }

    .fl-sumstrip>div:last-child {
        border-bottom: none;
    }
}

/* Hide native check-in tiles' page bg clash */
.spc-fl .readonly-field {
    font-family: var(--font-head, 'Kanit', sans-serif);
}
</style>

<div class="spc-fl">

    {{-- ========================================================= --}}
    {{-- ===================== HERO HEADER ======================= --}}
    {{-- ========================================================= --}}

    <div class="fl-hero">

        <i class="fa fa-seedling fl-wave"></i>

        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

            <div>
                <span class="fl-eyebrow">SPC Portal · Field Work</span>
                <h2 class="fl-hero-title">
                    @if(!$fieldLog) Good
                    {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}! 🌿
                    @elseif($fieldLog->status == 'Checked Out') Day Complete ✓
                    @else On The Field 🌿
                    @endif
                </h2>
                <p class="fl-hero-sub">
                    @if(!$fieldLog)
                    Check in to start logging your daily field work and tasks.
                    @elseif($isCheckedOut)
                    Your field log for {{ $fieldLog->work_date->format('d M Y') }} is closed and saved.
                    @else
                    Manage your daily field work and tasks — wrap up before you check out.
                    @endif
                </p>

                <div class="fl-clockchip">
                    <i class="ti ti-clock"></i>
                    <b id="flClock">--:--:--</b>
                    <span id="flDate">{{ now()->format('D, d M Y') }}</span>
                </div>
            </div>

            <div class="text-end">
                <span class="fl-status">
                    <span
                        class="dot {{ !$fieldLog ? 'warn' : ($fieldLog->status == 'Checked Out' ? 'off' : 'live') }}"></span>
                    @if(!$fieldLog)
                    Not Checked In
                    @elseif($fieldLog->status == 'Checked Out')
                    Checked Out
                    @else
                    Working
                    @endif
                </span>
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ===================== MESSAGES ========================== --}}
    {{-- ========================================================= --}}

    @if(session('success'))

    <div class="fl-alert ok">

        <i class="ti ti-circle-check"></i>

        <div>

            <strong>Success!</strong>

            {{ session('success') }}

        </div>

    </div>

    @endif


    @if($errors->any())

    <div class="fl-alert err">

        <i class="ti ti-alert-circle"></i>

        <div>

            <strong>Please check the following:</strong>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ===================== CHECK IN ========================== --}}
    {{-- ========================================================= --}}

    @if(!$fieldLog)

    <div class="fl-card">

        <div class="fl-card-head">

            <h5>

                <span class="h-ic"><i class="ti ti-player-play"></i></span>

                <span>
                    Start Your Workday
                    <small>Three quick steps and you're on the clock.</small>
                </span>

            </h5>

        </div>


        <div class="fl-card-body">

            <form action="{{ route('admin.field-log.checkin') }}" method="POST">

                @csrf

                {{-- Step 1 — When --}}
                <div class="fl-step">

                    <span class="fl-step-num">1</span>

                    <div class="flex-grow-1">

                        <div class="row">

                            {{-- Date --}}

                            <div class="col-md-6 mb-3">

                                <label class="fl-label"><i class="ti ti-calendar"></i> Date</label>

                                <input type="text" class="form-control fl-readonly" value="{{ now()->format('d-m-Y') }}"
                                    readonly>

                            </div>


                            {{-- Time --}}

                            <div class="col-md-6 mb-3">

                                <label class="fl-label"><i class="ti ti-clock"></i> Time</label>

                                <input type="text" class="form-control fl-readonly" value="{{ now()->format('h:i A') }}"
                                    readonly>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Step 2 — Remarks --}}
                <div class="fl-step">

                    <span class="fl-step-num">2</span>

                    <div class="flex-grow-1">

                        <label class="fl-label"><i class="ti ti-notes"></i> Check In Remarks</label>

                        <textarea class="form-control" rows="3" name="check_in_remark"
                            placeholder="Add any notes about today's work..."></textarea>

                    </div>

                </div>


                {{-- Step 3 — Plan tasks --}}
                <div class="fl-step">

                    <span class="fl-step-num">3</span>

                    <div class="flex-grow-1">

                        <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">

                            <div>

                                <label class="fl-label mb-1"><i class="ti ti-list-check"></i> Today's Tasks</label>

                                <small class="text-muted">
                                    Add the tasks you plan to work on today.
                                </small>

                            </div>


                            <button type="button" id="addTask" class="btn btn-fl">

                                <i class="ti ti-plus"></i> Add Task

                            </button>

                        </div>


                        {{-- Task Area --}}

                        <div id="taskArea">

                            <div class="fl-taskrow task-row">

                                <span class="fl-tnum">1</span>

                                <input type="text" name="tasks[]" class="form-control" placeholder="Enter task">

                                <button type="button" class="fl-tdel removeTask" title="Remove task">

                                    <i class="ti ti-x"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


                @include('admin.field-log._gps')

                {{-- Check In Button --}}
                <div class="text-end mt-4">

                    @can('field-log.check-in')

                    <button type="submit" class="btn btn-fl big">

                        <i class="ti ti-player-play"></i> Check In

                    </button>

                    @endcan

                </div>

            </form>

        </div>

    </div>


    @else


    {{-- ========================================================= --}}
    {{-- ================== SUMMARY TILES ======================== --}}
    {{-- ========================================================= --}}

    <div class="fl-tiles">

        {{-- Date --}}
        <div class="fl-tile" style="--tc:#5E8D3D;">

            <span class="t-ic"><i class="ti ti-calendar"></i></span>

            <div class="t-label">Date</div>

            <div class="t-val">{{ $fieldLog->work_date->format('d-m-Y') }}</div>

        </div>


        {{-- Check In --}}
        <div class="fl-tile" style="--tc:#7CA243;">

            <span class="t-ic"><i class="ti ti-login"></i></span>

            <div class="t-label">Check In</div>

            <div class="t-val">{{ $fieldLog->check_in_time->format('h:i A') }}</div>

        </div>


        {{-- Check Out --}}
        <div class="fl-tile" style="--tc:#A8CB6A;">

            <span class="t-ic"><i class="ti ti-logout"></i></span>

            <div class="t-label">Check Out</div>

            <div class="t-val">{{ optional($fieldLog->check_out_time)->format('h:i A') ?? '--' }}</div>

        </div>


        {{-- Status --}}
        <div class="fl-tile" style="--tc:#1F5C2E;">

            <span class="t-ic"><i class="ti ti-flag"></i></span>

            <div class="t-label">Status</div>

            <div class="t-val">

                @if($isCheckedOut)

                <span class="fl-pill out"><span class="d"></span> Checked Out</span>

                @else

                <span class="fl-pill done"><span class="d"></span> Working</span>

                @endif

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ===================== TASK LIST ========================= --}}
    {{-- ========================================================= --}}

    <div class="fl-card mt-4">

        <div class="fl-card-head">

            <h5>

                <span class="h-ic"><i class="ti ti-list-check"></i></span>

                <span>
                    Today's Tasks
                    <small>Track and update your work progress.</small>
                </span>

            </h5>


            <div class="fl-chips">

                <span class="fl-chip"><span class="d done"></span> Done · {{ $done }}</span>

                <span class="fl-chip"><span class="d prog"></span> In Progress · {{ $inProgressTasks }}</span>

                <span class="fl-chip"><span class="d pend"></span> Pending · {{ $pendingTasks }}</span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover fl-table">

                    <thead>

                        <tr>

                            <th width="6%">
                                #
                            </th>

                            <th>
                                Task
                            </th>

                            <th width="15%">
                                Status
                            </th>

                            <th width="28%">
                                Pending Remark
                            </th>

                            <th width="12%">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($fieldLog->tasks as $key => $task)

                        <tr>

                            {{-- Number --}}

                            <td data-label="#">

                                <span class="fl-tno">
                                    {{ $key + 1 }}
                                </span>

                            </td>


                            {{-- Task --}}

                            <td data-label="Task">

                                <span class="fl-task-name">
                                    {{ $task->task }}
                                </span>

                            </td>


                            {{-- Status --}}

                            <td data-label="Status">

                                @if($task->status == 'Done')

                                <span class="fl-pill done">
                                    <span class="d"></span> Done
                                </span>

                                @elseif($task->status == 'In Progress')

                                <span class="fl-pill prog">
                                    <span class="d"></span> In Progress
                                </span>

                                @else

                                <span class="fl-pill pend">
                                    <span class="d"></span> Pending
                                </span>

                                @endif

                            </td>


                            {{-- Pending Remark --}}

                            <td data-label="Pending Remark">


                                @if($task->pending_remark)

                                {{ $task->pending_remark }}

                                @else

                                <span class="fl-remark-none">
                                    --
                                </span>

                                @endif

                            </td>


                            {{-- Action --}}

                            <td data-label="Action">

                                <button type="button" class="editTaskBtn" data-id="{{ $task->id }}"
                                    data-task="{{ $task->task }}" data-status="{{ $task->status }}"
                                    data-remark="{{ $task->pending_remark }}"
                                    data-bs-toggle="{{ $isCheckedOut ? '' : 'modal' }}"
                                    data-bs-target="{{ $isCheckedOut ? '' : '#taskModal' }}"
                                    {{ $isCheckedOut ? 'disabled' : '' }}>
                                    <i class="ti ti-pencil me-1"></i> Update
                                </button>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="5" class="fl-empty">

                                <div class="e-ic">

                                    <i class="ti ti-leaf"></i>

                                </div>

                                <b>No Tasks Found</b>

                                <small>
                                    There are no tasks recorded for today.
                                </small>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ===================== PROGRESS ========================== --}}
    {{-- ========================================================= --}}

    <div class="fl-card">

        <div class="fl-card-body">

            <div class="fl-prog">

                <div class="fl-ring" style="--p: {{ $percent }};">

                    <div class="fl-ring-val">

                        <b>{{ $percent }}%</b>

                        <span>Done</span>

                    </div>

                </div>


                <div class="fl-prog-info">

                    <strong style="font-family:var(--font-head,'Kanit',sans-serif);font-size:15px;color:var(--fl-ink);">
                        Today's Progress
                    </strong>

                    <div class="text-muted" style="font-size:12px;">
                        {{ $done }} of {{ $total }} tasks completed
                    </div>

                    <div class="bar">

                        <i style="--p: {{ $percent }}%;"></i>

                    </div>


                    <div class="fl-minichips">

                        <span class="fl-chip"><span class="d done"></span> Done · {{ $done }}</span>

                        <span class="fl-chip"><span class="d prog"></span> In Progress · {{ $inProgressTasks }}</span>

                        <span class="fl-chip"><span class="d pend"></span> Pending · {{ $pendingTasks }}</span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ===================== CHECK OUT ========================= --}}
    {{-- ========================================================= --}}

    <div class="fl-card">

        <div class="fl-card-head">

            <h5>

                <span class="h-ic"><i class="ti ti-logout"></i></span>

                <span>
                    Check Out
                    <small>Complete your workday</small>
                </span>

            </h5>


            @if($isCheckedOut)

            <span class="fl-pill out"><span class="d"></span> Completed</span>

            @elseif($pendingTasks > 0)

            <span class="fl-pill pend"><span class="d"></span> Pending Tasks</span>

            @else

            <span class="fl-pill done"><span class="d"></span> Available</span>

            @endif

        </div>


        <div class="fl-card-body">

            {{-- Already Checked Out --}}

            @if($isCheckedOut)

            <div class="fl-co-state off mb-0">

                <span class="ic"><i class="ti ti-circle-check"></i></span>

                <div>

                    <b>Already Checked Out</b>

                    <br>

                    You have already checked out for today.

                    @if($fieldLog->check_out_time)

                    <br>

                    Check out time: <strong>{{ $fieldLog->check_out_time->format('h:i A') }}</strong>

                    @endif

                </div>

            </div>


            {{-- Pending Tasks --}}

            @elseif($pendingTasks > 0)

            <div class="fl-co-state warn mb-0">

                <span class="ic"><i class="ti ti-lock"></i></span>

                <div>

                    <b>Checkout Not Available</b>

                    <br>

                    You have <strong>{{ $pendingTasks }}</strong> Pending task(s).

                    <br>

                    Please move all Pending tasks to <strong>In Progress</strong> or <strong>Done</strong> before
                    checking out.

                </div>

            </div>


            {{-- Checkout Available --}}

            @else

            <div class="fl-co-state ok">

                <span class="ic"><i class="ti ti-rocket"></i></span>

                <div>

                    <b>Checkout Available</b>

                    <br>

                    You can check out now.

                    @if($inProgressTasks > 0)

                    <br>

                    <strong>{{ $inProgressTasks }}</strong> task(s) are still <strong>In Progress</strong>.

                    @endif

                </div>

            </div>


            <div class="fl-co-cta">

                <div>

                    <b>Ready to finish? 🎯</b>

                    <small>
                        Review your tasks before checking out.
                    </small>

                </div>


                <button type="button" class="btn btn-fl" data-bs-toggle="modal" data-bs-target="#checkoutModal">

                    <i class="ti ti-logout"></i> Check Out

                </button>

            </div>

            @endif


        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ================= CHECKOUT MODAL ======================== --}}
    {{-- ========================================================= --}}

    @if(!$isCheckedOut && $pendingTasks === 0)

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <form action="{{ route('admin.field-log.checkout') }}" method="POST">

                @csrf


                <div class="modal-content">

                    {{-- Modal Header --}}

                    <div class="modal-header">

                        <div class="d-flex align-items-center">

                            <span class="m-ic"><i class="ti ti-logout"></i></span>

                            <div>

                                <h5 class="modal-title" id="checkoutModalLabel">
                                    Confirm Check Out
                                </h5>

                                <small>
                                    Complete your field log for today
                                </small>

                            </div>

                        </div>


                        <button type="button" class="m-close" data-bs-dismiss="modal" aria-label="Close">

                            <i class="ti ti-x"></i>

                        </button>

                    </div>


                    {{-- Modal Body --}}

                    <div class="modal-body">

                        <div class="fl-alert warn mb-3" style="background:#FDF7EA;border-color:#F0E3C4;color:#7A5A14;">

                            <i class="ti ti-alert-circle"></i>

                            <div>

                                <strong>Are you sure you want to check out?</strong>

                                <br>

                                Once checked out, you will not be able to update today's tasks.

                            </div>

                        </div>


                        {{-- Checkout Summary --}}
                        <div class="fl-sumstrip">

                            <div>

                                <span class="sl">
                                    Total
                                </span>

                                <span class="sv">
                                    {{ $total }}
                                </span>

                            </div>


                            <div>

                                <span class="sl">
                                    Done
                                </span>

                                <span class="sv g">
                                    {{ $done }}
                                </span>

                            </div>


                            <div>

                                <span class="sl">
                                    Progress
                                </span>

                                <span class="sv">
                                    {{ $percent }}%
                                </span>

                            </div>

                        </div>


                        {{-- Checkout Remark --}}

                        <div class="mb-2">

                            <label class="fl-label"><i class="ti ti-notes"></i> Check Out Remark</label>

                            <textarea name="check_out_remark" class="form-control" rows="4"
                                placeholder="Enter check out remarks..."></textarea>

                        </div>

                        @include('admin.field-log._gps')

                    </div>


                    {{-- Modal Footer --}}

                    <div class="modal-footer">

                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button type="submit" class="btn btn-fl">

                            <i class="ti ti-logout"></i> Confirm Check Out

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ===================== TASK UPDATE MODAL ================ --}}
    {{-- ========================================================= --}}

    @if(!$isCheckedOut)

    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <form action="{{ route('admin.field-log.task.update') }}" method="POST">

                @csrf


                <input type="hidden" name="task_id" id="task_id">


                <div class="modal-content">

                    {{-- Modal Header --}}

                    <div class="modal-header">

                        <div class="d-flex align-items-center">

                            <span class="m-ic"><i class="ti ti-pencil"></i></span>

                            <div>

                                <h5 class="modal-title" id="taskModalLabel">
                                    Update Task
                                </h5>

                                <small>
                                    Update task status and remarks
                                </small>

                            </div>

                        </div>


                        <button type="button" class="m-close" data-bs-dismiss="modal" aria-label="Close">

                            <i class="ti ti-x"></i>

                        </button>

                    </div>


                    {{-- Modal Body --}}

                    <div class="modal-body">

                        {{-- Task --}}

                        <div class="mb-3">

                            <label class="fl-label"><i class="ti ti-list-check"></i> Task</label>

                            <input type="text" id="task_name" class="form-control fl-readonly" readonly>

                        </div>


                        {{-- Status --}}

                        <div class="mb-3">

                            <label class="fl-label"><i class="ti ti-flag"></i> Status</label>

                            <select name="status" id="task_status" class="form-select">

                                <option value="Pending">
                                    Pending
                                </option>

                                <option value="In Progress">
                                    In Progress
                                </option>

                                <option value="Done">
                                    Done
                                </option>

                            </select>

                        </div>


                        {{-- Pending Remark --}}

                        <div class="mb-3" id="remarkDiv">

                            <label class="fl-label"><i class="ti ti-notes"></i> Pending Remark</label>

                            <textarea name="pending_remark" id="pending_remark" rows="3" class="form-control"
                                placeholder="Enter pending/in-progress remark..."></textarea>

                        </div>

                    </div>


                    {{-- Modal Footer --}}

                    <div class="modal-footer">

                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                            Cancel
                        </button>


                        <button type="submit" class="btn btn-fl">
                            <i class="ti ti-check"></i> Update Task
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    @endif

    @endif

</div>


{{-- ========================================================= --}}
{{-- ===================== JAVASCRIPT ======================== --}}
{{-- ========================================================= --}}

@push('scripts')

<script>
$(function() {

    /*
    |--------------------------------------------------------------------------
    | ADD TASK (renumber rows too)
    |--------------------------------------------------------------------------
    */

    function flRenumber() {

        $('#taskArea .fl-taskrow').each(function(i) {

            $(this).find('.fl-tnum').text(i + 1);

        });

    }


    $('#addTask').on('click', function() {

        let html = `
            <div class="fl-taskrow task-row">

                <span class="fl-tnum"></span>

                <input
                    type="text"
                    name="tasks[]"
                    class="form-control"
                    placeholder="Enter task"
                >

                <button
                    type="button"
                    class="fl-tdel removeTask"
                    title="Remove task"
                >
                    <i class="ti ti-x"></i>
                </button>

            </div>
        `;

        $('#taskArea').append(html);

        flRenumber();

        $('#taskArea .fl-taskrow:last .form-control').trigger('focus');

    });


    /*
    |--------------------------------------------------------------------------
    | REMOVE TASK
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.removeTask', function() {

        $(this)
            .closest('.task-row')
            .remove();

        flRenumber();

    });


    /*
    |--------------------------------------------------------------------------
    | OPEN TASK UPDATE MODAL
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.editTaskBtn', function() {

        let taskId = $(this).data('id');

        let taskName = $(this).data('task');

        let taskStatus = $(this).data('status');

        let taskRemark = $(this).data('remark');

        $('#task_id').val(taskId);

        $('#task_name').val(taskName);

        $('#task_status').val(taskStatus);

        $('#pending_remark').val(taskRemark || '');

        toggleRemark();

    });


    /*
    |--------------------------------------------------------------------------
    | STATUS CHANGE
    |--------------------------------------------------------------------------
    */

    $('#task_status').on('change', function() {

        toggleRemark();

    });


    /*
    |--------------------------------------------------------------------------
    | SHOW / HIDE REMARK
    |--------------------------------------------------------------------------
    */

    function toggleRemark() {

        if ($('#task_status').val() === 'Done') {

            $('#remarkDiv').hide();

            $('#pending_remark').val('');

        } else {

            $('#remarkDiv').show();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LIVE CLOCK (hero)
    |--------------------------------------------------------------------------
    */

    function flTick() {

        const now = new Date();

        const t = now.toLocaleTimeString('en-IN', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });

        $('#flClock').text(t);

    }

    flTick();

    setInterval(flTick, 1000);


    /*
    |--------------------------------------------------------------------------
    | MOBILE CARD TABLE — inject data-labels (desktop does nothing)
    |--------------------------------------------------------------------------
    */

    $('.spc-fl .fl-table tbody td').each(function() {

        if (!$(this).attr('data-label')) {

            $(this).attr('data-label', $(this).closest('table').find('thead th').eq($(this).index())
                .text().trim());

        }

    });


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT MODAL
    |--------------------------------------------------------------------------
    |
    | Bootstrap handles the modal through:
    |
    | data-bs-toggle="modal"
    | data-bs-target="#checkoutModal"
    |
    | No additional JavaScript is required.
    |
    |--------------------------------------------------------------------------
    */

});
</script>

@endpush

@endsection