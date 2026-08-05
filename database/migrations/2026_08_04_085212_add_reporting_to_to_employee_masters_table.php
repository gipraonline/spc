<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('employee_masters', function (Blueprint $table) {
        $table->unsignedBigInteger('reporting_to')->nullable()->after('n_designation_id');

        $table->foreign('reporting_to')
              ->references('n_employee_id')
              ->on('employee_masters')
              ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('employee_masters', function (Blueprint $table) {
        $table->dropForeign(['reporting_to']);
        $table->dropColumn('reporting_to');
    });
}
};