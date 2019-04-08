<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
class rating{



  function get_rating($flag_null){
    global $vk, $database,$row, $sigen;
    $database->execute_query("UPDATE `users` SET `busy` = '1' WHERE id = $sigen");
    $user_request = $row["position"];
    $data = $row['data'];
    $change_time = $row['change_time'];
    $json= json_decode($data, true);
    $zach=$row["zach"];
    $position = $row["position"];
    $plan = $row["plan"];
    $data = $row["data"];
    $groups = $row["groups"];
    $time_chache = $row["time"];
    $json = json_decode($data,true);
    $json["position"] =	$row['numb'];
    $difference = $row['difference'];
    $sum_ball = $row['sum_ball'];
    $quontityPeopleFromGroup = $row['COUNT'];
    $json["position"] .= " из ".$quontityPeopleFromGroup;
    $massiv = ["😏", "😊", "😁", "😄", "😉", "😜", "🙃", "🙂", "😎", "😌", "😇", "🤗", "😋", "😃", "😈", "👻", "🤓", "😛", "😼", "😸", "😺", "🌝", "🌚"];
    $masPlohSmile = ["😐", "😒", "😔", "😕", "🤔", "☹", "😯", "😦", "🙄", "😶"];
    $mas_good_words = ["Молодец","Так держать","Поздравляю"];
    $randomn_good_words = rand(0, 2);
    $mas_negod_words = ["Мне грустно, но","Прости, но","Мне жаль, но"];
    $randomn_negod_words = rand(0, 2);
    $randomnPlohSmile = rand(0, 9);
    $good_fr=$mas_good_words[$randomn_good_words];
    $negod_fr=$mas_negod_words[$randomn_negod_words];
    $plohSmile = $masPlohSmile[$randomnPlohSmile];
    $randomn = rand(0, 22);
    $smile = $massiv[$randomn];


    if ($user_request == false||$user_request=="1"){
      $vk->keyboard_m(array("Справка"));
      $vk->messageFromGroup( "Вы у нас впервые $smile. Напишите номер вашей  зачетной книги или номер студенческого билета.");
      $database->savePlace("position", "1");
    }elseif($user_request == "5"){

         if($row['plan']==""){
             $vk->keyboard_m(array("Сброс"));
             $vk->messageFromGroup( "Возникла ошибка #17\nВозможно, вашей зачетной книги не существует.\nНапишите сброс и заново все настройте\nЕсли не помогло, напишите vk.com/x_dev");
             $database->savePlace("error", "1");
			  $quontity = $row['request_r']+1;
      $database->execute_query("UPDATE `users` SET `request_r` = ".$quontity." WHERE id = $sigen");
      $database->execute_query("UPDATE `users` SET `busy` = 0 WHERE id = $sigen");
             die('ok');
         }

         if($json["position"]<20&&$json["position"]>10){
                 $title_text = "\nУ тебя есть к чему стремиться, ты на ".$json["position"]." месте $smile";

         }elseif($json["position"]<=10&&$json["position"]>5){
                 $title_text = "\nТак держать, ты на ".$json["position"]." месте $smile";

         }elseif($json["position"]<=5){
                 $title_text = "\n$good_fr, ты на ".$json["position"]." месте $smile";

         }else{
                 if($row['rasspam']=="1"){
                 $title_text = "\n$negod_fr ты на ".$json["position"]." месте $plohSmile";

                 }else{
					$vk->keyboard_m(array("Все команды","Подписаться на уведомления"));
                 $title_text = "\n$negod_fr ты ниже 20 места $plohSmile\nДля показа позиции напиши: \nПодписаться на уведомления\nИ перейди по ссылке";
                 }
         }

        if($difference>0)
         $razn= "\nРазница с человеком выше: ".$difference." бал.";
        elseif($difference==0)
         $razn = "\nРазницы с человеком выше в сумме баллов нет";
         $res_text = "$title_text\n".$razn;
         if($row['rasspam']=="0"){
           $vk->keyboard_m(array("Все команды","Подписаться на уведомления"));
           $res_text = "\nКоманда выключена.\nДля того, чтобы ее включить, напиши: \nПодписаться на уведомления\nИ перейди по ссылке";
         }
       $sob_text="Ваша группа: ".$json["group"].$res_text."\n";
       $count_predmet = count($json["predmet"]);

              if($vk->apiVkUser("groups.isMember", array("group_id"=>'146433609', 'user_id' => $sigen))['response']=='0'){
              $not_podps="Вы не подписаны на группу и поэтому будет выведено всего 4 предмета\n\n";
              $count_predmet=4;
            }
       for($i=0;$i<$count_predmet;$i++){
         if($flag_null == "1"&&array_sum($json["ball"])!=0){
		 if($row['rasspam']=="0"){
			 $vk->keyboard_m(array("Все команды","Подписаться на уведомления"));
			 $vk->messageFromGroup( "Показ позиции выключен.\nДля ее показа напиши: \nПодписаться на уведомления\nИ перейди по ссылке");
			  $quontity = $row['request_r']+1;
      $database->execute_query("UPDATE `users` SET `request_r` = ".$quontity." WHERE id = $sigen");
      $database->execute_query("UPDATE `users` SET `busy` = 0 WHERE id = $sigen");
			 die('ok');
		 }
         if($json["ball"][$i]!=0)
         $sob_text="$sob_text\n".$json["predmet"][$i]." ".ex($json["control"][$i]).": ".$json["ball"][$i]." бал.";
       }else{
         $sob_text="$sob_text\n".$json["predmet"][$i]." ".ex($json["control"][$i]).": ".$json["ball"][$i]." бал.";
       }
     }
      $vk->messageFromGroup( $not_podps."Данные были обновлены: $change_time\n".$sob_text);
      $quontity = $row['request_r']+1;
      $database->execute_query("UPDATE `users` SET `request_r` = ".$quontity." WHERE id = $sigen");
      $database->execute_query("UPDATE `users` SET `busy` = 0 WHERE id = $sigen");
      }
  }

}

?>
