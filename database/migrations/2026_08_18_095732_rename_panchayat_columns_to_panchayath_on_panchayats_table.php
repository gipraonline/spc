<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panchayats', function (Blueprint $table) {
            $table->renameColumn('panchayat_name', 'panchayath_name');
            $table->renameColumn('panchayat_code', 'panchayath_code');
        });
    }

    public function down(): void
    {
        Schema::table('panchayats', function (Blueprint $table) {
            $table->renameColumn('panchayath_name', 'panchayat_name');
            $table->renameColumn('panchayath_code', 'panchayat_code');
        });
    }
};
