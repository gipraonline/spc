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
        Schema::create('product_masters', function (Blueprint $table) {
            $table->bigIncrements('n_product_id');

            $table->string('c_product_code')->nullable()->index();
            $table->string('c_product_name');

            $table->decimal('n_purchase_price', 12, 2)->default(0.00);
            $table->decimal('n_selling_price', 12, 2)->default(0.00);
            $table->decimal('n_mrp', 12, 2)->default(0.00);

            $table->string('c_status')->default('Y');

            $table->timestamps();

            // Soft delete support
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_masters');
    }
};