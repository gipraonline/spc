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
        Schema::create('employee_bank_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('n_employee_id');

            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('document_path');
            $table->enum('status', ['Active', 'Inactive'])->nullable();

            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_submissions');
    }
};
