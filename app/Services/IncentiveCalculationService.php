<?php

namespace App\Services;

use App\Models\DailyStoreSale;
use App\Models\DesignationMaster;
use App\Models\EmployeeIncentive;
use App\Models\EmployeeMaster;
use App\Models\ProductIncentive;
use App\Models\StoreMaster;
use App\Models\EmployeeSalesDraft;
use Illuminate\Support\Facades\DB;
use Log;


class IncentiveCalculationService
{
    public function calculateSaleIncentives($saleId, $batchId = null)
    {
        $sale = DailyStoreSale::find($saleId);
        if (!$sale)
            return [];

        $storeId = $sale->n_store_id;
        if (!$storeId)
            return [];
        $store = StoreMaster::find($storeId);
    // Checks for store status is active or inactive
        if (!$store || $store->c_store_status !== 'Y') {
            Log::info('Incentive skipped: inactive store', [
                'store_id' => $storeId,
                'sale_id' => $saleId
            ]);
            return [];
        }

        $productIncentive = ProductIncentive::where('n_product_id', $sale->n_product_id)->first();
        if (!$productIncentive)
            return [];

        $baseAmount = $sale->total_margin_amount;
        $results = [];

        // =========================================================
        // DESIGNATION MAP
        // =========================================================
        $designationMap = [
            'CSA' => $productIncentive->n_customer_service_associate,
            'C&A' => $productIncentive->n_cash_accountant,
            'CA' => $productIncentive->n_cash_accountant,
            'SM' => $productIncentive->n_sales_manager,
            'CLUSTER' => $productIncentive->n_clustor_manager,
            'OPERATIONS' => $productIncentive->n_operations,
            'BM' => $productIncentive->n_bm_teams,
            'DC' => $productIncentive->n_dc_teams,
            'HO' => $productIncentive->n_head_office,
        ];

        // =========================================================
        // SALES EMPLOYEE
        // =========================================================
        $salesDesignation = null;

        if ($sale->n_employee_id) {
            $salesEmployee = EmployeeMaster::with('designation')->find($sale->n_employee_id);
            $salesDesignation = $salesEmployee && $salesEmployee->designation
                ? strtoupper(trim($salesEmployee->designation->c_designation))
                : null;
        }

        // =========================================================
        // 1. DIRECT INCENTIVE (COMMON LOGIC)
        // =========================================================

//    DB::beginTransaction();

// try {
//      $sale->n_slno;

// $sales = DailyStoreSale::lockForUpdate()
//     ->where('c_status', 'Y')
//     ->get();



    DB::beginTransaction();

    try {



$sale = DailyStoreSale::where('n_slno', $saleId)->first();

    //------------------------------------


      if ($salesDesignation && $sale->n_employee_id) {

            $csaRatio = $designationMap['CSA'] ?? 0;

            $designation = DesignationMaster::where('c_designation', $salesDesignation)->first();

            if ($designation) {

                // 1a. CSA Portion (Sales Maker Portion)
                if ($csaRatio > 0) {
                    $csaAmount = ($csaRatio / 100) * $baseAmount;


                    $inc1 = EmployeeIncentive::create([
                        'n_slno' => $sale->n_slno,
                        'n_employee_id' => $sale->n_employee_id,
                        'n_pool_percentage' => $csaRatio,
                        'c_pool_name' => 'CSA',
                        'n_base_amount' => $baseAmount,
                        'n_incentive_amount' => $csaAmount,
                        'd_date' => $sale->d_date,
                        'incentive_batch_id' => $batchId,
                    ]);

                    $this->depositInWallet($sale->n_employee_id, $csaAmount, $sale->n_slno, $inc1->id);


                    $results[] = [
                        'designation' => 'CSA (Sale Maker)',
                        'employee_id' => $sale->n_employee_id,
                        'amount' => $csaAmount,
                    ];
                }
            }
        }

        // =========================================================
            // 1B. STORE SPECIFIC C&A AND SM INCENTIVES
            // =========================================================

            foreach (['C&A', 'SM'] as $desigName) {

                $percentage = $designationMap[$desigName] ?? 0;

                if ($percentage <= 0) {
                    continue;
                }

                $designation = DesignationMaster::where('c_designation', $desigName)->first();

                if (!$designation) {
                    continue;
                }

                $amountForDesignation = ($percentage / 100) * $baseAmount;

                if ($amountForDesignation <= 0) {
                    continue;
                }

                // Only employees from the sale's store
                $employees = EmployeeMaster::where('n_designation_id', $designation->n_designation_id)
                    ->where('n_store_id', $storeId)
                    ->where('c_status', 'Y')
                    ->get();

                $count = $employees->count();

                if ($count <= 0) {
                    continue;
                }

                $amountPerEmployee = $amountForDesignation / $count;

                foreach ($employees as $emp) {

                    $newIncentive = EmployeeIncentive::create([
                        'n_slno'             => $sale->n_slno,
                        'n_employee_id'      => $emp->n_employee_id,
                        'n_pool_percentage'  => $percentage,
                        'c_pool_name'        => $desigName,
                        'n_base_amount'      => $baseAmount,
                        'n_incentive_amount' => $amountPerEmployee,
                        'd_date'             => $sale->d_date,
                        'incentive_batch_id' => $batchId,
                    ]);

                    $this->depositInWallet(
                        $emp->n_employee_id,
                        $amountPerEmployee,
                        $sale->n_slno,
                        $newIncentive->id
                    );

                    $results[] = [
                        'designation' => $desigName,
                        'employee_id' => $emp->n_employee_id,
                        'amount'      => $amountPerEmployee,
                    ];
                }
            }

        // =========================================================
        // 2. POOL INCENTIVES
        // =========================================================
        $allowedPools = ['CLUSTER', 'OPERATIONS', 'BM', 'DC', 'HO'];

        foreach ($allowedPools as $desigName) {

            $percentage = $designationMap[$desigName] ?? 0;
            if ($percentage <= 0)
                continue;

            $designation = DesignationMaster::where('c_designation', $desigName)->first();
            if (!$designation)
                continue;

            $amountForDesignation = ($percentage / 100) * $baseAmount;
            if ($amountForDesignation <= 0)
                continue;

            $employees = EmployeeMaster::with('clusters')
                ->where('n_designation_id', $designation->n_designation_id)
                ->where('c_status', 'Y')
                ->get();

            Log::info('employees', ['employees' => $employees]);

            $eligibleEmployees = $employees->filter(function ($emp) use ($storeId, $desigName) {
                if ($desigName === 'OPERATIONS') {
                    $clusterManagerId = \App\Models\StoreCluster::where('n_store_id', $storeId)->value('n_employee_id');
                    if ($clusterManagerId) {
                        return \App\Models\OperationCluster::where('n_cluster_manager_id', $clusterManagerId)
                            ->where('n_employee_id', $emp->n_employee_id)
                            ->exists();
                    }
                    return false;
                } elseif ($desigName === 'CLUSTER') {
                    return $emp->clusters->contains('n_store_id', $storeId);
                } elseif (isset($emp->n_pool_id) && $emp->n_pool_id == 0) {
                    return $emp->n_store_id == $storeId;
                } else {
                    return true;
                }
            });

            Log::info('eligible', ['eligibleEmployees' => $eligibleEmployees, 'desig' => $desigName]);

            // ======================================================
// CLUSTER FALLBACK TO OPERATIONS
// ======================================================

if ($desigName === 'CLUSTER' && $eligibleEmployees->count() == 0) {

    Log::info('Cluster inactive. Sending incentive to operations users');

    $eligibleEmployees = EmployeeMaster::with('clusters')
        ->whereHas('designation', function ($q) {
            $q->where('c_designation', 'OPERATIONS');
        })
        ->where('c_status', 'Y')
        ->get()
        ->filter(function ($emp) use ($storeId) {

            $clusterManagerId = \App\Models\StoreCluster::where('n_store_id', $storeId)
                ->value('n_employee_id');

            if (!$clusterManagerId) {
                return false;
            }

            return \App\Models\OperationCluster::where('n_cluster_manager_id', $clusterManagerId)
                ->where('n_employee_id', $emp->n_employee_id)
                ->exists();
        });

    // rename pool
    $desigName = 'OPERATIONS';
}
            //---------------------------
            $count = $eligibleEmployees->count();
            if ($count <= 0)
                continue;

            $amountPerEmployee = $amountForDesignation / $count;

            foreach ($eligibleEmployees as $emp) {

                $newIncentive = EmployeeIncentive::create([
                    'n_slno' => $sale->n_slno,
                    'n_employee_id' => $emp->n_employee_id,
                    'n_pool_percentage' => $percentage,
                    'c_pool_name' => $desigName,
                    'n_base_amount' => $baseAmount,
                    'n_incentive_amount' => $amountPerEmployee,
                    'd_date' => $sale->d_date,
                    'incentive_batch_id' => $batchId,
                ]);


                $this->depositInWallet($emp->n_employee_id, $amountPerEmployee, $sale->n_slno);

                $results[] = [
                    'designation' => $desigName,
                    'employee_id' => $emp->n_employee_id,
                    'amount' => $amountPerEmployee,
                ];
            }
        }

    // your processing logic here


$employeeId = $sale->n_employee_id;
 DB::commit();
 //return $results;
 return true; // ✅ success


} catch (\Exception $e) {

    DB::rollBack();

    Log::error('Incentive Processing Failed', [
        'sale_id' => $saleId,
        'error' => $e->getMessage()
    ]);

    return false; // ✅ IMPORTANT
}

    }
    /**
     * Calculate incentives for a store based on sales (Batch process)
     */


    public function calculateStoreIncentives($storeId, $dateRange = null)
    {
        $query = DailyStoreSale::where('n_store_id', $storeId);

        if ($dateRange && isset($dateRange['from']) && isset($dateRange['to'])) {
             $query->whereDate('d_date', '>=', $dateRange['from'])
            ->whereDate('d_date', '<=', $dateRange['to']);
        }

        $sales = $query->get();

        if ($sales->isEmpty()) {
            return ['status' => 'no_sales', 'message' => 'No sales found for this store'];
        }

        $totalSalesAmount = $sales->sum('n_sold_price');
        $saleIds = $sales->pluck('n_slno');

        // Distribution from EmployeeIncentive
        $incentives = EmployeeIncentive::with('employee.designation')
            ->whereIn('n_slno', $saleIds)
            ->get();

        $totalIncentivePool = $incentives->sum('n_incentive_amount');

        // Group results by pool name
        $poolGroups = $incentives->groupBy('c_pool_name');
        $distribution = [];

        foreach ($poolGroups as $poolName => $poolIncentives) {
            $poolNameKey = $poolName ?: 'OTHER';
            $percentage = $poolIncentives->first()->n_pool_percentage;

            $employeeIncentives = $poolIncentives->groupBy('n_employee_id')->map(function ($items) {
                $employee = $items->first()->employee;
                return [
                    'code' => $employee ? $employee->c_employee_code : 'N/A',
                    'name' => $employee ? $employee->c_employee_name : 'Unknown',
                    'designation' => $employee && $employee->designation ? $employee->designation->c_designation : 'Unknown',
                    'incentive' => $items->sum('n_incentive_amount'),
                ];
            })->values()->toArray();

            $distribution[$poolNameKey] = [
                'percentage' => $percentage,
                'total_amount' => $poolIncentives->sum('n_incentive_amount'),
                'employees' => $employeeIncentives,
            ];
        }

        return [
            'status' => 'success',
            'total_sales' => $totalSalesAmount,
            'incentive_pool' => $totalIncentivePool,
            'sale_count' => $sales->count(),
            'distribution' => $distribution,
            'message' => 'Incentives computed and grouped by pool.',
        ];
    }



    /**
     * Get summary statistics for incentives
     */
    public function getIncentivesSummary($storeId = null, $dateRange = null)
    {
        $salesQuery = DailyStoreSale::query();

        if ($storeId) {
            $salesQuery->where('n_store_id', $storeId);
        }

        if ($dateRange && isset($dateRange['from']) && isset($dateRange['to'])) {
            $salesQuery->whereBetween('d_date', [$dateRange['from'], $dateRange['to']]);
        }

        $sales = $salesQuery->get();
        $totalSales = $sales->sum('n_sold_price');
        $saleIds = $sales->pluck('n_slno');

        $incentives = EmployeeIncentive::with('employee.designation')
            ->whereIn('n_slno', $saleIds)
            ->get();

        $totalIncentivePool = $incentives->sum('n_incentive_amount');

        // Breakdown by pool name
        $breakdown = [];
        $groupedByPool = $incentives->groupBy('c_pool_name');

        foreach ($groupedByPool as $poolName => $items) {
            $breakdown[] = [
                'pool_name' => $poolName ?: 'OTHER',
                'percentage' => $items->first()->n_pool_percentage,
                'amount' => $items->sum('n_incentive_amount'),
            ];
        }

        return [
            'total_sales' => $totalSales,
            'total_incentive_pool' => $totalIncentivePool,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Helper to credit incentive amount to employee wallet balance.
     * No transaction row is written — deposits are derived from employee_incentives table.
     */
    private function depositInWallet($employeeId, $amount, $saleId)
    {
        $wallet = \App\Models\EmployeeWallet::firstOrCreate(
            ['n_employee_id' => $employeeId],
            ['n_balance' => 0]
        );

        $wallet->n_balance += $amount;
        $wallet->save();

        \App\Models\EmployeeWalletTransaction::create([
            'n_employee_id' => $employeeId,
            'n_wallet_id' => $wallet->id,
            'c_type' => 'DEPOSIT',
            'n_amount' => $amount,
            'c_status' => 'COMPLETED',
            'c_description' => "Incentive for Sale #" . $saleId,
            'n_reference_id' => $saleId,
            'c_reference_type' => EmployeeIncentive::class,
        ]);
    }
}
