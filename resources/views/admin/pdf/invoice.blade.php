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

        {{-- ================= HEADER ================= --}}

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
                                    {{ $order->c_order_no }}
                                </td>
                            </tr>

                            <tr>
                                <td class="label">
                                    Date:
                                </td>

                                <td class="value">
                                    {{ $order->d_date?->format('d M Y') }}
                                </td>
                            </tr>

                            <tr>
                                <td class="label">
                                    Due Date:
                                </td>

                                <td class="value">
                                    {{ $order->d_date?->format('d M Y') }}
                                </td>
                            </tr>

                        </table>

                    </td>

                </tr>

            </table>

        </div>


        {{-- ================= BILLING + DETAILS ================= --}}

        <table class="info-table">

            <tr>

                <td class="left-info">

                    <div class="box">

                        <div class="box-title">
                            Billed From
                        </div>

                        <div class="box-content">


                            <div class="company-name">
                                {{ $company->company_name }}
                            </div>

                            <div class="small-line">
                                {{ $company->address }}<br>

                                @if($company->phone)
                                Phone: {{ $company->phone }}<br>
                                @endif

                                @if($company->email)
                                Email: {{ $company->email }}<br>
                                @endif

                                @if($company->website)
                                Website: {{ $company->website }}
                                @endif
                            </div>

                            <div class="section-label">
                                Buyer (Bill to)
                            </div>

                            <div class="buyer-name">
                                {{ $order->c_customer_name }}
                            </div>

                            <div class="small-line">
                                {{ $order->c_customer_address }}<br>
                                Ph: {{ $order->n_customer_mobile }}

                                @if($order->c_customer_email)
                                <br>
                                Email: {{ $order->c_customer_email }}
                                @endif
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
                                        {{ $order->c_order_no }}
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
                                        {{ $order->d_date?->format('d-M-y') }}
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
                                        {{ ucwords(str_replace('_', ' ', $order->c_mode_of_payment ?? '-')) }}
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
                                        {{ $order->c_customer_address ?? '-' }}
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



        {{-- ================= PRODUCTS ================= --}}
        {{-- ================= PRODUCTS ================= --}}

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

                @foreach($calculation['items'] as $index => $item)

                <tr>

                    {{-- SL NO --}}
                    <td>
                        {{ $index + 1 }}
                    </td>

                    {{-- DESCRIPTION --}}
                    <td class="description">
                        {{ $item['product_name'] }}
                    </td>

                    {{-- HSN CODE --}}
                    <td>
                        {{ $item['hsn'] }}
                    </td>

                    {{-- QUANTITY --}}
                    <td>
                        {{ number_format($item['qty'], 0) }}
                    </td>

                    {{-- UNIT --}}
                    <td>
                        {{ $item['unit'] }}
                    </td>

                    {{-- PRICE --}}
                    <td class="right">
                        ₹ {{ number_format($item['rate'] ?? $item['rate_inclusive'] ?? 0, 2) }}
                    </td>

                    {{-- DISCOUNT --}}
                    <td class="right">
                        ₹ {{ number_format($item['discount'] ?? 0, 2) }}
                    </td>

                    {{-- DISCOUNTED PRICE --}}
                    <td class="right">
                        ₹ {{ number_format(
        $item['discounted_price']
        ?? (($item['rate'] ?? $item['rate_inclusive'] ?? 0) - ($item['discount'] ?? 0)),
        2
    ) }}
                    </td>

                    {{-- PRICE INCLUDING GST --}}
                    <td class="right">
                        ₹ {{ number_format($item['amount_inclusive'] ?? 0, 2) }}
                    </td>
                </tr>

                @endforeach

            </tbody>

            <tfoot>

                {{-- TAXABLE AMOUNT --}}
                <tr>

                    <td colspan="7"></td>

                    <td class="summary-label">
                        Taxable amount
                    </td>

                    <td class="summary-value">
                        ₹ {{ number_format($calculation['subtotal'], 2) }}
                    </td>

                </tr>

                {{-- GST --}}
                <tr>

                    <td colspan="7"></td>

                    <td class="summary-label">
                        GST
                    </td>

                    <td class="summary-value">
                        ₹ {{ number_format($calculation['gst_total'], 2) }}
                    </td>

                </tr>

                {{-- AMOUNT PAYABLE --}}
                <tr>

                    <td colspan="7"></td>

                    <td class="total-label">
                        Amount Payable
                    </td>

                    <td class="total-value">
                        ₹ {{ number_format($calculation['grand_total'], 2) }}
                    </td>

                </tr>

            </tfoot>

        </table>


        {{-- ================= AMOUNT WORDS ================= --}}

        <div class="amount-words">

            <div class="amount-label">
                Amount Chargeable (in words)
            </div>

            <div class="words">
                {{ $calculation['grand_total_words'] }}
            </div>

            <div class="currency">
                E. &amp; O. E
            </div>

        </div>


        {{-- ================= PAYMENT + DECLARATION ================= --}}

        <table class="footer-table">

            <tr>

                <td class="footer-left">

                    <div class="payment-box">

                        <div class="payment-title">
                            Payment Method
                        </div>

                        <div class="payment-line">
                            {{ ucwords(str_replace('_', ' ', $order->c_mode_of_payment ?? '-')) }}
                        </div>

                        <br>

                        <div class="payment-line">
                            <strong>Bank Details:</strong>
                        </div>

                        <div class="payment-line">
                            {{ $company->account_name }}
                        </div>

                        <div class="payment-line">
                            A/c No: {{ $company->account_number }}
                        </div>

                        <div class="payment-line">
                            IFSC: {{ $company->ifsc_code }}
                        </div>

                        <div class="payment-line">
                            Bank: {{ $company->bank_name }}
                        </div>

                        @if($company->branch)
                        <div class="payment-line">
                            Branch: {{ $company->branch }}
                        </div>
                        @endif

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


        {{-- ================= FOOTER ================= --}}

        <div class="bottom">

            <div class="bottom-text">
                Pure Spices. Better Life.
            </div>

        </div>

    </div>

</body>

</html>