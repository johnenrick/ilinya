<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueCardField extends APIModel
{
    protected $fillable = ['queue_card_id', 'queue_form_field_id', 'value']
}
