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
  public function som(Request $request ,$id){
    require_once("SOMBatchLearning.php");
    return view('som');
  }
}
