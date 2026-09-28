<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Renames the main "Employees" menu item (admin.employees.index) to
     *   "Employee Records" — it stays in its current position/section and
     *   keeps using the same controller/views (list, add, edit).
     * - Hides the separate "Employee Records" item under HR Management
     *   (hr.records.index) for every role by setting status = 0, so it no
     *   longer shows for any login. The route/controller/views behind it
     *   are left untouched in case they're still needed elsewhere.
     */
    public function up(): void
    {
        // 1) Rename the main "Employees" nav item.
        DB::table('menus')
            ->where('route_name', 'admin.employees.index')
            ->update(['name' => 'Employee Records']);

        // 2) Hide the HR Management "Employee Records" item for all logins.
        //    Match primarily by its stable route name; fall back to matching
        //    by name under an HR-named parent, in case it was added by hand
        //    with a different route reference.
        $hidden = DB::table('menus')
            ->where('route_name', 'hr.records.index')
            ->update(['status' => 0]);

        if ($hidden === 0) {
            $hrParentIds = DB::table('menus')
                ->where('name', 'like', '%HR%')
                ->pluck('id');

            DB::table('menus')
                ->where('name', 'Employee Records')
                ->where('route_name', '!=', 'admin.employees.index')
                ->when($hrParentIds->isNotEmpty(), function ($query) use ($hrParentIds) {
                    $query->orWhereIn('parent_id', $hrParentIds);
                })
                ->update(['status' => 0]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('menus')
            ->where('route_name', 'admin.employees.index')
            ->update(['name' => 'Employees']);

        DB::table('menus')
            ->where('route_name', 'hr.records.index')
            ->update(['status' => 1]);
    }
};
