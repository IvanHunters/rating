
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

function clean_notes($text){
	$text=preg_replace("'<style[^>]*?>.*?</style>'si","",$text);
	$text=trim(strip_tags($text));
	if(strlen($text)>0){
		return '<p>'.$text.'</p>';
	}
	return '';
}

$config = require_once "lib/Config.php";
$soapConfig = new SoapConfig($config['staff']['url'], $config['staff']['login'], $config['staff']['password']);
for($i=3962;$i!=5559;$i++){
	if(preg_match("/\d\d\d\d/",$i)) $id=$i;
  elseif(preg_match("/\d\d\d/",$i)) $id="0".$i;
  elseif(preg_match("/\d\d/",$i)) $id="00".$i;
  elseif(preg_match("/\d/",$i)) $id="000".$i;
	$client = new SoapClient($soapConfig,'ПолучитьИнформациюОСотруднике', [
		'КодСотрудника' => '00000'.$id
	]);
//5558
//0001
$name = $client->getResponse()->Наименование;

if($name){
$array_name=explode(" ",$name);
$inicials = mb_substr($array_name[1], 0, 1).".".mb_substr($array_name[2], 0, 1).".";
$name_sokr = $array_name[0]." ".$inicials;
$post = $client->getResponse()->Должность;
$degree = $client->getResponse()->УченаяСтепень;
$academicTitle = $client->getResponse()->УченоеЗвание;
$experience = $client->getResponse()->СтажПоСпециальности;
$kontakts = strip_tags(clean_notes($client->getResponse()->КонтактныеДанные));
$dostizh = preg_replace("/'/",'"',preg_replace("/&quot;/","",strip_tags(clean_notes($client->getResponse()->Достижения))));
$thematics = preg_replace("/'/",'"',preg_replace("/&quot;/","",strip_tags(clean_notes($client->getResponse()->ТематикаНИД))));
$database->mysqli->query("INSERT INTO `lectures` (`name`,`name_sokr`,`post`,`degree`,`academicTitle`,`experience`,`kontakts`,`thematics`,`dostizh`) VALUES ('$name','$name_sokr','$post','$degree','$academicTitle','$experience','$kontakts','$thematics','$dostizh')");
echo $id."\n";
}
}
?>
