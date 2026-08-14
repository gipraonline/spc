<?php

namespace App\Services;

class InvoiceCalculationService
{
    public function calculate($order)
    {
        $subtotal = 0;
        $totalQty = 0;
        $gstTotal = 0;
        $grandTotal = 0;
        $totalDiscount = 0;

        $items = [];

        foreach ($order->orderProducts as $item) {

            // =========================
            // Quantity
            // =========================

            $qty = (float) ($item->qty ?? 0);

            // =========================
            // Product price
            // =========================
            // Same as JS:
            // price * qty

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
            // Same as current JS:
            // grossAmount - discount
            //
            // IMPORTANT:
            // discount is treated as LINE discount,
            // not per-unit discount.

            $discount = (float) ($item->discount ?? 0);

            // =========================
            // Gross amount
            // =========================

            $grossAmount = $price * $qty;

            // =========================
            // Taxable amount
            // =========================

            $taxableAmount = $grossAmount - $discount;

            if ($taxableAmount < 0) {
                $taxableAmount = 0;
            }

            // =========================
            // GST amount
            // =========================

            $gstAmount =
                $taxableAmount * ($gstPercentage / 100);

            // =========================
            // Final amount including GST
            // =========================

            $amountInclusive =
                $taxableAmount + $gstAmount;

            // =========================
            // Running totals
            // =========================

            $subtotal += $grossAmount;

            $gstTotal += $gstAmount;

            $totalDiscount += $discount;

            $totalQty += $qty;

            $grandTotal += $amountInclusive;

            // =========================
            // Store item calculation
            // =========================

            $items[] = [

                'product_name' => $item->product?->c_product_name ?? 'Product',

                'hsn' => $item->product?->c_hsn_code ?? '-',

                'qty' => $qty,

                'unit' => $item->product?->c_unit ?? '-',

                'gst_percentage' => $gstPercentage,

                'rate_inclusive' => $price,

                'rate_exclusive' => $price,

                'discount' => $discount,

                'discounted_price' => $taxableAmount,

                'taxable_amount' => $taxableAmount,

                'gst_amount' => $gstAmount,

                'amount_inclusive' => $amountInclusive,
            ];
        }

        // =========================
        // Amount in words
        // =========================

        $grandTotalWords =
            $this->numberToWords($grandTotal);

        return [

            'items' => $items,

            'subtotal' => $subtotal,

            'total_qty' => $totalQty,

            'gst_total' => $gstTotal,

            'total_discount' => $totalDiscount,

            'grand_total' => $grandTotal,

            'grand_total_words' => $grandTotalWords,
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