<?php

namespace App\Services\Hr;

use App\Services\Hr\AssociateScope;
use App\Services\HierarchyScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Turns approved + paid sales orders into monthly incentive payouts.
 *
 * Eligible sale  : sales_orders.c_order_status = config('spc.incentive.order_status')
 *                  AND LOWER(payment_status) IN config('spc.incentive.payment_status')
 *                  dated inside the month, credited to farm_care_advisor_id.
 *
 * basis = own    : the person's own eligible net sales.
 * basis = team   : eligible net sales of everyone below them in the reporting
 *                  line (employee_masters.reporting_to), at any depth - an override.
 *
 * Rules (spc_hr.incentive_rules) are marginal slabs: each rule pays
 * incentive_percent on the part of the base that falls between slab_from and
 * slab_to (slab_to NULL = no upper limit), plus flat_amount when the base is
 * inside the slab. A rule with designation_code applies to that SPC designation
 * (designation_masters.identifier) only; with no code it applies to the HR portal role.
 *
 * min_achievement_pct: if the employee has a net_sales target for the cycle the
 * month falls in, the monthly target is (cycle target / months in cycle) and
 * nothing is paid for that rule below the given achievement. No target = no gate.
 *
 * Payouts that are no longer 'pending' are never touched, so a re-run cannot
 * change money that has been approved or paid.
 */
class IncentiveEngine
{
    /**
     * @return array{month:string, employees:int, created:int, updated:int, locked:int, removed:int, total:float}
     */
    public function run(int $year, int $month): array
    {
        $from = Carbon::create($year, $month, 1)->startOfDay();
        $to = $from->copy()->endOfMonth();
        $hr = DB::connection('spc_hr');

        $rules = $hr->table('incentive_rules')
            ->where('is_active', 1)
            ->where('effective_from', '<=', $to->toDateString())
            ->where(fn ($q) => $q->whereNull('effective_to')->orWhere('effective_to', '>=', $from->toDateString()))
            ->orderBy('slab_from')->get();

        $employees = $hr->table('employees as e')
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->where('e.employment_status', '!=', 'exited')
            ->whereNotNull('e.employee_master_id')
            // associates (Farm Care Advisers / Tele Callers) earn commission, not incentives
            ->whereNotIn('e.employee_master_id', AssociateScope::masterIds())
            ->get(['e.id', 'e.employee_master_id as master_id', 'u.role', 'u.name']);

        // Unapproved incentives already created for associates are dropped; approved / paid ones are never touched.
        $associateHrIds = AssociateScope::hrEmployeeIds();
        if ($associateHrIds !== []) {
            $hr->table('incentive_payouts')->where('status', 'pending')->whereIn('employee_id', $associateHrIds)->delete();
        }

        $summary = ['month' => $from->format('Y-m'), 'employees' => $employees->count(), 'created' => 0, 'updated' => 0, 'locked' => 0, 'removed' => 0, 'total' => 0.0];

        if ($employees->isEmpty()) {
            return $summary;
        }

        $masterIds = $employees->pluck('master_id')->map(fn ($i) => (int) $i)->all();
        $codes = $this->designationCodes($masterIds);
        $own = $this->eligibleSales($from, $to);
        $childrenMap = HierarchyScope::childrenMap();
        $targets = $this->monthlyTargets($employees->pluck('id')->all(), $from);

        foreach ($employees as $emp) {
            $code = $codes[$emp->master_id] ?? null;

            foreach (['own', 'team'] as $basis) {
                $applicable = $rules->filter(fn ($r) => $r->basis === $basis && $this->ruleMatches($r, $emp->role, $code));
                if ($applicable->isEmpty()) {
                    continue;
                }

                $base = $basis === 'own'
                    ? (float) ($own[$emp->master_id] ?? 0)
                    : (float) collect(HierarchyScope::descendants((int) $emp->master_id, $childrenMap))->sum(fn ($id) => $own[$id] ?? 0);

                $monthlyTarget = $targets[$emp->id] ?? null;
                $ownAchievement = $monthlyTarget ? round(((float) ($own[$emp->master_id] ?? 0)) / $monthlyTarget * 100, 1) : null;

                $amount = 0.0;
                $lines = [];
                foreach ($applicable as $r) {
                    $gate = $r->min_achievement_pct !== null && $ownAchievement !== null && $ownAchievement < (float) $r->min_achievement_pct;
                    $pay = $gate ? 0.0 : $this->ruleAmount($r, $base);
                    $amount += $pay;
                    $lines[] = [
                        'rule_id' => (int) $r->id, 'rule' => $r->name,
                        'from' => (float) $r->slab_from, 'to' => $r->slab_to !== null ? (float) $r->slab_to : null,
                        'percent' => $r->incentive_percent !== null ? (float) $r->incentive_percent : null,
                        'flat' => $r->flat_amount !== null ? (float) $r->flat_amount : null,
                        'blocked_by_min_achievement' => $gate, 'amount' => round($pay, 2),
                    ];
                }

                $amount = round($amount, 2);
                $detail = json_encode([
                    'designation' => $code, 'basis' => $basis, 'base_sales' => round($base, 2),
                    'monthly_target' => $monthlyTarget ? round($monthlyTarget, 2) : null,
                    'own_achievement_pct' => $ownAchievement, 'lines' => $lines,
                ]);

                $existing = $hr->table('incentive_payouts')
                    ->where(['employee_id' => $emp->id, 'month' => $month, 'year' => $year, 'basis' => $basis])->first();

                if ($existing && $existing->status !== 'pending') {
                    $summary['locked']++;
                    continue;
                }

                if ($amount <= 0) {
                    if ($existing) {
                        $hr->table('incentive_payouts')->where('id', $existing->id)->delete();
                        $summary['removed']++;
                    }
                    continue;
                }

                $row = [
                    'incentive_rule_id' => $lines[0]['rule_id'] ?? null,
                    'achieved_value' => round($base, 2),
                    'incentive_amount' => $amount,
                    'calc_detail' => $detail,
                ];

                if ($existing) {
                    $hr->table('incentive_payouts')->where('id', $existing->id)->update($row);
                    $summary['updated']++;
                } else {
                    $hr->table('incentive_payouts')->insert($row + [
                        'employee_id' => $emp->id, 'month' => $month, 'year' => $year, 'basis' => $basis, 'status' => 'pending',
                    ]);
                    $summary['created']++;
                }
                $summary['total'] += $amount;
            }
        }

        $summary['total'] = round($summary['total'], 2);

        return $summary;
    }

    /** Amount one rule pays on a base (marginal slab). Public so the UI can preview a rule. */
    public function ruleAmount(object $rule, float $base): float
    {
        $from = (float) ($rule->slab_from ?? 0);
        $to = $rule->slab_to !== null ? (float) $rule->slab_to : null;
        $amount = 0.0;

        if ($rule->incentive_percent !== null && $base > $from) {
            $portion = min($base, $to ?? $base) - $from;
            $amount += max(0.0, $portion) * (float) $rule->incentive_percent / 100;
        }

        if ($rule->flat_amount !== null && $base > 0 && $base >= $from && ($to === null || $base < $to)) {
            $amount += (float) $rule->flat_amount;
        }

        return $amount;
    }

    private function ruleMatches(object $rule, string $role, ?string $code): bool
    {
        if ($rule->designation_code) {
            return $code !== null && strcasecmp($rule->designation_code, $code) === 0;
        }

        return $rule->applies_to_role === $role;
    }

    /** employee_masters id => SPC designation identifier (FCA, FCO, TL ...). */
    private function designationCodes(array $masterIds): array
    {
        return DB::table('employee_masters as em')
            ->join('designation_masters as d', 'd.n_designation_id', '=', 'em.n_designation_id')
            ->whereIn('em.n_employee_id', $masterIds)
            ->pluck('d.identifier', 'em.n_employee_id')->all();
    }

    /** advisor (employee_masters id) => eligible net sales in the month. */
    private function eligibleSales(Carbon $from, Carbon $to): array
    {
        $paid = array_map('strtolower', (array) config('spc.incentive.payment_status', ['paid']));

        return DB::table('sales_orders')
            ->whereNull('deleted_at')
            ->where('c_order_status', config('spc.incentive.order_status', 'Approved'))
            ->whereIn(DB::raw('LOWER(payment_status)'), $paid)
            ->whereBetween('d_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotNull('farm_care_advisor_id')
            ->groupBy('farm_care_advisor_id')
            ->selectRaw('farm_care_advisor_id, COALESCE(SUM(n_net_sales_amount),0) as s')
            ->pluck('s', 'farm_care_advisor_id')->map(fn ($v) => (float) $v)->all();
    }

    /** hr employee id => monthly net_sales target (cycle target / months in cycle). */
    private function monthlyTargets(array $employeeIds, Carbon $monthStart): array
    {
        $rows = DB::connection('spc_hr')->table('sales_targets as t')
            ->join('appraisal_cycles as c', 'c.id', '=', 't.appraisal_cycle_id')
            ->where('t.metric', 'net_sales')
            ->whereIn('t.employee_id', $employeeIds)
            ->whereDate('c.start_date', '<=', $monthStart->copy()->endOfMonth())
            ->whereDate('c.end_date', '>=', $monthStart)
            ->get(['t.employee_id', 't.target_value', 'c.start_date', 'c.end_date']);

        $out = [];
        foreach ($rows as $r) {
            $months = max(1, Carbon::parse($r->start_date)->startOfMonth()->diffInMonths(Carbon::parse($r->end_date)->startOfMonth()) + 1);
            if ((float) $r->target_value > 0) {
                $out[$r->employee_id] = (float) $r->target_value / $months;
            }
        }

        return $out;
    }
}
