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
    $users = DB::table('users')->get();
    $perspectives = DB::table('perspectives')->where('event_id', $id)->get();
    return view('view',['perspectives' => $perspectives ],['users' => $users]);
  }


}

