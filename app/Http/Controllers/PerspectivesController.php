<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class PerspectivesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  
  public function index(){
  $perspectives = DB::table('perspectives')->get();
  return view('view',['perspectives' => $perspectives ] );
  }


}

