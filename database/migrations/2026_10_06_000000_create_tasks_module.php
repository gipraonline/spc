<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Task assignment module.
 *
 *   tasks           one row per task (title, priority, due date, department)
 *   task_assignees  one row per employee the task is given to; each employee
 *                   updates the status of their own row
 *   task_updates    history of everything that happened (created, each status
 *                   change with remark, edits, cancellation)
 *
 * Permissions (managed afterwards on Administration > Roles):
 *   tasks.view, tasks.update-status -> every role that already has "dashboard.view"
 *   tasks.create, tasks.edit, tasks.delete -> top-level roles listed below
 *
 * Menu: "Tasks" under the "Activity" group, shown to every role that holds tasks.view.
 */
return new class extends Migration
{
    private const TOP_LEVEL_ROLES = [
        'Super Admin', 'Gipra Admin', 'Chairman', 'Managing Director',
        'Chief Executive Officer', 'COO / CFO', 'GM', 'National Sales Head',
    ];

    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('priority', 10)->default('medium');   // low | medium | high | urgent
            $table->date('due_date')->nullable();
            // HR "departments" table lives in the spc_hr database, so no foreign key.
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('created_by');             // admins.n_role_id
            $table->unsignedBigInteger('created_by_employee_id')->nullable();
            $table->string('created_by_name', 150)->nullable();
            $table->string('status', 12)->default('active');      // active | cancelled
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('priority');
            $table->index('due_date');
            $table->index('department_id');
            $table->index('created_by');
        });

        Schema::create('task_assignees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->unsignedBigInteger('employee_id');             // employee_masters.n_employee_id
            $table->string('status', 12)->default('pending');      // pending | in_progress | on_hold | completed
            $table->string('remark', 1000)->nullable();            // latest remark from the employee
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_status_at')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'employee_id']);
            $table->index(['employee_id', 'status']);
        });

        Schema::create('task_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->unsignedBigInteger('task_assignee_id')->nullable();
            $table->string('type', 12)->default('status');         // created | status | edited | cancelled
            $table->string('from_status', 12)->nullable();
            $table->string('to_status', 12)->nullable();
            $table->string('remark', 1000)->nullable();
            $table->unsignedBigInteger('actor_admin_id')->nullable();
            $table->string('actor_name', 150)->nullable();
            $table->timestamps();

            $table->index('task_id');
        });

        $this->registerAccess();
    }

    public function down(): void
    {
        $menuIds = DB::table('menus')->where('route_name', 'admin.tasks.index')->pluck('id');
        DB::table('role_menu')->whereIn('menu_id', $menuIds)->delete();
        DB::table('menus')->whereIn('id', $menuIds)->delete();

        $permIds = Permission::whereIn('name', $this->permissionNames())->where('guard_name', 'web')->pluck('id');
        DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
        Permission::whereIn('id', $permIds)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Schema::dropIfExists('task_updates');
        Schema::dropIfExists('task_assignees');
        Schema::dropIfExists('tasks');
    }

    private function permissionNames(): array
    {
        return ['tasks.view', 'tasks.create', 'tasks.edit', 'tasks.delete', 'tasks.update-status'];
    }

    private function registerAccess(): void
    {
        $perm = [];
        foreach ($this->permissionNames() as $name) {
            $perm[$name] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web'])->id;
        }

        // Everyone who can open the dashboard can see their tasks and update their own status.
        $dashboardId = Permission::where('name', 'dashboard.view')->where('guard_name', 'web')->value('id');
        $staffRoleIds = $dashboardId
            ? DB::table('role_has_permissions')->where('permission_id', $dashboardId)->pluck('role_id')
            : collect();

        foreach ($staffRoleIds as $roleId) {
            foreach (['tasks.view', 'tasks.update-status'] as $name) {
                DB::table('role_has_permissions')->insertOrIgnore(['permission_id' => $perm[$name], 'role_id' => $roleId]);
            }
        }

        // Top level can assign / edit / delete.
        $topRoleIds = DB::table('roles')->whereIn('name', self::TOP_LEVEL_ROLES)->pluck('id');
        foreach ($topRoleIds as $roleId) {
            foreach ($this->permissionNames() as $name) {
                DB::table('role_has_permissions')->insertOrIgnore(['permission_id' => $perm[$name], 'role_id' => $roleId]);
            }
        }

        // Menu item under "Activity".
        if (! DB::table('menus')->where('route_name', 'admin.tasks.index')->exists()) {
            $parentId = DB::table('menus')->where('name', 'Activity')->whereNull('parent_id')->value('id');

            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Tasks',
                'route_name' => 'admin.tasks.index',
                'icon' => 'clipboard-check',
                'parent_id' => $parentId,
                'sort_order' => 6,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $menuRoleIds = DB::table('role_has_permissions')->where('permission_id', $perm['tasks.view'])->pluck('role_id');
            foreach ($menuRoleIds as $roleId) {
                DB::table('role_menu')->insert([
                    'role_id' => $roleId,
                    'menu_id' => $menuId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
