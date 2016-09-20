@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Inputs View</div>
        <div class="panel-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>perspectives_id</th>
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
              <tr>
                <th>
                  <?php {echo $perspective->id;} ?>
                </th>
                <th> <?php {echo $perspective->from_id;} ?></th>
                <th> <?php {echo $perspective->to_id;} ?></th>
                <th> <?php {echo $perspective->pers01;} ?></th>
                <th> <?php {echo $perspective->pers02;} ?></th>
                <th> <?php {echo $perspective->pers03;} ?></th>
                <th> <?php {echo $perspective->pers04;} ?></th>
                <th> <?php {echo $perspective->pers05;} ?></th>
                <th> <?php {echo $perspective->pers06;} ?></th>
                <th> <?php {echo $perspective->pers07;} ?></th>
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

