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

        // Determine invoice payment mode
        $paymentMode = $order->c_mode_of_payment;

        if (in_array(strtolower(trim($paymentMode)), ['upi', 'bank deposit'])) {
            $paymentMode = 'Paid';
        }

        return view('admin.pdf.invoice-preview', [
            'order' => $order,
            'company' => $company,
            'calculation' => $calculation,
            'paymentMode' => $paymentMode,
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

        // Determine invoice payment mode
        $paymentMode = $order->c_mode_of_payment;

        if (in_array(strtolower(trim($paymentMode)), ['upi', 'bank deposit'])) {
            $paymentMode = 'Paid';
        }

        $pdf = Pdf::loadView('admin.pdf.invoice', [
            'order' => $order,
            'company' => $company,
            'calculation' => $calculation,
            'paymentMode' => $paymentMode,
        ]);
        $invoiceNo = $order->invoice_no;

        return $pdf->download(
            'invoice-'.$invoiceNo.'.pdf'
        );
    }
}
