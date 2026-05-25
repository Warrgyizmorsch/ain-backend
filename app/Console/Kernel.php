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
        /**
         * Wallet expiry scheduler
         * ---------------------------------------
         * - Run hourly → safe for time-zone drift & midnight issues.
         * - Alternative options:
         *      ->dailyAt('00:05')   // once every midnight
         *      ->twiceDaily(1, 13)  // 1 AM & 1 PM
         */
        $schedule->command('wallet:expire')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();   // queue-friendly
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // automatically route to console.php for custom Artisan commands
        require base_path('routes/console.php');
    }
}
