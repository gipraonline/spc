<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This table lives in the spc_hr database, same as employees/users/etc.
     * Note: none of the other HR-module tables are tracked as migrations in
     * this repo (they were created directly on the live database), so this
     * migration won't run unless you point it at that connection explicitly.
     */
    protected $connection = 'spc_hr';

    public function up(): void
    {
        Schema::connection('spc_hr')->create('employee_secondary_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->unique()
                ->constrained('employees')
                ->cascadeOnDelete();
            $table->string('name', 150)->nullable();
            $table->string('relation', 60)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('address', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('spc_hr')->dropIfExists('employee_secondary_contacts');
    }
};
