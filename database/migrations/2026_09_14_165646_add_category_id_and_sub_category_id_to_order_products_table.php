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
        Schema::table('order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('n_category_id')
                ->nullable()
                ->after('n_order_id');

            $table->unsignedBigInteger('n_sub_category_id')
                ->nullable()
                ->after('n_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn([
                'n_category_id',
                'n_sub_category_id',
            ]);
        });
    }
};
