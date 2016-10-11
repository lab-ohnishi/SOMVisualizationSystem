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
      <h1>第<?php echo $id ?>回相互評価</h1>
      <h4>相互評価結果参照</h4>
      <div id="otherResult" class="col-xs-12 col-md-12 col-lg-12">
        <?php
        for($i = 1; $i <= $eventNum; $i++){
        if($i != $eventID){
        echo '<a href="sompage.php?id='. $i .'" class="btn btn-success">第<strong>'. $i .'</strong>回の相互評価結果</a>';
        } else {
        echo '<a href="#" class="btn btn-default">第<strong>'. $i .'</strong>回の相互評価結果</a>';
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
              <p>・評価結果</p>
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
                echo("<option value='${i}'>${i}. 学生->".$locationData[$i][1]."</option>\n");
                }
                ?>
              </select>
            </div>
            <!--person selectbox-->
            <div id="person">
              <p>・評価者</p>
              <select id="fromBox" class="selectpicker" data-style="btn-primary" name="fromSelect" size="1" multiple>
                <?php
                echo("<option value=\"".$fromName."\">".$fromName."</option>\n");
                ?>
              </select>
              <p>・評価された人</p>
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
              <h4 id="action">評価項目</h4>
              <table>
                <tr>
                  <td class="feat">スライド</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">発表の流れ</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">発話</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">姿勢</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">指示棒</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">時間配分</td>
                  <td name="pow"></td>
                </tr>
                <tr>
                  <td class="feat">質疑応答</td>
                  <td name="pow"></td>
                </tr>
              </table>
              </li>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
              <h4 id="comment">コメント</h4>
              <table>
                <tr>
                  <th>良かった点</th>
                </tr>
                <tr>
                  <td name="pow"></td>
                </tr>
              </table>
              <table>
                <tr>
                  <th>良くなかった点</th>
                </tr>
                <tr>
                  <td name="pow"></td>
                </tr>
              </table>
              </li>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3">
              <h4 class="secret">隠しコマンド</h4>
              <table>
                <tr>
                  <td class="feat">周囲との距離</td>
                </tr>
                <tr>
                  <td class="feat">まっさら</td>
                </tr>
                <tr>
                  <td class="feat">真っ黒</td>
                </tr>
                <tr>
                  <td class="feat">平均</td>
                </tr>
              </table>
              </li>
            </div>
          </div><!--featuresPanel-->
          <div class="memo">
            <?php
            echo '<p><a class="btn btn-info" href="inputs.php?id='.$eventID.'">入力画面へ戻る</a></p>';
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
