<?php

	if(!isset($_COOKIE['loggin']) || !isset($_POST['excluded']) || !isset($_POST['year'])){
		echo '0';
		exit(0);
	}
	
	include '../mysql_connection.php';
	
	$conn = get_connection();
	
	$r = $conn->query('update exclusions set excluded=\''.$_POST['excluded'].'\' , year='.$_POST['year'].' where user='.$_COOKIE['loggin'].';');
	if($r) {echo '1'; exit(0);} else {
		$r = $conn->query('insert into exclusions(user,excluded,year) values ('.$_COOKIE['loggin'].',\''.$_POST['excluded'].'\' , year='.$_POST['year'].');');
		if($r) echo '1'; else echo '0';
	}
	
?>