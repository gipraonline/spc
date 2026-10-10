<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hr\CommissionLine;
use App\Services\Hr\CommissionEngine;
use Illuminate\Support\Facades\DB;

/**
 * "My Commission" for associates (Farm Care Advisers / Tele Callers).
 *
 * Associates have no HR-portal access, so this lives on the main SPC site.
 * A person only ever sees their own lines, and only from statements that the
 * COO and MD have approved (awaiting payment) or Finance has paid. Anything
 * still with HR or in approval stays hidden.
 */
class MyCommissionController extends Controller
{
    public function index(CommissionEngine $engine)
    {
        $me = auth()->user()?->employee;
        abort_unless($me, 403);

        $id = (int) $me->n_employee_id;

        $lines = CommissionLine::query()
            ->join('commission_runs as r', 'r.id', '=', 'commission_lines.commission_run_id')
            ->where('commission_lines.employee_master_id', $id)
            ->whereIn('r.approval_stage', ['pending_finance', 'completed'])
            ->orderByDesc('r.year')->orderByDesc('r.month')
            ->get(['commission_lines.*', 'r.month', 'r.year', 'r.approval_stage', 'r.paid_at']);

        $code = optional($me->designation)->identifier;
        $rate = $code ? DB::connection('spc_hr')->table('commission_rates')
            ->where('designation_code', $code)
            ->whereDate('effective_from', '<=', now()->toDateString())
            ->orderByDesc('effective_from')->orderByDesc('id')
            ->value('percent') : null;

        // Running figure for this month, before it is calculated and approved.
        $hasThisMonth = $lines->contains(fn ($l) => (int) $l->month === now()->month && (int) $l->year === now()->year);
        $estimate = null;
        if (! $hasThisMonth) {
            $estimate = collect($engine->calculate(now()->month, now()->year)['rows'])
                ->firstWhere('employee_master_id', $id);
        }

        return view('admin.my-commission.index', [
            'lines' => $lines,
            'rate' => $rate,
            'estimate' => $estimate,
            'paidTotal' => (float) $lines->where('approval_stage', 'completed')->sum('commission_amount'),
            'awaitingTotal' => (float) $lines->where('approval_stage', 'pending_finance')->sum('commission_amount'),
            'designationLabel' => config('commission.designations')[$code] ?? ($me->designation->c_designation ?? 'Associate'),
        ]);
    }
}
