<?php
	include("../config.php");
	include("../db_functions.php");
	include("../functions.php");
	include("../connect.php");
	
	if(isset($_GET["id"])){
		print getDbTabellHTML($_GET["id"]);
	} else {
		print "Felaktig parameter";
	}
?>
