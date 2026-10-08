<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignationMaster;
use App\Models\Hr\Department as HrDepartment;
use App\Services\DashboardCardVisibility;
use App\Services\AuditTrail;
use App\Services\Hr\DesignationSyncService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = DesignationMaster::with('parent')
            ->where('c_status', 'Y')
            ->orderBy('hierarchy_level')
            ->get();

        return view('admin.designations.index', compact('designations'));
    }

    public function create()
    {
        // Ordered by level so the "Reports To" dropdown reads top-down.
        $parentOptions = DesignationMaster::where('c_status', 'Y')
            ->orderBy('hierarchy_level')
            ->get();

        $hrDepartments = HrDepartment::orderBy('name')->get();

        return view('admin.designations.create', compact('parentOptions', 'hrDepartments'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
        'c_designation' => [
        'required',
        'string',
        'max:30',
        'unique:designation_masters,c_designation',

        // Letters, spaces, & and / only
        'regex:/^[A-Za-z &\/]+$/',
    ],
    // Optional: HR department the matching HR designation is filed under.
    'department_id' => 'nullable|integer|exists:spc_hr.departments,id',
     'identifier' => [
            'required',
            'string',
            'max:50',
            'unique:designation_masters,identifier',
    ],
    // Multiple designations can share a hierarchy_level (e.g. GM, HR
    // Manager, Finance and Marketing Manager are all Level 5), so this
    // is no longer unique — parent_designation_id is what pins each
    // one to its actual spot in the org chart.
    'hierarchy_level' => 'required|integer|min:1',
    'parent_designation_id' => 'nullable|exists:designation_masters,n_designation_id',

        'c_status' => 'required|in:Y,N',
        'c_status.required' => 'Please select a Status.',

    ], [
        'c_designation.required' => 'Designation is required.',
        'c_designation.max' => 'Designation must not exceed 30 characters.',
        'c_designation.unique' => 'This designation already exists.',
        'c_designation.regex' => 'Only letters, spaces, & and / are allowed.',
        'identifier.required' => 'Identifier is required.',
        'identifier.unique' => 'This identifier already exists.',
        'parent_designation_id.exists' => 'Please select a valid reporting designation.',

    ]);

        $departmentId = $validated['department_id'] ?? null;
        unset($validated['department_id']);

        $master = DesignationMaster::create($validated);

        // Mirror into the HR module's designation list.
        DesignationSyncService::pushMasterToHr($master, $departmentId ? (int) $departmentId : null);

        return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully');
    }

    public function show(DesignationMaster $designation)
    {
        return view('admin.designations.show', compact('designation'));
    }

    public function edit(DesignationMaster $designation)
    {
        // A designation can't report to itself or to one of its own
        // descendants (that would create a loop).
        $excludedIds = array_merge(
            [$designation->n_designation_id],
            $designation->descendantIds()
        );

        $parentOptions = DesignationMaster::where('c_status', 'Y')
            ->whereNotIn('n_designation_id', $excludedIds)
            ->orderBy('hierarchy_level')
            ->get();

        // Dashboard cards this designation can see. An unconfigured
        // designation sees everything, so every box starts ticked.
        $cardCatalog = DashboardCardVisibility::catalog();
        $cardGroups = DashboardCardVisibility::groups();
        $selectedCards = DashboardCardVisibility::forDesignation((int) $designation->n_designation_id)
            ?? array_keys($cardCatalog);

        return view('admin.designations.edit', compact(
            'designation', 'parentOptions', 'cardCatalog', 'cardGroups', 'selectedCards'
        ));
    }

    public function update(Request $request, DesignationMaster $designation)
    {
        $validated = $request->validate([
            'c_designation' => 'required|string|unique:designation_masters,c_designation,'.$designation->n_designation_id.',n_designation_id',
            'hierarchy_level' => 'required|integer|min:1',
            'parent_designation_id' => [
                'nullable',
                'exists:designation_masters,n_designation_id',
                Rule::notIn(array_merge([$designation->n_designation_id], $designation->descendantIds())),
            ],
            'c_status' => 'required|in:Y,N',
            'dashboard_cards' => 'nullable|array',
            'dashboard_cards.*' => ['string', Rule::in(array_keys(DashboardCardVisibility::catalog()))],
        ], [
            'parent_designation_id.not_in' => 'A designation cannot report to itself or one of its own subordinates.',
        ]);

        $oldTitle = $designation->c_designation;

        $cards = $validated['dashboard_cards'] ?? [];
        unset($validated['dashboard_cards']);

        $designation->update($validated);

        // Only touch card settings when the form actually carried that section.
        if ($request->boolean('dashboard_cards_present')) {
            $cardsBefore = DashboardCardVisibility::forDesignation((int) $designation->n_designation_id);
            DashboardCardVisibility::save((int) $designation->n_designation_id, $cards);

            $cardsBefore = $cardsBefore === null ? array_keys(DashboardCardVisibility::catalog()) : $cardsBefore;
            $shown = array_values(array_intersect($cards, array_keys(DashboardCardVisibility::catalog())));
            $nowShown = implode(', ', array_diff($shown, $cardsBefore)) ?: null;
            $nowHidden = implode(', ', array_diff($cardsBefore, $shown)) ?: null;

            if ($nowShown || $nowHidden) {
                AuditTrail::record('designations', 'UPDATE', (int) $designation->n_designation_id, null, array_filter([
                    'dashboard_cards_shown' => $nowShown,
                    'dashboard_cards_hidden' => $nowHidden,
                    '_entity' => 'Dashboard cards',
                    '_subject' => $designation->c_designation,
                ]));
            }
        }

        DesignationSyncService::renameEverywhere($oldTitle, $designation->c_designation);

        return redirect()->route('admin.designations.index')->with('success', 'Designation updated successfully');
    }

    public function destroy(DesignationMaster $designation)
    {
        try {
            DesignationSyncService::deleteFromMaster($designation);
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.designations.index')->with('error', $e->getMessage());
        }

        return redirect()->route('admin.designations.index')->with('success', 'Designation deleted successfully');
    }
}
