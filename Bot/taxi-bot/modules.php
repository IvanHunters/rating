<?php
$database = new database();
$taxiR = new taxi();
$vk = new vk($token_User, $token_Group);
$data = json_decode(file_get_contents('php://input'));


?>