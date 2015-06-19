<?php
require_once("class_bok.php");

$selectedBokId = "";
$mode = "view";
$rubrik = "Detaljer för bok";
$saveSuccess = true;
$dbDebug = "";

if(isset($_GET[Config::PARAM_REF_ID])){
	$selectedBokId = $_GET[Config::PARAM_REF_ID];
}
if(isset($_GET[Config::PARAM_NAV])){
	$mode = $_GET[Config::PARAM_NAV];
}

switch($mode){
	case "bocker-edit":
		$rubrik = "Ändra information för bok";
		$mode = "edit";
		break;
	case "bocker-add":
		$rubrik = "Lägga till en bok";
		$mode = "add";
		break;
	case "bocker-view":
		$rubrik = "Information om bok";
		$mode = "view";
		break;
	case "bocker-save":
		$rubrik = "Boken är sparad";
		$mode = "save";
		break;
	case "bocker-delete":
		$rubrik = "Boken har raderats";
		$mode = "delete";
		break;
		
}

//print "<h1>$rubrik</h1>";

if($mode == "delete"){
	$bok = new Bok();
	$bok->setFromId($_GET[Config::PARAM_REF_ID]);
	$deleteSuccess = $bok->delete();
} else {

	if(($mode == "save")&&($_POST["isbn"])){
		//var_dump($_POST);
		$newBok = new Bok($_POST);
		//print $newBok->toString();


		if($newBok->save()){
			$selectedBokId = $_POST["isbn"];
			$saveSuccess = true;
			$mode = "view"; // växlar läge till view för att se sparad data

		} else {
			$rubrik = "<span class=\"warning\">Sparandet av boken  misslyckades :(</span>";
			$saveSuccess = false;
			$mode = "add"; // växlar läge till add vid olycka
		}
	}

	$bok = new Bok();
	$antal = new Bokantal();
	if(($mode != "add")&&($saveSuccess)){
		$bok->setFromId($selectedBokId);
		//$antal = $bok->getAntalBokade();
		//$bok = getBokArray($selectedBokId);
	} 
	//print $bok->toString();

	if($mode == "view"){
		$titelUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_TITEL, "Titel", $bok->titel, displayValue($bok->titel), "", "col-md-8");
		$upplagaUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_UPPLAGA, "Upplaga", $bok->upplaga, displayValue($bok->upplaga), "", "col-md-4");

		$undertitelUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_UNDERTITEL, "Undertitel", $bok->undertitel, displayValue($bok->undertitel), "", "col-md-12");

		$isbnUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_ISBN, "ISBN", $bok->isbn, displayValue($bok->isbn), "", "col-md-4");
		$forlagUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_FORLAG, "Förlag", $bok->forlag, displayValue($bok->forlag), "", "col-md-4");
		$antalUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_ANTAL, "Antal exemplar", $bok->antal , displayValue($bok->antal), "# <strong>inköpta</strong> exemplar", "col-md-4");
		
		$utgivningUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_UTGIVNING, "Utgivningsår", $bok->utg_ar, displayValue($bok->utg_ar), "", "col-md-4");
		$fornamnUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_FORF_FNAMN, "Författare förnamn", $bok->forf_fornamn, displayValue($bok->forf_fornamn), "", "col-md-4");
		$efternamnUI = HTML_FACTORY::getStaticTextFieldHTML(Bok::FN_FORF_ENAMN, "Författare efternamn", $bok->forf_efternamn, displayValue($bok->forf_efternamn), "", "col-md-4");
	} else {
		$isbnUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_ISBN, "ISBN", $bok->isbn);
		$antalUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_ANTAL, "Antal exemplar", $bok->antal, "# <strong>inköpta</strong> exemplar");
		$titelUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_TITEL, "Titel", $bok->titel, "");
		$upplagaUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_UPPLAGA, "Upplaga", $bok->upplaga, "");
		$forlagUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_FORLAG, "Förlag", $bok->forlag);
		$utgivningUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_UTGIVNING, "Utgivningsår", $bok->utg_ar);
		$undertitelUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_UNDERTITEL, "Undertitel", $bok->undertitel, "");
		$fornamnUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_FORF_FNAMN, "Författare förnamn", $bok->forf_fornamn);
		$efternamnUI = HTML_FACTORY::getTextFieldHTML(Bok::FN_FORF_ENAMN, "Författare efternamn", $bok->forf_efternamn);
	}

	$arkiveradUI = HTML_FACTORY::getHiddenFieldHTML(Bok::FN_ARKIVERAD, $bok->arkiverad);

} // slut if DELETE

	if($mode == "delete"){ 
		if($deleteSuccess){
			print "<h2>Boken har raderats</h2>";
			print "<p>Observera att eventuella <strong>bokningar</strong> finns för boken finns kvar i arkiverat läge. Bara bokens ISBN är känt om boken för de arkiverade bokningarna</p>";
		} else {
			print "<h2>Ett fel har uppstått</h2>";
			print "<p>Boken har INTE raderats. Kontakta utvecklare (Martin)</p>";
		}
	} else {

			if($dbDebug != ""){ 
				//print $dbDebug; 
			} 
		?>
	
		<form id="form-bocker" method="post" action="<?php print $bok->urlSave ?>">
		<input type="hidden" id="form-mode" name="form-mode" value="idle" />
		
		<?php

			print "<div class=\"row\">";
			print $titelUI;
			print $upplagaUI;
			print "</div>";

			print "<div class=\"row\">";
			print $undertitelUI;
			print "</div>";

			print "<div class=\"row\">";
			print $isbnUI;
			print $forlagUI;
			print $antalUI;
			//if($mode != "add"){ print HTML_FACTORY::getStaticTextFieldHTML("antal-bokade", "Antal bokade", $antal->bokade, "", "# <strong>bokade</strong> exemplar"); }
			print "</div>";
		
			print "<div class=\"row\">";
			print $utgivningUI;
			print $fornamnUI;
			print $efternamnUI;
			print "</div>";

			// print $arkiveradUI; 
		?>

<?php } // slut if DELETE 2 ?>

<div class="btn-group btn-group-lg" role="group">
<?php 
	switch($mode){ 
		case "add":
			print HTML_FACTORY::getSubmitKnappHTML("Spara", $size = "", $flair = "success", $submitParam = "save", $title = "Spara boken");
			print HTML_FACTORY::getKnappHTML("bocker", "Avbryt", "", "warning", "Spara inte");
			break;
		case "edit":
			print HTML_FACTORY::getSubmitKnappHTML("Spara", $size = "", $flair = "success", $submitParam = "update", $title = "Spara ändringen");
			print HTML_FACTORY::getKnappHTML("bocker", "Avbryt", "", "warning", "Spara inte");
			break;
		default:
			if(isAdmin() && $mode !="delete"){
				//print getKnappHTML("bocker&" . $CONFIG["secNavParam"] . "=delete&" . $CONFIG["refIdParam"] . "=" . $bok["isbn"], "Radera", "button-red", "Radera boken", "big");
				print HTML_FACTORY::getSubmitKnappHTML("Radera", $size = "", $flair = "danger", $submitParam = "delete", $title = "Radera boken");
				print HTML_FACTORY::getKnappHTML("bocker-edit&" . Config::PARAM_REF_ID . "=" . $bok->isbn, "Ändra", "", "warning", "Ändra boken");
			}
			print HTML_FACTORY::getKnappHTML("bocker", "Tillbaka", "", "primary", "Till boklistan");
			
 	} 
?>

</div>


</form>

</div>
<?php 
	//HTML_FACTORY::printPanel("default", "Bokingar för boken", "<em>Boken har inga bokningar</em>");

function displayValue($value){
	if(empty($value)){
		return "---";
	} else {
		return $value;
	}
}

function submitForm(){

}
?>