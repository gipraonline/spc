<?php

namespace App\Http\Controllers;

use App\Models\CustomerMaster;
use App\Models\EmployeeMaster;
use App\Models\Hr\Announcement;
use App\Models\Hr\Attendance;
use App\Models\Hr\Attendance as HrAttendance;
use App\Models\Hr\AttendanceRegularization;
use App\Models\Hr\Department as HrDepartment;
use App\Models\Hr\Employee as HrEmployee;
use App\Models\Hr\Holiday as HrHoliday;
use App\Models\Hr\JobRequisition;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\PayrollRun;
use App\Models\Hr\User as HrUser;
use App\Models\Hr\WfhRequest;
use App\Models\SalesOrder;
use App\Services\DashboardCardVisibility;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Single combined landing dashboard for SPC — merges what used to be two
 * separate screens (the Sales dashboard at "/dashboard" and the HR
 * dashboard at "/hr") into one page: Sales KPIs + order lifecycle +
 * payments on top, HR KPIs + approvals + workforce charts below, all
 * scoped to what the signed-in user is allowed to see.
 *
 * The Sales data below intentionally mirrors DashboardController::index()
 * (same status normalization, same role scoping) — that controller and
 * its route are left in place, untouched, as a fallback.
 *
 * HR data is only added when the signed-in SPC admin also has a linked
 * HR account (App\Models\Hr\User::findForSpcAdmin — the same single
 * sign-on bridge the /hr module itself uses). Users without HR access
 * simply don't see the HR section.
 */
class UnifiedDashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        $user = Auth::user();

        $isSuperAdmin = $user->hasRole('Super Admin');
        $isGipraAdmin = $user->hasRole('Gipra Admin');

        $isAdminDashboard = $user->hasAnyRole([
            'Super Admin',
            'Gipra Admin',
            'National Sales Head',
            'Regional Sales Head',
            'Team Lead',
        ]) || $user->can('dashboard.view-all-orders');

        $attendance = $dashboardService->getAttendance($user);
        $summary = $dashboardService->getSummary($user);

        $sales = $this->buildSalesData($user, $isAdminDashboard);
        $hr = $this->buildHrData($user);

        return view('dashboard-unified', array_merge(
            [
                'user' => $user,

                // null = show every card; array = only these card keys
                // (configured per designation: Admin > Designations > Edit)
                'visibleCards' => DashboardCardVisibility::forUser($user),

                'isAdminDashboard' => $isAdminDashboard,
                'isSuperAdmin' => $isSuperAdmin,
                'isGipraAdmin' => $isGipraAdmin,

                'todayLog' => $attendance['todayLog'],
                'checkInTime' => $attendance['checkInTime'],
                'checkOutTime' => $attendance['checkOutTime'],
                'totalWorkingHours' => $attendance['totalWorkingHours'],
                'workStatus' => $attendance['workStatus'],
                'workStatusSubtext' => $attendance['workStatusSubtext'],

                // FCAs (and anyone else with field-log access) check in/out
                // through the existing Field Activity module — the dashboard
                // only shows their live status + a link there, it never
                // duplicates that flow (check-in there requires a task list).
                'canFieldLogAttendance' => $user->can('field-log.view'),

                'summary' => $summary,
            ],
            $sales,
            $hr
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Sales side
    |--------------------------------------------------------------------------
    */
    private function buildSalesData($user, bool $isAdminDashboard): array
    {
        $scopedEmployeeIds = null;

        if (! $isAdminDashboard) {
            $scopedEmployeeIds = [(int) $user->n_employee_id];

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

        $orderQuery = SalesOrder::query()->whereNull('sales_orders.deleted_at');

        if (! $isAdminDashboard) {
            $orderQuery->whereIn('sales_orders.farm_care_advisor_id', $scopedEmployeeIds);
        }

        $currentStatusSql = "
            CASE
                WHEN LOWER(TRIM(COALESCE(sales_orders.c_order_status, ''))) IN (
                    '', 'pending', 'pending approval', 'awaiting approval',
                    'waiting for approval', 'waiting approval', 'under approval',
                    'approval pending', 'new', 'open'
                )
                THEN COALESCE(
                    (SELECT NULLIF(TRIM(sa.status), '')
                     FROM sales_approvals AS sa
                     WHERE sa.sales_order_id = sales_orders.n_sl_no
                     ORDER BY sa.created_at DESC, sa.id DESC LIMIT 1),
                    NULLIF(TRIM(sales_orders.c_order_status), ''),
                    'pending'
                )
                ELSE sales_orders.c_order_status
            END
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

        foreach ($orders as $order) {
            $status = strtolower(trim((string) $order->current_order_status));
            $status = str_replace(['_', '-'], ' ', $status);
            $status = trim(preg_replace('/\s+/', ' ', $status));

            $order->dashboard_status = match ($status) {
                '', 'pending', 'pending approval', 'awaiting approval',
                'waiting for approval', 'waiting approval', 'under approval',
                'approval pending', 'new', 'open' => 'pending',

                'approved', 'approval approved', 'order approved',
                'approval accepted', 'accepted' => 'approved',

                'dispatched', 'dispatch' => 'dispatched',

                'shipped', 'shipping', 'in transit', 'out for delivery' => 'shipped',

                'delivered', 'delivery completed', 'delivery complete' => 'delivered',

                'completed', 'complete', 'order completed' => 'completed',

                'returned', 'return', 'return initiated', 'returned order',
                'cancelled', 'canceled', 'rejected', 'declined' => 'returned',

                default => 'pending',
            };
        }

        $counts = [
            'pendingOrders' => $orders->where('dashboard_status', 'pending')->count(),
            'approvedOrders' => $orders->where('dashboard_status', 'approved')->count(),
            'dispatchedOrders' => $orders->where('dashboard_status', 'dispatched')->count(),
            'shippedOrders' => $orders->where('dashboard_status', 'shipped')->count(),
            'deliveredOrders' => $orders->where('dashboard_status', 'delivered')->count(),
            'completedOrders' => $orders->where('dashboard_status', 'completed')->count(),
            'returnedOrders' => $orders->where('dashboard_status', 'returned')->count(),
            'totalOrders' => $orders->count(),
        ];

        // 7-day sales trend
        // Deleted orders must never count (SalesOrder has no SoftDeletes
        // trait, so the filter has to be explicit).
        $salesTrendQuery = SalesOrder::query()
            ->whereNull('sales_orders.deleted_at')
            ->whereBetween('sales_orders.created_at', [
                now()->subDays(30)->startOfDay(),
                now()->endOfDay(),
            ]);

        if ($user->hasRole('Farm Care Advisor')) {
            $salesTrendQuery->where('sales_orders.farm_care_advisor_id', (int) $user->n_employee_id);
        } elseif ($user->hasRole('Farm Care Officer')) {
            $fcoId = (int) $user->n_employee_id;
            $reportingFcaId = EmployeeMaster::query()
                ->where('n_employee_id', $fcoId)
                ->whereNull('deleted_at')
                ->value('reporting_to');

            $employeeIds = [$fcoId];
            if ($reportingFcaId) {
                $employeeIds[] = (int) $reportingFcaId;
            }

            $salesTrendQuery->whereIn('sales_orders.farm_care_advisor_id', array_unique($employeeIds));
        } elseif (! $isAdminDashboard) {
            // Every other non-admin role: same scope as the order cards —
            // own sales + direct reports only (was company-wide).
            $salesTrendQuery->whereIn('sales_orders.farm_care_advisor_id', $scopedEmployeeIds);
        }

        $salesTrend = $salesTrendQuery
            ->selectRaw('DATE(sales_orders.created_at) as date, SUM(sales_orders.n_net_sales_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesTrendLabels = [];
        $salesTrendValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $salesTrendLabels[] = $date->format('d M');
            $salesTrendValues[] = (float) ($salesTrend->firstWhere('date', $date->format('Y-m-d'))->total ?? 0);
        }

        $totalSalesValue = $orders->sum(fn ($o) => (float) ($o->n_net_sales_amount ?? 0));

        $todaysSalesValue = $orders
            ->filter(fn ($o) => $o->d_date && Carbon::parse($o->d_date)->isToday())
            ->sum(fn ($o) => (float) ($o->n_net_sales_amount ?? 0));

        // Customer count, scoped the same way the Sales dashboard does it
        $totalCustomersQuery = CustomerMaster::query()
            ->whereNull('deleted_at')
            ->where('c_status', 'Y');

        if (! $isAdminDashboard) {
            $roleNames = $user->getRoleNames();

            if ($roleNames->contains('Farm Care Advisor')) {
                $customerEmployeeIds = [(int) $user->n_employee_id];
            } elseif ($roleNames->contains('Farm Care Officer')) {
                $customerEmployeeIds = EmployeeMaster::query()
                    ->where('reporting_to', $user->n_employee_id)
                    ->whereNull('deleted_at')
                    ->pluck('n_employee_id')
                    ->map(fn ($id) => (int) $id)
                    ->toArray();
            } else {
                $customerEmployeeIds = [(int) $user->n_employee_id];
            }

            $totalCustomersQuery->whereIn('created_by', $customerEmployeeIds);
        }

        $totalCustomers = $totalCustomersQuery->count();

        $paymentOverview = $orders
            ->groupBy(function ($o) {
                $mode = trim((string) $o->c_mode_of_payment);

                return $mode !== '' ? $mode : 'Unknown';
            })
            ->map(function ($modeOrders) {
                $pending = $modeOrders->filter(fn ($o) => strtolower(trim((string) $o->payment_status)) === 'pending')->count();
                $paid = $modeOrders->filter(fn ($o) => strtolower(trim((string) $o->payment_status)) === 'paid')->count();

                return ['pending' => $pending, 'paid' => $paid, 'total' => $modeOrders->count()];
            })
            ->sortKeys();

        return array_merge($counts, [
            'salesTrendLabels' => $salesTrendLabels,
            'salesTrendValues' => $salesTrendValues,
            'paymentOverview' => $paymentOverview,
            'totalCustomers' => $totalCustomers,
            'todaysSalesValue' => $todaysSalesValue,
            'totalSalesValue' => $totalSalesValue,
            'orderStatusChart' => [
                'labels' => ['Pending', 'Approved', 'Dispatched', 'Shipped', 'Delivered', 'Completed', 'Returned'],
                'values' => [
                    $counts['pendingOrders'],
                    $counts['approvedOrders'],
                    $counts['dispatchedOrders'],
                    $counts['shippedOrders'],
                    $counts['deliveredOrders'],
                    $counts['completedOrders'],
                    $counts['returnedOrders'],
                ],
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HR side
    |--------------------------------------------------------------------------
    |
    | Resolved through the same single-sign-on bridge the /hr module uses
    | (App\Models\Hr\User::findForSpcAdmin, matched by email), so a user
    | who is only logged into the Sales side sees no HR section at all.
    */
    private function buildHrData($user): array
    {
        // Associates (un-promoted Farm Care Advisers / Tele Callers) are not
        // employees: no HR section on their dashboard.
        if ($user instanceof \App\Models\Admin && $user->isAssociate()) {
            return ['hasHrAccess' => false];
        }

        $hrUser = HrUser::findForSpcAdmin($user);

        if (! $hrUser) {
            return ['hasHrAccess' => false];
        }

        $hrRole = $hrUser->role ?? 'employee';
        $hrEmployee = HrEmployee::where('user_id', $hrUser->id)->first();

        // FCAs never use HR Attendance — they check in/out on Field Log
        // (App\Models\Admin::fieldLogs()) instead, so every attendance
        // scenario below is swapped out for Field Log data for them.
        $isFca = $user->hasRole('Farm Care Advisor');

        $isHrOrAbove = in_array($hrRole, ['hr_admin', 'super_admin'], true);
        $isManagerOrAbove = in_array($hrRole, ['manager', 'hr_admin', 'super_admin'], true);

        $data = [
            'hasHrAccess' => true,
            'hrUser' => $hrUser,
            'hrRole' => $hrRole,
            'hrRoleLabel' => $hrUser->roleLabel(),
        ];

        if ($isHrOrAbove) {
            $headcount = HrEmployee::where('employment_status', 'active')->count();

            $latestRun = PayrollRun::orderByDesc('year')->orderByDesc('month')->first();
            $payrollCost = $latestRun ? $latestRun->payslips()->sum('gross_pay') : 0;

            $openPositions = JobRequisition::where('status', 'open')->sum('openings');

            $today = now()->toDateString();

            $presentToday = HrAttendance::whereDate('attendance_date', $today)
                ->whereIn('status', ['present', 'late', 'half_day'])
                ->whereHas('employee', fn ($q) => $q->where('employment_status', 'active'))
                ->distinct()
                ->count('employee_id');

            $wfhToday = WfhRequest::where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->whereHas('employee', fn ($q) => $q->where('employment_status', 'active'))
                ->distinct()
                ->count('employee_id');

            $data['hrKpis'] = [
                ['label' => 'Present Today', 'value' => "{$presentToday} / {$headcount}", 'icon' => 'fa-solid fa-user-check'],
                ['label' => 'WFH Today', 'value' => (string) $wfhToday, 'icon' => 'fa-solid fa-house-laptop'],
                ['label' => 'Open Positions', 'value' => (string) $openPositions, 'icon' => 'fa-solid fa-briefcase'],
                ['label' => 'Payroll Cost (latest run)', 'value' => '₹'.number_format($payrollCost, 0), 'icon' => 'fa-solid fa-sack-dollar'],
            ];

            $departments = HrDepartment::withCount(['employees' => fn ($q) => $q->where('employment_status', 'active')])
                ->orderByDesc('employees_count')
                ->get();

            $data['deptDistribution'] = [
                'departments' => $departments,
                'max' => max(1, $departments->max('employees_count') ?? 1),
            ];

            $data['quickActionsHr'] = [
                ['label' => 'Approve Leave', 'url' => route('hr.leave.index')],
                ['label' => 'Approve WFH', 'url' => route('hr.wfh.index')],
                ['label' => 'Manage Employees', 'url' => route('admin.employees.index')],
                ['label' => 'Run Payroll', 'url' => route('hr.payroll.index')],
                ['label' => 'Generate Report', 'url' => route('hr.reports.index')],
            ];
        }

        if ($isManagerOrAbove) {
            $reportIds = ($hrRole === 'manager' && $hrEmployee) ? $hrEmployee->directReports()->pluck('id') : null;

            $leave = LeaveRequest::with(['employee.user', 'leaveType'])->where('status', 'pending')
                ->when($reportIds, fn ($q) => $q->whereIn('employee_id', $reportIds))
                ->orderBy('start_date')->get()
                ->map(fn ($r) => [
                    'employee' => $r->employee->user->name ?? 'Unknown',
                    'detail' => 'Leave · '.($r->leaveType->name ?? '').' · '.rtrim(rtrim(number_format($r->days, 1), '0'), '.').'d',
                    'route' => route('hr.leave.decide', $r),
                ]);

            $wfh = WfhRequest::with('employee.user')->where('status', 'pending')
                ->when($reportIds, fn ($q) => $q->whereIn('employee_id', $reportIds))
                ->orderBy('start_date')->get()
                ->map(fn ($r) => [
                    'employee' => $r->employee->user->name ?? 'Unknown',
                    'detail' => 'WFH · '.Carbon::parse($r->start_date)->format('d M'),
                    'route' => route('hr.wfh.decide', $r),
                ]);

            $corrections = AttendanceRegularization::with('attendance.employee.user')->where('status', 'pending')
                ->when($reportIds, fn ($q) => $q->whereHas('attendance', fn ($qq) => $qq->whereIn('employee_id', $reportIds)))
                ->orderByDesc('id')->get()
                ->map(fn ($c) => [
                    'employee' => $c->attendance->employee->user->name ?? 'Unknown',
                    'detail' => 'Correction · '.($c->attendance ? Carbon::parse($c->attendance->attendance_date)->format('d M') : ''),
                    'route' => route('hr.attendance.decide', $c),
                ]);

            $data['hrPendingApprovals'] = $leave->concat($wfh)->concat($corrections)->take(6)->values();
        }

        $data['upcomingHoliday'] = HrHoliday::where('holiday_date', '>=', now()->toDateString())
            ->orderBy('holiday_date')
            ->first();

        // Org notices — same source and audience-scoping as the HR
        // dashboard's ticker, shown in the same spot (right under the hero)
        // on the unified dashboard.
        $data['tickerAnnouncements'] = Announcement::whereNotNull('published_at')
            ->when($hrRole !== 'super_admin', function ($q) use ($hrRole) {
                $q->where(function ($w) use ($hrRole) {
                    $w->where('audience_role', 'all')->orWhere('audience_role', $hrRole);
                });
            })
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        // Employees who clock in/out through HR Attendance (not FCAs on
        // Field Activity) get the same one-click check-in/out control the
        // HR dashboard hero shows, surfaced here too. FCAs are excluded
        // here even if their linked HR account is 'employee' — they use
        // Field Log (canFieldLogAttendance in index()) instead.
        $data['hrAttendanceEligible'] = (bool) $hrEmployee
            && in_array($hrRole, ['employee', 'manager', 'hr_admin'], true)
            && ! $isFca;

        if ($data['hrAttendanceEligible']) {
            $data['hrTodayAttendance'] = Attendance::where('employee_id', $hrEmployee->id)
                ->whereDate('attendance_date', now()->toDateString())
                ->first();
        }

        if ($hrEmployee) {
            $month = now()->format('Y-m');

            if ($isFca) {
                // FCAs: pull the count from Field Log, never HR Attendance.
                $fieldLogDays = $user->fieldLogs()
                    ->whereBetween('work_date', [
                        now()->startOfMonth()->toDateString(),
                        now()->endOfMonth()->toDateString(),
                    ])
                    ->count();

                $attendanceLabel = $fieldLogDays ? "{$fieldLogDays} days logged" : '—';
                $attendanceKpiLabel = 'My Field Log (this month)';
            } else {
                $present = $hrEmployee->attendances()
                    ->where('attendance_date', 'like', $month.'%')
                    ->whereIn('status', ['present', 'late', 'half_day'])
                    ->count();
                $workingDays = $hrEmployee->attendances()->where('attendance_date', 'like', $month.'%')->count();

                $attendanceLabel = $workingDays ? "{$present} / {$workingDays} days" : '—';
                $attendanceKpiLabel = 'My Attendance (this month)';
            }

            $balances = $hrEmployee->leaveBalances()->where('year', now()->year)->get();
            $totalRemaining = $balances->sum(fn ($b) => (float) $b->opening_balance + (float) $b->accrued + (float) $b->carried_forward - (float) $b->used);

            $data['myHrSnapshot'] = [
                'attendance' => $attendanceLabel,
                'attendanceLabel' => $attendanceKpiLabel,
                'leaveBalance' => number_format($totalRemaining, 1).' days',
            ];
        }

        return $data;
    }
}
