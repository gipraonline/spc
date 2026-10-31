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
    /** Default level when none is typed: parent's level + 1 (top of chart = 1). */
    private function levelFor(?int $parentId): int
    {
        return $parentId
            ? ((int) DesignationMaster::whereKey($parentId)->value('hierarchy_level')) + 1
            : 1;
    }

    /** Tidy a typed identifier: UPPER_CASE, letters/digits/underscores only. */
    private function normalizeIdentifier(?string $value): string
    {
        $value = strtoupper(preg_replace('/[^A-Za-z0-9]+/', '_', trim((string) $value)));

        return trim($value, '_');
    }

    /**
     * Build a unique identifier from the designation name.
     *   "Senior Manager" -> SM        "Chairman" -> CHAIRMAN
     * If taken, a number is appended: SM, SM_2, SM_3 ...
     */
    private function generateIdentifier(string $name, ?int $ignoreId = null): string
    {
        $words = preg_split('/[^A-Za-z0-9]+/', trim($name), -1, PREG_SPLIT_NO_EMPTY);

        if (! $words) {
            $base = 'DESG';
        } elseif (count($words) === 1) {
            $base = strtoupper($words[0]);
        } else {
            $base = strtoupper(implode('', array_map(fn ($w) => $w[0], $words)));
        }

        $base = substr($base, 0, 40);
        $candidate = $base;
        $i = 2;

        while (
            DesignationMaster::where('identifier', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('n_designation_id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base . '_' . $i++;
        }

        return $candidate;
    }

    /**
     * When a designation's level changes by N, move everything under it by N
     * too. This keeps the gaps between levels exactly as HR set them instead of
     * flattening every child to parent + 1.
     */
    private function shiftDescendantLevels(DesignationMaster $designation, int $delta): void
    {
        if ($delta === 0) {
            return;
        }

        $ids = $designation->descendantIds();
        if (! $ids) {
            return;
        }

        foreach (DesignationMaster::whereIn('n_designation_id', $ids)->get() as $child) {
            $child->update(['hierarchy_level' => max(0, (int) $child->hierarchy_level + $delta)]);
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

        // Identifiers already in use, so the form can suggest a free one live.
        $existingIdentifiers = DesignationMaster::whereNotNull('identifier')
            ->pluck('identifier')
            ->map(fn ($i) => strtoupper($i))
            ->values();

        return view('admin.designations.create', compact(
            'parentOptions', 'hrDepartments', 'parentDepartments', 'existingIdentifiers'
        ));
    }

    public function store(Request $request)
    {
        // Clean the typed identifier first so uniqueness is checked on the final value.
        $request->merge(['identifier' => $this->normalizeIdentifier($request->input('identifier'))]);

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
            // Left blank -> generated from the name below.
            'identifier' => [
                'nullable',
                'string',
                'max:50',
                'unique:designation_masters,identifier',
            ],
            // Optional: if left blank it is parent's level + 1. Several
            // designations can share a level; parent_designation_id pins each
            // one to its spot in the org chart.
            'hierarchy_level' => 'nullable|integer|min:0|max:50',
            'parent_designation_id' => 'nullable|exists:designation_masters,n_designation_id',

            'c_status' => 'required|in:Y,N',
        ], [
            'c_designation.required' => 'Designation is required.',
            'c_designation.max' => 'Designation must not exceed 30 characters.',
            'c_designation.unique' => 'This designation already exists.',
            'c_designation.regex' => 'Only letters, spaces, & and / are allowed.',
            'identifier.unique' => 'This identifier already exists.',
            'hierarchy_level.integer' => 'Hierarchy level must be a whole number.',
            'hierarchy_level.min' => 'Hierarchy level cannot be negative.',
            'parent_designation_id.exists' => 'Please select a valid reporting designation.',
            'c_status.required' => 'Please select a Status.',
        ]);

        $departmentId = $validated['department_id'] ?? null;
        unset($validated['department_id']);

        if (($validated['identifier'] ?? '') === '') {
            $validated['identifier'] = $this->generateIdentifier($validated['c_designation']);
        }

        if (! isset($validated['hierarchy_level']) || $validated['hierarchy_level'] === '') {
            $validated['hierarchy_level'] = $this->levelFor($validated['parent_designation_id'] ?? null);
        }

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
        $request->merge(['identifier' => $this->normalizeIdentifier($request->input('identifier'))]);

        $validated = $request->validate([
            'c_designation' => 'required|string|unique:designation_masters,c_designation,'.$designation->n_designation_id.',n_designation_id',
            'identifier' => [
                'required',
                'string',
                'max:50',
                Rule::unique('designation_masters', 'identifier')
                    ->ignore($designation->n_designation_id, 'n_designation_id'),
            ],
            'hierarchy_level' => 'required|integer|min:0|max:50',
            'parent_designation_id' => [
                'nullable',
                'exists:designation_masters,n_designation_id',
                Rule::notIn(array_merge([$designation->n_designation_id], $designation->descendantIds())),
            ],
            'c_status' => 'required|in:Y,N',
            'dashboard_cards' => 'nullable|array',
            'dashboard_cards.*' => ['string', Rule::in(array_keys(DashboardCardVisibility::catalog()))],
        ], [
            'identifier.required' => 'Identifier is required.',
            'identifier.unique' => 'This identifier already exists.',
            'hierarchy_level.required' => 'Hierarchy level is required.',
            'hierarchy_level.integer' => 'Hierarchy level must be a whole number.',
            'hierarchy_level.min' => 'Hierarchy level cannot be negative.',
            'parent_designation_id.not_in' => 'A designation cannot report to itself or one of its own subordinates.',
        ]);

        $oldTitle = $designation->c_designation;
        $oldLevel = (int) $designation->hierarchy_level;

        $cards = $validated['dashboard_cards'] ?? [];
        unset($validated['dashboard_cards']);

        $designation->update($validated);

        // Everything below moves up/down by the same amount this one did.
        $this->shiftDescendantLevels($designation->fresh(), (int) $validated['hierarchy_level'] - $oldLevel);

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