$(function(){
    var xs = 25;
    var ys = 22.5;
    var dy = 30;
    var NOFeatures=12;
    var canvas = document.getElementById('somCanvas');
    var ctx = canvas.getContext('2d');
    var dataSelectBox = document.getElementById('dataSelectBox');
    var power = document.getElementsByName('pow');
    var feat = document.getElementsByName('feat');
    var selectedFeature = -1;
    var selectedData = null;//red
    var fromOrToData = null;//blue
    var fromOrToSwitch = null;//0:from , 1:to
    var toFlag;
    var fromFrag;
    var peerAssessmentData;

    var somColor = new Array();
    for(var i=0; i<15; i++){
    somColor[i] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
    }
    var somColorAve = new Array();
    for(var i=0; i<15; i++){
      somColorAve[i] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
    }

    var min;
    var max;
    var gap = max - min;
    var step = gap/4;

    for(y=0; y<15; y++){
      for(x=0; x<15; x++){
        for(selectedF=0; selectedF<7; selectedF++){
          somColor[y][x]+=map[y][x][selectedF];
        }
        somColorAve[y][x] = somColor[y][x]/selectedF;
      }
    }

    $.post('getFeatureValueAll.php',{
        'filename': filename
        },function(data){
        peerAssessmentData = JSON.parse(data);
        for(var i=0; i<peerAssessmentData.length; i++){
        if(i==0){
        min = peerAssessmentData[i].ave;
        max = peerAssessmentData[i].ave;
        } else if(peerAssessmentData[i].ave < min){
        min = peerAssessmentData[i].ave;
        } else if(max < peerAssessmentData[i].ave){
        max = peerAssessmentData[i].ave;
        }
        }
        gap = max - min;
        step = gap/4;
        draw();
        ctx.fillStyle = 'rgb(0, 0, 0)';
        ctx.font = "14px 'Arial'";
        plotString();
        });

    //押したら赤くなるクリックリスナ(som画面右)
    dataSelectBox.onchange = function(){
      selectedData = JSON.parse(dataSelectBox.value);
      ctx.fillStyle = "rgb(255, 255, 255)";
      ctx.fillRect(0,0,400,400);
      draw();
      ctx.fillStyle = 'rgb(0, 0, 0)';
      ctx.font = "14px 'Arial'";
      plotString();
      $.post('getFeatureValues.php',{
          'selected_data': JSON.parse(dataSelectBox.value),
          'filename': filename
          },
          function(data){
          var d = JSON.parse(data);
          for(var i=0; i<d.length-1; i++){
          power[i].innerText=":"+d[i];
          if(i >= d.length-3){
          power[i].textContent=d[i];
          }
          else{
          d[i]++;
          power[i].textContent=":"+d[i];
          }

          }
          showReport(d[d.length-1]);
          });
    }

    //なんとか力・性のクリックリスナ(som画面下部)
    $('.feat').click(function(){
        $('.feat:eq('+selectedFeature+')').css("background-color", "white");
            selectedFeature = $('.feat').index(this);
            $('.feat:eq('+selectedFeature+')').css("background-color", "#cccccc");
              for(y=0; y<15; y++){
              for(x=0; x<15; x++){
              somColor[y][x]=map[y][x][selectedFeature];
              }
              }
              //ctx.fillStyle = "rgb(255, 255, 255)";
              ctx.clearRect(0,0,400,400);
              draw();
              ctx.fillStyle = 'rgb(0, 0, 0)';
              ctx.font = "14px 'Arial'";
              plotString();
              });

            //toBoxクリックリスナ
            $('#toBox').change(function(){
              if($('#fromBox').val() != 0){
              $('#fromBox').val(0);
              }
              ctx.clearRect(0,0,400,400);
              draw();
              ctx.fillStyle = 'rgb(0, 0, 0)';
              ctx.font = "14px 'Arial'";
              if($('#toBox').val() != null){
              fromOrToData = $('#toBox').val();
              fromOrToSwitch = 1;
              }
              else{
              fromOrToData = null;
              fromOrToSwitch = null;
              }
              plotString();
              showReport();
              });              

            //fromBoxクリックリスナ
            $('#fromBox').change(function(){
                if($('#tobox').val() != 0){
                $('#toBox').val(0);
                }
                ctx.clearRect(0,0,400,400);
                draw();
                ctx.fillStyle = 'rgb(0, 0, 0)';
                ctx.font = "14px 'Arial'";
                if($('#fromBox').val() != null){
                fromOrToData = $('#fromBox').val();
                fromOrToSwitch = 0;
                }
                else{
                fromOrToData = null;
                fromOrToSwitch = null;
                }
                plotString();

                });

            function showReport(reportNum) {
              var str = reportNum;
              if(str == null){
                $( "#toBox option:selected" ).each(function() {
                    str = $( this ).text();
                    });
              }
              document.getElementById("report")
                .innerHTML="<object type=\"application/pdf\" data=\"slides/"+str+id+".pdf\" class=\"report\" style=\"width: 100%; height: 100%;\"></object>";
            }         



            //以下キャンパス描画
            //ハニカム構造描画
            function draw(){
              ctx.lineWidth=0.8;

              if(selectedFeature == -1 || selectedFeature == 10){// 評価全容による可視化
                for(var y=0; y<15; y++){ 
                  for(var x=0; x<15; x++){ //奇数行目の描画
                    setPath(ctx, 6+x*xs, 320-y*ys, dy);
                    if(somColorAve[y][x] < min+step){
                      ctx.fillStyle = "rgba(125, 245, 125, 0)";
                    }
                    else if(somColorAve[y][x] < min+2*step){
                      ctx.fillStyle = "rgba(125, 245, 125, "+1/3+")";
                    }
                    else if(somColorAve[y][x] < min+3*step){
                      ctx.fillStyle = "rgba(125, 245, 125, "+2/3+")";
                    }
                    else{
                      ctx.fillStyle = "rgba(125, 245, 125, 1)";
                    }
                    ctx.fill();
                    ctx.stroke();
                  }
                  y++;
                  if(y==15)break;
                  for(var x=0; x<15; x++){ //偶数行目の描画
                    setPath(ctx, 6+x*xs+xs/2, 320-y*ys, dy);
                    if(somColorAve[y][x] < min+step){
                      ctx.fillStyle = "rgba(125, 245, 125, 0)";
                    }
                    else if(somColorAve[y][x] < min+2*step){
                      ctx.fillStyle = "rgba(125, 245, 125, "+1/3+")";
                    }
                    else if(somColorAve[y][x] < min+3*step){
                      ctx.fillStyle = "rgba(125, 245, 125, "+2/3+")";
                    }
                    else{
                      ctx.fillStyle = "rgba(125, 245, 125, 1)";
                    }
                    ctx.fill();
                    ctx.stroke();
                  }
                }
              }
              // "評価項目"と"周囲との距離"
              else if(selectedFeature < 9){ 
                for(var y=0; y<15; y++){ 
                  for(var x=0; x<15; x++){ //奇数行目の描画
                    setPath(ctx, 6+x*xs, 320-y*ys, dy);
                    ctx.fillStyle = "rgba(125, 245, 125,"+(somColor[y][x]/3)+")";
                    ctx.fill();
                    ctx.stroke();
                  }
                  y++;
                  if(y==15)break;
                  for(var x=0; x<15; x++){ //偶数行目の描画
                    setPath(ctx, 6+x*xs+xs/2, 320-y*ys, dy);
                    ctx.fillStyle = "rgba(125, 245, 125,"+(somColor[y][x]/3)+")";
                    ctx.fill();
                    ctx.stroke();
                  }
                }
              }
              // まっさら
              else if(selectedFeature == 9){
                for(var y=0; y<15; y++){
                  for(var x=0; x<15; x++){ //奇数行目の描画
                    setPath(ctx, 6+x*xs, 320-y*ys, dy);
                    ctx.fillStyle = "rgba(0, 0, 0, 1)";
                    ctx.fill();
                    ctx.stroke();
                  }
                  y++;
                  if(y==15)break;
                  for(var x=0; x<15; x++){ //偶数行目の描画
                    setPath(ctx, 6+x*xs+xs/2, 320-y*ys, dy);
                    ctx.fillStyle = "rgba(0, 0, 0, 1)";
                    ctx.fill();
                    ctx.stroke();
                  }
                }
              }
            }
            //文字列描画
            function plotString(){
              ctx.fillStyle = 'rgb(0, 0, 0)';
              ctx.font = "14px 'Arial'";
              if(fromOrToData != null){
                for(var i=0; i<dataLocation.length; i++){
                  for(var j=0; j<fromOrToData.length; j++){
                    if(fromOrToData[j]==dataLocation[i][fromOrToSwitch]){
                      if(selectedData == i){
                        ctx.fillStyle = 'rgb(255, 0, 255)';
                        ctx.font = "20px 'Arial'";
                      }
                      else{
                        ctx.fillStyle = 'rgb(0, 0, 255)';
                        ctx.font = "18px 'Arial'";
                      }
                      plotStr(i);
                      ctx.fillStyle = 'rgb(0, 0, 0)';
                      ctx.font = "14px 'Arial'";
                      break;
                    }
                    if(j==fromOrToData.length-1){
                      if(selectedData == i){
                        ctx.fillStyle = 'rgb(255, 0, 0)';
                        ctx.font = "20px 'Arial'";
                        plotStr(i);
                        ctx.fillStyle = 'rgb(0, 0, 0)';
                        ctx.font = "14px 'Arial'";
                      }
                      else{
                        plotStr(i);
                      }
                    }
                  }
                }
              }
              else{
                for(var i=0; i<dataLocation.length; i++){
                  if(selectedData == i){
                    ctx.fillStyle = 'rgb(255, 0, 0)';
                    ctx.font = "20px 'Arial'";
                    plotStr(i);
                    ctx.fillStyle = 'rgb(0, 0, 0)';
                    ctx.font = "14px 'Arial'";
                  }
                  else plotStr(i);
                }
              }
            }

            function plotStr(i){
              ctx.fillText(i,((dataLocation[i][3]%2==0)?(12+dataLocation[i][2]*xs):(12+dataLocation[i][2]*xs+xs/2)) ,310+dy-dataLocation[i][3]*ys,17);
            }

});

function setPath(ctx, xs, ys, dy){
  var tx1 = xs+Math.floor(0.433*dy);
  var tx2 = xs+Math.floor(0.866*dy);
  var ty1 = ys+Math.floor(0.25*dy);
  var ty2 = ys+Math.floor(0.75*dy);

  ctx.beginPath();
  ctx.moveTo(xs,ty1);
  ctx.lineTo(tx1,ys);
  ctx.lineTo(tx2,ty1);
  ctx.lineTo(tx2,ty2);
  ctx.lineTo(tx1,ys+dy);
  ctx.lineTo(xs,ty2);
  ctx.closePath();
}

