<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Leave policy setup.
 *
 *  1. leave_types.applicable_to (all / female / male) so maternity and
 *     paternity leave only show up for the employees they're meant for.
 *  2. Adds the leave types a typical company offers, alongside the existing
 *     Casual / Sick / Earned / Unpaid. Existing types and their numbers are
 *     left exactly as they are. Everything here is editable afterwards in
 *     HR Settings > Leave entitlements.
 *
 * Unpaid Leave stays unlimited (is_paid = 0 is never capped by the app).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('spc_hr')->hasColumn('leave_types', 'applicable_to')) {
            Schema::connection('spc_hr')->table('leave_types', function (Blueprint $table) {
                $table->string('applicable_to', 10)->default('all')->after('max_carry_forward');
            });
        }

        $defaults = [
            // name, paid, days/year, applicable_to
            ['Maternity Leave', 1, 182, 'female'], // 26 weeks (Maternity Benefit Act)
            ['Paternity Leave', 1, 5, 'male'],
            ['Marriage Leave', 1, 5, 'all'],
            ['Bereavement Leave', 1, 3, 'all'],
        ];

        foreach ($defaults as [$name, $paid, $days, $applies]) {
            if (DB::connection('spc_hr')->table('leave_types')->where('name', $name)->exists()) {
                continue;
            }

            DB::connection('spc_hr')->table('leave_types')->insert([
                'name' => $name,
                'is_paid' => $paid,
                'default_annual_days' => $days,
                'carry_forward' => 0,
                'max_carry_forward' => 0,
                'applicable_to' => $applies,
            ]);
        }
    }

    public function down(): void
    {
        DB::connection('spc_hr')->table('leave_types')
            ->whereIn('name', ['Maternity Leave', 'Paternity Leave', 'Marriage Leave', 'Bereavement Leave'])
            ->delete();

        if (Schema::connection('spc_hr')->hasColumn('leave_types', 'applicable_to')) {
            Schema::connection('spc_hr')->table('leave_types', function (Blueprint $table) {
                $table->dropColumn('applicable_to');
            });
        }
    }
};
