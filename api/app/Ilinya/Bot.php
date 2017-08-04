<?php

namespace App\Ilinya;

use App\Ilinya\Webhook\Messaging;
use App\Ilinya\Webhook\Message;
use Illuminate\Support\Facades\Log;
use App\Ilinya\ServiceProvider;


class Bot{  
  
    private     $messaging;
    protected   $serviceProvider;

    public function __construct(Messaging $messaging){
        $this->messaging = $messaging;
        $this->serviceProvider = new ServiceProvider();
    }

    public function extractData(){
        $type = $this->messaging->getType();
        if($type == "message") {
            return $this->extractDataFromMessage();
        } else if ($type == "postback") {
            return $this->extractDataFromPostback();
        }
        return [];
    }

    public function extractDataFromMessage(){
        $message = new Message($this->messaging->getMessageArray());
        return [
            "type"  => "message",
            "text" => $message->getText()
        ];
    }

    public function extractDataFromPostback(){
        $payload = $this->messaging->getPostback()->getPayload();
        return [
            "type" => "postback",
            "payload" => $payload
        ];
    }

    public function reply($data, $flag){
        $message = ($flag == true)?["text" => $data] : $data;
        $id = $this->messaging->getSenderId();
        $this->serviceProvider->send($id, $message);
    }
}