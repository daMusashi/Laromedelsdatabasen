<?php
function makeDemoBokningar(){
	debugLog ("<h1>SKAPAR DEMO_BOKNINGAR</h1>", "db_functions|makeDemoBokningar");	
	
	$kurserBockerPoster = mysql_query("DELETE FROM kurser_bocker");
	debugLog ("<p><strong>TÖMT TABELL MED BOKNINGAR</strong></p><p>BKurser-bocker: kurserBockerPoster </p>", "db_functions|makeDemoBokningar");
		
	DEMO_SET_BOCKER_ANTAL(0);
	DEMO_SET_BOCKER_ANTAL(1);
	$bocker = getBockerAsArray();
	$kurser = getKurserAsArray();
	debugLog ("<strong>Antal lärare:" . countLarare() . "</strong>", "db_functions|makeDemoBokningar");	
	$larare = getLarareAsArray();
	
	foreach($bocker as $bok){
		$bokningsSannolikhet = rand(1, 10);
		if($bokningsSannolikhet < 7){
			$antalBokningsKurser = rand(1,4);
			for($i = 0; $i < $antalBokningsKurser; $i++){
				$kurs = $kurser[rand(0, count($kurser)-1)];
				$larar = $larare[rand(0, count($larare)-1)];
				addBokning($bok["isbn"], $kurs["id"], "utTid", "inTid", $larar["id"], "2012/2013", "true");
				debugLog ("Skapa bokning mellan bok:" . $bok["isbn"] . " och kurs:" . $kurs["id"] . ", av lärare:" . $larar["id"]  . "</p>", "db_functions|makeDemoBokningar");	
			}
		}
	}
	debugLog ("<strong>" . countBokningar() . " bokningar skapade</strong>", "db_functions|makeDemoBokningar");	
}


function DEMO_SET_BOCKER_ANTAL($num = 0){
	$bocker = getBockerAsArray();
	
	foreach($bocker as $bok){
		if($num == 0){
			$count = 0;
		} else {
			$count = rand(16, 70);
		}
		$q = "UPDATE bocker SET antal = $count WHERE isbn = '" . $bok["isbn"] . "'";
		//debugLog("q: $q", "db_functions|DEMO_SET_BOCKER_ANTAL");
		mysql_query($q);
	}
}


function EMPTY_ALL_BOK_DATA_TABLES(){
	debugLog("startad", "db_functions|EMPTY_ALL_BOK_DATA_TABLES");
	$bokPoster = mysql_query("DELETE FROM bocker");
	$kurserBockerPoster = mysql_query("DELETE FROM kurser-bocker");
	
	return "<div class=\"info-box\"><p><strong>TÖMT ALLA TABELLER MED BOK-DATA</strong></p><p>Bocker: $bokPoster, Kurser-bocker: kurserBockerPoster </p></div>";
}

function EMPTY_DATA_TABLE($table){
	debugLog("startad", "db_functions|EMPTY_DATA_TABLE");
	$poster = mysql_query("DELETE FROM $table");
	
	return "<div class=\"info-box\"><p><strong>TÖMT ALL DATA I [$table]</p><p>Poster: $poster </p></div>";
}
?>