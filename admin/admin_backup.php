<?php
include("db_backup_functions.php");

if(isset($_GET[Config::PARAM_ID])){
	$mode = $_GET[Config::PARAM_ID];
} else {
	$mode = "";
}

$baseuRL = "?".Config::PARAM_NAV."=admin-backup&".Config::PARAM_ID."=";

?>
<h1>Backup</h1>
<p><a href="<?php print $baseuRL."allt" ?>">Gör backup på <strong>allt</strong></a></p>
<p><a href="<?php print $baseuRL."bocker" ?>">Gör backup på endast<strong>böcker</strong></a></p>
<p><a href="<?php print $baseuRL."bokningar" ?>">Gör backup på endast<strong>bokningar</strong></a></p>
<?php

if($mode){
	switch($mode){
		case "allt":
			print "<h2>Backup på allt</h2>";
			backup_tables();
			break;
		case "bocker":
			print "<h2>Endast böcker</h2>";
			backup_tables('bocker');
			break;
		case "bokningar":
			print "<h2>Endast bokningar</h2>";
			backup_tables('bokningar');
			break;
	}
	
}

?>