<?php

namespace App\Ilinya\Webhook;
use App\Ilinya\Webhook\Postback;
use App\Ilinya\User;
use App\Ilinya\Webhook\Message;


class Messaging
{
    public static $TYPE_MESSAGE = "message";

    private $senderId;
    private $recipientId;
    private $timestamp;
    private $message;
    private $messageArray;
    private $type;
    private $postback;
    private $user;

    public function __construct(array $data)
    {
        $this->senderId = $data["sender"]["id"];
        $this->recipientId = $data["recipient"]["id"];
        $this->timestamp = $data["timestamp"];
        if(isset($data["message"])) {
            $this->type = "message";
            $this->messageArray = $data['message'];
            $this->message = new Message($data["message"]);
        } else if (isset($data["postback"])) {
            $this->type = "postback";
            $this->postback = new Postback($data["postback"]);
        }
    }

    public function getSenderId(){
        return $this->senderId;
    }

    public function getRecipientId(){
        return $this->recipientId;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }

    public function getMessage(){
        return $this->message;
    }

    public function getMessageArray(){
        return $this->messageArray;
    }

    public function getType(){
        return $this->type;
    }

    public function getPostback(){
        return $this->postback;
    }

}