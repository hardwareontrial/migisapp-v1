<?php

namespace App\Console;

use App\Http\Controllers\API\Elearning\ElearningScheduleController;
use App\Http\Controllers\API\Hr\AppAttendanceController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        /** Commented this out in staging or production env */
        // $schedule->call(function(){
        //   app(AppAttendanceController::class)->autosync();
        // })->cron('58 0,7,11,16 * * *')->description('Sync Attendance from DB att_log');
        // $schedule->call(function(){
        //   app(AppAttendanceController::class)->generateAutoText();
        // })->cron('2 1,8,12,17 * * *')->description('Sync Text Upload File from DB app_hr_attendances');
        // $schedule->call(function(){
        //   app(AppAttendanceController::class)->scanNewPegawai();
        // })->everySixHours()->description('Sync New Employee from DB att_log');
        // $schedule->call(function(){
        //   app(ElearningScheduleController::class)->turnOffSchedule();
        // })->cron('13 0 * * *')->description('Turn Off Exam Schedule.');
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
