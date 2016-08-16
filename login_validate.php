<?php
	ini_set("session.cookie_lifetime", CONFIG::SESSION_TIMEOIUT); 
	ini_set("session.gc_maxlifetime", CONFIG::SESSION_TIMEOIUT); //60 min (lite mer än hemmagjorde hanteringen, se nedan)


	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > CONFIG::SESSION_TIMEOIUT)) {
	    session_unset();     // töm alla variabler
	    session_destroy();   // förstör disksparad session-data
		//print "<p>Försört session</p>";
		header("Location: /");
		die();
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
?>