<?php

ini_set('error_reporting', 1);
ini_set('display_errors', 1);
include "kernel/kernel.php";
include "kernel/rating.php";
$confirmation_token = '50d1d60f';

$token_Group = '07f1415ac6b929361912121c859f977f142e6995eb13911836eb55c5f9ff1ad6f675da608e83ac641d8f5';
$token_User  = 'f8b7f5a0bdb0fb409978cb62ae84cd81de82c2b140a82c4bff04eee231c5735ba444d9c68e0d86735f882';


$vk = new vk($token_User, $token_Group);
$key_B = array("Рейтинг","Рб0","Все команды","Изменить зачетку","Изменить семестр","Справка");
$database = new database();
$rating = new rating();

$data = json_decode(file_get_contents('php://input'));

switch ($data->type)
	{

case 'confirmation':
	exit($confirmation_token);


case 'message_new':
    /*-----------ЗАПИСЫВАЕМ ВХОДНЫЕ ДАННЫЕ-----------*/
	$chat=$data->object->peer_id;
	$active = $data->object->action->type;
	$sigen = $data->object->from_id;
	$group = $data->group_id;
	$id_mes= $data->object->id;
	$ata = $data->object->attachments;
	if($ata){
		    $attachments = parse_attachments($ata);
	}else{
	    $attachments = "";
	}
	$textrass = $data->object->text;
	$publish_date = $data->object->date;
	$vk->putChatUser($data->object->peer_id, $data->object->from_id);
	if($sigen == 145567397)
	$key_B = array("Рб0","Изменить зачетку","Изменить семестр","Админ-панель");
	$vk->keyboard_m($key_B);
	$querydd = "SELECT vm_d.sum_ball, vm_d.numb, users.*, cache.data, cache.change_time, cache.plan, ( SELECT COUNT(zach) FROM cache WHERE plan = @plan AND groups = @groups AND semestr = @semestr ) AS COUNT, ( SELECT sum_ball FROM cache WHERE plan = @plan AND semestr = @semestr AND sum_ball > vm_d.sum_ball AND groups = vm_d.groups ORDER BY sum_ball ASC LIMIT 1 ) - vm_d.sum_ball AS difference FROM ( SELECT t.zach, t.semestr, t.sum_ball, t.`groups`, @row_n := @row_n + 1 AS numb FROM cache t, ( SELECT @row_n := 0, @id := '$sigen', @zach :=( SELECT zach FROM users WHERE id = @id ), @groups :=( SELECT `group` FROM users WHERE zach = @zach and id= @id ), @semestr :=( SELECT semestr FROM users WHERE id = @id ), @plan :=( SELECT plan FROM cache WHERE zach = @zach limit 1 ) ) r WHERE plan = @plan AND semestr = @semestr AND groups = @groups ORDER BY `t`.`sum_ball` DESC ) vm_d INNER JOIN users ON users.zach = vm_d.zach INNER JOIN cache ON cache.zach = vm_d.zach WHERE vm_d.zach = @zach AND users.id = @id AND cache.semestr = @semestr";
	$row = $database->execute_query($querydd);
    if($row["position"]==false){
        $query = "INSERT INTO `users` (`id`,`position`) VALUES ('$sigen','0')";
        $resulter = $database->execute_query($query);
    }
	
		$tester = $spam_m = $vk->chat_text = $data->object->text;
		$tester = preg_replace("/\[(.*)\]\s/","",mb_strtolower($tester));
		$tester = preg_replace("/ё/","е",$tester);
		$commander = $database->execute_query("SELECT * FROM commands WHERE command like BINARY '$tester%'");
		if($tester == "" )
		die('ok');
		if( $commander['kolit_zapis']>1 ){
			$vk->messageFromGroup("Найдено больше 1 команды. Введите более полное название");
			die('ok');
		}
		$command = $commander['command'];
		if($command != false)
		$tester = $command;

	$user_request = $row["position"];
	if($user_request==false)
	$user_request = $database->execute_query("SELECT * FROM users WHERE id = $sigen")["position"];
	$user_zach = $row["zach"];
	$change_time = $row["change_time"];
	$user_reset = $row["reset"];
	$follow = $row["follow"];
	$user_time = $row["time"];
	$user_zamenazach = $row["user_zamena_zach"];
	$planner= $row["plan"];
	$user_textr = $row["text"];
	$kss=$row["control_sum"];
	$helper = $row["helper"];
    $group=$row["group"];
	$rassgroup = $row["rassgroup"];
	$spam_mes=$row["rasspam"];
	$pos_rait = $row["pos_rait"];
	$edSem = $row["semEdit"];
	$ids = $row["ids"];
	$key_settings = $row["keyboard"];
	if(preg_match("/\d{6}/imu",$tester) && $database->execute_query("SELECT plan, semestr, groups FROM cache WHERE zach = '$tester'")['plan']!=""){
		$zach = $tester;
		$row = $database->execute_query("SELECT plan, semestr, groups FROM cache WHERE zach = '$zach'");
		$vk->messageFromGroup( "По вашим данным найден студент из группы ".$row['groups']."\nДля просмотра своих баллов, напишите:\nРейтинг");
		$database->savePlace("user_zamena_zach", "0");
		$database->savePlace("position", "5");
		$database->savePlace("zach", "$tester");
	die('ok');
	}
	/*if($sigen!=145567397&&$sigen!=276849799&&$sigen!=153673346){
		$vk->messageFromGroup("Извините, ведутся технические работы!\nНапишите позже");
		die('ok');
	}else{
	}*/
	$vk->key_settings = $key_settings;
	switch ($tester)
		{

/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/
	case $tester:
	if($row['new_notes']=="1"){
		$vk->messageFromGroup( $attachments."Ваша заметка сохранена!\nХотите ли задать для нее таймер?");
			$database->savePlace("new_notes","0");
	}

	if ($edSem == "1" && (!ctype_digit($tester) || $tester <0 || $tester > 10)&&$tester!='отмена'){
			$vk->keyboard_m(array("Отмена"));
		$vk->messageFromGroup( "Ваша новый семестр не корректен $plohSmile\nПожалуйста, введите его заново!");
		die('ok');

	}elseif ($edSem == "1"){
		if($database->execute_query("SELECT * from cache WHERE zach = '".$row['zach']."' and semestr = '$tester'")['stID']!=false){
			$vk->messageFromGroup( "Ваш новый семестр сохранен.\nНапиши РБ, чтобы посмотреть свои баллы");
			$database->savePlace("semEdit","0");
			$database->savePlace("semestr",$tester);
		}else {
			$r_count = $database->execute_query("SELECT * from cache WHERE zach = '".$row['zach']."' order by semestr asc",1);
				while($c_semestr = mysqli_fetch_array($r_count,MYSQL_ASSOC))
				$r_sem[]=$c_semestr['semestr'];
			$vk->messageFromGroup( "Извини, но такого семестра нет в базе только ".implode(', ',$r_sem)." семестры");
			$database->savePlace("semEdit","0");
		}
	  die('ok');

	}elseif ($user_zamenazach == "1" && (!ctype_digit($tester) || $tester <= 100000 || $tester >= 1000000)&&$tester!="отмена"){
			$vk->keyboard_m(array("Отмена"));
		$vk->messageFromGroup( "Ваша новая зачётная книжка не корректна $plohSmile\nПожалуйста, введите ее заново!");
		break;

	}elseif ($user_zamenazach == "1" && $database->execute_query("SELECT plan, semestr, groups FROM cache WHERE zach = '$tester'")['plan']!=""){
		$zach = $tester;
		$row = $database->execute_query("SELECT plan, semestr, groups FROM cache WHERE zach = '$zach'");
			 if($row['plan']==""){
					 $vk->messageFromGroup( "Указанные Вами данные не найдены, напишите пожалуйста vk.com/x_dev и он Вам поможет");
					 $database->savePlace("error", "1");
			 }else{
					 $vk->messageFromGroup( "По вашим данным найден студент из группы ".$row['groups']."\nДля просмотра своих баллов, напишите:\nРейтинг");
					 $database->savePlace("position", "5");
			 $database->savePlace("user_zamena_zach", "0");
				 $database->savePlace("zach", "$tester");
				 $database->savePlace("group", $row['groups']);
			}
			break;

		}elseif($user_zamenazach == "1"&&$tester!="отмена"){

			$vk->keyboard_m(array("Отмена"));
			$vk->messageFromGroup("Ой, кажется вы не вышли из режима смены зачетной книги.\nНапишите отмена, чтобы продолжить работу с ботом");
			break;

		}elseif($user_reset == "1"&&$tester!="да"&&$tester!="нет"){
			$vk->keyboard_m(array("Нет"));
			$vk->messageFromGroup("Ой, кажется вы не вышли из режима сброса настроек.\nНапишите Нет, чтобы продолжить работу с ботом");
			break;

		}elseif ($user_request == "1"){

		    $zach = $tester;
		    $row = $database->execute_query("SELECT plan, semestr, groups FROM cache WHERE zach = '$zach'");
		       if($row['plan']==""){
		           $vk->messageFromGroup( "Указанные Вами данные не найдены, напишите пожалуйста vk.com/x_dev и он Вам поможет");
							 $database->savePlace("error", "1");
		       }else{
						 	$vk->keyboard_m(array("Изменить семестр"));
							$semestr = $database->execute_query("SELECT semestr FROM cache_plan WHERE plan_id= '".$row['plan']."'")['semestr'];
		          $vk->messageFromGroup( "По вашим данным найден студент из группы ".$row['groups']."\nНастройка завершена.\nДля просмотра своих баллов, напишите:\nРБ");
		          $database->savePlace("position", "5");
				   		$database->savePlace("user_zamena_zach", "0");
							$database->savePlace("semestr", $semestr);
							$database->savePlace("semEdit", "0");
		    	   	$database->savePlace("zach", "$tester");
		    	   	$database->savePlace("group", $row['groups']);

						}
		break;

		}elseif ($user_request == false){
				$vk->keyboard_m(array("Справка"));
				$vk->messageFromGroup( "Вы у нас впервые $smile. Напишите номер вашей  зачетной книги или номер студенческого билета.");
				$database->savePlace("position", "1");
				break;
		}elseif($command == false){
			if($sigen==145567397){

				if(preg_match("/https:\/\/vk.com/",$tester)){
					$text = explode("https://vk.com/im?sel=",$tester);
					if($text[1]==false)
					$text = explode("https://vk.com/",$tester);
					$text[1] = preg_replace("/\s(.*)/","",$text[1]);
					$profile = $vk->apiVkUser("users.get",array("name_case"=>"Nom",'user_ids'=>$text[1]))['response'][0];
					$info = $database->execute_query("SELECT vm_d.sum_ball, cache_plan.*, vm_d.numb, users.*, cache.data, cache.change_time, cache.plan, ( SELECT COUNT(zach) FROM cache WHERE plan = @plan AND groups = @groups AND semestr = @semestr ) AS count, ( SELECT sum_ball FROM cache WHERE plan = @plan AND semestr = @semestr AND sum_ball > vm_d.sum_ball AND groups = vm_d.groups ORDER BY sum_ball ASC LIMIT 1 ) - vm_d.sum_ball AS difference FROM ( SELECT t.zach, t.semestr, t.sum_ball, t.`groups`, @row_n := @row_n + 1 AS numb FROM cache t, ( SELECT @row_n := 0, @id := '".$profile['id']."', @zach :=( SELECT zach FROM users WHERE id = @id ), @groups :=( SELECT `group` FROM users WHERE zach = @zach and id= @id ), @semestr :=( SELECT semestr FROM users WHERE id = @id ), @plan :=( SELECT plan FROM cache WHERE zach = @zach limit 1 ) ) r WHERE plan = @plan AND semestr = @semestr AND groups = @groups ORDER BY `t`.`sum_ball` DESC ) vm_d INNER JOIN users ON users.zach = vm_d.zach INNER JOIN cache ON cache.zach = vm_d.zach INNER JOIN cache_plan ON cache.plan = cache_plan.plan_id WHERE vm_d.zach = @zach AND users.id = @id AND cache.semestr = @semestr");
					$response = $profile['first_name']." ".$profile['last_name']."\n";
					foreach($info as $key=>$value){
						if($value == "0"&&$key!='request_r'&&$key!='kolit_zapis'&&$key!='numb')
						$info[$key] = "выкл.";
						if($value == "1"&&$key!='request_r'&&$key!='kolit_zapis'&&$key!='numb')
						$info[$key] = "вкл.";
					}
					$set = explode(" ",$tester);
					if($set[1]=="1")
					$settings="".$info['count']."\n------------\nСправка: ".$info['helper']."\nКлавиатура: ".$info['keyboard']."\nРежим показывания позиции: ".$info['pos_rait']."\nПодписка на рассылку: ".$info['rasspam']."\nОбращений к рейтингу сегодня: ".$info['request_r']."\nРежим замены зачетки: ".$info['user_zamena_zach']."\nРежим сброса: ".$info['reset']."\n------------";
					if($info['zach'] == false)
					$response .= $text[1]."Такой пользователь не авторизовался";
					else
					$response .= "Количество пользователей, привязанных к зачетке: ".$info['kolit_zapis']."\nИнститут: ".$info['institut']."\nЗачетка: ".$info['zach']."\nID учебного плана: ".$info['plan']."\nНазвание направления: ".$info['name_plan']."\nФорма обучения: ".$info['form']."\nГода обучения: ".$info['year']."\nГруппа: ".$info['group']."\nПозиция: ".$info['numb']."/".$info['count'].$settings."\nСсылка на диалог: https://vk.com/gim146433609?sel=".$info['id'];
					$vk->messageFromGroup($response);
					die('ok');
				}


			$spam = $database->execute_query("SELECT * FROM spam");

			if($spam['spam_id']!=false&&$spam['whom']!=false){
				$database->updatePlace("spam_text", $spam_m, 1);
				$database->updatePlace("attachment", $attachments, 1);
				system("php spam.php > /dev/null&");
				$vk->messageFromGroup("Рассылка запущена по группе или институту");
				die('ok');
			}elseif($spam['spam_id']!=false&&($spam['spam_id']==2||$spam['spam_id']==3)){
				$vk->messageFromGroup("Напиши текст");
				$database->updatePlace("whom", $spam_m, 1);
				$vk->keyboard_m(array("Отмена"));
				die('ok');
			}elseif($spam['spam_id']!=false){
				$database->updatePlace("spam_text", $spam_m, 1);
				$database->updatePlace("attachment", $attachments, 1);
				system("php spam.php > /dev/null&");
				$vk->messageFromGroup("Рассылка запущена по всем");
				die('ok');

			}

		if($sigen==145567397 && $tester=="рассылка"){
			$vk->keyboard_m(array("Группе", "Институту", "Не авторизованным", "Всем"));
			$vk->messageFromGroup("Выберите тип рассылки");
				die('ok');
			}

			if($sigen==145567397 && $tester=="группе"){
			$vk->keyboard_m(array("Отмена"));
			$vk->messageFromGroup("Напиши группу для рассылки или нажми отмена");
			$database->savePlace("spam_id", "2", 1);
			$database->updatePlace("spam_id", "2");
				die('ok');
			}

			if($sigen==145567397 && $tester=="институту"){
			$vk->keyboard_m(array("Отмена"));
			$vk->messageFromGroup("Напиши институт для рассылки или нажми отмена");
			$database->savePlace("spam_id", "3", 1);
			$database->updatePlace("spam_id", "3");
				die('ok');
			}
			if($sigen==145567397 && $tester=="старостам"){
			$vk->keyboard_m(array("Отмена"));
			$vk->messageFromGroup("Напиши сообщение для рассылки");
			$database->savePlace("spam_id", "6", 1);
			$database->updatePlace("spam_id", "6");
				die('ok');
			}

			if($sigen==145567397 && $tester=="не авторизованным"){
			$vk->keyboard_m(array("Отмена"));
			$vk->messageFromGroup("Напиши сообщение для рассылки или нажми отмена");
			$database->savePlace("spam_id", "4", 1);
			$database->updatePlace("spam_id", "4");
				die('ok');
			}

			if($sigen==145567397 && $tester=="всем"){
			$vk->keyboard_m(array("Отмена"));
			$vk->messageFromGroup("Напиши сообщение для рассылки или нажми отмена");
			$database->savePlace("spam_id", "5", 1);
			$database->updatePlace("spam_id", "5");
				die('ok');
			}
		}elseif(preg_match("/семестр\s(\d+)/imu",$textrass)){
			$semestr = preg_replace("/семестр\s(\d+)/imu","$1",$textrass);

			if($database->execute_query("SELECT * from cache WHERE zach = '".$row['zach']."' and semestr = '$semestr'")['stID']!=false){
				$vk->messageFromGroup( "Ваш новый семестр сохранен.\nНапиши РБ, чтобы посмотреть свои баллы");
				$database->savePlace("semEdit","0");
				$database->savePlace("semestr",$semestr);
			}else {
				$r_count = $database->execute_query("SELECT * from cache WHERE zach = '".$row['zach']."' order by semestr asc",1);
					while($c_semestr = mysqli_fetch_array($r_count,MYSQL_ASSOC))
					$r_sem[]=$c_semestr['semestr'];
				$vk->messageFromGroup( "Извини, но такого семестра нет в базе только ".implode(', ',$r_sem)." семестры");
				$database->savePlace("semEdit","0");
			}

		}elseif($row['helper']=="1"){
			$vk->messageFromGroup( "Такой команды не существует $plohSmile\nДоступные команды:\nРейтинг - просмотр рейтинга.\nСброс - cброс всех настроек.\nИзменить зачётку - изменение зачётной книжки.\nОтказ от уведомлений - Отказ от всех уведомлений о новых баллах\nПодписаться на уведомления - подписаться на уведомления группы о новых баллах\nИзменить семестр - позволяет изменить семестр\nВыключить справку - выключает ответ на каждое нешаблонное слово справкой.\nВключить справку - отменяет предыдущую команду.\nПоказывать позицию - показывать в рейтинге вашу текущую позицию,если она ниже 20 места.\nНе показывать позицию - не показывать в рейтинге вашу текущую позицию.");
		}
			break;
		}


	case $command:
	eval($commander['execute_code']);
	if($commander['message']!=false)
	$vk->messageFromGroup($commander['message']);
	die('ok');
	break;

/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/

	default:



		break;
/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/
		}
	echo ('ok');
	break;

	case 'vkpay_transaction':
	$from_to = $data->object->from_id;
	$textrass = $data->object->text;
	$sigen = $data->object->peer_id;
	$publish_date = $data->object->date;
	$vk->putChatUser($sigen, $sigen);
	$vk->messageFromGroup( print_r($data->object,true));
	break;
	case 'message_reply':
	$from_to = $data->object->from_id;
	$textrass = $data->object->text;
	$sigen = $data->object->peer_id;
	$publish_date = $data->object->date;
	$vk->putChatUser($sigen, $sigen);
	if($from_to == 145567397){
		$querydd = "SELECT vm_d.sum_ball, vm_d.numb, users.*, cache.data, cache.change_time, cache.plan, ( SELECT COUNT(zach) FROM cache WHERE plan = @plan AND groups = @groups AND semestr = @semestr ) AS COUNT, ( SELECT sum_ball FROM cache WHERE plan = @plan AND semestr = @semestr AND sum_ball > vm_d.sum_ball AND groups = vm_d.groups ORDER BY sum_ball ASC LIMIT 1 ) - vm_d.sum_ball AS difference FROM ( SELECT t.zach, t.semestr, t.sum_ball, t.`groups`, @row_n := @row_n + 1 AS numb FROM cache t, ( SELECT @row_n := 0, @id := '$sigen', @zach :=( SELECT zach FROM users WHERE id = @id ), @groups :=( SELECT `group` FROM users WHERE zach = @zach and id= @id ), @semestr :=( SELECT semestr FROM users WHERE id = @id ), @plan :=( SELECT plan FROM cache WHERE zach = @zach limit 1 ) ) r WHERE plan = @plan AND semestr = @semestr AND groups = @groups ORDER BY `t`.`sum_ball` DESC ) vm_d INNER JOIN users ON users.zach = vm_d.zach INNER JOIN cache ON cache.zach = vm_d.zach WHERE vm_d.zach = @zach AND users.id = @id AND cache.semestr = @semestr";
	$row = $database->execute_query($querydd);

	$vk->keyboard_m($key_B);
	$tester = $spam_m = $vk->chat_text = $data->object->text;
		$tester = preg_replace("/\[(.*)\]\s/","",mb_strtolower($tester));
		$tester = preg_replace("/ё/","е",$tester);
	$commander = $database->execute_query("SELECT * FROM commands WHERE command like BINARY '$tester%'");
	if($tester == "" )
	die('ok');
		if($commander['kolit_zapis']>1){
			$vk->messageFromGroup("Найдено больше 1 команды. Введите более полное название");
			die('ok');
		}
		$command = $commander['command'];
		if($command != false)
		$tester = $command;

	$user_request = $row["position"];
	if($user_request==false)
	$user_request = $database->execute_query("SELECT * FROM users WHERE id = $sigen")["position"];
	$user_zach = $row["zach"];
	$change_time = $row["change_time"];
	$user_reset = $row["reset"];
	$follow = $row["follow"];
	$user_time = $row["time"];
	$user_zamenazach = $row["user_zamena_zach"];
	$planner= $row["plan"];
	$user_textr = $row["text"];
	$kss=$row["control_sum"];
	$helper = $row["helper"];
    $group=$row["group"];
	$rassgroup = $row["rassgroup"];
	$spam_mes=$row["rasspam"];
	$pos_rait = $row["pos_rait"];
	$edSem = $row["semEdit"];
	$ids = $row["ids"];
	$key_settings = $row["keyboard"];
	$vk->key_settings = $key_settings;
	if(preg_match("/\d{6}/imu",$tester) && $database->execute_query("SELECT plan, semestr, groups FROM cache WHERE zach = '$tester'")['plan']!=""){
		$zach = $tester;
		$row = $database->execute_query("SELECT plan, semestr, groups FROM cache WHERE zach = '$zach'");
		$vk->messageFromGroup( "По вашим данным найден студент из группы ".$row['groups']."\nДля просмотра своих баллов, напишите:\nРейтинг");
		$database->savePlace("user_zamena_zach", "0");
		$database->savePlace("position", "5");
		$database->savePlace("zach", "$tester");
	die('ok');
}elseif(preg_match("/семестр\s(\d+)/imu",$textrass)){
	$semestr = preg_replace("/семестр\s(\d+)/imu","$1",$textrass);

	if($database->execute_query("SELECT * from cache WHERE zach = '".$row['zach']."' and semestr = '$semestr'")['stID']!=false){
		$vk->messageFromGroup( "Ваш новый семестр сохранен.\nНапиши РБ, чтобы посмотреть свои баллы");
		$database->savePlace("semEdit","0");
		$database->savePlace("semestr",$semestr);
	}else {
		$r_count = $database->execute_query("SELECT * from cache WHERE zach = '".$row['zach']."' order by semestr asc",1);
			while($c_semestr = mysqli_fetch_array($r_count,MYSQL_ASSOC))
			$r_sem[]=$c_semestr['semestr'];
		$vk->messageFromGroup( "Извини, но такого семестра нет в базе только ".implode(', ',$r_sem)." семестры");
		$database->savePlace("semEdit","0");
	}

}
	switch ($tester)
		{

/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------*/
case 'о человеке':
$vk->messageFromGroup($texts);
					$profile = $vk->apiVkUser("users.get",array("name_case"=>"Nom",'user_ids'=>$sigen))['response'][0];
					$info = $database->execute_query("SELECT vm_d.sum_ball, cache_plan.*, vm_d.numb, users.*, cache.data, cache.change_time, cache.plan, ( SELECT COUNT(zach) FROM cache WHERE plan = @plan AND groups = @groups AND semestr = @semestr ) AS count, ( SELECT sum_ball FROM cache WHERE plan = @plan AND semestr = @semestr AND sum_ball > vm_d.sum_ball AND groups = vm_d.groups ORDER BY sum_ball ASC LIMIT 1 ) - vm_d.sum_ball AS difference FROM ( SELECT t.zach, t.semestr, t.sum_ball, t.`groups`, @row_n := @row_n + 1 AS numb FROM cache t, ( SELECT @row_n := 0, @id := '".$profile['id']."', @zach :=( SELECT zach FROM users WHERE id = @id ), @groups :=( SELECT `group` FROM users WHERE zach = @zach and id= @id ), @semestr :=( SELECT semestr FROM users WHERE id = @id ), @plan :=( SELECT plan FROM cache WHERE zach = @zach limit 1 ) ) r WHERE plan = @plan AND semestr = @semestr AND groups = @groups ORDER BY `t`.`sum_ball` DESC ) vm_d INNER JOIN users ON users.zach = vm_d.zach INNER JOIN cache ON cache.zach = vm_d.zach INNER JOIN cache_plan ON cache.plan = cache_plan.plan_id WHERE vm_d.zach = @zach AND users.id = @id AND cache.semestr = @semestr");
					$response = $profile['first_name']." ".$profile['last_name']."\n";
					$response .= "Количество пользователей, привязанных к зачетке: ".$info['kolit_zapis']."\nИнститут: ".$info['institut']."\nЗачетка: ".$info['zach']."\nID учебного плана: ".$info['plan']."\nНазвание направления: ".$info['name_plan']."\nФорма обучения: ".$info['form']."\nГода обучения: ".$info['year']."\nГруппа: ".$info['group']."\nПозиция: ".$info['numb']."/".$info['count'].$settings."\nСсылка на диалог: https://vk.com/gim146433609?sel=".$info['id'];
					$vk->messageFromGroup($response);
					die('ok');
break;
	case $command:
	eval($commander['execute_code']);
	if($commander['message']!=false)
	$vk->messageFromGroup($commander['message']);
	die('ok');
	break;

		}
	}


	echo ('ok');
	break;


	}

?>
