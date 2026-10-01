<?php

namespace App\Console\Commands;

use App\Models\Hr\EmployeeExit;
use App\Services\Hr\EmployeeExitService;
use Illuminate\Console\Command;

/**
 * People who resign/are served notice stay "on notice" (and can log in)
 * until their last working day. This completes those exits each morning:
 * disables their HR + SPC login and refreshes their history snapshot.
 */
class FinalizeDueExits extends Command
{
    protected $signature = 'hr:finalize-exits {--dry-run : List who would be exited without changing anything}';

    protected $description = 'Complete exits whose last working day has passed';

    public function handle(): int
    {
        $due = EmployeeExit::with('employee.user')
            ->where('status', 'on_notice')
            ->whereDate('last_working_day', '<=', today())
            ->get();

        if ($due->isEmpty()) {
            $this->info('No exits due.');

            return self::SUCCESS;
        }

        foreach ($due as $exit) {
            $name = $exit->employee->user->name ?? $exit->employee->employee_code ?? '#'.$exit->employee_id;

            if ($this->option('dry-run')) {
                $this->line("Would exit {$name} (last day {$exit->last_working_day->toDateString()})");

                continue;
            }

            EmployeeExitService::finalize($exit);
            $this->info("Exited {$name}");
        }

        return self::SUCCESS;
    }
}
