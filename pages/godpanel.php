<!DOCTYPE html>
  <?php
   if(!isset($_COOKIE['loggin'])) {
	 header("location: ../?admin");
   }
      
   include '../php/mysql_connection.php';

   $v = verify_loggin($_COOKIE['loggin'],$_COOKIE['password']);
   if(!$v) header("location: ..");
   
  ?>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="author" content="zolken">
  <meta name="description" content="The omnipotent panel for the fucking god!">
  <meta name="robots" content="noindex">
  <meta name=”viewport” content=”width=device-width, initial-scale=1″ />
  
  <title>GodPanel</title>
  
  <link rel="stylesheet" type="text/css" href="../style/god_theme.css">
  <link rel="stylesheet" href="../js/jqwidgets/styles/jqx.base.css" type="text/css" />
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="../js/jqwidgets/jqxcore.js"></script>
  <script type="text/javascript" src="../js/jqwidgets/jqxdata.js"></script> 
  <script type="text/javascript" src="../js/jqwidgets/jqxbuttons.js"></script>
  <script type="text/javascript" src="../js/jqwidgets/jqxscrollbar.js"></script>
  <!--<script type="text/javascript" src="../js/jqwidgets/jqxmenu.js"></script>
  <script type="text/javascript" src="../js/jqwidgets/jqxcheckbox.js"></script>
  <script type="text/javascript" src="../js/jqwidgets/jqxlistbox.js"></script>
  <script type="text/javascript" src="../js/jqwidgets/jqxdropdownlist.js"></script>-->
  <script type="text/javascript" src="../js/jqwidgets/jqxgrid.js"></script>
  <script type="text/javascript" src="../js/jqwidgets/jqxgrid.selection.js"></script>
  <script type="text/javascript" src="../js/godpanel_functions.js"></script>
  
</head>
<body>
	
	<div id="container">
		<div class="frontground"></div>
		<div class="dialog"><p id="dialog_message">Message</p><input type="submit" value="" id="ok"/><input type="submit" value="" id="cancel"/></div>
		<h1>GodPanel</h1>
		<hr>
		<p>Welcome to the God Panel! This is the place when you will can alterate things in database that, others users will can't done.</p>
        <hr>
        <h2>Universes</h2>
		<div id="universe-table"></div>
		<div id="error_cu">Error in create universe! The universe isn't created or exists.</div>
		<b>New Universe</b>
		<input type="text" id="universe_name" value="Name" maxlength="50" />
		<input type="text" id="universe_description" value="Description" maxlength="250"/>
		<input type="submit" value="Create!" name="create" id="create_universe_btn"/>
		<h2>Stars</h2>
		<div id="stars-table"></div>
		<br>
	</div>
   
</body>
</html>