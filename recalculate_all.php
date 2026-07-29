<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\SalesMaster;
use App\Services\IncentiveCalculationService;
use Illuminate\Contracts\Console\Kernel;

$service = new IncentiveCalculationService;
$sales = SalesMaster::all();

echo 'Recalculating incentives for '.$sales->count()." sales...\n";

foreach ($sales as $sale) {
    $service->calculateSaleIncentives($sale->n_slno);
}

echo "Done.\n";
