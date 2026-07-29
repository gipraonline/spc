<?php

namespace App\Imports;

use App\Models\AdminSaleDraft;
use App\Models\StoreMaster;
use App\Models\ProductMaster;
use App\Models\AdminSaleUnnormalizedLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesImport implements ToCollection, WithHeadingRow
{
    public function __construct(public string $batchId)
    {
    }

    public function collection(Collection $rows)
    {

        // Preload (performance 🔥)
        $stores = StoreMaster::pluck('n_store_id', 'c_store_code');
        $products = ProductMaster::pluck('n_product_id', 'c_product_code');

        // ✅ MUST exist
        $seenBills = [];

        // Only consider bills from other batches
        $existingBills = AdminSaleDraft::pluck('c_billno')
            ->mapWithKeys(function ($bill) {
                $normalized = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $bill));
                return [$normalized => true];
            })->toArray();


        foreach ($rows as $row) {
            //  SKIP EMPTY ROWS FIRST
            if (
                $this->isBlank($row['c_store_code'] ?? null) &&
                $this->isBlank($row['c_item_code'] ?? null) &&
                $this->isBlank($row['c_billno'] ?? null) &&
                $this->isBlank($row['d_date'] ?? null) &&
                $this->isBlank($row['n_quantity'] ?? null)
            ) {
                continue;
            }


            // 1. Log raw unnormalized data for tracing
            AdminSaleUnnormalizedLog::create([
                'n_batch_id' => $this->batchId,
                'd_date' => $row['d_date'] ?? null,
                'c_store_code' => $row['c_store_code'] ?? null,
                'c_billno' => $row['c_billno'] ?? null,
                'c_item_code' => $row['c_item_code'] ?? null,
                'n_quantity' => $row['n_quantity'] ?? 1,
            ]);

            $errors = [];

            $date = $row['d_date'] ?? null;

            // Excel date handling
            if (is_numeric($date)) {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)
                    ->format('Y-m-d');
            }
            if (!$date) {
                $errors[] = 'Date required';
            }

            // $sellingPrice = $row['n_selling_price'] ?? '';
            // if (!is_numeric($sellingPrice)) {
            //     $errors[] = "Selling price must be a number";
            // }

            // $buyingPrice = $row['buying_rate'] ?? '';
            // if (!is_numeric($buyingPrice)) {
            //     $errors[] = "Buying price must be a number";
            // }

            // if (is_numeric($sellingPrice) && is_numeric($buyingPrice)) {
            //     if (($sellingPrice - $buyingPrice) < 0) {
            //         $errors[] = "Selling price cannot be less than buying price";
            //     }
            // }

            // Normalization: Uppercase, No Whitespace, No Special Characters
            $rawStoreCode = $row['c_store_code'] ?? '';
            $normalizedStoreCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $rawStoreCode));

            if (!isset($stores[$normalizedStoreCode])) {
                $errors[] = "Invalid store";
            }

            $quantity = $row['n_quantity'] ?? null;
            $quantityStr = trim((string) $quantity);

            //  Empty check
            if ($quantityStr === '') {
                $errors[] = "Quantity is required";
            }

            //  Not numeric (catches strings like abc)
            elseif (!is_numeric($quantityStr)) {
                $errors[] = "Quantity must be a number (given: $quantity)";
            }

            // Negative values
            elseif ((float) $quantityStr < 0) {
                $errors[] = "Quantity cannot be negative (given: $quantity)";
            }

            // Not integer (decimal check)
            elseif (floor((float) $quantityStr) != (float) $quantityStr) {
                $errors[] = "Quantity must be an integer (given: $quantity)";
            }

            // Zero check
            elseif ((int) $quantityStr === 0) {
                $errors[] = "Quantity must be greater than 0";
            }


            $rawItemCode = $row['c_item_code'] ?? '';
            $normalizedItemCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $rawItemCode));


            if (!isset($products[$normalizedItemCode])) {
                $errors[] = "Invalid product";
            }

            $rawBillNo = $row['c_billno'] ?? null;
            $normalizedBillNo = $rawBillNo !== null
                ? strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $rawBillNo))
                : null;


            if (empty($normalizedBillNo)) {
                $errors[] = "Bill number required";
            } else {
                /* if (isset($seenBills[$normalizedBillNo]) || isset($existingBills[$normalizedBillNo])) {
                    $errors[] = "Duplicate Bill number";
                } */

                $key = $normalizedBillNo . '|' . $normalizedItemCode;

                    if (isset($seenBillProducts[$key])) {
                        $errors[] = "Duplicate Bill/Product combination";
                    } else {
                        $seenBillProducts[$key] = true;
                    }


                // Mark this bill as seen for current file only
                $seenBills[$normalizedBillNo] = true;
            }

            $qtyVal = (int) ($row['n_quantity'] ?? 1);

            AdminSaleDraft::create([
                'n_batch_id' => $this->batchId,
                'd_date' => $date,
                'c_store_code' => $normalizedStoreCode,
                'c_billno' => $normalizedBillNo,
                'c_item_code' => $normalizedItemCode,
                'n_quantity' => $qtyVal,
                'c_status' => empty($errors) ? 'valid' : 'error',
                'c_validation_message' => implode(', ', $errors),
            ]);
        }
    }

    private function isBlank($value): bool
    {
        if (is_null($value)) {
            return true;
        }

        // Convert to string and normalize
        $value = (string) $value;

        // Remove hidden spaces (Excel issue)
        $value = str_replace("\xc2\xa0", ' ', $value);

        // Trim spaces
        $value = trim($value);

        // Handle common "fake empty" values
        if ($value === '' || $value === '-') {
            return true;
        }

        // Handle numeric cases (0, 0.00, "0")
        if (is_numeric($value)) {
            return (float) $value == 0;
        }

        return false;
    }

}
