<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\EmployeeMaster;
use App\Services\Hr\EmployeeHrSyncService;
use Illuminate\Console\Command;

/**
 * One-time (or repeatable) backfill for employees that existed before the
 * SPC -> HR sync (EmployeeHrSyncService) was wired into the employee
 * create/edit screens. Going forward, saving an employee in SPC keeps HR's
 * copy (role, designation, department, ...) up to date automatically; this
 * command just catches up everyone who was never re-saved since then.
 *
 * Deliberately read-only on the SPC side (it only loops and calls the same
 * sync used by the controller) and does not touch auth/session/login code
 * at all, so it can't affect sign-in.
 */
class SyncEmployeesToHr extends Command
{
    protected $signature = 'hr:sync-employees {--dry-run : List who would be synced without writing anything}';

    protected $description = 'Backfill every SPC employee into the HR module (role, designation, department, etc.)';

    public function handle(): int
    {
        $employees = EmployeeMaster::whereNotNull('c_employee_email')
            ->where('c_employee_email', '!=', '')
            ->get();

        if ($employees->isEmpty()) {
            $this->info('No employees with a work email to sync.');

            return self::SUCCESS;
        }

        $this->info("Found {$employees->count()} employee(s) to sync.");

        $dryRun = (bool) $this->option('dry-run');
        $synced = 0;
        $failed = 0;

        foreach ($employees as $employee) {
            if ($dryRun) {
                $this->line("Would sync: {$employee->c_employee_name} <{$employee->c_employee_email}>");

                continue;
            }

            try {
                EmployeeHrSyncService::sync($employee);
                $synced++;
            } catch (\Throwable $e) {
                $failed++;
                $this->error("Failed for {$employee->c_employee_email}: {$e->getMessage()}");
            }
        }

        if (! $dryRun) {
            $this->info("Synced: {$synced}. Failed: {$failed}.");
        }

        // Standalone admin logins with no linked employee (e.g. a
        // "Gipra Admin" style account) never go through the loop above —
        // catch their HR role up too.
        $standaloneAdmins = Admin::whereNull('n_employee_id')->get();

        if ($standaloneAdmins->isNotEmpty()) {
            $this->info("Found {$standaloneAdmins->count()} standalone admin account(s) with no linked employee.");

            foreach ($standaloneAdmins as $admin) {
                if ($dryRun) {
                    $this->line("Would sync role for: {$admin->c_name} <{$admin->c_username}>");

                    continue;
                }

                try {
                    EmployeeHrSyncService::syncRoleForAdmin($admin);
                } catch (\Throwable $e) {
                    $failed++;
                    $this->error("Failed for {$admin->c_username}: {$e->getMessage()}");
                }
            }
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
