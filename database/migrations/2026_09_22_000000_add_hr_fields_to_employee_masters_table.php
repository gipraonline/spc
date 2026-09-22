<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SPC's employee module is now the single place an employee is
     * created — these columns are the extra fields the HR module needs
     * that employee_masters didn't carry before. They're plain columns
     * (no foreign key on department_id: the HR "departments" table lives
     * in the separate spc_hr database), filled in on the SPC create/edit
     * form and pushed into the HR module by EmployeeHrSyncService.
     */
    public function up(): void
    {
        Schema::table('employee_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('n_designation_id');
            $table->date('date_of_birth')->nullable()->after('c_employee_email');
            $table->string('gender', 10)->nullable()->after('date_of_birth');
            $table->string('personal_email')->nullable()->after('c_employee_email');
            $table->string('city')->nullable()->after('c_employee_address');
            $table->date('date_of_joining')->nullable()->after('n_designation_id');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();

            // Access level to grant on the HR side when this employee is
            // synced there: employee, manager, hr_admin or super_admin.
            $table->string('c_hr_role')->default('employee')->after('c_status');
        });
    }

    public function down(): void
    {
        Schema::table('employee_masters', function (Blueprint $table) {
            $table->dropColumn([
                'department_id',
                'date_of_birth',
                'gender',
                'personal_email',
                'city',
                'date_of_joining',
                'bank_name',
                'bank_account_number',
                'bank_ifsc',
                'c_hr_role',
            ]);
        });
    }
};
