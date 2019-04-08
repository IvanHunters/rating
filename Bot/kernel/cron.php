<meta charset='utf8'>
<?php
require_once 'login_rait.php';
include 'kernel.php';
$remmove=0;

$db = new database();
   while(true){
 $q0 = "SELECT * FROM cache_plan";
   $result = $db->mysqli->query($q0);

while($row=mysqli_fetch_array($result,MYSQL_ASSOC)){
 if($row['plan_id']!=false){
 $a[$row['plan_id']] = $row['semestr'];
 }
}
    $count = 1;
	$co = 1;
   foreach($a as $key=>$rdd){
       echo "\n$count--План: ".$key." Семестр: $rdd";
       //echo pcntl_getpriority ();
    $a = shell_exec("php get_data.php ".$key." $rdd > /dev/null&");/*> /dev/null&*/
	if($count%5==0||$key=="000000790")
		sleep(6);
     $count++;
   }
   sleep(3);
 shell_exec("php change_marks.php");/*> /dev/null&*/
sleep(7200);
 }
?>
