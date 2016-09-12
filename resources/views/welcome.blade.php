@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">Welcome SOM System</div>

        <div class="panel-body">
        <div class="col-md-4">
        <a href="/login" class="btn btn-primary btn-lg active" role="button">LOGIN</a>
        <a href="/register" class="btn btn-default btn-lg active" role="button">SIGN UP</a>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
