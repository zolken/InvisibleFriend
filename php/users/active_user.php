<!DOCTYPE html>
<html>
<head>
  <meta content="width=device-width,maximum-scale=1.0,initial-scale=1.0,minimum-scale=1.0,user-scalable=yes" name="viewport">
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="refresh" content="10;url=../..">
  <meta name="author" content="zolken">
  <meta name="description" content="Active user in invisible friend application.">
  <meta name="robots" content="noindex">
  
  <title>Active New User</title>
  
  <link rel="stylesheet" type="text/css" href="../../style/register_theme.css">
  
</head>
<body>
    
    <?php

    if(isset($_GET['id']) && isset($_GET['hash'])){
        include '../mysql_connection.php';
        
        $conn = get_connection();
        
        //check hash
        
        $r = $conn->query('select email,password from users where id='.$_GET['id'].';');
        if($r){
            $row = $r->fetch_array(MYSQLI_ASSOC);
            $hash = md5($row['email'].$row['password']);

            if($hash == $_GET['hash'] && $conn->query('update users set status=1 where id='.$_GET['id'].';')) {
                echo '<div id="register_complete" style="display: block;">
                        <h1>S\'ha activat l\'usuari amb éxit!</h1>
                        <hr>
                        <p>Si us plau, espera uns segons a que se\'t redirigeixi a la pàgina d\'accés.</p> 
                    </div>';
                    $conn->close();
                exit(0);
            }
        }                
    }
    echo '<div id="register_error" style="display: block;">
		<h1>No s\'ha pogut activar l\'usuari</h1>
		<hr>
		<p>Hi ha hagut un error al activar l\'usuari solicitat. Això podría ser degut a un error en l\'enllaç.
        <br>Provi-ho una altra vegada més tard. Si el problema perdura, possis en contacte amb l\'administrador de la pàgina.</p>
        </div>';
    $conn->close();
?>
   
</body>
</html>