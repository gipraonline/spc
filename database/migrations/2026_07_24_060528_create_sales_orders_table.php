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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id('n_sl_no');
            $table->date('d_date');
            $table->string('n_sold_price', 20);

            $table->unsignedBigInteger('farm_care_advisor_id');
            $table->string('c_customer_name');
            $table->text('c_customer_address')->nullable();
            $table->string('c_customer_email')->nullable();
            $table->string('n_customer_mobile', 20);

            $table->string('c_state');
            $table->string('c_district');

            $table->string('c_mode_of_payment');
            $table->unsignedBigInteger('nearest_franchise_id');

            $table->string('payment_status')->default('Pending');
            $table->string('delivery_status')->default('Pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
