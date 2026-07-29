<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [

            ['name'=>'Dashboard','route_name'=>'dashboard','icon'=>'layout-dashboard','sort_order'=>1],

            ['name'=>'Designations','route_name'=>'admin.designations.index','icon'=>'award','sort_order'=>2],

            ['name'=>'Stores','route_name'=>'admin.stores.index','icon'=>'store','sort_order'=>3],

            ['name'=>'Employees','route_name'=>'admin.employees.index','icon'=>'users','sort_order'=>4],

            ['name'=>'Products','route_name'=>'admin.products.index','icon'=>'package','sort_order'=>5],

            ['name'=>'Bulk Upload','route_name'=>'admin.sales.bulk-upload','icon'=>'upload-cloud','sort_order'=>6],

            ['name'=>'Draft Sales','route_name'=>'admin.sales.drafts','icon'=>'file-edit','sort_order'=>7],

            ['name'=>'Sales Report','route_name'=>'admin.sales.uploads.report','icon'=>'line-chart','sort_order'=>8],

            ['name'=>'Verified Sales','route_name'=>'sales.report','icon'=>'file-check','sort_order'=>9],

            ['name'=>'Sale Returns','route_name'=>'admin.sales.returns-report','icon'=>'rotate-ccw','sort_order'=>10],

            ['name'=>'Return Upload','route_name'=>'admin.returns.bulk-upload','icon'=>'upload','sort_order'=>11],

            ['name'=>'Return Drafts','route_name'=>'admin.returns.drafts','icon'=>'file-minus','sort_order'=>12],

            ['name'=>'Incentive Batches','route_name'=>'admin.incentives.batch','icon'=>'bar-chart-3','sort_order'=>13],

            ['name'=>'Payouts','route_name'=>'admin.withdrawals.index','icon'=>'banknote-arrow-down','sort_order'=>14],

            ['name'=>'Payout Reports','route_name'=>'admin.payout-reports.index','icon'=>'receipt','sort_order'=>15],

            ['name'=>'KYC Submissions','route_name'=>'admin.kyc.index','icon'=>'book-user','sort_order'=>16],

            ['name'=>'Incentives','route_name'=>'admin.sales.index','icon'=>'hand-coins','sort_order'=>17],

            ['name'=>'Operation Incentives','route_name'=>'admin.incentives.operation-incentives','icon'=>'trending-up','sort_order'=>18],

            ['name'=>'Incentive Summary','route_name'=>'admin.incentives.incentive-summary-report','icon'=>'award','sort_order'=>19],

            ['name'=>'Store Incentives','route_name'=>'admin.incentives.index','icon'=>'baggage-claim','sort_order'=>20],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['route_name' => $menu['route_name']],
                $menu
            );
        }
    }
}