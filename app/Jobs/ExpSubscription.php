<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
// use Illuminate\Support\Facades\DB;
use App\Notification\NotificationTe;

class ExpSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $user) { 
            // DB::table('user')
            // ->where('id', $user->id)
            // ->update(['title' => "Updated Title"]);
            $getSub = SubscriptionHistory::find($user->subscription_id);
            if($getSub != null){
                $getSub->active = 0;
                $getSub->save();
            }
            $sendChat = new NotificationTe;
            $sendChat->sendMsgToPayment(' انتهاء اشتراك ' . $user->name  . ' id : ' . $user->subscription_id);

            $userUpdate = new User();
            $userUpdate = $user;
            $userUpdate->premuim = 0;
            $userUpdate->ends_at = null;
            $userUpdate->subscription_id = null;
            $userUpdate->save();
        }
    }
}
