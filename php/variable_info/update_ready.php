<?php

	if(!isset($_COOKIE['loggin']) || !isset($_POST['ready'])){
		echo '0';
		exit(0);
	}
	
	include '../mysql_connection.php';
	
	$conn = get_connection();
	
	$r = $conn->query('update users set ready='.$_POST['ready'].' where id='.$_COOKIE['loggin'].';');
	if($r) echo '1'; else echo '0';
	
?>