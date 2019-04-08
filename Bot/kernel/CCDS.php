<?php
ini_set('display_errors','On');
error_reporting('E_ALL');
class CCDS {

  function __construct(){
    global $argv, $_GET;
    $this->cher = '';
    $this->semestr = 1;
    $db_hostname = '127.0.0.1';
    $db_database = 'rating';
    $db_username = 'rating';
    $db_password = 'WDN8aWyAt9_volsu';
    $mysqli = mysqli_connect($db_hostname, $db_username, $db_password,$db_username);
    $mysqli->query("SET character_set_client='utf8'");
    $mysqli->query("SET character_set_connection='utf8'");
    $mysqli->query("SET character_set_results='utf8'");
    $this->mysqli = $mysqli;
    $this->planId = $argv[1];
    $this->semestr = $argv[2];
    if(isset($_GET['plan'])){
     $this->planId = $_GET['plan'];
     $this->semestr = $_GET['semestr'];
    }
	$this->upp = '';
	$this->control = '';
    $this->predmets = '';
	$control='';
	$predmet = '';
  }

  function getOldData(){
    $d_cache = $this->mysqli->query("select * from cache where plan = '".$this->planId."' and semestr = '".$this->semestr."'");
    while($row = mysqli_fetch_array($d_cache))
    	$this->getDatabaseData[$row['zach']]=array('data'=>$row['data'], 'semestr'=>$row['semestr']);
  }

  function getDataPrCo(){
    global $arResult;
    $predmeter = array();
	if($arResult['MARKS'][0]['objects'] == false){
		//$this->mysqli->query("DELETE FROM `cache_plan` WHERE `cache_plan`.`plan_id` = '$this->planId'");
    //$this->mysqli->close();
		//die('ok');


	}
    foreach($arResult['MARKS'][0]['objects'] as $object){
      if(!in_array($object['name']." ".$object['control'],$predmeter)){
      $predmeter[]= $object['name']."  ".$object['control'];
      $predmet[]= $object['name'];
	  $control[]= $object['control'];
        if($object['name']==false){
        	//$this->mysqli->query("DELETE FROM `cache_plan` WHERE `cache_plan`.`plan_id` = '$this->planId'");
          $this->mysqli->close();
			die('ok');
		}

        }
      }

    $this->control = $control;
    $this->predmets = $predmet;
  }

  function parseDataIntoZach(){
    foreach($this->objectMark['objects'] as $objectInfo){

     $this->rait[$this->zach]["predmet"][]=preg_replace("/\"/",'\"',$this->predmets[$this->ch]);
     $this->rait[$this->zach]["ball"][]=$objectInfo['ball'];
     $this->rait[$this->zach]["control"][]=$this->control[$this->ch];
     $this->ch++;
    }
    $this->rait[$this->zach]['group'] = $this->group;
    $this->getDatabaseData[$this->zach]['data'] = json_decode($this->getDatabaseData[$this->zach]['data'],true);
    $this->getDatabaseData[$this->zach]['data']['predmet'] = preg_replace("/\"/",'\"',$this->getDatabaseData[$this->zach]['data']['predmet']);
    unset($this->getDatabaseData[$this->zach]['data']['position']);
    unset($this->rait[$this->zach]['position']);
    if($this->getDatabaseData[$this->zach]['data']['predmet']==false&&$this->getDatabaseData[$this->zach]['semestr']!=$this->semestr){
      //echo $this->getDatabaseData[$this->zach]['data']['predmet']."  ".$this->getDatabaseData[$this->zach]['semestr'];
    $this->mysqli->query("INSERT INTO `cache` (`zach`, `semestr`) VALUES ('$this->zach', '".$this->semestr."');");
    echo $this->mysqli->error;
    }
  }

  function mainActionsScript(){
    global $arResult;

    $this->count = 0;
    $this->formatMarks = $arResult['MARKS'];

    reset($this->formatMarks);

      foreach($this->formatMarks as $this->objectMark){

        $this->count++;
        $this->zach = $this->objectMark['number'];
        $this->group = $this->objectMark['group'];
        $this->ch = 0;

        $this->rait[$this->zach]=array(
          "group"=>$this->group,
            "position"=> $this->count);
        $this->parseDataIntoZach();


      if(preg_match("/(.*)-14(.*)/",$this->rait[$this->zach]['group'])||$this->zach == false){
        $this->mysqli->close();
      die($this->rait[$this->zach]['group']);

      }
      $this->hash_md5 = md5(print_r($this->rait[$this->zach]['ball'],true));
      $this->hash_md5_old = md5(print_r($this->getDatabaseData[$this->zach]['data']['ball'],true));
      		if($this->hash_md5!=$this->hash_md5_old&&$this->getDatabaseData[$this->zach]['semestr']==$this->semestr){
      	$this->query_marks[] = "('".$this->zach."','".preg_replace("/\"/",'\"',json_encode($this->getDatabaseData[$this->zach]['data'],JSON_UNESCAPED_UNICODE))."','".json_encode($this->rait[$this->zach],JSON_UNESCAPED_UNICODE)."')";
          $this->cher = 10;
          }
      $this->rait[$this->zach]['position'] = $this->count;
      $this->rait[$this->zach]['sum_ball'] = array_sum($this->rait[$this->zach]['ball']);
      $this->set = "change_time= '".date("d.m.y H:i", time()+7200)."', sum_ball= '".$this->rait[$this->zach]['sum_ball']."', position = '".$this->rait[$this->zach]['position']."', semestr = '".$this->semestr."', plan = '".$this->planId."', groups = '".$this->group."',   data = '".json_encode($this->rait[$this->zach],JSON_UNESCAPED_UNICODE)."', time= '".time()."'";
      $this->upp[] = "UPDATE cache SET ".$this->set."  WHERE zach = '".$this->zach."' and semestr = '".$this->semestr."';";
      }

      if($this->cher == "10"){
     $this->mysqli->query("INSERT INTO `change_marks` (`zach`,`data_old`,`data_new`) VALUES".implode(",",$this->query_marks));
     }
	 if($this->upp!=false)
     $this->mysqli->multi_query(implode("",$this->upp));
  }


  function __destruct() {
//$this->mysqli->close();
  }

}


?>
