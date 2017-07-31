<?php

namespace App\Http\Controllers;
use App\QueueCard as DBItem;
use Illuminate\Http\Request;

class QueueCardFieldController extends APIController
{
  function __construct(){
    $this->model = new DBItem();
  }
}
