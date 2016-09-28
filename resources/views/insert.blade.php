@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Insert View</div>

        <div class="panel-body">
          <form action="/events/<?php 
            $event_id = preg_replace('/[^0-9]/', '', $_SERVER["REQUEST_URI"]);
            echo $event_id;
            ?>/inputs/insert " method="post">
            <div class="row">
              <div class="col-sm-2">To_id</div>
              <div class="col-sm-10 form-inline">
                <select class="form-control input-sm" name="to_id">
                  @foreach($users as $user)
                  <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                  @endforeach
                </select>
              </div>
            </div>
            @for ($i =1; $i < 8; $i++)
            <div class="row">
              <div class="col-sm-2">Pers0<?php echo $i; ?></div>
              <div class="col-sm-10 form-inline">
                <select class="form-control input-sm" name="pers0<?php echo $i; ?>">
                  @for ($j =0; $j < 6 ; $j++)
                  @if($j==0)
                  <option value="">-----</option>
                  @else
                  <option value="<?php echo $j; ?>"><?php echo $j; ?></option>
                  @endif
                  @endfor
                </select>
              </div>
            </div>
            @endfor
            <input type="submit" value="SEND">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
