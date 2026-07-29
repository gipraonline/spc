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
        Schema::create('employee_edit_logs', function (Blueprint $table) {
             $table->bigIncrements('n_log_id');

            $table->integer('n_employee_id');
            $table->integer('n_pre_designation_id');
            $table->integer('n_new_designation_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_edit_logs');
    }
};
