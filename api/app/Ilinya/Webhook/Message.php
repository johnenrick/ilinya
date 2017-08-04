<?php

namespace App\Ilinya\Webhook;


class Message{

    private $mId;
    private $text;
    private $attachments;
    private $quickReply;

    public function __construct(array $data){
     $this->mId = $data["mid"];
     $this->text = isset($data["text"]) ? $data["text"] : null;
     $this->attachments = isset($data["attachments"]) ? $data["attachments"] : null;
     $this->quickReply = isset($data["quick_reply"]) ? $data["quick_reply"] : null;
    }

    public function getId(){
        return $this->mId;
    }

    public function getText(){
        return $this->text;
    }

    public function getAttachments(){
        return $this->attachments;
    }

    public function getQuickReply(){
        return $this->quickReply;
    }
}