<?php

    include '../mysql_connection.php';
    
	$conn = get_connection();
    $query = "select id,status from users where";
    $query .= " email='".base64_encode($_POST['email'])."'";
    $query .= " and password='".crypt($_POST['password'],'$5$rounds=5000$universe'.$_POST['universe'].'$')."'";
    $query .= " and universe=".$_POST['universe'].";";

	$result = $conn->query($query);

	if($result != FALSE && $result->num_rows > 0){
        $row = $result->fetch_array(MYSQLI_ASSOC);
		if($row['status']==0) echo '{"status":2,"id":'.$row['id'].'}'; else {
			setcookie('loggin',$row['id'],time()+3600,'/');
			setcookie('password',crypt($_POST['password'],'$5$rounds=5000$universe'.$_POST['universe'].'$'),time()+3600,'/');
			if ($_POST['universe'] == 1) {echo '{"status":1}';} else {echo '{"status":0}';}
		}
	} else{
        echo '{"status":-1}';
    }
    $conn->close();
?>