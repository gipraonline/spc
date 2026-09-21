<?php

namespace Database\Seeders\Hr;

use App\Models\Hr\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrDatabaseSeeder extends Seeder
{
    /**
     * Loads the HR module's sample dataset (10 users/employees across
     * HR, Sales and Operations, plus attendance, leave, payroll, PF,
     * appraisal, recruitment and incentive history) into the hr_spc
     * database — this never touches the SPC module's own database.
     *
     * The statements in seed_data.sql are ordered so that parent rows
     * (departments, users, employees, ...) are always inserted before
     * rows that reference them via foreign key.
     */
    public function run(): void
    {
        $path = __DIR__.'/seed_data.sql';

        if (! file_exists($path)) {
            $this->command?->warn('seed_data.sql not found — skipping HR sample data.');

            return;
        }

        $connection = DB::connection('hr_spc');

        $this->clearExistingData($connection);

        if ($connection->getDriverName() === 'sqlite') {
            $connection->statement('PRAGMA foreign_keys = ON');
        } else {
            $connection->statement('SET FOREIGN_KEY_CHECKS = 1');
        }

        $connection->unprepared(file_get_contents($path));

        // The SQL dump carries opaque bcrypt hashes; normalize every seeded
        // account to the documented demo password so sign-in always works.
        User::query()->update(['password' => bcrypt('Password@123')]);

        // Sample data for WFH, Announcements, Support, Notifications, Holidays,
        // and the document-verification/birthday fields.
        $this->call(ExpandHrmsFeaturesSeeder::class);
    }

    protected function clearExistingData($connection): void
    {
        $tables = [
            'announcement_reads',
            'notifications',
            'support_tickets',
            'wfh_requests',
            'holidays',
            'audit_logs',
            'attendance_regularizations',
            'attendance',
            'leave_requests',
            'leave_balances',
            'payslips',
            'pf_contributions',
            'incentive_payouts',
            'salary_structures',
            'employee_documents',
            'employee_history',
            'employee_secondary_contacts',
            'onboarding_checklist_items',
            'candidates',
            'job_requisitions',
            'appraisal_goals',
            'appraisals',
            'appraisal_cycles',
            'gratuity_records',
            'system_settings',
            'incentive_rules',
            'pf_accounts',
            'payroll_runs',
            'leave_types',
            'designations',
            'employees',
            'users',
            'departments',
        ];

        if ($connection->getDriverName() === 'sqlite') {
            $connection->statement('PRAGMA foreign_keys = OFF');
        } else {
            $connection->statement('SET FOREIGN_KEY_CHECKS = 0');
        }

        foreach ($tables as $table) {
            if ($connection->getSchemaBuilder()->hasTable($table)) {
                $connection->table($table)->delete();
            }
        }

        if ($connection->getDriverName() === 'sqlite') {
            $connection->statement('PRAGMA foreign_keys = ON');
        } else {
            $connection->statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
