<?php

namespace App\Listeners;

use App\Models\User;
use App\Notification\NotificationFb;
use App\Notification\NotificationTe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;

class PassportEventSubscriber
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AccessTokenCreated $event): void
    {
        $te = new NotificationTe();
        $te->sendMsgToAdmin('omaaaaaaaaa');
        $te->sendMsgToAdmin($event->userId);
        $te->sendMsgToAdmin($event->tokenId);

        $data = Token::where('user_id','=',$event->userId)->where('id', '!=',$event->tokenId)->update(['revoked' => true]);
        $te->sendMsgToAdmin(json_encode($data));
    }
}
