<?php session_start(); ?>
<?php require_once("config.php"); ?>
<?php require_once("functions.php"); ?>
<?php require_once("class_html_factory.php"); ?>
<?php require_once("db_connect.php"); ?>
<?php require_once("login_validate.php"); ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Läromedelsbokning [v<?php print Config::VERSION ?>]</title>
<link rel="shortcut icon" href="favicon.ico" />
<script language="javascript" type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script language="javascript" type="text/javascript" src="js/thingies.js"></script>

<?php //<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> ?>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/laromedel.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

</head>

<body>
   
<?php 
	if(CONFIG::$DB_SUCCESS){  
		require_once("login.php");
	}
	include("header.php");
?>


<div id="main" >

<div id="content" class="content container"><div class="inner">

<?php 

if(CONFIG::$DB_SUCCESS){  

	$navItem = $NAV[$_SESSION["currentNavKey"]];
	includeContent($navItem["roll"], $navItem["include"]);


}  else { // DB_SUCCESS = false 
	$content = "<p><strong>Det går inte att skapa en anslutning till databasen.</strong></p>";
	if(!empty(CONFIG::$DB_ERROR)){
		$content .= "<p>Databashanteraren rapporterar följande problem:</p>";
		$content .= "<p><code>".CONFIG::$DB_ERROR."</code></p>";
		$content .= "<p>Rapportera gärna detta till Martin (martin.nilsson@karlstad.se | 054-540 1934) eller Biblioteket</p>";
	}
	HTML_FACTORY::printPanel("danger", "Applikationen har stött på problem", $content);
}  // end if DB_SUCCESS 

?>

</div></div><!-- content -->


</div><!-- main -->

<?php include("footer.php"); ?>

</body>
</html>