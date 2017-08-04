<?php

namespace App\Ilinya\Facebook;

class ButtonElement{
  protected $type;
  protected $url;
  protected $payload;
  protected $title;


  function __construct($title){
    $this->title = $title;
  }

  public static function title($title){
    return new static($title);
  }

  public function url($url){
    $this->url = $url;
    return $this;
  }

  public function type($type){
    $this->type = $type;
    return $this;
  }

  public function payload($payload){
    $this->payload = $payload;
    $this->toArray();
    return $this;
  }

  public function toArray(){
    $response =  [
      "type"    => $this->type,
      "url"     => $this->url,
      "payload" => $this->payload,
      "title"   => $this->title
    ];
    
    return $response;
  }

}