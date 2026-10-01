<?php

namespace App\Http\Controllers\Hr;

use App\Models\DesignationMaster;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Holiday;
use App\Services\Hr\DesignationSyncService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $module = $this->abortUnlessModuleAllowed('organization');

        return view('hr.modules.organization', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'organization',
            'departments' => Department::withCount('employees')->orderBy('name')->get(),
            'designations' => Designation::with('department')->withCount('employees')->orderBy('title')->get(),
            'holidays' => Holiday::orderBy('holiday_date')->get(),
            'parentOptions' => DesignationMaster::where('c_status', 'Y')->orderBy('hierarchy_level')->get(),
        ]));
    }

    public function storeDepartment(Request $request)
    {
        $this->abortUnlessModuleAllowed('organization');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'code' => 'required|string|max:20',
        ]);

        Department::create($data);

        return back()->with('status', 'Department "'.$data['name'].'" added.');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $this->abortUnlessModuleAllowed('organization');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'code' => 'required|string|max:20',
        ]);

        $department->update($data);

        return back()->with('status', 'Department updated.');
    }

    public function storeDesignation(Request $request)
    {
        $this->abortUnlessModuleAllowed('organization');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'title' => DesignationSyncService::TITLE_RULES,
            'department_id' => 'nullable|exists:spc_hr.departments,id',
            // Optional: who this designation reports to in the SPC org chart.
            'parent_designation_id' => 'nullable|exists:designation_masters,n_designation_id',
        ], [
            'title.regex' => 'Only letters, spaces, & and / are allowed.',
            'title.max' => 'Designation must not exceed 30 characters.',
        ]);

        $departmentId = $data['department_id'] ?? null;

        // Same title + department already in HR -> nothing new to add.
        if (Designation::where('title', $data['title'])->where('department_id', $departmentId)->exists()) {
            return back()->withErrors(['title' => 'This designation already exists in that department.'])->withInput();
        }

        // Creates the SPC master row (if new) and the HR row together.
        DesignationSyncService::createFromHr(
            $data['title'],
            $departmentId ? (int) $departmentId : null,
            ! empty($data['parent_designation_id']) ? (int) $data['parent_designation_id'] : null,
        );

        return back()->with('status', 'Designation "'.$data['title'].'" added.');
    }

    public function updateDesignation(Request $request, Designation $designation)
    {
        $this->abortUnlessModuleAllowed('organization');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'title' => 'required|string|max:120',
            'department_id' => 'nullable|exists:spc_hr.departments,id',
        ]);

        $oldTitle = $designation->title;

        $designation->update($data);

        DesignationSyncService::renameEverywhere($oldTitle, $designation->title);

        return back()->with('status', 'Designation updated.');
    }

    public function destroyDesignation(Designation $designation)
    {
        $this->abortUnlessModuleAllowed('organization');
        abort_unless($this->isHrOrAbove(), 403);

        $title = $designation->title;

        try {
            $merged = DesignationSyncService::deleteFromHr($designation);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['designation' => $e->getMessage()]);
        }

        return back()->with('status', $merged
            ? 'Duplicate "'.$title.'" removed; its employees now use the identical entry.'
            : 'Designation "'.$title.'" deleted.');
    }

    public function storeHoliday(Request $request)
    {
        $this->abortUnlessModuleAllowed('organization');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'holiday_date' => 'required|date',
            'is_optional' => 'nullable|boolean',
        ]);

        $data['is_optional'] = $request->boolean('is_optional');

        Holiday::create($data);

        return back()->with('status', 'Holiday "'.$data['name'].'" added.');
    }

    public function destroyHoliday(Holiday $holiday)
    {
        $this->abortUnlessModuleAllowed('organization');
        abort_unless($this->isHrOrAbove(), 403);

        $holiday->delete();

        return back()->with('status', 'Holiday removed.');
    }
}
