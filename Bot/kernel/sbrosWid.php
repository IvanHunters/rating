<?php
require_once 'login_rait.php';
$remmove=0;

//$token_User = 'fa5a8dcef2c9bd5574525b94acff4fdd000f80feb74e9dad2af75bd372ac32a43e6b8c37ea23d78201102'; //Ключ доступа пользователя
     $query = "UPDATE users set request_r =0 where request_r!=0";
    $result = mysql_query($query);
?>
