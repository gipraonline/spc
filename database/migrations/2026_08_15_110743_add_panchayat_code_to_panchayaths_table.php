<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panchayats', function (Blueprint $table) {
            $table->string('panchayat_code', 20)
                ->nullable()
                ->unique()
                ->after('panchayat_name');
        });
    }

    public function down(): void
    {
        Schema::table('panchayats', function (Blueprint $table) {
            $table->dropUnique(['panchayat_code']);
            $table->dropColumn('panchayat_code');
        });
    }
};