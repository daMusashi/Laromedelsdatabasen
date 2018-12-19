<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL); // E_ERROR / E_ALL

setlocale(LC_ALL,"SV");
ini_set('max_execution_time', 120); //120 seconds = 2 minuter

require_once("class_termin.php");


final class CONFIG {
	
	const VERSION = "3.22"; // med avslutande "/" (om inte tom, då bara "")
	const DEBUG = false; // om visa debug-prylar

	const SIMPLE_MODE = false; // Visar bara det viktigaste - såsom bokning - när inte all data är klar
	// se publics nedan för NAVs i simple mode
	
	const BASE_URL = "/laromedel/"; // med avslutande "/" (om inte tom, då bara "")

	const DB_BACKUP_PATH = "backups";

	const PARAM_NAV = "o";
	const PARAM_ID = "id";
	const PARAM_REF_TYP = "ref";
	const PARAM_REF_ID = "refid";
	const PARAM_AJAX = "x";
	const PARAM_DEFAULT_NAV = "bocker";
	const PARAM_DEFAULT_NAV_SIMPLE_MODE = "bocker";

	const CURRENT_START_YEAR = 2016; // vilket start-år för presentation och filtrering av av tillfällen

	const TILLFALLEN_START_YEAR = 2014; // vilket start-år för presentation och filtrering av av tillfällen
	const TILLFALLEN_END_YEAR = 2017; // vilket start-år för presentation och filtrering av av tillfällen

	const TILLFALLEN_LASAR_GENERIC_START_ID = "start"; // generellt datum för läsårtstar för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_END_ID = "slut"; // generellt datum för läsårtslutför presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_MID_ID = "vt"; // generellt datum för vårtermins-start för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_START_DATE = "08-24"; // generellt datum för läsårtstar för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_END_DATE = "06-10"; // generellt datum för läsårtslutför presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_MID_DATE = "02-01"; // generellt datum för vårtermins-start för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)

	const SESSION_TIMEOIUT = 3600; // timeout på 60 min

	const BOK_INSTOCK_WARNING = 20; // antalet böcker kvar som generar varnings-färg

	const DATALAGER_MIN_CACHE_TIME = 10; // Antalet minuter datalagret måste vara gammalt innan det får uppdateras

	const NULL = "null"; // används som null-värde främst i forms

	const LOADING_HTML = "<div class=\"loading-wrapper\"><span class=\"text\">Laddar</span><div class=\"loading\"><div></div><div></div><div></div><div></div><div></div></div></div>";

	public static $DB_LINK = null;
	public static $DB_SUCCESS = true;
	public static $DB_ERROR = "";

	public static $SIMPLE_MODE_NAVS = array("bokningar", "kurser", "bocker", "help", "admin-import","admin-backup"); // vilka nav-key som ska användas i simple-mode

}

require_once("config_db.php");
require_once("config_nav.php");
require_once("config_import.php");
require_once("config_texter.php");

//$_SESSION["isDev"] = true;
//$_SESSION["isAdmin"] = true;
//$_SESSION["isLoggedin"] = true;
?>