<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Registers the two new SPC screens:
 *   - Follow-up Cockpit   (Sales menu)            permission: leads.cockpit
 *   - Coverage Map        (Field Operations menu) permission: coverage-map.view
 *
 * To avoid a lock-out / leak, access is copied from the closest existing screen:
 *   cockpit      -> every role that already has "leads.view" and the Leads menu
 *   coverage map -> every role that already has "field-activity.view" and the Field Activity menu
 * Adjust afterwards on Admin > Roles.
 */
return new class extends Migration
{
    public function up(): void
    {
        $cockpit = Permission::firstOrCreate(['name' => 'leads.cockpit', 'guard_name' => 'web']);
        $map = Permission::firstOrCreate(['name' => 'coverage-map.view', 'guard_name' => 'web']);

        $this->copyPermission('leads.view', $cockpit->id);
        $this->copyPermission('field-activity.view', $map->id);

        $salesParent = DB::table('menus')->where('name', 'Sales')->whereNull('parent_id')->value('id');
        $fieldParent = DB::table('menus')->where('name', 'Field Operations')->whereNull('parent_id')->value('id');

        $this->addMenu('Follow-up Cockpit', 'admin.leads.cockpit', 'list-checks', $salesParent, 6, 'admin.leads.index');
        $this->addMenu('Coverage Map', 'admin.coverage-map.index', 'map', $fieldParent, 3, 'admin.admin-log.index');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $menuIds = DB::table('menus')->whereIn('route_name', ['admin.leads.cockpit', 'admin.coverage-map.index'])->pluck('id');
        DB::table('role_menu')->whereIn('menu_id', $menuIds)->delete();
        DB::table('menus')->whereIn('id', $menuIds)->delete();

        $permIds = Permission::whereIn('name', ['leads.cockpit', 'coverage-map.view'])->where('guard_name', 'web')->pluck('id');
        DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
        Permission::whereIn('id', $permIds)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function copyPermission(string $fromName, int $toId): void
    {
        $fromId = Permission::where('name', $fromName)->where('guard_name', 'web')->value('id');
        if (! $fromId) {
            return;
        }

        $roleIds = DB::table('role_has_permissions')->where('permission_id', $fromId)->pluck('role_id');
        foreach ($roleIds as $roleId) {
            DB::table('role_has_permissions')->insertOrIgnore(['permission_id' => $toId, 'role_id' => $roleId]);
        }
    }

    private function addMenu(string $name, string $route, string $icon, ?int $parentId, int $sort, string $copyFromRoute): void
    {
        if (DB::table('menus')->where('route_name', $route)->exists()) {
            return;
        }

        $menuId = DB::table('menus')->insertGetId([
            'name' => $name,
            'route_name' => $route,
            'icon' => $icon,
            'parent_id' => $parentId,
            'sort_order' => $sort,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sourceMenuId = DB::table('menus')->where('route_name', $copyFromRoute)->value('id');
        if (! $sourceMenuId) {
            return;
        }

        $roleIds = DB::table('role_menu')->where('menu_id', $sourceMenuId)->pluck('role_id');
        foreach ($roleIds as $roleId) {
            DB::table('role_menu')->insert([
                'role_id' => $roleId,
                'menu_id' => $menuId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
