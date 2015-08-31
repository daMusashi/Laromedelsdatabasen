<?php
include("db_arkiv_functions.php");

if(isset($_GET[Config::PARAM_NAV])){
	$mode = $_GET[Config::PARAM_NAV];
} else {
	$mode = "";
}

$baseuRL = "?".Config::PARAM_NAV."=admin-arkivera";

$prevLA = Lasar::getLasar(-1);

?>
<h1>Arkivera</h1>
<p>BACKUPPA FÖRST !!!</p>
<p><a href="<?php print $baseuRL."-allt-older" ?>">Arkivera <strong>alla</strong> kurser och bokningar som slutar under  <strong><em><?php print $prevLA->descLong; ?></em></strong> och (ev) <strong>äldre</strong></a></p>
<br>
<p><a href="<?php print $$baseuRL ?>">[INTE KLAR!!!]  <strong>Välj</strong> kurser att arkivera</a></p>
<p><a href="<?php print $baseuRL ?>">[INTE KLAR!!!]   <strong>Ta tillbaka</strong> kurser från arkivet</a></p>
<?php

if($mode){
	switch($mode){
		case "admin-arkivera-allt-older":
			print "<h2>Arkvierar allt öldre</h2>";
			arkivera_allt_older();
			break;
		case "bocker":
			print "<h2>Endast böcker</h2>";
			//backup_tables('bocker');
			break;
		case "bokningar":
			print "<h2>Endast bokningar</h2>";
			//backup_tables('kurser_bocker');
			break;
		default:

	}
	
}

?>