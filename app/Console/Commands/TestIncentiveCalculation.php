<?php

namespace App\Console\Commands;

use App\Models\StoreMaster;
use App\Services\IncentiveCalculationService;
use Illuminate\Console\Command;

class TestIncentiveCalculation extends Command
{
    protected $signature = 'incentive:test {store_id?}';

    protected $description = 'Test incentive calculation for a store';

    public function handle()
    {
        $incentiveService = app(IncentiveCalculationService::class);

        $storeId = $this->argument('store_id');

        if (! $storeId) {
            // Get first store with sales
            $store = StoreMaster::has('employees.salesMasters')
                ->with('employees.salesMasters')
                ->first();

            if (! $store) {
                $this->error('No store with sales found');

                return 1;
            }

            $storeId = $store->n_store_id;
        }

        $this->info("Calculating incentives for store ID: {$storeId}");

        try {
            $result = $incentiveService->calculateStoreIncentives($storeId);

            if ($result['status'] === 'success') {
                $this->info('✓ Calculation successful!');
                $this->line('');
                $this->line('RESULTS:');
                $this->table(
                    ['Metric', 'Amount'],
                    [
                        ['Total Sales', '₹'.number_format($result['total_sales'], 2)],
                        ['Incentive Pool (20%)', '₹'.number_format($result['incentive_pool'], 2)],
                        ['Sales Count', $result['sale_count']],
                    ]
                );

                $this->line('');
                $this->line('DISTRIBUTION BREAKDOWN:');

                foreach ($result['distribution'] as $poolName => $dist) {
                    $this->line('');
                    $this->info(strtoupper(str_replace('_', ' ', $poolName)));

                    if (isset($dist['total_amount'])) {
                        $this->line('  Total: ₹'.number_format($dist['total_amount'], 2));
                        $this->line('  Employees: '.$dist['employee_count']);
                        $this->line('  Per Employee: ₹'.number_format($dist['per_employee'], 2));

                        if (isset($dist['employees']) && count($dist['employees']) > 0) {
                            foreach ($dist['employees'] as $emp) {
                                $this->line("    - {$emp['code']} ({$emp['name']}): ₹".number_format($emp['incentive'], 2));
                            }
                        }
                    } else {
                        $this->line('  Amount: ₹'.number_format($dist['amount'], 2));
                        $this->line('  Note: '.($dist['note'] ?? ''));
                    }
                }

                $this->line('');
                $this->info('✓ Incentive calculation completed successfully!');

                return 0;
            } else {
                $this->warn($result['message'] ?? 'Calculation failed');

                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());

            return 1;
        }
    }
}
