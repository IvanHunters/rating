<?php

ini_set('error_reporting', 0);
ini_set('display_errors', 0);

    require_once 'login_rait.php';
    include "kernel.php";

    $token_Group = 'd8b141d4a58ab2afc89728a313fb1b58e46399ef9f5ce8583b3b5a19384e6093872e6d0db3592b0b7875c';
    $vk = new vk($token_Group, $token_Group);
    $vk->keyboard_m(array("Рейтинг","Подписаться на рассылку","Отказ от рассылки"));

    $req = mysql_query("SELECT change_marks.zach, change_marks.data_old, change_marks.data_new, users.id, users.rasspam as rass FROM change_marks LEFT join users on change_marks.zach = users.zach");
    $rows = mysql_num_rows($req);
    if($rows>0){
    while($res = mysql_fetch_array($req, MYSQL_ASSOC)){
        $zach= $res['zach'];
        $user_id = $res['id'];
        if($user_id != NULL){
        $text = "\nВам уведомленечка: изменились баллы!\n";
        $text.="------------------------\n";
        $data_old = json_decode($res['data_old'],true);
        $data_new = json_decode($res['data_new'],true);
        foreach($data_old['ball'] as $key=>$znach){
            if($znach!=$data_new['ball'][$key]&&$znach>=0){
                $a = 1;
               $text.= "".$data_new['predmet'][$key].": ".$data_new['ball'][$key]." бал.\n";
            }
        }
        if($a == 1){
          $text.="";
          //echo $text;
           print_r($text);
        $vk->putChatUser($user_id,$user_id);
        $vk->messageFromGroup($text);
        usleep(3600);
        }
        }
        unset($text);
        unset($a);
        mysql_query("DELETE FROM change_marks WHERE zach = '$zach'");
    }


    //echo $rows;


    }


?>
