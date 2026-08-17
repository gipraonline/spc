<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        // Authenticated user
        $user = Auth::user();

        // Attendance
        $attendance = $dashboardService->getAttendance($user);

        // Dashboard summary
        $summary = $dashboardService->getSummary($user);


        /*
        |--------------------------------------------------------------------------
        | Order Overview Counts
        |--------------------------------------------------------------------------
        */

        $orderCountQuery = SalesOrder::query()
            ->whereNull('sales_orders.deleted_at');




        /*
        |--------------------------------------------------------------------------
        | Current Order Status
        |--------------------------------------------------------------------------
        | Priority:
        | 1. Latest follow-up status
        | 2. Latest approval status
        | 3. sales_orders.c_order_status
        |--------------------------------------------------------------------------
        */

        $currentStatusSql = "
            COALESCE(

                (
                    SELECT NULLIF(TRIM(sof.c_order_status), '')
                    FROM sales_order_followups AS sof
                    WHERE sof.n_sale_id = sales_orders.n_sl_no
                    ORDER BY sof.created_at DESC, sof.n_followup_id DESC
                    LIMIT 1
                ),

                (
                    SELECT NULLIF(TRIM(sa.status), '')
                    FROM sales_approvals AS sa
                    WHERE sa.sales_order_id = sales_orders.n_sl_no
                    ORDER BY sa.created_at DESC, sa.id DESC
                    LIMIT 1
                ),

                sales_orders.c_order_status

            )
        ";

        /*
        |--------------------------------------------------------------------------
        | Get Current Status For Each Order
        |--------------------------------------------------------------------------
        */

        $orderStatusCounts = $orderCountQuery
            ->select(
                'sales_orders.n_sl_no',
                DB::raw("
            LOWER(TRIM($currentStatusSql)) AS current_status
        ")
            )
            ->get()
            ->groupBy(function ($order) {
                return $order->current_status ?? 'pending';
            });

        /*
        |--------------------------------------------------------------------------
        | Status Totals
        |--------------------------------------------------------------------------
        */

        $approvedOrders = $orderStatusCounts
            ->get('approved', collect())
            ->count();

        $dispatchedOrders = $orderStatusCounts
            ->get('dispatched', collect())
            ->count();

        $pendingOrders = $orderStatusCounts
            ->get('pending', collect())
            ->count();

        $deliveredOrders = $orderStatusCounts
            ->get('delivered', collect())
            ->count();

        return view('dashboard', [
            'user' => $user,

            // Attendance
            'todayLog' => $attendance['todayLog'],
            'checkInTime' => $attendance['checkInTime'],
            'checkOutTime' => $attendance['checkOutTime'],
            'totalWorkingHours' => $attendance['totalWorkingHours'],
            'workStatus' => $attendance['workStatus'],
            'workStatusSubtext' => $attendance['workStatusSubtext'],

            // Summary cards
            'summary' => $summary,

            // Order Overview
            'approvedOrders' => $approvedOrders,
            'dispatchedOrders' => $dispatchedOrders,
            'pendingOrders' => $pendingOrders,
            'deliveredOrders' => $deliveredOrders,
        ]);
    }
}
