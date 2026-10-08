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
        ->orderBy('id')
        ->paginate(10); // 10 parent menus per page

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
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer|exists:menus,id',
            'sort_order' => 'nullable|integer|min:0|max:65535',
        ], [
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min' => 'Sort order cannot be negative.',
        ]);

        // Use the sort order that was typed in; if it was left empty, put the
        // new menu at the end of the selected parent (or the main menu).
        $sortOrder = $request->filled('sort_order')
            ? (int) $request->sort_order
            : ((int) Menu::where('parent_id', $request->input('parent_id'))->max('sort_order')) + 1;

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
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => ['nullable', 'integer', 'exists:menus,id', 'not_in:'.$menu->id],
            'sort_order' => 'nullable|integer|min:0|max:65535',
        ], [
            'parent_id.not_in' => 'A menu cannot be its own parent.',
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min' => 'Sort order cannot be negative.',
        ]);

        $data = [
            'name'       => $request->name,
            'route_name' => $request->route_name,
            'icon'       => $request->icon,
            'parent_id'  => $request->parent_id,
        ];

        // Empty sort order = keep the current one
        if ($request->filled('sort_order')) {
            $data['sort_order'] = (int) $request->sort_order;
        }

        $menu->update($data);

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
    $menus = Menu::whereNull('parent_id')
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

    // Associates (un-promoted Farm Care Advisers / Tele Callers) are not
    // employees: hide every employee-portal (hr.*) item, and any group
    // that ends up empty. Sales, leads and field menus are unaffected.
    if ($user instanceof \App\Models\Admin && $user->isAssociate()) {
        $menus = $menus->each(function ($parent) {
            $parent->setRelation(
                'children',
                $parent->children->reject(fn ($c) => str_starts_with((string) $c->route_name, 'hr.'))->values()
            );
        })->filter(fn ($parent) => $parent->children->isNotEmpty()
            || ($parent->route_name && ! str_starts_with((string) $parent->route_name, 'hr.')))->values();
    }

    return $menus;
}
}
