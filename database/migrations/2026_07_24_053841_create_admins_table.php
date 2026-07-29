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
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('n_role_id');
            $table->string('c_name');
            $table->string('c_username')->nullable();
            $table->string('c_password')->nullable();
            $table->string('c_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
