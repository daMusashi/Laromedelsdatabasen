<?php
require_once("class_bokning.php");

class HTML_FACTORY {

	function _getSkrivutKnapp($printElement, $label, $title, $size, $color){
		$html = "<a href=\"#\" class=\"button-$size button-$color\" onclick=\"printElement('" . $printElement . "');\" title=\"$title\">$label</a>";
		
		return $html;
	}


	private static function getKnappHTML_base($navValue, $label, $size = "", $flair="primary", $title = "", $submit = false, $submitParam = "save"){

		if($submit){
			$html = "<a href=\"#\" class=\"btn btn-$flair btn-$size\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"$title\" onclick=\"checkForm('$submitParam');\" role=\"button\">$label</a>";
		} else {
			$html = "<a href=\"?".CONFIG::PARAM_NAV."=$navValue\" class=\"btn btn-$flair btn-$size\" title=\"$title\" onclick=\"checkForm('$submitParam');\" role=\"button\">$label</a>";
		}
		
		return $html;
	}

	public static function getKnappHTML($navValue, $label, $size = "", $flair = "primary", $title = ""){
		
		return Self::getKnappHTML_base($navValue, $label, $size, $flair, $title);
	}

	public static function getSubmitKnappHTML($label, $size = "", $flair = "success", $submitParam = "save", $title = ""){

		return Self::getKnappHTML_base("", $label, $size, $flair, $title, true, $submitParam);
	}

	public static function getBokaKnappHTML($size = "", $referensTyp = "", $referensID = "", $title = "Gör en bokning!"){
		if($referensTyp == ""){
			$ref="";
		} else {
			$ref="&".CONFIG::PARAM_REF_TYP."=".$referensTyp."&".CONFIG::PARAM_REF_ID."=".$referensID;
		}
		
		return Self::getKnappHTML("bokningar-add&".$ref, "Boka", $size, "success", $title);
	}

	public static function getBokKnappHTML($action, $label, $referensID = "", $title = "", $classes){

		$ref="&".CONFIG::PARAM_REF_ID."=".$referensID;	
		
		return Self::getKnappHTML("bocker-$action".$ref, $label, $classes, $title);
	}

	public static function getBokningarHTML($where = "", $orderOn = ""){

		$bokningar = Bokning::getAll($where);
		
		if(!isAdmin()){
			print "<div class=\"alert alert-warning\">
				<p>OBSERVERA!! Bokningar kan bara ändras eller tas bort av biblioteket. Detta för att förhindra att felaktigheter uppstår av misstag.</p>
				<p>KOntakt biblioteket om du vill ha något ändrat</p></div>";
		}
		
		$html = "<table class=\"table main table-hover bockningar\">
					<thead><tr>
						<th>Bok</th>
						<th>Antal</th>
						<th><span class=\"slut\">Överbokad</span></th>
						<th>Kurs</th>
						<th>Hämtas<br />ut</th>
						<th>Lämnas<br />in</th>
						<th>Bokad</th>
						<th>Bokare</th>
					</tr></thead>
					<tbody>";


		$index = 0;
		foreach($bokningar as $bokning){
			$bok = new Bok();
			$bok->setFromId($bokning->bokId);
			$antal = $bok->getAntalBokade();
			$kurs = new Kurs();
			$kurs->setFromId($bokning->kursId);
	
			if($antal->bokade > $antal->antal){
				$overBooked = "<span class=\"slut\">ÖVERBOKAD med " . $antal->bokbara. "</span>";
			} else {
				$overBooked = "";
			}
			
			$html = $html .  "<tr>";
			
			$html = $html .  "<td class=\"major\">" . $bok->fullTitel . "</td>";
			//$html = $html .  "<td>" . $bokning["bok_id"] . "</td>";
			$html = $html .  "<td>" . $kurs->antalElever . "</td>";
			$html = $html .  "<td>" . $overBooked . "</td>";
			$html = $html .  "<td class=\"major\">" . $bokning->kursId. "</td>";
			$html = $html .  "<td class=\"minor\">" . $bokning->utTillfalle->desc . "</td>";
			$html = $html .  "<td class=\"minor\">" . $bokning->inTillfalle->desc . "</td>";
			$html = $html .  "<td class=\"minor\">" . $bokning->datum. "</td>";
			$html = $html .  "<td>" . $bokning->bokare . "</td>";
			//$html = $html .  "<td>" . getKnappHTML("bokningar&" . $CONFIG["secNavParam"] . "=view&" . $CONFIG["refIdParam"] . "=" . $bokning["bok_id"]. "," . $bokning["kurs_id"], "Detaljer", "button-orange", "Se all information om bokningen") . "</td>";
			
			$html = $html .  "</tr>";
			$index++;
		}

		$html = $html . "</tbody></table>";
		
		return $html;
	}

	function ______getBokningarForBockerHTML($isbn){
		return getBokningarHTML("kurser_bocker.bok_id = '$isbn'");
	}

	function ______getBokningarForKurserHTML($kursid){
		return getBokningarHTML("kurser_bocker.kurs_id = '$kursid'");
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

	private static function getAssocArrayAsSelectHTML($assocArr, $selectName, $selectLabel = "Välj...", $fieldLabel = "", $fieldDescription = "", $selectedKey = "", $width = "", $elementId = ""){
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
		return Self::getAssocArrayAsSelectHTML(Kurs::getAllAsSelectAssoc(), $elementId, "Välj en kurs...", "Kurs", $fieldDescription, $selectedId, "180", $elementId);
	}

	/*public static function getSelectKursWithBokningarHTML($fieldDescription = "", $selectedId = "", $elementId = "select-kurs"){
		return Self::getAssocArrayAsSelectHTML(Kurs::getAllAsSelectAssoc(""), $elementId, "Välj en kurs...", "Kurs", $fieldDescription, $selectedId, "", $elementId);
	}*/

	public static function getSelectBokHTML($fieldDescription = "", $selectedId = "", $elementId = "select-bok"){
		$selectArr = getAssocArray(getBockerAsArray(), "isbn", "fulltitel");
		return Self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj en bok...", "Bok", $fieldDescription, $selectedId, "300", $elementId);
	}

	public static function getSelectKlassHTML($fieldDescription = "", $selectedId = "", $elementId = "select-klass"){
		$selectArr = getAssocArray(getKlasserAsArray(), "id", "id");
		return Self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj en klass...", "Klass", $fieldDescription, $selectedId, "", $elementId);
	}

	public static function getSelectKlassWithBokningarHTML($fieldDescription = "", $selectedId = "", $elementId = "select-klass"){
		$selectArr = getKlasserWithBokningarAsAssocArray();
		//print_r($selectArr);
		return Self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj en klass...", "Klass", $fieldDescription, $selectedId, "", $elementId);
	}

	public static function getSelectElevFromKlassHTML($klassId, $fieldDescription = "", $selectedId = "", $elementId = "select-elev"){
		$selectArr = getAssocArray(getEleverFromKlassAsArray($klassId), "elevid", "fullNamn");
		return Self::getAssocArrayAsSelectHTML($selectArr, $elementId, "Välj elev...", "Enskild elev från $klassId", $fieldDescription, $selectedId, "", $elementId);
	}

	public static function getSelectLarareHTML($fieldLabel = "Lärare", $fieldDescription = "", $selectedId = "", $elementId = "select-larare"){
		return Self::getAssocArrayAsSelectHTML(Larare::getAllAsSelectAssoc(), $elementId, "Välj en lärare...", $fieldLabel, $fieldDescription, $selectedId, "180");
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

		return Self::getAssocArrayAsSelectHTML($selectArr, "select-tillfalle-$name", "Välj ett tillfälle...", $fieldLabel, $fieldDescription, $selectedId);
	}

	public static function getTextareaHTML($areaName, $fieldLabel = "", $fieldDescription = "", $value = "", $width = ""){
		
		if($width != ""){
			$width = "style=\"width:".$width."\"";
		}
		
		$html = "<div class=\"field-container\" $width>";
		if($fieldLabel != ""){
			$html = $html . "<label for=\"$areaName\">$fieldLabel</label>";
		}
		$html = $html . "<textarea id=\"$areaName\" name=\"$areaName\" class=\"form-field form-textarea\" $width >$value</textarea>";

		if($fieldDescription != ""){
			$html = $html . "<p class=\"description\">$fieldDescription</p>";
		}
		$html = $html . "</div>";
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