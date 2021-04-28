<?php

function getTableNamesAsArr(){
	global $DB_NAMN;
	$q = "SHOW TABLES FROM $DB_NAMN";
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getTableNamesAsArr");
	}
	
	$tables = array();
	while ($row = mysql_fetch_row($result)) {
    	//array_push($tables, $row[0]);
		$tables[$row[0]] = $row[0];
	}
	
	return $tables;
}

function addDataRowByElev($elevid, $fornamn, $efternamn, $klassid, $kursidArr, $lararidArr, $lasar){
	// kollar om lärare finns, annars lägga till
	foreach($lararidArr as $lararid){
		if(!rowExist("larare", "id", $lararid)){
			addLarare($id);
		}
	}
	
	// kollar om kurs finns, annars lägga till
	foreach($kursidArr as $kursid){
		if(!rowExist("kurser", "id", $kursid)){
			addKurs($id, $lasar);
		}
		// relation till lärare
		if(!rowExistRelation("kurser_larare", "kurs_id", "larar_id", $kursid, $id2, $id1IsString = true, $id2IsString = true)){
			
		}
	}
	
	// kollar om klass finns, annars lägga till
	if(!rowExist("klasser", "id", $klassid)){
		addKlass($id);
	}
	
	// kollar om elev finns, annars lägga till
	if(!rowExist("elever", "id", $elevid)){
		addElev($id, $fornamn, $efternamn, $klassid);
	}
	
	

}

// Böcker

function addBok($isbnId, $titel, $undertitel, $forfFornamn, $forfEfternamn, $upplaga, $forlag, $pris = "0", $antal = "0"){
	
	$q = "INSERT INTO bocker (isbn, titel, upplaga, forf_efternamn, forf_fornamn, antal, undertitel, forlag, pris, arkiverad) VALUES ('$isbnId', '$titel', '$upplaga', '$forfFornamn', '$forfEfternamn', $antal, '$undertitel', '$forlag', $pris, false)";
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addBokning");
	}
	
	return $result;
}

function getBokArray($isbn){
	$q = "SELECT * FROM bocker WHERE isbn = '$isbn'";

	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBockerAsArray");
	}
	
	$bok = mysql_fetch_assoc($result);
	
	return $bok;
}

function bokExists($bokId){
	return rowExist("bocker", "isbn", $bokId);
}

function countBocker(){
	return countRows("bocker");
}

function getBockerAsArray($where = "", $fullTitle = false){
	$default_where = "bocker.arkiverad = false";
	if($where != ""){
		//$where = " WHERE " . $where . " AND $default_where";
		$where = " WHERE " . $where;
	} else {
		//$where = " WHERE " . $default_where;
		$where = "";
	}
	$q = "SELECT * FROM bocker $where ORDER by titel";

	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBockerAsArray");
	}
	
	$bocker = array();
	while($bok = mysql_fetch_assoc($result)){
		$bok["fulltitel"] = getFullBokTitelFromFieldAssoc($bok);
		array_push($bocker, $bok);
	}
	
	return $bocker;
}

function getBockerForKursAsArray($kursId){
	$q = "SELECT * FROM kurser_bocker JOIN bocker ON kurser_bocker.bok_id = bocker.isbn WHERE kurser_bocker.kurs_id = '$kursId' ORDER by bocker.titel";
	//print "<p>$q</p>";
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBockerForKursAsArray");
	}
	
	$bocker = array();
	while($bok = mysql_fetch_assoc($result)){
		array_push($bocker, $bok);
	}
	
	return $bocker;
}

function getBockerForKlassAsArray($klassId){
	$q="SELECT DISTINCT bok_id as isbn, forf_fornamn, forf_efternamn, klasser.id as klassid, kurser.id as kursid FROM kurser_bocker ";
	$q=$q . "JOIN bocker ON kurser_bocker.bok_id = bocker.isbn ";
	$q=$q . "JOIN kurser ON kurser_bocker.kurs_id = kurser.id ";
	$q=$q . "JOIN kurser_elever ON kurser.id = kurser_elever.kurs_id ";
	$q=$q . "JOIN elever ON kurser_elever.elev_id = elever.id ";
	$q=$q . "JOIN klasser ON klass_id = klasser.id ";
	$q=$q . "WHERE  klasser.id = '$klassId'";
	$q=$q . "ORDER BY klassid";
	//print "<p>$q</p>";
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBockerForKursAsArray");
	}
	
	$bocker = array();
	while($bok = mysql_fetch_assoc($result)){
		array_push($bocker, $bok);
	}
	
	return $bocker;
}

function getBockerForElevAsArray($elevId){
	$q="SELECT DISTINCT bok_id as isbn, forf_fornamn, forf_efternamn, kurser.id as kursid, ut_tid_id, in_tid_id FROM kurser_bocker ";
	$q=$q . "JOIN bocker ON kurser_bocker.bok_id = bocker.isbn ";
	$q=$q . "JOIN kurser ON kurser_bocker.kurs_id = kurser.id ";
	$q=$q . "JOIN kurser_elever ON kurser.id = kurser_elever.kurs_id ";
	$q=$q . "JOIN elever ON kurser_elever.elev_id = elever.id ";
	$q=$q . "WHERE  elever.id = '$elevId'";
	$q=$q . "ORDER BY efternamn";
	//print "<p>$q</p>";
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBockerForKursAsArray");
	}
	
	$bocker = array();
	while($bok = mysql_fetch_assoc($result)){
		array_push($bocker, $bok);
	}
	
	return $bocker;
}

// FLYTTAD KLASS BOK
function getFullBokTitelFromId($bokId){
	return getFullBokTitelFromFieldAssoc(getBokArray($bokId));
}

// FLYTTAD TILL KLASS BOK
function getFullBokTitelFromFieldAssoc($bokFieldAssoc){
	if($bokFieldAssoc["upplaga"] == ""){
		$upplaga = "";
	} else {
		$upplaga = " (" . $bokFieldAssoc["upplaga"] . ")";
	}
	if($bokFieldAssoc["undertitel"] == ""){
		$undertitel = "";
	} else {
		$undertitel = " - " . $bokFieldAssoc["undertitel"];
	}
	return $bokFieldAssoc["titel"] . "$undertitel$upplaga";
}

function getShortBokTitelFromId($bokId){
	return getShortBokTitelFromFieldAssoc(getBokArray($bokId));
}

function getShortBokTitelFromFieldAssoc($bokFieldAssoc){
	if($bokFieldAssoc["upplaga"] == ""){
		$upplaga = "";
	} else {
		$upplaga = " (" . $bokFieldAssoc["upplaga"] . ")";
	}
	return $bokFieldAssoc["titel"] . $upplaga;
}

// FLYTTAD TILL KLASS BOK
function getNumBokadeForBok($bokId){
	$num = 0;
	$kurserForBok = getKurserForBokAsAssocArray($bokId);
	foreach($kurserForBok as $kurs){
		$num = $num + antalEleverIKurs($kurs);
	}
	return $num;
}

// Bokningar

function addBokning($isbnId, $kurId, $utId, $inId, $bokareId, $lasarId, $isDemo = "false"){
	
	$q = "INSERT INTO kurser_bocker (bok_id, kurs_id, ut_tid_id, in_tid_id, larar_id, lasar_id, demo, arkiverad) VALUES ('$isbnId', '$kurId', '$utId', '$inId', '$bokareId', '$lasarId', $isDemo, false)";
	debugLog("q:$q" , "db_functions|addBokning");
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addBokning");
	}
	
	return $result;
}

function bokningExists($bokId, $kursId){
	return rowExistRelation("kurser_bocker", "bok_id", "kurs_id", $bokId, $kursId);
}

function countBokningar(){
	return countRows("kurser_bocker");
}

// FLYTTAD TILL KLASS DO
function getBokningarAsArray($where = ""){
	$default_where = "kurser_bocker.arkiverad = false";

	if($where != ""){
		//$where = " WHERE " . $where . " AND $default_where";
		$where = " WHERE " . $where;
	} else {
		//$where = " WHERE " . $default_where;
		$where = "";
	}
	
	$q = "SELECT * FROM kurser_bocker $where ORDER by datum";

	//debugLog("q:$q" , "db_functions|getBokningAsArray");

	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBokningarAsArray");
	}
	
	$bokningar = array();
	while($bokning = mysql_fetch_assoc($result)){
		array_push($bokningar, $bokning);
	}
	
	return $bokningar;
}

function getBokningarFullInfoAsArray($where = "", $orderOn = ""){
	$q = "SELECT kurser.id as kurs, larare.id as larare, bocker.*, kurser_bocker.* FROM kurser_bocker
	JOIN bocker ON kurser_bocker.bok_id = bocker.isbn
	JOIN kurser ON kurser_bocker.kurs_id = kurser.id
	JOIN larare ON kurser_bocker.larar_id = larare.id";

	$default_where = "kurser_bocker.arkiverad = false";

	if($where != ""){
		//$where = " WHERE " . $where . " AND $default_where";
		$where = " WHERE " . $where;
	} else {
		//$where = " WHERE " . $default_where;
		$where = "";
	}
	
	if($orderOn != ""){
		$order = " ORDER BY $orderOn";
	} else {
		$order = " ORDER BY datum DESC";
		//$order = " ORDER BY bocker.titel";
	}
	
	
	
	$q = $q . $where . $order;
	//print "<p>$q</p>";

	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBokningarAsArray");
	}
	
	$bokningar = array();
	while($bokning = mysql_fetch_assoc($result)){
		$bokning["fullTitel"] = getFullBokTitelFromFieldAssoc($bokning);
		$bokning["antalElever"] = antalEleverIKurs($bokning["kurs_id"]);
		array_push($bokningar, $bokning);
	}
	
	return $bokningar;
}



// Kurser/grupper

function addKurs($id){
	
	$q = "INSERT INTO kurser (id) VALUES ('$id')";
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addKurs");
	}
	
	return $result;
}

function kursExists($kursId){
	return rowExist("kurser", "id", $kursId);
}

function countBokningarForKurs($kursId){
	return count(getKurserWithBokningarAsAssocArray("kursid = '$kursId'"));	
}

function countKurser(){
	return countRows("kurser");
}

function getKurserAsArray($where = ""){
	if($where != ""){
		$where = "WHERE $where";
	}
	$q = "SELECT * FROM kurser $where ORDER by id";

	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getKurserAsArray");
	}
	
	$kurser = array();
	while($kurs = mysql_fetch_assoc($result)){
		array_push($kurser, $kurs);
	}
	
	return $kurser;
}

/*function getKurserWithBokningarAsAssocArray($where = ""){
	$q="SELECT DISTINCT kurser.id as kursid FROM kurser_bocker ";
	$q=$q . "JOIN kurser ON kurser_bocker.kurs_id = kurser.id ";
	$q=$q . "JOIN kurser_elever ON kurser.id = kurser_elever.kurs_id ";
	$q=$q . "JOIN elever ON kurser_elever.elev_id = elever.id ";
	$q=$q . "JOIN klasser ON klass_id = klasser.id ";
	if($where != ""){
		$q=$q . "WHERE $where ";
	}
	$q=$q . "ORDER BY kursid";
	
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getKlasserAsArray");
	}
	$result = mysql_query($q);
	//print "<p>".mysql_num_rows($result)."</p>";
	$kurser = array();
	while($kurs = mysql_fetch_assoc($result)){
		//print "<p>".$klass["klassid"]."</p>";
		$kurser[$kurs["kursid"]] =  $kurs["kursid"];
	}
	
	return $kurser;
}*/

//FLYTTAD TILL KLASS KURS
function getKurserForBokAsAssocArray($bokId){
	$q = "SELECT kurs_id as id FROM kurser_bocker
	WHERE bok_id = '$bokId'";

	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBokningarAsArray");
	}
	
	$kurser = array();
	while($kurs = mysql_fetch_assoc($result)){
		$kurser[$kurs["id"]] = $kurs["id"];
	}
	return $kurser;
}

//FLYTTAD TILL KLASS KURS
function antalEleverIKurs($kursId){
	$q = "SELECT * FROM kurser_elever WHERE kurs_id = '$kursId'"; 
	//debugLog(" q:$q" , "db_functions|antalEleverIKurs");
	$result = mysql_query($q);
	//debugLog(" num_rows:" . mysql_num_rows($result) , "db_functions|antalEleverIKurs");
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|antalEleverIKurs");
	}
	
	return mysql_num_rows($result);
}

// ELEVER

function addElev($id, $fornamn, $efternamn, $klassid){
	
	$q = "INSERT INTO elever (id, fornamn, efternamn, klass_id) VALUES ('$id', '$fornamn', '$efternamn', '$klassid')";
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addElev");
	}
	
	return $result;
}

function elevExists($elevId){
	return rowExist("elever", "id", $elevId);
}

function countElever(){
	return countRows("elever");
}

function getEleverAsArray($where = ""){
	if($where != ""){
		$where = "WHERE $where";
	}
	$q = "SELECT * FROM elever $where ORDER by efternamn";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getEleverAsArray");
	}
	
	$elever = array();
	while($elev = mysql_fetch_assoc($result)){
		$elev["fullNamn"] = $elev["fornamn"] + " " + $elev["efternamn"];
		array_push($elever, $elev);
	}
	
	return $elever;
}

function getEleverFromKlassAsArray($KlassId, $where = ""){
	$theWhere = " WHERE elever.klass_id = klasser.id AND klasser.id = '$KlassId'";
	if($where != ""){
		$theWhere = " AND Where";
	}
	$q = "SELECT elever.id as elevid, klasser.id as klassid, fornamn, efternamn FROM elever, klasser$theWhere ORDER by efternamn";
	//print "<p>$q</p>";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getEleverAsArray");
	}
	
	$elever = array();
	while($elev = mysql_fetch_assoc($result)){
		$elev["fullNamn"] = $elev["fornamn"] . " " . $elev["efternamn"];
		array_push($elever, $elev);
	}
	
	return $elever;
}

function getElevnamn($elevId, $includeKlass = false){
	$q = "SELECT elever.id as elevid, klasser.id as klassid, fornamn, efternamn FROM elever, klasser WHERE elever.klass_id = klasser.id AND elever.id = '$elevId'";
	
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getElevnamn");
	}
	
	$elev = mysql_fetch_assoc($result);
	
	$namn =  $elev["fornamn"] . " " . $elev["efternamn"];
	
	if($includeKlass){
		$namn = $namn . " (" . $elev["klassid"] . ")";
	}
	
	return $namn;

}


// ELEVER-KURS

function addRelationElevKurs($elevId, $kursId){
	
	$q = "INSERT INTO kurser_elever (kurs_id, elev_id) VALUES ('$kursId', '$elevId')";
	debugLog("q:$q" , "db_functions|addRelationElevKurs");
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addRelationElevKurs");
	}
	
	return $result;
}

// Lärare

function addLarare($id, $mentorKlassId = ""){
	
	if($mentorKlassId == ""){
		$q = "INSERT INTO larare (id) VALUES ('$id')";
	} else {
		$q = "INSERT INTO larare (id, klass_id) VALUES ('$id', '$mentorKlassId')";
	}
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addLarare");
	}
	
	return $result;
}

function larareExists($lararId){
	return rowExist("larare", "id", $lararId);
}

function countLarare(){
	return countRows("larare");
}

function getLarareAsArray($where = ""){
	if($where != ""){
		$where = "WHERE $where";
	}
	$q = "SELECT * FROM larare $where ORDER by id";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getLarareAsArray");
	}
	
	$larare = array();
	while($larar = mysql_fetch_assoc($result)){
		array_push($larare, $larar);
	}
	
	return $larare;
}

function getLarareAsAssocArray($where = ""){
	$arr = getLarareAsArray($where);
	
	$lararAssoc = array();
	while($larare = mysql_fetch_assoc($result)){
		$lararAssoc[$larare["id"]] = $larare["fornamn"] . " " . $larare["efternamn"];
	}
	
	return $lararAssoc;
}

function getLarareForKursAsArray($kursId){
	$q = "SELECT * FROM kurser_larare JOIN larare ON kurser_larare.larar_id = larare.id WHERE kurser_larare.kurs_id = '$kursId' ORDER by larare.id";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getLarareForKursAsArray");
	}
	
	$larare = array();
	while($larar = mysql_fetch_assoc($result)){
		array_push($larare, $larar);
	}
	
	return $larare;
}

// Kärare-Kurs

function addRelationLarareKurs($lararId, $kursId){
	
	$q = "INSERT INTO kurser_larare (kurs_id, larar_id) VALUES ('$kursId', '$lararId')";
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addLarareKurs");
	}
	
	return $result;
}

// Klass

function addKlass($id){
	
	$q = "INSERT INTO klasser (id) VALUES ('$id')";
	
	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|addKlass");
	}
	
	return $result;
}

function klassExists($klassId){
	return rowExist("klasser", "id", $klassId);
}

function countKlasser(){
	return countRows("klasser");
}

function getKlasserAsArray($where = ""){
	if($where != ""){
		$where = "WHERE $where";
	}
	$q = "SELECT * FROM klasser $where ORDER by id";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getKlasserAsArray");
	}
	$result = mysql_query($q);
	
	$klasser = array();
	while($klass = mysql_fetch_assoc($result)){
		array_push($klasser, $klass);
	}
	
	return $klasser;
}

function getKlasserWithBokningarAsAssocArray(){
	$q="SELECT DISTINCT klasser.id as klassid FROM kurser_bocker ";
	$q=$q . "JOIN kurser ON kurser_bocker.kurs_id = kurser.id ";
	$q=$q . "JOIN kurser_elever ON kurser.id = kurser_elever.kurs_id ";
	$q=$q . "JOIN elever ON kurser_elever.elev_id = elever.id ";
	$q=$q . "JOIN klasser ON klass_id = klasser.id ";
	$q=$q . "ORDER BY klassid";
	
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getKlasserAsArray");
	}
	$result = mysql_query($q);
	//print "<p>".mysql_num_rows($result)."</p>";
	$klasser = array();
	while($klass = mysql_fetch_assoc($result)){
		//print "<p>".$klass["klassid"]."</p>";
		$klasser[$klass["klassid"]] = $klass["klassid"];
	}
	
	return $klasser;
}

// in-ut-tillfällen
function getInutTillfallenAsAssocArray($where = ""){
	if($where != ""){
		$where = "WHERE $where";
	}
	$q = "SELECT * FROM inuttillfallen $where ORDER by sortorder";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getInutTillfallenAsAssocArray");
	}
	
	$tillfallen = array();
	while($tillfalle = mysql_fetch_assoc($result)){
		$tillfallen[$tillfalle["id"]] = $tillfalle["beskrivning"];
	}
	
	return $tillfallen;
}

function getTillfalleDescLongForId($tillfalleId){
	$q = "SELECT * FROM inuttillfallen WHERE id = '$tillfalleId'";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getTillfalleDescLongForId");
	}
	
	$row = mysql_fetch_assoc($result);
	
	return $row["beskrivning"];
}

function getTillfalleDescShortForId($tillfalleId){
	$q = "SELECT * FROM inuttillfallen WHERE id = '$tillfalleId'";
	$result = mysql_query($q);
	
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getTillfalleDescShortForId");
	}
	
	$row = mysql_fetch_assoc($result);
	
	return $row["beskrivning_kort"];
}


// Läsår

function getLasarAsAssocArray(){

	$q = "SELECT * FROM lasar ORDER by id";

	$result = mysql_query($q);
	if(!$result){
		debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error() , "db_functions|getBockerAsArray");
	}
	
	$lasarArr = array();
	while($lasar = mysql_fetch_assoc($result)){
		$lasarArr[$lasar["id"]] = $lasar["id"];
	}
	
	return $lasarArr;
}

// GENERALLA

function countRows($table){
	$q = "SELECT * FROM $table";
	return mysql_num_rows(mysql_query($q));
}


// FLYYTAD KLASS DO
function rowExist($table, $idField, $id, $idIsString = true){
	if($idIsString){
		$id = "'$id'";		
	}
	$q = "SELECT $idField FROM $table WHERE $idField = $id";
	
	$result = mysql_query($q);
	//print "<p>$q - " . mysql_num_rows($result) . "</p>";
	//debugLog("q: $q, result: " . mysql_num_rows($result), "rowExist");
	
	//debugLog("DB-fel: " . mysql_error(), "rowExist");
	
	if(mysql_num_rows($result) > 0){
		return true;
	} else {
		return false;
	}
}

function getDbTabellHTML($tabellNamn){
	$html = "";
	$q = "SELECT * FROM $tabellNamn";
	$r = mysqli_query(CONFIG::$DB_LINK, $q);
	return getDbResursHTML($r);


}


function rowExistRelation($table, $idField1, $idField2, $id1, $id2, $id1IsString = true, $id2IsString = true){
	if($id1IsString){
		$id1 = "'$id1'";		
	}
	if($id2IsString){
		$id2 = "'$id2'";		
	}
	
	$q = "SELECT $idField1 FROM $table WHERE $idField1 = $id1 AND $idField2 = $id2";
	
	$result = mysql_query($q);
	
	if(mysql_num_rows($result) > 0){
		return true;
	} else {
		return false;
	}
}

function EMPTY_ALL_IMPORT_DATA_TABLES(){
	debugLog("startad", "EMPTY_ALL_IMPORT_DATA_TABLES");
	$kursPoster = mysql_query("DELETE FROM kurser");
	$elevPoster = mysql_query("DELETE FROM elever");
	$lararPoster = mysql_query("DELETE FROM larare");
	$klassPoster = mysql_query("DELETE FROM klasser");
	$kurserEleverPoster = mysql_query("DELETE FROM kurser_elever");
	$kurserLararePoster = mysql_query("DELETE FROM kurser_larare");
	
	return "<div class=\"info-box\"><p><strong>TÖMT ALLA TABELLER MED IMPORT-DATA</strong></p><p>Kurser: $kursPoster, Elever: $elevPoster, Lärare: $lararPoster, Klasser: $klassPoster, Koppling kurs-elev: $kurserEleverPoster, Koppling kurs-lärare: $kurserLararePoster</p></div>";
}

function DB_UPDATE(){
	$q1 = "UPDATE inuttillfallen SET beskrivning='Årskursens SLUT' WHERE id='slut'";
	$q2 = "UPDATE inuttillfallen SET beskrivning='Årskursens START' WHERE id='start'";
	$q3 = "UPDATE inuttillfallen SET beskrivning='Vårterminens START' WHERE id='vt'";
	mysql_query($q1);
	print "UPPDERTAT inuttillfallen: [" . mysql_info() . "]";
	mysql_query($q2);
	print "UPPDERTAT inuttillfallen: [" . mysql_info() . "]";
	mysql_query($q3);
	print "UPPDERTAT inuttillfallen: [" . mysql_info() . "]";

}

?>