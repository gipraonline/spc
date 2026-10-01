<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Document Verification module.
 *
 *  1. spc_hr.employee_documents.remarks  - reason shown to the employee on rejection.
 *  2. "Document Verification" menu entry under the HR menu, visible ONLY to roles
 *     whose hr_access is hr_admin or super_admin (the same rule the module itself
 *     enforces server-side via the hr.admin middleware).
 *  3. Repairs already-stored HR notification links. They were saved without the
 *     /hr prefix (e.g. /modules/employee-records?employee=13), which 404s.
 */
return new class extends Migration
{
    private const ROUTE = 'hr.verification.index';

    public function up(): void
    {
        // 1. Rejection reason
        if (! Schema::connection('spc_hr')->hasColumn('employee_documents', 'remarks')) {
            Schema::connection('spc_hr')->table('employee_documents', function (Blueprint $table) {
                $table->string('remarks', 200)->nullable()->after('status');
            });
        }

        // 2. Menu entry + role access
        if (! DB::table('menus')->where('route_name', self::ROUTE)->exists()) {
            $parentId = DB::table('menus')->where('name', 'HR')->whereNull('parent_id')->value('id');

            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Document Verification',
                'route_name' => self::ROUTE,
                'icon' => 'file-check',
                'parent_id' => $parentId,
                'sort_order' => 6,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $roleIds = DB::table('roles')
                ->whereIn('hr_access', ['hr_admin', 'super_admin'])
                ->pluck('id');

            foreach ($roleIds as $roleId) {
                DB::table('role_menu')->insert([
                    'role_id' => $roleId,
                    'menu_id' => $menuId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Repair stored notification links (order matters: specific first).
        DB::connection('spc_hr')->table('notifications')
            ->where('type', 'document_uploaded')
            ->where('link', 'like', '/modules/employee-records%')
            ->update(['link' => '/hr/modules/document-verification']);

        DB::connection('spc_hr')->table('notifications')
            ->whereIn('type', ['document_verified', 'document_rejected'])
            ->where('link', 'like', '/modules/employee-records%')
            ->update(['link' => '/hr/my-profile']);

        DB::connection('spc_hr')->table('notifications')
            ->where('link', 'like', '/modules/%')
            ->update(['link' => DB::raw("CONCAT('/hr', link)")]);
    }

    public function down(): void
    {
        $menuIds = DB::table('menus')->where('route_name', self::ROUTE)->pluck('id');
        DB::table('role_menu')->whereIn('menu_id', $menuIds)->delete();
        DB::table('menus')->whereIn('id', $menuIds)->delete();

        if (Schema::connection('spc_hr')->hasColumn('employee_documents', 'remarks')) {
            Schema::connection('spc_hr')->table('employee_documents', function (Blueprint $table) {
                $table->dropColumn('remarks');
            });
        }
        // Notification link repairs are intentionally not reverted (the old links were broken).
    }
};
