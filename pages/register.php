<?php
 include '../php/mysql_connection.php';
 if(isset($_COOKIE['loggin']) && verify_loggin($_COOKIE['loggin'],$_COOKIE['password'])) {
  if($_COOKIE['loggin'] == 1) header("location: godpanel.php");
  else header("location: upanel.php");
 }
?>
<!DOCTYPE html>
<html>
<head>
  <meta content="width=device-width,maximum-scale=1.0,initial-scale=1.0,minimum-scale=1.0,user-scalable=yes" name="viewport">
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="author" content="zolken">
  <meta name="description" content="Register user in invisible friend application.">
  <meta name="robots" content="noindex">
  <meta name=”viewport” content=”width=device-width, initial-scale=1″ />
  
  <title>Register</title>
  
  <link rel="stylesheet" type="text/css" href="../style/register_theme.css">
  <script type="text/javascript" src="../js/register_functions.js"></script>
  
</head>
<body>
	
	<div id="container">
		<h1>Registre d'usuari</h1>
		<hr>
		<p>Ompli tots els camps que trobaràs a continuació. S'agrairà que dongui el nom real, ja que, el seu amic invisible podrà reconeixe'l amb facilitat.</p>
        <h2>Nom Complet</h2>
		<input type="text" id="name" value="" maxlength="60" autofocus/>
		<br>
		<h2>Correu electrònic</h2>
		<input type="email" id="email" value="" maxlength="60"/>
		<br>
		<h2>Paraula de pas (repeteix-la)</h2>
		<input type="password" id="password" value="" maxlength="60"/>
        <input type="password" id="password2" value="" maxlength="60"/>
		<br>
		<h2>Univers</h2>
		<select id="universe">
		  <?php
			$where = ""; if(!isset($_GET['admin'])) $where = ' where id > 1';
			$conn = get_connection();
			$result = $conn->query("select id,name,description from universes".$where.";");
			if($result != FALSE){
			  while( $row = $result->fetch_array(MYSQLI_ASSOC) ){
				echo '<option value="'.$row['id'].'" title="'.$row['description'].'">'.$row['name'].'</option>';
			  }  
			}
			$conn->close();
		  ?>
		</select>
        <img src="../src/help_icon.png" title="L'univers representa el grup al que vols registrar-te. Escull segons les teves necesitats." alt="Help icon" />
		<br>
		<span id="universe_error" style="display:none;">No s'ha seleccionat cap univers, si us plau, seleccioneu-ne un.<br>
			  Si no t'apareix cap univers a seleccionar, posa't en contacte amb l'adminstrador de la web.</span>
		<img id="load_animation" src="../src/loading.gif" alt="On load animation" />
		<input type="submit" value="Enregistra'm" name="register" id="register_btn"/>
	</div>
    
    <div id="register_complete">
		<h1>Registre Complet</h1>
		<hr>
		<p>Se li acaba d'enviar un correu electrònic de confirmació al correu que ha enregistrat.
        <br><br>Es possible que trigui uns minuts a arrivar, o bé, que arrivi al <b>correu brossa</b>.
        <br><br>Podrà accedir a les seves dades un cop hagi premut l'enllaç adjunt al correu.</p>
	</div>
    
    <div id="register_partial">
		<h1>Registre Mig-complet</h1>
		<hr>
		<p>S'ha enregistrat l'usuari però no s'ha pogut enviar el correu de confirmació.
        <br><br>Possis en contacte amb l'adminstrador de la pàgina per a resoldre el problema.</p>
	</div>
    
    <div id="register_error">
		<h1>No s'ha pogut enregistrar</h1>
		<hr>
		<p>No s'ha pogut enregistrar l'usuari. Es possible que ja existeixi o coincideixi amb un altre usuari ja enregistrat.
        <br><br>Possis en contacte amb l'adminstrador de la pàgina per a resoldre el problema.</p>
	</div>
   
</body>
</html>