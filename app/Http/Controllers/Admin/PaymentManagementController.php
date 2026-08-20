<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PaymentManagementExport;
use App\Http\Controllers\Controller;
use App\Models\PaymentStatusLog;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PaymentManagementController extends Controller
{
    /**
     * Display payment management page.
     */
    public function index(Request $request)
    {
        $query = SalesOrder::query();

        // Payment mode filter
        if ($request->filled('payment_mode')) {
            $query->where(
                'c_mode_of_payment',
                $request->payment_mode
            );
        }

        // Payment status filter
        if ($request->filled('status')) {
            $query->where(
                'payment_status',
                $request->status
            );
        }

        // From date filter
        if ($request->filled('from_date')) {
            $query->whereDate(
                'd_date',
                '>=',
                $request->from_date
            );
        }

        // To date filter
        if ($request->filled('to_date')) {
            $query->whereDate(
                'd_date',
                '<=',
                $request->to_date
            );
        }

        $orders = $query
            ->orderByDesc('n_sl_no')
            ->paginate(15)
            ->withQueryString();

        // Payment modes
        $paymentModes = SalesOrder::query()
            ->whereNotNull('c_mode_of_payment')
            ->where('c_mode_of_payment', '!=', '')
            ->distinct()
            ->orderBy('c_mode_of_payment')
            ->pluck('c_mode_of_payment');

        // Payment statuses
        $paymentStatuses = SalesOrder::query()
            ->whereNotNull('payment_status')
            ->where('payment_status', '!=', '')
            ->distinct()
            ->orderBy('payment_status')
            ->pluck('payment_status');

        return view(
            'admin.payment-management.index',
            compact(
                'orders',
                'paymentModes',
                'paymentStatuses'
            )
        );
    }

    /**
     * Export payment management data to Excel.
     */
    public function export(Request $request)
    {
        return Excel::download(
            new PaymentManagementExport(
                $request->payment_mode,
                $request->status,
                $request->from_date,
                $request->to_date
            ),
            'payment-management.xlsx'
        );
    }

    /**
     * Update payment status and create payment status log.
     */
    public function updatePaymentStatus(
        Request $request,
        SalesOrder $salesOrder
    ) {
        $request->validate([
            'payment_status' => [
                'required',
                'in:pending,paid',
            ],
        ]);

        $newStatus = $request->payment_status;
        $oldStatus = $salesOrder->payment_status;

        // No change
        if ($oldStatus === $newStatus) {
            return back();
        }

        DB::transaction(function () use (
            $salesOrder,
            $oldStatus,
            $newStatus
        ) {

            // Update current payment status
            $salesOrder->update([
                'payment_status' => $newStatus,
            ]);

            // Create payment status history
            PaymentStatusLog::create([
                'sales_order_n_sl_no' => $salesOrder->n_sl_no,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Payment status updated successfully.'
        );
    }

    public function updateRemarks(Request $request, SalesOrder $salesOrder)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'max:1000'],
        ]);

        $log = PaymentStatusLog::where(
            'sales_order_n_sl_no',
            $salesOrder->n_sl_no
        )
            ->where('new_status', 'paid')
            ->latest('id')
            ->first();

        if (! $log) {
            return response()->json([
                'success' => false,
                'message' => 'Payment status log not found.',
            ], 404);
        }

        $log->update([
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Remarks saved successfully.',
        ]);
    }
}
