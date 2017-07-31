<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueCard extends APIModel
{
  public function  queue_card_fields(){
    return $this->hasMany('App\QueueCardField');
  }
}
