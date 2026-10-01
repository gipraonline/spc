<?php

namespace App\Http\Controllers\Admin;

use App\Exports\IncentiveSalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AuditRecord;
use App\Models\CategoryMaster;
use App\Models\CustomerMaster;
use App\Models\District;
use App\Models\EmployeeMaster;
use App\Models\OrderProduct;
use App\Models\Panchayath;
use App\Models\ProductMaster;
use App\Models\SalesApproval;
use App\Models\SalesOrder;
use App\Models\SalesOrderstatusUpdation;
use App\Models\State;
use App\Models\StoreMaster;
use App\Support\Geo;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    private function isFca(): bool
    {
        return Auth::check()
            && Auth::user()->roles()
                ->where('identifier', 'FCA')
                ->exists();
    }

    private function isFco(): bool
    {
        return Auth::check()
            && Auth::user()->roles()
                ->where('identifier', 'FCO')
                ->exists();
    }

    private function isTC(): bool
    {
        return Auth::check()
            && Auth::user()->roles()
                ->where('identifier', 'TC')
                ->exists();
    }

    // private function isFcaToday(SalesOrder $sale): bool
    // {
    //     return ! $this->isFca()
    //         || Carbon::parse($sale->d_date)->isToday();
    // }

    // public function index(Request $request)
    // {
    //     /*
    //     |--------------------------------------------------------------------------
    //     | Base Sales Order Query
    //     |--------------------------------------------------------------------------
    //     */

    //     $query = SalesOrder::with(
    //         'employee',
    //         'franchise',
    //         'customer'
    //     )
    //         ->whereNull('sales_orders.deleted_at');

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Logged-in User
    //     |--------------------------------------------------------------------------
    //     */

    //     $user = Auth::user();

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Farm Care Advisor Access
    //     |--------------------------------------------------------------------------
    //     */

    //     $isFarmCareAdvisor = $this->isFca();

    //     // if ($isFarmCareAdvisor) {

    //     //     $employeeId = $user->n_employee_id;

    //     //     $query->where(
    //     //         'sales_orders.farm_care_advisor_id',
    //     //         $employeeId
    //     //     );

    //     //     /*   $query->whereDate(
    //     //           'sales_orders.d_date',
    //     //           today()
    //     //       ); */
    //     // }

    //     if ($isFarmCareAdvisor) {

    //         $employeeIds = $this->getSubordinateEmployeeIds(
    //             (int) $user->n_employee_id
    //         );

    //         $query->whereIn(
    //             'sales_orders.farm_care_advisor_id',
    //             $employeeIds
    //         );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Employee Search
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('search')) {

    //         $search = trim($request->search);

    //         $query->whereHas('employee', function ($q) use ($search) {

    //             $q->where(function ($sub) use ($search) {

    //                 $sub->where(
    //                     'c_employee_name',
    //                     'like',
    //                     "%{$search}%"
    //                 )
    //                     ->orWhere(
    //                         'c_employee_code',
    //                         'like',
    //                         "%{$search}%"
    //                     );

    //             });

    //         });
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | From Date
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('start_date')) {

    //         $query->whereDate(
    //             'sales_orders.d_date',
    //             '>=',
    //             $request->start_date
    //         );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | To Date
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('end_date')) {

    //         $query->whereDate(
    //             'sales_orders.d_date',
    //             '<=',
    //             $request->end_date
    //         );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Payment Status
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('payment_status')) {

    //         $query->where(
    //             'sales_orders.payment_status',
    //             $request->payment_status
    //         );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | CURRENT ORDER STATUS
    //     |--------------------------------------------------------------------------
    //     |
    //     | Priority:
    //     |
    //     | 1. Latest follow-up status
    //     | 2. Latest approval status
    //     | 3. sales_orders.c_order_status
    //     |
    //     */

    //     $currentStatusSql = "
    //                 COALESCE(

    //                     (
    //                         SELECT NULLIF(TRIM(sof.c_order_status), '')
    //                         FROM  sales_orderstatus_updations AS sof
    //                         WHERE sof.n_sale_id = sales_orders.n_sl_no
    //                         ORDER BY sof.created_at DESC, sof.n_statusupdate_id DESC
    //                         LIMIT 1
    //                     ),

    //                     (
    //                         SELECT NULLIF(TRIM(sa.status), '')
    //                         FROM sales_approvals AS sa
    //                         WHERE sa.sales_order_id = sales_orders.n_sl_no
    //                         ORDER BY sa.created_at DESC, sa.id DESC
    //                         LIMIT 1
    //                     ),

    //                     sales_orders.c_order_status

    //                 )
    //             ";

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Add Current Status to Result
    //     |--------------------------------------------------------------------------
    //     */

    //     $query->select('sales_orders.*');

    //     $query->addSelect(
    //         DB::raw("$currentStatusSql AS current_order_status")
    //     );

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Order Status Filter
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('order_status')) {

    //         $orderStatus = trim($request->order_status);

    //         $query->whereRaw(
    //             "LOWER(TRIM($currentStatusSql)) = LOWER(TRIM(?))",
    //             [$orderStatus]
    //         );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Export Excel
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->export === 'excel') {

    //         $sales = $query
    //             ->orderBy(
    //                 'sales_orders.d_date',
    //                 'desc'
    //             )
    //             ->get();

    //         return Excel::download(
    //             new IncentiveSalesReportExport($sales),
    //             'sales-report.xlsx'
    //         );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Total Counts by Current Order Status
    //     |--------------------------------------------------------------------------
    //     */

    //     $countQuery = SalesOrder::query()
    //         ->whereNull('sales_orders.deleted_at');

    //     /* | Farm Care Advisor Access
    //      |--------------------------------------------------------------------------
    //      | FCA can see only their own sales .
    //      | Other roles can see sales according to their existing permissions.
    //      |--------------------------------------------------------------------------
    //      */

    //     $isFarmCareAdvisor = $this->isFca();

    //     if ($isFarmCareAdvisor) {

    //         $employeeIds = $this->getSubordinateEmployeeIds(
    //             (int) $user->n_employee_id
    //         );

    //         $countQuery->whereIn(
    //             'sales_orders.farm_care_advisor_id',
    //             $employeeIds
    //         );
    //     }

    //     /*
    //     | Get current status for each order
    //     */
    //     $statusCounts = $countQuery
    //         ->select(
    //             'sales_orders.n_sl_no',
    //             DB::raw("
    //                 COALESCE(

    //                     (
    //                         SELECT NULLIF(TRIM(sof.c_order_status), '')
    //                         FROM sales_orderstatus_updations AS sof
    //                         WHERE sof.n_sale_id = sales_orders.n_sl_no
    //                         ORDER BY sof.created_at DESC, sof.n_statusupdate_id DESC
    //                         LIMIT 1
    //                     ),

    //                     (
    //                         SELECT NULLIF(TRIM(sa.status), '')
    //                         FROM sales_approvals AS sa
    //                         WHERE sa.sales_order_id = sales_orders.n_sl_no
    //                         ORDER BY sa.created_at DESC, sa.id DESC
    //                         LIMIT 1
    //                     ),

    //                     sales_orders.c_order_status

    //                 ) AS current_status
    //             ")
    //         )
    //         ->get()
    //         ->groupBy(function ($orderData) {
    //             return strtolower(trim($order->current_status ?? 'pending'));
    //         });

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Status Counts
    //     |--------------------------------------------------------------------------
    //     */

    //     $totalSalesOrders = $statusCounts->flatten()->count();

    //     $pendingOrders = $statusCounts->get('pending', collect())->count();

    //     $completedOrders = $statusCounts->get('completed', collect())->count();
    //      $returned = $statusCounts->get('returned', collect())->count();

    //     $approvedOrders = $statusCounts->get('approved', collect())->count();

    //     $dispatchedOrders = $statusCounts->get('dispatched', collect())->count();

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Pagination
    //     |--------------------------------------------------------------------------
    //     */

    //     $sales = $query
    //         ->orderBy(
    //             'sales_orders.created_at',
    //             'desc'
    //         )
    //         ->orderBy(
    //             'sales_orders.n_sl_no',
    //             'desc'
    //         )
    //         ->paginate(20)
    //         ->withQueryString();

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Return View
    //     |--------------------------------------------------------------------------
    //     */

    //     return view(
    //         'admin.sales.index',
    //         compact(
    //             'sales',
    //             'isFarmCareAdvisor',
    //             // 'totalSalesOrders',
    //             'pendingOrders',
    //             'completedOrders',
    //             'approvedOrders',
    //             'dispatchedOrders'
    //         )
    //     );
    // }

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Role Checks
        |--------------------------------------------------------------------------
        */

        $isFarmCareAdvisor = $this->isFca();
        $isFarmCareOfficer = $this->isFco();
        $isTeleCaller = $this->isTC();

        /*
        |--------------------------------------------------------------------------
        | Employee IDs allowed to see sales
        |--------------------------------------------------------------------------
        |
        | FCO:
        |   Own sales + direct FCA sales
        |
        | FCA:
        |   Own sales only
        |
        */

        $allowedEmployeeIds = null;

        if ($isFarmCareOfficer) {

            $allowedEmployeeIds = EmployeeMaster::query()
                ->where(function ($q) use ($user) {
                    $q->where(
                        'n_employee_id',
                        $user->n_employee_id
                    )
                        ->orWhere(
                            'reporting_to',
                            $user->n_employee_id
                        );
                })
                ->whereNull('deleted_at')
                ->pluck('n_employee_id')
                ->unique()
                ->values()
                ->toArray();

        } elseif ($isFarmCareAdvisor) {

            $allowedEmployeeIds = [
                (int) $user->n_employee_id,
            ];
        } elseif ($isTeleCaller) {

            $allowedEmployeeIds = [
                (int) $user->n_employee_id,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Current Order Status SQL
        |--------------------------------------------------------------------------
        |
        | Priority:
        |
        | 1. Latest follow-up status
        | 2. Latest approval status
        | 3. sales_orders.c_order_status
        | 4. pending
        |
        | IMPORTANT:
        | sales_orderstatus_updations DOES NOT EXIST.
        | Therefore we use sales_orderstatus_updations	.
        |
        */

        $currentStatusSql = "
        COALESCE(
            (
                SELECT NULLIF(TRIM(sof.c_order_status), '')
                FROM sales_orderstatus_updations	 AS sof
                WHERE sof.n_sale_id = sales_orders.n_sl_no
                ORDER BY sof.created_at DESC,
                         sof.n_statusupdate_id DESC
                LIMIT 1
            ),
            (
                SELECT NULLIF(TRIM(sa.status), '')
                FROM sales_approvals AS sa
                WHERE sa.sales_order_id = sales_orders.n_sl_no
                ORDER BY sa.created_at DESC,
                         sa.id DESC
                LIMIT 1
            ),
            NULLIF(TRIM(sales_orders.c_order_status), ''),
            'pending'
        )
    ";

        /*
        |--------------------------------------------------------------------------
        | Requested Status
        |--------------------------------------------------------------------------
        |
        | Dashboard sends:
        |
        | ?status=pending
        |
        | Existing sales page may send:
        |
        | ?order_status=pending
        |
        | Support both.
        |
        */

        $requestedStatus = $request->input(
            'status',
            $request->input('order_status')
        );

        $requestedStatus = strtolower(
            trim((string) $requestedStatus)
        );

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = SalesOrder::query()
            ->with([
                'employee',
                'franchise',
                'customer',
                'approval',
            ])
            ->whereNull('sales_orders.deleted_at');

        /*
        |--------------------------------------------------------------------------
        | FCO / FCA/Tele Caller Access Restriction
        |--------------------------------------------------------------------------
        */

        if ($allowedEmployeeIds !== null) {

            $query->whereIn(
                'sales_orders.created_by',
                $allowedEmployeeIds
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FCO / FCA Access Restriction
        |--------------------------------------------------------------------------
        */

        /*  elseif ($allowedEmployeeIds !== null) {

             $query->whereIn(
                 'sales_orders.farm_care_advisor_id',
                 $allowedEmployeeIds
             );
         }
 */

        /*
        |--------------------------------------------------------------------------
        | Employee Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('employee', function ($q) use ($search) {

                $q->where(function ($sub) use ($search) {

                    $sub->where(
                        'c_employee_name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'c_employee_code',
                            'like',
                            "%{$search}%"
                        );
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        |
        | Dashboard:
        |
        | ?date=2026-08-31
        |
        */

        if ($request->filled('date')) {

            try {

                $date = Carbon::parse(
                    $request->date
                )->format('Y-m-d');

                $query->whereDate(
                    'sales_orders.d_date',
                    $date
                );

            } catch (\Throwable $e) {
                // Ignore invalid date.
            }
        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date')) {

            $query->whereDate(
                'sales_orders.d_date',
                '>=',
                $request->start_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('end_date')) {

            $query->whereDate(
                'sales_orders.d_date',
                '<=',
                $request->end_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'sales_orders.payment_status',
                $request->payment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Mode of Payment
        |--------------------------------------------------------------------------
        |
        | Dashboard "Payment Overview" cards link here with
        | ?c_mode_of_payment=<mode> (plus payment_status above) to jump
        | straight to the matching orders.
        |
        */

        if ($request->filled('c_mode_of_payment')) {

            $query->where(
                'sales_orders.c_mode_of_payment',
                $request->c_mode_of_payment
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Add Current Status
        |--------------------------------------------------------------------------
        */

        $query->select('sales_orders.*');

        $query->addSelect(
            DB::raw("($currentStatusSql) AS current_order_status")
        );

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        |
        | Dashboard cards:
        |
        | pending
        | approved
        | dispatched
        | delivered
        | returned
        |
        */

        if ($requestedStatus !== '') {

            switch ($requestedStatus) {

                /*
                |--------------------------------------------------------------------------
                | Pending
                |--------------------------------------------------------------------------
                */

                case 'pending':

                    $query->whereRaw("
                    LOWER(
                        TRIM(
                            COALESCE(
                                (
                                    SELECT NULLIF(TRIM(sof.c_order_status), '')
                                    FROM sales_orderstatus_updations AS sof
                                    WHERE sof.n_sale_id = sales_orders.n_sl_no
                                    ORDER BY sof.created_at DESC,
                                             sof.n_sale_id DESC
                                    LIMIT 1
                                ),
                                (
                                    SELECT NULLIF(TRIM(sa.status), '')
                                    FROM sales_approvals AS sa
                                    WHERE sa.sales_order_id = sales_orders.n_sl_no
                                    ORDER BY sa.created_at DESC,
                                             sa.id DESC
                                    LIMIT 1
                                ),
                                NULLIF(TRIM(sales_orders.c_order_status), ''),
                                'pending'
                            )
                        )
                    ) IN (
                        'pending',
                        'pending approval',
                        'awaiting approval',
                        'waiting for approval',
                        'waiting approval',
                        'under approval',
                        'approval pending',
                        'new',
                        'open',
                        ''
                    )
                ");

                    break;

                    /*
                    |--------------------------------------------------------------------------
                    | Approved
                    |--------------------------------------------------------------------------
                    */

                case 'approved':

                    $query->whereRaw("
                    LOWER(
                        TRIM(
                            ($currentStatusSql)
                        )
                    ) IN (
                        'approved',
                        'approval',
                        'approval approved',
                        'order approved'
                    )
                ");

                    break;

                    /*
                    |--------------------------------------------------------------------------
                    | Dispatched
                    |--------------------------------------------------------------------------
                    |
                    | Shipped is included in dispatched.
                    |
                    */

                case 'dispatched':

                    $query->whereRaw("
                    LOWER(
                        TRIM(
                            ($currentStatusSql)
                        )
                    ) IN (
                        'dispatched',
                        'dispatch',
                        'in transit',
                        'out for delivery'
                    )
                ");

                    break;

                    /*
                    |--------------------------------------------------------------------------
                    | Shipped
                    |--------------------------------------------------------------------------
                    */

                case 'shipped':

                    $query->whereRaw("
                    LOWER(
                        TRIM(
                            ($currentStatusSql)
                        )
                    ) IN (
                        'shipped',
                        'shipping',
                        'in transit',
                        'out for delivery'
                    )
                ");

                    break;

                    /*
                    |--------------------------------------------------------------------------
                    | Delivered
                    |--------------------------------------------------------------------------
                    */

                case 'delivered':

                    $query->whereRaw("
                    LOWER(
                        TRIM(
                            ($currentStatusSql)
                        )
                    ) IN (
                        'delivered',
                        'delivery completed'
                    )
                ");

                    break;

                    /*
                    |--------------------------------------------------------------------------
                    | Completed
                    |--------------------------------------------------------------------------
                    */

                case 'completed':

                    $query->whereRaw("
                    LOWER(
                        TRIM(
                            ($currentStatusSql)
                        )
                    ) IN (
                        'completed',
                        'complete',
                        'order completed'
                    )
                ");

                    break;

                    /*
                    |--------------------------------------------------------------------------
                    | Returned
                    |--------------------------------------------------------------------------
                    */

                case 'returned':

                    $query->whereRaw("
                    LOWER(
                        TRIM(
                            ($currentStatusSql)
                        )
                    ) IN (
                        'returned',
                        'return',
                        'return initiated',
                        'returned order',
                        'cancelled',
                        'canceled',
                        'rejected',
                        'declined'
                    )
                ");

                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Excel Export
        |--------------------------------------------------------------------------
        */

        if ($request->export === 'excel') {

            $sales = $query
                ->orderBy(
                    'sales_orders.d_date',
                    'desc'
                )
                ->orderBy(
                    'sales_orders.n_sl_no',
                    'desc'
                )
                ->get();

            return Excel::download(
                new IncentiveSalesReportExport($sales),
                'sales-report.xlsx'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Count Query
        |--------------------------------------------------------------------------
        |
        | Build the count query independently so the status cards show counts
        | based on the same date/search/payment/employee filters.
        |
        | We intentionally do NOT apply the requested status to this query,
        | otherwise every card would show the same filtered total.
        |
        */

        $countQuery = SalesOrder::query()
            ->whereNull('sales_orders.deleted_at');

        /*
        |--------------------------------------------------------------------------
        | Employee Restriction
        |--------------------------------------------------------------------------
        */

        if ($allowedEmployeeIds !== null) {

            $countQuery->whereIn(
                'sales_orders.farm_care_advisor_id',
                $allowedEmployeeIds
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Employee Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $countQuery->whereHas('employee', function ($q) use ($search) {

                $q->where(function ($sub) use ($search) {

                    $sub->where(
                        'c_employee_name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'c_employee_code',
                            'like',
                            "%{$search}%"
                        );
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {

            try {

                $date = Carbon::parse(
                    $request->date
                )->format('Y-m-d');

                $countQuery->whereDate(
                    'sales_orders.d_date',
                    $date
                );

            } catch (\Throwable $e) {
                // Ignore invalid date.
            }
        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date')) {

            $countQuery->whereDate(
                'sales_orders.d_date',
                '>=',
                $request->start_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('end_date')) {

            $countQuery->whereDate(
                'sales_orders.d_date',
                '<=',
                $request->end_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $countQuery->where(
                'sales_orders.payment_status',
                $request->payment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Current Statuses
        |--------------------------------------------------------------------------
        */

        $statusOrders = $countQuery
            ->select([
                'sales_orders.n_sl_no',
            ])
            ->addSelect(
                DB::raw("($currentStatusSql) AS current_status")
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Normalize Status
        |--------------------------------------------------------------------------
        */

        $normalizeStatus = function ($status) {

            $status = strtolower(
                trim((string) $status)
            );

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

            return trim($status);
        };

        /*
        |--------------------------------------------------------------------------
        | Status Counts
        |--------------------------------------------------------------------------
        */

        $totalSalesOrders = $statusOrders->count();

        $pendingOrders = $statusOrders
            ->filter(function ($order) use ($normalizeStatus) {

                return in_array(
                    $normalizeStatus($order->current_status),
                    [
                        'pending',
                        'pending approval',
                        'awaiting approval',
                        'waiting for approval',
                        'waiting approval',
                        'under approval',
                        'approval pending',
                        'new',
                        'open',
                        '',
                    ],
                    true
                );
            })
            ->count();

        $approvedOrders = $statusOrders
            ->filter(function ($order) use ($normalizeStatus) {

                return in_array(
                    $normalizeStatus($order->current_status),
                    [
                        'approved',
                        'approval',
                        'approval approved',
                        'order approved',
                    ],
                    true
                );
            })
            ->count();

        $dispatchedOrders = $statusOrders
            ->filter(function ($order) use ($normalizeStatus) {

                return in_array(
                    $normalizeStatus($order->current_status),
                    [
                        'dispatched',
                        'dispatch',
                        'shipped',
                        'shipping',
                        'in transit',
                        'out for delivery',
                    ],
                    true
                );
            })
            ->count();

        $shippedOrders = $statusOrders
            ->filter(function ($order) use ($normalizeStatus) {

                return in_array(
                    $normalizeStatus($order->current_status),
                    [
                        'shipped',
                        'shipping',
                        'in transit',
                        'out for delivery',
                    ],
                    true
                );
            })
            ->count();

        $deliveredOrders = $statusOrders
            ->filter(function ($order) use ($normalizeStatus) {

                return in_array(
                    $normalizeStatus($order->current_status),
                    [
                        'delivered',
                        'delivery completed',
                    ],
                    true
                );
            })
            ->count();

        $completedOrders = $statusOrders
            ->filter(function ($order) use ($normalizeStatus) {

                return in_array(
                    $normalizeStatus($order->current_status),
                    [
                        'completed',
                        'complete',
                        'order completed',
                    ],
                    true
                );
            })
            ->count();

        $returnedOrders = $statusOrders
            ->filter(function ($order) use ($normalizeStatus) {

                return in_array(
                    $normalizeStatus($order->current_status),
                    [
                        'returned',
                        'return',
                        'return initiated',
                        'returned order',
                        'cancelled',
                        'canceled',
                        'rejected',
                        'declined',
                    ],
                    true
                );
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $sales = $query
            ->orderBy(
                'sales_orders.created_at',
                'desc'
            )
            ->orderBy(
                'sales_orders.n_sl_no',
                'desc'
            )
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.sales.index',
            compact(
                'sales',
                'isFarmCareAdvisor',
                'isFarmCareOfficer',
                'totalSalesOrders',
                'pendingOrders',
                'completedOrders',
                'approvedOrders',
                'dispatchedOrders',
                'shippedOrders',
                'deliveredOrders',
                'returnedOrders',
                'requestedStatus'
            )
        );
    }

    private function getSubordinateEmployeeIds(int $employeeId): array
    {
        $employeeIds = [$employeeId];

        $children = EmployeeMaster::where(
            'reporting_to',
            $employeeId
        )
            ->whereNull('deleted_at')
            ->pluck('n_employee_id');

        foreach ($children as $childId) {

            $employeeIds = array_merge(
                $employeeIds,
                $this->getSubordinateEmployeeIds((int) $childId)
            );
        }

        return array_unique($employeeIds);
    }

    /**
     * Get Farm Care Advisors allowed in the Add Sales Order advisor dropdown.
     *
     * SUPER_ADMIN / GIPRA_ADMIN: all active FCA employees.
     * FCO: only FCA employees anywhere below the logged-in FCO in reporting_to hierarchy.
     * FCA: only the logged-in FCA.
     * Other roles: preserve the previous active-employee list.
     */
    private function getFarmCareAdvisorsForSalesOrder()
    {
        $user = Auth::user();

        $query = EmployeeMaster::query()
            ->join(
                'designation_masters as dm',
                'dm.n_designation_id',
                '=',
                'employee_masters.n_designation_id'
            )
            ->where('employee_masters.c_status', 'Y')
            ->where('dm.identifier', 'FCA')
            ->whereNull('employee_masters.deleted_at')
            ->select(
                'employee_masters.n_employee_id',
                'employee_masters.c_employee_name'
            )
            ->orderBy('employee_masters.c_employee_name');

        if ($this->isFco()) {
            $allowedIds = $this->getSubordinateEmployeeIds(
                (int) $user->n_employee_id
            );

            $query->whereIn(
                'employee_masters.n_employee_id',
                $allowedIds
            );
        } elseif ($this->isFca()) {
            $query->where(
                'employee_masters.n_employee_id',
                $user->n_employee_id
            );
        } elseif (! $user || ! $user->roles()->whereIn('identifier', [
            'SUPER_ADMIN',
            'GIPRA_ADMIN',
        ])->exists()) {
            // Preserve the existing behaviour for roles other than Admin/FCO/FCA.
            return EmployeeMaster::where('c_status', 'Y')
                ->whereNull('deleted_at')
                ->orderBy('c_employee_name')
                ->get([
                    'n_employee_id',
                    'c_employee_name',
                ]);
        }

        return $query->get();
    }

    private function getAllowedFarmCareAdvisorIdsForSalesOrder(): array
    {
        return $this->getFarmCareAdvisorsForSalesOrder()
            ->pluck('n_employee_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Decrypt an encrypted id coming from the URL / a hidden field.
     * A tampered or expired value is a 404, not a 500.
     */
    private function decryptId(?string $encrypted): int
    {
        try {
            return (int) Crypt::decryptString((string) $encrypted);
        } catch (\Throwable $e) {
            abort(404);
        }
    }

    /**
     * Role flags shared by the create / edit / show screens.
     * The Blade view relies on ALL of these being defined.
     */
    private function roleFlags(): array
    {
        $user = Auth::user();
        $identifiers = $user ? $user->roles->pluck('identifier')->all() : [];
        $employeeId = $user?->n_employee_id;

        $is = fn (array $ids) => (bool) array_intersect($ids, $identifiers);

        $isFca = $is(['FCA']);
        $isFco = $is(['FCO']);
        $isAdmin = $is(['SUPER_ADMIN', 'GIPRA_ADMIN']);
        $isTc = $is(['TC']);

        return [
            'isFarmCareAdvisor' => $isFca,
            'farmCareAdvisorId' => $isFca ? $employeeId : null,
            'isFarmCareOfficer' => $isFco,
            'farmCareOfficerId' => $isFco ? $employeeId : null,
            'isAdmin' => $isAdmin,
            'isAdminId' => $isAdmin ? $employeeId : null,
            'isTelecaller' => $isTc,
            'isTelecallerId' => $isTc ? $employeeId : null,
        ];
    }

    /**
     * Load a (non-deleted) sales order and make sure the logged-in user is
     * allowed to touch it. Mirrors the visibility rules of index():
     *  - FCA / TC : only their own orders
     *  - FCO      : own orders + orders of everyone below them
     *  - others   : unrestricted (permissions are enforced by the routes)
     */
    private function findAccessibleOrder(int $id, array $with = []): SalesOrder
    {
        $order = SalesOrder::with($with)
            ->whereNull('sales_orders.deleted_at')
            ->findOrFail($id);

        $user = Auth::user();
        $employeeId = (int) ($user?->n_employee_id ?? 0);

        if ($this->isFco()) {
            $allowed = $this->getSubordinateEmployeeIds($employeeId);
        } elseif ($this->isFca() || $this->isTC()) {
            $allowed = [$employeeId];
        } else {
            return $order;
        }

        abort_unless(
            in_array((int) $order->created_by, $allowed, true)
            || in_array((int) $order->farm_care_advisor_id, $allowed, true),
            403,
            'You are not allowed to access this Sales Order.'
        );

        return $order;
    }

    /**
     * Order number by the role of the logged-in user:
     *  TC                       -> TL-n   (tele caller)
     *  SUPER_ADMIN/GIPRA_ADMIN  -> FS-n   (admin orders)
     *  FCO                      -> FCO-n
     *  FCA                      -> the booklet serial no. typed on the form
     */
    private function generateOrderNoForUser(?string $typedOrderNo = null): ?string
    {
        if ($this->isTC()) {
            return SalesOrder::generateTeleOrderNo();
        }

        if ($this->isFca()) {
            return $typedOrderNo ?: null;
        }

        if ($this->isFco()) {
            return SalesOrder::generateFCOOrderNo();
        }

        $isAdmin = Auth::user()?->roles()
            ->whereIn('identifier', ['SUPER_ADMIN', 'GIPRA_ADMIN'])
            ->exists();

        if ($isAdmin) {
            return SalesOrder::generateFCOrderNo();
        }

        return $typedOrderNo ?: null;
    }

    public function create()
    {
        $employees = $this->getFarmCareAdvisorsForSalesOrder();
        $productCategories = CategoryMaster::where('c_status', 'y')->whereNull('n_parent_category_id')->get();
        $franchises = StoreMaster::where('c_store_status', 'Y')->get();
        $states = State::where('status', 1)->get();
        $districts = District::get();
        $customers = CustomerMaster::orderBy('c_customer_name')->get();
        $customerCode = CustomerMaster::generateCustomerCode();

        $viewmode = 'off';

        return view('admin.sales.create', array_merge($this->roleFlags(), compact(
            'employees',
            'franchises',
            'states',
            'viewmode',
            'customers',
            'customerCode',
            'productCategories',
            'districts'
        )));
    }

    public function districtFilter(Request $request)
    {
        $districts = District::where('state_id', $request->state)->get();

        return response()->json(['districts' => $districts]);
    }

    /**
     * PUT route target. The create/edit form posts here when editing so the
     * `sales-orders.edit` / `tele-callers.edit` permission is what guards it.
     */
    public function update(Request $request)
    {
        abort_unless($request->filled('id'), 404);

        return $this->store($request);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $existingOrder = null;

        if ($request->filled('id')) {
            $existingOrder = $this->findAccessibleOrder(
                (int) $request->input('id'),
                ['orderProducts', 'customer']
            );
        }

        $existingId = $existingOrder?->n_sl_no;

        $isCreatorRoleWithAdvisor = $user && $user->roles()
            ->whereIn('identifier', ['SUPER_ADMIN', 'GIPRA_ADMIN', 'FCO', 'FCA'])
            ->exists();

        $isAdminRole = $user && $user->roles()
            ->whereIn('identifier', ['SUPER_ADMIN', 'GIPRA_ADMIN'])
            ->exists();

        $paymentModes = $this->isTC()
            ? ['Cash on Delivery', 'Paid to Franchise']
            : ['Cash on Delivery', 'UPI', 'Bank Deposit', 'Paid to Franchise'];

        $proofModes = ['UPI', 'Bank Deposit'];

        /*
        |--------------------------------------------------------------------------
        | Is a payment proof image mandatory?
        |--------------------------------------------------------------------------
        | Only for UPI / Bank Deposit, and only when there is no stored image
        | that is being kept (create, no image yet, or the image is being removed).
        */
        $paymentImageRequired = false;

        if (in_array($request->input('c_mode_of_payment'), $proofModes, true)) {
            $paymentImageRequired = $existingOrder === null
                || empty($existingOrder->payment_image)
                || $request->input('remove_payment_image') == '1';
        }

        $rules = [
            'd_date' => 'required|date',

            'c_order_no' => [
                'nullable',
                'string',
                'max:100',
                Rule::requiredIf($this->isFca()),
                Rule::unique('sales_orders', 'c_order_no')->ignore($existingId, 'n_sl_no'),
            ],

            'farm_care_advisor_id' => [
                'nullable',
                'integer',
                'exists:employee_masters,n_employee_id',
                Rule::requiredIf($isCreatorRoleWithAdvisor),
            ],

            /* Customer */
            'c_customer_type' => 'required|in:new,existing',

            'n_customer_id' => [
                'nullable',
                'integer',
                'exists:customer_masters,n_customer_id,deleted_at,NULL',
                Rule::requiredIf($request->input('c_customer_type') === 'existing'),
            ],

            'c_customer_name' => 'required|string|max:255',
            'n_mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'n_whatsapp' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'c_email' => ['required', 'email', 'max:255'],
            'c_address' => 'required|string|max:1000',
            'c_post_office' => 'required|string|max:255',
            'customer_state_id' => 'required|integer|exists:states,n_state_id',
            'customer_district_id' => 'required|integer|exists:districts,id',
            'c_thaluk' => 'required|string|max:255',
            'c_pincode' => 'required|digits:6',
            'c_status' => 'required|in:Y,N',

            /* Order location (address -> map pin). Optional, never half-filled. */
            'latitude' => 'nullable|required_with:longitude|numeric|between:-90,90',
            'longitude' => 'nullable|required_with:latitude|numeric|between:-180,180',

            /* Payment */
            'c_mode_of_payment' => ['required', Rule::in($paymentModes)],

            'order_type' => [
                'nullable',
                'string',
                'in:company,franchise',
                Rule::requiredIf($isAdminRole),
            ],

            /* Franchise / order location (NOT the customer's address) */
            'n_state_id' => ['required_unless:order_type,company', 'nullable', 'integer', 'exists:states,n_state_id'],
            'n_district_id' => ['required_unless:order_type,company', 'nullable', 'integer', 'exists:districts,id'],
            'n_panchayath_id' => ['nullable', 'integer', 'exists:panchayaths,id'],
            'nearest_franchise_id' => ['required_unless:order_type,company', 'nullable', 'integer', 'exists:store_masters,n_store_id'],

            'payment_status' => ['required_unless:c_mode_of_payment,Paid to Franchise', 'nullable', 'in:pending,paid'],

            'c_transaction_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(in_array($request->input('c_mode_of_payment'), $proofModes, true)),
                Rule::unique('sales_orders', 'c_transaction_id')->ignore($existingId, 'n_sl_no'),
            ],

            'payment_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
                Rule::requiredIf($paymentImageRequired),
            ],
            'remove_payment_image' => ['nullable', 'in:0,1'],

            'booklet_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_booklet_image' => ['nullable', 'in:0,1'],

            /* Products */
            'products' => 'required|array|min:1',
            'products.*.n_category_id' => 'required|integer',
            'products.*.n_sub_category_id' => 'nullable|integer',
            'products.*.product_id' => 'required|integer|distinct|exists:product_masters,n_product_id',
            'products.*.product_price' => 'required|numeric|min:0',
            'products.*.c_hsn_code' => 'nullable|string|max:50',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.c_unit' => 'nullable|string|max:50',
            'products.*.discount' => 'nullable|numeric|min:0',
            'products.*.n_gst_percentage' => 'nullable|numeric|min:0|max:100',
        ];

        $messages = [
            'c_customer_code.unique' => 'Customer Code already exists.',
            'c_order_no.required' => 'Booklet Serial No is required.',
            'c_order_no.unique' => 'This Booklet Serial No / Order No is already used.',
            'farm_care_advisor_id.required' => 'Please select a Farm Care Advisor.',
            'n_customer_id.required' => 'Please search and select an existing customer, or choose "New Customer".',
            'c_customer_name.required' => 'Customer Name is required.',
            'n_mobile.required' => 'Mobile Number is required.',
            'n_mobile.regex' => 'Please enter a valid 10-digit mobile number.',
            'n_whatsapp.required' => 'WhatsApp Number is required.',
            'n_whatsapp.regex' => 'Please enter a valid 10-digit WhatsApp number.',
            'c_email.required' => 'Email is required.',
            'c_email.email' => 'Please enter a valid email address.',
            'c_pincode.digits' => 'Pincode should be 6 digits.',
            'c_status.required' => 'Please select customer status.',
            'customer_state_id.required' => 'Please select the customer\'s State.',
            'customer_district_id.required' => 'Please select the customer\'s District.',
            'n_state_id.required_unless' => 'Please select the franchise State.',
            'n_district_id.required_unless' => 'Please select the franchise District.',
            'nearest_franchise_id.required_unless' => 'Please select the Nearest Franchise.',
            'order_type.required' => 'Please choose the Order Type (Company / Franchise).',
            'c_mode_of_payment.required' => 'Please choose a Mode of Payment.',
            'c_mode_of_payment.in' => 'The selected Mode of Payment is not allowed.',
            'payment_status.required_unless' => 'Please select the Payment Status.',
            'c_transaction_id.required' => 'Transaction ID is required for UPI / Bank Deposit.',
            'c_transaction_id.unique' => 'This Transaction ID is already used on another order.',
            'payment_image.required' => 'Payment proof image is required for UPI or Bank Deposit.',
            'payment_image.max' => 'Payment proof image must not exceed 5 MB.',
            'booklet_image.max' => 'Booklet proof image must not exceed 5 MB.',
            'products.required' => 'Add at least one product.',
            'products.min' => 'Add at least one product.',
            'products.*.product_id.distinct' => 'The same product is added more than once.',
            'products.*.product_id.required' => 'Please select a product in every row.',
            'products.*.n_category_id.required' => 'Please select a category in every product row.',
        ];

        $attributes = [
            'd_date' => 'date',
            'products.*.qty' => 'quantity',
            'products.*.product_price' => 'price',
            'products.*.product_id' => 'product',
            'products.*.discount' => 'discount',
            'c_address' => 'address',
            'c_post_office' => 'post office',
            'c_thaluk' => 'thaluk',
            'c_pincode' => 'pincode',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        /*
        |--------------------------------------------------------------------------
        | Farm Care Advisor authorization
        |--------------------------------------------------------------------------
        | Never rely only on the dropdown. An FCO/Admin must only be able to
        | submit an FCA ID that is actually allowed for the logged-in user.
        */
        if ($isCreatorRoleWithAdvisor) {
            $allowedAdvisorIds = $this->getAllowedFarmCareAdvisorIdsForSalesOrder();
            $selectedAdvisorId = (int) ($validated['farm_care_advisor_id'] ?? 0);

            // On update, an already-assigned advisor is always acceptable
            $isCurrentAdvisor = $existingOrder
                && (int) $existingOrder->farm_care_advisor_id === $selectedAdvisorId;

            if (! $isCurrentAdvisor && ! in_array($selectedAdvisorId, $allowedAdvisorIds, true)) {
                abort(403, 'You are not authorized to select this Farm Care Advisor.');
            }
        }

        // FCA orders always belong to the logged-in FCA
        if ($this->isFca()) {
            $validated['farm_care_advisor_id'] = (int) $user->n_employee_id;
        }

        /*
        |--------------------------------------------------------------------------
        | Recalculate product lines and totals on the server
        |--------------------------------------------------------------------------
        | Same formula as the screen: taxable = qty*price - discount,
        | GST = taxable * GST%, line total = taxable + GST. Never trust the
        | totals posted by the browser.
        */
        $lines = [];
        $sumGross = $sumDiscount = $sumTaxable = $sumGst = 0.0;

        foreach ($validated['products'] as $product) {
            $price = (float) $product['product_price'];
            $qty = (int) $product['qty'];
            $gstPct = (float) ($product['n_gst_percentage'] ?? 0);

            $gross = round($price * $qty, 2);
            $discount = min(round((float) ($product['discount'] ?? 0), 2), $gross);
            $taxable = round($gross - $discount, 2);
            $gst = round($taxable * $gstPct / 100, 2);

            $lines[] = [
                'n_category_id' => $product['n_category_id'],
                'n_sub_category_id' => $product['n_sub_category_id'] ?? null,
                'product_id' => $product['product_id'],
                'product_price' => $price,
                'c_hsn_code' => $product['c_hsn_code'] ?? null,
                'qty' => $qty,
                'c_unit' => $product['c_unit'] ?? null,
                'discount' => $discount,
                'n_gst_percentage' => $gstPct,
                'gst_amount' => $gst,
                'discounted_price' => $taxable,
                'product_total' => round($taxable + $gst, 2),
            ];

            $sumGross += $gross;
            $sumDiscount += $discount;
            $sumTaxable += $taxable;
            $sumGst += $gst;
        }

        /*
        |--------------------------------------------------------------------------
        | Payment fields
        |--------------------------------------------------------------------------
        */
        $isPaidToFranchise = $validated['c_mode_of_payment'] === 'Paid to Franchise';

        $transactionId = $isPaidToFranchise ? null : ($validated['c_transaction_id'] ?? null);
        $paymentStatus = $isPaidToFranchise ? null : ($validated['payment_status'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | Order number
        |--------------------------------------------------------------------------
        */
        $newFiles = [];        // files written by this request (deleted again if the save fails)
        $oldFilesToDelete = []; // replaced / removed files (deleted only after the save succeeded)
        $oldSnapshot = $existingOrder ? $existingOrder->toArray() : null;

        DB::beginTransaction();

        try {
            /*
            |----------------------------------------------------------------------
            | Order No: generated once on create, never regenerated on edit
            |----------------------------------------------------------------------
            */
            if ($existingOrder && $this->isFca()) {
                // FCA types the booklet serial number by hand
                $orderNo = $validated['c_order_no'] ?? $existingOrder->c_order_no;
            } elseif ($existingOrder && ! empty($existingOrder->c_order_no)) {
                // Never regenerate an existing number
                $orderNo = $existingOrder->c_order_no;
            } else {
                // New order, or an old order that was saved without a number
                $orderNo = $this->generateOrderNoForUser($validated['c_order_no'] ?? null);
            }

            /*
            |----------------------------------------------------------------------
            | Customer
            |----------------------------------------------------------------------
            */
            if ($validated['c_customer_type'] === 'existing') {
                $customer = CustomerMaster::findOrFail($validated['n_customer_id']);
            } elseif (
                $existingOrder
                && $existingOrder->customer
                && $existingOrder->c_customer_type === 'new'
            ) {
                // Editing an order that was created with a new customer:
                // update that customer instead of creating a duplicate.
                $customer = $existingOrder->customer;
                $customer->update($this->customerAttributes($validated));
            } else {
                $customer = $this->customerSave($validated);
            }

            /*
            |----------------------------------------------------------------------
            | Proof images
            |----------------------------------------------------------------------
            */
            $paymentImageName = $existingOrder?->payment_image;
            $bookletImageName = $existingOrder?->booklet_image;

            if ($request->hasFile('payment_image')) {
                if ($paymentImageName) {
                    $oldFilesToDelete[] = ['payment_images', $paymentImageName];
                }
                $paymentImageName = $this->storeProofFile($request->file('payment_image'), 'payment_images', 'payment_');
                $newFiles[] = ['payment_images', $paymentImageName];
            } elseif ($request->input('remove_payment_image') == '1' && $paymentImageName) {
                $oldFilesToDelete[] = ['payment_images', $paymentImageName];
                $paymentImageName = null;
            }

            if ($request->hasFile('booklet_image')) {
                if ($bookletImageName) {
                    $oldFilesToDelete[] = ['booklet_images', $bookletImageName];
                }
                $bookletImageName = $this->storeProofFile($request->file('booklet_image'), 'booklet_images', 'booklet_');
                $newFiles[] = ['booklet_images', $bookletImageName];
            } elseif ($request->input('remove_booklet_image') == '1' && $bookletImageName) {
                $oldFilesToDelete[] = ['booklet_images', $bookletImageName];
                $bookletImageName = null;
            }

            /*
            |----------------------------------------------------------------------
            | Order
            |----------------------------------------------------------------------
            */
            $orderData = [
                'c_order_no' => $orderNo,
                'd_date' => $validated['d_date'],
                'farm_care_advisor_id' => $validated['farm_care_advisor_id'] ?? ($existingOrder->farm_care_advisor_id ?? null),
                'c_customer_type' => $validated['c_customer_type'],
                'n_customer_id' => $customer->n_customer_id,
                'order_type' => $validated['order_type'] ?? ($existingOrder->order_type ?? null),
                'n_state_id' => $validated['n_state_id'] ?? null,
                'n_district_id' => $validated['n_district_id'] ?? null,
                'n_panchayath_id' => $validated['n_panchayath_id'] ?? null,
                'nearest_franchise_id' => $validated['nearest_franchise_id'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'c_mode_of_payment' => $validated['c_mode_of_payment'],
                'payment_status' => $paymentStatus,
                'c_transaction_id' => $transactionId,
                'payment_image' => $paymentImageName,
                'booklet_image' => $bookletImageName,
                'n_total_sales_amount' => round($sumGross, 2),
                'n_product_discount_total' => round($sumDiscount, 2),
                'n_total_gst' => round($sumGst, 2),
                'n_total_discount' => $existingOrder->n_total_discount ?? 0,
                'n_net_sales_amount' => round($sumTaxable + $sumGst, 2),
            ];

            if ($existingOrder) {
                // Status, invoice no. and the original creator are NOT touched on edit
                $existingOrder->fill($orderData)->save();
                $order = $existingOrder;

                OrderProduct::where('n_order_id', $order->n_sl_no)->delete();
                $message = 'Sales Order updated successfully.';
            } else {
                $order = SalesOrder::create($orderData + [
                    'c_order_status' => 'Pending',
                    'created_by' => $user->n_employee_id,
                ]);
                $message = 'Sales Order created successfully.';
            }

            foreach ($lines as $line) {
                OrderProduct::create($line + ['n_order_id' => $order->n_sl_no]);
            }

            $fresh = SalesOrder::with(['orderProducts', 'customer'])->find($order->n_sl_no);

            $this->auditRecord(
                $oldSnapshot,
                $fresh,
                $existingOrder ? 'SalesOrderUpdate' : 'SalesOrdercreate',
                $order->n_sl_no,
                $existingOrder ? 'Updated' : 'Created'
            );

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            foreach ($newFiles as [$type, $name]) {
                $this->deleteProofFile($type, $name);
            }

            Log::error('Sales order save failed', [
                'order_id' => $existingId,
                'user_id' => $user?->getKey(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'The Sales Order could not be saved. '.(
                    config('app.debug') ? $e->getMessage() : 'Please check the details and try again.'
                ));
        }

        foreach ($oldFilesToDelete as [$type, $name]) {
            $this->deleteProofFile($type, $name);
        }

        return redirect()
            ->route($request->routeIs('admin.telecallers.*') ? 'admin.telecallers.index' : 'admin.salesorders.index')
            ->with('success', $message);
    }

    private function customerAttributes(array $v): array
    {
        return [
            'c_customer_name' => $v['c_customer_name'],
            'n_mobile' => $v['n_mobile'],
            'n_whatsapp' => $v['n_whatsapp'] ?? null,
            'c_email' => $v['c_email'] ?? null,
            'c_address' => $v['c_address'] ?? null,
            'c_post_office' => $v['c_post_office'] ?? null,
            'n_state_id' => $v['customer_state_id'] ?? null,
            'n_district_id' => $v['customer_district_id'] ?? null,
            'c_thaluk' => $v['c_thaluk'] ?? null,
            'c_pincode' => $v['c_pincode'] ?? null,
            'c_status' => $v['c_status'] ?? 'Y',
        ];
    }

    public function customerSave($validated)
    {
        return CustomerMaster::create($this->customerAttributes($validated) + [
            'c_customer_code' => CustomerMaster::generateCustomerCode(),
            'created_by' => auth()->user()->n_employee_id,
        ]);
    }

    /**
     * Write an audit row. $oldRecord / $newRecord may be models, arrays or null.
     * (AuditRecord casts both columns to array, so they must NOT be json_encode()d here.)
     */
    public function auditRecord($oldRecord, $newRecord, $moduleName, $recordId = null, $action = 'Updated')
    {
        $toArray = fn ($r) => $r instanceof Arrayable
            ? $r->toArray()
            : (is_array($r) ? $r : null);

        $old = $toArray($oldRecord);
        $new = $toArray($newRecord);

        if ($old === null && $new === null) {
            return null;
        }

        return AuditRecord::create([
            'user_id' => auth()->user()?->n_role_id,
            'module' => $moduleName,
            'action' => $action,
            'record_id' => $recordId ?? ($old['n_sl_no'] ?? $new['n_sl_no'] ?? null),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function salesUpdateStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'n_sale_id' => 'required',
            'd_followup_date' => 'required|date',
            'c_order_status' => 'nullable|string|max:100',
            'remarks' => 'required|string',
        ], [
            'd_followup_date.required' => 'Please select the follow-up date.',
            'remarks.required' => 'Remarks are required.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $sale = $this->findAccessibleOrder($this->decryptId($request->n_sale_id));

        DB::transaction(function () use ($request, $sale) {
            SalesOrderstatusUpdation::create([
                'n_sale_id' => $sale->n_sl_no,
                'd_followup_date' => $request->d_followup_date,
                'c_order_status' => $request->c_order_status,
                'remarks' => $request->remarks,
                'n_created_by' => auth()->user()->n_role_id,
            ]);

            if ($request->filled('c_order_status')) {
                $sale->c_order_status = $request->c_order_status;
                $sale->save();
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Order status updated successfully.');
    }

    public function approve(Request $request)
    {
        $request->validate([
            'sales_id' => 'required',
            'status' => 'required|in:Approved,Rejected',
            'remarks' => 'required|string',
        ]);

        $id = $this->decryptId($request->sales_id);

        DB::transaction(function () use ($request, $id) {
            $salesOrder = $this->findAccessibleOrder($id);

            SalesApproval::updateOrCreate(
                ['sales_order_id' => $salesOrder->n_sl_no],
                [
                    'status' => $request->status,
                    'remarks' => $request->remarks,
                    'approved_by' => auth()->user()->n_role_id,
                    'approved_at' => now(),
                ]
            );

            // Invoice number is generated ONLY on approval, and only once.
            if (strtolower($request->status) === 'approved' && is_null($salesOrder->invoice_no)) {
                $lastInvoice = SalesOrder::whereNotNull('invoice_no')
                    ->where('invoice_no', 'like', 'INV%')
                    ->orderByRaw('CAST(SUBSTRING(invoice_no, 4) AS UNSIGNED) DESC')
                    ->lockForUpdate()
                    ->value('invoice_no');

                $nextNumber = $lastInvoice ? ((int) substr($lastInvoice, 3)) + 1 : 1;

                $salesOrder->invoice_no = 'INV'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $salesOrder->save();
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Approval completed successfully.');
    }

    /**
     * Shared data for the create.blade.php view when it is showing / editing an existing order.
     */
    private function orderScreenData(SalesOrder $sale, string $viewmode): array
    {
        $franchise = StoreMaster::where('n_store_id', $sale->nearest_franchise_id)->first();

        return array_merge($this->roleFlags(), [
            'sale' => $sale,
            'employees' => $this->getFarmCareAdvisorsForSalesOrder(),
            'products' => ProductMaster::where('c_status', 'Y')->get(),
            'productCategories' => CategoryMaster::where('c_status', 'y')->whereNull('n_parent_category_id')->get(),
            'states' => State::with('districts')->where('status', '1')->get(),
            'districts' => District::get(),
            'franchisePanchayaths' => Panchayath::where('district_id', $sale->n_district_id)->get(),
            'franchises' => StoreMaster::where('c_store_status', 'Y')->get(),
            'customers' => CustomerMaster::orderBy('c_customer_name')->get(),
            'franchisePanchayathId' => $franchise->n_panchayath_id ?? null,
            'viewmode' => $viewmode,
        ]);
    }

    private const ORDER_RELATIONS = [
        'orderProducts',
        'orderProducts.category',
        'orderProducts.subCategory',
        'orderProducts.product',
        'customer',
        'approval',
    ];

    public function show(Request $request, $id)
    {
        $sale = $this->findAccessibleOrder($this->decryptId($id), self::ORDER_RELATIONS);

        return view('admin.sales.create', $this->orderScreenData($sale, 'on'));
    }

    public function edit(Request $request, $id)
    {
        $sale = $this->findAccessibleOrder($this->decryptId($id), self::ORDER_RELATIONS);

        return view('admin.sales.create', $this->orderScreenData($sale, 'off'));
    }

    public function destroy(Request $request, $id)
    {
        $sale = $this->findAccessibleOrder($this->decryptId($id));

        $sale->deleted_at = now();
        $sale->save();

        return redirect()
            ->route($request->routeIs('admin.telecallers.*') ? 'admin.telecallers.index' : 'admin.salesorders.index')
            ->with('success', 'Sales entry deleted successfully.');
    }

    public function franchiseFilter(Request $request)
    {
        $franchises = StoreMaster::where('c_store_status', 'Y')
            ->where('n_state_id', $request->state)
            ->where('n_district_id', $request->district)
            ->where('n_panchayath_id', $request->panchayath)
            ->orderBy('c_store_name', 'ASC')
            ->get([
                'n_store_id',
                'c_store_name',
                'c_store_code',
            ]);

        return response()->json([
            'franchises' => $franchises,
        ]);
    }

    public function panchayathFilter(Request $request)
    {
        $panchayaths = Panchayath::where('district_id', $request->district)
            ->where('status', 'Y')
            ->orderBy('panchayath_name', 'ASC')
            ->get([
                'id',
                'panchayath_name',
            ]);

        return response()->json([
            'panchayaths' => $panchayaths,
        ]);
    }

    /**
     * Find the franchise(s) nearest to a customer based purely on the
     * administrative location entered under "Address Details" (Panchayath /
     * District / State) - never on the browser's current GPS location, since
     * a sales order is very often entered from a location other than the
     * customer's own area (e.g. an office or a different franchise).
     *
     * Matching narrows from the most specific area to the least specific:
     *   1. Exact Panchayath match
     *   2. Same District (if no franchise is registered in that Panchayath)
     *   3. Same State (if no franchise is registered in that District)
     */
    public function nearestFranchise(Request $request)
    {
        // Preferred: rank by real distance when the order location is known.
        if (Geo::valid($request->latitude, $request->longitude)) {
            $ranked = Geo::rank(
                (float) $request->latitude,
                (float) $request->longitude,
                StoreMaster::where('c_store_status', 'Y')->whereNotNull('latitude')->whereNotNull('longitude')->get(),
                (float) config('spc.nearest_franchise_max_km', 50),
                3
            );

            if (! empty($ranked)) {
                return response()->json([
                    'success' => true,
                    'matched_on' => 'distance',
                    'franchises' => collect($ranked)->map(fn ($r) => [
                        'n_store_id' => $r['item']->n_store_id,
                        'c_store_name' => $r['item']->c_store_name,
                        'c_store_code' => $r['item']->c_store_code,
                        'distance_km' => $r['km'],
                    ])->values(),
                ]);
            }
            // nothing within range: fall through to the panchayath / district / state match below
        }

        $panchayathId = $request->panchayath_id;
        $districtId = $request->district_id;
        $stateId = $request->state_id;

        // Fill in district/state from the panchayath itself when they weren't
        // passed in explicitly, so a bare panchayath_id is still enough.
        if ($panchayathId) {
            $panchayath = Panchayath::find($panchayathId);

            if ($panchayath) {
                $districtId = $districtId ?: $panchayath->district_id;
                $stateId = $stateId ?: ($panchayath->state_id ?? null);
            }
        }

        if (! $panchayathId && ! $districtId && ! $stateId) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least a State to find a franchise.',
            ]);
        }

        $baseQuery = StoreMaster::where('c_store_status', 'Y');
        $matchedOn = null;
        $franchises = collect();

        if ($panchayathId) {
            $franchises = (clone $baseQuery)
                ->where('n_panchayath_id', $panchayathId)
                ->orderBy('c_store_name', 'ASC')
                ->get(['n_store_id', 'c_store_name', 'c_store_code']);

            if ($franchises->isNotEmpty()) {
                $matchedOn = 'panchayath';
            }
        }

        if ($franchises->isEmpty() && $districtId) {
            $franchises = (clone $baseQuery)
                ->where('n_district_id', $districtId)
                ->orderBy('c_store_name', 'ASC')
                ->get(['n_store_id', 'c_store_name', 'c_store_code']);

            if ($franchises->isNotEmpty()) {
                $matchedOn = 'district';
            }
        }

        if ($franchises->isEmpty() && $stateId) {
            $franchises = (clone $baseQuery)
                ->where('n_state_id', $stateId)
                ->orderBy('c_store_name', 'ASC')
                ->get(['n_store_id', 'c_store_name', 'c_store_code']);

            if ($franchises->isNotEmpty()) {
                $matchedOn = 'state';
            }
        }

        if ($franchises->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No franchise found for the selected location.',
            ]);
        }

        return response()->json([
            'success' => true,
            'franchises' => $franchises,
            'matched_on' => $matchedOn,
        ]);
    }

    public function getSubcategories(Request $request, $categoryId)
    {
        $subcategories = CategoryMaster::where('n_parent_category_id', $categoryId)
            ->where('c_status', 'Y')
            ->select(
                'n_category_id',
                'n_parent_category_id',
                'c_category_name',
            )
            ->get();

        return response()->json([
            'subcategories' => $subcategories,
        ]);
    }

    /* public function getProducts(Request $request , $subcategoryId)
    {
       $products = ProductMaster::where('n_category_id', $subcategoryId)
        ->where('c_status', 'Y')
        ->select('n_product_id','c_product_name')
        ->distinct()
        ->orderBy('c_product_name')
        ->get();

        return response()->json([
            'products' => $products
        ]);
    } */

    public function getProducts(Request $request, $subcategoryId)
    {
        $products = ProductMaster::where('n_category_id', $subcategoryId)
            ->where('c_status', 'Y')
            ->selectRaw('MIN(n_product_id) as n_product_id, c_product_name')
            ->groupBy('c_product_name')
            ->orderBy('c_product_name')
            ->get();

        return response()->json([
            'products' => $products,
        ]);
    }

    public function getAttributesFromProductname(Request $request, $productId)
    {
        $productAttributes = ProductMaster::where('n_product_id', $productId)
            ->where('c_status', 'Y')
            ->select(
                'n_product_id',
                'c_hsn_code',
                'n_gst_percentage',
                'n_mrp'
            )
            ->first();

        return response()->json(
            $productAttributes
        );
    }

    public function getProductPackSize(Request $request, $productName)
    {

        $units = ProductMaster::where('c_product_name', $productName)
            ->where('c_status', 'Y')
            ->whereNotNull('c_unit')
            ->where('c_unit', '!=', '')
            ->select(
                'c_unit',
            )
            ->get();

        return response()->json([
            'units' => $units,
        ]);
    }

    public function getProductAttributes(Request $request, $productName, $packSize)
    {

        $productAttributes = ProductMaster::where('c_product_name', $productName)
            ->where('c_unit', $packSize)
            ->where('c_status', 'Y')
            ->whereNotNull('c_unit')
            ->select(
                'n_product_id',
                'c_hsn_code',
                'n_gst_percentage',
                'n_mrp'
            )
            ->first();

        return response()->json(
            $productAttributes
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment / booklet proof images
    |--------------------------------------------------------------------------
    | Stored on the private "local" disk (storage/app/private/sales/...), never
    | under public/, and streamed only through proof() below so the sales-order
    | permissions apply. File names and extensions are generated server-side
    | from the detected MIME type, never from the client's filename.
    */

    private const PROOF_TYPES = ['payment_images', 'booklet_images'];

    private const PROOF_MIME_EXT = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    private function storeProofFile(UploadedFile $file, string $type, string $prefix): string
    {
        abort_unless(in_array($type, self::PROOF_TYPES, true), 500);

        $ext = self::PROOF_MIME_EXT[$file->getMimeType()] ?? null;
        abort_unless($ext, 422, 'Unsupported image type.');

        $name = $prefix.Str::random(32).'.'.$ext;

        Storage::disk('local')->putFileAs('sales/'.$type, $file, $name);

        return $name;
    }

    private function deleteProofFile(string $type, ?string $name): void
    {
        if (! $name || ! in_array($type, self::PROOF_TYPES, true)) {
            return;
        }

        $name = basename($name);

        Storage::disk('local')->delete('sales/'.$type.'/'.$name);

        // Legacy location (files uploaded before the move to private storage)
        $legacy = public_path('uploads/'.$type.'/'.$name);
        if (is_file($legacy)) {
            @unlink($legacy);
        }
    }

    public function proof(string $type, string $filename)
    {
        abort_unless(in_array($type, self::PROOF_TYPES, true), 404);

        $filename = basename($filename);
        $disk = Storage::disk('local');
        $path = 'sales/'.$type.'/'.$filename;

        if ($disk->exists($path)) {
            return response()->file($disk->path($path), [
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, max-age=300',
            ]);
        }

        // Legacy fallback until `php artisan files:secure-legacy` has been run
        $legacy = public_path('uploads/'.$type.'/'.$filename);
        abort_unless(is_file($legacy), 404);

        return response()->file($legacy, ['X-Content-Type-Options' => 'nosniff']);
    }
}
