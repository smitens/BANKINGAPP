<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
protected function schedule(Schedule $schedule): void
{
$schedule->command('fetch:crypto')->everyFiveMinutes();
$schedule->command('fetch:currency')->hourly();
$schedule->command('inspire')->hourly();
}
}
