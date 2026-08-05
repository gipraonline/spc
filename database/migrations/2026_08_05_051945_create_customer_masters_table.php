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
        Schema::create('customer_masters', function (Blueprint $table) {

    $table->id('n_customer_id');

    $table->string('c_customer_code',20)->unique();
    $table->string('c_customer_name');
    $table->string('n_mobile',10);
    $table->string('n_whatsapp',10)->nullable();
    $table->string('c_email')->nullable();

    $table->text('c_address')->nullable();

    $table->string('c_district')->nullable();
    $table->string('c_state')->nullable();
    $table->string('c_pincode',10)->nullable();

    $table->enum('c_status',['Y','N'])->default('Y');

    $table->unsignedBigInteger('created_by')->nullable();

    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_masters');
    }
};