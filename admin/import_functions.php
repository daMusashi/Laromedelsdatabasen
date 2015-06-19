<?php

require_once("class_html_factory.php");
require_once("class_klass.php");
require_once("class_elev.php");
require_once("class_kurs.php");
require_once("class_larare.php");

// Kör Elever först - den skapar grunddata
function parseElever($fileRowArray){
	

	global $IMPORT;
	
	debugLog("Import påbörjas...");
	foreach($fileRowArray as $rowString){
		$rowFields = getFieldsArray($rowString);
		
		// klass
		$klassId = $rowFields[$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Klass"]];
		
		try {
			HTML_FACTORY::printInfoAlert("Klass-import", Klass::importSave($klassId));
		} catch (Exception $e) {
			HTML_FACTORY::printDangerAlert("Klass-import", $e->getMessage());
		}
		
		// TOG BORT FÖR EN LÄRAR-IMPORT HÄR, som verkar förekomma i specialla fall
		// Kolla äldre versioner om behövs lägga tillbaka
		
		// elever
		$elevId = $rowFields[$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["ElevID"]];
		$elevEfternamn = $rowFields[$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Efternamn"]];
		$elevFornamn = $rowFields[$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Fornamn"]];
		
		try {
			HTML_FACTORY::printInfoAlert("Elev-import", Elev::importSave($elevId, $elevFornamn , $elevEfternamn, $klassId));
		} catch (Exception $e) {
			HTML_FACTORY::printDangerAlert("Elev-import", $e->getMessage());
		}
	}
	
}


// KÖr den här efter Elever - Grupprapporten
function parseKurser($fileRowArray, $lasarObj){
	

	global $IMPORT;
	
	foreach($fileRowArray as $rowString){
		$rowFields = getFieldsArray($rowString);
		
		// Kurser/grupper
		$kursId = $rowFields[$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["GruppID"]];
		
		if(validateKursId($kursId)){
			$period = $rowFields[$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Period"]];
		
		
			try {
				HTML_FACTORY::printInfoAlert("Kurs-import", Kurs::importSave($kursId, $period, $lasarObj));
				
				if(count($rowFields) > $IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Elever"]){ // kollar så att elever är angivna i datan
					$elevArr = getValuesArray($rowFields[$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Elever"]]);
					
					//var_dump($elevArr);

					if(!empty($elevArr)){
						try {
							HTML_FACTORY::printInfoAlert("Elever-i-Kurs-import", Kurs::importSaveAddElever($kursId, $elevArr));
						} catch (Exception $e) {
							HTML_FACTORY::printDangerAlert("Elever-i-Kurs-import", $e->getMessage());
						}
					}
				}

			} catch (Exception $e) {
				HTML_FACTORY::printDangerAlert("Kurs-import", $e->getMessage());
			}
		
		
			// lärare (undervisande) lägger till saknade (ej mentorer) och kopplar till kurs
			$lararArr = getValuesArray($rowFields[$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Larare"]]);
			foreach($lararArr as $lararId){
				try {
					HTML_FACTORY::printInfoAlert("Lärar-import", Larare::importSave($lararId, "", ""));
				} catch (Exception $e) {
					HTML_FACTORY::printDangerAlert("Lärar-import", $e->getMessage());
				}

				// ska inte behöva kolla om relation existerar då datan kommer från annan db där dubletter av detta slag inte ska kunna finnas
				try {
					HTML_FACTORY::printInfoAlert("Lärare-i-kurs-import", Kurs::importSaveAddlarare($kursId, $lararId));
				} catch (Exception $e) {
					HTML_FACTORY::printDangerAlert("Lärare-i-kurs-import", $e->getMessage());
				}
			}

		} else {
			HTML_FACTORY::printWarningAlert("Kurs-import", "Kurs med id $kursId finns INTE på WHITELIST. INTE importerad.");
		}// slut om validerat namn
	} // slut for each kurs
}

// Importera elever från andra skolor - Inviduella elever-rapporten
/* 
	format:
	14EM/AG/ENGENG07;;;
	TE12B;Berglund;André;960505-3694
	TE12B;Davidsson;Fredric;950806-8997
	TE12E;Gjedsted;Julia;960105-4720
	TE12C;Åhsbom;Jakob;960113-0116
	upprepas för varje ind-val
	
	TODO: tomma rader läggs till som elev :(
*/
function parseIndKurser($fileRowArray){
	global $IMPORT;
	
	$currentIndKurs = "";
	importLog("Behnadling påbörjas...");
	
	foreach($fileRowArray as $rowString){
		$rowFields = getFieldsArray($rowString);
		
		// om tom rad, hoppa över
		if(count($rowFields) == 0){
			importLog("* tom rad *");
		} else {
		
			// om en kursnamns-rad, byt aktuell kurs
			if(kursExists($indKurs = $rowFields[0])){
				$currentIndKurs = $indKurs;
				importLog("Satte aktuell ind-kurs till $currentIndKurs");
			
			} else { // annars Elev
				// FORMAT klass(0);efternamn(1); förnamn(2); personnummer(3)
				
				$elevId = $rowFields[3];
				$elevFnamn = $rowFields[2];
				$elevEnamn = $rowFields[1];
				$elevKlass = $rowFields[0];
				// om inte elev finns = utomskola-elev -> lägg till elev till kurs
				if(elevExists($elevId)){
					importLog("Elev $elevId FINNS...gör inget");
				} else {
					//addElev($elevId, $elevFnamn, $elevEnamn, $elevKlass);
					//addRelationElevKurs($elevId, $currentIndKurs);
					
					importLog("IND-ELEV! Lägger till Elev $elevFnamn $elevEnamn från $elevKlass ($elevId) till kursen $currentIndKurs");
				}
			}
		}

	}
}

function validateKursId($kursID){

	global $IMPORT;
	$valid = true;
	
	$prefixFound = false;
	foreach($IMPORT["Rapporter"]["Grupper"]["AllowedPrefixes"] as $prefix){
		$kursPrefix = substr($kursID, 0, strlen($prefix));
		if($kursPrefix == $prefix){
			$prefixFound = true;
			break;
		}
	}
	
	$postfixFound = false;
	foreach($IMPORT["Rapporter"]["Grupper"]["DisallowedPostfixes"] as $postfix){
		$kursPostfix = substr($kursID, -strlen($postfix));
		if($kursPostfix == $postfix){
			$postfixFound = true;
			break;
		}
	}
	
	if($prefixFound&&!$postfixFound){
		return true;
	} else {
		return false;
	}
}


// Import bokfil
function parseBocker($fileRowArray){
	global $IMPORT;
	
	foreach($fileRowArray as $rowString){
		$rowFields = getFieldsArray($rowString);
		
		$titel = $rowFields[0];
		$undertitel = $rowFields[1];
		$forfattare = $rowFields[2];
		if($forfattare != ""){
			$namnArr = getValuesArray($forfattare);
			if(count($namnArr) > 0){
				$fornamn = $namnArr[1];
				$efternamn = $namnArr[0];
			} else {
				$fornamn = "";
				$efternamn = $namnArr[0];
			}
		}
		$forlag = $rowFields[3];
		$upplaga = $rowFields[4];
		$isbn = $rowFields[5];
		$pris = $rowFields[6];
		if($pris == ""){
			$pris = "0";
		}
		
		if($response = addBok($isbn, $titel, $undertitel, $fornamn, $efternamn, $upplaga, $forlag, $pris)){
			//importLog("Lade till bok: $response");		
		} else {
			debugLog("Fel vid lägga till bok: " . mysql_error(), "parseBocker");
			}
		}
		
}


function importLog($txt){
	global $IMPORT;

	if($IMPORT["outputLog"] == true){
		print "<p class=\"import-log-item\">$txt</p>";	
	}
}

// ANVÄNDS INTE (ÄN)
// Skicka en fil-rads-array
// Returnerar en array med  fält/kolumner som ordningsindexerade under-arrays
function getColumnsArrays($fileRowArray){
	global $IMPORT;
	
	$returnArr = array();
	
	// Skapar under-arrayr
	if(count($fileRowArray) > 0){ // kollar bara så att det finns något i $returnArr
		$rowArray = explode($IMPORT["Fil"]["ColumnSeparator"], $fileRowArray[0]);
		for($i = 0; $i < count($rowArray); $i++){
			$returnArr[$i] = array();
		}
	}
	
	foreach($fileRowArray as $row){
		$row = trim($row);
		$rowArray = explode($IMPORT["Fil"]["ColumnSeparator"], $row);
		
		for($i = 0; $i < count($rowArray); $i++){
			array_push($returnArr[$i], $rowArray[$i]);
		}

	}
	
	return $returnArr;
}

function getFieldsArray($rowString){
	global $IMPORT;
	return trimArrayValues(explode($IMPORT["Fil"]["ColumnSeparator"], $rowString));
}

function getValuesArray($fieldString){
	global $IMPORT;
	return trimArrayValues(explode($IMPORT["Fil"]["ValueSeparator"], $fieldString));
}

function trimArrayValues($arr){
	$returnArr = array();
	foreach($arr as $row){
		$row = trim($row);
		array_push($returnArr, $row);
	}
	
	return $returnArr;
}

function validateName($namn){
	$valid = true;
	if($namn == NULL){
		$valid = false;
	}
	if($namn == ""){
		$valid = false;
	}
	return $valid;
}

function getFile($file_key){
	if($importfil = getAndValidateFile($file_key)){
		printFileInfo($file_key, $importfil);
		return $importfil;
	} else {
		printFileERRORInfo($file_key);
		return false;
	}	
}


function getAndValidateFile($file_key){
	global $IMPORT;

	$temp = explode(".", $_FILES[$file_key]["name"]);
	$extension = end($temp);
	
	debugLog("filtyp: ".$_FILES[$file_key]["type"], "getAndValidateFile");
	
	if ((($_FILES[$file_key]["type"] == "text/csv")
		|| ($_FILES[$file_key]["type"] == "text/plain")
		|| ($_FILES[$file_key]["type"] == "application/octet-stream"))
		&& ($_FILES[$file_key]["size"] < $IMPORT["Fil"]["MaxFileSize"] )
		&& in_array($extension, $IMPORT["Fil"]["AllowedExts"])){

			if ($_FILES[$file_key]["error"] > 0){
				return false;
  			} else {
				// läser in filen
  				$filRowArray=  file($_FILES[$file_key]["tmp_name"]);
				if($IMPORT["Fil"]["FirstLineIsColumnnames"]){
					array_shift($filRowArray); // tar bort första raden med kolumnnamn
				}
				return $filRowArray;
  			}
	} else {
		return false;	
	}
}



function printFileInfo($file_key, $filRowArray){
	global $IMPORT;
	print "<div class=\"info-box\">";
	print "<p class=\"label\">Fil uppladdad</p>";
	print "<h3>" . $_FILES[$file_key]["name"] . "</h3>";
   	print "<p>Typ: <span class=\"data\">" . $_FILES[$file_key]["type"] . "</span></p>";
    print "<p>Storlek: <span class=\"data\">" . ($_FILES[$file_key]["size"] / 1024.0) . " kB</p>";
	if($IMPORT["Fil"]["PreivewData"]){
		printPreview($filRowArray);
	}
    print "</div>";
}

function printFileERRORInfo($file_key){
	print "<div class=\"error\">";
	print "<p class=\"label\">UPPLADDNING MISSLYCKADES</p>";
	print "<h3>" . $_FILES[$file_key]["name"] . "</h3>";
   	print "<p>" . $_FILES[$file_key]["error"] . "</p>";
    print "</div>";
}

function printPreview($filRowArray){
	global $IMPORT;
  // Skriv ut preview
  $i = 1;
  print "<div class=\"preview-data\">";
  print "<h3>Exempeldata</h3>";
  print "<div class=\"data\">";
  foreach($filRowArray as $row){
	  print "<p>$row</p>";
	  $i++;
	  if($i > $IMPORT["Fil"]["NumPreviewLines"]){ break; }
  }
  print "</div></div>";	
}
?>