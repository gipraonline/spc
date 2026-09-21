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
            Schema::table('sales_orders', function (Blueprint $table) {

                $table->decimal('n_total_sales_amount', 12, 2)
                    ->default(0.00);

                $table->decimal('n_product_discount_total', 12, 2)
                    ->default(0.00);

                $table->decimal('n_total_gst', 12, 2)
                    ->default(0.00);

                $table->decimal('n_total_discount', 12, 2)
                    ->default(0.00);

                $table->decimal('n_net_sales_amount', 12, 2)
                    ->default(0.00);
            });
        }

        public function down(): void
        {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->dropColumn([
                    'n_total_sales_amount',
                    'n_product_discount_total',
                    'n_total_gst',
                    'n_total_discount',
                    'n_net_sales_amount',
                ]);
            });
        }
};
