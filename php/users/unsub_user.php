<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="author" content="zolken">
  <meta name="description" content="Delete user in invisible friend application.">
  <meta name="robots" content="noindex">
  <meta name=”viewport” content=”width=device-width, initial-scale=1″ />
  
  <title>Delete New User</title>
  
  <link rel="stylesheet" type="text/css" href="../../style/register_theme.css">
  
  <script language="javascript" type="text/javascript">
  function windowClose() {
	window.open('','_parent','');
	window.close();
  }
  window.onload=function(){setInterval(windowClose,5000);}
  </script>
  
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

            if($hash == $_GET['hash'] && $conn->query('call delete_user('.$_GET['id'].');')) {
                echo '<div id="register_complete" style="display: block;">
                        <h1>S\'ha eliminat l\'usuari de la base de dades!</h1>
						<hr>
						<p></p>
                    </div>';
                    $conn->close();
                exit(0);
            }
        }                
    }
    echo '<div id="register_error" style="display: block;">
		<h1>No s\'ha pogut eliminar l\'usuari</h1>
		<hr>
		<p>Hi ha hagut un error al activar l\'usuari solicitat. Això podría ser degut a un error en l\'enllaç.
        <br>Provi-ho una altra vegada més tard. Si el problema perdura, possis en contacte amb l\'administrador de la pàgina.</p>
        </div>';
    $conn->close();
?>
   
</body>
</html>