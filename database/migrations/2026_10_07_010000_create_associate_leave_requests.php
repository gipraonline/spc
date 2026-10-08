<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Simple leave request / approval flow for associates (Farm Care Advisers and
 * Tele Callers who are not employees yet). It lives entirely in the SPC
 * database and does not touch the HR module, payroll or leave balances.
 */
return new class extends Migration
{
    private const ROUTE = 'admin.associate-leave.index';

    /** Applicants + people who approve. */
    private const ROLE_IDENTIFIERS = [
        'FCA', 'TC',
        'FCO', 'TL', 'RSH', 'NSH', 'GM', 'AGM_OM', 'HRM', 'HR_TEAM',
        'SUPER_ADMIN', 'GIPRA_ADMIN',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('associate_leave_requests')) {
            Schema::create('associate_leave_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('n_employee_id')->index();
                $table->string('leave_type', 30);
                $table->date('start_date');
                $table->date('end_date');
                $table->unsignedSmallInteger('days');
                $table->string('reason', 500)->nullable();
                $table->string('status', 15)->default('pending')->index(); // pending|approved|rejected|cancelled
                $table->unsignedBigInteger('decided_by')->nullable();      // employee_masters.n_employee_id or null for standalone admin
                $table->string('decided_by_name')->nullable();
                $table->timestamp('decided_at')->nullable();
                $table->string('decision_remark', 500)->nullable();
                $table->timestamps();
            });
        }

        // Sidebar entry under "Home", ticked for the roles above.
        $now = now();
        $parentId = DB::table('menus')->whereNull('parent_id')->where('name', 'Home')->value('id');

        $menuId = DB::table('menus')->where('route_name', self::ROUTE)->value('id');

        if (! $menuId) {
            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Leave Requests',
                'route_name' => self::ROUTE,
                'icon' => 'calendar-days',
                'parent_id' => $parentId,
                'sort_order' => 4,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $roleIds = DB::table('roles')->whereIn('identifier', self::ROLE_IDENTIFIERS)->pluck('id');

        foreach ($roleIds as $roleId) {
            $has = DB::table('role_menu')->where('role_id', $roleId)->where('menu_id', $menuId)->exists();

            if (! $has) {
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

        Schema::dropIfExists('associate_leave_requests');
    }
};
