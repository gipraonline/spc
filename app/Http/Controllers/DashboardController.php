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

        $isSuperAdmin = $user->hasRole('Super Admin');
        $isGipraAdmin = $user->hasRole('Gipra Admin');

        /*
        |--------------------------------------------------------------------------
        | Dashboard type
        |--------------------------------------------------------------------------
        */

        $isAdminDashboard = $user->hasAnyRole([
            'Super Admin',
            'Gipra Admin',
            'National Sales Head',
            'Regional Sales Head',
            'Team Lead',
        ]) || $user->can('dashboard.view-all-orders');

        /*
        |--------------------------------------------------------------------------
        | Attendance
        |--------------------------------------------------------------------------
        */

        $attendance = $dashboardService->getAttendance($user);

        /*
        |--------------------------------------------------------------------------
        | Today's summary
        |--------------------------------------------------------------------------
        */

        $summary = $dashboardService->getSummary($user);

        /*
        |--------------------------------------------------------------------------
        | Employee scope
        |--------------------------------------------------------------------------
        |
        | Admin:
        |   All employees/orders.
        |
        | Staff/FCO/FCA:
        |   Logged-in employee + directly reporting employees.
        |
        */

        $scopedEmployeeIds = null;

        if (! $isAdminDashboard) {

            $scopedEmployeeIds = [
                (int) $user->n_employee_id,
            ];

            $subordinateIds = EmployeeMaster::query()
                ->where('reporting_to', $user->n_employee_id)
                ->whereNull('deleted_at')
                ->pluck('n_employee_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $scopedEmployeeIds = array_values(
                array_unique([
                    ...$scopedEmployeeIds,
                    ...$subordinateIds,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Orders
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

        /*
        |--------------------------------------------------------------------------
        | CURRENT ORDER STATUS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Latest approval status is checked first.
        | If no approval status exists, sales_orders.c_order_status
        | is used.
        |
        */

        $currentStatusSql = "
    CASE
        WHEN LOWER(TRIM(COALESCE(sales_orders.c_order_status, ''))) IN (
            '',
            'pending',
            'pending approval',
            'awaiting approval',
            'waiting for approval',
            'waiting approval',
            'under approval',
            'approval pending',
            'new',
            'open'
        )
        THEN COALESCE(
            (
                SELECT NULLIF(TRIM(sa.status), '')
                FROM sales_approvals AS sa
                WHERE sa.sales_order_id = sales_orders.n_sl_no
                ORDER BY sa.created_at DESC, sa.id DESC
                LIMIT 1
            ),
            NULLIF(TRIM(sales_orders.c_order_status), ''),
            'pending'
        )

        ELSE sales_orders.c_order_status
    END
";
        /*
        |--------------------------------------------------------------------------
        | Get orders
        |--------------------------------------------------------------------------
        */

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
            ->addSelect(
                DB::raw("$currentStatusSql AS current_order_status")
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Normalize order status
        |--------------------------------------------------------------------------
        */

        foreach ($orders as $order) {

            $status = strtolower(
                trim(
                    (string) $order->current_order_status
                )
            );

            /*
            |--------------------------------------------------------------------------
            | Normalize separators and whitespace
            |--------------------------------------------------------------------------
            */

            $status = str_replace(
                ['_', '-'],
                ' ',
                $status
            );

            $status = preg_replace(
                '/\s+/',
                ' ',
                $status
            );

            $status = trim($status);

            /*
            |--------------------------------------------------------------------------
            | Map database statuses to dashboard statuses
            |--------------------------------------------------------------------------
            */

            $order->dashboard_status = match ($status) {

                /*
                |--------------------------------------------------------------------------
                | PENDING
                |--------------------------------------------------------------------------
                */

                '',
                'pending',
                'pending approval',
                'awaiting approval',
                'waiting for approval',
                'waiting approval',
                'under approval',
                'approval pending',
                'new',
                'open' => 'pending',

                /*
                |--------------------------------------------------------------------------
                | APPROVED
                |--------------------------------------------------------------------------
                */

                'approved',
                'approval approved',
                'order approved',
                'approval accepted',
                'accepted' => 'approved',

                /*
                |--------------------------------------------------------------------------
                | DISPATCHED
                |--------------------------------------------------------------------------
                */

                'dispatched',
                'dispatch' => 'dispatched',

                /*
                |--------------------------------------------------------------------------
                | SHIPPED
                |--------------------------------------------------------------------------
                */

                'shipped',
                'shipping',
                'in transit',
                'out for delivery' => 'shipped',

                /*
                |--------------------------------------------------------------------------
                | DELIVERED
                |--------------------------------------------------------------------------
                */

                'delivered',
                'delivery completed',
                'delivery complete' => 'delivered',

                /*
                |--------------------------------------------------------------------------
                | COMPLETED
                |--------------------------------------------------------------------------
                */

                'completed',
                'complete',
                'order completed' => 'completed',

                /*
                |--------------------------------------------------------------------------
                | RETURNED
                |--------------------------------------------------------------------------
                */

                'returned',
                'return',
                'return initiated',
                'returned order',
                'cancelled',
                'canceled',
                'rejected',
                'declined' => 'returned',

                /*
                |--------------------------------------------------------------------------
                | Unknown
                |--------------------------------------------------------------------------
                */

                default => 'pending',
            };
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER COUNTS
        |--------------------------------------------------------------------------
        */

        $pendingOrders = $orders
            ->where('dashboard_status', 'pending')
            ->count();

        $approvedOrders = $orders
            ->where('dashboard_status', 'approved')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Dispatched
        |--------------------------------------------------------------------------
        |
        | Staff dashboard combines:
        | dispatched + shipped
        |
        */

        $dispatchedOrders = $orders
            ->whereIn(
                'dashboard_status',
                [
                    'dispatched',

                ]
            )
            ->count();

        $shippedOrders = $orders
            ->where('dashboard_status', 'shipped')
            ->count();

        $deliveredOrders = $orders
            ->where('dashboard_status', 'delivered')
            ->count();

        $completedOrders = $orders
            ->where('dashboard_status', 'completed')
            ->count();

        $returnedOrders = $orders
            ->where('dashboard_status', 'returned')
            ->count();

        $totalOrders = $orders->count();

        /*
        |--------------------------------------------------------------------------
        | Sales values
        |--------------------------------------------------------------------------
        */

        $totalSalesValue = $orders->sum(
            fn ($order) => (float) (
                $order->n_net_sales_amount ?? 0
            )
        );

        $todaysSalesValue = $orders
            ->filter(function ($order) {

                if (! $order->d_date) {
                    return false;
                }

                return Carbon::parse(
                    $order->d_date
                )->isToday();
            })
            ->sum(
                fn ($order) => (float) (
                    $order->n_net_sales_amount ?? 0
                )
            );

        /*
        |--------------------------------------------------------------------------
        | Customer count
        |--------------------------------------------------------------------------
        |
        | Admin:
        |   All active customers.
        |
        | FCA:
        |   Customers created by the logged-in FCA.
        |
        | FCO:
        |   Customers created by employees reporting to the logged-in FCO.
        |
        */

        $totalCustomersQuery = CustomerMaster::query()
            ->whereNull('deleted_at')
            ->where('c_status', 'Y');

        if (! $isAdminDashboard) {

            $roleNames = $user->getRoleNames();

            /*
            |--------------------------------------------------------------------------
            | FCA
            |--------------------------------------------------------------------------
            */
            if ($roleNames->contains('Farm Care Advisor')) {

                $customerEmployeeIds = [
                    (int) $user->n_employee_id,
                ];

                /*
                |--------------------------------------------------------------------------
                | FCO
                |--------------------------------------------------------------------------
                */
            } elseif ($roleNames->contains('Farm Care Officer')) {

                $customerEmployeeIds = EmployeeMaster::query()
                    ->where('reporting_to', $user->n_employee_id)
                    ->whereNull('deleted_at')
                    ->pluck('n_employee_id')
                    ->map(fn ($id) => (int) $id)
                    ->toArray();

                /*
                |--------------------------------------------------------------------------
                | Other staff
                |--------------------------------------------------------------------------
                */
            } else {

                $customerEmployeeIds = [
                    (int) $user->n_employee_id,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Apply customer restriction
            |--------------------------------------------------------------------------
            */
            $totalCustomersQuery->whereIn(
                'created_by',
                $customerEmployeeIds
            );
        }

        $totalCustomers = $totalCustomersQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Payment overview
        |--------------------------------------------------------------------------
        */

        $paymentOverview = $orders
            ->groupBy(function ($order) {

                $mode = trim(
                    (string) $order->c_mode_of_payment
                );

                return $mode !== ''
                    ? $mode
                    : 'Unknown';
            })
            ->map(function ($modeOrders) {

                $pending = $modeOrders
                    ->filter(function ($order) {

                        return strtolower(
                            trim(
                                (string) $order->payment_status
                            )
                        ) === 'pending';
                    })
                    ->count();

                $paid = $modeOrders
                    ->filter(function ($order) {

                        return strtolower(
                            trim(
                                (string) $order->payment_status
                            )
                        ) === 'paid';
                    })
                    ->count();

                return [
                    'pending' => $pending,
                    'paid' => $paid,
                    'total' => $modeOrders->count(),
                ];
            })
            ->sortKeys();

        /*
        |--------------------------------------------------------------------------
        | Return dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', [

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            'user' => $user,

            /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */

            'todayLog' => $attendance['todayLog'],
            'checkInTime' => $attendance['checkInTime'],
            'checkOutTime' => $attendance['checkOutTime'],
            'totalWorkingHours' => $attendance['totalWorkingHours'],
            'workStatus' => $attendance['workStatus'],
            'workStatusSubtext' => $attendance['workStatusSubtext'],

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            'summary' => $summary,

            /*
            |--------------------------------------------------------------------------
            | Order counts
            |--------------------------------------------------------------------------
            */

            'pendingOrders' => $pendingOrders,
            'approvedOrders' => $approvedOrders,
            'dispatchedOrders' => $dispatchedOrders,
            'shippedOrders' => $shippedOrders,
            'deliveredOrders' => $deliveredOrders,
            'completedOrders' => $completedOrders,
            'returnedOrders' => $returnedOrders,
            'totalOrders' => $totalOrders,

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'paymentOverview' => $paymentOverview,

            /*
            |--------------------------------------------------------------------------
            | Sales / Customers
            |--------------------------------------------------------------------------
            */

            'totalCustomers' => $totalCustomers,
            'todaysSalesValue' => $todaysSalesValue,
            'totalSalesValue' => $totalSalesValue,

            /*
            |--------------------------------------------------------------------------
            | Dashboard type
            |--------------------------------------------------------------------------
            */

            'isAdminDashboard' => $isAdminDashboard,
            'isSuperAdmin' => $isSuperAdmin,
            'isGipraAdmin' => $isGipraAdmin,
        ]);
    }
}
