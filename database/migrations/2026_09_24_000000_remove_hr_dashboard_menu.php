<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The separate HR dashboard (route hr.dashboard) no longer exists —
     * the unified /dashboard replaced it. Remove its sidebar menu row (and
     * the role_menu links to it) so the main layout stops calling
     * route('hr.dashboard').
     */
    public function up(): void
    {
        $ids = DB::table('menus')->where('route_name', 'hr.dashboard')->pluck('id');

        if ($ids->isNotEmpty()) {
            DB::table('role_menu')->whereIn('menu_id', $ids)->delete();
            DB::table('menus')->whereIn('id', $ids)->delete();
        }
    }

    public function down(): void
    {
        // Intentionally empty: the HR dashboard page itself has been removed.
    }
};
