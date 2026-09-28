<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Gives Tele Caller (role identifier TC) a spot in the reporting
 * hierarchy at Level 8, alongside Farm Care Officer / Agro Clinic, so it
 * now appears in the Designations list. The TC role itself already
 * exists (added by the earlier org-chart migration) — this only adds
 * the missing designation_masters row.
 */
return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('designation_masters')->where('identifier', 'TC')->exists();

        if (! $exists) {
            DB::table('designation_masters')->insert([
                'identifier' => 'TC',
                'c_designation' => 'Tele Caller',
                'hierarchy_level' => 8,
                'c_status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('designation_masters')->where('identifier', 'TC')->delete();
    }
};
