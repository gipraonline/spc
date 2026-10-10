<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "My Commission" sidebar entry (main SPC site) for associates:
 * Farm Care Advisers and Tele Callers. They have no HR-portal access, so they
 * read their own commission here. Default (spc) connection only.
 */
return new class extends Migration
{
    private const ROUTE = 'admin.my-commission.index';

    private const ROLE_IDENTIFIERS = ['FCA', 'TC'];

    public function up(): void
    {
        $now = now();
        $parentId = DB::table('menus')->whereNull('parent_id')->where('name', 'Home')->value('id');

        $menuId = DB::table('menus')->where('route_name', self::ROUTE)->value('id');

        if (! $menuId) {
            $menuId = DB::table('menus')->insertGetId([
                'name' => 'My Commission',
                'route_name' => self::ROUTE,
                'icon' => 'percent',
                'parent_id' => $parentId,
                'sort_order' => 5,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach (DB::table('roles')->whereIn('identifier', self::ROLE_IDENTIFIERS)->pluck('id') as $roleId) {
            if (! DB::table('role_menu')->where('role_id', $roleId)->where('menu_id', $menuId)->exists()) {
                DB::table('role_menu')->insert([
                    'role_id' => $roleId, 'menu_id' => $menuId, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        $menuId = DB::table('menus')->where('route_name', self::ROUTE)->value('id');

        if ($menuId) {
            DB::table('role_menu')->where('menu_id', $menuId)->delete();
            DB::table('menus')->where('id', $menuId)->delete();
        }
    }
};
