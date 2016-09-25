<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;
class PerspectivesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function res(Request $request, $id){

    $event_id = $id;
    $from_id = Auth::user()->id;
    $to_id = Input::get('to_id');
    $pers01 = Input::get('pers01');
    $pers02 = Input::get('pers02');
    $pers03 = Input::get('pers03');
    $pers04 = Input::get('pers04');
    $pers05 = Input::get('pers05');
    $pers06 = Input::get('pers06');
    $pers07 = Input::get('pers07');

    DB::table('perspectives')->insert([
        'event_id'=>$event_id,
        'from_id'=>$from_id,
        'to_id'=>$to_id,
        'pers01'=>$pers01,
        'pers02'=>$pers02,
        'pers03'=>$pers03,
        'pers04'=>$pers04,
        'pers05'=>$pers05,
        'pers06'=>$pers06,
        'pers07'=>$pers07
    ]);
  return redirect('home');
    
  }

  public function insert(Request $request, $id){
    $users = DB::table('users')->select('id', 'name')->get();
    $perspectives = DB::table('perspectives')->where('event_id', $id)->get();
    echo Auth::user()->name;
    return view('insert',['perspectives' => $perspectives ],['users' => $users]);
  }
}
