<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CompanySetting;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $order = SalesOrder::with([
            'customer',
            'orderProducts.product',
        ])->findOrFail($id);
        $company = CompanySetting::first();
        $pdf = Pdf::loadView('admin.pdf.invoice', [
        'order' => $order,
        'company' => $company,
        ]);
 

return $pdf->download(
    'invoice-' . $order->c_order_no . '.pdf'
);
    }
}