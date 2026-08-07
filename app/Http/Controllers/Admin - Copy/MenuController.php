<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
         $menus = Menu::whereNull('parent_id')
        ->with(['children' => function ($q) {
            $q->orderBy('sort_order');
        }])
        ->orderBy('sort_order')
        ->paginate(1); // 10 parent menus per page

    return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::whereNull('parent_id')->get();

        return view('admin.menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'route_name' => 'nullable',
            'icon' => 'nullable',
            'parent_id' => 'nullable',
        ]);
         // Get the next sort order for the selected parent
    $sortOrder = Menu::where('parent_id', $request->parent_id)
        ->max('sort_order') + 1;

        Menu::create([
            'name'       => $request->name,
            'route_name' => $request->route_name,
            'icon'       => $request->icon,
            'parent_id'  => $request->parent_id,
            'sort_order' => $sortOrder,
            'status'     => $request->status ?? 1,
        ]);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->get();

        return view('admin.menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $menu->update([
            'name'       => $request->name,
            'route_name' => $request->route_name,
            'icon'       => $request->icon,
            'parent_id'  => $request->parent_id,
        ]);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back()->with('success', 'Menu deleted successfully.');
    }


   public static function getMenus()
{
    // Get the currently logged-in user
    $user = auth()->user();

    // Get all role IDs assigned to the logged-in user
    $roleIds = $user->roles->pluck('id');
    // Get only parent menus (menus without a parent)
    return Menu::whereNull('parent_id')
     // Get only active menus
        ->where('status', 1)
         // Filter menus based on user roles
        ->where(function ($query) use ($roleIds) {
         // Include parent menu if it is directly assigned to the user's role
            $query->whereHas('roles', function ($q) use ($roleIds) {
                $q->whereIn('roles.id', $roleIds);
            })
         // OR include parent menu if any of its child menus
        // are assigned to the user's role
            ->orWhereHas('children.roles', function ($q) use ($roleIds) {
                $q->whereIn('roles.id', $roleIds);
            });

        })
          // Load child menus for each parent menu
        ->with([
            'children' => function ($q) use ($roleIds) {
                $q->where('status', 1)
                  ->whereHas('roles', function ($q2) use ($roleIds) {
                      $q2->whereIn('roles.id', $roleIds);
                  })
                  ->orderBy('sort_order');
            }
        ])
        ->orderBy('sort_order')
        ->get();
}
}
