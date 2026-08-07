<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_log_tasks', function (Blueprint $table) {

            $table->id();

            $table->foreignId('field_log_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('task');

            $table->enum('status', [
                'Pending',
                'Done'
            ])->default('Pending');

            $table->text('pending_remark')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_log_tasks');
    }
};