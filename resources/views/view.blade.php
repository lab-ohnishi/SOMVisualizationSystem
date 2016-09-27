@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          Inputs View 
        </div>

        <div class="panel-body">
          <div class="row">
            <div align="right">
              <a href="/events/<?php 
                $event_id = preg_replace('/[^0-9]/', '', $_SERVER["REQUEST_URI"]);
                echo $event_id;
                ?>/inputs/insert " class="btn btn-primary btn-lg active" role="button">INSERT</a>
            </div>
          </div>

          <div class="row">
            <div class="panel-body ">
              From <?php {echo $users[Auth::user()->id-1]->name;} ?>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="col-xs-1">To</th>
                    <th class="col-xs-1">Pers01</th>
                    <th class="col-xs-1">Pers02</th>
                    <th class="col-xs-1">Pers03</th>
                    <th class="col-xs-1">Pers04</th>
                    <th class="col-xs-1">Pers05</th>
                    <th class="col-xs-1">Pers06</th>
                    <th class="col-xs-1">Pers07</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $count = 1; ?>
                  @foreach ($users as $user)
                  @foreach ($perspectives as $perspective) 
                  @if($perspective->from_id == Auth::user()->id && $user->id == $perspective->to_id) 
                  <tr>
                    <td> <?php {echo $user->name;} ?></td>
                    <td> <?php {echo $perspective->pers01;} ?></td>
                    <td> <?php {echo $perspective->pers02;} ?></td>
                    <td> <?php {echo $perspective->pers03;} ?></td>
                    <td> <?php {echo $perspective->pers04;} ?></td>
                    <td> <?php {echo $perspective->pers05;} ?></td>
                    <td> <?php {echo $perspective->pers06;} ?></td>
                    <td> <?php {echo $perspective->pers07;} ?></td>
                  </tr>
                  <?php $count++;
                  break; ?>
                  @endif
                  @endforeach
                  @if($user->id == $count)
                  <tr>
                    <td> <?php {echo $user->name;} ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <?php $count++; ?>
                  @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

