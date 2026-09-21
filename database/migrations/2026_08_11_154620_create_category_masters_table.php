<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_masters', function (Blueprint $table) {
            $table->bigIncrements('n_category_id');

            $table->string('c_category_code', 50);
            $table->string('c_category_name', 150);

            $table->char('c_status', 1)->default('Y');

            $table->timestamps();
            $table->softDeletes();

            $table->unique('c_category_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_masters');
    }
};