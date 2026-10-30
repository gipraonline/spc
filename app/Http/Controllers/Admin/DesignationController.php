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
    /** Level is never typed in: it is the parent's level + 1 (top of chart = 1). */
    private function levelFor(?int $parentId): int
    {
        return $parentId
            ? ((int) DesignationMaster::whereKey($parentId)->value('hierarchy_level')) + 1
            : 1;
    }

    /** When a designation moves, push the new levels down to everything under it. */
    private function cascadeLevels(DesignationMaster $designation): void
    {
        $children = DesignationMaster::where('parent_designation_id', $designation->n_designation_id)->get();

        foreach ($children as $child) {
            $child->update(['hierarchy_level' => $designation->hierarchy_level + 1]);
            $this->cascadeLevels($child);
        }
    }

    /** Active designations in org-chart order (parent, then its children), with ->depth set. */
    private function orderedTree(array $excludeIds = [])
    {
        $all = DesignationMaster::where('c_status', 'Y')
            ->whereNotIn('n_designation_id', $excludeIds)
            ->get();

        $ids = $all->pluck('n_designation_id')->all();
        $byParent = $all->groupBy(
            fn ($d) => in_array($d->parent_designation_id, $ids, true) ? $d->parent_designation_id : 0
        );

        $out = collect();
        $walk = function ($parentId, $depth) use (&$walk, $byParent, $out) {
            foreach ($byParent->get($parentId, collect())->sortBy('c_designation') as $d) {
                $d->depth = $depth;
                $out->push($d);
                $walk($d->n_designation_id, $depth + 1);
            }
        };
        $walk(0, 0);

        return $out;
    }

    public function index()
    {
        $designations = DesignationMaster::with('parent')
            ->where('c_status', 'Y')
            ->orderBy('hierarchy_level')
            ->paginate(10);

        return view('admin.designations.index', compact('designations'));
    }

    public function create()
    {
        // Shown as an indented org-chart tree so HR can see where each one sits.
        $parentOptions = $this->orderedTree();

        $hrDepartments = HrDepartment::orderBy('name')->get();

        // HR designations that already have a department, keyed by lowercase title
        $deptByTitle = \App\Models\Hr\Designation::whereNotNull('department_id')
            ->get()
            ->mapWithKeys(fn ($d) => [mb_strtolower(trim($d->title)) => $d->department_id]);

        // master designation id => department id (used to pre-select the dropdown)
        $parentDepartments = $parentOptions->mapWithKeys(fn ($d) => [
            $d->n_designation_id => $deptByTitle[mb_strtolower(trim($d->c_designation))] ?? null,
        ]);

        return view('admin.designations.create', compact('parentOptions', 'hrDepartments', 'parentDepartments'));
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
            // hierarchy_level is no longer entered by hand — it is worked out
            // from "Reports To" below. Several designations can share a level
            // (e.g. GM, HR Manager, Finance and Marketing Manager are Level 5);
            // parent_designation_id pins each one to its spot in the org chart.
            'parent_designation_id' => 'nullable|exists:designation_masters,n_designation_id',

            'c_status' => 'required|in:Y,N',
        ], [
            'c_designation.required' => 'Designation is required.',
            'c_designation.max' => 'Designation must not exceed 30 characters.',
            'c_designation.unique' => 'This designation already exists.',
            'c_designation.regex' => 'Only letters, spaces, & and / are allowed.',
            'identifier.required' => 'Identifier is required.',
            'identifier.unique' => 'This identifier already exists.',
            'parent_designation_id.exists' => 'Please select a valid reporting designation.',
            'c_status.required' => 'Please select a Status.',
        ]);

        $departmentId = $validated['department_id'] ?? null;
        unset($validated['department_id']);

        $validated['hierarchy_level'] = $this->levelFor($validated['parent_designation_id'] ?? null);

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

        $parentOptions = $this->orderedTree($excludedIds);

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

        // Level follows the chosen parent; sub-designations follow in turn.
        $validated['hierarchy_level'] = $this->levelFor($validated['parent_designation_id'] ?? null);

        $designation->update($validated);
        $this->cascadeLevels($designation->fresh());

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