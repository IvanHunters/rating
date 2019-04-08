<meta charset="utf8">
<?php
ini_set('display_errors','On');
error_reporting('E_ALL');
$time_st = microtime(true);
include "CCDS.php";

require_once "./lib/SoapConfig.php";
require_once "./lib/SoapClient.php";
require_once "./lib/Uri.php";
require_once "./lib/Util.php";
require_once "./lib/Validator.php";

use Volsu\Soap\SoapConfig;
use Volsu\Soap\SoapClient;
use Volsu\Utility\Helper;
use Volsu\Url\Uri;
use Volsu\Validation\Validator;

$config = require_once "./lib/Config.php";
//for($i=1;$i!=9;$i++){
//$argv[2] = $i;
$CCDS = new CCDS();
$mysqli = $CCDS->mysqli;
$soapConfig = new SoapConfig($config['eduprogs']['url'], $config['eduprogs']['login'], $config['eduprogs']['password']);

    $client = new SoapClient($soapConfig, 'ПолучитьРезультатыАттестации', [
        'НомерУчебногоПлана' => $CCDS->planId,
        'Семестр' => $CCDS->semestr,
        'Язык' =>"Ru"
    ]);



    if(Validator::isEmptyObject($client->getResponse())){
        $arResult['EMPTY'] = true;
		mysql_query("DELETE FROM `cache_plan` WHERE `cache_plan`.`plan_id` = '$planId'");
    }else{
		$arResult['SEMESTRES'] = Helper::toArray($client->getResponse()->ДоступныеПериодыКонтроля);
		$arResult['SURRENT_SEMESTR'] = $client->getResponse()->ПериодКонтроля;
        $arResult['PLAN_ID'] = $CCDS->planId;

        $arResult['MARKS'] = Helper::formatMarks($client->getResponse()->Оценки);
        $arResult['RAW'] = $client->getResponse();
    }


//$mysqli->query("START TRANSACTION;");
$getDatabaseData= $CCDS->getOldData();
$CCDS->getDataPrCo();
$control = $CCDS->control;
$predmets = $CCDS->predmets;
$CCDS->mainActionsScript();
//$mysqli->multi_query(implode("",$CCDS->upp));
$CCDS->mysqli->close();
//}
$timmer = microtime(true)-$time_st;
echo $timmer."\n";
?>
