<?php 
function getPeople($selector = null){
  $fp = fopen(dirname(__FILE__).'/people.txt','r');
  $i = 0;
  while($data = fgets($fp)){
    $data = rtrim($data);
    list($people[$i],$uid[$i]) = preg_split("/,/", $data);
    $i++;
  }
  fclose($fp);
  if($i!=0) return $people;      
  else return null;
}
function getTargetPeople($fromName){
  $fp = fopen(dirname(__FILE__).'/people.txt','r');
  $fpTarget = fopen('targetReport.txt','r');
  $i=0;
  while($data = fgets($fp)){
    $data = rtrim($data);
    list($userName,$uid) = preg_split("/,/", $data);
    if($userName == $fromName){
      break;
    }
    $i++;
  }
  $i=0;
  while($data = fgets($fpTarget)){
    list($target[0],$target[1],$target[2],$target[3]) = preg_split("/,/", rtrim($data));
    if(strcmp($target[0],$uid)==0){
      fclose($fp);
      fclose($fpTarget);
      return $target;           
    }
    $i++;
  }        
  return null;
}
function getMyNumber($my){
  $fp = fopen(dirname(__FILE__).'/people.txt','r');
  $i = 0;
  while($data = fgets($fp)){
    $data = rtrim($data);
    list($userName,$uid) = preg_split("/,/", $data); 
    if($userName == $my){
      return $uid;
    }
    $i++;
  }
}
function getMyRegisteredPeople($my){
  //$fp=fopen('data0.txt','r');
  $fp=fopen('data2.txt','r');
  $i=0;
  $inData = null;
  while($data = fgetcsv($fp)){
    if($data[0] == $my && $data[1] != $my){
      $inData[$i] = $data[1];
      $i++;
    }
  }
  fclose($fp);
  return $inData;
}
function getAllPeopleName(){
  $fp=fopen('people.txt', 'r');
  $i = 0;
  $peopleNames = array();
  while($data = fgets($fp)){
    $data = rtrim($data);
    list($userName, $uid) = split(",", $data);
    array_push($peopleNames, $userName);
  }
  fclose($fp);
  return $peopleNames;
}    
function getPeerAssessmentData($eventID, $fromName, $persNum){
  $filename = "data".$eventID.".txt";
  $fp = fopen($filename, "r");
  $i=0;
  while (($line = fgetcsv($fp)) !== FALSE) {
    if($i == 0){
      $header = $line;
      $i++;
      continue;
    }
    else if($line[0] == $fromName){
      for($j = 2; $j < 2+$persNum-2; $j++){
        $line[$j]++;
      }
      $data[] = $line;
    }
    $i++;
  }
  fclose($fp);
  return $data;
}
function getSpecificAssessmentData($eventID, $fromName, $toName, $persNum){
  if($eventID == 1){
    $eventID = 0;
    $filename = "data".$eventID.".txt";
  } else {
    $filename = "data".$eventID.".txt";
  }
  $fp = fopen($filename, "r");
  $i = 0;
  while (($line = fgetcsv($fp)) != FALSE) {
    if($i == 0){
      $header = $line;
      $i++;
      continue;
    } else if(($line[0] == $fromName)&&($line[1] == $toName)){
      fclose($fp);
      return $line;
    }
  }
  fclose($fp);
  return NULL;
}
