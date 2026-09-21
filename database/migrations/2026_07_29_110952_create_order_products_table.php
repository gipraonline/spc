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
        Schema::create('order_products', function (Blueprint $table) {

            $table->id('n_id');
            $table->integer('n_order_id')->nullable();
            $table->unsignedBigInteger('product_id');

            $table->decimal('product_price', 10, 0)->nullable();

            $table->integer('qty')->nullable();

            $table->decimal('product_total', 10, 0)->nullable();

            $table->dateTime('created_at')->nullable();

            $table->dateTime('updated_at')->nullable();

            // Foreign key (optional)
            // $table->foreign('product_id')
            //       ->references('id')
            //       ->on('products')
            //       ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
