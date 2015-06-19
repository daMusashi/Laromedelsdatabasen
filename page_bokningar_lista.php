<?php
	global $CONFIG;
	
	$output = HTML_FACTORY::getBokningarHTML();
	$rubrik = "Bokningar";
	
	/*if(isset($_GET[$CONFIG["refTypParam"]])&&isset($_GET[$CONFIG["refIdParam"]])){
		switch($_GET[$CONFIG["refTypParam"]]){
			case "bok":
				$output = getBokningarForBockerHTML($_GET[$CONFIG["refIdParam"]]);
				$rubrik = "Bokningar för boken " . getShortBokTitelFromId($_GET[$CONFIG["refIdParam"]]);
				break;
			case "kurs":
				$output = getBokningarForKurserHTML($_GET[$CONFIG["refIdParam"]]);
				$rubrik = "Bokningar för kursen " . $_GET[$CONFIG["refIdParam"]];
				break;
		}
	}*/
?>
<div class="page-header">
	<h1><?php print $rubrik; ?></h1>
</div>
<?php
	//if(isAdmin()){
		print "<p>".HTML_FACTORY::getKnappHTML("bokningar-add", "Gör en bokning", "lg", "success", "Skapa en bokning")."</p>";
	//}
?>
<div class="info-box">
<p>Välj en bokning för att se detaljer för den. Bara biblioteket kan ta bort eller redigera en bokning.</p>
<?php if(isAdmin()){ ?>
<p><strong>Administratör</strong>: Du kan radera och redigera bokningen på dess detaljsida</p> 
<?php } ?>
</div>
<?php print $output; ?>
