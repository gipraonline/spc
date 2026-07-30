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
        Schema::create('states', function (Blueprint $table) {
            $table->id('n_state_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('name')->nullable();
            $table->string('state_code', 10)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('country_id')
                  ->references('country_id')
                  ->on('countries')
                  ->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
