<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL); // E_ERROR / E_ALL

setlocale(LC_ALL,"SV");

require_once("class_tillfalle_data.php");

final class CONFIG {
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
	const PARAM_DEFAULT_PRIM = "bocker";

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

	public static $TILLFALLEN_DATA = null;
}
// Spara data om tillfällen i Config (classen genererar en massa tillfällen att använda i UI och för filtrering)
// Tillfälle-id som sparas i db används för att hämta info om tillfälle (och spara koppling till generareat tillfälle i db)
CONFIG::$TILLFALLEN_DATA = new Tillfalle_data(); 

?>

<?php
$CONFIG["sessionTimeout"] = 3600; // timeout på 60 min (lite mer än hemmagjorde hanteringen, se nedan)
$CONFIG["outputDebug"] = false;

//$CONFIG["primNavParam"] = "a"; // get-parameter för primärmeny
//$CONFIG["secNavParam"] = "b"; // get-parameter för secundärmeny
$CONFIG["idParam"] = "id"; // get-parameter för objekt-ID
$CONFIG["refTypParam"] = "ref"; // get-parameter för referensTYP
$CONFIG["refIdParam"] = "refid"; // get-parameter för referensID
$CONFIG["ajaxParam"] = "x"; // get-parameter för ajax-medetoder
$CONFIG["defaultPrimNav"] = "bocker"; // default prim navigering key

$CONFIG["nullVauleForSelects"] = "null"; // värde som skrivas i select-listor för första option, typ "Välj..."

$CONFIG["varnaBokAntalUnder"] = "15"; // markera varning för återstående bok antal under detta

$DB_SUCCESS = true;

require_once("config_nav.php");
require_once("config_import.php");


ini_set("session.cookie_lifetime", CONFIG::SESSION_TIMEOIUT); 
ini_set("session.gc_maxlifetime", CONFIG::SESSION_TIMEOIUT); //60 min (lite mer än hemmagjorde hanteringen, se nedan)
// Session-hantering. Jag implementerar den själv
// Se varför: http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
//session_unset();     // töm alla variabler
//session_destroy();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $CONFIG["sessionTimeout"])) {
    session_unset();     // töm alla variabler
    session_destroy();   // förstör disksparad session-data
	//print "<p>Försört session</p>";
} else {
	//print "<p>Pågående session</p>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // uppdatera senaste session-aktivitet


// om ny session
if(!isset($_SESSION["isLoggedin"])){
	$_SESSION["isDefaultNav"] = true;
	$_SESSION["currentNavKey"] = "";
	$_SESSION["isLoggedin"] = false;
}

//$_SESSION["isDev"] = true;
//$_SESSION["isAdmin"] = true;
//$_SESSION["isLoggedin"] = true;
?>