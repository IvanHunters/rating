<?php

ini_set('error_reporting', 0);
ini_set('display_errors', 0);

include "kernel/kernel.php";
require_once 'kernel/login_rait.php';

$token_Group = '07f1415ac6b929361912121c859f977f142e6995eb13911836eb55c5f9ff1ad6f675da608e83ac641d8f5';
$vk = new vk("", $token_Group);
$vk->keyboard_m(array("Рейтинг","Изменить зачетку","Все команды","Справка"));

while($row = mysql_fetch_array(mysql_query("SELECT * FROM spam"), MYSQL_ASSOC)){
	$message = $row['spam_text'];
	$act = $row['spam_id'];
	$whom = $row['whom'];
	switch($act){
		case '2':
		while($gr_q = mysql_fetch_array(mysql_query("SELECT cache.zach as zach, users.id as id, users.rasspam as spam FROM cache INNER JOIN users on cache.zach=users.zach WHERE groups = '$whom' and users.rasspam = 1"), MYSQL_ASSOC)){
			$id = $qr_q['id'];
			$vk->putChatUser($id,$id);
			 $vk->messageFromGroup($message);
			 echo $id."  ";
			 die();
			 usleep(50000);
		}
		mysql_query("TRUNCATE `rating`.`spam`");
		break;

		case '3':
		while($ins_q = mysql_fetch_array(mysql_query("SELECT cache.zach as zach, users.id as id, users.rasspam as spam FROM cache INNER JOIN users on cache.zach=users.zach INNER JOIN cache_plan on cache.plan = cache_plan.plan_id WHERE cache_plan.institut = '$whom' and users.rasspam = 1"), MYSQL_ASSOC)){
			$id = $ins_q['id'];
			$vk->putChatUser($id,$id);
			 $vk->messageFromGroup($message);
			 usleep(50000);
		}
		mysql_query("TRUNCATE `rating`.`spam`");
		break;

		case '4':
		$q = mysql_query("SELECT * FROM users WHERE `group`=''");
		while($not_auth = mysql_fetch_array($q, MYSQL_ASSOC)){
			$id = $not_auth['id'];
			 $vk->putChatUser($id,$id);
			 $vk->messageFromGroup($message);
			 echo $id."  ";
			 usleep(50000);
		}
		mysql_query("TRUNCATE `rating`.`spam`");
		break;

		case '5':
		while($all = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE not `group`='' and rasspam = 1"), MYSQL_ASSOC)){
			$id = $all['id'];
			 $vk->putChatUser($id,$id);
			 $vk->messageFromGroup($message);
			 usleep(50000);
		}
		mysql_query("TRUNCATE `rating`.`spam`");
		break;

	}
}

?>
