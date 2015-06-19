<?php 
// Returnerar bara HTML
session_start();
header('Content-Type: text/html; charset=utf-8' ); 
require_once("config.php");
require_once("db_functions.php");
require_once("functions.php");
require_once("admin/dev_functions.php");

require_once("db_connect.php");
?>
<?php
if(isset($_GET[Config::PARAM_AJAX])){
	$html = "";
	switch($_GET[Config::PARAM_AJAX]){
		case "tabeller":
			if($_SESSION["isDev"] == true){
				if(isset($_GET[Config::PARAM_REF_ID])){
					print getDbTabellHTML($_GET[Config::PARAM_REF_ID]);
				} else {
					printAjaxError("Inget tabellnamn angivet");
				}
			} else {
				printDevsOnly();
			}
			
			break;
		case "print":
			include("reports.php");
			break;
		case "login":
		case "logout":
		case "getLogoutHTML":
		case "getLoginHTML":
			include("admin/ajax_login.php");
			break;
	}


	
	
} else {
	printAjaxError("Felaktig server-anrop [" . Config::PARAM_AJAX  . "]");
}

// functions

function printAjaxError($msg){
	print "<p class=\"error\">$msg</p>";
}
?>

