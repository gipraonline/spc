<?php

namespace Database\Seeders\Hr;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpandHrmsFeaturesSeeder extends Seeder
{
    /**
     * Adds sample data for the HR module's newer tables: birthdays/gender
     * backfill, holidays, announcements, WFH requests, a support ticket and
     * a few notifications — all against the hr_spc database. Safe to run
     * once on top of HrDatabaseSeeder — it only updates existing rows and
     * inserts new ones, it never re-inserts the original dataset.
     */
    public function run(): void
    {
        $path = __DIR__.'/seed_data_v2.sql';

        if (! file_exists($path)) {
            $this->command?->warn('seed_data_v2.sql not found — skipping expansion sample data.');

            return;
        }

        $connection = DB::connection('hr_spc');

        $tablesToReset = [
            'announcement_reads' => [1, 2, 3],
            'notifications' => [1, 2, 3, 4],
            'wfh_requests' => [1, 2],
            'support_tickets' => [1, 2],
            'announcements' => [1, 2, 3],
            'holidays' => [1, 2, 3, 4, 5, 6],
        ];

        foreach ($tablesToReset as $table => $ids) {
            if ($connection->getSchemaBuilder()->hasTable($table)) {
                $connection->table($table)->whereIn('id', $ids)->delete();
            }
        }

        if ($connection->getSchemaBuilder()->hasTable('system_settings')) {
            $connection->table('system_settings')
                ->whereIn('setting_key', ['attendance_grace_minutes', 'wfh_max_days_per_month'])
                ->delete();
        }

        $connection->unprepared(file_get_contents($path));
    }
}
