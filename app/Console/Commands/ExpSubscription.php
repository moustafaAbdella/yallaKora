<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check is subscription expierd';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now();
        $formatedDate  = $date->format('Y-m-d H:i:s');

        $user = User::where('ends_at','<', $formatedDate)->where('premuim', '=' , '1')
            ->where('never_ends', '!=' , '1')->chunk(20, function($users) {
                dispatch(new \App\Jobs\ExpSubscription($users))->onQueue('subscription');
        });

        $this->info( $user);
    }
}
