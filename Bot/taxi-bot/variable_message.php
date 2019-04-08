<?php
	$chat=$data->object->peer_id;
	$active = $data->object->action->type;
	$sigen = $data->object->from_id;
	$group = $data->group_id;
	$id_mes= $data->object->id;
	$dbRequest = "SELECT * from users where id = '".$data->object->peer_id."'";
	$row = $database->execute_query($dbRequest);
	$tester = $spam_m = $data->object->text;
	$vk->putChatUser($data->object->peer_id, $data->object->from_id);
	$vk->keyboard_m(array("Заказать такси","Отменить такси","Мои заказы"));
	$tester = preg_replace("/\[(.*)\]\s/","",mb_strtolower($tester));
	$ata = $data->object->attachments;
	$textrass = $data->object->text;
	$publish_date = $data->object->date;
?>