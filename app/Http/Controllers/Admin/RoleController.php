<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Menu;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->paginate(10);

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
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
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
        'name' => 'required|unique:roles,name,' . $role->id,
    ]);

    $role->update([
        'name' => $request->name,
    ]);

    $role->menus()->sync($request->menus ?? []);
    // Sync permissions
    $role->syncPermissions($request->permissions ?? []);

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