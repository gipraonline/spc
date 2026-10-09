<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Associate commission module (Farm Care Advisers + Tele Callers).
 *
 *  spc_hr.commission_rates  commission % per designation, set by Office Administration
 *  spc_hr.commission_runs   one statement per month, with the HR -> COO -> MD -> Finance stage
 *  spc_hr.commission_lines  one row per associate in a statement (snapshot of sales, rate, amount)
 *  spc.menus / role_menu    "Commission" under the Finance menu group
 *
 * Mixed migration (spc + spc_hr): run it with plain `php artisan migrate`,
 * NOT with --database=spc_hr.
 */
return new class extends Migration
{
    private const ROUTE = 'hr.commission.index';

    /** SPC role identifiers that get the menu (Office Admin, HR, COO, MD, Finance). */
    private const ROLE_IDENTIFIERS = ['OFFICE_ADMIN', 'HRM', 'COO_CFO', 'MD', 'FINANCE'];

    public function up(): void
    {
        $hr = Schema::connection('spc_hr');

        if (! $hr->hasTable('commission_rates')) {
            $hr->create('commission_rates', function (Blueprint $table) {
                $table->id();
                $table->string('designation_code', 30)->index();
                $table->decimal('percent', 5, 2);
                $table->date('effective_from');
                $table->unsignedBigInteger('set_by')->nullable();
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! $hr->hasTable('commission_runs')) {
            $hr->create('commission_runs', function (Blueprint $table) {
                $table->id();
                $table->unsignedTinyInteger('month');
                $table->unsignedSmallInteger('year');
                $table->string('approval_stage', 20)->default('draft')->index();
                $table->unsignedInteger('advisor_count')->default(0);
                $table->decimal('total_sales', 14, 2)->default(0);
                $table->decimal('total_commission', 14, 2)->default(0);
                $table->unsignedBigInteger('generated_by')->nullable();
                $table->timestamp('generated_at')->nullable();
                $table->unsignedBigInteger('submitted_by')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->unsignedBigInteger('coo_by')->nullable();
                $table->timestamp('coo_at')->nullable();
                $table->unsignedBigInteger('md_by')->nullable();
                $table->timestamp('md_at')->nullable();
                $table->unsignedBigInteger('paid_by')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->unsignedBigInteger('returned_by')->nullable();
                $table->timestamp('returned_at')->nullable();
                $table->string('workflow_remarks', 255)->nullable();
                $table->unique(['month', 'year']);
            });
        }

        if (! $hr->hasTable('commission_lines')) {
            $hr->create('commission_lines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('commission_run_id')->index();
                $table->unsignedBigInteger('employee_master_id')->index();   // spc.employee_masters.n_employee_id
                $table->string('employee_code', 50)->nullable();
                $table->string('employee_name', 150)->nullable();
                $table->string('designation_code', 30)->nullable();
                $table->unsignedInteger('orders_count')->default(0);
                $table->decimal('sales_amount', 14, 2)->default(0);
                $table->decimal('rate_percent', 5, 2)->default(0);
                $table->decimal('commission_amount', 12, 2)->default(0);
            });
        }

        // Menu under "Finance", shown only to the roles that use it.
        if (! DB::table('menus')->where('route_name', self::ROUTE)->exists()) {
            $parentId = DB::table('menus')->where('name', 'Finance')->whereNull('parent_id')->value('id');

            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Commission',
                'route_name' => self::ROUTE,
                'icon' => 'percent',
                'parent_id' => $parentId,
                'sort_order' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $roleIds = DB::table('roles')->whereIn('identifier', self::ROLE_IDENTIFIERS)->pluck('id');
            foreach ($roleIds as $roleId) {
                DB::table('role_menu')->insert([
                    'role_id' => $roleId,
                    'menu_id' => $menuId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $menuIds = DB::table('menus')->where('route_name', self::ROUTE)->pluck('id');
        DB::table('role_menu')->whereIn('menu_id', $menuIds)->delete();
        DB::table('menus')->whereIn('id', $menuIds)->delete();

        Schema::connection('spc_hr')->dropIfExists('commission_lines');
        Schema::connection('spc_hr')->dropIfExists('commission_runs');
        Schema::connection('spc_hr')->dropIfExists('commission_rates');
    }
};
