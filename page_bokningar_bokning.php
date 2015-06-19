<?php
require_once("class_bokning.php");
require_once("class_html_factory.php");

$selectedBokningsId = "";
$mode = "view";
$rubrik = "Information om bokning";
$saveSuccess = true;
$dbDebug = "";
$userNotice = "";
$bokId = "";
$kursId = "";



switch($_GET[CONFIG::PARAM_NAV]){
	case "bokningar-edit":
		$mode = "edit";
		break;
	case "bokningar-add":
		$mode = "add";
		break;
	case "bokningar-save":
		$mode = "save";
		break;
	case "bokningar-delete":
		$mode = "delete";
		break;
	default:
		$mode = "view";
}
//print "<p>page_bokningar - början: $mode</p>";

$bokning = new Bokning();
if(isset($_GET[CONFIG::PARAM_REF_ID])){
	
	if(isset($_GET[Config::PARAM_REF_TYP])){
		// add med referens (klickat bokningsknapp från bok eller kurs)
		// då är $_GET[CONFIG::PARAM_REF_ID] INTE en bokning, utan något av nedanstående

		
		switch($_GET[CONFIG::PARAM_REF_TYP]){
			case "bok":
				$bokning->bokId = $_GET[CONFIG::PARAM_REF_ID];
				break;
			case "kurs":
				$bokning->kursId = $_GET[CONFIG::PARAM_REF_ID];
				break;
		}

		
	} else {
		if(($mode != "add")&&($mode != "save")){
			$bokning->setFromId($_GET[CONFIG::PARAM_REF_ID]);
		}
	}
	
}


if($mode == "delete"){
	// SQL DELETE
	if($bokning->delete()){
		$deleteSuccess = true;
	} else {
		$deleteSuccess = false;
	} 

} // TOG BORT ELSE HÄR


if(($mode == "save")&&($_POST["select-bok"])){
	
	$bokning->setFromData($_POST["select-kurs"], $_POST["select-bok"], $_POST["select-larare"], $_POST["input-kommentar"]);

	if(!Bokning::bokningExists($bokning->bokId, $bokning->kursId)){
		// om bokningen - kombon kurs-bok -  inte finns tidigare
		try {

			$bokning->save();
			$saveSuccess = true;
			$mode = "view"; // växlar läge till view för att se sparad data
			HTML_FACTORY::printInfoAlert("Genomfört", "Bokningen har sparats.");

		} catch (Exception $e) {
			$rubrik = "<span class=\"warning\">Bokningen misslyckades :(</span>";
			$saveSuccess = false;
			$mode = "add"; // växlar läge till add vid olycka
			HTML_FACTORY::printErrorAlert("Bokningen misslyckades :´(", $e->getMessage());
		}

	} else {
		// tillåt inte bokningen om kombon kurs-bok redan finns
		$b = new Bok();
		$b->setFromId($bokning->bokId);
		HTML_FACTORY::printWarningAlert("Bokningen genomfördes INTE:", "Boken '".$b->fullTitel."' är redan bokad till kursen ".$bokning->kursId);
		$mode = "add"; // växlar läge till add vid olycka
	}
}

if($mode == "add"){

	$bokning->datum = time();
	$bokning->demo = 0;
	$bokning->arkiverad = 0;
	
}

if(isset($bokning->bokId)){
	$bok = new Bok();
	$bok->setFromId($bokning->bokId);
} else {
	$bok = null;
}

if(isset($bokning->kursId)){
	$kurs = new Kurs();
	$kurs->setFromId($bokning->kursId);
} else {
	$kurs = null;
}

if($mode == "view"){

	$bokUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-bok", "Bok", $bokning->bokId, $bok->getFullBokTitel() , "Vald bok för bokningen");
	$kursUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-kurs", "Kurs", $bokning->kursId, $kurs->namn, "Vald kurs för bokningen");
	
	$lararUI = HTML_FACTORY::getStaticTextFieldHTML("select-larare", "Bokningslärare", $bokning->bokare, "", "Läraren som <strong>gör bokning</strong>");
	
	$kommentarUI = HTML_FACTORY::getStaticTextFieldHTML("input-kommentar", "Kommentar", $bokning->kommentar, "", "", "400");
} else {
	//var_dump($bok);
	//if(isset($bok)){
		//$bokUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-bok", "Bok", $bok->id, $bok->fullTitel, "Vald bok för bokningen");
	//} else {
		$bokUiHTML = Bok::getSelectHTML("Välj vilken bok som ska bokas till en kurs", $bokning->bokId);
	//}
	//if(isset($kurs)){
		//$kursUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-kurs", "Kurs", $kurs->id, $kurs->namn, "Vald kurs för bokningen");
	//} else {
		$kursUiHTML = Kurs::getSelectHTML(NULL, "Välj vilken kurs boken ska bokas till", $bokning->kursId);
	//}

	$lararUI = Larare::getSelectHTML("Läraren som <strong>gör bokning</strong> (du). Systemet känner redan till undervisningsläraren.", $bokning->bokare);
	$kommentarUI = HTML_FACTORY::getTextareaHTML("input-kommentar", "Kommentar", "Skriv en kommentar om du vill ge biblioteket någon extra-information om den här bokningen", $bokning->kommentar, "100%");
}
//} // slut if DELETE
?>
<?php if(isLoggedin()) { ?>
<script type="text/javascript">
	function checkForm(mode){
		mnnDebug("bokning-checkForm", "Kontrollera form. mode = '"+mode+"'");
		//alert("Kontrollera form. mode = '"+mode+"'");
		
		if(mode == "delete"){
			mnnDebug("bokning-checkForm", "Delete-läge: skapar bekräftelse");
			dialogConfirmation("Bekräfta", "Vill du verkligen <strong>RADERA</strong> bokningen?", "sendDelete();")
		} else {
			mnnDebug("bokning-checkForm", "Startar submit");
			submitForm(mode);
		}
	}
	
	function sendDelete(){
		window.location.href = "<?php print $bokning->urlDelete; ?>";	
	}
	
	function submitForm(mode){
		mnnDebug("bokning-submitForm", "Skickar form. mode = '"+mode+"'");
		//alert("Skickar form. mode = '"+mode+"'");
		var theForm = document.getElementById("form-bokning");
		$("#form-mode").val(mode);
		theForm.submit();
	}
</script>
<?php } ?>

<?php 
// Sätter slutgiltiga UI-värden för mode här - den ursprungliga kan ha ändrats ovan
switch($mode){
	case "edit":
		$rubrik = "Ändra bokningen";
		$userNotice = "<div class=\"info-box\">Du kan <strong>inte</strong> ändra bok eller kurs i efterhand. Om kurs och/eller bok behöver ändras för bokningen så måste en ny bokning skapas (och den här bör först raderas)</div>";
		break;
	case "add":
		$rubrik = "Gör en bokning";
		break;
	case "save":
		$rubrik = "Bokningen är sparad";
		break;
	case "delete":
		$rubrik = "Bokningen har raderats";
		break;
	default:
		$rubrik = "Information om bokning";
}
//print "<p>page_bokningar - slutet: $mode</p>";
?>
<h1><?php print $rubrik ?></h1>

<?php 
	if($mode == "delete"){ 
		if($deleteSuccess){
			print "<h2>Bokningen har raderats</h2>";
			//print "<p>Observera att eventuella <strong>bokningar</strong> finns för boken finns kvar i arkiverat läge. Bara bokens ISBN är känt om boken för de arkiverade bokningarna</p>";
		} else {
			print "<h2>Ett fel har uppstått</h2>";
			print "<p>Bokningen har INTE raderats. Kontakta utvecklare (Martin)</p>";
			print "<p>SQL: $q</p>";
		}
	} else {
?>

<?php //if($dbDebug != ""){ print $dbDebug; } ?>

<?php 
	if($userNotice != ""){ 
		print $userNotice; 
	} 
?>

<div id="form-bokning-container" class="form-container">

<form id="form-bokning" method="post" action="<?php print Bokning::getSaveUrl(); ?>">
<input type="hidden" id="form-mode" name="form-mode" value="idle" />
<?php print $bokUiHTML; ?>
<?php print $kursUiHTML; ?><br />
<?php print $lararUI; ?><br />
<?php print $kommentarUI; ?>

<?php } // slut if DELETE 2 ?>
<div class="submit-container btn-group">
<?php 
	switch($mode){ 
		case "add":
			print HTML_FACTORY::getSubmitKnappHTML("Boka", "", "success", "bokningar-save", "Skapa bokningen");
			//getSubmitKnappHTML($label, $size = "", $flair = "success", $submitParam = "save", $title = "")
			print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bokningar", "Avbryt", "", "warning", "Avbryt bokningen");

			break;
		case "edit":
			print HTML_FACTORY::getSubmitKnappHTML("Boka", "", "success", "bokningar-save", "Spara ändringen");
			print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bokningar", "Avbryt", "", "warning", "Avbryt bokningen");
			break;
		case "delete":
			if(isAdmin()){
				//print getKnappHTML("bokningar&" . $CONFIG["secNavParam"] . "=delete&" . $CONFIG["refIdParam"] . "=" . $bokning["bok_id"]. "," . $bokning["kurs_id"], "Radera", "button-red", "Radera bokningen", "big");
				print HTML_FACTORY::getSubmitKnappHTML("Radera", "", "danger", "bokningar-delete", "Radera");
				print HTML_FACTORY::getKnappHTML("bokningar", "Avbryt", "", "warning");
			}
			
			
 	}
 	print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bokningar", "Tillbaka", "", "primary", "Tillbaka till alla bokningar"); 
?>
</div>
</form>

</div>