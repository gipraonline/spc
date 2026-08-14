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
        Schema::create('sales_order_followups', function (Blueprint $table) {
            $table->bigIncrements('n_followup_id');

            $table->unsignedBigInteger('n_sale_id');

            $table->date('d_followup_date');

            $table->string('c_order_status')->nullable();

            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('n_created_by')->nullable();

            $table->timestamps();

            $table->foreign('n_sale_id')
                ->references('n_sl_no')
                ->on('sales_orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_followups');
    }
};
