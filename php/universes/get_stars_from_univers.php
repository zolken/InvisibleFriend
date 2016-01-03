<?php

    $universe = $_GET['universe'];
    
    include '../mysql_connection.php';
    $conn = get_connection();
    $r = $conn->query('select * from `users` WHERE universe='.$universe.' order by id;');
    $data = array(); $i = 0;
    if($r){
        while($row = $r->fetch_array(MYSQLI_ASSOC)){
            $row['name']=base64_decode($row['name']);
            $row['email']=base64_decode($row['email']);
            $data[$i] = $row;
            $i++;
        }
        header("Content-type: application/json; charset=utf-8"); 
        echo "{\"data\":" .json_encode($data). "}";
    }
    $conn->close();
?>