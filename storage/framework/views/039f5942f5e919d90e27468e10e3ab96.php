<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
    @page {
        size: A4 portrait;
        margin: 7mm;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 7px;
        color: #1d2b21;
    }

    .page {
        width: 100%;
    }

    /* ================= HEADER ================= */

    .header {
        width: 100%;
        border-bottom: 2px solid #1b6b3a;
        padding-bottom: 7px;
        margin-bottom: 8px;
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
        font-size: 27px;
        font-weight: bold;
        font-style: italic;
    }

    .logo-sub {
        color: #126b39;
        font-size: 6px;
        font-weight: bold;
        letter-spacing: 2px;
        margin-top: -3px;
    }

    .invoice-title {
        color: #126b39;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .header-info {
        width: 100%;
        border-collapse: collapse;
    }

    .header-info td {
        padding: 1px 0;
        font-size: 7px;
    }

    .header-info .label {
        text-align: right;
        padding-right: 7px;
    }

    .header-info .value {
        width: 80px;
        text-align: left;
        font-weight: bold;
    }

    /* ================= INFO BOXES ================= */

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }

    .info-table td {
        vertical-align: top;
    }

    .left-info {
        width: 50%;
        padding-right: 5px;
    }

    .right-info {
        width: 50%;
        padding-left: 5px;
    }

    .box {
        border: 1px solid #d4e4d0;
        border-radius: 5px;
        overflow: hidden;
    }

    .box-title {
        background: #e7f2e4;
        color: #126b39;
        font-size: 8px;
        font-weight: bold;
        padding: 5px 7px;
        border-bottom: 1px solid #d4e4d0;
    }

    .box-content {
        padding: 6px 7px;
        min-height: 103px;
    }

    .company-name {
        font-size: 8px;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .small-line {
        line-height: 1.45;
    }

    .section-label {
        color: #126b39;
        font-weight: bold;
        margin-top: 5px;
        margin-bottom: 2px;
    }

    .buyer-name {
        font-weight: bold;
        font-size: 8px;
    }

    .details {
        width: 100%;
        border-collapse: collapse;
    }

    .details td {
        padding: 2px 0;
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
        font-size: 6.5px;
    }

    .items th {
        background: #dcebd8;
        color: #075f32;
        border: 1px solid #c6dbc1;
        padding: 4px 2px;
        text-align: center;
        font-weight: bold;
        line-height: 1.2;
    }

    .items td {
        border: 1px solid #dce5d9;
        padding: 3px 2px;
        text-align: center;
        line-height: 1.2;
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
        font-size: 8px;
    }

    /* ================= AMOUNT WORDS ================= */

    .amount-words {
        margin-top: 5px;
        margin-bottom: 6px;
    }

    .amount-label {
        font-size: 6.5px;
    }

    .words {
        color: #126b39;
        font-weight: bold;
        font-size: 8px;
        margin-top: 2px;
    }

    .currency {
        text-align: right;
        font-size: 6px;
    }

    /* ================= FOOTER ================= */

    .footer-table {
        width: 100%;
        border-collapse: collapse;
    }

    .footer-left {
        width: 50%;
        padding-right: 5px;
        vertical-align: top;
    }

    .footer-right {
        width: 50%;
        padding-left: 5px;
        vertical-align: top;
    }

    .payment-box {
        background: #e7f2e4;
        border-radius: 5px;
        padding: 7px;
        min-height: 80px;
    }

    .payment-title {
        color: #126b39;
        font-size: 8px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .payment-line {
        line-height: 1.4;
    }

    .declaration-box {
        border: 1px solid #d4e4d0;
        border-radius: 5px;
        padding: 7px;
        min-height: 80px;
    }

    .declaration-title {
        color: #126b39;
        font-size: 8px;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .declaration-text {
        font-size: 6.5px;
        line-height: 1.35;
    }

    .signature {
        text-align: center;
        margin-top: 20px;
        font-size: 6.5px;
    }

    .signature-line {
        width: 100px;
        margin: 0 auto 3px auto;
        border-top: 1px solid #333;
    }

    /* ================= BOTTOM ================= */

    .bottom {
        margin-top: 6px;
        border-top: 1px solid #126b39;
        padding-top: 4px;
    }

    .bottom-text {
        color: #126b39;
        font-size: 7px;
        font-weight: bold;
        font-style: italic;
    }
    </style>
</head>

<body>

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

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        <?php echo e($order->c_order_no); ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Dated
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        <?php echo e($order->d_date?->format('d-M-y')); ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Delivery Note
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        -
                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Reference No. & Date
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        -
                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Buyer's Order No.
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        -
                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Dispatch Doc No.
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        -
                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Dispatched through
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        -
                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Mode of Payment
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        <?php echo e(ucwords(str_replace('_', ' ', $order->c_mode_of_payment ?? '-'))); ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        LOAD
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

                                    <td class="d-value">
                                        <?php echo e($order->c_customer_address ?? '-'); ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td class="d-label">
                                        Terms of Delivery
                                    </td>

                                    <td class="colon">
                                        :
                                    </td>

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


        

        <?php
        /*
        * IMPORTANT:
        *
        * product_price is GST-INCLUSIVE.
        *
        */

        $subtotal = 0;
        $totalQty = 0;
        $gstTotal = 0;
        $grandTotal = 0;
        ?>


        

        <table class="items">

            <thead>

                <tr>

                    <th style="width: 5%;">
                        Sl.<br>No.
                    </th>

                    <th style="width: 25%;">
                        Description of Goods
                    </th>

                    <th style="width: 12%;">
                        HSN/SAC
                    </th>

                    <th style="width: 10%;">
                        Quantity
                    </th>

                    <th style="width: 11%;">
                        Rate<br>(Incl. of Tax)
                    </th>

                    <th style="width: 11%;">
                        Rate<br>
                    </th>

                    <th style="width: 8%;">
                        Per
                    </th>

                    <th style="width: 18%;">
                        Amount
                    </th>

                </tr>

            </thead>


            <tbody>

                <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php

                /*
                * qty = actual quantity ordered
                *
                * product_price = GST-INCLUSIVE selling price / MRP
                *
                * c_unit = product unit / pack size
                */

                $qty = (float) ($item->qty ?? 0);

                $rateInclusive = (float) ($item->product_price ?? 0);

                $gstPercentage = (float) (
                $item->product?->n_gst_percentage ?? 0
                );


                /*
                * Extract GST from GST-inclusive price.
                *
                * Formula:
                *
                * Taxable Rate =
                * Inclusive Rate / (1 + GST / 100)
                */

                if ($gstPercentage > 0) {

                $rateExclusive = $rateInclusive
                / (1 + ($gstPercentage / 100));

                } else {

                $rateExclusive = $rateInclusive;

                }


                /*
                * GST per unit
                */

                $gstPerUnit = $rateInclusive - $rateExclusive;


                /*
                * Line totals
                */

                $amountInclusive = $rateInclusive * $qty;

                $amountExclusive = $rateExclusive * $qty;

                $gstAmount = $gstPerUnit * $qty;


                /*
                * Running totals
                */

                $subtotal += $amountExclusive;

                $gstTotal += $gstAmount;

                $totalQty += $qty;

                $grandTotal += $amountInclusive;

                ?>


                <tr>

                    

                    <td>
                        <?php echo e($index + 1); ?>

                    </td>


                    

                    <td class="description">
                        <?php echo e($item->product?->c_product_name ?? 'Product'); ?>

                    </td>


                    

                    <td>
                        <?php echo e($item->product?->c_hsn_code ?? '-'); ?>

                    </td>


                    

                    <td>
                        <?php echo e(number_format($qty, 0)); ?> Nos
                    </td>


                    

                    <td>
                        ₹ <?php echo e(number_format($rateInclusive, 2)); ?>

                    </td>


                    

                    <td>
                        ₹ <?php echo e(number_format($rateExclusive, 2)); ?>

                    </td>


                    

                    <td>
                        <?php echo e($item->product?->c_unit ?? '-'); ?>

                    </td>


                    

                    <td class="right">
                        ₹ <?php echo e(number_format($amountInclusive, 2)); ?>

                    </td>

                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>


            <tfoot>

                

                <tr>

                    <td colspan="6"></td>

                    <td class="summary-label">
                        Taxable Value
                    </td>

                    <td class="summary-value">
                        ₹ <?php echo e(number_format($subtotal, 2)); ?>

                    </td>

                </tr>


                

                <tr>

                    <td colspan="6"></td>

                    <td class="summary-label">
                        GST
                    </td>

                    <td class="summary-value">
                        ₹ <?php echo e(number_format($gstTotal, 2)); ?>

                    </td>

                </tr>


                

                <tr>

                    <td colspan="3" class="total-label">
                        Total
                    </td>

                    <td class="total-label">
                        <?php echo e(number_format($totalQty, 0)); ?> Nos
                    </td>

                    <td colspan="3" class="total-label">
                        Total Amount
                    </td>

                    <td class="total-value">
                        ₹ <?php echo e(number_format($grandTotal, 2)); ?>

                    </td>

                </tr>

            </tfoot>

        </table>


        

        <div class="amount-words">

            <div class="amount-label">
                Amount Chargeable (in words)
            </div>

            <div class="words">
                INR <?php echo e(number_format($grandTotal, 2)); ?> Only
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

</body>

</html><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/pdf/invoice.blade.php ENDPATH**/ ?>