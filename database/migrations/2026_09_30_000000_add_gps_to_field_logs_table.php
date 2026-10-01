<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('field_logs', function (Blueprint $table) {
            // Same precision as store_masters / sales_orders latitude & longitude.
            $table->decimal('check_in_latitude', 10, 7)->nullable()->after('check_in_remark');
            $table->decimal('check_in_longitude', 10, 7)->nullable()->after('check_in_latitude');
            $table->unsignedInteger('check_in_accuracy_m')->nullable()->after('check_in_longitude');

            $table->decimal('check_out_latitude', 10, 7)->nullable()->after('check_out_remark');
            $table->decimal('check_out_longitude', 10, 7)->nullable()->after('check_out_latitude');
            $table->unsignedInteger('check_out_accuracy_m')->nullable()->after('check_out_longitude');

            $table->index(['user_id', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::table('field_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'work_date']);
            $table->dropColumn([
                'check_in_latitude', 'check_in_longitude', 'check_in_accuracy_m',
                'check_out_latitude', 'check_out_longitude', 'check_out_accuracy_m',
            ]);
        });
    }
};
