<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL); // E_ERROR / E_ALL

setlocale(LC_ALL,"SV");

final class CONFIG {
	
	const TITEL = "Älvkullens läromedelsbokning"; // med avslutande "/" (om inte tom, då bara "")
	const VERSION = "2.01 beta 5"; // med avslutande "/" (om inte tom, då bara "")
	const DEBUG = true; // om visa debug-prylar

	const SIMPLE_MODE = false; // Visar bara det viktigaste - såsom bokning - när inte all data är klar
	// se publics nedan för NAVs i simple mode
	
	const BASE_URL = "/laromedel/"; // med avslutande "/" (om inte tom, då bara "")

	// lampan
	// const DB_HOST = "localhost";
	// const DB_NAME = "laromedel";
	//const DB_USER = "laromedel";
	//const DB_PASS = "arkimedes";
	
	// labs.mn.se
	const DB_HOST = "mysql384.loopia.se";
	const DB_NAME = "martinnilsson_se_db_4";
	const DB_USER = "laromedel@m80331";
	const DB_PASS = "laromedelarkimedes";

	const DB_BACKUP_PATH = "backups";

	const PARAM_NAV = "o";
	const PARAM_ID = "id";
	const PARAM_REF_TYP = "ref";
	const PARAM_REF_ID = "refid";
	const PARAM_AJAX = "x";
	const PARAM_DEFAULT_NAV = "kurser";
	const PARAM_DEFAULT_NAV_SIMPLE_MODE = "kurser";

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

	public static $DB_LINK = null;
	public static $DB_SUCCESS = true;
	public static $DB_ERROR = "";

	public static $SIMPLE_MODE_NAVS = array("bokningar", "kurser", "bocker", "help", "admin-import","admin-backup"); // vilka nav-key som ska användas i simple-mode

}


require_once("config_nav.php");
require_once("config_import.php");

//$_SESSION["isDev"] = true;
//$_SESSION["isAdmin"] = true;
//$_SESSION["isLoggedin"] = true;
?>