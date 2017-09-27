<?php

namespace App\Ilinya\API;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Ilinya\API\Controller;

class QueueCard{
    public static function retrieve($data, $column = null){
      $controller = 'App\Http\Controllers\QueueCardController';
      $request = new Request();
       $condition[] = [
          "column"  => "company_id",
          "clause"  => "=",
          "value"   => $data['company_id']
        ];
        $condition[] = [
          "column"  => "queue_form_id",
          "clause"  => "=",
          "value"   => $data['queue_form_id']
        ];
        $condition[] = [
          "column"  => "facebook_user_id",
          "clause"  => "=",
          "value"   => $data['facebook_user_id']
        ];
      $request['condition'] = $condition;
      $qc = Controller::retrieve($request, $controller);
      if($column)
        return (sizeof($qc) > 0) ? $qc[0][$column] : null;
        return $qc;
    } 

    public static function retrieveById($id, $column = null){
      $controller = 'App\Http\Controllers\QueueCardController';
      $request = new Request();
       $condition[] = [
          "column"  => "id",
          "clause"  => "=",
          "value"   => $id
        ];

      $request['condition'] = $condition;
      $qc = Controller::retrieve($request, $controller);
      if($column)
        return (sizeof($qc) > 0) ? $qc[0][$column] : null;
        return $qc;
    }

}