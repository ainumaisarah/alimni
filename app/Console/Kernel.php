<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clear uploads hourly
        $schedule->command('uploads:clear')->hourly();

        // Backup commands
        if (app()->environment('local', 'development')) {
            // Local/dev: run every minute for testing
            $schedule->command('backup:run')->everyMinute()->withoutOverlapping();
            $schedule->command('backup:clean')->everyMinute()->withoutOverlapping();
        } else {
            // Production: daily backups
            $schedule->command('backup:run')->dailyAt('02:00')->withoutOverlapping();
            $schedule->command('backup:clean')->daily()->withoutOverlapping();
        }
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
