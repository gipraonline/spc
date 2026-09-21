<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sales_orders', 'c_order_status')) {
            Schema::table('sales_orders', function (Blueprint $table) {
                $table->string('c_order_status')->default('Pending')->after('c_mode_of_payment');
            });
        }
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('c_order_status');
        });
    }
};