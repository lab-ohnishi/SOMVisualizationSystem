<?php
//$a = array(1,1,1,1,1,1,1,1,1,1,2,3);
$sd=$_POST['selected_data'];
$filename=$_POST['filename'];
$fp = fopen($filename,'r');
$i=0;
$num = fgetcsv($fp); //data0.txt first line is people number, not data
while($data = fgetcsv($fp)){
  //        if($data[0] == $sd[0] && $data[1] == $sd[1]){
    if ($sd === "$i"){
        $to[] = $data[1];
            unset($data[1]);
                unset($data[0]);
                    $data = array_merge($data, $to);
                        echo json_encode($data);
                            break;
                              }
                                $i++;
                                }
                                fclose($fp);

