<?php

namespace App\Console;

use App\Console\Commands\SearchEntities;
use App\Console\Commands\SearchInvoices;
use App\Console\Commands\SearchProducts;
use App\Console\Commands\UpdateEntities;
use App\Console\Commands\UploadRedemptions;
use App\Console\Commands\EntiiesAsingTypeMass;
use App\Console\Commands\CustomCommand;
use App\Console\Commands\UpdateZohoInvoices;
use App\Console\Commands\FixZohoEntities;







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
        SearchEntities::class,
        SearchInvoices::class,
        SearchProducts::class,
        UpdateEntities::class,
        UploadRedemptions::class,
        EntiiesAsingTypeMass::class,
        CustomCommand::class,
        UpdateZohoInvoices::class,
        FixZohoEntities::class,
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


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
