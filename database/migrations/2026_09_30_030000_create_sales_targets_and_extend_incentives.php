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
 *     --path=database/migrations/2026_09_30_030000_create_sales_targets_and_extend_incentives.php
 *
 * or run database/sql/hr_targets_and_incentives.sql on the spc_hr database.
 *
 *  - sales_targets:      per employee, per appraisal cycle, per metric
 *  - incentive_rules:    + designation_code, basis (own / team), min_achievement_pct
 *  - incentive_payouts:  + basis, calc_detail; one payout per employee / month / basis
 *  - starter incentive rules for the SPC sales hierarchy (edit on HR > Incentives)
 */
return new class extends Migration
{
    protected $connection = 'spc_hr';

    public function up(): void
    {
        $hr = Schema::connection('spc_hr');

        if (! $hr->hasTable('sales_targets')) {
            $hr->create('sales_targets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
                $table->foreignId('appraisal_cycle_id')->constrained('appraisal_cycles')->cascadeOnDelete();
                // net_sales (INR), orders, new_leads, field_days
                $table->enum('metric', ['net_sales', 'orders', 'new_leads', 'field_days']);
                $table->decimal('target_value', 14, 2);
                $table->unsignedBigInteger('set_by')->nullable();
                $table->timestamps();

                $table->unique(['employee_id', 'appraisal_cycle_id', 'metric'], 'sales_targets_unique');
            });
        }

        $hr->table('incentive_rules', function (Blueprint $table) use ($hr) {
            if (! $hr->hasColumn('incentive_rules', 'designation_code')) {
                // SPC designation identifier (designation_masters.identifier): FCA, FCO, TL, RSH ...
                // NULL = any designation of the chosen portal role.
                $table->string('designation_code', 30)->nullable()->after('applies_to_role');
            }
            if (! $hr->hasColumn('incentive_rules', 'basis')) {
                // own  = the person's own approved+paid sales
                // team = sales of everyone below them in the reporting line (override)
                $table->enum('basis', ['own', 'team'])->default('own')->after('designation_code');
            }
            if (! $hr->hasColumn('incentive_rules', 'min_achievement_pct')) {
                // Nothing is paid unless target achievement >= this (only when a target is set).
                $table->decimal('min_achievement_pct', 5, 2)->nullable()->after('flat_amount');
            }
        });

        $hr->table('incentive_payouts', function (Blueprint $table) use ($hr) {
            if (! $hr->hasColumn('incentive_payouts', 'basis')) {
                $table->enum('basis', ['own', 'team'])->default('own')->after('incentive_rule_id');
            }
            if (! $hr->hasColumn('incentive_payouts', 'calc_detail')) {
                $table->json('calc_detail')->nullable()->after('incentive_amount');
            }
            $table->unique(['employee_id', 'month', 'year', 'basis'], 'incentive_payouts_period_unique');
        });

        $this->seedStarterRules();
    }

    public function down(): void
    {
        $hr = Schema::connection('spc_hr');

        $hr->table('incentive_payouts', function (Blueprint $table) {
            $table->dropUnique('incentive_payouts_period_unique');
            $table->dropColumn(['basis', 'calc_detail']);
        });
        $hr->table('incentive_rules', function (Blueprint $table) {
            $table->dropColumn(['designation_code', 'basis', 'min_achievement_pct']);
        });
        $hr->dropIfExists('sales_targets');
    }

    /**
     * Starter scheme. Marginal slabs on monthly eligible net sales (approved + paid):
     *   FCA  : 1% up to 1 L, 2% from 1-3 L, 3% above 3 L (needs 70% of target if one is set)
     *   FCO  : 1% own sales  + 0.5% override on the FCAs below
     *   TC   : 1% own sales
     *   TL / AGM_OM / RSH / NSH : override on everyone below (0.4 / 0.3 / 0.2 / 0.1 %)
     * These rates are placeholders - management should review them before payouts are approved.
     */
    private function seedStarterRules(): void
    {
        $db = DB::connection('spc_hr');

        if ($db->table('incentive_rules')->whereNotNull('designation_code')->exists()) {
            return;
        }

        $today = now()->toDateString();
        $rows = [];
        $add = function (string $name, string $role, string $code, string $basis, float $from, ?float $to, float $pct, ?float $minAch = null) use (&$rows, $today) {
            $rows[] = [
                'name' => $name,
                'applies_to_role' => $role,
                'designation_code' => $code,
                'basis' => $basis,
                'target_metric' => 'net_sales',
                'slab_from' => $from,
                'slab_to' => $to,
                'incentive_percent' => $pct,
                'flat_amount' => null,
                'min_achievement_pct' => $minAch,
                'effective_from' => $today,
                'effective_to' => null,
                'is_active' => 1,
            ];
        };

        $add('FCA own sales - slab 1', 'employee', 'FCA', 'own', 0, 100000, 1.0, 70);
        $add('FCA own sales - slab 2', 'employee', 'FCA', 'own', 100000, 300000, 2.0, 70);
        $add('FCA own sales - slab 3', 'employee', 'FCA', 'own', 300000, null, 3.0, 70);
        $add('FCO own sales', 'employee', 'FCO', 'own', 0, null, 1.0);
        $add('FCO team override', 'employee', 'FCO', 'team', 0, null, 0.5);
        $add('Tele Caller own sales', 'employee', 'TC', 'own', 0, null, 1.0);
        $add('Team Lead team override', 'employee', 'TL', 'team', 0, null, 0.4);
        $add('AGM/OM team override', 'manager', 'AGM_OM', 'team', 0, null, 0.3);
        $add('RSH team override', 'manager', 'RSH', 'team', 0, null, 0.2);
        $add('NSH team override', 'manager', 'NSH', 'team', 0, null, 0.1);

        $db->table('incentive_rules')->insert($rows);
    }
};
