<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Farm Care Advisers and Tele Callers are not employees until they reach
 * their goals and are promoted to Farm Care Officer. `engagement_type`
 * records that: 'associate' (not an employee yet) or 'employee'.
 *
 * The default is 'employee', so every row that exists today behaves
 * exactly as before. Only records created afterwards with an FCA / TC
 * designation are saved as 'associate' (see EmployeeController::store).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('employee_masters', 'engagement_type')) {
            Schema::table('employee_masters', function (Blueprint $table) {
                $table->string('engagement_type', 20)->default('employee')->after('c_employee_type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('employee_masters', 'engagement_type')) {
            Schema::table('employee_masters', function (Blueprint $table) {
                $table->dropColumn('engagement_type');
            });
        }
    }
};
