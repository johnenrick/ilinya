<?php

namespace App\Ilinya;

use App\Ilinya\Webhook\Messaging;
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
        return [
            "type"  =>  "message"
        ];
    }

    public function extractDataFromPostback(){
        $payload = $this->messaging->getPostback()->getPayload();
        return [
            "type" => "postback",
            "payload" => $payload
        ];
    }

    public function reply($data){
        if (method_exists($data, "toMessage")) {
            $data = $data->toMessage();
        } else if (gettype($data) == "string") {
            $data = ["text" => $data];
        }
        $id = $this->messaging->getSenderId();
        $this->serviceProvider->send($id, $data);
    }
}