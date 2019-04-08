<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
require_once "./login.php";

switch ($_GET['method']) {

  case 'getInfoWithUserID':
  $req = mysql_query("SELECT * FROM `users` u INNER JOIN cache c on c.zach = u.zach and c.semestr = u.semestr where id = '".$_GET['id']."';");
$res = mysql_fetch_array($req, MYSQL_ASSOC);
    die(json_encode($res,JSON_UNESCAPED_UNICODE));
  break;

  case 'getInstituts':
    $req = mysql_query("SELECT DISTINCT institut FROM `cache_plan`");
    while($res = mysql_fetch_array($req, MYSQL_ASSOC))
      $instituts[]=$res['institut'];
      die(json_encode($instituts,JSON_UNESCAPED_UNICODE));
    break;

  case 'getNamePlan':
    $institut = $_GET['institut'];
    $req = mysql_query("SELECT DISTINCT name_plan FROM `cache_plan` WHERE institut = '$institut' ORDER BY name_plan ASC");
    while($res = mysql_fetch_array($req, MYSQL_ASSOC))
      $name_plan[]=$res['name_plan'];
      die(json_encode($name_plan,JSON_UNESCAPED_UNICODE));
    break;

  case 'getNameGroups':
    $name_plan = preg_replace("/pr/"," ", $_GET['name_plan']);
    $req = mysql_query("SELECT cache.plan, cache.groups FROM cache_plan INNER JOIN cache on cache_plan.plan_id = cache.plan WHERE cache_plan.name_plan = '$name_plan' ORDER BY cache.groups DESC");
    while($res = mysql_fetch_array($req, MYSQL_ASSOC)){
      if($res['groups']!=false)
    $GroupsIntoPlanName[$res['groups']."volsu".$res['plan']]=$res['groups'];
  }

    die(json_encode($GroupsIntoPlanName,JSON_UNESCAPED_UNICODE));
    break;

  case 'getZachIds':
    $group_name = explode("volsu",$_GET['name_plan'])[0];
    $group_semestr = explode("volsu",$_GET['name_plan'])[1];
    $req = mysql_query("SELECT zach, plan FROM cache WHERE `groups` = '$group_name' ORDER BY zach ASC");
    $flag = 0;
    while($res = mysql_fetch_array($req, MYSQL_ASSOC)){
      $pl = $res['plan'];
      if($flag==0)
      $GroupsIntoPlanName[$pl."volsuAllvolsu".$group_semestr]="Всей группы";
      $flag = 1;
    $GroupsIntoPlanName[$res['plan']."volsu".$res['zach']."volsu".$group_semestr]=$res['zach'];
  }
    die(json_encode($GroupsIntoPlanName,JSON_UNESCAPED_UNICODE));
    break;

	 case 'getSemestrs':
    $zach = $_GET['zach'];
    $group = $_GET['group'];
	if($zach!=false)
    $req = mysql_query("SELECT DISTINCT semestr FROM cache WHERE zach = '".$zach."' ORDER by semestr ASC");
	if($group!=false)
	$req = mysql_query("SELECT DISTINCT semestr FROM cache WHERE groups = '".$group."' ORDER by semestr ASC");
    while($res = mysql_fetch_array($req, MYSQL_ASSOC)){
      $pl[] = $res['semestr'];
  }
    die(json_encode($pl,JSON_UNESCAPED_UNICODE));
    break;

  default:
    die('Method Not Found!');
    break;

}

?>
