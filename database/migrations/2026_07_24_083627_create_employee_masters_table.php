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
        Schema::create('employee_masters', function (Blueprint $table) {
            $table->bigIncrements('n_employee_id');

            $table->string('c_employee_code')->index();
            $table->string('c_username')->nullable();
            $table->string('c_password')->nullable();
            $table->string('c_employee_name')->nullable();
            $table->string('c_employee_address')->nullable();
            $table->string('c_employee_email')->nullable();
            $table->string('n_employee_phone')->nullable();
            $table->string('profile_path')->nullable();

            $table->integer('n_designation_id')->nullable();
            $table->integer('n_store_id')->nullable();
            $table->integer('n_operations_poolid')->default(0);
            $table->integer('n_pool_id')->nullable();

            $table->string('c_status')->default('Y');

            $table->timestamps();

            // Soft Delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_masters');
    }
};