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
        Schema::create('franchise_masters', function (Blueprint $table) {
             $table->bigIncrements('n_store_id');

            $table->string('c_store_code')
                  ->nullable()
                  ->index();

            $table->integer('n_clustor_manager_id')
                  ->nullable();

            $table->string('c_store_name')
                  ->nullable();

            $table->string('c_store_address')
                  ->nullable();

            $table->string('c_store_email')
                  ->nullable();

            $table->string('n_store_phone')
                  ->nullable();

            $table->string('c_store_status')
                  ->default('Y');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_masters');
    }
};
