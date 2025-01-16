<?php

namespace App\Notification;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

class NotificationFb
{
    private $auth;
    private $messaging;


    public function __construct($type = 'main')
    {
//        $firebase = (new Factory)
//            ->withServiceAccount(config('firebase.projects.app.credentials.file'));
//
//        $messaging = $firebase->createMessaging();
//        $auth = app('firebase.auth');
//        $this->auth = $auth;
        if('main'){
            $firebaseCredentialsPath = storage_path('/app/firebase-auth.json');
            Config::set('firebase.projects.app.credentials.file', $firebaseCredentialsPath);
        } else {
            $firebaseCredentialsPath = storage_path('/app/firebase-auth2.json');
            Config::set('firebase.projects.app.credentials.file', $firebaseCredentialsPath);
        }
        $this->messaging = app('firebase.messaging');;

    }

    /**
     * @throws \Exception
     */
    public function sendMsgToAllUser($title,$body,$image,$type)
    {
        if ($type == 'main'){
            $firebaseCredentialsPath = storage_path('/app/firebase-auth.json');
        } else {
            $firebaseCredentialsPath = storage_path('/app/firebase-auth2.json');
        }
        $firebase = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath);
        $messaging = $firebase->createMessaging();
        $body = [
            'topic' => 'all',
            "priority" => "high",
            "mutable_content" => true,
            "content_available" => true,
            "notification" => [
                "body" => $body,
                "title"=> $title,
                "sound" => "alarm.caf"
            ],
            "apns"=> [
                "payload"=> [
                    "aps"=> [
                        "content_available"=> 1,
                        "mutable-content"=> 1
                    ]
                ]
            ],
            "data"  =>  [
                "type"  =>  "auto",
                "title" =>  $title,
                "body"  =>  $body,
                "bigPicture" =>  $image,
                "content_available" => true,
                "priority" => "high"
            ]// optional
        ];

        $message = CloudMessage::fromArray($body);

        return $messaging->send($message);
    }

    /**
     * return domain.
     *
     * @return void
     */
    public function sendMsgToFavourite($title,$body,$comp,$team_a,$team_b,$sound)
    {
        $condition = "'team_".$team_a."' in topics || 'team_".$team_b."' in topics || 'comp_".$comp."' in topics";

        $body = [
            "condition" => $condition,
            "notification" => [
                "body" => $body,
                "title"=> "جوكر سبورت",
                "subtitle" => $title,
                "content_available" => true,
                "priority" => "high",
                "sound" => $sound
            ],
            "apns"=> [
                "payload"=> [
                    "aps"=> [
                        "contentAvailable"=> true,
                        "mutable-content"=> 1
                    ]
                ]
            ],
            "data"  =>  [
                "type"  =>  "auto",
                "title" =>  $title,
                "body"  =>  $body,
                "comp" =>  $comp,
                "team_a" =>  $team_a,
                "team_b" =>  $team_b,
                "content_available" => true,
                "priority" => "high"
            ]
        ];

        $message = CloudMessage::fromArray($body);

        return $this->messaging->send($message);
    }


}
