<?php
 $db_hostname = '127.0.0.1';
 $db_database = 'rating';
 $db_username = 'rating';
 $db_password = 'WDN8aWyAt9_volsu';
 $db_server = mysql_connect($db_hostname, $db_username, $db_password);
 mysql_query("SET character_set_client='utf8'", $db_server);
 mysql_query("SET character_set_connection='utf8'", $db_server);
 mysql_query("SET character_set_results='utf8'", $db_server);
if (!$db_server) die("Невозможно подключиться к MySQL: " . mysql_error());
mysql_select_db($db_database, $db_server)
or die("Невозможно выбрать базу данных: " . mysql_error());
?>
