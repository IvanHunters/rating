<?php
include "kernel/kernel.php";
$database = new database();
$row = $database->execute_query("SELECT * FROM temperature where id = '1'");
$token_Group = '74e71d372a0c596646967e205707528aa2c5f0164d91018e701bfe517f05636cc4d0354f23e54feadaf96';
$token_User  = '22a3ee6e42d43f779282c0a94800d1d878a6f6334c10a328982b4e5094aec94c7d9578dca19a533c13bbc';
$vk = new vk($token_User, $token_Group);
$temp = $_GET['a'];
if($temp > 27&&$row['temp'] > 27){
 //file_get_contents("https://sms.ru/sms/send?api_id=BE2C780F-44FA-A398-F3C9-BC9F6DAB49A7&to=79275196095&msg=".urlencode("Т>35")."&json=1");
 //$vk->apiVkGroup("messages.send",array('user_id'=>145567397,'message'=>"Включи вентилятор, дядь.\nТемпература ".round($row['temp'],0)));
 //$database->execute_query("UPDATE relays SET mode = '1' where id = '1'");
 echo "dd";
}
 if(round($temp,0) < 19&&round($row['temp'],0)>19){
 //file_get_contents("https://sms.ru/sms/send?api_id=BE2C780F-44FA-A398-F3C9-BC9F6DAB49A7&to=79275196095&msg=".urlencode("Т<15")."&json=1");
 $vk->apiVkGroup("messages.send",array('user_id'=>145567397,'message'=>"Включи обогреватель, дядь.\nТемпература ".round($row['temp'],0)));
 $database->execute_query("UPDATE relays SET mode = '1' where id = '1'");
 }
$time = time();
if($row['temp']!=$temp){
$database->execute_query("UPDATE temperature SET temp = '$temp', time='$time' WHERE id= '1'");
$database->execute_query("INSERT INTO temperature (`temp`,`time`) values ('$temp','$time')");
}else{
$database->execute_query("UPDATE temperature SET time='$time' WHERE id= '1'");
}
?>