<?php 
header("Location: http://labs.martinnilsson.se/laromedel/");
die();
?>
<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="js/jquery-ui-1.10.4.custom/css/cupertino/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css" />
<title>Läromedelsbokning</title>
<link rel="shortcut icon" href="favicon.ico" />
<?php require_once("config.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php require_once("functions.php"); ?>
<?php require_once("form_functions.php"); ?>
<?php require_once("admin/dev_functions.php"); ?>
<script language="javascript" type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.tablesorter/jquery.tablesorter.min.js"></script>
<script language="javascript" type="text/javascript" src="js/thingies.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/tables.css" rel="stylesheet" type="text/css" />
<link href="css/forms.css" rel="stylesheet" type="text/css" />
<link href="css/print.css" rel="stylesheet" type="text/css" />
<link href="js/jquery-ui-1.10.4.custom/css/cupertino/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
   
<?php require_once("login.php"); ?>
<?php include("header.php"); ?>


<div id="main" >

<div id="content" class="content page-width"><div class="inner">

<?php if($DB_SUCCESS){ ?>    

<?php 
/* DEV STUFF !!!! */
	//print EMPTY_DATA_TABLE("kurser_bocker"); 
?>

<?php 
//makeDemoBokningar();
//DB_UPDATE();
function includeContent($roll, $includeFile){
	switch($roll){
		case "dev":
			//print "dev";
			if(isDev()){
				include($includeFile);
			} else {
				printNoRights($roll);	
			}
			break;
		case "admin":
			//print "admin";
			if(isAdmin()){
				include($includeFile);
			} else {
				printNoRights($roll);	
			}
			break;
		case "user":
			//print "user";
			if(isLoggedin()){
				include($includeFile);
			} else {
				printNoRights($roll);	
			}
			break;
		default:
			//print "alla";
			include($includeFile);
	}
}

;
// navigering.php ser till att $_SESSION["currentNavKey"] alltid har ett värde (defaultar till förstasidan)
$primNavKey = $_SESSION["currentNavKey"];
$secNavKey = "NONE";
	
// om sekundär navigering
if(isset($_GET[$CONFIG["secNavParam"]])&&isset($NAV_SEC[$primNavKey])){
	// gör sekundär navigering	
	$secNavKey = $_GET[$CONFIG["secNavParam"]];
	$roll = $NAV_SEC[$primNavKey][$secNavKey]["roll"];
	$includeFile = $NAV_SEC[$_SESSION["currentNavKey"]][$secNavKey]["include"];
} else {
	// gör primär navigering
	$roll = $NAV_PRIM[$primNavKey]["roll"];
	$includeFile = $NAV_PRIM[$_SESSION["currentNavKey"]]["include"];
}
//debugLog("primNavKey: $primNavKey | secNavKey: $secNavKey | roll: $roll | includeFile: $includeFile", "index.php - include content");
includeContent($roll, $includeFile);



?>

<?php }  else { // DB_SUCCESS ?>
<h2>Applikationen kan inte fungera :(</h2>
<p>Databasanslutning saknas. Rapportera felmeddelandet till första person som verkar förstå det</p>
<?php }  // DB_SUCCESS ?>

</div></div><!-- content -->


</div><!-- main -->

<?php include("footer.php"); ?>

</body>
</html>