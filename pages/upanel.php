<?php
   if(!isset($_COOKIE['loggin'])) {
      header("location: ../?admin");
   }
      
  include '../php/mysql_connection.php';

  $v = verify_loggin($_COOKIE['loggin'],$_COOKIE['password']);
  if(!$v) header("location: ..");
?>
<!DOCTYPE html>
<html>
<head>
  <meta content="width=device-width,maximum-scale=1.0,initial-scale=1.0,minimum-scale=1.0,user-scalable=yes" name="viewport">
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="author" content="zolken">
  <meta name="description" content="The user panel for manage his data!">
  <meta name="robots" content="noindex">
  <meta name=”viewport” content=”width=device-width, initial-scale=1″ />
  
  <title>User Panel</title>
  
  <link rel="stylesheet" type="text/css" href="../style/user_theme.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="../js/html2canvas/html2canvas.js"></script>
  <script type="text/javascript" src="../js/editor_functions.js"></script>
  <script type="text/javascript" src="../js/userpanel_functions.js"></script>
  
</head>
<body>
	<div id="userbar">
	  <img id="menu-icon" alt="Menu icon" src="../src/mobile-menu.png">
	  <span class="value" id="universe" ></span>
	  <div id="var-fields">
		 <input type="text" class="value" id="name" value="" readonly>
		 <input type="text" class="value" id="email" value="" readonly>
		 <span id="year">enregistrat des de </span>
	  </div>
	  <input id="goodbye" class="value" type="submit" value="sortir" name="exit" alt="Exit Button">
	</div>
	<div id="first-time-div">
		<div class="wmessage">Hola #user#!</div>
		<div class="wmessage">Benvingut al panell principal</div>
		<div class="wmessage" style="font-size:400%;">En aquest espai<br> podrà editar la seva informació.</div>
		<div class="wmessage" style="font-size:150%; width: 300px;">Aquí podrà redactar la carta que rebrà el seu amic invisible.<br><br>
		Aquesta carta hauria de contindre les seves preferencies.<br><br>
		¡Recordi llegir les instruccions al peu de pàgina!</div>
		<img class="mask" src="../src/letter.jpg"></img>
		<div class="wmessage" style="font-size:150%; width: 500px; text-align: right;">Aquí podrà realitzar una exclusió.<br><br>
		Quan realitza una exclusió, fa impossible que li toqui la persona seleccionada en el sorteig.</div>
		<img class="mask" src="../src/exclusion.jpg" style="left: calc(50% + 212px);
		left: -webkit-calc(50% + 212px); left: -moz-calc(50% + 212px); left: -o-calc(50% + 212px);
		top: 205px;
		"></img>
		<div class="wmessage" style="font-size:150%; width: 600px; text-align: right;">Un cop estigui conforme amb la seva configuració,<br>
		premi el botó per a entrar en el sorteig.<br><br>
		Podrà seguir editant la seva configuració fins al moment del sorteig.</div>
		<img class="mask" src="../src/ready.jpg" style="left: calc(50% + 214px);
		left: -webkit-calc(50% + 214px); left: -moz-calc(50% + 214px); left: -o-calc(50% + 214px);
		top: 297px;
		"></img>
		<input type="submit" value="Continua" id="next">
	</div>
	<div class="frontground"></div>
	<div id="container">		
		<div class="dialog"><p id="dialog_void_message">Message</p></div>
        <div id="list-panel">
		  <br>
		  <div id="letter">
            <div class="cheader"><span>Estimat Amic Invisible,</span></div>
            <div class="cbody">
			 <input data-nline="0" data-endl="0" class="cline" type="text"/>
            </div>
            <div class="cfooter"><input type="submit" id="save-letter" alt="Desar Carta" value="Desar la carta"/></div>
			<div id="error-list"></div>
			</div>
            <br>
			<h2>Instruccions</h2>
			<p>Pots crear la carta al teu amic invisible, usant l'editor just a sobre d'aquest text. Per a fer-ho, només cal
			que premis sobre una línea i comencis a escriure. Aquest editor disposa d'algunes característiques especials:</p>
			<ul>
			  <li>Si comences una línea amb el caràcter "-" seguit d'un espai, podràs crear una llista.</li>
			</ul>
		</div>
        <div id="info-panel">
			<h1>Exclusió</h1>
			<select id="exclusions">
			  <option value="0"></option>
			  <?php
				  $conn = get_connection();
				  $r = $conn->query('select u.id,u.name
									from zolken_invisiblefriend.users u
									inner join zolken_invisiblefriend.users f on f.universe=u.universe and f.id='.$_COOKIE['loggin'].'
									where u.status = 1 and u.id!='.$_COOKIE['loggin'].';');
				  if($r){
					while($row = $r->fetch_array(MYSQLI_ASSOC)){
					  echo '<option value="'.$row['id'].'">'.base64_decode($row['name']).'</option>\n';
					}
				  }
				  $conn->close();
			  ?>
			</select>
			<img src="../src/help_icon.png" title="Aquesta opció et permet realitzar una exclusió en el sorteig. Es a dir, un cop es realitzi el sorteig, serà impossible que et toqui la persona seleccionada." alt="Help Icon">
			<hr>
			<h1 id="ready-title">¿Estàs llest?</h1>
			<input id="ready" type="submit" value="Sí, estic llest!" class="notready" alt="Ready button" title="¿Estàs llest?">
        </div>
	</div>
   
</body>
</html>
