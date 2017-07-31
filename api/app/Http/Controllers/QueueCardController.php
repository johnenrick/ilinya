<?php

namespace App\Http\Controllers;
use App\QueueCard as DBItem;
use Illuminate\Http\Request;

class QueueCardController extends APIController
{
  function __construct(){
    $this->model = new DBItem();
    $this->editableForeignTable = array(
      'queue_card_fields'
    );
    $this->defaultValue = array(
      'company_id' => $this->getUserCompanyID()
    );
  }
}
