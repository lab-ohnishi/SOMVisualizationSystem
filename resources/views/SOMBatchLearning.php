<?php
//inputdata[][];
//refVector[][][];
//uMatrix[][];
//times;
//maxLearn;
//x-size;
//y-size;
//$a = new SOMTan('data0.txt',null,15,15,12,3,1000);
class SOMBatchLearning{
  public $refVector;
  public $uMatrix;
  public $inputData;
  public $x_size;
  public $y_size;
  public $maxLearn;
  public $maxValue;
  public $numberOfFeatures;
  public $times;
  function __construct($dataFile,$targetName=null,$x_size,$y_size,$numberOfFeatures,$maxValue,$maxLearn){
    $this->x_size = $x_size;
    $this->y_size = $y_size;
    $this->numberOfFeatures = $numberOfFeatures;
    $this->maxValue = $maxValue;
    //バッチ学習のため学習回数は少なめ、ここでは10に定義しておく
    $this->maxLearn = 10;//$maxLearn;
    //$this->refVectorInit();
    $this->inputData = $this->loadData($dataFile,$targetName);
    $tmp = count($this->inputData);//tmp=12に設定。後の計算で用いるため。
    $this->times = 0;
    //主成分分析で各ノードを初期化
    //$this->pcAnalysis2($this->inputData,$tmp);
    $this->refVectorInitPCA($this->inputData,$tmp);
    //バッチ学習型SOMで計算を行う
    while($this->times < $this->maxLearn){
      $this->learn($this->inputData,$tmp);
      $this->times++;
    }
    $this->calculateUMatrix();
    for($y=0; $y<$this->y_size; $y++){
      for($x=0; $x<$this->x_size; $x++){
        array_push($this->refVector[$y][$x],$this->uMatrix[$y][$x]);
        array_push($this->refVector[$y][$x],0);
      }
    }
  }
  function loadData($dataFile,$targetName = null){
    $fp = fopen($dataFile,'r');
    $i=0;
    $inData = null;
    fgetcsv($fp);
    if($targetName == null){
      while($data = fgetcsv($fp)){
        $inData[$i] = $data;
        $i++;
      }
    }
    else{
      while($data = fgetcsv($fp)){
        if($data[1] == $targetName){
          $inData[$i] = $data;
          $i++;
        }
      }
    }
    fclose($fp);
    return $inData;
  }
  function refVectorInit(){
    for($y=0; $y<$this->y_size; $y++){
      for($x=0; $x<$this->x_size; $x++){
        for($k=0; $k<$this->numberOfFeatures; $k++){
          $this->refVector[$y][$x][$k] = rand(0,$this->maxValue);
        }
      }
    }
  }
  function getClose($data){
    $min_distance = INF;
    $distance = 0;
    $min_x = -1;
    $min_y = -1;
    for($y=0; $y<$this->y_size; $y++){
      for($x=0; $x<$this->x_size; $x++){
        $distance = 0;
        for($i=0; $i<$this->numberOfFeatures; $i++){
          $distance += ($this->refVector[$y][$x][$i]-$data[$i+2])*($this->refVector[$y][$x][$i]-$data[$i+2]);
        }
        if($min_distance > $distance){
          $min_distance = $distance;
          $min_x = $x;
          $min_y = $y;
        }
      }
    }
    return array($min_x, $min_y);
  }
  public function getLocation(){
    $location=null;
    for($i=0; $i<count($this->inputData); $i++){
      $loc = $this->getClose($this->inputData[$i]);
      $location[$i] = array($this->inputData[$i][0],$this->inputData[$i][1],$loc[0],$loc[1]);
    }
    return $location;
  }
  function calculateUMatrix(){
    $d = null;
    $sum = null;
    $max=0.01;
    for($y=0; $y<$this->y_size; $y++){
      if($y%2==0){
        for($x=0; $x<$this->x_size; $x++){
          $d[0]=$d[1]=$d[2]=$d[3]=$d[4]=$d[5]=0;
          $sum=0;
          for($i=0; $i<$this->numberOfFeatures; $i++){
            $d[0] += (($x==0)? 0:pow($this->refVector[$y][$x-1][$i] - $this->refVector[$y][$x][$i],2));
            $d[1] += (($x==($this->x_size-1))? 0:pow($this->refVector[$y][$x+1][$i] - $this->refVector[$y][$x][$i],2));
            $d[2] += (($y==0 || $x==0)? 0:pow($this->refVector[$y-1][$x-1][$i] - $this->refVector[$y][$x][$i],2));
            $d[3] += (($y==0)? 0:pow($this->refVector[$y-1][$x][$i] - $this->refVector[$y][$x][$i],2));
            $d[4] += (($x==0 || $y==($this->y_size-1))? 0:pow($this->refVector[$y+1][$x-1][$i] - $this->refVector[$y][$x][$i],2));
            $d[5] += (($y==($this->y_size-1))? 0:pow($this->refVector[$y+1][$x][$i] - $this->refVector[$y][$x][$i],2));
          }
          for($i=0; $i<6; $i++){
            $sum += sqrt($d[$i]);
          }
          $this->uMatrix[$y][$x] = $sum/6;
          if($max<$this->uMatrix[$y][$x]) $max = $this->uMatrix[$y][$x];
        }
      }
      else{
        for($x=0; $x<$this->x_size; $x++){
          $d[0]=$d[1]=$d[2]=$d[3]=$d[4]=$d[5]=0;
          $sum=0;
          for($i=0; $i<$this->numberOfFeatures; $i++){
            $d[0] += (($x==0)? 0:pow($this->refVector[$y][$x-1][$i] - $this->refVector[$y][$x][$i],2));
            $d[1] += (($x==($this->x_size-1))? 0:pow($this->refVector[$y][$x+1][$i] - $this->refVector[$y][$x][$i],2));
            $d[2] += (($y==0)? 0:pow($this->refVector[$y-1][$x][$i] - $this->refVector[$y][$x][$i],2));
            $d[3] += (($x==($this->x_size-1)||$y==0)? 0:pow($this->refVector[$y-1][$x+1][$i] - $this->refVector[$y][$x][$i],2));
            $d[4] += (($y==($this->y_size-1))? 0:pow($this->refVector[$y+1][$x][$i] - $this->refVector[$y][$x][$i],2));
            $d[5] += (($x==($this->x_size-1)||$y==($this->y_size-1))? 0:pow($this->refVector[$y+1][$x+1][$i] - $this->refVector[$y][$x][$i],2));
          }
          for($i=0; $i<6; $i++){
            $sum += sqrt($d[$i]);
          }
          $this->uMatrix[$y][$x] = $sum/6;
          if($max<$this->uMatrix[$y][$x]) $max = $this->uMatrix[$y][$x];
        }
      }
    }
    for($y=0; $y<$this->y_size; $y++){
      for($x=0; $x<$this->x_size; $x++){
        $this->uMatrix[$y][$x] = $this->uMatrix[$y][$x]/$max*$this->maxValue;
      }
    }
  }
  //バッチ学習型SOM
  function learn($data,$tmp){
    //全入力データに対し一番近い参照ベクトルを求めていく。
    for($c=0;$c<$tmp;$c++){
      $close[$c] = $this->getClose($data[$c]);
    }
    $Sxy;//近傍領域の入力データの集合
    $Nxy;//近傍領域内の入力データの数
    $alpha=0.9*(1-$this->times/$this->maxLearn);
    if($alpha<0.01)$alpha=0.01;
    //$beta=($this->x_size/4)-$this->times;
    $beta=($this->x_size/4)*(1-($this->times/$this->maxLearn));
    if($beta<1)$beta=1;
    //xyループ
    for($y=0; $y<$this->y_size; $y++){
      for($x=0; $x<$this->x_size; $x++){
        $Nxy=0;
        //入力データの数分ループ
        for($i=0;$i<$tmp;$i++){
          //y座標が近傍領域か
          if($y-$beta<=$close[$i][1] && $close[$i][1]<=$y+$beta){
            //参照yと入力yの差が偶数距離の場合
            if(($y-$close[$i][1])%2==0){
              $dx=abs($y-$close[$i][1])/2;
              if($x-$beta+$dx<=$close[$i][0] && $close[$i][0]<=$x+$beta-$dx){
                $Sxy[$Nxy]=$data[$i];
                $Nxy++;
              }
            }
            //参照yと入力yが奇数距離の場合
            else{
              $dx=(abs($y-$close[$i][1])+1)/2;
              if($y%2==1){
                if($x-$beta+$dx-1<=$close[$i][0] && $close[$i][0]<=$x+$beta-$dx){
                  $Sxy[$Nxy]=$data[$i];
                  $Nxy++;
                }
              }
              else{
                if($x-$beta+$dx<=$close[$i][0] && $close[$i][0]<=$x+$beta-$dx+1){
                  $Sxy[$Nxy]=$data[$i];
                  $Nxy++;
                }
              }
            }
          }
        }
        //参照ベクトル更新
        for($j=0; $j<$this->numberOfFeatures; $j++){
          if($Nxy>0){
            $dataSUM=0;
            for($i=0;$i<$Nxy;$i++){
              $dataSUM+=$Sxy[$i][$j+2];
            }
            $old=$this->refVector[$y][$x][$j];
            $this->refVector[$y][$x][$j]=$old+$alpha*($dataSUM/$Nxy-$old);
            //$this->refVector[$y][$x][$j]+=$dataSUM/$Nxy;
          }
        }
      }
    }
  }
  //主成分分析にて参照ノードを初期化する。
  function refVectorInitPCA($data,$tmp){
    //もし要素数が0ならランダム初期化を行い終了
    if($tmp==0){
      $this->refVectorInit();
      return;
    }
    //第一主成分、第二主成分を求める
    //パワー法
    $L=$this->powerPCA($data,$tmp);
    //ヤコビ法
    //$L=$this->yakobiPCA($data,$tmp);
    //固有値が低すぎるときはランダム初期化を行い終了(判断基準は第二成分が0.5以下の時)
    if($L[1][$this->numberOfFeatures]<0.5){
      $this->refVectorInit();
      return;
    }
    //入力データの平均
    for($j=0;$j<$this->numberOfFeatures;$j++){
      $Xav[$j]=$this->aveInputData($data,$tmp,$j);
    }
    //各種成分の平均を求める
    for($n=0;$n<2;$n++){
      $sum=0;
      for($j=0;$j<$this->numberOfFeatures;$j++){
        $sum+=$L[$n][$j];
      }
      $Lav[$n]=$sum/$this->numberOfFeatures;
    }
    //各種成分の分散を求める
    for($n=0;$n<2;$n++){
      $Lvar[$n]=0;
      for($j=0;$j<$this->numberOfFeatures;$j++){
        $Lvar[$n]+=pow($L[$n][$j]-$Lav[$n],2);
      }
      $Lvar[$n]/=$this->numberOfFeatures;
    }
    $b1=$L[0];//第一主成分ベクトル
    $b2=$L[1];//第二主成分ベクトル
    $s1=sqrt($Lvar[0]);//第一主成分ベクトルの標準偏差
    $s2=sqrt($Lvar[1]);//第二主成分ベクトルの標準偏差
    $Ir=$this->x_size;
    $Jr=$this->y_size;
    $x_rate=$this->x_size/(5*$s1);//x座標の大きさ合わせ
    $y_rate=$this->y_size/(5*$s2);//y座標の大きさ合わせ
    //主成分ベクトルに基づき参照ノードを初期化していく
    for($y=0; $y<$this->y_size; $y++){
      for($x=0; $x<$this->x_size; $x++){
        for($j=0; $j<$this->numberOfFeatures; $j++){
          $this->refVector[$y][$x][$j] = $Xav[$j]+(5*$x_rate*$s1*$b1[$j]*(($x-$Ir/2)/$Ir))+(5*$y_rate*$s2*$b2[$j]*(($y-$Jr/2)/$Jr));
        }
      }
    }
  }
  //入力データの各変数の平均を求める。
  function aveInputData($data,$tmp,$j){
    $sum=0;
    for($i=0;$i<$tmp;$i++){
      $sum+=$data[$i][$j+2];
    }
    return $sum/$tmp;
  }
  //ヤコビ法を用いた主成分分析
  /* 主成分分析で用いる変数
   * Xav[j]:入力データの平均。jは変数の次元(j=1~12)。
   * V[i][j]:入力データに対する分散共分散行列。i*jの行列(12*12)。i,jは共に変数の次元(i,j=1~12)。
   * L[n][i+1]:主成分ベクトル(固有ベクトル)。第一主成分ベクトルと第二主成分ベクトルを表している。
   *        nは主成分の番号(n=1,2)。iは変数の次元(i=1~12)。L[n][12]にはn番目の主成分の固有値を格納してある。
   * ヤコビ法で用いる変数(固有ベクトルを計算するための計算)
   * A[i][j]:ヤコビ法にて求める対象となるベクトル。i*jの行列(12*12)。i,jは共に変数の次元(i,j=1~12)。
   * U[i][j]:固有ベクトル。i*jの行列(12*12)。i*jは変数の次元(i,j=1~12)。
   * ramuda[j]:固有値。各主成分の固有値を格納してある。jは変数の次元(j=1~12)。
   */
  function yakobiPCA($data,$tmp){
    //各変数の平均Xavを求める
    for($j=0;$j<$this->numberOfFeatures; $j++){
      $Xav[$j]=$this->aveInputData($data,$tmp,$j);
    }
    //分散・共分散行列Vを求める
    //初期化
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        $V[$i][$j]=0;
      }
    }
    //計算
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        for($n=0;$n<$tmp;$n++){
          $V[$i][$j]+=($data[$n][$i+2]-$Xav[$i])*($data[$n][$j+2]-$Xav[$j]);
        }
        $V[$i][$j]/=($tmp);
      }
    }
    //Vが0行列なら終了
    $check0=0;
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        if($V[$i][$j]!=0)$check0=1;
      }
    }
    if($check0==0){
      $L[0][$this->numberOfFeatures]=0;
      $L[1][$this->numberOfFeatures]=0;
      return $L;
    }
    //ヤコビ法を用いて主成分ベクトルLを求める
    //求めるベクトルAを分散共分散行列Vとする。
    $A=$V;
    //固有ベクトルを初期化
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        if($i==$j)$U[$i][$j]=1;
        else $U[$i][$j]=0;
      }
    }
    //規定値内のループで、Aの非対角成分を0にしていき、固有値と固有ベクトルを求めていく
    for($n=0;$n<300;$n++){
      //Aの非対角成分の中から最大にものを求める。
      $max=0;
      for($i=0;$i<$this->numberOfFeatures;$i++){
        for($j=0;$j<$this->numberOfFeatures;$j++){
          if($i!=$j){
            if(abs($A[$i][$j])>$max){
              $max=abs($A[$i][$j]);
              $p=$i;
              $q=$j;
            }
          }
        }
      }
      //各パラメータの設定
      $app=$A[$p][$p];
      $apq=$A[$p][$q];
      $aqq=$A[$q][$q];
      $alpha = ($app - $aqq)/2;
      $beta = -$apq;
      //0divなら終了
      if($alpha*$alpha + $beta*$beta ==0){
        $L[0][$this->numberOfFeatures]=0;
        $L[1][$this->numberOfFeatures]=0;
        return $L;
      }
      $gamma = abs($alpha)/sqrt($alpha*$alpha + $beta*$beta);
      $s = sqrt((1 - $gamma)/2);
      $c = sqrt((1 + $gamma)/2);
      if($alpha*$beta < 0) $s = -$s;
      //ベクトルAの更新
      for($i=0; $i<$this->numberOfFeatures; $i++){
        $temp = $c*$A[$p][$i] - $s*$A[$q][$i];
        $A[$q][$i] = $s*$A[$p][$i] + $c*$A[$q][$i];
        $A[$p][$i] = $temp;
      }
      for($i=0; $i<$this->numberOfFeatures; $i++){
        $A[$i][$p] = $A[$p][$i];
        $A[$i][$q] = $A[$q][$i];
      }
      $A[$p][$p] = $c*$c*$app + $s*$s*$aqq - 2*$s*$c*$apq;
      $A[$p][$q] = $s*$c*($app-$aqq) + ($c*$c - $s*$s)*$apq;
      $A[$q][$p] = $s*$c*($app-$aqq) + ($c*$c - $s*$s)*$apq;
      $A[$q][$q] = $s*$s*$app + $c*$c*$aqq + 2*$s*$c*$apq;
      //固有ベクトルUの更新
      for($i=0; $i<$this->numberOfFeatures; $i++){
        $temp = $c*$U[$i][$p] - $s*$U[$i][$q];
        $U[$i][$q] = $s*$U[$i][$p] + $c*$U[$i][$q];
        $U[$i][$p] = $temp;
      }
    }
    //固有値ramuda
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        if($i==$j)$ramuda[$i]=$A[$i][$j];
      }
    }
    //第一主成分と第二主成分を見つける
    $max1=0;$max2=0;
    $pc1=1;$pc2=2;
    for($j=0;$j<$this->numberOfFeatures; $j++){
      if($ramuda[$j]>$max1){
        $max1=$ramuda[$j];
        $max2=$max1;
        $pc2=$pc1;
        $pc1=$j;
      }
      if($max2<$ramuda[$j] && $ramuda[$j]<max1){
        $max2=$ramuda[$j];
        $pc2=$j;
      }
    }
    //第一主成分ベクトル、第二主成分ベクトル、及び各固有値を格納
    for($i=0;$i<$this->numberOfFeatures; $i++){
      $L[0][$i]=$U[$i][$pc1];
      $L[1][$i]=$U[$i][$pc2];
    }
    $L[0][$this->numberOfFeatures]=$ramuda[$pc1];
    $L[1][$this->numberOfFeatures]=$ramuda[$pc2];
    return $L;
  }
  //パワー法を用いた主成分分析
  /* 主成分分析で用いる変数
   * Xav[j]:入力データの平均。jは変数の次元(j=1~12)。
   * V[i][j]:入力データに対する分散共分散行列。i*jの行列(12*12)。i,jは共に変数の次元(i,j=1~12)。
   * L[n][i]:主成分ベクトル(固有ベクトル)。第一主成分ベクトルと第二主成分ベクトルを表している。
   *        nは主成分の番号(n=1,2)。iは変数の次元(i=1~12)。
   * パワー法で用いる変数(固有ベクトルを計算するための計算)
   * A[i][j]:パワー法にて求める対象となるベクトル。i*jの行列(12*12)。i,jは共に変数の次元(i,j=1~12)。
   * U[j]:固有ベクトル。iは変数の次元(i=1~12)。
   * $A_U[$i]:AとUの積。iは変数の次元(i=1~12)。
   * norm:スカラー変数。固有値の近似値の二乗。
   * eig:スカラー変数。固有値の近似値。
   */
  function powerPCA($data,$tmp){
    //各変数の平均Xavを求める
    for($j=0;$j<$this->numberOfFeatures; $j++){
      $Xav[$j]=$this->aveInputData($data,$tmp,$j);
    }
    //分散・共分散行列Vを求める
    //初期化
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        $V[$i][$j]=0;
      }
    }
    //計算
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        for($n=0;$n<$tmp;$n++){
          $V[$i][$j]+=($data[$n][$i+2]-$Xav[$i])*($data[$n][$j+2]-$Xav[$j]);
        }
        $V[$i][$j]/=($tmp);
      }
    }
    //0行列なら終了
    $check0=0;
    for($i=0;$i<$this->numberOfFeatures; $i++){
      for($j=0;$j<$this->numberOfFeatures; $j++){
        if($V[$i][$j]!=0)$check0=1;
      }
    }
    if($check0==0){
      $L[0][$this->numberOfFeatures]=0;
      $L[1][$this->numberOfFeatures]=0;
      return $L;
    }
    //パワー法を用いて主成分ベクトルLを求める
    //第二主成分までここでは求める(最大で変数の次元数12まで求めることができる。)
    for($n=0;$n<2;$n++){
      //1求めるベクトルをAとする。(第主一成分は相関行列を、第２主成分以降は以下の計算を行った残差行列をAとする。）
      //第一主成分なら
      if($n==0){
        $A=$V;//分散・共分散行列VをAとする。
      }
      //第二主成分以降なら
      else{
        for($i=0;$i<$this->numberOfFeatures; $i++){
          for($j=0;$j<$this->numberOfFeatures; $j++){
            $A[$i][$j]-=$eig*$U[$i]*$U[$j];//残差行列をAとする。
          }
        }
      }
      //規定の精度以下になるまで繰り返す。（ここでは10回の繰り返しまで行う)
      for($pre=0;$pre<10;$pre++){
        //2固有ベクトルLの初期化(第一行だけ1、それ以外の行は0に初期化)
        if($pre==0){
          for($j=0;$j<$this->numberOfFeatures; $j++){
            if($j==0){$U[$j]=1.0;}
            else{$U[$j]=0.0;}
          }
        }
        //3相関行列Rと固有ベクトルLの積を求める。
        //4ノルムを求める。
        $norm=0;//ノルムの初期化
        for($i=0;$i<$this->numberOfFeatures; $i++){
          $A_U[$i]=0;//A*Uの初期化
          for($j=0;$j<$this->numberOfFeatures; $j++){
            $A_U[$i]+=$A[$i][$j]*$U[$j];//A*Uを計算
          }
          $norm+=$A_U[$i]*$A_U[$i];//ノルムを計算
        }
        //5ノルムの平方根を取る。これが固有値の近似値である。
        $eig=sqrt($norm);
        //6固有ベクトルの更新;
        for($i=0;$i<$this->numberOfFeatures; $i++){
          if($eig==0){
            $pre=10;
            break;
          }
          else{
            $U[$i]=$A_U[$i]/$eig;
          }
        }
      }
      //主成分ベクトルLに追加
      $L[$n]=$U;
      $L[$n][$this->numberOfFeatures]=$eig;
    }
    return $L;
  }
}
