<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL); // E_ERROR / E_ALL

setlocale(LC_ALL,"SV");

final class CONFIG {
	
	const VERSION = "2.0 beta 1"; // med avslutande "/" (om inte tom, då bara "")

	const BASE_URL = "/laromedel/"; // med avslutande "/" (om inte tom, då bara "")

	const DB_HOST = "localhost";
	const DB_NAME = "laromedel";
	//const DB_USER = "laromedel";
	//const DB_PASS = "arkimedes";
	const DB_USER = "root";
	const DB_PASS = "";

	const PARAM_NAV = "o";
	const PARAM_PRIM_NAV = Self::PARAM_NAV; // !! DEPRICATED
	const PARAM_SEC_NAV = "b"; // !! DEPRICATED
	const PARAM_ID = "id";
	const PARAM_REF_TYP = "ref";
	const PARAM_REF_ID = "refid";
	const PARAM_AJAX = "x";
	const PARAM_DEFAULT_NAV = "bocker";

	const TILLFALLEN_START_YEAR = 2014; // vilket start-år för presentation och filtrering av av tillfällen
	const TILLFALLEN_END_YEAR = 2017; // vilket start-år för presentation och filtrering av av tillfällen

	const TILLFALLEN_LASAR_GENERIC_START_ID = "start"; // generellt datum för läsårtstar för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_END_ID = "slut"; // generellt datum för läsårtslutför presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_MID_ID = "vt"; // generellt datum för vårtermins-start för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_START_DATE = "08-24"; // generellt datum för läsårtstar för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_END_DATE = "06-10"; // generellt datum för läsårtslutför presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)
	const TILLFALLEN_LASAR_GENERIC_MID_DATE = "02-01"; // generellt datum för vårtermins-start för presentation och filtrering av av tillfällen (visas inte just nu, ungefärligt bara för beräkning)

	const SESSION_TIMEOIUT = 3600; // timeout på 60 min

	public static $DB_LINK = null;
	public static $DB_SUCCESS = true;
	public static $DB_ERROR = "";

}


require_once("config_nav.php");
require_once("config_import.php");

$CONFIG["outputDebug"] = false;


$CONFIG["nullVauleForSelects"] = "null"; // värde som skrivas i select-listor för första option, typ "Välj..."

$CONFIG["varnaBokAntalUnder"] = "15"; // markera varning för återstående bok antal under detta






//$_SESSION["isDev"] = true;
//$_SESSION["isAdmin"] = true;
//$_SESSION["isLoggedin"] = true;
?>