<?php

namespace App\Services\Hr;

use App\Models\Hr\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Sales performance for a team lead: their own sales, and their team's sales
 * (the lead + everyone who reports to them: telecallers, FCOs, FCAs ...),
 * as daily, weekly and monthly series.
 *
 * Same sales rules as PerformanceInsightsService: sales_orders.n_net_sales_amount,
 * Rejected / Cancelled orders left out, matched on farm_care_advisor_id =
 * employee_masters.n_employee_id (Employee::employee_master_id).
 */
class TeamLeadSalesService
{
    private const EXCLUDED_ORDER_STATUSES = ['Rejected', 'Cancelled'];

    /** How many periods each tab shows. */
    private const PERIODS = ['day' => 30, 'week' => 12, 'month' => 12];

    /** null when nobody reports to this employee (not a team lead). */
    public function forLead(Employee $lead): ?array
    {
        $ownId = $lead->employee_master_id ? (int) $lead->employee_master_id : null;

        $members = Employee::where('reporting_manager_id', $lead->id)
            ->where('employment_status', '!=', 'exited')
            ->get(['id', 'employee_master_id']);

        if ($members->isEmpty()) {
            return null;
        }

        $reports = $members->pluck('employee_master_id')->filter()->map(fn ($i) => (int) $i)->all();
        $teamIds = array_values(array_unique(array_merge($ownId ? [$ownId] : [], $reports)));

        return [
            'sales_linked' => $ownId !== null,
            'team_size' => $members->count(),
            'monthly' => $this->series('month', $ownId, $teamIds),
            'weekly' => $this->series('week', $ownId, $teamIds),
            'daily' => $this->series('day', $ownId, $teamIds),
        ];
    }

    /**
     * @return array{rows: array<int,array>, current: array, previous: array}
     *         each row: key, label, own, team, own_orders, team_orders
     */
    private function series(string $unit, ?int $ownId, array $teamIds): array
    {
        $count = self::PERIODS[$unit];
        $rows = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            [$key, $label] = match ($unit) {
                'day' => [now()->subDays($i)->format('Y-m-d'), now()->subDays($i)->format('d M')],
                'week' => [
                    now()->startOfWeek(Carbon::MONDAY)->subWeeks($i)->format('Y-m-d'),
                    now()->startOfWeek(Carbon::MONDAY)->subWeeks($i)->format('d M'),
                ],
                'month' => [
                    now()->startOfMonth()->subMonths($i)->format('Y-m'),
                    now()->startOfMonth()->subMonths($i)->format("M 'y"),
                ],
            };
            $rows[$key] = ['key' => $key, 'label' => $label, 'own' => 0.0, 'team' => 0.0, 'own_orders' => 0, 'team_orders' => 0];
        }

        $from = match ($unit) {
            'day' => now()->subDays($count - 1)->startOfDay(),
            'week' => now()->startOfWeek(Carbon::MONDAY)->subWeeks($count - 1),
            'month' => now()->startOfMonth()->subMonths($count - 1),
        };

        if (! empty($teamIds)) {
            $keyExpr = match ($unit) {
                'day' => 'DATE(d_date)',
                'week' => 'DATE_SUB(DATE(d_date), INTERVAL WEEKDAY(d_date) DAY)', // Monday of that week
                'month' => "DATE_FORMAT(d_date, '%Y-%m')",
            };
            $own = $ownId ?? 0; // 0 never matches an advisor id

            try {
                DB::table('sales_orders')
                    ->whereNull('deleted_at')
                    ->whereNotIn('c_order_status', self::EXCLUDED_ORDER_STATUSES)
                    ->where('d_date', '>=', $from->toDateString())
                    ->where('d_date', '<', now()->addDay()->toDateString())
                    ->whereIn('farm_care_advisor_id', $teamIds)
                    ->selectRaw(
                        "$keyExpr as k,
                         COALESCE(SUM(CASE WHEN farm_care_advisor_id = ? THEN n_net_sales_amount ELSE 0 END),0) as own,
                         COALESCE(SUM(n_net_sales_amount),0) as team,
                         COALESCE(SUM(farm_care_advisor_id = ?),0) as own_orders,
                         COUNT(*) as team_orders",
                        [$own, $own]
                    )
                    ->groupBy('k')->get()
                    ->each(function ($r) use (&$rows) {
                        if (isset($rows[$r->k])) {
                            $rows[$r->k]['own'] = (float) $r->own;
                            $rows[$r->k]['team'] = (float) $r->team;
                            $rows[$r->k]['own_orders'] = (int) $r->own_orders;
                            $rows[$r->k]['team_orders'] = (int) $r->team_orders;
                        }
                    });
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $rows = array_values($rows);

        return [
            'rows' => $rows,
            'current' => $rows[count($rows) - 1],
            'previous' => $rows[count($rows) - 2],
        ];
    }
}