<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        'App\Console\Commands\SendTWEmails'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        /*if (!strstr(shell_exec('ps xf'), 'php artisan queue:work')){
            $schedule->command('queue:work')
                     ->everyMinute()
                     ->withoutOverlapping();
        }*/
        //$schedule->exec("php artisan queue:work");
        //$schedule->exec("php artisan queue:work")->appendOutputTo('/my/logs/laravel_output.log');
        //$schedule->command('queue:work --daemon --once')->withoutOverlapping();
        $schedule->command('twemail:send')
            ->evenInMaintenanceMode()
            ->onOneServer()
            //->runInBackground()
            //->withoutOverlapping()
            //->everyFiveMinutes()
            ->environments(['staging', 'production']);
        
        $schedule->command('queue:work')
            ->evenInMaintenanceMode()
            ->onOneServer()
            //->runInBackground()
            //->withoutOverlapping()
            //->everyFiveMinutes()
            ->environments(['staging', 'production']);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
