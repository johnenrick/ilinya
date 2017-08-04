<?php


namespace App\Ilinya;

use Illuminate\Support\Facades\Cache;
use App\Ilinya\ServiceProvider;
use App\Ilinya\Webhook\Messaging;
use App\Ilinya\User;
use App\Ilinya\Facebook\QuickReplyTemplate;
use App\Ilinya\Facebook\QuickReplyElement;
use App\Ilinya\Facebook\ButtonTemplate;
use App\Ilinya\Facebook\ButtonElement;
use App\Ilinya\Facebook\GenericTemplate;


//@models
use App\BusinessType;

class Ilinya{
  
    public  $GET_STARTED      = "@start";
    public  $CATEGORIES       = "@categories";
    public  $MY_QUEUE_CARDS   = "@my_queue_cards";
    public  $USER_GUIDE       = "@users_guide";
    public  $CONVERSATION     = "@conversation";
    public $ERROR             = "I'm sorry but I can't do what you want me to do :'(";
    private $user;
    private $messaging;
    private $serviceProvider;

    public function __construct(Messaging $messaging){
        $this->messaging = $messaging;
        $this->serviceProvider = new ServiceProvider();
        
    }   

    public function user(){
        $user = $this->serviceProvider->getUser($this->messaging->getSenderId());
        $this->user = new User($this->messaging->getSenderId(), $user['first_name'], $user['last_name']);
    }

    public function start(){
        $this->user();
        return "Hi ".$this->user->getFirstName()."! My Name is Ilinya, I can help to get your reservations or tickets easily. Just follow my instructions and you will be good to go!";
    }
    
    public function categories(){
        $categories = BusinessType::get();
        $buttons = [];
        if($categories){
            foreach ($categories as $category) {
                $buttons[] = ButtonElement::title($category['title'])->type('postback')->payload('categories@'.strtolower($category['title']));
            }
        }

        return ButtonTemplate::toArray('Select Categories:',$buttons);
    }

    public function conversation($category){
        $quickReplies[] = QuickReplyElement::title('Company Name')->contentType('text')->payload('@company_name');
        $quickReplies[] = QuickReplyElement::title('Company Location')->contentType('text')->payload('@location');
        $quickReplies[] = QuickReplyElement::title('')->contentType('location')->payload('');

        return QuickReplyTemplate::toArray('Select options for search:', $quickReplies);
    } 

    public function myQueueCards(){
        return "My Queue Cards";
    }

    public function userGuide(){
        return "User Guide";
    }
}