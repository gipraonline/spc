<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CompanySetting;
use App\Services\InvoiceCalculationService;

class InvoiceController extends Controller
{
    public function download($id, InvoiceCalculationService $calculator)
    {
        $order = SalesOrder::with([
            'customer',
            'orderProducts.product',
        ])->findOrFail($id);


        $company = CompanySetting::first();

         // Calculate invoice values
        $calculation = $calculator->calculate($order);
        
        $pdf = Pdf::loadView('admin.pdf.invoice', [
        'order' => $order,
        'company' => $company,
        'calculation' => $calculation,
        ]);
 

return $pdf->download(
    'invoice-' . $order->c_order_no . '.pdf'
);
    }
}