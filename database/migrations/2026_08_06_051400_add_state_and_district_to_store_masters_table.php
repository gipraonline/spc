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
            $table->unsignedBigInteger('n_state_id')->nullable()->after('c_store_address');
            $table->unsignedBigInteger('n_district_id')->nullable()->after('n_state_id');
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::table('store_masters', function (Blueprint $table) {
            $table->dropColumn(['n_state_id', 'n_district_id']);
        });
    }
};