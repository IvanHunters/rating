<?php
include "kernel/kernel.php";
$database = new database();
$confirmation_token = '3a000cfa';
$token_Group = 'b974bc94fd1635d3a4c28a3efaf613107eee2135e93e1fb65defa92afb08d4241fa935d3514feb2620431';
$token_User  = 'b974bc94fd1635d3a4c28a3efaf613107eee2135e93e1fb65defa92afb08d4241fa935d3514feb2620431';
$vk = new vk($token_User, $token_Group);
$data = json_decode(file_get_contents('php://input'));
switch ($data->type)
	{
case 'confirmation':
	exit($confirmation_token);
case 'message_new':
	$chat=$data->object->peer_id;
	$active = $data->object->action->type;
	$sigen = $data->object->from_id;
	$group = $data->group_id;
	$numb_z= md5(time().$sigen.time());
	$id_mes= $data->object->id;
	$tester = $spam_m = $data->object->text;
  $vk->putChatUser($data->object->peer_id, $data->object->from_id);
	$vk->keyboard_m(array("Купить коины","Продать коины","Обменять коины","Мои заявки"));
  $tester = preg_replace("/\[(.*)\]\s/","",mb_strtolower($tester));
	$textrass = $data->object->text;
	$publish_date = $data->object->date;
	if($data->object->fwd_messages[0]->text)
		$textrass = $data->object->fwd_messages[0]->text;
	switch ($tester)
		{

    case 'начать':
    $vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Рад видеть тебя в нашей группе.\nВыбери, что тебя интересует",'keyboard'=>$vk->keyboard));
die('ok');
	break;

	case 'назад':
    $vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Вы вышли в меню",'keyboard'=>$vk->keyboard));
die('ok');
	break;

	case 'купить коины':
	$vk->keyboard_m(array("Назад","Купить 3кк","Купить 5кк"));
  	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Наш курс для покупки:\n3кк коинов - 200р\n5кк коинов - 400р",'keyboard'=>$vk->keyboard));
die('ok');
	break;

	case 'продать коины':
	$vk->keyboard_m(array("Назад","Продать 3кк","Продать 5кк"));
  	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Наш курс для продажи:\n3кк коинов - 100р\n5кк коинов - 200р",'keyboard'=>$vk->keyboard));
die('ok');
	break;

	case 'обменять коины':
		$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Один набор стикеров стоит 1кк коин.\nЕсли готов, напиши:\nОбмен <1>\nГде <1> - название стикерпака",'keyboard'=>$vk->keyboard));
die('ok');
	break;

	case 'мои заявки':
	$tick = $database->execute_query("SELECT * FROM tickets where id = $sigen");
		if($tick['kolit_zapis']=="1"){
  	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Вот твои заявки: \nНомер: ".$tick['num']."\nCоздана ".date("d-m-Y в H:i",$tick['time'])."\nРасшифровка: ".$tick['event']." ".$tick['count']."\nСтатус: ".$tick['status'],'keyboard'=>$vk->keyboard));
	}else{
		$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"У тебя нет заявок",'keyboard'=>$vk->keyboard));
	}
	die('ok');
	break;

	default:
	   $tick = $database->execute_query("SELECT * FROM tickets where id = $sigen");
		if(preg_match("/обмен\s(.*)/imu",$textrass,$m)){
			if($tick['kolit_zapis']=="1"){
			$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"У тебя активная заявка: ".$tick['num']."\nДля продолжения операций дождсь ответа",'keyboard'=>$vk->keyboard));
			die('ok');
			}
			$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Заявка оформлена.\nНомер заявки: $numb_z\nПереведи 1кк коинов на страницу: \nhttps://vk.com/id539627842\nИ пришли сюда скриншот.\nВо избижания задежек переводите только указанное количество коинов",'keyboard'=>$vk->keyboard));
				$database->execute_query("INSERT INTO tickets (id,num,time,event,count) values ('$sigen','$numb_z','".time()."','обменять','1000000')");
		}elseif(preg_match("/(.*)\s(\d+)к/",$textrass,$m)){
			if($tick['kolit_zapis']=="1"){
			$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"У тебя активная заявка: ".$tick['num']."\nДля продолжения операций дождсь ответа",'keyboard'=>$vk->keyboard));
			die('ok');
			}
		if($m[1]=="Купить"){
			switch($m[2]){
				case '3':
				$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Заявка оформлена.\nНомер заявки: $numb_z\nПереведи 500р на\nкиви: +79996288989\n[в комментариях укажи номер заявки, это важно]\nИ в течении получаса я переведу тебе коины",'keyboard'=>$vk->keyboard));
				$database->execute_query("INSERT INTO tickets (id,num,time,event,count) values ('$sigen','$numb_z','".time()."','купить','3000000')");
			die('ok');
				break;

				case '5':
				$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Заявка оформлена.\nНомер заявки: $numb_z\nПереведи 500р на\nкиви: +79996288989\n[в комментариях укажи номер заявки, это важно]\nИ в течении получаса я переведу тебе коины",'keyboard'=>$vk->keyboard));
				$database->execute_query("INSERT INTO tickets (id,num,time,event,count) values ('$sigen','$numb_z','".time()."','купить','5000000')");
			die('ok');
				break;
			}
		}elseif($m[1]=="Продать"){
			switch($m[2]){
				case '3':
				$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Заявка оформлена.\nНомер заявки: $numb_z\nПереведи 3кк коинов на страницу: \nhttps://vk.com/id539627842\nИ пришли сюда скриншот.\nВо избижания задежек переводите только указанное количество коинов",'keyboard'=>$vk->keyboard));
				$database->execute_query("INSERT INTO tickets (id,num,time,event,count) values ('$sigen','$numb_z','".time()."','продать','3000000')");
			die('ok');
				break;

				case '5':
				$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Заявка оформлена.\nНомер заявки: $numb_z\nПереведи 5кк коинов на страницу: \nhttps://vk.com/id539627842\nИ пришли сюда скриншот.\nВо избижания задежек переводите только указанное количество коинов",'keyboard'=>$vk->keyboard));
				$database->execute_query("INSERT INTO tickets (id,num,time,event,count) values ('$sigen','$numb_z','".time()."','продать','5000000')");
			die('ok');
				break;
			}
		}
		die('ok');
	}else{
		$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Извини, но я не знаю такой команды.",'keyboard'=>$vk->keyboard));
	}
	die ('ok');
die('ok');
	break;
	}
die('ok');
	break;
	}
?>
