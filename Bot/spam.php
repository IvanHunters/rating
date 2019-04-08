<?php
include "kernel/kernel.php";
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
$db = new database();
$token_Group = '07f1415ac6b929361912121c859f977f142e6995eb13911836eb55c5f9ff1ad6f675da608e83ac641d8f5';
$vk = new vk("", $token_Group);
$row = $db->mysqli->query("SELECT * FROM spam")->fetch_array();
	$message = $row['spam_text'];
	$attachment = $row['attachment'];
	$act = $row['spam_id'];
	$whom = $row['whom'];
	switch($act){
		case '2':
		$querr = $db->mysqli->query("SELECT cache.zach as zach, users.id as id, users.rasspam as spam FROM cache INNER JOIN users on cache.zach=users.zach WHERE groups = '$whom' GROUP by cache.zach");
			$rows = $querr->fetch_array();
			$ard=1;
			$vk->keyboard_m( array("Рейтинг","Рб0","РГ","Все команды","Изменить зачетку","Изменить семестр"));
				while($rows['id']!=false){
				 $id = $rows['id'];
				 if($ard%100==0){
					$idss = implode(",",$idsee);
	 			 $vk->apiVkGroup("messages.send",array('user_ids'=>$idss,'message'=>$message,'random_id'=>rand(1,rand(1,56)),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
					$idsee= array();
					$ard=1;
				 }
				 $idsee[]=$id;
				 $rows = $querr->fetch_array();
				 $ard++;
			}
			$idser = implode(",",$idsee);
		  $vk->apiVkGroup("messages.send",array('user_ids'=>$idser,'message'=>$message,'random_id'=>rand(1,63),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
		$db->mysqli->query("TRUNCATE `rating`.`spam`");
		echo 'sd';
		break;

		case '3':
		$querr = $db->mysqli->query("SELECT cache.zach as zach, users.id as id, users.rasspam as spam FROM cache INNER JOIN users on cache.zach=users.zach INNER JOIN cache_plan on cache.plan = cache_plan.plan_id WHERE cache_plan.institut = '$whom' GROUP BY cache.zach");
		$rows = $querr->fetch_array();
		$ard=1;
			$vk->keyboard_m( array("Рейтинг","Рб0","РГ","Все команды","Изменить зачетку","Изменить семестр"));
			while($rows['id']!=false){
			 $id = $rows['id'];
			 if($ard%100==0){
				$idss = implode(",",$idsee);
			 $vk->apiVkGroup("messages.send",array('user_ids'=>$idss,'message'=>$message,'random_id'=>rand(1,rand(1,56)),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
				$idsee= array();
				$ard=1;
			 }
			 $idsee[]=$id;
			 $rows = $querr->fetch_array();
			 $ard++;
		}
		$idser = implode(",",$idsee);
		$vk->apiVkGroup("messages.send",array('user_ids'=>$idser,'message'=>$message,'random_id'=>rand(1,63),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
		$db->mysqli->query("TRUNCATE `rating`.`spam`");
		echo 'ds';
		break;

		case '4':
		$querr = $db->mysqli->query("SELECT * FROM users WHERE `position`!='5'");
		$rows = $querr->fetch_array();
		$ard=1;
			$vk->keyboard_m( array("Рейтинг","Рб0","РГ","Все команды","Изменить зачетку","Изменить семестр"));
			while($rows['id']!=false){
			 $id = $rows['id'];
			 if($ard%100==0){
				$idss = implode(",",$idsee);
			 $vk->apiVkGroup("messages.send",array('user_ids'=>$idss,'message'=>$message,'random_id'=>rand(1,rand(1,56)),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
				$idsee= array();
				$ard=1;
			 }
			 $idsee[]=$id;
			 $rows = $querr->fetch_array();
			 $ard++;
		}
		$idser = implode(",",$idsee);
		$vk->apiVkGroup("messages.send",array('user_ids'=>$idser,'message'=>$message,'random_id'=>rand(1,63),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
		$db->mysqli->query("TRUNCATE `rating`.`spam`");
		echo 3;
		break;
		
		case '6':
		$querr = $db->mysqli->query("SELECT * FROM users WHERE starosta='1'");
		$rows = $querr->fetch_array();
		$ard=1;
			$vk->keyboard_m( array("Рейтинг","Рб0","РГ","Все команды","Изменить зачетку","Изменить семестр"));
			while($rows['id']!=false){
			 $id = $rows['id'];
			 if($ard%100==0){
				$idss = implode(",",$idsee);
			 $vk->apiVkGroup("messages.send",array('user_ids'=>$idss,'message'=>$message,'random_id'=>rand(1,rand(1,56)),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
				$idsee= array();
				$ard=1;
			 }
			 $idsee[]=$id;
			 $rows = $querr->fetch_array();
			 $ard++;
		}
		$idser = implode(",",$idsee);
		$vk->apiVkGroup("messages.send",array('user_ids'=>$idser,'message'=>$message,'random_id'=>rand(1,63),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
		$db->mysqli->query("TRUNCATE `rating`.`spam`");
		echo 3;
		break;

		case '5':
		$querr = $db->mysqli->query("SELECT * FROM users WHERE position=5");
		$rows = $querr->fetch_array();
		$ard=1;
			$vk->keyboard_m( array("Рейтинг","Рб0","РГ","Все команды","Изменить зачетку","Изменить семестр"));
			while($rows['id']!=false){
			 $id = $rows['id'];
			 if($ard%100==0){
				$idss = implode(",",$idsee);
 			 $vk->apiVkGroup("messages.send",array('user_ids'=>$idss,'message'=>$message,'random_id'=>rand(1,rand(1,56)),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
				$idsee= array();
				$ard=1;
			 }
			 $idsee[]=$id;
			 $rows = $querr->fetch_array();
			 $ard++;
		}
		$idser = implode(",",$idsee);
	  $vk->apiVkGroup("messages.send",array('user_ids'=>$idser,'message'=>$message,'random_id'=>rand(1,63),'keyboard'=>$vk->keyboard, 'attachment'=>$attachment));
		$db->mysqli->query("TRUNCATE `rating`.`spam`");
		echo 4;
		break;

	}

?>
