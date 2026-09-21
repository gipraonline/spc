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
        Schema::table('customer_masters', function (Blueprint $table) {
            $table->string('c_post_office')->nullable()->after('c_address');
            $table->string('c_thaluk')->nullable()->after('n_district_id');

        });
    }

    public function down(): void
    {
        Schema::table('customer_masters', function (Blueprint $table) {
            $table->dropColumn(['c_post_office', 'c_thaluk']);
        });
    }
};
