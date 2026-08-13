<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Invoice Preview - <?php echo e($order->c_order_no); ?>

    </title>

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 25px;
        background: #f3f5f3;
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 9px;
        color: #1d2b21;
    }

    .preview-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    /* ================= ACTIONS ================= */

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-bottom: 15px;
    }

    .btn {
        display: inline-block;
        border: 0;
        border-radius: 5px;
        padding: 9px 16px;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-print {
        background: #126b39;
        color: #fff;
    }

    .btn-close {
        background: #777;
        color: #fff;
    }

    /* ================= PAGE ================= */

    .page {
        width: 100%;
        background: #fff;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.10);
    }

    /* ================= HEADER ================= */

    .header {
        width: 100%;
        border-bottom: 2px solid #1b6b3a;
        padding-bottom: 10px;
        margin-bottom: 12px;
    }

    .header-table {
        width: 100%;
        border-collapse: collapse;
    }

    .logo-cell {
        width: 55%;
        vertical-align: middle;
    }

    .invoice-cell {
        width: 45%;
        text-align: right;
        vertical-align: middle;
    }

    .logo {
        color: #126b39;
        font-size: 32px;
        font-weight: bold;
        font-style: italic;
    }

    .logo-sub {
        color: #126b39;
        font-size: 7px;
        font-weight: bold;
        letter-spacing: 2px;
        margin-top: -3px;
    }

    .invoice-title {
        color: #126b39;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 7px;
    }

    .header-info {
        width: 100%;
        border-collapse: collapse;
    }

    .header-info td {
        padding: 2px 0;
        font-size: 9px;
    }

    .header-info .label {
        text-align: right;
        padding-right: 8px;
    }

    .header-info .value {
        width: 100px;
        text-align: left;
        font-weight: bold;
    }

    /* ================= INFO BOXES ================= */

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }

    .info-table td {
        vertical-align: top;
    }

    .left-info {
        width: 50%;
        padding-right: 6px;
    }

    .right-info {
        width: 50%;
        padding-left: 6px;
    }

    .box {
        border: 1px solid #d4e4d0;
        border-radius: 6px;
        overflow: hidden;
    }

    .box-title {
        background: #e7f2e4;
        color: #126b39;
        font-size: 10px;
        font-weight: bold;
        padding: 7px 9px;
        border-bottom: 1px solid #d4e4d0;
    }

    .box-content {
        padding: 9px;
        min-height: 135px;
    }

    .company-name {
        font-size: 10px;
        font-weight: bold;
        margin-bottom: 3px;
    }

    .small-line {
        line-height: 1.55;
    }

    .section-label {
        color: #126b39;
        font-weight: bold;
        margin-top: 8px;
        margin-bottom: 3px;
    }

    .buyer-name {
        font-weight: bold;
        font-size: 10px;
    }

    .details {
        width: 100%;
        border-collapse: collapse;
    }

    .details td {
        padding: 3px 0;
        vertical-align: top;
    }

    .details .d-label {
        width: 38%;
    }

    .details .colon {
        width: 4%;
        text-align: center;
    }

    .details .d-value {
        width: 58%;
        font-weight: bold;
    }

    /* ================= PRODUCT TABLE ================= */

    .items {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 8px;
    }

    .items th {
        background: #dcebd8;
        color: #075f32;
        border: 1px solid #c6dbc1;
        padding: 6px 3px;
        text-align: center;
        font-weight: bold;
        line-height: 1.3;
    }

    .items td {
        border: 1px solid #dce5d9;
        padding: 5px 3px;
        text-align: center;
        line-height: 1.3;
        vertical-align: middle;
    }

    .items .description {
        text-align: left;
        font-weight: bold;
    }

    .items .right {
        text-align: right;
    }

    .items .summary-label {
        text-align: right;
        font-style: italic;
    }

    .items .summary-value {
        text-align: right;
        font-weight: bold;
    }

    .items .total-label {
        background: #e7f2e4;
        text-align: right;
        font-weight: bold;
    }

    .items .total-value {
        background: #126b39;
        color: white;
        text-align: right;
        font-weight: bold;
        font-size: 10px;
    }

    /* ================= AMOUNT WORDS ================= */

    .amount-words {
        margin-top: 7px;
        margin-bottom: 8px;
    }

    .amount-label {
        font-size: 8px;
    }

    .words {
        color: #126b39;
        font-weight: bold;
        font-size: 10px;
        margin-top: 3px;
    }

    .currency {
        text-align: right;
        font-size: 7px;
    }

    /* ================= FOOTER ================= */

    .footer-table {
        width: 100%;
        border-collapse: collapse;
    }

    .footer-left {
        width: 50%;
        padding-right: 6px;
        vertical-align: top;
    }

    .footer-right {
        width: 50%;
        padding-left: 6px;
        vertical-align: top;
    }

    .payment-box {
        background: #e7f2e4;
        border-radius: 6px;
        padding: 9px;
        min-height: 105px;
    }

    .payment-title {
        color: #126b39;
        font-size: 10px;
        font-weight: bold;
        margin-bottom: 6px;
    }

    .payment-line {
        line-height: 1.5;
    }

    .declaration-box {
        border: 1px solid #d4e4d0;
        border-radius: 6px;
        padding: 9px;
        min-height: 105px;
    }

    .declaration-title {
        color: #126b39;
        font-size: 10px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .declaration-text {
        font-size: 8px;
        line-height: 1.45;
    }

    .signature {
        text-align: center;
        margin-top: 25px;
        font-size: 8px;
    }

    .signature-line {
        width: 120px;
        margin: 0 auto 4px auto;
        border-top: 1px solid #333;
    }

    /* ================= BOTTOM ================= */

    .bottom {
        margin-top: 8px;
        border-top: 1px solid #126b39;
        padding-top: 6px;
    }

    .bottom-text {
        color: #126b39;
        font-size: 9px;
        font-weight: bold;
        font-style: italic;
    }

    /* ================= PRINT ================= */

    @media print {

        body {
            background: #fff;
            padding: 0;
        }

        .preview-wrapper {
            max-width: none;
        }

        .page {
            box-shadow: none;
            padding: 10px;
        }

        .actions {
            display: none;
        }
    }

    /* ================= MOBILE ================= */

    @media screen and (max-width: 700px) {

        body {
            padding: 10px;
        }

        .page {
            padding: 15px;
        }

        .header-table,
        .info-table,
        .footer-table {
            display: block;
        }

        .header-table tr,
        .info-table tr,
        .footer-table tr {
            display: block;
        }

        .logo-cell,
        .invoice-cell,
        .left-info,
        .right-info,
        .footer-left,
        .footer-right {
            display: block;
            width: 100%;
            padding: 0;
        }

        .invoice-cell {
            text-align: left;
            margin-top: 15px;
        }

        .right-info {
            margin-top: 10px;
        }

        .footer-right {
            margin-top: 10px;
        }

        .items {
            font-size: 7px;
            min-width: 700px;
        }

        .items-wrapper {
            overflow-x: auto;
        }
    }
    </style>
</head>

<body>

    <div class="preview-wrapper">

        

        <div class="actions">

            <button type="button" class="btn btn-print" onclick="window.print()">
                Print
            </button>

            <button type="button" class="btn btn-close" onclick="window.history.back()">
                Close
            </button>

        </div>


        <div class="page">

            

            <div class="header">

                <table class="header-table">

                    <tr>

                        <td class="logo-cell">

                            <div class="logo">
                                spc
                            </div>

                            <div class="logo-sub">
                                SPICES PRODUCERS COMPANY
                            </div>

                        </td>

                        <td class="invoice-cell">

                            <div class="invoice-title">
                                INVOICE
                            </div>

                            <table class="header-info">

                                <tr>
                                    <td class="label">
                                        Invoice No:
                                    </td>

                                    <td class="value">
                                        <?php echo e($order->c_order_no); ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td class="label">
                                        Date:
                                    </td>

                                    <td class="value">
                                        <?php echo e($order->d_date?->format('d M Y')); ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td class="label">
                                        Due Date:
                                    </td>

                                    <td class="value">
                                        <?php echo e($order->d_date?->format('d M Y')); ?>

                                    </td>
                                </tr>

                            </table>

                        </td>

                    </tr>

                </table>

            </div>


            

            <table class="info-table">

                <tr>

                    <td class="left-info">

                        <div class="box">

                            <div class="box-title">
                                Billed From
                            </div>

                            <div class="box-content">

                                <div class="company-name">
                                    <?php echo e($company->company_name); ?>

                                </div>

                                <div class="small-line">

                                    <?php echo e($company->address); ?><br>

                                    <?php if($company->phone): ?>
                                    Phone: <?php echo e($company->phone); ?><br>
                                    <?php endif; ?>

                                    <?php if($company->email): ?>
                                    Email: <?php echo e($company->email); ?><br>
                                    <?php endif; ?>

                                    <?php if($company->website): ?>
                                    Website: <?php echo e($company->website); ?>

                                    <?php endif; ?>

                                </div>

                                <div class="section-label">
                                    Buyer (Bill to)
                                </div>

                                <div class="buyer-name">
                                    <?php echo e($order->c_customer_name); ?>

                                </div>

                                <div class="small-line">

                                    <?php echo e($order->c_customer_address); ?><br>

                                    Ph: <?php echo e($order->n_customer_mobile); ?>


                                    <?php if($order->c_customer_email): ?>
                                    <br>
                                    Email: <?php echo e($order->c_customer_email); ?>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </td>


                    <td class="right-info">

                        <div class="box">

                            <div class="box-title">
                                Invoice Details
                            </div>

                            <div class="box-content">

                                <table class="details">

                                    <tr>
                                        <td class="d-label">
                                            Invoice No.
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            <?php echo e($order->c_order_no); ?>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Dated
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            <?php echo e($order->d_date?->format('d-M-y')); ?>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Delivery Note
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            -
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Reference No. & Date
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            -
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Buyer's Order No.
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            -
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Dispatch Doc No.
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            -
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Dispatched through
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            -
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Mode of Payment
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            <?php echo e(ucwords(str_replace('_', ' ', $order->c_mode_of_payment ?? '-'))); ?>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            LOAD
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            <?php echo e($order->c_customer_address ?? '-'); ?>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="d-label">
                                            Terms of Delivery
                                        </td>

                                        <td class="colon">:</td>

                                        <td class="d-value">
                                            -
                                        </td>
                                    </tr>

                                </table>

                            </div>

                        </div>

                    </td>

                </tr>

            </table>


            

            <div class="items-wrapper">

                

                <div class="items-wrapper">

                    <table class="items">

                        <thead>

                            <tr>

                                <th style="width: 5%;">
                                    Sl No
                                </th>

                                <th style="width: 22%;">
                                    Description of goods
                                </th>

                                <th style="width: 11%;">
                                    HSN Code
                                </th>

                                <th style="width: 9%;">
                                    Quantity
                                </th>

                                <th style="width: 8%;">
                                    Unit
                                </th>

                                <th style="width: 11%;">
                                    Price
                                </th>

                                <th style="width: 10%;">
                                    Discount
                                </th>

                                <th style="width: 12%;">
                                    Discounted price
                                </th>

                                <th style="width: 12%;">
                                    Taxable Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php $__currentLoopData = $calculation['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php

                            /*
                            * Safe fallback values.
                            * These prevent Undefined array key errors
                            * until the controller is updated.
                            */

                            $price = $item['rate_exclusive']
                            ?? $item['rate']
                            ?? $item['rate_inclusive']
                            ?? 0;

                            $discount = $item['discount']
                            ?? 0;

                            $discountedPrice = $item['discounted_price']
                            ?? ($price - $discount);

                            $priceIncludingGst = $item['amount_inclusive']
                            ?? 0;

                            ?>

                            <tr>

                                

                                <td>
                                    <?php echo e($index + 1); ?>

                                </td>


                                

                                <td class="description">
                                    <?php echo e($item['product_name'] ?? '-'); ?>

                                </td>


                                

                                <td>
                                    <?php echo e($item['hsn'] ?? '-'); ?>

                                </td>


                                

                                <td>
                                    <?php echo e(number_format($item['qty'] ?? 0, 0)); ?>

                                </td>


                                

                                <td>
                                    <?php echo e($item['unit'] ?? '-'); ?>

                                </td>


                                

                                <td class="right">
                                    ₹ <?php echo e(number_format($price, 2)); ?>

                                </td>


                                

                                <td class="right">
                                    ₹ <?php echo e(number_format($discount ?? 0, 2)); ?>

                                </td>


                                

                                <td class="right">
                                    ₹ <?php echo e(number_format($discountedPrice ?? 0, 2)); ?>

                                </td>


                                

                                <td class="right">
                                    ₹ <?php echo e(number_format($priceIncludingGst, 2)); ?>

                                </td>

                            </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>


                        <tfoot>

                            

                            <tr>

                                <td colspan="7"></td>

                                <td class="summary-label">
                                    Taxable amount
                                </td>

                                <td class="summary-value">
                                    ₹ <?php echo e(number_format($calculation['subtotal'] ?? 0, 2)); ?>

                                </td>

                            </tr>


                            

                            <tr>

                                <td colspan="7"></td>

                                <td class="summary-label">
                                    GST
                                </td>

                                <td class="summary-value">
                                    ₹ <?php echo e(number_format($calculation['gst_total'] ?? 0, 2)); ?>

                                </td>

                            </tr>


                            

                            <tr>

                                <td colspan="7"></td>

                                <td class="total-label">
                                    Amount Payable
                                </td>

                                <td class="total-value">
                                    ₹ <?php echo e(number_format($calculation['grand_total'] ?? 0, 2)); ?>

                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>


            

            <div class="amount-words">

                <div class="amount-label">
                    Amount Chargeable (in words)
                </div>

                <div class="words">
                    <?php echo e($calculation['grand_total_words']); ?>

                </div>

                <div class="currency">
                    E. &amp; O. E
                </div>

            </div>


            

            <table class="footer-table">

                <tr>

                    <td class="footer-left">

                        <div class="payment-box">

                            <div class="payment-title">
                                Payment Method
                            </div>

                            <div class="payment-line">
                                <?php echo e(ucwords(str_replace('_', ' ', $order->c_mode_of_payment ?? '-'))); ?>

                            </div>

                            <br>

                            <div class="payment-line">
                                <strong>Bank Details:</strong>
                            </div>

                            <div class="payment-line">
                                <?php echo e($company->account_name); ?>

                            </div>

                            <div class="payment-line">
                                A/c No: <?php echo e($company->account_number); ?>

                            </div>

                            <div class="payment-line">
                                IFSC: <?php echo e($company->ifsc_code); ?>

                            </div>

                            <div class="payment-line">
                                Bank: <?php echo e($company->bank_name); ?>

                            </div>

                            <?php if($company->branch): ?>

                            <div class="payment-line">
                                Branch: <?php echo e($company->branch); ?>

                            </div>

                            <?php endif; ?>

                        </div>

                    </td>


                    <td class="footer-right">

                        <div class="declaration-box">

                            <div class="declaration-title">
                                Declaration
                            </div>

                            <div class="declaration-text">

                                We declare that this invoice shows the actual
                                price of the goods described and that all
                                particulars are true and correct.

                            </div>


                            <div class="signature">

                                <div class="signature-line"></div>

                                Authorised Signatory

                            </div>

                        </div>

                    </td>

                </tr>

            </table>


            

            <div class="bottom">

                <div class="bottom-text">
                    Pure Spices. Better Life.
                </div>

            </div>

        </div>

    </div>

</body>

</html><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/pdf/invoice-preview.blade.php ENDPATH**/ ?>