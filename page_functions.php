<?php
require_once("functions.php");
require_once("class_kurs.php");
require_once("class_bok.php");
require_once("class_larare.php");

function getKurserPageList(){

	$Selectedkurser = Kurs::getAllForLasar($_SESSION["active-termin"]);

	$html = "
		<table class=\"table kurser main table-striped kurser\">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Namn</th>
				<th>Antal <br />elever</th>
				<th>Start</th>
				<th>Slut</th>
				<th>Lärare</th>
				<th>Bokade böcker</th>
				</tr>
		</thead>
		<tbody>
	";

	$rowIndex = 0;
	$bokIndex = 0;

	foreach($Selectedkurser as $kurs){
		
		$html .= "<tr>";
		
		$html .= "<td>";
		if(isLoggedin()){
			if(!$kurs->isOld()){
				$html .=  HTML_FACTORY::getBokaKnappHTML("sm", "kurs", $kurs->id, "Boka ett läromedel för kursen", "left");
			} else {
				$html .=  "avslutad";
			}
		} else {
			$html .=  "";
		}
		$html .= "</td>";
		
		//$html .= "<td class=\"major\">" . $kurs->id . "</td>";
		$html .= "<td class=\"major\">" . kurs::getTdInfoSnippet($rowIndex, $kurs, $kurs->startTermin->id, $kurs->slutTermin->id) . "</td>";
		
		$html .= "<td class=\"minor\">" .  Kurs::getAntalElever($kurs->id) . "</td>";

		$html .=  "<td class=\"minor\">" .  $kurs->startTermin->desc. "</td>";

		$html .=  "<td class=\"minor\">" .  $kurs->slutTermin->desc. "</td>";

		$lararStr = "";
		$lararList = Kurs::getLarare($kurs->id);
		if(count($lararList) > 0){
			foreach($lararList as $larare){
				$lararStr = $lararStr . $larare->id . "<br />";
			}
		} else {
			$lararStr = "Ingen lärare<br />knuten till kursen än";
		}
		$html .= "<td class=\"minor\">$lararStr</td>";
		
		$html .= "<td class=\"bocker\">";
		if(isLoggedIn()){
			$bokList = Kurs::getBocker($kurs->id);
			if(count($bokList) > 0){
				$html .= "<div>";
				foreach($bokList as $bok){		
					//var_dump($bok);
					$html .= "<p><a href=\"".$bok->urlView."\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Se info & andra bokningar\">".$bok->fullTitel."</a></p>";
					//Bok::getTdInfoSnippet($bokIndex, $bok);
					$bokIndex++;	
				}
				$html .= "</div>";
			} else {
				$html .= "<em>Inga bokningar än</em>";
			}
		} else {
			$html .= "Logga in för info";
		}
		$html .= "</td>";
		
		$html .= "</tr>";
		$rowIndex++;
	}

	$html .= "</tbody>";
	$html .= "</table>";

	$html .= "
		<script>
			$(document).ready(function(){
				$('[data-toggle=\"tooltip\"]').tooltip();

				$('.kurs-info button').click(function(e){
					e.preventDefault();
					//alert('klick');
					kursId = $(this).attr('data-kursid');
					startId = $(this).attr('data-startid');
					slutId = $(this).attr('data-slutid');
					setChangeTerminModal(kursId, startId, slutId);
		
					$('#change-termin-modal-titel').text('Ändra start-/sluttermin för '+kursId);

					$('#change-termin-modal-confirm').click(function(e){
						e.preventDefault();
						$.get(getAjaxCAll(), function(data){
							$('#ajax-debug').html(data);
						}); // getAjaxCall finns changeTerminModal.php
						$('#change-termin-modal').modal('hide');
					});
					
					$('#change-termin-modal').modal();
				});

			});
		</script>
	";

	return $html;
}

// $_SESSION["bok-termin"] & $_SESSION["bok-urval"] måste vara satta innan körning (vilket i praktien ajax gör)
function getBockerPageList(){
	
	$activeTermin = new Termin();
	$activeTermin->setFromId($_SESSION["bok-termin"]);

	$teckenUrval = $_SESSION["bok-urval"];

	if($teckenUrval == "*"){
		$where = NULL;
	} else {
		$where = Bok::FN_TITEL." LIKE '".$teckenUrval."%'";
	}

	$html = "<table class=\"table main table-striped bocker\">
			<tbody>
	";

	$bocker = Bok::getAll($where);
	$index=0;

	foreach($bocker as $bok){
		
		$statusClass = "";

			$antalObj = $bok->getAntalBokade($activeTermin->id);
			
			if($antalObj->bokbar){
				$statusClass = "";
				$buttonClass = "success";
				if($antalObj->bokbara <= Config::BOK_INSTOCK_WARNING){
					$buttonClass = "warning";
				}	
			} else {
				$statusClass = "bg-danger";
				$buttonClass = "danger";	
			}

		$html .= "<tr class=\"$statusClass\">";
		//$html .= "<tr>";
		
		$html .= "<td>";
		if($antalObj->bokbar && isLoggedin()){
			$html .= HTML_FACTORY::getBokaKnappHTML("sm", "bok", $bok->id, "Boka läromedlet", "left");
		} 
		$html .="</td>";
		
		$html .= "<td class=\"major\">";
		$html .= Bok::getTdInfoSnippet($index, $bok, $antalObj);
		$html .= "</td>";


		$html .= "<td>";
		if(isLoggedin()){
			$bokningsLink = $bok->urlBoka;
		} else {
			$bokningsLink = "#";
		}
		$html .= "Tillgängliga <strong>".$activeTermin->desc."</strong> <a href=\"".$bokningsLink."\" class=\"btn btn-$buttonClass btn-xs\"><span class=\"badge\">".$antalObj->bokbara."</span> av ".$antalObj->antal . "</a>";
		$html .= "</td>";


		$html .= "</tr>";

		$index++;

	} 

	$html .= "</tbody></table>";

	$html .= "
		<script>
			$(document).ready(function(){
				$('[data-toggle=\"tooltip\"]').tooltip();
			});
		</script>
	";

	return $html;
}

// $_SESSION["active-termin"] & $_SESSION["bokning-bokare"] måste vara satta innan körning (vilket i praktien ajax gör)
function getBokningarPageList($bokId = ""){

	$bokare = $_SESSION["bokning-bokare"];
	if(!empty($bokId)){ // visa alla bokare om bok-bokningar visas
		$bokare = "*";
	}

	$kurserInTermin = Kurs::getAllForLasar($_SESSION["active-termin"]);
	//print "<p>factory: getbokningarHTML antal kurser:".count($kurserInTermin)."</p>";
	$bokningar = [];

	foreach ($kurserInTermin as $kurs) {
		$bokningar = array_merge($bokningar, Bokning::getForKurs($kurs->id));
	}

	
	$html = "<table class=\"table main table-striped bockningar\"><thead><tr>";
	$html .= "<th>Bokning</th>";
	$html .= "<th>Antal böcker</th>";
	$html .= "<th><span class=\"slut\">Överbokad</span></th>";
	$html .= "<th>Bokare</th>";
	$html .= "</tr></thead><tbody>";


	$index = 0;

	foreach($bokningar as $bokning){
		//var_dump($bokning);
		
		//print "<p>bokId[".$bokId."] | bokning->bokId[".$bokning->bokId."]";
		if(!empty($bokId)&&($bokning->bokId != $bokId)){
			// DO NOTHING - visa bok aktiverad och ingen match
		} else {
			
			if(($bokare != "*")&&($bokning->bokare != $bokare)){
				// DO NOTHING - visa för bokare aktiverad och ingen match
			} else {

				$bok = new Bok();

				if(Bok::exists($bokning->bokId)){
					$bok->setFromId($bokning->bokId);
				} else {
					$bok = Bok::getGhostBok($bokning->bokId);
				}

				$antalObj = $bok->getAntalBokade($_SESSION["active-termin"]);

				$kurs = new Kurs();
				$kurs->setFromId($bokning->kursId);

				$overBooked = "";
				$statusClass = "";
				if(!$antalObj->bokbar){
					if($antalObj->bokbara < 0){
						$statusClass = "bg-danger";
						$overBooked = "<span class=\"slut\"><strong>ÖVERBOKAD med " . abs($antalObj->bokbara). "</strong></span>";
					}
				}
				
				$html = $html .  "<tr class=\"$statusClass\">";
				
				$bokningsNamn = $bokning->kursId . " &rArr; " . $bok->fullTitel;
				$html = $html .  "<td class=\"major\">" . Bokning::getTdInfoSnippet($index, $bokningsNamn, $bokning, $kurs) . "</td>";
				//$html = $html .  "<td>" . $bokning["bok_id"] . "</td>";
				$html = $html .  "<td>" . Kurs::getAntalElever($kurs->id) . "</td>";
				$html = $html .  "<td>" . $overBooked . "</td>";
			
				$html = $html .  "<td>" . $bokning->bokare . "</td>";
				//$html = $html .  "<td>" . getKnappHTML("bokningar&" . $CONFIG["secNavParam"] . "=view&" . $CONFIG["refIdParam"] . "=" . $bokning["bok_id"]. "," . $bokning["kurs_id"], "Detaljer", "button-orange", "Se all information om bokningen") . "</td>";
				
				$html = $html .  "</tr>";
				$index++;
			}
		}
	}

	$html = $html . "</tbody></table>";

	$html .= "
		<script>
			$(document).ready(function(){
				$('[data-toggle=\"tooltip\"]').tooltip();
			});
		</script>
	";

	if($index == 0){
		$termin = new Termin();
		$termin->setFromId($_SESSION["active-termin"]);
		if($bokare == "*"){
			$html = "<em>Det finns inga bokningar för ".$termin->lasar->descLong."</em>";
		} else {
			$html = "<em>Det finns inga bokningar för bokare <strong>".$bokare."</strong> under ".$termin->lasar->descLong."</em>";
		}
	}
	
	return $html;
}




