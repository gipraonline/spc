<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panchayats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('district_id');

            $table->string('panchayat_name');

            $table->char('status', 1)
                ->default('Y')
                ->comment('Y = Active, N = Inactive');

            $table->timestamps();

            $table->index('state_id');
            $table->index('district_id');

            $table->index([
                'state_id',
                'district_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panchayats');
    }
};