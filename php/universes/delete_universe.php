<?php
    include '../mysql_connection.php';
    $conn = get_connection();
    if($conn->query("delete from universes where id=".$_POST['id'])) echo 200; else echo 0;
    $conn->close();
?>