<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a real parent/child link between designations so reporting can be
 * restricted to the correct branch of the org chart, not just "anyone at
 * the level above" (which the hierarchy_level-only design allowed).
 *
 * hierarchy_level is left untouched — it still drives display order and
 * general "above/below" checks. parent_designation_id is the new,
 * precise link used for reporting-manager lookups.
 *
 * Parent mapping is taken directly from the hand-drawn org chart. One
 * branch point isn't explicit there: the chart draws "AGM/Operation
 * Manager/Sales Head/Office Administration" as a single box that then
 * splits into Franchise Manager and Team Lead. Since those 4 roles are
 * kept separate, Franchise Manager and Team Lead are attached to AGM /
 * Operational Manager specifically (the operations-focused one of the
 * four) — flag this to the client if a different one of the four should
 * be their actual parent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('designation_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_designation_id')->nullable()->after('identifier');

            $table->foreign('parent_designation_id')
                ->references('n_designation_id')
                ->on('designation_masters')
                ->nullOnDelete();
        });

        // identifier => parent identifier (null = top of the tree / not
        // part of the numbered hierarchy)
        $parents = [
            'CHAIRMAN' => null,
            'MD' => 'CHAIRMAN',
            'CEO' => 'MD',
            'COO_CFO' => 'CEO',
            'GM' => 'COO_CFO',
            'FINANCE' => 'COO_CFO',
            'HRM' => 'COO_CFO',
            'MKT_MGR' => 'COO_CFO',
            'AGM_OM' => 'GM',
            'OFFICE_ADMIN' => 'GM',
            'RSH' => 'GM',
            'NSH' => 'GM',
            'SR_ACCT' => 'FINANCE',
            'HR_TEAM' => 'HRM',
            'ACCOUNTANT' => 'SR_ACCT',
            'FR_MGR' => 'AGM_OM',
            'TL' => 'AGM_OM',
            'AGRO_CLINIC' => 'FR_MGR',
            'FCO' => 'TL',
            'TC' => 'TL',
            'FRANCHISE' => 'AGRO_CLINIC',
            'FCA' => 'FCO',
        ];

        $ids = DB::table('designation_masters')->pluck('n_designation_id', 'identifier');

        foreach ($parents as $identifier => $parentIdentifier) {
            if ($parentIdentifier === null || ! isset($ids[$identifier]) || ! isset($ids[$parentIdentifier])) {
                continue;
            }

            DB::table('designation_masters')
                ->where('n_designation_id', $ids[$identifier])
                ->update([
                    'parent_designation_id' => $ids[$parentIdentifier],
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('designation_masters', function (Blueprint $table) {
            $table->dropForeign(['parent_designation_id']);
            $table->dropColumn('parent_designation_id');
        });
    }
};
