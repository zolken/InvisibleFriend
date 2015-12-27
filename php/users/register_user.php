<?php
	
	//ini_set('display_errors', 1);
	
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $universe = $_POST['universe'];
    $year = $_POST['year'];

    /*      Register        */
    
    include '../mysql_connection.php';

	$conn = get_connection();
    echo $conn->connect_error;

    $eencoded = base64_encode($email);
    $epass = crypt($password,'$5$rounds=5000$universe'.$universe.'$');
    
    $query = "insert into users(name,email,password,universe,year) values (";
    $query .= "'".base64_encode($name)."'";
    $query .= ",'".$eencoded."'";
    $query .= ",'".$epass."'";
    $query .= ",".$universe;
    $query .= ",".$year.");";
    
    /*        Check if user exists        */
    $res = $conn->query("select id from users where (name='".base64_encode($name)."' or email='".$eencoded."') and universe=".$universe.";");
    if(!$res || $res->num_rows > 0) {
        echo '{"id":0,"mail":0}';
        exit(1);
    }
    
	if(!$conn->query($query)){
        echo '{"id":0,"mail":0}';
        exit(1);
    }
    $id = $conn->insert_id;

    echo '{"id":'.$id.',';
    
    $conn->close();
    
    /*      Mail        */
    
    include 'transactional_mail.php';

    if(send_welcome_mail($id)) echo '"mail":1}'; else echo '"mail":0}';
    
?>