<?php
require_once("class_bokning.php");
require_once("class_bok.php");
require_once("class_kurs.php");
require_once("class_klass.php");
require_once("class_datalager.php");

class HTML_FACTORY_PRINT {

	const DEFAULT_DATA = "<em>Ingen utskrift vald...</em>";

	public static function getKursAjaxSelect(){
		$id = "print-kurs";
		$title = "Välj kurs...";
		$ajaxNav = "view-kurs";
		$selectAssoc = Kurs::getAllAsSelectAssoc(null, true);

		$html = "<div class=\"toolbar\">";
		$html .= HTML_FACTORY_PRINT::_getCommonToolbarHTML($id, $title, $selectAssoc);
		$html .= "</div>";
		$html .= HTML_FACTORY_PRINT::_getCommonOutputHTML($id);
		$html .= HTML_FACTORY_PRINT::_getCommonToolbarScript($id, $ajaxNav);

		return $html;
	}

	public static function getKlassAjaxSelect(){
		$id = "print-klass";
		$title = "Välj klass...";
		$ajaxNav = "view-klass";
		$selectAssoc = Klass::getAllAsSelectAssoc();

		$html = "<div class=\"toolbar\">";
		$html .= HTML_FACTORY_PRINT::_getCommonToolbarHTML($id, $title, $selectAssoc);
		$html .= "</div>";
		$html .= HTML_FACTORY_PRINT::_getCommonOutputHTML($id);
		$html .= HTML_FACTORY_PRINT::_getCommonToolbarScript($id, $ajaxNav);

		return $html;
	}

	public static function getElevKlassAjaxSelect(){
		$id = "print-elev-klass";
		$title = "Välj klass...";
		$ajaxNav = "view-elev-klass";
		$selectAssoc = Klass::getAllAsSelectAssoc();

		$html = "<div class=\"toolbar\">";
		$html .= HTML_FACTORY_PRINT::_getCommonToolbarHTML($id, $title, $selectAssoc);
		$html .= "</div>";
		$html .= HTML_FACTORY_PRINT::_getCommonOutputHTML($id);
		$html .= HTML_FACTORY_PRINT::_getCommonToolbarScript($id, $ajaxNav);

		return $html;
	}

	public static function getElevIndAjaxSelect(){
		$idKlass = "print-elev-ind";
		$idElev = "print-elev";
		$title = "Välj elevens klass...";
		$ajaxNavKlass = "select-elev";
		$ajaxNavElev = "view-elev-ind";
		$selectAssoc = Klass::getAllAsSelectAssoc();

		$html = "<div class=\"toolbar\">";
		$html .= HTML_FACTORY_PRINT::_getElevIndToolbarHTML($idKlass, $title, $selectAssoc);
		$html .= "</div>";

		$html .= HTML_FACTORY_PRINT::_getCommonOutputHTML($idElev);
		$html .= HTML_FACTORY_PRINT::_getElevIndToolbarScript($idKlass, $idElev, $ajaxNavKlass, $ajaxNavElev);

		return $html;
	}


	public static function getElevAjaxSelect($klassId){
		$id = "print-elev";
		$title = "Välj elev...";
		$selectAssoc = Elev::getAllAsSelectAssoc(Elev::FN_KLASSID . " = '" . $klassId . "'");
		$html = HTML_FACTORY_PRINT::_getCommonToolbarHTML($id, $title, $selectAssoc);

	
		return $html;
	}
	
	public static function getBokTableForKurs($kursId){
		$kurs = new Kurs();
		$kurs->setFromId($kursId);
		//var_dump($kursId);
		//var_dump($kurs);

		$bocker = Kurs::getBocker($kursId);

		$html = "";

		foreach($bocker as $bok){
			$html .= HTML_FACTORY_PRINT::_getBokTableRow($kurs, $bok, true);
		}

		return HTML_FACTORY_PRINT::_getTableHead("För kursen $kursId", true) . $html . HTML_FACTORY_PRINT::_getTableFoot();
	}

	public static function getBokTableForKlass($klassId){
		$elever = Klass::getElever($klassId);

		$kurser = [];
		foreach($elever as $elev){
			$elevKurser = Elev::getKurser($elev->id);
			foreach($elevKurser as $kurs){
				if(!in_array($kurs, $kurser)){
					array_push($kurser, $kurs);
				}
			}
		}

		$boklist = [];
		foreach($kurser as $kurs){
			$kursBocker = Kurs::getBocker($kurs->id);
			foreach($kursBocker as $bok){
				$item = ["kurs" => $kurs, "bok" => $bok];
				if(!in_array($item, $boklist)){
					array_push($boklist, $item);
				}
			}
		}

		$html = "";

		foreach($boklist as $item){
			$html .= HTML_FACTORY_PRINT::_getBokTableRow($item["kurs"], $item["bok"], true);
		}

		return HTML_FACTORY_PRINT::_getTableHead("För klassen $klassId", true) . $html . HTML_FACTORY_PRINT::_getTableFoot();
	}


	/*public static function _getBokTableForElevInd($elevId){

		$elev = new Elev();
		$elev->setFromId($elevId);
		//var_dump($elevId);
		//var_dump($elev);

		$kurser = [];

		$elevKurser = Elev::getKurser($elev->id);
		foreach($elevKurser as $kurs){
			if(!in_array($kurs, $kurser)){
				array_push($kurser, $kurs);
			}
		}

		$boklist = [];
		foreach($kurser as $kurs){
			if(!$kurs->isOld()){
				$kursBocker = Kurs::getBocker($kurs->id);
				foreach($kursBocker as $bok){
					$item = ["kurs" => $kurs, "bok" => $bok];
					if(!in_array($item, $boklist)){
						array_push($boklist, $item);
					}
				}
			}
		}

		$html = "";

		foreach($boklist as $item){
			$html .= HTML_FACTORY_PRINT::_getBokTableRow($item["kurs"], $item["bok"]);
		}

		return HTML_FACTORY_PRINT::_getTableHead("För ".$elev->namn." (".$elev->klass.")") . $html . HTML_FACTORY_PRINT::_getTableFoot();
	}*/

	public static function _getBokTableForElevInd($elevId){

		$elev = new Elev();
		$elev->setFromId($elevId);

		$bokningar = Datalager::getBokningarForElev($elevId);

		$html = "";

		foreach($bokningar as $bokning){

			$kurs = new Kurs();
			$kurs->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_KURSID]);
			$bok = new Bok();
			$bok->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_BOKID]);

			$html .= HTML_FACTORY_PRINT::_getBokTableRow($kurs, $bok);
		}

		return HTML_FACTORY_PRINT::_getTableHead("För ".$elev->namn." (".$elev->klass.")") . $html . HTML_FACTORY_PRINT::_getTableFoot();
	}

	public static function getBokTableForElevInd($elevId){

		$elev = new Elev();
		$elev->setFromId($elevId);

		$bokningar = Datalager::getBokningarForElev($elevId);

		$bockningar_lamnain = [];
		$bockningar_hamtaut = [];
		$bockningar_behall = [];

		$html = "";

		$nowTermin = Termin::getCurrentTermin();
		$nextTermin = $nowTermin->getNextTermin();

		foreach($bokningar as $bokning){
			$utTermin = new Termin();
			$utTermin->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_UT]);
			$inTermin = new Termin();
			$inTermin->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_IN]);
			//print "in".$utTermin->id.":";
			//print "ut".$utTermin->id.":";
			//print "nu".Termin::getCurrentTerminId().":";
			$bokning_pushed = false;
			if($nowTermin->id == $bokning[Datalager::TABLE_ELEVBOCKER_FN_IN]){
				array_push($bockningar_lamnain, $bokning);
				$bokning_pushed = true;
			}
			if($nextTermin->id == $bokning[Datalager::TABLE_ELEVBOCKER_FN_UT]){
				array_push($bockningar_hamtaut, $bokning);
				$bokning_pushed = true;
			}
			if(!$bokning_pushed){
				array_push($bockningar_behall, $bokning);
			}

			$kurs = new Kurs();
			$kurs->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_KURSID]);
			$bok = new Bok();
			$bok->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_BOKID]);

			$html .= HTML_FACTORY_PRINT::_getBokTableRow($kurs, $bok);
		}

		$html = "";
		if(count($bockningar_lamnain) > 0){
			$html .= "<h4>ÅTERLÄMNA</h4>";
			$html .= self::_getTableHead();
			$html .= self::_getHTMLTDsForBokningArray($bockningar_lamnain);
			$html .= self::_getTableFoot();
		}
		if(count($bockningar_hamtaut) > 0){
			$html .= "<h4>LÅNA</h4>";
			$html .= self::_getTableHead();
			$html .= self::_getHTMLTDsForBokningArray($bockningar_hamtaut);
			$html .= self::_getTableFoot();
		}
		if(count($bockningar_behall) > 0){
			$html .= "<h4>Behåll</h4>";
			$html .= self::_getTableHead();
			$html .= self::_getHTMLTDsForBokningArray($bockningar_behall);
			$html .= self::_getTableFoot();
		}
		/*$html .= "<h4>DEBUG</h4>";
		$html .= self::_getTableHead();
		$html .= self::_getHTMLTDsForBokningArray($bokningar);
		$html .= self::_getTableFoot();*/

		$pageHead = self::_getPageHead("LÄROMEDELSLISTA", $elev->namn." (".$elev->klass.")", $nextTermin->descLong);
		$pageFoot = self::_getPageFoot();

		return $pageHead . $html . $pageFoot;
	}

	public static function getBokTableForElevklass($klassId){

		$elever = Klass::getElever($klassId);

		$html = "";
		foreach($elever as $elev){
			$html .= HTML_FACTORY_PRINT::getBokTableForElevInd($elev->id);
		}

		return $html;

	}

	private static function _getHTMLTDsForBokningArray($bokningarArray){

		$html = "";

		foreach($bokningarArray as $bokning){

			$kurs = new Kurs();
			$kurs->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_KURSID]);
			$bok = new Bok();
			$bok->setFromId($bokning[Datalager::TABLE_ELEVBOCKER_FN_BOKID]);

			$html .= HTML_FACTORY_PRINT::_getBokTableRow($kurs, $bok);
		}

		return $html;
	}
	

	private static function _getBokTableRow($kursObj, $bokObj, $useAntal = false){
		

		$html = "<tr>
					<td class=\"titel major\">".$bokObj->fullTitel."</td>
					<td class=\"forf major\">".$bokObj->getForfattarNamn()."</td>";
		if($useAntal){
			$html .="<td class=\"antal minor\">".$kursObj->getAntalElever($kursObj->id)."</td>";
		}
		$html .="
					<td class=\"ut minor\">".$kursObj->startTermin->hamtasDesc."</td>
					<td class=\"in minor\">".$kursObj->slutTermin->lamnasDesc."</td>
				</tr>
		";

		return $html;
	}

	private static function _getPageHead($typeTitel, $elevTitel, $forTerminDesc){

		$html = "
				<div class=\"utskrift-data\">
				<header>
				<img class=\"print-logo\" src=\"gfx/logo_alvkullen_print.png\">
				<p><strong>".$forTerminDesc."</strong></p>
				<h1>".$typeTitel."</h1>
				<h3><span class=\"elev-namn\">$elevTitel</span></h3>
				<div class=\"meta\"><em>
					Utskriven ".date("Y-m-d")."
				</em></div>
				</header>
		";

		return $html;
	}
	private static function _getTableHead($useAntal = false){

		$html = "<table class=\"table\">
					<tr>
						<th>Titel</th>
						<th>Författare</th>";
		if($useAntal){
			$html .="<th>Antal</t>";
		}
		$html .="				
						<th>Hämtas</th>
						<th>Återlämnas</th>
					</tr>
		";

		return $html;
	}

	private static function _getTableFoot(){
		$html = "</table>";

		return $html;
	}

	private static function _getPageFoot(){
		$html = "</div>";

		return $html;
	}

	
	private static function _getCommonToolbarHTML($id, $title, $assocArr){

		$html =  "
				<select name=\"select-$id\" id=\"select-$id\">
					<option value=\"".Config::NULL."\">$title</option>
		";

		foreach($assocArr as $etikett => $value){
			$html .= "<option value=\"$value\">$etikett</option>";
		}

		$html .= "
				</select>

			<a href=\"#\" id=\"button-$id\" class=\"btn btn-primary btn-\">Skriv ut</a>

		";

		return $html;
	}

	private static function _getElevIndToolbarHTML($id, $title, $assocArr){

		$html =  "
				<select name=\"select-$id\" id=\"select-$id\">
					<option value=\"".Config::NULL."\">$title</option>
		";

		foreach($assocArr as $etikett => $value){
			$html .= "<option value=\"$value\">$etikett</option>";
		}


		$html .= "
				</select>

			<span id=\"container-select-elev\"></span>
		";



		return $html;
	}

	private static function _getCommonOutputHTML($id){

		$html =  "

			<h4><strong>Förhandsgranskning:</strong></h4>

			<div id=\"data-$id\" class=\"well\">".HTML_FACTORY_PRINT::DEFAULT_DATA."</div>

		";

		return $html;
	}


	private static function _getCommonToolbarScript($id, $ajaxNav){
		$html = "
			<script>
				$('#select-".$id."').on('change', function(){
					$('#data-".$id."').html('".Config::LOADING_HTML."');
					val = $(this).val();
					console.log('AJAX-SELECT VAL: '+val);
					$.get('ajax_print.php?".Config::PARAM_AJAX."=".$ajaxNav."&id='+val, function(data){
						$('#data-".$id."').html(data);
					});
				});

				$('#button-".$id."').on('click', function(){
					printData('data-".$id."');
				});

			</script>
		";

		return $html;
	}

	private static function _getElevIndToolbarScript($id, $elevId, $ajaxNav, $ajaxNavElev){
		$html = "
			<script>
				$('#select-".$id."').on('change', function(){
					val = $(this).val();
					console.log('AJAX-SELECT VAL: '+val);
					$.get('ajax_print.php?".Config::PARAM_AJAX."=".$ajaxNav."&id='+val, function(data){
						$('#container-select-elev').html(data);
						
						$('#select-".$elevId."').on('change', function(){
							$('#data-".$elevId."').html('".Config::LOADING_HTML."');
							val = $(this).val();
							console.log('AJAX-SELECT VAL: '+val);
							$.get('ajax_print.php?".Config::PARAM_AJAX."=".$ajaxNavElev."&id='+val, function(data){
								$('#data-".$elevId."').html(data);
							});
						});

						$('#button-".$elevId."').on('click', function(){
							printData('data-".$elevId."');
						});
					});
					$('#data-".$elevId."').html('".HTML_FACTORY_PRINT::DEFAULT_DATA."');
				});

				$('#button-".$id."').on('click', function(){
					printData('data-".$id."');
				});

			</script>
		";

		return $html;
	}

}

?>