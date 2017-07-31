<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Messages\Message;
use Mpociot\BotMan\Facebook\ElementButton;
use Mpociot\BotMan\Facebook\ButtonTemplate;


/*
  @Model
*/
  use App\BusinessType;

class BotManController extends APIController
{

  protected $recipient = array(
    "id" => null,
    "first_name" => null,
    "last_name" => null
  );

  protected $attachment = array(
    "type" => null,
    "payload" => null
  );

  protected $propertName = array(
    "template_type" => null,
    "text"  => null,
    "buttons" => null
  );

  protected $button = array(
    'title' => null,
    'type'  => 'postback',
    'url' => null,
    'payload' => null
  );


  public function handler(){
    $ilinya = app('botman');
    $ilinya->verifyServices('GoCentral123456789ekennCKdashIlinya2017143143leadUsLord');

    /*

                Actions Here

    */


    // Persistent Menus
    $ilinya->hears('@start', function (BotMan $bot) {$this->start($bot);});
    $ilinya->hears('@categories', function (BotMan $bot) {$this->categories($bot);});
    $ilinya->hears('@my_queue_cards', function (BotMan $bot) {$this->queueCards($bot);});
    $ilinya->hears('@user_guide', function (BotMan $bot) {$this->guide($bot);});



    //Categories
    $ilinya->hears('@ktv', function (BotMan $bot) {$this->categoryHandler($bot,'KTV');});
    $ilinya->hears('@shippings', function (BotMan $bot) {$this->categoryHandler($bot,'Shippings');});

    // start listening
    $ilinya->listen();
  }

  public function start(Botman $ilinya){
     $ilinya->typesAndWaits(2);
     $ilinya->reply('Hi '.$ilinya->getUser()->getFirstName().'! My Name is Ilinya, I can help to get your reservations or tickets easily. Just follow my instructions and you will be good to go!');
     $this->categories($ilinya);
  }

  public function categories(Botman $ilinya){
    $buttons = array();
    $categories = BusinessType::get();
    if($categories){
      $i = 0;
      foreach ($categories as $category) {
        $buttons[] = ElementButton::create($category['title'])->type('postback')->payload('@'.strtolower($category['title']));
      }
    }
    $ilinya->reply(ButtonTemplate::create('Select Categories:')->addButtons($buttons));
  }
  
  public function categoryHandler(BotMan $ilinya, $category){
    $ilinya->reply($category);
  }

  public function queueCards(BotMan $ilinya){
    $id = $ilinya->getUser()->getId();
  }

  public function guide(BotMan $ilinya){

  }

}
