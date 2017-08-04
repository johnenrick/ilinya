<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueCard extends APIModel
{
  public function  queue_card_fields(){
    return $this->hasMany('App\QueueCardField')->with('queue_form_field');
  }
  public function  facebook_user(){
    return $this->hasMany('App\FacebookUser');
  }
}
