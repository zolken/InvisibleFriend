<?php
	
    include "../mysql_connection.php";
	
    $conn = get_connection();
    $r = $conn->query("select * from universes where id > 1;");

    $data = array(); $i = 0;

    while($row = $r->fetch_array(MYSQLI_ASSOC)){
        $data[$i] = $row;
        $i++;
    }
    header("Content-type: application/json; charset=utf-8"); 
	echo "{\"data\":" .json_encode($data). "}";
    $conn->close();
?>