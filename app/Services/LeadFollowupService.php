<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Numbers behind the Follow-up Cockpit and the dashboard "follow-ups due" card.
 *
 * $fcaIds is the visibility scope from HierarchyScope::employeeIds():
 *   null = all leads, array = only leads owned by these employee ids.
 */
class LeadFollowupService
{
    /** Lead statuses that no longer need chasing. */
    public const CLOSED = ['won', 'lost', 'not-nterested', 'not-interested'];

    /** "Contacted or beyond" = anything except a brand-new lead. */
    private const NEW_STATUSES = ['new'];

    /** Reached genuine interest. */
    private const INTERESTED = ['interested', 'follow-up', 'negotiation', 'won'];

    protected function openLeads(?array $fcaIds, ?int $fcaFilter = null)
    {
        return DB::table('leads as l')
            ->leftJoin('employee_masters as em', 'em.n_employee_id', '=', 'l.n_fca_id')
            ->whereNull('l.deleted_at')
            ->where(function ($q) {
                $q->whereNull('l.c_lead_status')->orWhereNotIn('l.c_lead_status', self::CLOSED);
            })
            ->when($fcaIds !== null, fn ($q) => $q->whereIn('l.n_fca_id', $fcaIds ?: [0]))
            ->when($fcaFilter, fn ($q) => $q->where('l.n_fca_id', $fcaFilter));
    }

    /** Overdue / today / next-7-days lists (each capped) plus their true totals. */
    public function board(?array $fcaIds, ?int $fcaFilter = null, int $cap = 50): array
    {
        $today = Carbon::today();

        $select = [
            'l.n_lead_id as id', 'l.c_customer_name as name', 'l.n_mobile as mobile',
            'l.c_lead_status as status', 'l.priority', 'l.followup_type',
            'l.next_followup_date as due_date', 'l.next_followup_time as due_time',
            'l.remarks', 'em.c_employee_name as fca',
        ];

        $order = "FIELD(LOWER(COALESCE(l.priority,'')), 'high','medium','normal','low','') ASC";

        $fetch = function (callable $where) use ($fcaIds, $fcaFilter, $select, $order, $cap, $today) {
            $base = $this->openLeads($fcaIds, $fcaFilter)->whereNotNull('l.next_followup_date');
            $where($base);

            return [
                'total' => (clone $base)->count(),
                'rows' => $base->orderBy('l.next_followup_date')->orderByRaw($order)
                    ->orderBy('l.next_followup_time')->limit($cap)->get($select)
                    ->map(function ($r) use ($today) {
                        $r->days_overdue = $r->due_date ? (int) max(0, Carbon::parse($r->due_date)->diffInDays($today, false)) : 0;

                        return $r;
                    })->all(),
            ];
        };

        $overdue = $fetch(fn ($q) => $q->whereDate('l.next_followup_date', '<', $today));
        $due = $fetch(fn ($q) => $q->whereDate('l.next_followup_date', '=', $today));
        $upcoming = $fetch(fn ($q) => $q->whereDate('l.next_followup_date', '>', $today)
            ->whereDate('l.next_followup_date', '<=', $today->copy()->addDays(7)));

        // Open leads with no follow-up date at all - easy to forget.
        $unscheduled = $this->openLeads($fcaIds, $fcaFilter)->whereNull('l.next_followup_date')->count();

        return [
            'overdue' => $overdue,
            'today' => $due,
            'upcoming' => $upcoming,
            'unscheduled' => $unscheduled,
        ];
    }

    /** Cheap counts for the dashboard card / digest. */
    public function counts(?array $fcaIds, ?int $fcaFilter = null): array
    {
        $today = Carbon::today()->toDateString();

        $r = $this->openLeads($fcaIds, $fcaFilter)->whereNotNull('l.next_followup_date')
            ->selectRaw('SUM(l.next_followup_date < ?) as overdue, SUM(l.next_followup_date = ?) as today', [$today, $today])
            ->first();

        return ['overdue' => (int) ($r->overdue ?? 0), 'today' => (int) ($r->today ?? 0)];
    }

    /**
     * Lead -> order funnel for leads created in [$from, $to].
     * An "order" is a non-rejected/cancelled order placed on/after the lead date
     * by a customer with the same mobile number (last 10 digits).
     */
    public function funnel(?array $fcaIds, Carbon $from, Carbon $to, ?int $fcaFilter = null): array
    {
        $scope = fn ($q) => $q->whereNull('l.deleted_at')
            ->whereBetween('l.created_at', [$from, $to])
            ->when($fcaIds !== null, fn ($x) => $x->whereIn('l.n_fca_id', $fcaIds ?: [0]))
            ->when($fcaFilter, fn ($x) => $x->where('l.n_fca_id', $fcaFilter));

        $byStatus = $scope(DB::table('leads as l'))
            ->selectRaw("LOWER(COALESCE(l.c_lead_status,'new')) as s, COUNT(*) as n")
            ->groupBy('s')->pluck('n', 's')->all();

        $total = array_sum($byStatus);
        $contacted = $total - array_sum(array_intersect_key($byStatus, array_flip(self::NEW_STATUSES)));
        $interested = array_sum(array_intersect_key($byStatus, array_flip(self::INTERESTED)));

        $ordersQuery = fn (string $extra = '') => $scope(DB::table('leads as l'))
            ->join('customer_masters as cm', function ($j) {
                $j->whereRaw("cm.n_mobile = RIGHT(REPLACE(REPLACE(l.n_mobile,' ',''),'+',''),10)")
                    ->whereNull('cm.deleted_at');
            })
            ->join('sales_orders as so', function ($j) {
                $j->on('so.n_customer_id', '=', 'cm.n_customer_id')
                    ->whereNull('so.deleted_at')
                    ->whereRaw('DATE(so.d_date) >= DATE(l.created_at)')
                    ->whereNotIn('so.c_order_status', ['Rejected', 'Cancelled']);
            })
            ->when($extra !== '', fn ($q) => $q->whereRaw($extra));

        $ordered = (int) $ordersQuery()->distinct()->count('l.n_lead_id');
        $approved = (int) $ordersQuery("so.c_order_status = 'Approved'")->distinct()->count('l.n_lead_id');
        $delivered = (int) $ordersQuery("so.delivery_status = 'Completed'")->distinct()->count('l.n_lead_id');

        $stages = [
            ['label' => 'Leads created', 'n' => $total],
            ['label' => 'Contacted', 'n' => $contacted],
            ['label' => 'Interested', 'n' => $interested],
            ['label' => 'Order placed', 'n' => $ordered],
            ['label' => 'Approved', 'n' => $approved],
            ['label' => 'Delivered', 'n' => $delivered],
        ];

        return [
            'stages' => $stages,
            'conversion_pct' => $total ? round($ordered / $total * 100, 1) : null,
            'lost' => (int) (($byStatus['lost'] ?? 0) + ($byStatus['not-nterested'] ?? 0) + ($byStatus['not-interested'] ?? 0)),
        ];
    }

    /** One row per advisor - for supervisors reviewing their team. */
    public function byAdvisor(?array $fcaIds, Carbon $from, Carbon $to): array
    {
        $today = Carbon::today()->toDateString();

        $open = $this->openLeads($fcaIds)
            ->selectRaw('l.n_fca_id, COUNT(*) as open_leads, SUM(l.next_followup_date < ?) as overdue, SUM(l.next_followup_date = ?) as today', [$today, $today])
            ->groupBy('l.n_fca_id')->get()->keyBy('n_fca_id');

        $created = DB::table('leads as l')->whereNull('l.deleted_at')
            ->whereBetween('l.created_at', [$from, $to])
            ->when($fcaIds !== null, fn ($q) => $q->whereIn('l.n_fca_id', $fcaIds ?: [0]))
            ->selectRaw("l.n_fca_id, COUNT(*) as created, SUM(LOWER(COALESCE(l.c_lead_status,'')) = 'won') as won")
            ->groupBy('l.n_fca_id')->get()->keyBy('n_fca_id');

        $ids = $open->keys()->merge($created->keys())->unique()->filter()->values();

        $names = DB::table('employee_masters')->whereIn('n_employee_id', $ids)
            ->pluck('c_employee_name', 'n_employee_id');

        return $ids->map(fn ($id) => [
            'id' => (int) $id,
            'name' => $names[$id] ?? ('#'.$id),
            'open' => (int) ($open[$id]->open_leads ?? 0),
            'overdue' => (int) ($open[$id]->overdue ?? 0),
            'today' => (int) ($open[$id]->today ?? 0),
            'created' => (int) ($created[$id]->created ?? 0),
            'won' => (int) ($created[$id]->won ?? 0),
        ])->sortByDesc('overdue')->values()->all();
    }
}