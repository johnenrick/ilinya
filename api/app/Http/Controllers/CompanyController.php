<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class CompanyController extends APIController
{
  function __construct(){
    $this->model = new Company();
  }
}
