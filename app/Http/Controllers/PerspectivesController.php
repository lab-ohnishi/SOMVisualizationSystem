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

  public function view(Request $request, $id){
    $perspectives = DB::table('perspectives')->get();
    $users = DB::table('users')->get();
   // echo $id;
    return view('view',['perspectives' => $perspectives ],['users' => $users]);
  }


}

