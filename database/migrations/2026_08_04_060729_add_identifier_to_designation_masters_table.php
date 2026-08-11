<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add identifier without unique constraint
        Schema::table('designation_masters', function (Blueprint $table) {
            $table->string('identifier', 100)
                ->nullable()
                ->after('designation_name');
        });

        // 2. Give existing records unique identifiers
        DB::table('designation_masters')
            ->orderBy('id')
            ->get()
            ->each(function ($designation) {
                DB::table('designation_masters')
                    ->where('id', $designation->id)
                    ->update([
                        'identifier' => 'DES-' . str_pad(
                            $designation->id,
                            3,
                            '0',
                            STR_PAD_LEFT
                        ),
                    ]);
            });

        // 3. Now create the unique index
        Schema::table('designation_masters', function (Blueprint $table) {
            $table->unique('identifier');
        });
    }

    public function down(): void
    {
        Schema::table('designation_masters', function (Blueprint $table) {
            $table->dropUnique('designation_masters_identifier_unique');
            $table->dropColumn('identifier');
        });
    }
};
