<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use App\Services\AuditTrail;


class PermissionController extends Controller
{
    
public function index(Request $request)
{
    $groupedPermissions = Permission::orderBy('name')
        ->get()
        ->groupBy(function ($permission) {
            return ucfirst(explode('.', $permission->name)[0]);
        });

    $perPage = 10;

    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    $currentItems = $groupedPermissions->slice(
        ($currentPage - 1) * $perPage,
        $perPage,
        true // preserve keys
    );

    $permissions = new LengthAwarePaginator(
        $currentItems,
        $groupedPermissions->count(),
        $perPage,
        $currentPage,
        [
            'path' => request()->url(),
            'query' => request()->query(),
        ]
    );

    return view('admin.permissions.index', compact('permissions'));
}

public function create(Request $request)
{
    $parents = Menu::whereNull('parent_id')
        ->where('status', 1)
        ->with('children')
        ->orderBy('sort_order')
        ->get();
// dd($parents->toArray());
    $selectedModule = $request->module;
    $existingActions = [];

   if ($selectedModule) {

    $module = Str::slug($selectedModule, '-');

    $existingActions = Permission::where('name', 'like', $module . '.%')
        ->pluck('name')
        ->map(function ($permission) {
            return str_replace('-', ' ', explode('.', $permission)[1]);
        })
        ->toArray();
}

    return view(
        'admin.permissions.create',
        compact(
            'parents',
            'selectedModule',
            'existingActions'
        )
    );
}
public function store(Request $request)
{
    $request->validate([
        'module' => 'required',
        'actions' => 'required|array|min:1',
    ]);

   foreach ($request->actions as $action) {

    $permission = Permission::firstOrCreate([
        'name' => Str::slug($request->module, '-') . '.' . Str::slug($action, '-'),
        'guard_name' => 'web',
    ]);

    if ($permission->wasRecentlyCreated) {
        AuditTrail::record('roles', 'CREATE', (int) $permission->id, null, [
            'name' => $permission->name,
            '_entity' => 'Permission',
            '_subject' => $permission->name,
        ]);
    }

}

    return redirect()
        ->route('admin.permissions.index')
        ->with('success', 'Permissions created successfully.');
}

      public function edit(Permission $permission)
        {
            return view('admin.permissions.edit', compact('permission'));
        }  
    public function update(Request $request, Permission $permission)
        {
            $request->validate([
                'name' => 'required|unique:permissions,name,' . $permission->id,
            ]);

            $oldName = $permission->name;

            $permission->update([
                'name' => $request->name,
            ]);

            if ($oldName !== $permission->name) {
                AuditTrail::record('roles', 'UPDATE', (int) $permission->id, ['name' => $oldName], [
                    'name' => $permission->name,
                    '_entity' => 'Permission',
                    '_subject' => $permission->name,
                ]);
            }

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission updated successfully.');
        }
    public function destroy(Permission $permission)
        {
            AuditTrail::record('roles', 'DELETE', (int) $permission->id, [
                'name' => $permission->name,
                '_entity' => 'Permission',
                '_subject' => $permission->name,
            ], null);

            $permission->delete();

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission deleted successfully.');
        }
}