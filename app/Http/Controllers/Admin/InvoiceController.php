<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\SalesOrder;
use App\Services\InvoiceCalculationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceCalculationService $invoiceCalculator)
    {
    }

    /**
     * Order summary preview (HTML page).
     */
    public function preview($id)
    {
        try {
            $saleId = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Invalid sales order ID.');
        }

        $order = $this->loadOrder($saleId);

        return view('admin.pdf.invoice-preview', $this->viewData($order));
    }

    /**
     * Download the invoice as a PDF. Only for approved orders
     * (the invoice number is generated at approval).
     */
    public function download($id)
    {
        try {
            $saleId = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Invalid sales order ID.');
        }

        $order = $this->loadOrder($saleId);

        if (strtolower($order->approval?->status ?? '') !== 'approved') {
            return redirect()->back()->with('error', 'Invoice can be generated only after the order is approved.');
        }

        $pdf = Pdf::loadView('admin.pdf.invoice', $this->viewData($order))
            ->setPaper('a4', 'portrait');

        $fileName = 'Invoice-'.($order->invoice_no ?: $order->c_order_no ?: $order->n_sl_no).'.pdf';

        return $pdf->download($fileName);
    }

    private function loadOrder($id): SalesOrder
    {
        return SalesOrder::with([
            'customer',
            'approval',
            'orderProducts.product',
        ])->findOrFail($id);
    }

    private function viewData(SalesOrder $order): array
    {
        return [
            'order' => $order,
            'company' => CompanySetting::first() ?? new CompanySetting(),
            'calculation' => $this->invoiceCalculator->calculate($order),
            'paymentMode' => $order->c_mode_of_payment,
        ];
    }
}
