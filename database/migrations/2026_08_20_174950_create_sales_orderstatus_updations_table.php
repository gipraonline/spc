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
        Schema::create('sales_orderstatus_updations', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('n_sale_id');
                $table->date('d_followup_date')->nullable();
                $table->string('c_order_status')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('n_created_by')->nullable();

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orderstatus_updations');
    }
};
