@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Dashboard</div>

        <div class="panel-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>part</th>
                <th>date</th>
                <th>insert</th>
                <th>view</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($events as $event) 
                <tr>
                <th> <?php {echo $event->id;} ?></th>
                <th> <?php {echo $event->date;} ?></th>
                <th> <a href="/events/<?php {echo $event->id;} ?>/inputs" class="btn btn-primary btn-lg active" role="button">INSERT</a> </th>
                <th> <a href="/events/<?php {echo $event->id;} ?>/som" class="btn btn-primary btn-lg active" role="button">SOM</a> </th>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
