<?php

	if(!isset($_COOKIE['loggin']) || !isset($_POST['name']) || !isset($_POST['email'])){
		exit(0);
	}
	
	include '../mysql_connection.php';
	
	$conn = get_connection();
	
	$r = $conn->query('update users set name=\''.base64_encode($_POST['name']).'\',email=\''.base64_encode($_POST['email']).'\' where id='.$_COOKIE['loggin'].';');
	
?>