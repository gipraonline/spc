<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\EmployeeIncentive;
use App\Models\EmployeeMaster;
use App\Models\SalesMaster;
use Illuminate\Contracts\Console\Kernel;

$john = EmployeeMaster::where('c_employee_code', 'EMP001')->first();
if (! $john) {
    echo "John not found\n";
    exit;
}

$sale = SalesMaster::where('n_csa_id', $john->n_employee_id)->first();
if (! $sale) {
    echo "Sale not found\n";
    exit;
}

$item = $sale->item;
$incentives = EmployeeIncentive::where('n_sale_id', $sale->n_slno)->get();

echo 'Sale Qty: '.$sale->n_quantity."\n";
echo 'Item SP: '.$item->d_selling_price.', PP: '.$item->n_purchase_price."\n";
echo 'Margin per unit: '.($item->d_selling_price - $item->n_purchase_price)."\n";
echo 'Total Margin: '.(($item->d_selling_price - $item->n_purchase_price) * $sale->n_quantity)."\n";

foreach ($incentives as $inc) {
    $emp = EmployeeMaster::find($inc->employee_id);
    echo 'Employee: '.$emp->c_employee_name.' ('.$emp->c_employee_code.'), Amount: '.$inc->d_incentive_amount."\n";
}
