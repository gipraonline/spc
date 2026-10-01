<?php

namespace App\Console\Commands;

use App\Services\Hr\DesignationSyncService;
use Illuminate\Console\Command;

class MergeDuplicateHrDesignations extends Command
{
    protected $signature = 'hr:merge-duplicate-designations {--dry-run : Only list what would be merged}';

    protected $description = 'Merge HR designations that share the same title and department, moving their employees onto the oldest entry';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        [$groups, $removed] = DesignationSyncService::mergeDuplicates($dry, function ($keeper, $dup) use ($dry) {
            $this->line(sprintf(
                '%s #%d -> #%d  "%s" (dept %s)',
                $dry ? 'would merge' : 'merged',
                $dup->id,
                $keeper->id,
                $keeper->title,
                $keeper->department_id ?? 'none'
            ));
        });

        $this->info("{$groups} duplicate group(s), {$removed} row(s) ".($dry ? 'would be removed.' : 'removed.'));

        return self::SUCCESS;
    }
}
