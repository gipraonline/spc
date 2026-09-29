<?php

namespace App\Console\Commands;

use App\Models\Hr\Employee;
use App\Models\Hr\EmployeeExit;
use App\Models\Hr\EmployeeHistory;
use App\Services\Hr\EmployeeExitService;
use Illuminate\Console\Command;

/**
 * One-off catch-up for employees that existed before employee history
 * was wired up: gives each a "Joined" timeline entry and creates a
 * "details pending" exit record for anyone already marked exited.
 */
class BackfillEmployeeHistory extends Command
{
    protected $signature = 'hr:backfill-history {--dry-run}';

    protected $description = 'Add Joined entries and missing exit records for existing employees';

    public function handle(): int
    {
        $dry = $this->option('dry-run');
        $joined = 0;
        $exits = 0;

        Employee::with('user')->orderBy('id')->each(function (Employee $e) use ($dry, &$joined, &$exits) {
            $hasJoined = EmployeeHistory::where('employee_id', $e->id)->where('event_type', 'joined')->exists();

            if (! $hasJoined) {
                $joined++;
                if (! $dry) {
                    EmployeeHistory::log(
                        $e->id, 'joined', 'Joined company', null,
                        trim(($e->designation->title ?? 'Employee').' — '.($e->department->name ?? '—'), ' —'),
                        null, 'Backfilled', $e->date_of_joining ? (string) $e->date_of_joining : null
                    );
                }
            }

            if ($e->employment_status === 'exited'
                && ! EmployeeExit::where('employee_id', $e->id)->whereIn('status', ['on_notice', 'completed'])->exists()) {
                $exits++;
                if (! $dry) {
                    EmployeeExitService::ensureExitRecord($e);
                }
            }
        });

        $this->info(($dry ? '[dry run] ' : '')."Joined entries: {$joined}, exit records created: {$exits}");

        return self::SUCCESS;
    }
}
