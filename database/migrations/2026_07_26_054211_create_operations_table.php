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
        Schema::create('operations', function (Blueprint $table) {
            $table->id('n_operation_id');
            $table->string('c_operation_name');
            $table->unsignedBigInteger('n_pool_id');
            $table->timestamps();

            // Uncomment if pools table exists
            // $table->foreign('n_pool_id')
            //       ->references('n_pool_id')
            //       ->on('pools')
            //       ->cascadeOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
