<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
        $schedule->call(function () {
            $results = DB::table('weixins')
                ->select('id')
                ->get();
            foreach($results as $r){
                $weixin_support = app('WeixinSupport' , ['weixin_id' => $r->id]);
                $weixin_support->cacheAccessTokenAndTicket();
            }
        })->hourly();
    }
}
