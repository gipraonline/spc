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
            $table->bigIncrements('n_sl_no');

            $table->string('c_bill_no')->nullable();
            $table->date('d_date')->nullable();


            $table->unsignedBigInteger('farm_care_advisor_id')->nullable();

            $table->string('c_customer_name')->nullable();
            $table->text('c_customer_address')->nullable();
            $table->string('c_customer_email')->nullable();
            $table->string('n_customer_mobile', 20)->nullable();

            $table->unsignedBigInteger('n_state_id')->nullable();
            $table->unsignedBigInteger('n_district_id')->nullable();

            $table->string('c_mode_of_payment')->nullable();

            $table->unsignedBigInteger('nearest_franchise_id')->nullable();

            $table->string('payment_status')->default('Pending');
            $table->string('delivery_status')->default('Pending');

            $table->timestamps();
            $table->softDeletes();

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
