<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeMaster;
use App\Models\Menu;
use App\Models\Role;
use App\Services\Hr\EmployeeHrSyncService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        // Order by organizational level (same order as the Designations
        // list): Super Admin / Gipra Admin / Tele Caller have no level in
        // the org chart, so they're kept at the top, above Chairman.
        $roles = Role::query()
            ->leftJoin('designation_masters', 'designation_masters.identifier', '=', 'roles.identifier')
            ->select('roles.*')
            ->orderByRaw('COALESCE(designation_masters.hierarchy_level, -1) asc')
            ->orderBy('roles.id')
            ->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'identifier' => 'required|unique:roles,identifier',
            'hr_access' => 'nullable|in:super_admin,hr_admin,manager,employee',
        ]);

        Role::create([
            'name' => $request->name,
            'identifier' => $request->identifier,
            'guard_name' => 'web',
            'hr_access' => $request->hr_access ?: null,
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $parents = Menu::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        $permissions = Permission::orderBy('name')
            ->get()
            ->groupBy(function ($permission) {

                return explode('.', $permission->name)[0];

            });

        return view('admin.roles.edit', compact(
            'role',
            'parents',
            'permissions'
        ));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'identifier' => 'required|unique:roles,identifier,'.$role->id,
            'hr_access' => 'nullable|in:super_admin,hr_admin,manager,employee',
        ]);

        $hrAccessChanged = $role->hr_access !== ($request->hr_access ?: null);

        $role->update([
            'name' => $request->name,
            'identifier' => $request->identifier,
            'hr_access' => $request->hr_access ?: null,
        ]);

        $role->menus()->sync($request->menus ?? []);
        // Sync permissions
        $role->syncPermissions($request->permissions ?? []);

        // HR reads its access tier off this role's hr_access field, but only at
        // the moment an employee is synced. Changing hr_access here doesn't
        // touch spc_hr on its own, so re-sync everyone holding this role now,
        // otherwise their HR access stays stale until they're individually
        // re-saved in Admin > Employees.
        if ($hrAccessChanged) {
            $admins = $role->users()->get();

            $employeeIds = $admins->pluck('n_employee_id')->filter();

            EmployeeMaster::whereIn('n_employee_id', $employeeIds)
                ->get()
                ->each(fn (EmployeeMaster $employee) => EmployeeHrSyncService::sync($employee));

            // Admins with no linked employee (e.g. a standalone admin-only
            // login) don't go through EmployeeHrSyncService::sync() above —
            // update just their HR role directly instead.
            $admins->whereNull('n_employee_id')
                ->each(fn ($admin) => EmployeeHrSyncService::syncRoleForAdmin($admin));
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
