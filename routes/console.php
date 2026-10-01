<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

<<<<<<< HEAD
Schedule::command('hr:incentives:calculate')->dailyAt('00:10');
=======
Schedule::command('incentives:calculate-daily')->dailyAt('00:10');
>>>>>>> ecbf179f112763652c4c004034826b6c8822c12d
Schedule::command('hr:finalize-exits')->dailyAt('00:20');
