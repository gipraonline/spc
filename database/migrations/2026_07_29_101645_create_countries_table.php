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
        Schema::create('countries', function (Blueprint $table) {

            $table->increments('n_country_id');

            $table->string('c_country_name', 128);

            $table->string('c_iso_code_2', 2);

            $table->string('c_iso_code_3', 3);

            $table->text('c_address_format');

            $table->boolean('postcode_required');

            $table->boolean('status')->default(1);

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
