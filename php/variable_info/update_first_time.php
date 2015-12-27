<?php

	if(!isset($_COOKIE['loggin'])){
		exit(0);
	}
	
	include '../mysql_connection.php';
	
	$conn = get_connection();
	
	$r = $conn->query('update users set first_time=0 where id='.$_COOKIE['loggin'].';');
	
?>