<?php
require_once "config.php";
require_once "modules.php";
/*
* Ключ API: dfd3e6f9cff34065acd78791f5592ec8
*
* 1) Необходимо создать клиента
*	Поможет: https://taximeter.yandex.rostaxi.org/api/passager/add?apikey=<API>&ПАРАМЕТР=ЗНАЧЕНИЕ
*
*
*
*
*/

switch ($data->type)
	{

case 'confirmation':
	exit($confirmation_token);


case 'message_new':
	require_once "variable_message.php";
	switch ($tester)
		{

      case 'начать':
			if($row['kolit_zapis']=="1")
			$vk->messageFromGroup("Вы уже зарегистрированы в системе.");
			else
      	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>print_r($row,true)."Здравствуйте. Вы не зарегистрированы в системе.\nКак Вас зовут?"));
    	break;


	case 'заказать такси':
  //	$vk->messageFromGroup($a);
		die('ok');
    //$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
	break;

	case 'отменить такси':
  	$vk->apiVkGroup("messages.send",array('user_id'=>$data->object->peer_id,'message'=>"Свет выключен, господин!",'keyboard'=>$vk->keyboard));
    //$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
	break;

	default:

	break;

}
	echo ('ok');
	break;
	}

?>
