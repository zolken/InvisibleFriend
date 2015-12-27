<?php
  include 'php/mysql_connection.php';
  if(isset($_COOKIE['loggin']) && verify_loggin($_COOKIE['loggin'],$_COOKIE['password'])) {
  if($_COOKIE['loggin'] == 1) header("Location: pages/godpanel.php");
  else header("Location: pages/upanel.php");
 }
?>
<!DOCTYPE html>
<html>
<head>
  <meta content="width=device-width,maximum-scale=1.0,initial-scale=1.0,minimum-scale=1.0,user-scalable=yes" name="viewport">
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="zolken">
  <meta name="description" content="Main panel from invisible friend application.">
  <meta name="robots" content="noindex">
  
  <title>Invisible friend</title>
  
  <link rel="stylesheet" type="text/css" href="style/index_theme.css">
  <script type="text/javascript" src="js/loggin_functions.js"></script>
  
</head>
 
<body>
	
	<div id="container">
		<h1>Conexió al servidor</h1>
		<hr>
		<p>Introdueix el teu e-mail i la paraula de pas que vas escollir en el moment del regístre.</p>
		<h2>Correu electrònic</h2>
		<input type="email" id="email" value="" maxlength="60" autofocus/>
		<br>
		<h2>Paraula de pas</h2>
		<input type="password" id="password" value="" maxlength="60"/>
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
		<br>
		<span id="loggin_error">El correu, la paraula de pas o l'univers no coincideixen a la base de dades!<br>
			  Comprova que tot està correcte.</span>
		<span id="universe_error">No s'ha seleccionat cap univers, si us plau, seleccioneu-ne un.<br>
			  Si no t'apareix cap univers a seleccionar, posa't en contacte amb l'adminstrador de la web.</span>
		<span id="status_error">L'usuari encara no ha estat activat.<br>Si no heu rebut el correu de confirmació, premeu el següent enllaç:
		<span id="link">Torna'm a enviar el correu!</span></span>
		<img id="load_animation" src="src/loading.gif" alt="On load animation" />
		<input type="submit" value="Entra" name="Access" id="access_btn"/>
		<hr>
		<p class="remind">Encara no tens una paraula de pas?</p>
		<a href="pages/register.php">Accedeix aquí per aconseguir-la.</a>
	</div>
   
</body>
</html>