@extends('layouts.SOMApp')

@section('content')
<?php
session_start();


$id = preg_replace('/[^0-9]/', '', $_SERVER["REQUEST_URI"]);
$eventID = $id;

if($id == 1){
$filename = "data0.txt";
}else{
$filename = "data".$id.".txt";
}

srand(1);
if(isset($_POST['toName']) && $_POST['toName']!=""){
$targetName=$_POST['toName'];
$a = new SOMBatchLearning($filename,$targetName,15,15,7,3,1000);
} else {
$a = new SOMBatchLearning($filename,null,15,15,7,3,1000);
}
$locationData = $a->getLocation();
$mapData = $a->refVector;
$people = getPeople();
$fromNameNumber = getMyNumber($fromName);
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>SOM Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="som_style_2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css" />
    <script src="jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/som.js"></script>
    <script>
      <?php
      echo("var dataLocation=".json_encode($locationData).";\n");
      echo("var map=".json_encode($mapData).";\n");
      echo("var filename=".json_encode($filename).";\n");
      echo("var id=".$id.";\n");
      ?>
    </script>
  </head>
  <body>

    <div class="container">
      <h1>$BBh(B<?php echo $id ?>$B2sAj8_I>2A(B</h1>
      <h4>$BAj8_I>2A7k2L;2>H(B</h4>
      <div id="otherResult" class="col-xs-12 col-md-12 col-lg-12">
        <?php
        for($i = 1; $i <= $eventNum; $i++){
        if($i != $eventID){
        echo '<a href="sompage.php?id='. $i .'" class="btn btn-success">$BBh(B<strong>'. $i .'</strong>$B2s$NAj8_I>2A7k2L(B</a>';
        } else {
        echo '<a href="#" class="btn btn-default">$BBh(B<strong>'. $i .'</strong>$B2s$NAj8_I>2A7k2L(B</a>';
        }
        }
        ?>
      </div> 
      <div id="som" class="col-sm-12 col-md-8 col-lg-7">
        <div class="row">
          <div id="cont1" class="col-sm-7 col-md-8 col-lg-8">
            <!-- som canvas -->
            <canvas id="somCanvas" width="400" height="360"></canvas>
          </div>
          <div id="cont2" class="col-sm-5 col-md-4 col-lg-4">
            <!--data selectbox-->
            <div id="data">
              <p>$B!&I>2A7k2L(B</p>
              <select id="dataSelectBox" class="selectpicker" data-style="btn-danger" name="dataSelect">
                <?php
                for($i=0; $i<count($locationData); $i++){
                if($fromName == $locationData[$i][0] && $fromName == $locationData[$i][1])
                echo("<option value='${i}' selected>${i}.".$locationData[$i][0]." Self</option>\n");
                else if($locationData[$i][0] === "ohnishi")
                echo("<option value='${i}'>${i}.".$locationData[$i][0]."->".$locationData[$i][1]."</option>\n");
                else if($locationData[$i][0] == $fromName)
                echo("<option value='${i}'>${i}.".$locationData[$i][0]."->".$locationData[$i][1]."</option>\n");
                else
                echo("<option value='${i}'>${i}. $B3X@8(B->".$locationData[$i][1]."</option>\n");
                }
                ?>
              </select>
            </div>
            <!--person selectbox-->
            <div id="person">
              <p>$B!&I>2A<T(B</p>
              <select id="fromBox" class="selectpicker" data-style="btn-primary" name="fromSelect" size="1" multiple>
                <?php
                echo("<option value=\"".$fromName."\">".$fromName."</option>\n");
                ?>
              </select>
              <p>$B!&I>2A$5$l$??M(B</p>
              <select id="toBox" class="selectpicker" data-style="btn-primary" name="toSelect" size="1" multiple>
                <?php
                $people = getPeople();
                for($i=0; $i<count($people); $i++){
                if($people[$i] != "ohnishi"){
                if($fromNameNumber == $people[$i])
                echo("<option value=\"".$people[$i]."\" selected>".$people[$i]."</option>\n");
                else
                echo("<option value=\"".$people[$i]."\">".$people[$i]."</option>\n");
                }
                }
                ?>
              </select>
            </div>
          </div><!--cont2-->
        </div><!--cont1-->
        <div class="row">
          <div id="featuresPanel" class="col-sm-12">
            <div class="col-sm-3 col-md-3 col-lg-3">
              <h4 id="action">$BI>2A9`L\(B</h4>
              <table>
                <tr>
                  <td class="feat">$B%9%i%$%I(B</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">$BH/I=$NN.$l(B</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">$BH/OC(B</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">$B;Q@*(B</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">$B;X<(K@(B</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">$B;~4VG[J,(B</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">$B<A5?1~Ez(B</td>
                  <td name="pow"></td>
                </tr>
              </table>
              </li>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
              <h4 id="comment">$B%3%a%s%H(B</h4>
              <table>
                <tr>
                  <th>$BNI$+$C$?E@(B</th>
                </tr>
                <tr>
                  <td name="pow"></td>
                </tr>
              </table>
              <table>
                <tr>
                  <th>$BNI$/$J$+$C$?E@(B</th>
                </tr>
                <tr>
                  <td name="pow"></td>
                </tr>
              </table>
              </li>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3">
              <h4 class="secret">$B1#$7%3%^%s%I(B</h4>
              <table>
                <tr>
                  <td class="feat">$B<~0O$H$N5wN%(B</td>
                </tr>
                <tr>
                  <td class="feat">$B$^$C$5$i(B</td>
                </tr>
                <tr>
                  <td class="feat">$B??$C9u(B</td>
                </tr>
                <tr>
                  <td class="feat">$BJ?6Q(B</td>
                </tr>
              </table>
              </li>
            </div>
          </div><!--featuresPanel-->
          <div class="memo">
            <?php
            echo '<p><a class="btn btn-info" href="inputs.php?id='.$eventID.'">$BF~NO2hLL$XLa$k(B</a></p>';
            ?>
          </div><!-- memo -->
        </div>
      </div><!-- som -->
      <div id="report" class="col-sm-4 col-md-4 col-lg-5">
        <?php
        $myReportNum = getMyNumber($fromName);
        echo("<object type=\"application/pdf\" data=\"slides/".$fromName."".$id.".pdf\" class=\"report\" style=\"width: 100%; height: 100%\"></object>");
        ?>
      </div><!-- report -->
    </div><!--container-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
  </body>
</html>
Contact GitHub API Training Shop Blog About


<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">SOM View</div>

        <div class="panel-body">
          You are logged in!
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
