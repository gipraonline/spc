<?php

namespace App\Services;

class InvoiceCalculationService
{
    public function calculate($order)
    {
        $subtotal = 0;
        $taxableTotal = 0;
        $totalQty = 0;
        $gstTotal = 0;
        $grandTotal = 0;
        $totalDiscount = 0;

        $cgstTotal = 0;
        $sgstTotal = 0;
        $igstTotal = 0;

        $items = [];

        /*
        |--------------------------------------------------------------------------
        | Customer State
        |--------------------------------------------------------------------------
        |
        | We need the customer's state to decide:
        |
        | Kerala customer:
        |     CGST 2.5%
        |     SGST 2.5%
        |     IGST 0%
        |
        | Outside Kerala:
        |     CGST 0%
        |     SGST 0%
        |     IGST 5%
        |
        */

        $customerState = trim(
            $order->customer?->c_state
            ?? $order->customer?->state
            ?? $order->c_customer_state
            ?? ''
        );

        $isKerala = strtolower($customerState) === 'kerala';

        foreach ($order->orderProducts as $item) {

            // =========================
            // Quantity
            // =========================

            $qty = (float) ($item->qty ?? 0);

            // =========================
            // Price
            // =========================
            //
            // IMPORTANT:
            // product_price is now treated as
            // PRICE EXCLUDING GST.
            //

            $price = (float) ($item->product_price ?? 0);

            // =========================
            // GST %
            // =========================

            $gstPercentage = (float) (
                $item->product?->n_gst_percentage ?? 0
            );

            // =========================
            // Discount
            // =========================
            //
            // Default discount = 0
            //
            // Discount is treated as LINE discount.
            //

            $discount = (float) ($item->discount ?? 0);

            // =========================
            // Gross Amount
            // =========================
            //
            // Price × Quantity
            //

            $grossAmount = $price * $qty;

            // =========================
            // Discounted Amount
            // =========================
            //
            // This becomes the taxable amount.
            //

            $discountedAmount = $grossAmount - $discount;

            if ($discountedAmount < 0) {
                $discountedAmount = 0;
            }

            // =========================
            // GST Calculation
            // =========================
            //
            // GST is calculated AFTER discount.
            //

            $gstAmount =
                $discountedAmount * ($gstPercentage / 100);

            // =========================
            // CGST / SGST / IGST
            // =========================

            $cgstRate = 0;
            $sgstRate = 0;
            $igstRate = 0;

            $cgstAmount = 0;
            $sgstAmount = 0;
            $igstAmount = 0;

            if ($isKerala) {

                // Kerala customer
                //
                // 5% GST =
                // CGST 2.5%
                // SGST 2.5%

                $cgstRate = $gstPercentage / 2;
                $sgstRate = $gstPercentage / 2;

                $cgstAmount =
                    $discountedAmount * ($cgstRate / 100);

                $sgstAmount =
                    $discountedAmount * ($sgstRate / 100);

            } else {

                // Outside Kerala customer
                //
                // Full GST as IGST

                $igstRate = $gstPercentage;

                $igstAmount =
                    $discountedAmount * ($igstRate / 100);
            }

            // =========================
            // Total GST
            // =========================

            $gstAmount =
                $cgstAmount
                + $sgstAmount
                + $igstAmount;

            // =========================
            // Final Amount
            // =========================
            //
            // Taxable amount + GST
            //

            $amountInclusive =
                $discountedAmount + $gstAmount;

            // =========================
            // Running Totals
            // =========================

            $subtotal += $grossAmount;

            $taxableTotal += $discountedAmount;

            $gstTotal += $gstAmount;

            $cgstTotal += $cgstAmount;

            $sgstTotal += $sgstAmount;

            $igstTotal += $igstAmount;

            $totalDiscount += $discount;

            $totalQty += $qty;

            $grandTotal += $amountInclusive;

            // =========================
            // Store Item Calculation
            // =========================

            $items[] = [

                'product_name' => $item->product?->c_product_name
                    ?? 'Product',

                'hsn' => $item->product?->c_hsn_code
                    ?? '-',

                'qty' => $qty,

                'unit' => $item->product?->c_unit
                    ?? '-',

                // GST percentage
                'gst_percentage' => $gstPercentage,

                // Price EXCLUDING GST
                'rate' => $price,

                'rate_exclusive' => $price,

                // Kept for compatibility
                'rate_inclusive' => $price,

                // Discount defaults to 0
                'discount' => $discount,

                // Price × Qty - Discount
                'discounted_price' => $discountedAmount,

                // Same as discounted price
                // because GST is calculated on this
                'taxable_amount' => $discountedAmount,

                // GST split
                'cgst_rate' => $cgstRate,

                'cgst_amount' => $cgstAmount,

                'sgst_rate' => $sgstRate,

                'sgst_amount' => $sgstAmount,

                'igst_rate' => $igstRate,

                'igst_amount' => $igstAmount,

                // Total GST
                'gst_amount' => $gstAmount,

                // Taxable + GST
                'amount_inclusive' => $amountInclusive,
            ];
        }

        // =========================
        // Amount in Words
        // =========================

        $grandTotalWords =
            $this->numberToWords($grandTotal);

        // =========================
        // Return Calculation
        // =========================

        return [

            'items' => $items,

            // Gross amount before discount
            'subtotal' => $subtotal,

            // Actual taxable amount after discount
            'taxable_total' => $taxableTotal,

            'total_qty' => $totalQty,

            'gst_total' => $gstTotal,

            'cgst_total' => $cgstTotal,

            'sgst_total' => $sgstTotal,

            'igst_total' => $igstTotal,

            'total_discount' => $totalDiscount,

            'grand_total' => $grandTotal,

            'grand_total_words' => $grandTotalWords,

            'customer_state' => $customerState,

            'is_kerala' => $isKerala,
        ];
    }

    /**
     * Convert number to Indian currency words.
     */
    private function numberToWords($amount)
    {
        $amount = round((float) $amount, 2);

        $rupees = (int) floor($amount);

        $paise = (int) round(
            ($amount - $rupees) * 100
        );

        $rupeeWords =
            $this->convertIndianNumberToWords($rupees);

        $result = '';

        if ($rupees > 0) {

            $result .=
                $rupeeWords.' Rupees';

        } else {

            $result .=
                'Zero Rupees';
        }

        if ($paise > 0) {

            $paiseWords =
                $this->convertIndianNumberToWords($paise);

            $result .=
                ' and '.$paiseWords.' Paise';
        }

        return $result.' Only';
    }

    /**
     * Convert Indian number system.
     */
    private function convertIndianNumberToWords($number)
    {
        $number = (int) $number;

        if ($number === 0) {
            return 'Zero';
        }

        $ones = [

            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
        ];

        $tens = [

            2 => 'Twenty',
            3 => 'Thirty',
            4 => 'Forty',
            5 => 'Fifty',
            6 => 'Sixty',
            7 => 'Seventy',
            8 => 'Eighty',
            9 => 'Ninety',
        ];

        $words = '';

        // Crore
        if ($number >= 10000000) {

            $crore =
                intdiv($number, 10000000);

            $words .=
                $this->convertIndianNumberToWords($crore)
                .' Crore ';

            $number %= 10000000;
        }

        // Lakh
        if ($number >= 100000) {

            $lakh =
                intdiv($number, 100000);

            $words .=
                $this->convertIndianNumberToWords($lakh)
                .' Lakh ';

            $number %= 100000;
        }

        // Thousand
        if ($number >= 1000) {

            $thousand =
                intdiv($number, 1000);

            $words .=
                $this->convertIndianNumberToWords($thousand)
                .' Thousand ';

            $number %= 1000;
        }

        // Hundred
        if ($number >= 100) {

            $hundred =
                intdiv($number, 100);

            $words .=
                $ones[$hundred].' Hundred ';

            $number %= 100;
        }

        // 1 - 19
        if ($number > 0 && $number < 20) {

            $words .=
                $ones[$number];

        }

        // 20 - 99
        elseif ($number >= 20) {

            $ten =
                intdiv($number, 10);

            $one =
                $number % 10;

            $words .=
                $tens[$ten];

            if ($one > 0) {

                $words .=
                    ' '.$ones[$one];
            }
        }

        return trim($words);
    }
}
