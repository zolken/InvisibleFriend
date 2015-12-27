<?php
    $name = $_POST['name'];
    $description = $_POST['description'];
    $year = $_POST['year'];
        
    include '../mysql_connection.php';
    
    $conn = get_connection();
    $query = "insert into universes(name,description,year) values ('".$name."','".$description."',".$year.");";
    if(!$conn->query($query)) echo "{\"id\":0}";
    else echo "{\"id\":".$conn->insert_id."}";
    $conn->close();
?>