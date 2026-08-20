<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_products', function (Blueprint $table) {

            $table->string('c_hsn_code', 50)
                ->nullable()
                ->after('product_id');

            $table->string('c_unit', 50)
                ->nullable()
                ->after('qty');

            $table->decimal('n_gst_percentage', 5, 2)
                ->default(0.00)
                ->after('discount');

            $table->decimal('gst_amount', 10, 2)
                ->default(0.00)
                ->after('n_gst_percentage');

            $table->decimal('discounted_price', 10, 2)
                ->default(0.00)
                ->after('gst_amount');

            // Optional: change existing money fields to 2 decimal places
            $table->decimal('product_price', 10, 2)
                ->nullable()
                ->change();

            $table->decimal('product_total', 10, 2)
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {

            $table->dropColumn([
                'n_hsn_code',
                'c_unit',
                'n_gst_percentage',
                'gst_amount',
                'discounted_price',
            ]);

            $table->decimal('product_price', 10, 0)
                ->nullable()
                ->change();

            $table->decimal('product_total', 10, 0)
                ->nullable()
                ->change();
        });
    }

};
