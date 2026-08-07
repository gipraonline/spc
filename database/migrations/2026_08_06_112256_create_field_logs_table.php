<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                    ->constrained('admins')
                    ->cascadeOnDelete();

            $table->date('work_date');

            $table->dateTime('check_in_time');

            $table->dateTime('check_out_time')->nullable();

            $table->text('check_in_remark')->nullable();

            $table->text('check_out_remark')->nullable();

            $table->enum('status', [
                'Checked In',
                'Checked Out'
            ])->default('Checked In');

            $table->timestamps();

            $table->unique(['user_id', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_logs');
    }
};