
<meta charset="utf8">
<?php
include "kernel.php";
$database = new database();
require_once "lib/SoapConfig.php";
require_once "lib/SoapClient.php";
require_once "lib/DepartmentVolsuMap.php";
require_once "lib/Uri.php";
require_once "lib/Util.php";
require_once "lib/Validator.php";

use Volsu\Soap\SoapConfig;
use Volsu\Soap\SoapClient;
use Volsu\Utility\Helper;
use Volsu\Url;
use Volsu\Validation\Validator;

$config = require_once "lib/Config.php";
$soapConfig = new SoapConfig($config['eduprogs']['url'], $config['eduprogs']['login'], $config['eduprogs']['password']);
	$database->mysqli->query("TRUNCATE `rating`.`cache_plan`");
for($id=3;$id<9;$id++){
	$id = "0".$id;
	$client = new SoapClient($soapConfig,'ПолучитьСписокПрограммПодготовки', [
		'КодУровняПодготовки' => $id,
		'Язык' => 'Ru'
	]);


		$mass = Helper::toArray($client->getResponse()->ПрограммыПодготовкиКратко);
	for($i=0;$i<=count($mass);$i++){
		if(isset($mass[$i]->Наименование)){
		$key=$mass[$i]->Код;
		$keys[]=$key;

	}
}
}


	for($id=0;$id<count($keys);$id++){
	$client = new SoapClient($soapConfig, 'ПолучитьИнформациюОПрограммеПодготовки', [
		'КодПрограммыПодготовки' => $keys[$id],
		'Язык' => 'Ru'
	]);
	$mass = Helper::toArray($client->getResponse()->УчебныеПланы);

	foreach($mass as $data){
		if(preg_match("/2018/",$data->Год)) $semestr = 2;
		if(preg_match("/2017/",$data->Год)) $semestr = 4;
		if(preg_match("/2016/",$data->Год)) $semestr = 6;
		if(preg_match("/2015/",$data->Год)) $semestr = 8;
		if(!preg_match("/2011/",$data->Год)&&!preg_match("/2012/",$data->Год)&&!preg_match("/2013/",$data->Год)&&!preg_match("/2014/",$data->Год))
	$database->mysqli->query("INSERT INTO `cache_plan` (`plan_id`,`name_plan`,`form`,`year`,`semestr`,`institut`) VALUES ('".$data->Номер."','".$data->Наименование."','".$data->ФормаОбучения."','".$data->Год."', $semestr, '')");
	//$resulter = mysql_query($query);
	}
	}
	$database->mysqli->query("CALL UNI()");
?>
