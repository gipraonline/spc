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
         Schema::create('sales_approvals', function (Blueprint $table) {
            $table->id();

            // Related sales order
            $table->unsignedBigInteger('sales_order_id');

            // Approval information
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])
                  ->default('Pending');

            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('sales_order_id')
                  ->references('n_sl_no')
                  ->on('sales_orders')
                  ->onDelete('cascade');

            $table->foreign('approved_by')
                  ->references('n_role_id')
                  ->on('admins')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_approvals');
    }
};
