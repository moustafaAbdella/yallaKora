<?php

namespace App\Notification;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NotificationTe {

    protected $url;
    protected $token;
    protected $idChatPayment;

    /**
     * Initializes the NotificationTe.
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = "https://api.telegram.org/bot";
        $this->token = env('te_token');
        $this->idChatAdmin = env('te_chat_id_admin');
        $this->idChatPayment = env('te_chat_id_payment');

    }

    /**
     * return domain.
     *
     * @return void
     */
    public function getDomainUrl(){
        return $this->url . $this->token;
    }

    /**
     * return domain.
     *
     * @return void
     */
    public function sendMsgToAdmin($text){
//        return '';
        if($this->idChatAdmin != null &&  $this->idChatAdmin != '' && $this->token != null && $this->token != ''){
            $api_telgram = new Client();
            $test = $api_telgram->request('GET', $this->getDomainUrl() . '/sendMessage?chat_id=' . $this->idChatAdmin . '&text=' . $text , ['http_errors' => false]);
        }
    }

    /**
     * return domain.
     *
     * @return void
     */
    public function sendMsgToPayment($text){
//        return '';
        if($this->idChatPayment != null &&  $this->idChatPayment != '' && $this->token != null && $this->token != ''){
            $api_telgram = new Client();
            $test = $api_telgram->request('GET', $this->getDomainUrl() . '/sendMessage?chat_id=' . $this->idChatPayment . '&text=' . $text , ['http_errors' => false]);
        }
    }


}
