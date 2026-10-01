<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The HR module's access tier for anyone holding this role. Nullable:
     * a role with no value set here doesn't grant any fixed tier, and
     * EmployeeHrSyncService falls back to its reporting-structure check
     * (manager if they have direct reports, otherwise employee).
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('hr_access')->nullable()->after('identifier');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('hr_access');
        });
    }
};
