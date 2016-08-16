<?php 
// Returnerar bara HTML
session_start();
header('Content-Type: text/html; charset=utf-8' ); 
require_once("config.php");
//require_once("db_functions.php");
//require_once("functions.php");
//require_once("admin/dev_functions.php");

require_once("db_connect.php");
require_once("class_datalager.php");
//require_once("page_functions.php");


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
		case "html-bok-info":
			include("ajax_html_factory.php");
			break;
		case "login":
		case "logout":
		case "getLogoutHTML":
		case "getLoginHTML":
			include("admin/ajax_login.php");
			break;
		
		case "get-kurser-pagelist":
			include "page_functions.php";
			if(isset($_GET[Config::PARAM_ID])){ // terminId
				$_SESSION["active-termin"] = $_GET[Config::PARAM_ID];
				print getKurserPageList();
			} else {
				printAjaxError("Termin-id saknas för anrop till kurslista.");
			}
			break;
		
		case "get-bocker-pagelist-urval":
			include "page_functions.php";
			if(isset($_GET[Config::PARAM_ID])){ // urvals-bokstav
				$_SESSION["bok-urval"] = $_GET[Config::PARAM_ID];
				print getBockerPageList();
			} else {
				printAjaxError("Urval saknas för anrop till läromedelslista.");
			}
			if(Config::DEBUG) {
				print '<div id="db-debug">'.Datalager::getDebugSqlCalls().'</div>';
			}
			break;
		
		case "get-bocker-pagelist-termin":
			include "page_functions.php";
			if(isset($_GET[Config::PARAM_ID])){ // terminId
				$_SESSION["bok-termin"] = $_GET[Config::PARAM_ID];
				print getBockerPageList();
			} else {
				printAjaxError("Urval saknas för anrop till läromedelslista.");
			}
			if(Config::DEBUG) {
				print '<div id="db-debug">'.Datalager::getDebugSqlCalls().'</div>';
			}
			break;
		
		case "get-bokningar-pagelist":
			include "page_functions.php";
			if(isset($_GET[Config::PARAM_ID])){ // terminId
				$_SESSION["active-termin"] = $_GET[Config::PARAM_ID];
				print getBokningarPageList();
				if(Config::DEBUG) {
					print '<div id="db-debug">'.Datalager::getDebugSqlCalls().'</div>';
				}
			} else {
				printAjaxError("Urval saknas för anrop till bokningslista.");
			}
			break;
		case "get-bokningar-boklist":
			include "page_functions.php";
			if(isset($_GET[Config::PARAM_ID])&&isset($_GET[Config::PARAM_REF_ID])){ // terminId & bokId
					$_SESSION["active-termin"] = $_GET[Config::PARAM_ID];
				print getBokningarPageList($_GET[Config::PARAM_REF_ID], "");
				if(Config::DEBUG) {
					print '<div id="db-debug">'.Datalager::getDebugSqlCalls().'</div>';
				}
			} else {
				printAjaxError("Urval saknas för anrop till bokningslista i bokvisning.");
			}
			break;
		case "get-bokningar-bokarelist":
			include "page_functions.php";
			if(isset($_GET[Config::PARAM_ID])){ // terminId & bokId
				$_SESSION["bokning-bokare"] = $_GET[Config::PARAM_ID];
				print getBokningarPageList();
			} else {
				printAjaxError("Urval saknas för anrop till bokningslista för bokare.");
			}
			break;
		case "update-termin-time":
			include "class_kurs.php";
			if(isset($_GET[Config::PARAM_ID])&&isset($_GET["start"])&&isset($_GET["slut"])){ // terminId & bokId
					$kurs = new Kurs();
					$kurs->setFromId($_GET[Config::PARAM_ID]);
					$kurs->startTermin_id = $_GET["start"];
					$kurs->slutTermin_id = $_GET["slut"];
					$kurs->save();
			} else {
				printAjaxError("Värden saknas för uppdatering av terminer för kurs");
			}
			break;

		case "update-datalager":
			require_once("class_datalager.php");
			Datalager::update();
			$_SESSION["datalagerDataChanged"] = false;
			print "ok";
			break;

		default:
			printAjaxError("En okänd parameter användes.");

	}


	
	
} else {
	printAjaxError("Felaktig server-anrop [" . Config::PARAM_AJAX  . "]");
}

// functions

function printAjaxError($msg){
	print "<div class=\"alert alert-danger\"><div><strong>Fel i AJAX-anrop</strong></div><div>$msg</div></div>";
}
?>

