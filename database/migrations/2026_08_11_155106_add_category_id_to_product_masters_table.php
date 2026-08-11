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
        Schema::table('product_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('n_category_id')
                ->nullable()
                ->after('n_product_id');
                 });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_masters', function (Blueprint $table) {
            $table->dropForeign(['n_category_id']);
            $table->dropColumn('n_category_id');
        });
    }
};