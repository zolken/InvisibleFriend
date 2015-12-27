<?php

	if(!isset($_COOKIE['loggin'])) exit(0);
	
	header("Content-type: application/json; charset=utf-8");
	
	include '../mysql_connection.php';
	
	$json = get_user_data($_COOKIE['loggin']);
	
	if(!is_null($json)) echo $json;

?>