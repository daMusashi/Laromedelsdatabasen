<?php 
	require_once("class_termin.php");
	require_once("class_lasar.php");
?>


<div class="page-header">
	<h1>Bokningar</h1>
</div>
<?php
	if(isLoggedIn()){
		print "<p>".HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bokningar-add", "Gör en bokning", "lg", "success", "Skapa en bokning")."</p>";
	}
	if(isset($_GET[Config::PARAM_ID])){
		$activeTermin = new Termin();
		$activeTermin->setFromId($_GET[Config::PARAM_ID]);
	} else {
		if(Config::SIMPLE_MODE){
			$lasar = Lasar::getCurrentLasar(1);
			$activeTermin= $lasar->getFirstTermin();
		} else {
			$activeTermin= Termin::getCurrentTermin();
		}
	}

	if(Config::SIMPLE_MODE){
		print Termin::getTabsHTML("bokningar", $activeTermin->id);
	}

	$output = HTML_FACTORY::getBokningarHTML($activeTermin);

HTML_FACTORY::printWarningAlert("OBS", "Bara biblioteket kan ta bort eller redigera en bokning, Detta för förhindra misstag. Eposta biblioteket eller martin.nilsson@karlstad.se om en bokning behöver ändras (ange bok och kurs)");

if(!Config::SIMPLE_MODE){
	print Lasar::getTabsHTML("bokningar", $activeTermin->lasar->id, true);
}


?>

<?php print $output; ?>
