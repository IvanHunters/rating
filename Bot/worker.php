<?php
include "kernel/kernel.php";
$database = new database();
$token_Group = '74e71d372a0c596646967e205707528aa2c5f0164d91018e701bfe517f05636cc4d0354f23e54feadaf96';
$token_User  = '22a3ee6e42d43f779282c0a94800d1d878a6f6334c10a328982b4e5094aec94c7d9578dca19a533c13bbc';
$vk = new vk($token_User, $token_Group);
sleep($argv[3]*60);
if($argv[1] == "вкл"){
	if($argv[2] == "0"){
		if($argv[4]!=false){
			$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
			sleep($argv[4]*60);
			$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
		}else{
			$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
		}
	}else{
		if($argv[4]!=false){
			$database->execute_query("UPDATE relays SET mode='1' WHERE id='1'");
			sleep($argv[4]*60);
			$database->execute_query("UPDATE relays SET mode='0' WHERE id='1'");
		}else{
			$database->execute_query("UPDATE relays SET mode='1' WHERE id='1'");
		}
	}
}else{
	if($argv[2] == "0"){
		if($argv[4]!=false){
			$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
			sleep($argv[4]*60);
			$vk->currl("https://maker.ifttt.com/trigger/lamp_on/with/key/dWYBZC9DLQLiifnB7BSWqw");
		}else{
			$vk->currl("https://maker.ifttt.com/trigger/lamp_off/with/key/dWYBZC9DLQLiifnB7BSWqw");
		}
	}else{
		if($argv[4]!=false){
			$database->execute_query("UPDATE relays SET mode='0' WHERE id='1'");
			sleep($argv[4]*60);
			$database->execute_query("UPDATE relays SET mode='1' WHERE id='1'");
		}else{
			$database->execute_query("UPDATE relays SET mode='0' WHERE id='1'");
		}
	}
}
?>