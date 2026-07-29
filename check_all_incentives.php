<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\EmployeeIncentive;
use App\Models\EmployeeMaster;
use App\Models\SalesMaster;
use Illuminate\Contracts\Console\Kernel;

$sales = SalesMaster::with('csa', 'item')->get();

foreach ($sales as $sale) {
    echo 'Sale ID: '.$sale->n_slno.' | CSA: '.($sale->csa->c_employee_name ?? 'N/A').' ('.($sale->csa->c_employee_code ?? 'N/A').') | Item: '.($sale->item->c_item_name ?? 'N/A').' | Qty: '.$sale->n_quantity."\n";

    $incentives = EmployeeIncentive::where('n_sale_id', $sale->n_slno)->get();
    foreach ($incentives as $inc) {
        $emp = EmployeeMaster::find($inc->employee_id);
        if ($emp) {
            echo '  - Incentive for '.$emp->c_employee_name.' ('.$emp->designation->c_designation_name.'): ₹'.number_format($inc->d_incentive_amount, 2)."\n";
        }
    }
    echo "--------------------------------------------------\n";
}
