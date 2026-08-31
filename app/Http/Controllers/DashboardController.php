<?php

namespace App\Http\Controllers;

use App\Models\CustomerMaster;
use App\Models\EmployeeMaster;
use App\Models\SalesOrder;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Dashboard type
        |--------------------------------------------------------------------------
        | Super Admin / Gipra Admin see the complete order lifecycle.
        | FCO sees own orders + orders belonging to directly reporting FCAs.
        | FCA sees only their own orders.
        |--------------------------------------------------------------------------
        */
        $isAdminDashboard = $user->hasAnyRole([
            'Super Admin',
            'Gipra Admin',
        ]) || $user->can('dashboard.view-all-orders');

        $attendance = $dashboardService->getAttendance($user);
        $summary = $dashboardService->getSummary($user);

        /*
        |--------------------------------------------------------------------------
        | Employee scope
        |--------------------------------------------------------------------------
        */
        $scopedEmployeeIds = null;

        if (! $isAdminDashboard) {
            $scopedEmployeeIds = [(int) $user->n_employee_id];

            // FCO -> include directly reporting FCA employees.
            $subordinateIds = EmployeeMaster::query()
                ->where('reporting_to', $user->n_employee_id)
                ->whereNull('deleted_at')
                ->pluck('n_employee_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $scopedEmployeeIds = array_values(array_unique([
                ...$scopedEmployeeIds,
                ...$subordinateIds,
            ]));
        }

        /*
        |--------------------------------------------------------------------------
        | Get every non-deleted order in the user's scope.
        |--------------------------------------------------------------------------
        */
        $orderQuery = SalesOrder::query()
            ->whereNull('sales_orders.deleted_at');

        if (! $isAdminDashboard) {
            $orderQuery->whereIn(
                'sales_orders.farm_care_advisor_id',
                $scopedEmployeeIds
            );
        }

        $currentStatusSql = "
            COALESCE(
                (
                    SELECT NULLIF(TRIM(sof.c_order_status), '')
                    FROM sales_orderstatus_updations AS sof
                    WHERE sof.n_sale_id = sales_orders.n_sl_no
                    ORDER BY sof.created_at DESC, sof.n_statusupdate_id DESC
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

        $orders = $orderQuery
            ->select([
                'sales_orders.n_sl_no',
                'sales_orders.n_net_sales_amount',
                'sales_orders.d_date',
                'sales_orders.created_at',
                'sales_orders.c_order_status',
                'sales_orders.farm_care_advisor_id',
                'sales_orders.payment_status',
                'sales_orders.c_mode_of_payment',
            ])
            ->addSelect(DB::raw("$currentStatusSql AS current_order_status"))
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Determine the current status.
        |--------------------------------------------------------------------------
        | The status source matches the Sales Order listing:
        | 1. latest status update
        | 2. latest approval status
        | 3. sales_orders.c_order_status
        | 4. pending
        |--------------------------------------------------------------------------
        */
        foreach ($orders as $order) {
            $status = $order->current_order_status ?: 'pending';

            $normalizedStatus = strtolower(trim(
                preg_replace('/\s+/', ' ', str_replace(
                    ['_', '-'],
                    ' ',
                    (string) $status
                ))
            ));

            /*
             * Keep the seven admin lifecycle states distinct.
             * For FCO/FCA, the requested five cards use the same
             * lifecycle with shipped treated as dispatched.
             */
            $order->dashboard_status = match ($normalizedStatus) {
                'pending',
                'pending approval',
                'awaiting approval',
                'waiting for approval',
                'waiting approval',
                'under approval',
                'approval pending',
                'new',
                'open',
                '' => 'pending',

                'approved',
                'approval',
                'approval approved',
                'order approved' => 'approved',

                'dispatched',
                'dispatch' => 'dispatched',

                'shipped',
                'shipping',
                'in transit',
                'out for delivery' => 'shipped',

                'delivered',
                'delivery completed' => 'delivered',

                'completed',
                'complete',
                'order completed' => 'completed',

                'returned',
                'return',
                'return initiated',
                'returned order',
                'cancelled',
                'canceled',
                'rejected',
                'declined' => 'returned',

                default => 'pending',
            };
        }

        /*
        |--------------------------------------------------------------------------
        | FCO / FCA five-card counts
        |--------------------------------------------------------------------------
        */
        $pendingOrders = $orders->where('dashboard_status', 'pending')->count();
        $approvedOrders = $orders->where('dashboard_status', 'approved')->count();

        // Shipped is part of the dispatched bucket for FCO/FCA.
        $dispatchedOrders = $orders->whereIn(
            'dashboard_status',
            ['dispatched', 'shipped']
        )->count();

        $deliveredOrders = $orders->where('dashboard_status', 'delivered')->count();
        $returnedOrders = $orders->where('dashboard_status', 'returned')->count();

        /*
        |--------------------------------------------------------------------------
        | Admin seven-state counts + total
        |--------------------------------------------------------------------------
        */
        $shippedOrders = $orders->where('dashboard_status', 'shipped')->count();
        $completedOrders = $orders->where('dashboard_status', 'completed')->count();
        $totalOrders = $orders->count();

        /*
        |--------------------------------------------------------------------------
        | Sales values
        |--------------------------------------------------------------------------
        */
        $totalSalesValue = $orders->sum(
            fn ($order) => (float) ($order->n_net_sales_amount ?? 0)
        );

        $todaysSalesValue = $orders
            ->filter(function ($order) {
                if (! $order->d_date) {
                    return false;
                }

                return Carbon::parse($order->d_date)->isToday();
            })
            ->sum(
                fn ($order) => (float) ($order->n_net_sales_amount ?? 0)
            );

        /*
        |--------------------------------------------------------------------------
        | Customer count
        |--------------------------------------------------------------------------
        */
        $totalCustomersQuery = CustomerMaster::query()
            ->whereNull('deleted_at')
            ->where('c_status', 'Y');

        if (! $isAdminDashboard) {
            $customerEmployeeIds = [(int) $user->n_employee_id];

            $customerSubordinateIds = EmployeeMaster::query()
                ->where('reporting_to', $user->n_employee_id)
                ->whereNull('deleted_at')
                ->pluck('n_employee_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $customerEmployeeIds = array_values(array_unique([
                ...$customerEmployeeIds,
                ...$customerSubordinateIds,
            ]));

            $totalCustomersQuery->whereIn('created_by', $customerEmployeeIds);
        }

        $totalCustomers = $totalCustomersQuery->count();

        /*
|--------------------------------------------------------------------------
| Payment status counts
|--------------------------------------------------------------------------
| Payment status comes directly from sales_orders.payment_status.
|
| Admin:
|   - All orders / customers
|
| FCO / ACO:
|   - Own orders + directly reporting FCA orders
|
| FCA:
|   - Own orders only
|--------------------------------------------------------------------------
*/

        $paymentOverview = $orders
            ->groupBy(function ($order) {
                $mode = trim((string) $order->c_mode_of_payment);

                return $mode !== '' ? $mode : 'Unknown';
            })
            ->map(function ($modeOrders) {

                $pending = $modeOrders->filter(function ($order) {
                    return strtolower(trim((string) $order->payment_status)) === 'pending';
                })->count();

                $paid = $modeOrders->filter(function ($order) {
                    return strtolower(trim((string) $order->payment_status)) === 'paid';
                })->count();

                return [
                    'pending' => $pending,
                    'paid' => $paid,
                    'total' => $modeOrders->count(),
                ];
            })
            ->sortKeys();

        return view('dashboard', [
            'user' => $user,

            // Attendance
            'todayLog' => $attendance['todayLog'],
            'checkInTime' => $attendance['checkInTime'],
            'checkOutTime' => $attendance['checkOutTime'],
            'totalWorkingHours' => $attendance['totalWorkingHours'],
            'workStatus' => $attendance['workStatus'],
            'workStatusSubtext' => $attendance['workStatusSubtext'],

            // Existing summary
            'summary' => $summary,

            // FCO / FCA five cards
            'pendingOrders' => $pendingOrders,
            'approvedOrders' => $approvedOrders,
            'dispatchedOrders' => $dispatchedOrders,
            'deliveredOrders' => $deliveredOrders,
            'returnedOrders' => $returnedOrders,

            // Admin lifecycle cards
            'shippedOrders' => $shippedOrders,
            'completedOrders' => $completedOrders,
            'totalOrders' => $totalOrders,

            // Payment status
            'paymentOverview' => $paymentOverview,

            // FCO / FCA sales/customer cards
            'totalCustomers' => $totalCustomers,
            'todaysSalesValue' => $todaysSalesValue,
            'totalSalesValue' => $totalSalesValue,

            // Used by the Blade to choose the correct card set.
            'isAdminDashboard' => $isAdminDashboard,
        ]);
    }
}
