<?php

namespace Database\Seeders;

use App\Services\IncentiveCalculationService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. admins
        DB::table('admins')->insert([
            ['n_role_id' => 1, 'c_role' => 'Super Admin', 'c_username' => 'superadmin@centralbazar.com', 'c_password' => Hash::make('Admin@12345'), 'c_status' => 'Y'],
            ['n_role_id' => 2, 'c_role' => 'Admin', 'c_username' => 'admin@centralbazar.com', 'c_password' => Hash::make('Admin@12345'), 'c_status' => 'Y'],
        ]);

        // 2. pool_masters
        DB::table('pool_masters')->insert([
            ['n_pool_id' => 1, 'c_pool_name' => 'Head Office', 'n_upline_id' => -1, 'n_bm_teams_id' => 0, 'n_dc_teams_id' => 0, 'n_head_office_id' => 0],
            ['n_pool_id' => 2, 'c_pool_name' => 'DC Teams', 'n_upline_id' => 1, 'n_bm_teams_id' => 0, 'n_dc_teams_id' => 0, 'n_head_office_id' => 1],
            ['n_pool_id' => 3, 'c_pool_name' => 'BM Teams', 'n_upline_id' => 2, 'n_bm_teams_id' => 0, 'n_dc_teams_id' => 2, 'n_head_office_id' => 0],
            ['n_pool_id' => 4, 'c_pool_name' => 'Centrial Bazaar Operations', 'n_upline_id' => 3, 'n_bm_teams_id' => 3, 'n_dc_teams_id' => 0, 'n_head_office_id' => 0],
            ['n_pool_id' => 5, 'c_pool_name' => 'Vanitham Operations', 'n_upline_id' => 3, 'n_bm_teams_id' => 3, 'n_dc_teams_id' => 0, 'n_head_office_id' => 0],
        ]);

        // 3. store_masters
        DB::table('store_masters')->insert([
            [
                'n_store_id' => 1,
                'c_store_code' => 'CB001',
                'n_clustor_manager_id' => 4,
                'c_store_name' => 'Centrial Bazaar',
                'c_store_address' => 'Puthiyakavu',
                'c_store_email' => null,
                'n_store_phone' => null,
                'c_store_status' => 'Y',
            ],
        ]);

        // 4. designation_masters
        DB::table('designation_masters')->insert([
            ['n_designation_id' => 1, 'c_designation' => 'CSA', 'c_status' => 'Y'],
            ['n_designation_id' => 2, 'c_designation' => 'C&A', 'c_status' => 'Y'],
            ['n_designation_id' => 3, 'c_designation' => 'SM', 'c_status' => 'Y'],
            ['n_designation_id' => 4, 'c_designation' => 'CLUSTER', 'c_status' => 'Y'],
            ['n_designation_id' => 5, 'c_designation' => 'OPERATIONS', 'c_status' => 'Y'],
            ['n_designation_id' => 6, 'c_designation' => 'BM', 'c_status' => 'Y'],
            ['n_designation_id' => 7, 'c_designation' => 'DC', 'c_status' => 'Y'],
            ['n_designation_id' => 8, 'c_designation' => 'HO', 'c_status' => 'Y'],
        ]);

        // 5. employee_masters
        $employees = [
            [1, 'EMP001', 'Kamash', 'xxxxx', 1, 1, 0, 0],
            [2, 'EMP002', 'Sami', 'xxxxx', 2, 1, 0, 0],
            [3, 'EMP003', 'Vineetha', 'xxxxx', 3, 1, 0, 0],
            [4, 'EMP004', 'Sruthi', 'xxxxx', 4, 1, 4, 0],
            [5, 'EMP005', 'Radhika', 'xxxxx', 5, 0, 0, 4],
            [6, 'EMP006', 'Suma', 'xxxxx', 5, 0, 1, 4],
            [7, 'EMP007', 'Vinoop', 'xxxxx', 5, 0, 0, 4],
            [8, 'EMP008', 'Suresh', 'xxxxx', 6, 0, 0, 3],
            [9, 'EMP009', 'Remesh', 'xxxxx', 6, 0, 0, 3],
            [10, 'EMP010', 'Vipin', 'xxxxx', 7, 0, 0, 2],
            [11, 'EMP011', 'Sathya', 'xxxxx', 7, 0, 0, 2],
            [12, 'EMP012', 'Sanju', 'xxxxx', 8, 0, 0, 1],
            [13, 'EMP013', 'Aneesh', 'xxxxx', 8, 0, 0, 1],
        ];

        foreach ($employees as $emp) {
            DB::table('employee_masters')->insert([
                'n_employee_id' => $emp[0],
                'c_employee_code' => $emp[1],
                'c_username' => strtolower($emp[2]) . '@centralbazar.com', // Dummy fallback
                'c_password' => Hash::make('Password@123'),
                'c_employee_name' => $emp[2],
                'c_employee_address' => $emp[3],
                'c_employee_email' => strtolower($emp[2]) . '@centralbazar.com',
                'n_employee_phone' => null,
                'n_designation_id' => $emp[4],
                'n_store_id' => $emp[5],
                'n_operations_poolid' => $emp[6],
                'n_pool_id' => $emp[7],
                'c_status' => 'Y',
            ]);
        }

        // 6. product_masters
        DB::table('product_masters')->insert([
            ['n_product_id' => 1, 'c_product_code' => '11014744', 'c_product_name' => 'Voltas AC', 'n_purchase_price' => 20000, 'n_selling_price' => 45000, 'c_status' => 'Y'],
            ['n_product_id' => 2, 'c_product_code' => '11001791', 'c_product_name' => 'Samsung Fridge', 'n_purchase_price' => 10000, 'n_selling_price' => 15000, 'c_status' => 'Y'],
            ['n_product_id' => 3, 'c_product_code' => '11000792', 'c_product_name' => 'Samsung Vacum Cleaner', 'n_purchase_price' => 2500, 'n_selling_price' => 3500, 'c_status' => 'Y'],
        ]);

        // 7. product_incentives
        for ($i = 1; $i <= 10; $i++) {
            DB::table('product_incentives')->insert([
                'n_product_id' => $i,
                'n_customer_service_associate' => 60,
                'n_cash_accountant' => 5,
                'n_sales_manager' => 10,
                'n_clustor_manager' => 5,
                'n_operations' => 3,
                'n_bm_teams' => 7,
                'n_dc_teams' => 5,
                'n_head_office' => 5,
            ]);
        }
        // 8. store_clusters (Linking Cluster Manager to Store)
        DB::table('store_clusters')->insert([
            ['n_employee_id' => 4, 'n_store_id' => 1]
        ]);
        // 9. daily_store_sales
        DB::table('daily_store_sales')->insert([
            [
                'n_slno' => 1,
                'd_date' => Carbon::create(2026, 3, 20)->format('Y-m-d'),
                'n_store_id' => 1,
                'n_employee_id' => 1,
                'n_product_id' => 1,
                'c_bill_no' => '10023',
                'd_bill_date' => Carbon::create(2026, 3, 20)->format('Y-m-d'),
                'n_sold_price' => 30000,
                'n_quantity' => 1,
                'c_approve' => 'N',
                'c_status' => 'Y',
            ],
        ]);

        // 10. calculate incentives for seeded sales
        $service = app(\App\Services\IncentiveCalculationService::class);
        $service->calculateSaleIncentives(1);
        // 11. Administration Menus
        $this->call([
            AdministrationMenuSeeder::class,
        ]);
    }
}