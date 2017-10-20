<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // * * * * * php /var/www/domain.com/public_html/artisan schedule:run 1>> /dev/null 2>&1
        
        // RESET ALL DATA ON DATABASE
        $schedule->command('migrate:reset')
            ->dailyAt('05:00')
            ->after(function () {
                // Task is complete...
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--seed' => true]);
            });
    }
}
