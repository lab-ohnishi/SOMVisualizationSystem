@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          Inputs View 
        </div>
        <div class="panel-body col-md-10">
          <div class="col-md-8" align="right">
          <a href="/events/<?php 
          $event_id = preg_replace('/[^0-9]/', '', $_SERVER["REQUEST_URI"]);
          echo $event_id;
          ?>/inputs/insert " class="btn btn-primary btn-lg active" role="button">INSERT</a>
          </div>
          To <?php {echo $users[Auth::user()->id]->name;} ?>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>From</th>
                <th>To</th>
                <th>Pers01</th>
                <th>Pers02</th>
                <th>Pers03</th>
                <th>Pers04</th>
                <th>Pers05</th>
                <th>Pers06</th>
                <th>Pers07</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($perspectives as $perspective) 
              @if($perspective->to_id === Auth::user()->id) 
              <tr>
                <th> <?php {echo $users[$perspective->from_id]->name;} ?></th>
                <th> <?php {echo $users[$perspective->to_id]->name;} ?></th>
                <th> <?php {echo $perspective->pers01;} ?></th>
                <th> <?php {echo $perspective->pers02;} ?></th>
                <th> <?php {echo $perspective->pers03;} ?></th>
                <th> <?php {echo $perspective->pers04;} ?></th>
                <th> <?php {echo $perspective->pers05;} ?></th>
                <th> <?php {echo $perspective->pers06;} ?></th>
                <th> <?php {echo $perspective->pers07;} ?></th>
              </tr>
              @endif
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="panel-body col-md-10">
          From <?php {echo $users[Auth::user()->id]->name;} ?>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>From</th>
                <th>To</th>
                <th>Pers01</th>
                <th>Pers02</th>
                <th>Pers03</th>
                <th>Pers04</th>
                <th>Pers05</th>
                <th>Pers06</th>
                <th>Pers07</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($perspectives as $perspective) 
              @if($perspective->from_id === Auth::user()->id) 
              <tr>
                <th> <?php {echo $users[$perspective->from_id]->name;} ?></th>
                <th> <?php {echo $users[$perspective->to_id]->name;} ?></th>
                <th> <?php {echo $perspective->pers01;} ?></th>
                <th> <?php {echo $perspective->pers02;} ?></th>
                <th> <?php {echo $perspective->pers03;} ?></th>
                <th> <?php {echo $perspective->pers04;} ?></th>
                <th> <?php {echo $perspective->pers05;} ?></th>
                <th> <?php {echo $perspective->pers06;} ?></th>
                <th> <?php {echo $perspective->pers07;} ?></th>
              </tr>
              @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

