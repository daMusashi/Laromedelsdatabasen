<?php
$bokId = "";
$mode = "view";
$rubrik = "Detaljer för bok";

if(isset($_GET[Config::PARAM_REF_ID])){
	$bokId = $_GET[Config::PARAM_REF_ID];
}

switch($_GET[Config::PARAM_NAV ]){
	case "bocker-edit":
		$rubrik = "Ändra information för bok";
		$mode = "edit";
		break;
	case "bocker-add":
		$rubrik = "Lägga till en bok";
		$mode = "add";
		break;
		
}


$bok = new Bok();
$antal = new Bokantal();
if(($mode == "edit")||($mode == "view")){
	$bok->setFromId($bokId);
	$antal = $bok->getAntalBokade();
}

?>


<h1><?php print $rubrik ?></h1>
<div id="form-bok" class="form-container container">
<form method="post" action="">


<?php 
	print HTML_FACTORY::getTextFieldHTML("isbn", "ISBN", $bok->isbn);
	print HTML_FACTORY::getTextFieldHTML("antal", "Antal exemplar", $antal->antal);
	print "<br />";
	print HTML_FACTORY::getTextFieldHTML("titel", "Titel", $bok->titel);
	print HTML_FACTORY::getTextFieldHTML("upplaga", "Upplaga", $bok->upplaga);
	print HTML_FACTORY::getTextFieldHTML("forlag", "Förlag", $bok->forlag);
	print HTML_FACTORY::getTextFieldHTML("undertitel", "Undertitel", $bok->undertitel);
	print "<br />";
	print HTML_FACTORY::getTextFieldHTML("forf_fornamn", "Författare förnamn", $bok->forf_fornamn);
	print HTML_FACTORY::getTextFieldHTML("forf_efternamn", "Författare efternamn", $bok->forf_efternamn);
?>

<?php if(($mode == "edit")||($mode == "add")){ ?>
	<div class="submit-container"><input class="button-big" type="submit" value="Spara" /></div>
<?php } ?>

</form>
</div>
<h2>Bokningar för boken</h2>
<?php //print getBokningarForBockerHTML($bok["isbn"]); ?>