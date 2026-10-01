<?php

namespace App\Console\Commands;

use App\Services\Hr\IncentiveEngine;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CalculateIncentives extends Command
{
    protected $signature = 'hr:incentives:calculate {--month= : YYYY-MM (default: this month and last month)}';

    protected $description = 'Calculate pending incentive payouts from approved + paid sales orders (approved/paid payouts are never changed).';

    public function handle(IncentiveEngine $engine): int
    {
        $months = $this->option('month')
            ? [Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()]
            : [now()->subMonthNoOverflow()->startOfMonth(), now()->startOfMonth()];

        foreach ($months as $m) {
            $r = $engine->run($m->year, $m->month);
            $this->info(sprintf('%s: %d created, %d updated, %d locked, %d removed - total ₹%s',
                $r['month'], $r['created'], $r['updated'], $r['locked'], $r['removed'], number_format($r['total'], 2)));
        }

        return self::SUCCESS;
    }
}
