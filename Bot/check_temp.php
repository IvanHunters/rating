<?php
include "kernel/kernel.php";
$database = new database();
$token_Group = '74e71d372a0c596646967e205707528aa2c5f0164d91018e701bfe517f05636cc4d0354f23e54feadaf96';
$token_User  = '22a3ee6e42d43f779282c0a94800d1d878a6f6334c10a328982b4e5094aec94c7d9578dca19a533c13bbc';
$vk = new vk($token_User, $token_Group);
while(true){
$row = $database->execute_query("SELECT * FROM temperature where id = '1'");
if(time()-$row['time']>300)
$vk->apiVkGroup("messages.send",array('user_id'=>145567397,'message'=>"Внимание, парниша!!!\nАрдуинка заглючила\nВремя сейчас: ".time()."\nВремя обновления: ".$row['time']));
sleep(300);
}
?>
