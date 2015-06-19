<?php
global $CONFIG;

include("backup_functions.php");

if(isset($_GET[$CONFIG["refIdParam"]])){
	$mode = $_GET[$CONFIG["refIdParam"]];
} else {
	$mode = "";
}

?>
<h1>Backup</h1>
<p><a href="?<?php print $CONFIG["primNavParam"]."=admin&". $CONFIG["secNavParam"]. "=backup&".$CONFIG["refIdParam"]."=allt" ?>">Gör backup på <strong>allt</strong></a></p>
<p><a href="?<?php print $CONFIG["primNavParam"]."=admin&". $CONFIG["secNavParam"]. "=backup&".$CONFIG["refIdParam"]."=bocker" ?>">Gör backup på endast<strong>böcker</strong></a></p>
<p><a href="?<?php print $CONFIG["primNavParam"]."=admin&". $CONFIG["secNavParam"]. "=backup&".$CONFIG["refIdParam"]."=bokningar" ?>">Gör backup på endast<strong>bokningar</strong></a></p>
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
			backup_tables('kurser_bocker');
			break;
	}
	
}

?>