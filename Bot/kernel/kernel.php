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

function download($picture, $filename) {
$pic = curl_init($picture);
$file = fopen($filename, 'w+');
curl_setopt($pic, CURLOPT_FILE, $file);
curl_setopt($pic, CURLOPT_HEADER, 0);
curl_exec($pic);
curl_close($pic);
fclose($file);

}
}

class catcut extends vk {

     function new_share($cclink){
         $this->link = $cclink;
return  $this->currl("https://catcut.net/api/create.php",array('longurl'=>$cclink, 'id'=>2466,  'hash'=>sha1($this->link.'2466nIJ7E805cWXDESjuY58LXZrTmlpPKgxj8alPjcGV3POAx91qt3091uFQO3aQvG1R9zdQMwv8XtKhfLmEMESMZmQWUXeePrJLIelU')));

     }

	 function get_money(){
return  $this->currl("https://catcut.net/api/get.php",array('id'=>2466, 'type'=>'getadsbalance',  'hash'=>sha1('2466getadsbalancenIJ7E805cWXDESjuY58LXZrTmlpPKgxj8alPjcGV3POAx91qt3091uFQO3aQvG1R9zdQMwv8XtKhfLmEMESMZmQWUXeePrJLIelU')));
}

}

class database{

  function __construct(){
    $db_hostname = '127.0.0.1';
    $db_database = 'rating';
    $db_username = 'rating';
    $db_password = 'WDN8aWyAt9_volsu';
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
$a = mysqli_fetch_array($result,MYSQL_ASSOC);
$a['kolit_zapis'] = mysqli_num_rows($result);
     return $a;
    }

    function insert ($table, $place, $value){
      return  $this->execute_query("INSERT INTO `$table` $place VALUES $value");
    }

    function select ($place, $table, $where){
        return  $this->execute_query("SELECT `$place` FROM $table WHERE $where");
    }

    function update ($table, $set, $where){
        return  $this->execute_query("update $table Set $set where $where");
    }

    function command($com,$f){
    $result = mysql_query($com);
    if($f != 1)
     $row = mysql_fetch_array($result,MYSQL_ASSOC);
     else
     $row = mysql_num_rows($result);
     return $row;
    }

    function resetUser (){
        global $sigen;

        $this->execute_query("DELETE FROM `users` WHERE `users`.`id` = $sigen");
        $this->execute_query("INSERT INTO `users` (`id`,`position`) VALUES ('$sigen','1')");
    }

    function delPlace ($filer){
        global $sigen;

       $this->execute_query("UPDATE `users` SET $filer = '' WHERE id = $sigen");
    }

    function savePlace ($place, $value, $flag){
        global $sigen,$signess,$zamenall;
if($flag==1)
	return $this->execute_query("INSERT INTO `spam` (`spam_id`, `whom`, `spam_text`) VALUES ('1', '', '');");
        if($zamenall===true){
            if($place == "zamena_id"){
                $this->execute_query("UPDATE `users` SET `$place` = '$value' WHERE id = $signess");
            }else{
               $this->execute_query("UPDATE `users` SET `$place` = '$value' WHERE id = $sigen");
            }
        }else{
        	 $this->execute_query("UPDATE `users` SET `$place` = '$value' WHERE id = $sigen");
        }
    }

	function updatePlace($place, $value, $flag){
		return $this->execute_query("UPDATE `spam` SET `$place` = '$value'");
	}

    function showbd (){
        global $sigen;
        return  $this->execute_query("SELECT * FROM `users` WHERE `id` = $sigen");
    }

}

     function ex($n){
            $res= preg_replace("/Зачет с оценкой/",'Зач.с.Оц.',$n);
            $res= preg_replace("/Зачет/",'Зач.',$res);
            $res= preg_replace("/Дифференцированный зачет/",' Диф.Зач.',$res);
            $res= preg_replace("/Экзамен/",'Экз.',$res);
            return $res;
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

function parse_doc($atta){ // Функция для работы с vk api
return implode(",",array_map(function($atta){
$id = $atta -> {$atta -> type} -> link_mp3;
if($attachment -> {$attachment -> type} -> access_key)$id;
return $id;
},$atta));
}
function generateRandomSelection($min, $max, $count){
$result=array();
if($min>$max) return $result;
$count=min(max($count,0),$max-$min+1);
while(count($result)<$count) {
$value=rand($min,$max-count($result));
foreach($result as $used) if($used<=$value) $value++; else break;
$result[]=dechex($value);
sort($result);
}

shuffle($result);
return $result;
}
function recognize($file, $keys) {
$uuid=generateRandomSelection(0,30,64);
$uuid=implode($uuid); $uuid=substr($uuid,1,32);
$curl = curl_init();
$url = 'https://asr.yandex.net/asr_xml?'.http_build_query(array(
  'key'=>$keys,
  'uuid' => $uuid,
  'topic' => 'queries',
  'lang'=>'ru-RU'
));
curl_setopt($curl, CURLOPT_URL, $url);
$data=file_get_contents(realpath($file));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: audio/x-mpeg-3'));
curl_setopt($curl, CURLOPT_VERBOSE, true);
$response = curl_exec($curl);
$err = curl_errno($curl);
curl_close($curl);
if ($err)
throw new exception("curl err $err");
return preg_replace("/^<variant(.*)>(.*)<\/variant>$/",'$1-$2-$3',$response);
//echo $response;
}
class taxi extends database{

  function __construct(){
    $this->taxiToken = "dfd3e6f9cff34065acd78791f5592ec8";
  }

  function currl($link){
    usleep(334000);
  	$ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, $link);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
  	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); // отключить валидацию ssl
  	curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
  	curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Expect:')); // это необходимо, чтобы cURL не высылал заголовок на ожидание
  	//curl_setopt($ch, CURLOPT_HEADER, 0); // Не возвращать заголовки
  	$data = curl_exec($ch); // Выполняем запрос
  	curl_close($ch); // Закрываем соединение
  	return json_decode($data,true); // Парсим JSON и отдаем
  }

  function request($method){
        return $this->currl("https://taximeter.yandex.rostaxi.org/api/$method?apikey=".$this->taxiToken);
  }


}

	function beeEncrypt($input){
$chars = "жъЖЪ";
$a = base64_encode($input);
$res = "";
for ($i = 0; $i < mb_strlen($a); $i++) {
$b = $a[$i];
$bintxt = "";
$c = ord($b);
for ($j = 0; $j < 4; $j++) {
$bintxt = mb_substr($chars,$c%4,1).$bintxt;
$c /= 4;
}
$res = $res.$bintxt;
}
return $res;
}
function beeDecrypt($input){
$chars = "жъЖЪ";
$b64 = "";
for ($i = 0; $i < mb_strlen($input) - 3; $i = $i + 4) {
$bintxt = mb_substr($input, $i, 4);
$c = 0;
for ($j = 0; $j < 4; $j++) {
$c = $c * 4 + max(0, mb_strpos($chars,mb_substr($bintxt,$j,1)));
}
$b64 = $b64.chr($c);
}
return base64_decode($b64);
}
?>
