<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `routes/web.php` has always guarded the designations create/edit/delete
 * routes with `permission:designations.create` etc., and the Designations
 * index view wraps its "Add Designation" button in `@can('designations.create')`
 * — but only `designations.view` was ever added to the `permissions` table.
 * With no matching permission row, no role (not even Super Admin — there's
 * no Gate::before bypass in this app) could ever be granted it, so the
 * button has been invisible and the routes unreachable for everyone since
 * launch. This is what actually blocked getting to the new "Reports To"
 * field on the Designation edit form.
 *
 * This adds the missing permissions and grants them to Super Admin and
 * Gipra Admin (the two system-admin roles), matching how sensitive,
 * structural settings are scoped elsewhere in the app. Nothing is removed
 * from any role, and designations.view (already working) is untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $newPermissions = ['designations.create', 'designations.edit', 'designations.delete'];

        foreach ($newPermissions as $name) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $newPermissions)
            ->pluck('id');

        $adminRoleIds = DB::table('roles')
            ->whereIn('identifier', ['SUPER_ADMIN', 'GIPRA_ADMIN'])
            ->pluck('id');

        foreach ($adminRoleIds as $roleId) {
            foreach ($permissionIds as $permissionId) {
                $exists = DB::table('role_has_permissions')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $permissionId)
                    ->exists();

                if (! $exists) {
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }

        // Spatie caches the permission map; clear it so this takes effect
        // immediately instead of waiting on the cache to expire.
        DB::table('cache')->where('key', 'like', '%spatie.permission.cache%')->delete();
    }

    public function down(): void
    {
        $names = ['designations.create', 'designations.edit', 'designations.delete'];

        $ids = DB::table('permissions')->whereIn('name', $names)->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $ids)->delete();
        DB::table('permissions')->whereIn('name', $names)->delete();
        DB::table('cache')->where('key', 'like', '%spatie.permission.cache%')->delete();
    }
};
