<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scrape\ScrapeJdwel;
use App\Models\Matche;
use Carbon\Carbon;
use App\Notification\NotificationTe;

class RefreshMatche extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matche:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh matches every mint ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // $count = 0;
        // while ($count < 59) {
            $date = Carbon::now();
            $fdate = Carbon::now()->subDay(1)->format('Y-m-d');
            $formatedDate  = $date->format('Y-m-d H:i:s');
            $getMatches    = Matche::where('startTime','>', $fdate)->where('startTime','<', $formatedDate)->where('statusGroup', '=' , 2)->where('statusText', '=' , 'Scheduled')->get();
            $sendChat = new NotificationTe;
            $sendChat->sendMsgToAdmin('MatchesRefresh');

            if($getMatches != null) {

                dispatch(new \App\Jobs\MatchesRefresh($getMatches))->onQueue('matchesRefresh');

            }
        //     sleep(15);
        // }

    }
}
