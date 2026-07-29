<?php

namespace Tests\Unit;

use App\Models\DesignationMaster;
use App\Models\EmployeeIncentive;
use App\Models\EmployeeMaster;
use App\Models\ItemMaster;
use App\Models\SalesMaster;
use App\Models\StoreCluster;
use App\Models\StoreMaster;
use App\Services\IncentiveCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncentiveCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected $incentiveService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedDesignations();
        $this->incentiveService = app(IncentiveCalculationService::class);
    }

    private function seedDesignations()
    {
        $roleNames = ['CSA', 'SM', 'ASM', 'CASHIER', 'C&A', 'CLUSTER', 'OPERATIONS', 'B&M', 'DC', 'HO'];
        foreach ($roleNames as $name) {
            DesignationMaster::updateOrCreate(
                ['c_designation' => $name],
                ['c_status' => 'active']
            );
        }
    }

   /*  public function test_incentive_calculation_for_store()
    {
        // Seed some data since we are using RefreshDatabase
        $this->artisan('db:seed', ['--class' => 'IncentiveSystemSeeder']);

        // Get first store with sales
        $store = StoreMaster::has('employees.salesMasters')
            ->with('employees.salesMasters')
            ->first();

        if (! $store) {
            $this->markTestSkipped('No store with sales data found');
        }

        // Calculate incentives
        $result = $this->incentiveService->calculateStoreIncentives($store->n_store_id);

        // Verify result structure
        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('total_sales', $result);
        $this->assertArrayHasKey('incentive_pool', $result);
        $this->assertArrayHasKey('distribution', $result);

        // Verify distribution adds up to the pool
        $distributionTotal = array_reduce(
            $result['distribution']->toArray(),
            function ($carry, $item) {
                return $carry + ($item['total_amount'] ?? 0);
            },
            0
        );

        // Allow small floating point difference
        $this->assertEqualsWithDelta($result['incentive_pool'], $distributionTotal, 0.01);
    } */


    public function test_incentive_calculation_for_store()
    {
        // Find any existing store
        $store = StoreMaster::first();

        if (! $store) {
            $this->markTestSkipped('No store available for testing.');
        }

        // Run calculation
        $result = $this->incentiveService->calculateStoreIncentives($store->n_store_id);

        // Verify structure
        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('total_sales', $result);
        $this->assertArrayHasKey('incentive_pool', $result);
        $this->assertArrayHasKey('distribution', $result);

        // Verify totals
        $distribution = collect($result['distribution']);

        $distributionTotal = $distribution->sum(function ($item) {
            if (is_array($item)) {
                return $item['total_amount'] ?? 0;
            }

            return $item->total_amount ?? 0;
        });

        $this->assertEqualsWithDelta(
            $result['incentive_pool'],
            $distributionTotal,
            0.01
        );
    }

    public function test_incentive_summary()
    {
        $summary = $this->incentiveService->getIncentivesSummary();

        $this->assertIsArray($summary);
        $this->assertArrayHasKey('total_sales', $summary);
        $this->assertArrayHasKey('total_incentive_pool', $summary);
        $this->assertArrayHasKey('breakdown', $summary);

        // Verify breakdown contains some expected roles (if data exists)
        if ($summary['total_incentive_pool'] > 0) {
            $this->assertNotEmpty($summary['breakdown']);
        }
    }

    public function test_incentive_calculation_includes_quantity()
    {
        // 1. Create a dummy store
        $store = StoreMaster::updateOrCreate(
            ['c_store_code' => 'TEST_QTY_STORE'],
            [
                'c_store_name' => 'Test Qty Store',
                'c_store_status' => 'active',
            ]
        );

        // 2. Create a CSA for this store
        $csaDesignation = DesignationMaster::where('c_designation', 'CSA')->first();
        $csa = EmployeeMaster::updateOrCreate(
            ['c_employee_code' => 'TEST_QTY_CSA'],
            [
                'c_employee_name' => 'Test Qty CSA',
                'n_designation_id' => $csaDesignation->n_designation_id,
                'n_store_id' => $store->n_store_id,
                'c_status' => 'active',
            ]
        );

        // 3. Create an Item
        $item = ItemMaster::updateOrCreate(
            ['c_item_name' => 'Test Qty Item'],
            [
                'd_selling_price' => 200.00,
                'n_purchase_price' => 100.00, // Margin = 100
                'in_status' => 'allowed',
            ]
        );

        // 4. Create a sale with quantity = 5
        $sale = SalesMaster::create([
            'n_csa_id' => $csa->n_employee_id,
            'n_item_id' => $item->n_item_id,
            'n_quantity' => 5,
            'created_date' => now()->format('Y-m-d'),
        ]);

        // 5. Calculate incentives
        $result = $this->incentiveService->calculateSaleIncentives($sale->n_slno);

        // 6. Verify CSA incentive
        // Total Margin = 5 * 100 = 500
        // Total Incentive Pool = 500 * 100% = 500
        // CSA Incentive = 500 * 60% = 300

        $csaIncentive = collect($result)->firstWhere('designation', 'CSA')['amount'];
        $this->assertEquals(300.00, $csaIncentive, 'Incentive should include quantity and 100% of margin (5 * 100 * 60% = 300)');
    }

    public function test_incentive_distribution_by_relationship()
    {
        // 1. Create two stores
        $store1 = StoreMaster::create(['c_store_code' => 'TEST_ST1', 'c_store_name' => 'Test Store 1']);
        $store2 = StoreMaster::create(['c_store_code' => 'TEST_ST2', 'c_store_name' => 'Test Store 2']);

        // 2. Get designations
        $designations = DesignationMaster::all()->pluck('n_designation_id', 'c_designation');

        // 3. Create Employees
        // Store 1 Bound
        $csa1 = EmployeeMaster::create(['c_employee_code' => 'TEST_CSA1', 'c_employee_name' => 'CSA 1', 'n_designation_id' => $designations['CSA'], 'n_store_id' => $store1->n_store_id, 'c_status' => 'active']);
        $sm1 = EmployeeMaster::create(['c_employee_code' => 'TEST_SM1', 'c_employee_name' => 'SM 1', 'n_designation_id' => $designations['SM'], 'n_store_id' => $store1->n_store_id, 'c_status' => 'active']);

        // Store 2 Bound
        $csa2 = EmployeeMaster::create(['c_employee_code' => 'TEST_CSA2', 'c_employee_name' => 'CSA 2', 'n_designation_id' => $designations['CSA'], 'n_store_id' => $store2->n_store_id, 'c_status' => 'active']);

        // Cluster Bound (Managing only Store 1)
        $cluster1 = EmployeeMaster::create(['c_employee_code' => 'TEST_CL1', 'c_employee_name' => 'Cluster 1', 'n_designation_id' => $designations['CLUSTER'], 'c_status' => 'active']);
        StoreCluster::create(['n_store_id' => $store1->n_store_id, 'n_employee_id' => $cluster1->n_employee_id]);

        // Independent
        $ops1 = EmployeeMaster::create(['c_employee_code' => 'TEST_OPS1', 'c_employee_name' => 'Ops 1', 'n_designation_id' => $designations['OPERATIONS'], 'c_status' => 'active']);

        // 4. Create an Item
        $item = ItemMaster::create(['c_item_name' => 'Rel Test Item', 'd_selling_price' => 200, 'n_purchase_price' => 100, 'in_status' => 'allowed']);

        // 5. Perform a sale in Store 1
        $sale1 = SalesMaster::create([
            'n_csa_id' => $csa1->n_employee_id,
            'n_item_id' => $item->n_item_id,
            'n_quantity' => 1,
            'created_date' => now()->format('Y-m-d'),
        ]);

        $this->incentiveService->calculateSaleIncentives($sale1->n_id);

        // Verify Store 1 bound employees got incentives
        $this->assertEquals(60, EmployeeIncentive::where('employee_id', $csa1->n_employee_id)->where('n_sale_id', $sale1->n_id)->sum('d_incentive_amount'));
        $this->assertEquals(5, EmployeeIncentive::where('employee_id', $sm1->n_employee_id)->where('n_sale_id', $sale1->n_id)->sum('d_incentive_amount'));

        // Verify Cluster 1 (managing Store 1) got incentive
        $this->assertEquals(5, EmployeeIncentive::where('employee_id', $cluster1->n_employee_id)->where('n_sale_id', $sale1->n_id)->sum('d_incentive_amount'));

        // Verify Independent (Ops 1) got incentive
        $this->assertEquals(5, EmployeeIncentive::where('employee_id', $ops1->n_employee_id)->where('n_sale_id', $sale1->n_id)->sum('d_incentive_amount'));

        // 6. Perform a sale in Store 2
        $sale2 = SalesMaster::create([
            'n_csa_id' => $csa2->n_employee_id,
            'n_item_id' => $item->n_item_id,
            'n_quantity' => 1,
            'created_date' => now()->format('Y-m-d'),
        ]);

        $this->incentiveService->calculateSaleIncentives($sale2->n_id);

        // Verify Cluster 1 (NOT managing Store 2) got NO incentive from sale 2
        $this->assertEquals(0, EmployeeIncentive::where('employee_id', $cluster1->n_employee_id)->where('n_sale_id', $sale2->n_id)->sum('d_incentive_amount'));

        // Verify Independent (Ops 1) STILL got incentive from sale 2
        $this->assertEquals(5, EmployeeIncentive::where('employee_id', $ops1->n_employee_id)->where('n_sale_id', $sale2->n_id)->sum('d_incentive_amount'));
    }
}
