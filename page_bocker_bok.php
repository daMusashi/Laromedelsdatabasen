<?php
require_once("class_bok.php");
require_once("page_functions_navs.php");

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
		$mode = "edit";
		break;
	case "bocker-add":
		$mode = "add";
		break;
	case "bocker-view":
		$mode = "view";
		break;
	case "bocker-save":
		$mode = "save";
		break;
	case "bocker-delete":
		$mode = "delete";
		break;
		
}

if(isLoggedin()) { 
	include("include_js_form_bok.php");
}

//print "<h1>$rubrik</h1>";

$bok = new Bok();

if($mode == "delete"){
	$bok->setFromId($_GET[Config::PARAM_REF_ID]);
	$deleteSuccess = $bok->delete();
} else {

	if($mode == "save"){
		$bok->setFromForm($_POST);
		//print $newBok->toString();

		try {
			$bok->save();
			$saveSuccess = true;
			$mode = "view"; // växlar läge till view för att se sparad data
			$_SESSION["datalagerDataChanged"] = true;

		} catch (Exception $e) {
			$rubrik = "<span class=\"warning\">Sparandet av boken misslyckades :(</span>";
			$saveSuccess = false;
			$mode = "add"; // växlar läge till add vid olycka
			HTML_FACTORY::printErrorAlert("Ett fel inträffade när boken skulle sparas :´(", $e->getMessage());
		}
	}

	
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
	$idUI = HTML_FACTORY::getHiddenFieldHTML(Bok::FN_ID, $bok->id);

} // slut if DELETE

	if($mode == "delete"){ 
		if($deleteSuccess){
			print "<div class=\"alert alert-success\" role=\"alert\">Boken <strong>".$bok->getFullBokTitel()."</strong> har raderats</div>";
		} else {
			print "<h2>Ett fel har uppstått</h2>";
			print "<p>Boken har INTE raderats. Kontakta utvecklare (Martin)</p>";
		}
	} else {

		switch($mode){
			case "edit":
				$rubrik = "Ändra information för '".$bok->titel."'";
				break;
			case "add":
				$rubrik = "Lägg till läromedel";
				break;
			case "view":
				$rubrik = "Information om '".$bok->titel."'";
				$mode = "view";
				break;
			case "save":
				$rubrik = "Läromedlet är sparad";
				break;
			case "delete":
				$rubrik = "Läromedlet har raderats";
				break;
			} 
		?>
		
		<h2><?php print $rubrik ?></h2>

		<form id="form-bocker" class="<?php print $mode; ?>" method="post" action="<?php print $bok->getSaveUrl(); ?>">
		<input type="hidden" id="form-mode" name="form-mode" value="idle" />
		
		<?php if($mode == "view") { ?>
			<div class="well">
		<?php } ?>

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

			print $arkiveradUI; 
			print $idUI; 
		?>

		<?php if($mode == "view") { ?>
			</div>
		<?php } ?>

<?php } // slut if DELETE 2 ?>

<div class="btn-group btn-group-lg" role="group">
<?php 
	switch($mode){ 
		case "add":
			print HTML_FACTORY::getSubmitKnappHTML("Spara", $size = "", $flair = "success", $submitParam = "save", $title = "Spara boken");
			print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bocker", "Avbryt", "", "warning", "Spara inte");
			break;
		case "edit":
			print HTML_FACTORY::getSubmitKnappHTML("Spara", $size = "", $flair = "success", $submitParam = "save", $title = "Spara ändringen");
			print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bocker", "Avbryt", "", "warning", "Spara inte");
			break;
		default:
			print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bocker", "Tillbaka", "", "primary", "Till boklistan");
			if(isLoggedin()){
			    print HTML_FACTORY::getKnappHTML($bok->urlEdit, "Redigera bok", "sm", "warning");
			}
			
 	} 
?>

</div>
</form>
</div>


<?php if(($mode == "view")&&(isLoggedIn())){ ?>
<div style="margin-top:40px">
<h2>Bokningar för '<?php print $bok->titel ?>'</h2>
<?php 
	$activeTermin = new Termin();
	$activeTermin->setFromId($_SESSION["active-termin"]);
	print getTabsAjaxHTML("bok-bokningar-termniner-tab", "bokningar", $activeTermin->id, "get-bokningar-boklist", "ajax-list-container", true, $bok->id); 
?>
<div id="ajax-list-container" class="well"></div>
</div>
<script>
	$(document).ready(function(){
		$('#ajax-list-container').html('<?php print Config::LOADING_HTML; ?>');
		$.get('ajax.php?<?php print Config::PARAM_AJAX; ?>=get-bokningar-boklist&<?php print Config::PARAM_ID; ?>=<?php print $_SESSION["active-termin"]; ?>&<?php print Config::PARAM_REF_ID; ?>=<?php print $bok->id; ?>', function(data){
			$('#ajax-list-container').html(data);
		});
	});
</script>
<?php } ?>
<?php 
	//HTML_FACTORY::printPanel("default", "Bokingar för boken", "<em>Boken har inga bokningar</em>");

function displayValue($value){
	if(empty($value)){
		return "---";
	} else {
		return $value;
	}
}


?>