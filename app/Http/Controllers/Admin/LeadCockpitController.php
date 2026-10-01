<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HierarchyScope;
use App\Services\LeadFollowupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Follow-up Cockpit: what to chase today, what is overdue, and how leads
 * turn into orders. Visibility follows the reporting line (HierarchyScope).
 */
class LeadCockpitController extends Controller
{
    public function index(Request $request, LeadFollowupService $service)
    {
        $admin = auth('web')->user();
        $scope = HierarchyScope::employeeIds($admin); // null = everyone

        $days = min(365, max(7, (int) $request->query('days', 30)));
        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        // Advisors this user may filter by.
        $advisors = DB::table('employee_masters')
            ->whereNull('deleted_at')
            ->when($scope !== null, fn ($q) => $q->whereIn('n_employee_id', $scope ?: [0]))
            ->whereIn('n_employee_id', fn ($q) => $q->select('n_fca_id')->from('leads')->whereNull('deleted_at')->whereNotNull('n_fca_id'))
            ->orderBy('c_employee_name')
            ->get(['n_employee_id as id', 'c_employee_name as name']);

        $fca = $request->filled('fca') ? (int) $request->query('fca') : null;
        if ($fca !== null && $scope !== null && ! in_array($fca, $scope, true)) {
            abort(403, 'You cannot view that advisor\'s leads.');
        }

        $isSupervisor = $scope === null || count($scope) > 1;

        return view('admin.Leads.cockpit', [
            'board' => $service->board($scope, $fca),
            'funnel' => $service->funnel($scope, $from, $to, $fca),
            'team' => ($isSupervisor && ! $fca) ? $service->byAdvisor($scope, $from, $to) : [],
            'advisors' => $advisors,
            'fca' => $fca,
            'days' => $days,
            'isSupervisor' => $isSupervisor,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
