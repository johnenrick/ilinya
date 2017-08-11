<?php

namespace App\Ilinya;

use App\Ilinya\Webhook\Messaging;
use App\Ilinya\Webhook\Message;

class MessageExtractor{
   protected $messaging;

   function __construct(Messaging $messaging){
      $this->messaging = $messaging;
   }

   public function extractData(){
        $type = $this->messaging->getType();
        if($type == "message") {
            return $this->extractDataFromMessage();
        } 
        else if ($type == "postback") {
            return $this->extractDataFromPostback();
        }
        else if($type == "read"){
            return $this->extractDataFromRead();
        }
        else if($type == "delivery"){
            return $this->extractDataFromDelivery();
        }
        return [];
    }

    public function extractDataFromMessage(){
        $message = new Message($this->messaging->getMessageArray());
        return [
            "type"          => "message",
            "text"          => $message->getText(),
            "attachments"   => $message->getAttachments(),
            "quick_reply"   => $message->getQuickReply()
        ];
    }

    public function extractDataFromPostback(){
        $payload = $this->messaging->getPostback()->getPayload();
        return [
            "type"      => "postback",
            "payload"   => $payload
        ];
    }

    public function extractDataFromRead(){
        return [
            "type"  => "read"
        ];
    }

    public function extractDataFromDelivery(){
        return [
            "type"  => "delivery"
        ];
    }
}