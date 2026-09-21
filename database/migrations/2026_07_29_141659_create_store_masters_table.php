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
        Schema::create('store_masters', function (Blueprint $table) {
            $table->id('n_store_id');
            $table->string('c_store_code')->nullable()->index();
            $table->integer('n_clustor_manager_id')->nullable();
            $table->string('c_store_name')->nullable();
            $table->string('c_store_address')->nullable();
            $table->string('c_store_email')->nullable();
            $table->string('n_store_phone')->nullable();
            $table->string('c_store_status')->default('Y');
            $table->timestamps();
            
            // Soft Delete
            
            $table->softDeletes();

            // Uncomment if n_clustor_manager_id references another table
            // $table->foreign('n_clustor_manager_id')
            //       ->references('id')
            //       ->on('cluster_managers')
            //       ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_masters');
    }
};