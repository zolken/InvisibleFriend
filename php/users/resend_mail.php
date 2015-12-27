<?php

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="refresh" content="10;url=../..">
  <meta name="author" content="zolken">
  <meta name="description" content="Resend email to user.">
  <meta name="robots" content="noindex">
  <meta name=”viewport” content=”width=device-width, initial-scale=1″ />
  
  <title>Resend Mail</title>
  
  <link rel="stylesheet" type="text/css" href="../../style/register_theme.css">
  
</head>
<body>
    
    <?php

    if(isset($_GET['id'])){
        include 'transactional_mail.php';

          if(send_welcome_mail($_GET['id'])) {
            echo '<div id="register_complete" style="display: block;">
                    <h1>Se li acaba d\'enviar un nou correu</h1>
                    <hr>
                    <p>El correu pot trigar entre 1 i 5 minuts a arrivar. Si no l\'ha rebut passat aquest temps, comprovi que no estigui dins la <b>carpeta d\'spam</b>.</p> 
                </div>';
            exit(0);
          }              
    }
    echo '<div id="register_error" style="display: block;">
		<h1>No s\'ha pogut enviar el correu</h1>
		<hr>
		<p>Comprovi que el seu proveïdor de correu no tingui restringit els dominis.</p>
        </div>';
  ?>
   
</body>
</html>