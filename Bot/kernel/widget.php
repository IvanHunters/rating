<?php
require_once 'login_rait.php';
$remmove=0;

//$token_User = 'fa5a8dcef2c9bd5574525b94acff4fdd000f80feb74e9dad2af75bd372ac32a43e6b8c37ea23d78201102'; //Ключ доступа пользователя
while(true){
  unset($a);
		    $query = "SELECT id,position FROM users WHERE position =5";
    $result = mysql_query($query);
     $rows = mysql_num_rows($result);
while($row=mysql_fetch_array($result,MYSQL_ASSOC))
{
$a[] = $row['id'];
}
$vk = count($a);
unset($a);
$a = '';
		    $query = "SELECT id,position FROM users WHERE rasspam = 1 ";
    $result = mysql_query($query);
     $rows = mysql_num_rows($result);
while($row=mysql_fetch_array($result,MYSQL_ASSOC))
{
$a[] = $row['id'];
}
$nnu = count($a);
if(isset($_GET["sbros"])){
     $query = "UPDATE users set request_r =0 where request_r!=0";
    $result = mysql_query($query);
}
  $query = "SELECT `request_r`,COUNT(`id`) FROM `users` where request_r != 0 group by `request_r`";
    $result = mysql_query($query);
     $rows = mysql_num_rows($result);
     $count_zapr = '';
while($row=mysql_fetch_array($result,MYSQL_ASSOC))
{
$count_zapr += ($row['request_r']*$row['COUNT(`id`)']);
}

$query = "SELECT cache_plan.institut as institut, COUNT(cache_plan.name_plan) as kol FROM users INNER JOIN (SELECT DISTINCT zach, plan from cache) as cache on users.zach=cache.zach INNER JOIN cache_plan on cache.plan = cache_plan.plan_id GROUP BY cache_plan.institut ORDER BY kol DESC";
 $result = mysql_query($query);
 $ca=1;
   $top='';
   $ca = '';
while($ca<4)
{
  $row=mysql_fetch_array($result,MYSQL_ASSOC);
$top = $top."\\n".$row["institut"].": ".$row["kol"];  //group
$ca++;
}
$query = "SELECT `request_r` FROM `users` where request_r != 0 ";
    $result = mysql_query($query);
     $rosfsdfsdws = mysql_num_rows($result);
//$vk=$new["max_user_vk"];
$count = "Пользователей: $vk\\nНаписало сегодня боту: $rosfsdfsdws\\nКоличество запросов к рейтингу сегодня: $count_zapr\\n________________________"."$top";
$code = 'return {
      "title": "('.date("H:i:s", time()+7200).') Количество авторизованных у бота пользователей:",
      "title_url": "https://vk.me/raiting_abitur",
      "text": "'.$count.'"
};';
/*{
    "title": "('.date("H:i:s").') Количество авторизованных у бота пользователей:",
    "text": "'.$count.'"
};*/
$a = file_get_contents("https://api.vk.com/method/appWidgets.update?access_token=4705e2c4ea376be71b39c30c18bc72deb85306ebed03b29020aa67bf6aa4b5c50d39e9c19b50d9b627473&code=".urlencode($code)."&type=text&v=1");
echo $a;
echo date("H:i:s", time()+7200);
sleep(180);
unset($this);
}
?>
