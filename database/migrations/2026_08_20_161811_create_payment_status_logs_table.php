<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $primaryKey = 'n_sl_no';

    public function up(): void
    {
        Schema::create('payment_status_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sales_order_n_sl_no');

            $table->string('old_status')->nullable();

            $table->string('new_status');

            $table->unsignedBigInteger('changed_by')->nullable();

            $table->timestamps();

            $table->foreign('sales_order_n_sl_no')
                ->references('n_sl_no')
                ->on('sales_orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_status_logs');
    }
};
