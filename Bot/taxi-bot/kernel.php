<?php
class vk {
     private $token_user, $token_group, $user_id, $chat;

    function __construct($tokenUs, $tokenGr) {
        $this->token_user=$tokenUs;
        $this->token_group=$tokenGr;
    }

    function putChatUser($ch, $us){
        $this->user_id = $us;
        $this->chat = $ch;
    }

    function get_token($why){
        echo $this->$why;
    }

		function currl($link, $param){
		  usleep(334000);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); // отключить валидацию ssl
			curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Expect:')); // это необходимо, чтобы cURL не высылал заголовок на ожидание
			if($PROXY)curl_setopt($ch, CURLOPT_PROXY, $PROXY); // Прокси сервер если есть
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param); // Данные для отправки
			//curl_setopt($ch, CURLOPT_HEADER, 0); // Не возвращать заголовки
			$data = curl_exec($ch); // Выполняем запрос
			curl_close($ch); // Закрываем соединение
			return json_decode($data,true); // Парсим JSON и отдаем

		}

		function apiVkGroup($method,$param){
			$param['access_token']= $this->token_group;
			return $this->currl("https://api.vk.com/method/$method?v=5.85", $param);
		}

		function apiVkUser($method,$param){
			$param['access_token']= $this->token_user;
			return $this->currl("https://api.vk.com/method/$method?v=5.80", $param);
		}

		function messageFromUser($user,$message){
		   return print_r($this->apiVkUser("messages.send",array('message'=>$message, 'user_id'=>$user)),true);
		}

		function messageFromGroup($message, $attachments){
			
			if($this->user_id != $this->chat){
				return print_r($this->apiVkGroup("messages.send",array('message'=>"[id".$this->user_id."|Ответ], $message", 'peer_id'=>$this->chat,/* 'keyboard'=>$this->keyboard*/ 'attachment'=>$attachments)),true);
			}
			if($this->key_settings != '1'){
				  $this->close_keyboard();
			}
			
			$mass = array('message'=>$message, 'user_id'=>$this->chat, 'keyboard'=>$this->keyboard, 'attachment'=>$attachments);
				return print_r($this->apiVkGroup("messages.send",$mass),true);
		}

		function close_keyboard(){
		   $this->keyboard = '{"buttons":[],"one_time":true}';
		}
		
		function parse_attachments($attachments){ // Функция для работы с vk api
		   return implode(",",array_map(function($attachment){
			   $id = $attachment -> type .
					 $attachment -> {$attachment -> type} -> owner_id . "_" .
					 $attachment -> {$attachment -> type} -> id;
				if($attachment -> {$attachment -> type} -> access_key)$id .= "_" .$attachment -> {$attachment -> type} -> access_key;
				  return $id;
			},$attachments));
		}

		function keyboard_m($arr_keyboard){
			 $a = array();
			$a['buttons']=array();
			foreach ($arr_keyboard as $i=>$value) {
			$a['buttons'][$i][0]=array('action'=>array('type'=>'text','payload'=>'{"button": "1"}','label'=>$value),'color'=>'default');
			}
			$this->keyboard =json_encode($a, JSON_UNESCAPED_UNICODE);
		}

		function uploadPhotos($file, $upload_url){
		   $ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $upload_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
				$response = curl_exec($ch);
				curl_close($ch);
			return(json_decode($response,true));
		}

		function get_photo($filename){
		return   new CURLFile(dirname(__FILE__).'/'.$filename, null, $filename);
		}

		function photosSave($id_album, $group_id, $server, $photos_list, $hash){
		return print_r(apiVkUser('photos.save', array('album_id'=>"$id_album", 'group_id'=>$group_id, 'server'=>$server, 'photos_list'=>$photos_list, 'hash'=>$hash)),true);
		}

		function giveFile($file, $upload_url, $id_album, $group_id){
			 $a["file1"]= get_photo($file);
				   $ar=  uploadPhotos($a, $upload_url);
				   photosSave($id_album, $group_id, $ar['server'], $ar['photos_list'], $ar['hash']);
		}

}

class database{

  function __construct(){
    $db_hostname = '127.0.0.1';
    $db_database = 'taxi';
    $db_username = 'taxi';
    $db_password = 'WDN8aWyAt9_taxi';
    $mysqli = mysqli_connect($db_hostname, $db_username, $db_password,$db_username);
    mysqli_query($mysqli,"SET character_set_client='utf8mb4'");
    mysqli_query($mysqli,"SET character_set_connection='utf8mb4'");
   mysqli_query($mysqli,"SET character_set_results='utf8mb4'");
   $this->mysqli = $mysqli;
  }

    function execute_query ($query, $flag){
		global $num;
		$result = $this->mysqli->query($query);
		
		if($num)
			return mysqli_num_rows($result);
			
		if($flag)
			return $result;
		
		$data = mysqli_fetch_array($result,MYSQL_ASSOC);
		$data['kolit_zapis'] = mysqli_num_rows($result);
			return $data;
    }
	
    function delPlace ($filer){
        global $user_id;
       $this->execute_query("UPDATE `users` SET $filer = '' WHERE id = $user_id");
    }

    function savePlace ($place, $value){
        global $user_id;
        	 $this->execute_query("UPDATE `users` SET `$place` = '$value' WHERE id = $user_id");
    }

}

class taxi {

	function __construct(){
		$this->taxiToken = "dfd3e6f9cff34065acd78791f5592ec8";
	}

	function currl($link, $param){
		usleep(334000);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); // отключить валидацию ssl
		curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Expect:')); // это необходимо, чтобы cURL не высылал заголовок на ожидание
		if($PROXY)curl_setopt($ch, CURLOPT_PROXY, $PROXY); // Прокси сервер если есть
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param); // Данные для отправки
		$data = curl_exec($ch); // Выполняем запрос
		curl_close($ch); // Закрываем соединение
		return json_decode($data,true); // Парсим JSON и отдаем
	}

	function request($method){
			return $this->currl("https://taximeter.yandex.rostaxi.org/api/$method?apikey=".$this->taxiToken,$param);
	}
  
	function registerUser(){
		  return false;
	}


}


?>
