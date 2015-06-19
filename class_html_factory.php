<?php
require_once("class_bokning.php");

class HTML_FACTORY {

	function _getSkrivutKnapp($printElement, $label, $title, $size, $color){
		$html = "<a href=\"#\" class=\"button-$size button-$color\" onclick=\"printElement('" . $printElement . "');\" title=\"$title\">$label</a>";
		
		return $html;
	}


	public static function printPanel($flair, $heading, $content){
		$html = "<div class=\"panel panel-$flair\">
					<div class=\"panel-heading\">$heading</div>
					<div class=\"panel-body\">
						$content
					</div>
				</div>
		";
		print $html;
	}

	public static function printInfoAlert($titel, $content){
		print self::getAlertHTML($titel, $content, "info");
	}

	public static function printWarningAlert($titel, $content){
		print self::getAlertHTML($titel, $content, "warning");
	}

	public static function printErrorAlert($titel, $content){
		print self::getAlertHTML($titel, $content, "danger");
	}

	public static function printDangerAlert($titel, $content){
		print self::getAlertHTML($titel, $content, "danger");
	}

	public static function getAlertHTML($titel, $content, $flair = "primary"){
		$html = "<div class=\"alert alert-$flair\">";
		$html .= "<div><strong>$titel</strong> $content</div>";
		$html .= "</div>";

		return $html;
	}

	private static function getKnappHTML_base($url, $label, $size = "", $flair="primary", $title = "", $submit = false, $submitParam = "save"){

		if($submit){
			$html = "<a href=\"#\" class=\"btn btn-$flair btn-$size\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"$title\" onclick=\"checkForm('$submitParam');\" role=\"button\">$label</a>";
		} else {
			$html = "<a href=\"$url\" class=\"btn btn-$flair btn-$size\" title=\"$title\" onclick=\"checkForm('$submitParam');\" role=\"button\">$label</a>";
		}
		
		return $html;
	}

	public static function getKnappHTML($url, $label, $size = "", $flair = "primary", $title = ""){
		
		return self::getKnappHTML_base($url, $label, $size, $flair, $title);
	}

	public static function getSubmitKnappHTML($label, $size = "", $flair = "success", $submitParam = "save", $title = ""){

		return self::getKnappHTML_base("#", $label, $size, $flair, $title, true, $submitParam);
	}

	public static function getBokaKnappHTML($size = "", $referensTyp = "", $referensID = "", $title = "Gör en bokning!"){
		if($referensTyp == ""){
			$ref="";
		} else {
			$ref="&".CONFIG::PARAM_REF_TYP."=".$referensTyp."&".CONFIG::PARAM_REF_ID."=".$referensID;
		}
		
		return self::getKnappHTML("?".Config::PARAM_NAV."=bokningar-add&".$ref, "Boka", $size, "success", $title);
	}


	public static function getBokningarHTML($terminObj, $where = "", $orderOn = ""){

		$kurserInTermin = Kurs::getAllForTermin($terminObj->id);
		//print "<p>factory: getbokningarHTML antal kurser:".count($kurserInTermin)."</p>";
		$bokningar = [];

		foreach ($kurserInTermin as $kurs) {
			$bokningar = array_merge($bokningar, Bokning::getForKurs($kurs->id));
		}

		
		$hover = "";
		if(isLoggedin()){ 
			$hover = " table-hover";
		}
		$html = "<table class=\"table main$hover table-striped bockningar\"><thead><tr>";
		$html .= "<th>Kurs</th>";
		$html .= "<th>Bok</th>";
		if(!Config::SIMPLE_MODE){
			$html .= "<th>Antal</th>";
			$html .= "<th><span class=\"slut\">Överbokad</span></th>";
			$html .= "<th>Hämtas<br />ut</th>";
			$html .= "<th>Lämnas<br />in</th>";
		}
		$html .= "<th>Bokad</th>";
		$html .= "<th>Bokare</th>";
		$html .= "</tr></thead><tbody>";


		$index = 0;
		foreach($bokningar as $bokning){
			$bok = new Bok();
			$bok->setFromId($bokning->bokId);

			$antalObj = $bok->getAntalBokade($terminObj->id);

			$kurs = new Kurs();
			$kurs->setFromId($bokning->kursId);
	
			if(!$antalObj->bokbar){
				$overBooked = "<span class=\"slut\">ÖVERBOKAD med " . $antalObj->bokbara. "</span>";
			} else {
				$overBooked = "";
			}
			
			$html = $html .  "<tr>";
			
			$html = $html .  "<td class=\"major\">" . $bokning->kursId. "</td>";
			$html = $html .  "<td class=\"major\">" . $bok->fullTitel . "</td>";
			//$html = $html .  "<td>" . $bokning["bok_id"] . "</td>";
			if(!Config::SIMPLE_MODE){
				$html = $html .  "<td>" . Kurs::getAntalElever($kurs->id) . "</td>";
				$html = $html .  "<td>" . $overBooked . "</td>";
				
				$html = $html .  "<td class=\"minor\">" . $kurs->startTermin->hamtasDesc . "</td>";
				$html = $html .  "<td class=\"minor\">" . $kurs->slutTermin->lamnasDesc . "</td>";
			}
			$html = $html .  "<td class=\"minor\">" . $bokning->datum. "</td>";
			$html = $html .  "<td>" . $bokning->bokare . "</td>";
			//$html = $html .  "<td>" . getKnappHTML("bokningar&" . $CONFIG["secNavParam"] . "=view&" . $CONFIG["refIdParam"] . "=" . $bokning["bok_id"]. "," . $bokning["kurs_id"], "Detaljer", "button-orange", "Se all information om bokningen") . "</td>";
			
			$html = $html .  "</tr>";
			$index++;
		}

		$html = $html . "</tbody></table>";
		
		return $html;
	}


	/********************************
	/*  FORMS
	/********************************/

	public static function getTextFieldHTML($fieldName, $label, $value, $fieldDescription = "", $width = "", $locked = false){
		//$disabled = "";
		//$inputWidth = "";
		
		
			
		//if($width == ""){
		//	$width = "180";
		//}
		
		// kompensera för padding
		//$padding = 6;
		//$border = 1;
		//$inputWidth = "style=\"width:" . ($width - (2*($padding + $border)))."px\"";
		//$width = "style=\"width:".$width."px\"";
		
		$disabled = "";
		if($locked){
			$disabled = " readonly";
		}
		
		$html = "<div class=\"form-group\">";
		$html = $html . "<label for=\"$fieldName\">$label</label>";
		$html = $html . "<input type=\"text\" value=\"$value\" id=\"$fieldName\" name=\"$fieldName\" class=\"form-control\"$disabled>";
		if($fieldDescription != ""){
			$html = $html . "<p class=\"help-block\">$fieldDescription</p>";
		}
		$html = $html . "</div>";
		
		return $html;
	}

	public static function getStaticTextFieldHTML($fieldName, $label, $value, $displayValue = "", $fieldDescription = "", $gridClass = ""){
		
		//if($width != ""){
			//$width = "style=\"width:".$width."px\"";
		//}
		
		if($displayValue == ""){
			$displayValue = $value;
		}

		if(!empty($gridClass)){
			$gridClass = " $gridClass";
		}
		
		$html = "<div class=\"form-group$gridClass\">";
		$html = $html . "<label for=\"$fieldName\">$label</label>";
		$html = $html . "<p class=\"form-control-static\">$displayValue</p>";
		$html = $html . "<input type=\"hidden\" value=\"$value\" id=\"$fieldName\" name=\"$fieldName\">";
		if($fieldDescription != ""){
			$html = $html . "<p class=\"help-block\">$fieldDescription</p>";
		}
		$html = $html . "</div>";
		
		return $html;
	}

	public static function getHiddenFieldHTML($fieldName, $value){
		
		$html = "<input type=\"hidden\" value=\"$value\" id=\"$fieldName\" name=\"$fieldName\">";
		
		return $html;
	}

	// Selecters

	public static function getAssocArrayAsSelectHTML($assocArr, $selectName, $selectLabel = "Välj...", $fieldLabel = "", $fieldDescription = "", $selectedKey = "", $width = "", $elementId = ""){
		if($elementId == ""){
			$elementId = $selectName;
		}
		
		//if($width != ""){
		//	$width = "style=\"width:".$width."px\"";
		//}
		
		$html = "<div class=\"form-group\">";
		if($fieldLabel != ""){
			$html = $html . "<label for=\"$selectName\">$fieldLabel</label>";
		}
		$html = $html . "<select id=\"$elementId\" name=\"$selectName\" class=\"form-control\">";
		$html = $html . "<option value=\"null\">$selectLabel</option>";
		foreach($assocArr as $key=>$label){
			//print "<p>$selectedKey == $key</p>";
			if($selectedKey == $key){
				$select = " selected";	
			} else {
				$select = "";	
			}
			$html = $html . "<option$select value=\"$key\">$label</option>";
		}
		$html = $html . "</select>";
		if($fieldDescription != ""){
			$html = $html . "<p class=\"help-block\">$fieldDescription</p>";
		}
		$html = $html . "</div>";
		return $html;
	} // getInutTillfallenAsAssocArray

	public static function getSelectKursHTML($fieldDescription = "", $selectedId = "", $elementId = "select-kurs"){
		return self::getAssocArrayAsSelectHTML(Kurs::getAllAsSelectAssoc(), $elementId, "Välj en kurs...", "Kurs", $fieldDescription, $selectedId, "180", $elementId);
	}

	/*public static function getSelectKursWithBokningarHTML($fieldDescription = "", $selectedId = "", $elementId = "select-kurs"){
		return self::getAssocArrayAsSelectHTML(Kurs::getAllAsSelectAssoc(""), $elementId, "Välj en kurs...", "Kurs", $fieldDescription, $selectedId, "", $elementId);
	}*/

	public static function getSelectBokHTML($fieldDescription = "", $selectedId = "", $elementId = "select-bok"){
		$selectArr = getAssocArray(getBockerAsArray(), "isbn", "fulltitel");
		return self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj en bok...", "Bok", $fieldDescription, $selectedId, "300", $elementId);
	}

	public static function getSelectKlassHTML($fieldDescription = "", $selectedId = "", $elementId = "select-klass"){
		$selectArr = getAssocArray(getKlasserAsArray(), "id", "id");
		return self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj en klass...", "Klass", $fieldDescription, $selectedId, "", $elementId);
	}

	public static function getSelectKlassWithBokningarHTML($fieldDescription = "", $selectedId = "", $elementId = "select-klass"){
		$selectArr = getKlasserWithBokningarAsAssocArray();
		//print_r($selectArr);
		return self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj en klass...", "Klass", $fieldDescription, $selectedId, "", $elementId);
	}

	public static function getSelectElevFromKlassHTML($klassId, $fieldDescription = "", $selectedId = "", $elementId = "select-elev"){
		$selectArr = getAssocArray(getEleverFromKlassAsArray($klassId), "elevid", "fullNamn");
		return self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj elev...", "Enskild elev från $klassId", $fieldDescription, $selectedId, "", $elementId);
	}

	public static function getSelectLarareHTML($fieldLabel = "Lärare", $fieldDescription = "", $selectedId = "", $elementId = "select-larare"){
		return self::getAssocArrayAsSelectHTML(Larare::getAllAsSelectAssoc(), $elementId, "Välj en lärare...", $fieldLabel, $fieldDescription, $selectedId, "180");
	}

	public function getSelectTillfalleHtml($fieldLabel = "Tillfälle", $fieldDescription = "", $selectedId = "", $isIn = false){
		$name = "ut";
		if($isIn){
			$name = "in";
		}

		$aselectArr = [];
		foreach(CONFIG::$TILLFALLEN_DATA->tillfallen as $tillfalle){
			$selectArr[$tillfalle->id] = $tillfalle->desc;
		}

		return self::getAssocArrayAsSelectHTML($selectArr, "select-tillfalle-$name", "Välj ett tillfälle...", $fieldLabel, $fieldDescription, $selectedId);
	}

	public static function getTextareaHTML($areaName, $fieldLabel = "", $fieldDescription = "", $value = "", $width = ""){
		
		if($width != ""){
			$width = "style=\"width:".$width."\"";
		}
		
		$html = "<div class=\"field-container\" $width>";
		if($fieldLabel != ""){
			$html = $html . "<label for=\"$areaName\">$fieldLabel</label><br>";
		}
		$html = $html . "<textarea id=\"$areaName\" name=\"$areaName\" class=\"form-field form-textarea\" $width >$value</textarea>";

		if($fieldDescription != ""){
			$html = $html . "<p class=\"description\">$fieldDescription</p>";
		}
		$html = $html . "</div>";
		return $html;
	}


	public static function getBokTdInfoSnippet($index, $bokObj, $antalObj = null, $bokningsObj = null){
		

		$collapseHTML = "";
		$collapseId = "bok-info-$index";



		if(isLoggedIn()){
			
			
			$collapseHTML .= "<div class=\"collapse bok-info\" id=\"$collapseId\">";
			if(!empty($antalObj)){
				$collapseHTML .= "<p>Bokade: <strong>" . $antalObj->bokade . "</strong> av " . $antalObj->antal . "</p>";
			}
			$collapseHTML .= "<div class=\"btn-group btn-group-sm\" role=\"group\">";
			if(!empty($bokningsObj)){
				// bokningsknappar
				$collapseHTML .= self::getKnappHTML($bokningsObj->urlView, "Visa bokning", "sm", "primary");
				if(isAdmin()){
					$collapseHTML .= self::getKnappHTML($bokningsObj->urlEdit, "Redigera bokning", "sm", "warning");
					$collapseHTML .= self::getKnappHTML($bokningsObj->urlDelete, "Radera bokning", "sm", "danger");
				}
			} else {
				// bokknappar
				$collapseHTML .= self::getKnappHTML($bokObj->urlView, "Visa bok", "sm", "primary");
				if(isAdmin()){
					$collapseHTML .= self::getKnappHTML($bokObj->urlEdit, "Redigera bok", "sm", "warning");
					$collapseHTML .= self::getKnappHTML($bokObj->urlDelete, "Radera bok", "sm", "danger");
				}
			}
			$collapseHTML .= "</div></div>";
		} 
	

		$html = "<div class=\"bok-titel\">";		
			if(isLoggedIn()){
				$html .= "<a data-toggle=\"collapse\" href=\"#$collapseId\" aria-expanded=\"false\" aria-controls=\"$collapseId\" class=\"titel-link collapsed\">";
					$html .= "<strong>" . $bokObj->fullTitel . "</strong>";
				$html .= "</a>";
			} else {
				$html .= "<strong>" . $bokObj->fullTitel . "</strong>";
			}
		$html .= "</div>";
		$html .= $collapseHTML;

		return $html;

	}

	// Widgets
	function createAjaxSelect($title, $assocArr, $onChange, $id){
		global $CONFIG;
		print "<select name=\"$id\" id=\"$id\" onchange=\"$onChange\">";
		print "<option value=\"". $CONFIG["nullVauleForSelects"]  . "\">$title</option>";
		foreach($assocArr as $etikett => $value){
			print "<option value=\"$value\">$etikett</option>";
		}
		print "</select>";
	}

}

?>