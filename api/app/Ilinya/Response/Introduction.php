<?php


namespace App\Ilinya\Response;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

/*
    @Providers
*/
use App\Ilinya\Http\Curl;
use App\Ilinya\Webhook\Messaging;
use App\Ilinya\User;

/*
    @Template
*/
use App\Ilinya\Templates\Facebook\QuickReplyTemplate;
use App\Ilinya\Templates\Facebook\ButtonTemplate;
use App\Ilinya\Templates\Facebook\GenericTemplate;
use App\Ilinya\Templates\Facebook\LocationTemplate;
use App\Ilinya\Templates\Facebook\ListTemplate;

/*
    @Elements
*/

use App\Ilinya\Templates\Facebook\ButtonElement;
use App\Ilinya\Templates\Facebook\GenericElement;
use App\Ilinya\Templates\Facebook\QuickReplyElement;


/*
    @Message
*/

use App\Ilinya\Message\Attachments;

/*
    @Controller
*/
use App\Http\Controllers\BusinessTypeController;
//use App\BusinessType;

/*
    @Database
*/
use App\Ilinya\Database\DBManager as DB;

class Introduction{
    public  $ERROR            = "I'm sorry but I can't do what you want me to do :'(";
    private $user;
    private $messaging;
    private $curl;

    protected $db_bTypes      = "business_types";



    public function __construct(Messaging $messaging){
        $this->messaging = $messaging;
        $this->curl = new Curl();
    }   

    public function user(){
        $user = $this->curl->getUser($this->messaging->getSenderId());
        $this->user = new User($this->messaging->getSenderId(), $user['first_name'], $user['last_name']);
    }

    public function start(){
        $this->user();

        return "Hi ".$this->user->getFirstName()."! My Name is Ilinya, I can help to get your reservations or tickets easily. Just follow my instructions and you will be good to go!";
    }
    
    public function categories(){
        $request = new Request();
        $request['sort'] = ["category" => "asc"];
        $result = app('App\Http\Controllers\BusinessTypeController')->retrieve($request);
        $result = json_decode($result->getContent(), true);
        $categories = $result['data'];
        //$categories = DB::retrieve($this->db_bTypes,null, ['category', 'asc']);
        $imgUrl = "http://www.gocentralph.com/gcssc/wp-content/uploads/2017/04/Services.png";
        $subtitle = "Get tickets or make reservations on category below:";
        $buttons = [];
        $elements = [];

        if($categories){
            $prev = $categories[0]['category'];
            $i = 0;
            foreach ($categories as $category) {
                $buttons[] = ButtonElement::title($category['sub_category'])
                    ->type('postback')
                    ->payload(strtolower($category['sub_category']).'@categoryselected')
                    ->toArray();
                if($i < sizeof($categories) - 1){
                    if($prev != $categories[$i + 1]['category']){
                        $title = $category['category'];
                        $elements[] = GenericElement::title($title)
                            ->imageUrl($imgUrl)
                            ->subtitle($subtitle)
                            ->buttons($buttons)
                            ->toArray();
                        $prev = $category['category'];
                        $buttons = null;
                    }
                }
                else{
                    $title = $category['category'];
                    $elements[] = GenericElement::title($title)
                        ->imageUrl($imgUrl)
                        ->subtitle($subtitle)
                        ->buttons($buttons)
                        ->toArray();
                }
                
                $i++;
            }
        }


        $response =  GenericTemplate::toArray($elements);
        return $response;
    }

    public function myQueueCards(){
        return "My Queue Cards";
    }

    public function userGuide(){
        return "User Guide";
    }

    public function priorityError(){
        $quickReplies[] = QuickReplyElement::title('Yes')->contentType('text')->payload('priority@yes');
        $quickReplies[] = QuickReplyElement::title('No')->contentType('text')->payload('priority@no');
        return QuickReplyTemplate::toArray('Are you sure you want cancel your current conversation?', $quickReplies);
    }
   
}