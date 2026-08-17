<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('store_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('n_panchayat_id')->nullable()->after('n_district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_masters', function (Blueprint $table) {
            //
        });
    }
};
