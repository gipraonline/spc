<?php

namespace App\Services\Hr;

use App\Models\Hr\AppraisalCycle;
use App\Models\Hr\Employee;
use App\Models\Hr\SalesTarget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Builds the numbers behind the Performance page, scoped by portal role:
 *
 *   employee     -> only their own sales, leads, attendance, incentives, ratings
 *   manager      -> the above for themselves + an aggregate/table for direct reports
 *   hr_admin     -> organisation-wide people & performance analytics + sales summary
 *   super_admin  -> everything hr_admin sees + franchise (store) sales breakdown
 *
 * SPC sales live in the default connection (sales_orders.farm_care_advisor_id
 * = employee_masters.n_employee_id) and HR data lives in "spc_hr"
 * (employees.employee_master_id links the two).
 *
 * Every method takes an explicit set of ids so the controller decides
 * *whose* data is visible; nothing here looks at the session.
 */
class PerformanceInsightsService
{
    /** Orders in these states are not counted as sales. */
    private const EXCLUDED_ORDER_STATUSES = ['Rejected', 'Cancelled'];

    /** Incentive payout states that count as "earned". */
    private const EARNED_PAYOUT_STATUSES = ['approved', 'included_in_payroll', 'paid'];

    /* ------------------------------------------------------------------ */
    /*  Period */
    /* ------------------------------------------------------------------ */

    /** [from, to] for a cycle (clipped to today), or the last 90 days. */
    public function period(?AppraisalCycle $cycle): array
    {
        if ($cycle) {
            $from = Carbon::parse($cycle->start_date)->startOfDay();
            $to = Carbon::parse($cycle->end_date)->endOfDay();
        } else {
            $to = now()->endOfDay();
            $from = now()->subDays(89)->startOfDay();
        }

        if ($to->gt(now())) {
            $to = now()->endOfDay();
        }
        if ($from->gt($to)) {
            $from = $to->copy()->startOfDay();
        }

        return [$from, $to];
    }

    /** ₹ with lakh / crore shorthand. */
    public static function inr(float|int|null $value): string
    {
        $v = (float) ($value ?? 0);
        $abs = abs($v);

        if ($abs >= 10000000) {
            return '₹'.number_format($v / 10000000, 2).' Cr';
        }
        if ($abs >= 100000) {
            return '₹'.number_format($v / 100000, 2).' L';
        }

        return '₹'.number_format($v);
    }

    /* ------------------------------------------------------------------ */
    /*  Role entry points */
    /* ------------------------------------------------------------------ */

    /** One employee's own numbers. */
    public function forEmployee(Employee $employee, Carbon $from, Carbon $to, ?int $cycleId = null): array
    {
        $masterIds = $employee->employee_master_id ? [(int) $employee->employee_master_id] : [];
        $sales = $this->salesSummary($masterIds, $from, $to);
        $leads = $this->leadsSummary($masterIds, $from, $to);
        $fieldDays = $this->fieldDays($masterIds, $from, $to);

        return [
            'targets' => $this->targetProgress($employee->id, $cycleId, [
                'net_sales' => $sales['net_sales'], 'orders' => $sales['orders'],
                'new_leads' => $leads['total'], 'field_days' => $fieldDays,
            ]),
            'field_days' => $fieldDays,
            'sales_linked' => ! empty($masterIds),
            'sales' => $sales,
            'trend' => $this->monthlyTrend($masterIds),
            'leads' => $leads,
            'attendance' => $this->attendanceSummary([$employee->id], $from, $to),
            'incentives' => $this->incentiveSummary([$employee->id], $from, $to),
            'ratings' => $this->ratingHistory($employee->id),
        ];
    }

    /** A manager's direct reports: totals + one row per person. */
    public function forTeam(Employee $manager, Carbon $from, Carbon $to, ?int $cycleId): array
    {
        $members = Employee::with('user')
            ->where('reporting_manager_id', $manager->id)
            ->where('employment_status', '!=', 'exited')
            ->get();

        if ($members->isEmpty()) {
            return ['size' => 0, 'rows' => [], 'sales' => null, 'trend' => [], 'attendance' => null, 'incentives' => null, 'leads' => null];
        }

        $hrIds = $members->pluck('id')->all();
        $masterIds = $members->pluck('employee_master_id')->filter()->map(fn ($i) => (int) $i)->values()->all();

        $salesByMaster = $this->salesByAdvisor($masterIds, $from, $to);
        $attendanceById = $this->attendanceByEmployee($hrIds, $from, $to);
        $ratingById = $this->latestRatingByEmployee($hrIds);
        $statusById = $cycleId
            ? DB::connection('spc_hr')->table('appraisals')
                ->where('appraisal_cycle_id', $cycleId)->whereIn('employee_id', $hrIds)
                ->pluck('status', 'employee_id')->all()
            : [];

        $salesTargets = $cycleId
            ? DB::connection('spc_hr')->table('sales_targets')->where('appraisal_cycle_id', $cycleId)->where('metric', 'net_sales')
                ->whereIn('employee_id', $hrIds)->pluck('target_value', 'employee_id')->map(fn ($v) => (float) $v)->all()
            : [];

        $rows = $members->map(function (Employee $m) use ($salesByMaster, $attendanceById, $ratingById, $statusById, $salesTargets) {
            $s = $salesByMaster[$m->employee_master_id] ?? null;

            return [
                'name' => $m->user->name ?? '—',
                'code' => $m->employee_code,
                'orders' => (int) ($s->orders ?? 0),
                'net_sales' => (float) ($s->net_sales ?? 0),
                'sales_target' => $salesTargets[$m->id] ?? null,
                'target_pct' => isset($salesTargets[$m->id]) && $salesTargets[$m->id] > 0 ? round((float) ($s->net_sales ?? 0) / $salesTargets[$m->id] * 100) : null,
                'attendance_rate' => $attendanceById[$m->id]['rate'] ?? null,
                'latest_rating' => $ratingById[$m->id] ?? null,
                'appraisal_status' => $statusById[$m->id] ?? null,
                'sales_linked' => (bool) $m->employee_master_id,
            ];
        })->sortByDesc('net_sales')->values()->all();

        return [
            'size' => $members->count(),
            'rows' => $rows,
            'sales' => $this->salesSummary($masterIds, $from, $to),
            'trend' => $this->monthlyTrend($masterIds),
            'leads' => $this->leadsSummary($masterIds, $from, $to),
            'attendance' => $this->attendanceSummary($hrIds, $from, $to),
            'incentives' => $this->incentiveSummary($hrIds, $from, $to),
        ];
    }

    /** Organisation-wide view for hr_admin / super_admin. */
    public function forOrganisation(Carbon $from, Carbon $to, ?int $cycleId, bool $includeFranchises): array
    {
        $hr = DB::connection('spc_hr');

        $headcount = $hr->table('employees')
            ->selectRaw("SUM(employment_status = 'active') as active, SUM(employment_status = 'on_notice') as on_notice")
            ->first();

        // --- appraisal progress for the selected cycle ---
        $statusCounts = $cycleId
            ? $hr->table('appraisals')->where('appraisal_cycle_id', $cycleId)
                ->selectRaw('status, COUNT(*) as n')->groupBy('status')->pluck('n', 'status')->all()
            : [];
        $totalAppraisals = array_sum($statusCounts);
        $completed = (int) ($statusCounts['completed'] ?? 0);

        $ratings = $cycleId
            ? $hr->table('appraisals')->where('appraisal_cycle_id', $cycleId)->whereNotNull('final_rating')->pluck('final_rating')->map(fn ($r) => (float) $r)
            : collect();

        $distribution = [
            'Below 2' => $ratings->filter(fn ($r) => $r < 2)->count(),
            '2 – 2.9' => $ratings->filter(fn ($r) => $r >= 2 && $r < 3)->count(),
            '3 – 3.9' => $ratings->filter(fn ($r) => $r >= 3 && $r < 4)->count(),
            '4 – 5' => $ratings->filter(fn ($r) => $r >= 4)->count(),
        ];

        $byDepartment = $cycleId
            ? $hr->table('appraisals as a')
                ->join('employees as e', 'e.id', '=', 'a.employee_id')
                ->leftJoin('departments as d', 'd.id', '=', 'e.department_id')
                ->where('a.appraisal_cycle_id', $cycleId)->whereNotNull('a.final_rating')
                ->selectRaw("COALESCE(d.name, 'Unassigned') as department, ROUND(AVG(a.final_rating), 2) as avg_rating, COUNT(*) as reviewed")
                ->groupBy('d.name')->orderByDesc('avg_rating')->get()
                ->map(fn ($r) => (array) $r)->all()
            : [];

        // --- sales (all advisors, not just ones linked into HR) ---
        $topSellers = $this->topSellers($from, $to, 5);

        return [
            'headcount' => ['active' => (int) ($headcount->active ?? 0), 'on_notice' => (int) ($headcount->on_notice ?? 0)],
            'appraisal' => [
                'total' => $totalAppraisals,
                'completed' => $completed,
                'completion_pct' => $totalAppraisals ? round($completed / $totalAppraisals * 100) : null,
                'avg_rating' => $ratings->isNotEmpty() ? round($ratings->avg(), 2) : null,
                'by_status' => $statusCounts,
                'pending_manager' => (int) (($statusCounts['self_review'] ?? 0) + ($statusCounts['manager_review'] ?? 0)),
                'distribution' => $distribution,
                'by_department' => $byDepartment,
            ],
            'sales' => $this->salesSummary(null, $from, $to),
            'trend' => $this->monthlyTrend(null),
            'leads' => $this->leadsSummary(null, $from, $to),
            'attendance' => $this->attendanceSummary(null, $from, $to),
            'incentives' => $this->incentiveSummary(null, $from, $to),
            'top_sellers' => $topSellers,
            'franchises' => $includeFranchises ? $this->franchiseSales($from, $to, 6) : [],
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Sales (default / "spc" connection) */
    /* ------------------------------------------------------------------ */

    /** Base query. $ids === null means "everyone". */
    protected function ordersQuery(?array $masterIds, Carbon $from, Carbon $to)
    {
        return DB::table('sales_orders')
            ->whereNull('deleted_at')
            ->whereNotIn('c_order_status', self::EXCLUDED_ORDER_STATUSES)
            ->whereBetween('d_date', [$from->toDateString(), $to->toDateString()])
            ->when($masterIds !== null, fn ($q) => $q->whereIn('farm_care_advisor_id', $masterIds));
    }

    protected function salesSummary(?array $masterIds, Carbon $from, Carbon $to): array
    {
        try {
            $r = $this->ordersQuery($masterIds, $from, $to)->selectRaw(
                "COUNT(*) as orders,
                 COUNT(DISTINCT n_customer_id) as customers,
                 COALESCE(SUM(n_net_sales_amount),0) as net_sales,
                 COALESCE(SUM(n_total_gst),0) as gst,
                 COALESCE(SUM(n_total_discount + n_product_discount_total),0) as discount,
                 SUM(c_order_status = 'Approved') as approved,
                 SUM(c_order_status = 'Pending') as pending"
            )->first();

            $orders = (int) $r->orders;

            return [
                'orders' => $orders,
                'customers' => (int) $r->customers,
                'net_sales' => (float) $r->net_sales,
                'gst' => (float) $r->gst,
                'discount' => (float) $r->discount,
                'approved' => (int) $r->approved,
                'pending' => (int) $r->pending,
                'avg_order' => $orders ? round((float) $r->net_sales / $orders, 2) : 0.0,
            ];
        } catch (\Throwable $e) {
            report($e);

            return ['orders' => 0, 'customers' => 0, 'net_sales' => 0.0, 'gst' => 0.0, 'discount' => 0.0, 'approved' => 0, 'pending' => 0, 'avg_order' => 0.0];
        }
    }

    /** Per-advisor totals keyed by employee_master id. */
    protected function salesByAdvisor(array $masterIds, Carbon $from, Carbon $to): array
    {
        if (empty($masterIds)) {
            return [];
        }

        try {
            return $this->ordersQuery($masterIds, $from, $to)
                ->selectRaw('farm_care_advisor_id, COUNT(*) as orders, COALESCE(SUM(n_net_sales_amount),0) as net_sales')
                ->groupBy('farm_care_advisor_id')->get()->keyBy('farm_care_advisor_id')->all();
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    /** Last 6 calendar months (independent of the selected cycle). */
    protected function monthlyTrend(?array $masterIds, int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);
        $end = now()->endOfMonth();

        $filled = [];
        for ($i = 0; $i < $months; $i++) {
            $m = $start->copy()->addMonths($i);
            $filled[$m->format('Y-m')] = ['label' => $m->format('M'), 'net_sales' => 0.0, 'orders' => 0];
        }

        try {
            $this->ordersQuery($masterIds, $start, $end)
                ->selectRaw("DATE_FORMAT(d_date, '%Y-%m') as ym, COUNT(*) as orders, COALESCE(SUM(n_net_sales_amount),0) as net_sales")
                ->groupBy('ym')->get()
                ->each(function ($row) use (&$filled) {
                    if (isset($filled[$row->ym])) {
                        $filled[$row->ym]['net_sales'] = (float) $row->net_sales;
                        $filled[$row->ym]['orders'] = (int) $row->orders;
                    }
                });
        } catch (\Throwable $e) {
            report($e);
        }

        return array_values($filled);
    }

    protected function topSellers(Carbon $from, Carbon $to, int $limit): array
    {
        try {
            return $this->ordersQuery(null, $from, $to)
                ->join('employee_masters as em', 'em.n_employee_id', '=', 'sales_orders.farm_care_advisor_id')
                ->selectRaw('em.c_employee_name as name, em.c_employee_code as code, COUNT(*) as orders, COALESCE(SUM(sales_orders.n_net_sales_amount),0) as net_sales')
                ->groupBy('em.n_employee_id', 'em.c_employee_name', 'em.c_employee_code')
                ->orderByDesc('net_sales')->limit($limit)->get()
                ->map(fn ($r) => ['name' => $r->name, 'code' => $r->code, 'orders' => (int) $r->orders, 'net_sales' => (float) $r->net_sales])
                ->all();
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    protected function franchiseSales(Carbon $from, Carbon $to, int $limit): array
    {
        try {
            return $this->ordersQuery(null, $from, $to)
                ->join('store_masters as st', 'st.n_store_id', '=', 'sales_orders.nearest_franchise_id')
                ->selectRaw('st.c_store_name as name, st.c_store_code as code, COUNT(*) as orders, COALESCE(SUM(sales_orders.n_net_sales_amount),0) as net_sales')
                ->groupBy('st.n_store_id', 'st.c_store_name', 'st.c_store_code')
                ->orderByDesc('net_sales')->limit($limit)->get()
                ->map(fn ($r) => ['name' => $r->name, 'code' => $r->code, 'orders' => (int) $r->orders, 'net_sales' => (float) $r->net_sales])
                ->all();
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    protected function leadsSummary(?array $masterIds, Carbon $from, Carbon $to): array
    {
        try {
            $byStatus = DB::table('leads')
                ->whereNull('deleted_at')
                ->whereBetween('created_at', [$from, $to])
                ->when($masterIds !== null, fn ($q) => $q->whereIn('n_fca_id', $masterIds))
                ->selectRaw("COALESCE(c_lead_status, 'Unknown') as status, COUNT(*) as n")
                ->groupBy('c_lead_status')->orderByDesc('n')->pluck('n', 'status')->all();

            return ['total' => (int) array_sum($byStatus), 'by_status' => $byStatus];
        } catch (\Throwable $e) {
            report($e);

            return ['total' => 0, 'by_status' => []];
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Targets */
    /* ------------------------------------------------------------------ */

    /** Distinct days on which the advisor(s) checked in to the field log. */
    protected function fieldDays(?array $masterIds, Carbon $from, Carbon $to): int
    {
        if ($masterIds !== null && empty($masterIds)) {
            return 0;
        }

        try {
            return (int) DB::table('field_logs as f')
                ->join('admins as a', 'a.n_role_id', '=', 'f.user_id')
                ->whereBetween('f.work_date', [$from->toDateString(), $to->toDateString()])
                ->when($masterIds !== null, fn ($q) => $q->whereIn('a.n_employee_id', $masterIds))
                ->selectRaw('COUNT(DISTINCT f.user_id, f.work_date) as n')->value('n');
        } catch (\Throwable $e) {
            report($e);

            return 0;
        }
    }

    /**
     * Targets set for this employee/cycle next to what they have achieved.
     *
     * @param  array<string, float|int>  $actuals  metric => actual value
     * @return array<int, array{metric:string,label:string,money:bool,target:float,actual:float,pct:int}>
     */
    public function targetProgress(int $employeeId, ?int $cycleId, array $actuals): array
    {
        if (! $cycleId) {
            return [];
        }

        return SalesTarget::where('employee_id', $employeeId)->where('appraisal_cycle_id', $cycleId)
            ->get()
            ->filter(fn ($t) => isset(SalesTarget::METRICS[$t->metric]) && (float) $t->target_value > 0)
            ->map(function ($t) use ($actuals) {
                $actual = (float) ($actuals[$t->metric] ?? 0);

                return [
                    'metric' => $t->metric,
                    'label' => SalesTarget::METRICS[$t->metric]['short'],
                    'money' => SalesTarget::METRICS[$t->metric]['money'],
                    'target' => (float) $t->target_value,
                    'actual' => $actual,
                    'pct' => (int) round($actual / (float) $t->target_value * 100),
                ];
            })
            ->sortBy(fn ($r) => array_search($r['metric'], array_keys(SalesTarget::METRICS)))
            ->values()->all();
    }

    /* ------------------------------------------------------------------ */
    /*  HR data ("spc_hr" connection) */
    /* ------------------------------------------------------------------ */

    /** $employeeIds === null means "everyone". */
    protected function attendanceSummary(?array $employeeIds, Carbon $from, Carbon $to): array
    {
        $rows = DB::connection('spc_hr')->table('attendance')
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->when($employeeIds !== null, fn ($q) => $q->whereIn('employee_id', $employeeIds))
            ->selectRaw('status, COUNT(*) as n, COALESCE(SUM(late_minutes),0) as late_min')
            ->groupBy('status')->get()->keyBy('status');

        $n = fn ($s) => (int) ($rows[$s]->n ?? 0);
        $present = $n('present');
        $late = $n('late');
        $half = $n('half_day');
        $absent = $n('absent');
        $counted = $present + $late + $half + $absent; // approved leave is neither present nor absent

        return [
            'present' => $present,
            'late' => $late,
            'half_day' => $half,
            'absent' => $absent,
            'on_leave' => $n('on_leave'),
            'late_minutes' => (int) ($rows['late']->late_min ?? 0),
            'rate' => $counted ? round(($present + $late + 0.5 * $half) / $counted * 100, 1) : null,
        ];
    }

    /** Attendance rate per employee: [id => ['rate' => %]]. */
    protected function attendanceByEmployee(array $employeeIds, Carbon $from, Carbon $to): array
    {
        $out = [];
        $rows = DB::connection('spc_hr')->table('attendance')
            ->whereIn('employee_id', $employeeIds)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('employee_id, status, COUNT(*) as n')
            ->groupBy('employee_id', 'status')->get()->groupBy('employee_id');

        foreach ($rows as $id => $set) {
            $c = $set->pluck('n', 'status');
            $counted = ($c['present'] ?? 0) + ($c['late'] ?? 0) + ($c['half_day'] ?? 0) + ($c['absent'] ?? 0);
            $out[$id] = ['rate' => $counted ? round((($c['present'] ?? 0) + ($c['late'] ?? 0) + 0.5 * ($c['half_day'] ?? 0)) / $counted * 100, 1) : null];
        }

        return $out;
    }

    protected function incentiveSummary(?array $employeeIds, Carbon $from, Carbon $to): array
    {
        $rows = DB::connection('spc_hr')->table('incentive_payouts')
            ->whereRaw('(year * 100 + month) BETWEEN ? AND ?', [(int) $from->format('Ym'), (int) $to->format('Ym')])
            ->when($employeeIds !== null, fn ($q) => $q->whereIn('employee_id', $employeeIds))
            ->selectRaw('status, COALESCE(SUM(incentive_amount),0) as amt, COALESCE(SUM(achieved_value),0) as achieved')
            ->groupBy('status')->get()->keyBy('status');

        $earned = 0.0;
        $achieved = 0.0;
        foreach (self::EARNED_PAYOUT_STATUSES as $s) {
            $earned += (float) ($rows[$s]->amt ?? 0);
            $achieved += (float) ($rows[$s]->achieved ?? 0);
        }

        return [
            'earned' => $earned,
            'pending' => (float) ($rows['pending']->amt ?? 0),
            'achieved_value' => $achieved,
        ];
    }

    /** A person's completed appraisals, oldest first (for a trend). */
    protected function ratingHistory(int $employeeId): array
    {
        return DB::connection('spc_hr')->table('appraisals as a')
            ->join('appraisal_cycles as c', 'c.id', '=', 'a.appraisal_cycle_id')
            ->where('a.employee_id', $employeeId)->whereNotNull('a.final_rating')
            ->orderBy('c.start_date')
            ->get(['c.name as cycle', 'a.final_rating'])
            ->map(fn ($r) => ['cycle' => $r->cycle, 'rating' => (float) $r->final_rating])->all();
    }

    protected function latestRatingByEmployee(array $employeeIds): array
    {
        return DB::connection('spc_hr')->table('appraisals as a')
            ->join('appraisal_cycles as c', 'c.id', '=', 'a.appraisal_cycle_id')
            ->whereIn('a.employee_id', $employeeIds)->whereNotNull('a.final_rating')
            ->orderBy('c.start_date')
            ->get(['a.employee_id', 'a.final_rating'])
            ->mapWithKeys(fn ($r) => [$r->employee_id => (float) $r->final_rating]) // later cycles overwrite earlier
            ->all();
    }
}
