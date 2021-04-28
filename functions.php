<?php
require_once("db_functions.php");
require_once("login_functions.php");
require_once("admin/dev_functions.php");

function includeContent($roll, $includeFile){
	switch($roll){
		case "dev":
			//print "dev";
			if(isDev()){
				include($includeFile);
			} else {
				printNoRights($roll);	
			}
			break;
		case "admin":
			//print "admin";
			if(isAdmin()){
				include($includeFile);
			} else {
				printNoRights($roll);	
			}
			break;
		case "user":
			//print "user";
			if(isLoggedin()){
				include($includeFile);
			} else {
				printNoRights($roll);	
			}
			break;
		default:
			//print "alla";
			include($includeFile);
	}
}



// Skriver ut en array som lista
function printArrayAsList($arr, $rubrik = "", $class = "data-list-box"){
	if($rubrik != ""){
		$h3	= "<h3>$rubrik</h3>";
	} else {
		$h3	= "";
	}
	
	$i = 0;
	print "<div class=\"$class\">$h3";
	print "<ul class=\"data-list\">";
	foreach($arr as $row){
		if(($i % 2) == 0){
			$rowclass = "even";	
		} else {
			$rowclass = "odd";	
		}
		print "<li class=\"data-list-item $rowclass\">$row</li>";
	}
	print "</ul></div>";
}

// ger klass efter udda/jämn
function getRowClass($index){
	// udda jämn
	if(($index % 2) == 0){
		return "even";
	} else {
		return "odd";
	}	
}

// konverterar post-array till assoc array id/label (key/value)
function getAssocArray($arr, $keyField, $valueField){
	$assocArr = array();
	foreach($arr as $row){
		$assocArr[$row[$keyField]] = $row[$valueField];
	}
	return $assocArr;
}


// print-prylar

function printAdminsOnly(){
	print "<h2>Innehållet kan inte visas</h2>";	
	print "<p>Logga in som administratör för att se det</p>";	
}
function printDevsOnly(){
	print "<h2>Innehållet kan inte visas</h2>";	
	print "<p>Logga in som developer för att se det</p>";	
}


function printAllBockerAsList(){
	printBockerArrAsList(getBockerAsArray(), $rubrik = "Alla böcker");
}

function printBockerArrAsList($bockerAssoc, $rubrik = "", $printLinks = false){
	global $CONFIG;
	
	$printArr = array();

	
	if($printLinks){
		$aPre = "<a href=\""  . $CONFIG["baseURL"]  . "?view=bok&id=" . $bok["isbn"] . "\">";
		$aPost = "</a>";
	} else {
		$aPre = "";
		$aPost = "";
	}
	
	foreach($bockerAssoc as $bok){
		if($bok["upplaga"] != ""){
			$upplaga = " (" . $bok["upplaga"] . "), ";
		} else {
			$upplaga = ", ";
		}
	
		if($bok["forf_efternamn"] != ""){
			$forf = " - " . $bok["forf_fornamn"] . " " . $bok["forf_efternamn"];
		} else {
			$forf = "";
		}
		
		$s = $aPre . $bok["titel"] . $upplaga . $bok["forlag"] . $forf . $aPost;
		array_push($printArr, $s);
	}
	
	printArrayAsList($printArr, $rubrik);
}


function printAllEleverAsList(){
	printEleverArrAsList(getEleverAsArray(), $rubrik = "Alla elever");
}

function printEleverArrAsList($eleverAssoc, $rubrik = ""){
	global $CONFIG;
	
	$printArr = array();

	foreach($eleverAssoc as $elev){
		$s = "<a href=\""  . $CONFIG["baseURL"]  . "?view=elev&id=" . $elev["id"] . "\">" . $elev["efternamn"] . ", " . $elev["fornamn"] . "</a>";
		array_push($printArr, $s);
	}
	
	printArrayAsList($printArr, $rubrik);
}

function printAllKurserAsList(){
	printKursArrAsList(getKurserAsArray(), $rubrik = "Alla kurser");
}

function printKursArrAsList($kurserAssoc, $rubrik = ""){
	global $CONFIG;
	
	$printArr = array();

	foreach($kurserAssoc as $kurs){
		$s = "<a href=\""  . $CONFIG["baseURL"]  . "?view=kurs&id=" . $kurs["id"] . "\">" . $kurs["id"] . "</a>";
		array_push($printArr, $s);
	}
	
	printArrayAsList($printArr, $rubrik);
}

function printAllKlasserAsList(){
	printKursArrAsList(getKlasserAsArray(), $rubrik = "Alla klasser");
}

function printKlassArrAsList($klasserAssoc, $rubrik = ""){
	global $CONFIG;
	
	$printArr = array();

	foreach($klasserAssoc as $klass){
		$s = "<a href=\""  . $CONFIG["baseURL"]  . "?view=klass&id=" . $klass["id"] . "\">" . $klass["id"] . "</a>";
		array_push($printArr, $s);
	}
	
	printArrayAsList($printArr, $rubrik);
}

function debugLog($text, $source = ""){
	global $CONFIG;
	if($CONFIG["outputDebug"]){
		if($source != ""){
			$source = "[$source]";
		}
		print "<p class=\"debug-item\">DEBUG$source: $text</p>";
	}
}

?>