<?php

namespace App\Services\Hr;

use App\Models\Hr\CommissionLine;
use App\Models\Hr\CommissionRun;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Monthly commission for Farm Care Advisers and Tele Callers.
 *
 * Eligible sale : exactly the incentive rule - sales_orders.c_order_status =
 *                 config('spc.incentive.order_status') and payment_status in
 *                 config('spc.incentive.payment_status'), dated inside the month,
 *                 credited to farm_care_advisor_id.
 * Commission    : eligible net sales (n_net_sales_amount) x the percentage set by
 *                 Office Administration for the person's designation. The rate used
 *                 is the latest one whose effective date is on or before month end.
 *
 * A calculated statement is a snapshot: changing a rate later never alters it.
 */
class CommissionEngine
{
    /** @return array{rows: array<int,array>, totals: array, missing_rates: array<int,string>} */
    public function calculate(int $month, int $year): array
    {
        $from = Carbon::create($year, $month, 1)->startOfDay();
        $to = $from->copy()->endOfMonth();

        $rates = DB::connection('spc_hr')->table('commission_rates')
            ->whereDate('effective_from', '<=', $to->toDateString())
            ->orderBy('effective_from')->orderBy('id')->get()
            ->groupBy('designation_code')
            ->map(fn ($g) => (float) $g->last()->percent);

        $people = DB::table('employee_masters as m')
            ->join('designation_masters as d', 'd.n_designation_id', '=', 'm.n_designation_id')
            ->whereIn('d.identifier', array_keys(config('commission.designations')))
            ->get(['m.n_employee_id', 'm.c_employee_code', 'm.c_employee_name', 'd.identifier'])
            ->keyBy('n_employee_id');

        $paid = array_map('strtolower', (array) config('spc.incentive.payment_status', ['paid']));

        $sales = $people->isEmpty() ? collect() : DB::table('sales_orders')
            ->whereNull('deleted_at')
            ->where('c_order_status', config('spc.incentive.order_status', 'Approved'))
            ->whereIn(DB::raw('LOWER(payment_status)'), $paid)
            ->whereBetween('d_date', [$from->toDateString(), $to->toDateString()])
            ->whereIn('farm_care_advisor_id', $people->keys()->all())
            ->groupBy('farm_care_advisor_id')
            ->selectRaw('farm_care_advisor_id, COUNT(*) as orders, COALESCE(SUM(n_net_sales_amount),0) as s')
            ->get()->keyBy('farm_care_advisor_id');

        $rows = [];
        $missing = [];
        foreach ($sales as $id => $s) {
            $p = $people[$id];
            $net = (float) $s->s;
            if ($net <= 0) {
                continue;
            }
            $rate = $rates[$p->identifier] ?? null;
            if ($rate === null) {
                $missing[$p->identifier] = config('commission.designations')[$p->identifier] ?? $p->identifier;
            }
            $rate = $rate ?? 0.0;

            $rows[] = [
                'employee_master_id' => (int) $id,
                'employee_code' => $p->c_employee_code,
                'employee_name' => $p->c_employee_name,
                'designation_code' => $p->identifier,
                'orders_count' => (int) $s->orders,
                'sales_amount' => round($net, 2),
                'rate_percent' => $rate,
                'commission_amount' => round($net * $rate / 100, 2),
            ];
        }

        usort($rows, fn ($a, $b) => strcmp((string) $a['employee_code'], (string) $b['employee_code']));

        return [
            'rows' => $rows,
            'totals' => [
                'count' => count($rows),
                'sales' => round(array_sum(array_column($rows, 'sales_amount')), 2),
                'commission' => round(array_sum(array_column($rows, 'commission_amount')), 2),
            ],
            'missing_rates' => $missing,
        ];
    }

    /** Create the month's statement, or rebuild its lines while it is still with HR. */
    public function generate(int $month, int $year, ?int $userId): CommissionRun
    {
        $calc = $this->calculate($month, $year);

        return DB::connection('spc_hr')->transaction(function () use ($month, $year, $userId, $calc) {
            $run = CommissionRun::where('month', $month)->where('year', $year)->first();

            if ($run) {
                CommissionLine::where('commission_run_id', $run->id)->delete();
            } else {
                $run = CommissionRun::create(['month' => $month, 'year' => $year, 'approval_stage' => 'draft']);
            }

            foreach ($calc['rows'] as $row) {
                CommissionLine::create($row + ['commission_run_id' => $run->id]);
            }

            $run->update([
                'approval_stage' => 'draft',
                'advisor_count' => $calc['totals']['count'],
                'total_sales' => $calc['totals']['sales'],
                'total_commission' => $calc['totals']['commission'],
                'generated_by' => $userId,
                'generated_at' => now(),
                'workflow_remarks' => null,
            ]);

            return $run;
        });
    }
}
