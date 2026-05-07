<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected $commands=[
        \App\Console\Commands\MehomanWarning::class,

    ];
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('expire:mehoman-warning')->everyMinute();
        $schedule->command('subscriptions:expire')->daily();
        $schedule->command('subscriptions:reminder')->daily();
        $schedule->command('app:expire-subscriptions')->dailyAt('01:00');
        $schedule->command('pricing:refresh-live-rates')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
