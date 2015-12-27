<?php

	if(!isset($_COOKIE['loggin']) || !isset($_POST['list']) || !isset($_POST['image']) || !isset($_POST['year'])){
		echo '0';
		exit(0);
	}
	
	include '../mysql_connection.php';
	
	$conn = get_connection();
	
	$r = $conn->query('update lists set list=\''.str_replace("'","\'",$_POST['list']).'\' , image=\''.$_POST['image'].'\', year='.$_POST['year'].' where user='.$_COOKIE['loggin'].';');
	if($r) {echo '1'; exit(0);} else {
		$r = $conn->query('insert into lists(user,list,image,year) values ('.$_COOKIE['loggin'].',\''.str_replace("'","\'",$_POST['list']).'\' , image=\''.$_POST['image'].'\', year='.$_POST['year'].');');
		if($r) echo '1'; else echo '0';
	}
	
?>