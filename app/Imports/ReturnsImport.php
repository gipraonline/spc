<?php

namespace App\Imports;

use App\Models\ReturnSaleDraft;
use App\Models\StoreMaster;
use App\Models\ProductMaster;
use App\Models\AdminSaleUpload;
use App\Models\ReturnDraftUpload;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReturnsImport implements ToCollection, WithHeadingRow
{
    public function __construct(public string $batchId)
    {
    }

    public function collection(Collection $rows)
    {
        // Preload lookups
        $stores = StoreMaster::pluck('n_store_id', 'c_store_code');
        $products = ProductMaster::pluck('n_product_id', 'c_product_code');

        $seenReturns = [];

        // Existing returns in return_draft_uploads (already confirmed returns)
        $existingReturns = ReturnDraftUpload::select('c_bill_no', 'n_product_id')
            ->get()
            ->mapWithKeys(function ($r) {
                $key = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $r->c_bill_no)) . '|' . $r->n_product_id;
                return [$key => true];
            })->toArray();

        foreach ($rows as $row) {
            // SKIP EMPTY ROWS
            if (
                $this->isBlank($row['c_store_code'] ?? null) &&
                $this->isBlank($row['c_item_code'] ?? null) &&
                $this->isBlank($row['c_billno'] ?? null) &&
                $this->isBlank($row['d_date'] ?? null) &&
                $this->isBlank($row['n_quantity'] ?? null)
            ) {
                continue;
            }

            $errors = [];

            // ── Date validation ──
            $date = $row['d_date'] ?? null;
            if (is_numeric($date)) {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)
                    ->format('Y-m-d');
            }
            if (!$date) {
                $errors[] = 'Date required';
            }

            // ── Store validation ──
            $rawStoreCode = $row['c_store_code'] ?? '';
            $normalizedStoreCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $rawStoreCode));

            if (!isset($stores[$normalizedStoreCode])) {
                $errors[] = "Invalid store";
            }

            // ── Quantity validation ──
            $quantity = $row['n_quantity'] ?? null;
            $quantityStr = trim((string) $quantity);

            if ($quantityStr === '') {
                $errors[] = "Quantity is required";
            } elseif (!is_numeric($quantityStr)) {
                $errors[] = "Quantity must be a number (given: $quantity)";
            } elseif ((float) $quantityStr < 0) {
                $errors[] = "Quantity cannot be negative (given: $quantity)";
            } elseif (floor((float) $quantityStr) != (float) $quantityStr) {
                $errors[] = "Quantity must be an integer (given: $quantity)";
            } elseif ((int) $quantityStr === 0) {
                $errors[] = "Quantity must be greater than 0";
            }

            // ── Product validation ──
            $rawItemCode = $row['c_item_code'] ?? '';
            $normalizedItemCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $rawItemCode));

            if (!isset($products[$normalizedItemCode])) {
                $errors[] = "Invalid product";
            }

            // ── Bill number validation ──
            $rawBillNo = $row['c_billno'] ?? null;
            $normalizedBillNo = $rawBillNo !== null
                ? strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $rawBillNo))
                : null;

            if (empty($normalizedBillNo)) {
                $errors[] = "Bill number required";
            }

            // ── Return-specific validations ──
            $resolvedStoreId = $stores[$normalizedStoreCode] ?? null;
            $resolvedProductId = $products[$normalizedItemCode] ?? null;
            $returnQty = is_numeric($quantityStr) ? (int) $quantityStr : 0;

            if (!empty($normalizedBillNo) && $resolvedStoreId && $resolvedProductId) {
                // 1. Check if the bill+product exists in admin_sale_uploads (verified sales)
                $originalSale = AdminSaleUpload::where('c_bill_no', $normalizedBillNo)
                    ->where('n_store_id', $resolvedStoreId)
                    ->where('n_product_id', $resolvedProductId)
                    ->first();

                if (!$originalSale) {
                    $errors[] = "No matching sale found for this bill/product in confirmed sales";
                } else {
                    // 2. Check return qty <= original sale qty
                    if ($returnQty > $originalSale->n_quantity) {
                        $errors[] = "Return qty ($returnQty) exceeds original sale qty ($originalSale->n_quantity)";
                    }
                }

                // 3. Check duplicate return (already confirmed)
                $returnKey = $normalizedBillNo . '|' . $resolvedProductId;
                if (isset($existingReturns[$returnKey])) {
                    $errors[] = "Return already exists for this bill/product";
                }

                // 4. Check duplicate within current upload
                if (isset($seenReturns[$returnKey])) {
                    $errors[] = "Duplicate return in current upload";
                }
                $seenReturns[$returnKey] = true;
            }

            $qtyVal = (int) ($row['n_quantity'] ?? 1);

            ReturnSaleDraft::create([
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

        $value = (string) $value;
        $value = str_replace("\xc2\xa0", ' ', $value);
        $value = trim($value);

        if ($value === '' || $value === '-') {
            return true;
        }

        if (is_numeric($value)) {
            return (float) $value == 0;
        }

        return false;
    }
}