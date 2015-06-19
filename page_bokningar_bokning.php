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
		$rubrik = "Ändra bokningen";
		$userNotice = "<div class=\"info-box\">Du kan <strong>inte</strong> ändra bok eller kurs i efterhand. Om kurs och/eller bok behöver ändras för bokningen så måste en ny bokning skapas (och den här bör först raderas)</div>";
		$mode = "edit";
		break;
	case "bokningar-add":
		$rubrik = "Gör en bokning";
		$mode = "add";
		break;
	case "bokningar-save":
		$rubrik = "Bokningen är sparad";
		$mode = "save";
		break;
	case "bokningar-delete":
		$rubrik = "Bokningen har raderats";
		$mode = "delete";
		break;
	default:
		$rubrik = "Information om bokning";
		$mode = "view";
}
print "<p>$mode (should be 'view')</p>";

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
				$bokning->kursd = $_GET[CONFIG::PARAM_REF_ID];
				break;
		}

		
	} else {
		$bokning->setFromId($_GET[CONFIG::PARAM_REF_ID]);
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
	
	if($bokning->save()){
		$saveSuccess = true;
		$mode = "view"; // växlar läge till view för att se sparad data
		$dbDebug = "<p class=\"varning\">result:[" . $result . "]</p>";
		$dbDebug = $dbDebug . "<p class=\"varning\">bok: " . $_POST["select-bok"] . "</p>";
		$dbDebug = $dbDebug . "<p class=\"varning\">bok: " . $_POST["select-kurs"] . "</p>";
		$dbDebug = $dbDebug . "<p class=\"varning\">Databasinfo: " . mysql_info() . "</p>";
		$dbDebug = $dbDebug . "<p class=\"varning\">Databasfel: " . mysql_error() . "</p>";
		$dbDebug = $dbDebug . "<p class=\"varning\">SQL källa: " . $q . "</p>";

	} else {
		$rubrik = "<span class=\"warning\">Bokningen misslyckades :(</span>";
		$saveSuccess = false;
		$mode = "add"; // växlar läge till add vid olycka
		$dbDebug = "<p class=\"varning\">Databasfel: " . mysql_error() . "</p>";
		$dbDebug = $dbDebug . "<p class=\"varning\">SQL källa: " . $q . "</p>";
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

if($mode == "view"){

	$bokUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-bok", "Bok", $bokning->id, $bok->getFullBokTitel() , "Vald bok för bokningen");
	$kursUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-kurs", "Kurs", $bokning->kursId, $bokning->kursId , "Vald kurs för bokningen");
	
	$intidUI = HTML_FACTORY::getStaticTextFieldHTML("select-in-tillfalle", "Återlämningstillfälle", $bokning->inTillfalle->desc, "", "Tillfälle då boken ska lämnas <strong>tillbaka</strong>");
	$inlasarUI = HTML_FACTORY::getStaticTextFieldHTML("select-in-lasar", "Läsår - återlämning", $bokning["in_lasar_id"]);
	
	$uttidUI = HTML_FACTORY::getStaticTextFieldHTML("select-ut-tillfalle", "Utlåningstillfälle", $bokning->utTillfalle->desc, "", "Tillfälle då boken ska lämnas <strong>ut</strong>");
	$utlasarUI = HTML_FACTORY::getStaticTextFieldHTML("select-ut-lasar", "Läsår - utlåning", $bokning["ut_lasar_id"]);
	$lararUI = HTML_FACTORY::getStaticTextFieldHTML("select-larare", "Bokningslärare", $bokning["larar_id"], "", "Läraren som <strong>gör bokning</strong>");
	
	$kommentarUI = HTML_FACTORY::getStaticTextFieldHTML("input-kommentar", "Kommentar", $bokning["kommentar"], "", "", "400");
} else {
	//var_dump($bok);
	if(isset($bok)){
		$bokUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-bok", "Bok", $bok->id, $bok->fullTitel , "Vald bok för bokningen");
	} else {
		$bokUiHTML = HTML_FACTORY::getSelectBokHTML("Välj vilken bok som ska bokas till en kurs", $bokning->bokId);
	}
	if(!empty($bokning->kursId)){
		$kursUiHTML = HTML_FACTORY::getStaticTextFieldHTML("select-kurs", "Kurs", $bokning->kursId, $bokning->kursId , "Vald kurs för bokningen");
	} else {
		$kursUiHTML = HTML_FACTORY::getSelectKursHTML("Välj vilken kurs boken ska bokas till", $bokning->kursId);
	}
	//if(!empty($bokning->inTid)){
		//$intidUI = HTML_FACTORY::getSelectTillfalleHTML("Återlämningstillfälle", "Välj vid vilket tillfälle boken ska lämnas <strong>tillbaka</strong>", $bokning->inTillfalle->id, true);
	//} else {
		//$intidUI = HTML_FACTORY::getSelectTillfalleHTML("Återlämningstillfälle", "Välj vid vilket tillfälle boken ska lämnas <strong>tillbaka</strong>");
	//}
	//if(!empty($bokning->utTid)){
		//$uttidUI = HTML_FACTORY::getSelectTillfalleHTML("Utlåningstillfälle", "VVälj vid vilket tillfälle boken ska lämnas <strong>ut</strong>", $bokning->utTillfalle->id, false);
	//} else {
		//$uttidUI = HTML_FACTORY::getSelectInUtTillfalleHTML("Utlåningstillfälle", "Välj vid vilket tillfälle boken ska lämnas <strong>ut</strong>");
	//}
	//$lararUI = HTML_FACTORY::getSelectLarareHTML("Bokningslärare", "Läraren som <strong>gör bokning</strong> (du). Systemet känner redan till undervisningsläraren.", $bokning->bokare);
	//$kommentarUI = HTML_FACTORY::getTextareaHTML("input-kommentar", "Kommentar", "Skriv en kommentar om du vill ge biblioteket någon extra-information om den här bokningen", $bokning->kommentar);
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
		window.location.href = "<?php print "?" . $CONFIG["primNavParam"] . "=bokningar&" . $CONFIG["secNavParam"] . "=delete&" . $CONFIG["refIdParam"] . "=" . $bokning["bok_id"]. "," . $bokning["kurs_id"]; ?>";	
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
<?php $action = "?".$CONFIG["primNavParam"]."=bokningar&".$CONFIG["secNavParam"]."=save" ?>
<form id="form-bokning" method="post" action="<?php print $action ?>">
<input type="hidden" id="form-mode" name="form-mode" value="idle" />
<?php print $bokUiHTML; ?>
<?php print $kursUiHTML; ?><br />
<?php print $uttidUI; ?>
<?php print $utlasarUI; ?><br />
<?php print $intidUI; ?>
<?php print $inlasarUI; ?><br />
<?php print $lararUI; ?><br />
<?php print $kommentarUI; ?>

<?php } // slut if DELETE 2 ?>
<div class="submit-container">
<?php 
	switch($mode){ 
		case "add":
			print HTML_FACTORY::getSubmitKnappHTML("Boka", "Skapa bokningen", "", "success", "bokningar-save");
			//getSubmitKnappHTML($navValue, $label, $size = "", $flair = "success", $submitParam = "save", $title = "")
			print HTML_FACTORY::getKnappHTML("bokningar", "Avbryt", "", "warning");
			//getKnappHTML($navValue, $label, $size = "", $flair = "primary", $title = "")
			break;
		case "edit":
			print HTML_FACTORY::getSubmitKnappHTML("Boka", "Spara ändringen", "", "success", "save");
			print HTML_FACTORY::getKnappHTML("bokningar", "Avbryt", "", "warning");
			break;
		case "delete":
			if(isAdmin()){
				//print getKnappHTML("bokningar&" . $CONFIG["secNavParam"] . "=delete&" . $CONFIG["refIdParam"] . "=" . $bokning["bok_id"]. "," . $bokning["kurs_id"], "Radera", "button-red", "Radera bokningen", "big");
				print HTML_FACTORY::getSubmitKnappHTML("Radera", "Radera", "", "danger", "delete");
				print HTML_FACTORY::getKnappHTML("bokningar", "Avbryt", "", "warning");
			}
			
			
 	}
 	print HTML_FACTORY::getKnappHTML("bokningar", "Tillbaka", "", "primary", "Tillbaka till alla bokningar"); 
?>
</div>
</form>

</div>