<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\SalesOrder;
use App\Services\InvoiceCalculationService;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function preview($id, InvoiceCalculationService $calculator)
    {
        $order = SalesOrder::with([
            'customer',
            'orderProducts.product',
        ])->findOrFail($id);

        // Get company details
        $company = CompanySetting::first();

        $calculation = $calculator->calculate($order);

        return view('admin.pdf.invoice-preview', [
            'order' => $order,
            'company' => $company,
            'calculation' => $calculation,
        ]);
    }

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
            'invoice-'.$order->c_order_no.'.pdf'
        );
    }
}