<?php

namespace App\Console\Commands;

use App\Models\EmployeeMaster;
use App\Models\Hr\Employee as HrEmployee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * One-time conversion of Farm Care Advisers / Tele Callers that were created
 * before the associate rule existed. They are marked 'associate' so they stop
 * being treated as employees (no HR portal, payroll, leave, PF).
 *
 * Safe by default: nothing is deleted. Without --exit-hr-records their
 * existing HR rows are left exactly as they are. Always run --dry-run first.
 */
class MarkAssociates extends Command
{
    protected $signature = 'employees:mark-associates
        {--dry-run : List who would change without writing anything}
        {--exit-hr-records : Also set their existing HR record to "exited" so they leave payroll, attendance and leave (reversible: promoting them restores it)}';

    protected $description = 'Mark existing Farm Care Advisers and Tele Callers as associates (not employees)';

    public function handle(): int
    {
        $people = EmployeeMaster::with('designation')
            ->where('engagement_type', EmployeeMaster::TYPE_EMPLOYEE)
            ->whereHas('designation', fn ($q) => $q->whereIn('identifier', EmployeeMaster::ASSOCIATE_DESIGNATIONS))
            ->orderBy('c_employee_code')
            ->get();

        if ($people->isEmpty()) {
            $this->info('Nothing to convert.');

            return self::SUCCESS;
        }

        $dry = (bool) $this->option('dry-run');
        $exitHr = (bool) $this->option('exit-hr-records');

        $rows = [];

        foreach ($people as $p) {
            $hr = HrEmployee::where('employee_master_id', $p->n_employee_id)->first();
            $payslips = $hr ? DB::connection('spc_hr')->table('payslips')->where('employee_id', $hr->id)->count() : 0;

            $rows[] = [
                $p->c_employee_code,
                $p->c_employee_name,
                $p->designation?->identifier,
                $hr ? $hr->employment_status : 'no HR record',
                $payslips,
            ];

            if ($dry) {
                continue;
            }

            $p->update(['engagement_type' => EmployeeMaster::TYPE_ASSOCIATE]);

            if ($hr && $exitHr && $hr->employment_status !== 'exited') {
                $hr->update(['employment_status' => 'exited']);
            }
        }

        $this->table(['Code', 'Name', 'Type', 'HR status', 'Payslips'], $rows);

        $this->info($dry
            ? 'Dry run: '.count($rows).' would be marked as associates. Nothing was written.'
            : count($rows).' marked as associates.'.($exitHr ? ' Their HR records were set to exited.' : ' HR records were left unchanged.'));

        return self::SUCCESS;
    }
}
