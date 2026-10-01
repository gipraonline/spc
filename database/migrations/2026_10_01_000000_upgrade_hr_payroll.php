<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * spc_hr database. Like the other HR tables this is not tracked by the default
 * migrator, so run it explicitly:
 *
 *   php artisan migrate --database=spc_hr \
 *     --path=database/migrations/2026_10_01_000000_upgrade_hr_payroll.php
 *
 * or run database/sql/hr_payroll_upgrade.sql on the spc_hr database.
 *
 *  - salary_structures: PF / ESI / PT applicability flags, manual TDS override, recurring deduction
 *  - payroll_runs:      totals, paid_at, notes, one run per month/year
 *  - payslips:          days worked, earned components, incentive, employer cost, calc_detail
 *  - system_settings:   statutory rates and payroll rules (editable on HR > Settings)
 */
return new class extends Migration
{
    protected $connection = 'spc_hr';

    public function up(): void
    {
        $hr = Schema::connection('spc_hr');

        $hr->table('salary_structures', function (Blueprint $table) use ($hr) {
            if (! $hr->hasColumn('salary_structures', 'pf_applicable')) {
                $table->boolean('pf_applicable')->default(true)->after('gross_monthly');
            }
            if (! $hr->hasColumn('salary_structures', 'esi_applicable')) {
                $table->boolean('esi_applicable')->default(true)->after('pf_applicable');
            }
            if (! $hr->hasColumn('salary_structures', 'pt_applicable')) {
                $table->boolean('pt_applicable')->default(true)->after('esi_applicable');
            }
            if (! $hr->hasColumn('salary_structures', 'tds_monthly_override')) {
                $table->decimal('tds_monthly_override', 12, 2)->nullable()->after('pt_applicable');
            }
            if (! $hr->hasColumn('salary_structures', 'other_deduction')) {
                // recurring monthly deduction (loan EMI, salary advance recovery ...)
                $table->decimal('other_deduction', 12, 2)->default(0)->after('tds_monthly_override');
            }
        });

        $hr->table('payroll_runs', function (Blueprint $table) use ($hr) {
            if (! $hr->hasColumn('payroll_runs', 'employee_count')) {
                $table->unsignedInteger('employee_count')->default(0)->after('status');
            }
            if (! $hr->hasColumn('payroll_runs', 'total_gross')) {
                $table->decimal('total_gross', 14, 2)->default(0)->after('employee_count');
                $table->decimal('total_deductions', 14, 2)->default(0)->after('total_gross');
                $table->decimal('total_net', 14, 2)->default(0)->after('total_deductions');
                $table->decimal('total_employer_cost', 14, 2)->default(0)->after('total_net');
            }
            if (! $hr->hasColumn('payroll_runs', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('processed_at');
            }
            if (! $hr->hasColumn('payroll_runs', 'notes')) {
                $table->string('notes', 255)->nullable()->after('paid_at');
            }
        });

        // One run per month. Clean obvious duplicates first is a manual job; fail loudly if any exist.
        $hasUnique = collect(DB::connection('spc_hr')->select("SHOW INDEX FROM payroll_runs WHERE Key_name = 'payroll_runs_period_unique'"))->isNotEmpty();
        if (! $hasUnique) {
            $hr->table('payroll_runs', function (Blueprint $table) {
                $table->unique(['month', 'year'], 'payroll_runs_period_unique');
            });
        }

        $hr->table('payslips', function (Blueprint $table) use ($hr) {
            if (! $hr->hasColumn('payslips', 'days_in_month')) {
                $table->unsignedTinyInteger('days_in_month')->default(30)->after('employee_id');
                $table->decimal('paid_days', 5, 2)->default(0)->after('days_in_month');
                $table->decimal('lop_days', 5, 2)->default(0)->after('paid_days');
                $table->decimal('basic_earned', 12, 2)->default(0)->after('lop_days');
                $table->decimal('hra_earned', 12, 2)->default(0)->after('basic_earned');
                $table->decimal('allowances_earned', 12, 2)->default(0)->after('hra_earned');
                $table->decimal('variable_earned', 12, 2)->default(0)->after('allowances_earned');
                $table->decimal('incentive_pay', 12, 2)->default(0)->after('variable_earned');
            }
            if (! $hr->hasColumn('payslips', 'employer_pf')) {
                $table->decimal('employer_pf', 12, 2)->default(0)->after('net_pay');
                $table->decimal('employer_esi', 12, 2)->default(0)->after('employer_pf');
                $table->decimal('employer_admin_charges', 12, 2)->default(0)->after('employer_esi');
                $table->json('calc_detail')->nullable()->after('employer_admin_charges');
            }
        });

        $hasSlipUnique = collect(DB::connection('spc_hr')->select("SHOW INDEX FROM payslips WHERE Key_name = 'payslips_run_employee_unique'"))->isNotEmpty();
        if (! $hasSlipUnique) {
            $hr->table('payslips', function (Blueprint $table) {
                $table->unique(['payroll_run_id', 'employee_id'], 'payslips_run_employee_unique');
            });
        }

        $defaults = [
            'weekly_off_days'            => '0',        // 0 = Sunday ... 6 = Saturday, comma separated
            'missing_attendance_as'      => 'present',  // present | absent  (working day with no attendance row)
            'pf_wage_ceiling'            => '15000',
            'pf_cap_wages'               => '1',        // 1 = PF on min(basic, ceiling); 0 = PF on full basic
            'esi_employee_rate'          => '0.75',
            'esi_employer_rate'          => '3.25',
            'pf_admin_edli_rate'         => '1',        // employer admin 0.5% + EDLI 0.5%, on capped wages
            'pt_enabled'                 => '1',
            'pt_deduction_mode'          => 'monthly',  // monthly (slab / 6 every month) | half_yearly (full slab in Sep & Mar)
            // [half-yearly gross from, half-yearly PT]. Kerala - VERIFY with your local body before go-live.
            'pt_slabs'                   => '[[0,0],[12000,320],[18000,450],[30000,600],[45000,750],[60000,1000],[125000,1250]]',
            'tds_enabled'                => '1',
            'tds_standard_deduction'     => '75000',
        ];

        foreach ($defaults as $key => $value) {
            $exists = DB::connection('spc_hr')->table('system_settings')->where('setting_key', $key)->exists();
            if (! $exists) {
                DB::connection('spc_hr')->table('system_settings')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $value,
                    'updated_at'    => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive: payroll history must never be dropped by a rollback.
    }
};
