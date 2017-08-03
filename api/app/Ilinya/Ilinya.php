<?php


namespace App\Ilinya;

use Illuminate\Support\Facades\Cache;
use App\Ilinya\ServiceProvider;
use App\Ilinya\Webhook\Messaging;
use App\Ilinya\User;
use App\BusinessType;

class Ilinya{
  
    public  $GET_STARTED      = "@start";
    public  $CATEGORIES       = "@categories";
    public  $MY_QUEUE_CARDS   = "@my_queue_cards";
    public  $USER_GUIDE       = "@users_guide";
    public  $CONVERSATION     = "@conversation";
    private $user;
    private $messaging;
    private $serviceProvider;

    public function __construct(Messaging $messaging){
        $this->messaging = $messaging;
        $this->serviceProvider = new ServiceProvider();
        $this->user();
    }   

    public function user(){
        $user = $this->serviceProvider->getUser($this->messaging->getSenderId());
        $this->user = new User($this->messaging->getSenderId(), $user['first_name'], $user['last_name']);
    }

    public function start(){
        return "Hi ".$this->user->getFirstName()."! My Name is Ilinya, I can help to get your reservations or tickets easily. Just follow my instructions and you will be good to go!";
    }
    
    public function categories(){
        $response = [
            "attachment" => [
                "type" => "template",
                "payload" => [
                    "template_type" => "button",
                    "text" => "Select Categories:",
                    "buttons" => []
                ]
            ]
        ];

        $categories = BusinessType::get();
        if($categories){
            foreach ($categories as $category) {
                $response['attachment']['payload']['buttons'][] = [
                    "title" => $category['title'],
                    "type"  => "postback",
                    "payload" => 'categories@'.strtolower($category['title'])
                ];
            }
        }

        return $response;
    }

    public function conversation($category){
        $response = [
            "message" => [
                "text"  => "Enter your Location:",
                "quick_replies" => [
                    array(
                        "content_type"  => "text",
                        "title"         => "Red",
                        "payload"       => "@conversation"
                    )
                ]
            ]
        ];
        echo json_encode($response);
        return $response;
    } 

    public function myQueueCards(){
        return "My Queue Cards";
    }

    public function userGuide(){
        return "User Guide";
    }
}