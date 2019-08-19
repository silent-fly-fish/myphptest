<?php

namespace App\Console;


use App\Jobs\DoctorHotsJob;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function (){
            logText('life ---------->','cron');
        });

        //每天晚上2:00医生热度积分脚本任务执行
        $schedule
            ->job(new DoctorHotsJob)
            ->dailyAt('2:00');


    }
}
