
<?php

ini_set('error_reporting', 1);
ini_set('display_errors', 1);
include "kernel/kernel.php";
$database = new database();
$confirmation_token = '2cbf6c44';

$token_Group = '74e71d372a0c596646967e205707528aa2c5f0164d91018e701bfe517f05636cc4d0354f23e54feadaf96';
$token_User  = '22a3ee6e42d43f779282c0a94800d1d878a6f6334c10a328982b4e5094aec94c7d9578dca19a533c13bbc';


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
	$id_mes= $data->object->id;
	$tester = $spam_m = $data->object->text;
	$key = '9192297c-1228-495b-b5ae-127cbea024d9';
  $vk->putChatUser($data->object->peer_id, $data->object->from_id);
	$vk->keyboard_m(array("Температура","Включить свет","Выключить свет","Включить вентилятор","Выключить вентилятор","Сос"));
  $tester = preg_replace("/\[(.*)\]\s/","",mb_strtolower($tester));
	$ata = parse_doc($data->object->attachments);
	$vk->download($ata, "test.mp3");
	$textrass = $data->object->text;
	$publish_date = $data->object->date;
	if($data->object->fwd_messages[0]->text)
		$textrass = $data->object->fwd_messages[0]->text;
	switch ($tester)
		{

      case 'начать':
      	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Жду приказаний, господин!",'keyboard'=>$vk->keyboard));
    	break;


	case 'включить свет':
  	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Свет включен, господин!",'keyboard'=>$vk->keyboard));
    $vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
	break;

	case 'выключить свет':
  	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Свет выключен, господин!",'keyboard'=>$vk->keyboard));
    $vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
	break;

	case 'сос':
		$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Режим сос!",'keyboard'=>$vk->keyboard));
		$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
		$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
		$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
		$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
	break;

	case 'sos':
  	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Режим сос!",'keyboard'=>$vk->keyboard));
		$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
		$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
		$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
		$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
	break;

	case 'включить вентилятор':
	$database->execute_query("UPDATE relays SET mode = '1' where id = '1'");
	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Вентилятор включен",'keyboard'=>$vk->keyboard));
	break;

	case 'выключить вентилятор':
	$database->execute_query("UPDATE relays SET mode = '0' where id = '1'");
	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Вентилятор выключен",'keyboard'=>$vk->keyboard));
	break;
	
	case 'температура':
	$temp = $database->execute_query("SELECT * from temperature where id=1");
	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Температура: ".$temp['temp']."°С",'keyboard'=>$vk->keyboard));
	break;

	default:
	if(preg_match("/([вкл|выкл|включить|выключить])\s([0-1])\s(\d+)/imu",$tester)){
	$string = preg_replace("/([вкл|выкл|включить|выключить])\s([0-1])\s(\d+)/imu",'$1-$2-$3',$tester);
	$array = explode("-",$string);
	system("php worker.php ".$array[0]." ".$array[1]." ".$array[2]." > /dev/null&");
	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>$array[0]." прибор ".$array[1]." через ".$array[2]." минут",'keyboard'=>$vk->keyboard));
}else{
	$string = preg_replace("/^(.*)<variant(.*)>(.*)<\/variant>(.*)$/s",'$3',recognize("test.mp3", $key));
	if(preg_match("/(.*)\s(.*)\s(через)\s(.*)\s(.*)/",$string)){
	$string = preg_replace("/(.*)\s(.*)\s(.*)\s(.*)\s(.*)/",'$1-$2-$3-$4-$5',$string);
	$string = preg_replace("/свет/",'0',$string);
	$string = preg_replace("/вентилятор[а-Я]?/",'1',$string);
	$string = preg_replace("/включить|включи/",'вкл',$string);
	$string = preg_replace("/выключить|выключи/",'выкл',$string);
	$string = preg_replace("/вентилятор/",'1',$string);
	$array = explode("-",$string);
	system("php worker.php ".$array[0]." ".$array[1]." ".$array[3]." > /dev/null&");
	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>$string,'keyboard'=>$vk->keyboard));
	}
	if(preg_match("/(.*)\s(.*)\s(на)\s(.*)\s(.*)/",$string)){
	$string = preg_replace("/(.*)\s(.*)\s(.*)\s(.*)\s(.*)/",'$1-$2-$3-$4-$5',$string);
	$string = preg_replace("/свет/",'0',$string);
	$string = preg_replace("/вентилятор[а-Я]?/",'1',$string);
	$string = preg_replace("/включить|включи/",'вкл',$string);
	$string = preg_replace("/выключить|выключи/",'выкл',$string);
	$string = preg_replace("/вентилятор/",'1',$string);
	$array = explode("-",$string);
	system("php worker.php ".$array[0]." ".$array[1]." 0 ".$array[3]." > /dev/null&");
	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>$string,'keyboard'=>$vk->keyboard));
	}
		if(preg_match("/ж([ж|Ж|Ъ|ъ])?/",$textrass)){
		$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"BeeCrypt: ".beeDecrypt($textrass),'keyboard'=>$vk->keyboard));
	}else{
	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>beeEncrypt($textrass),'keyboard'=>$vk->keyboard));
	}
	
}

	break;

}
	echo ('ok');
	break;
	}

?>
