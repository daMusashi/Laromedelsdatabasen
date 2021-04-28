<?php

// OBSELUT !!!!! ANNAN LÖSNING GJORD konverterar 1.0 Lasar + in/ut-id till class Tillfälle-id
function _OBSELET_toTillfalle(){
	$q = "SELECT * FROM kurser_bocker";

	$result = mysqli_query(Config::$DB_LINK, $q);

	while($bokningFieldArr = mysqli_fetch_assoc($result)){
		$in = "2014-2015:".$bokningFieldArr["in_tid_id"];
		$ut = "2014-2015:".$bokningFieldArr["ut_tid_id"];
		
		$qq = "UPDATE kurser_bocker SET in_tid_id = '".$in."', ut_tid_id = '".$ut."' WHERE bok_id = '".$bokningFieldArr["bok_id"]."' AND kurs_id = '".$bokningFieldArr["kurs_id"]."'";
		$result2 = mysqli_query(Config::$DB_LINK, $qq);
		print "<p><strong>$result2</strong> rader uppdaterades med $qq</p>";
	}
}

// OBSELUT !!!!! ANNAN LÖSNING GJORD Fix till ovan där en del att null och blev 2014-2015:null, ändras till 2014-2015:slut
function _OBSELET_toTillfalleNullFix(){
		
		$q = "UPDATE kurser_bocker SET in_tid_id = '2014-2015:slut' WHERE in_tid_id = '2014-2015:null'";
		$result2 = mysqli_query(Config::$DB_LINK, $q);
		print "<p><strong>$result2</strong> rader uppdaterades med $q</p>";
}

// OBSELUT !!!!! ANNAN LÖSNING GJORD konverterar null för kurs i bokningar till ospec
function _OBSELET_toOspec(){
	require_once("class_kurs.php");

	$qq = "UPDATE kurser_bocker SET kurs_id = '".Kurs::OSPEC_ID."' WHERE kurs_id = 'null'";
	$result2 = mysqli_query(Config::$DB_LINK, $qq);
	
	print "<p><strong>$result2</strong> rader uppdaterades med $qq </p>";

}

// OBSELUT !!!!! ANNAN LÖSNING GJORD function add start-slut-tid till kurser
function _OBSELET_addTimeToKurser0() {
	$q = "ALTER TABLE kurser ADD `starttid` VARCHAR(20) NOT NULL";
	mysqli_query(Config::$DB_LINK, $q);
	$q = "ALTER TABLE kurser ADD `sluttid` VARCHAR(20) NOT NULL";
	mysqli_query(Config::$DB_LINK, $q);

	$q = "UPDATE kurser SET starttid = '2014-2015:start'";
	mysqli_query(Config::$DB_LINK, $q);
	$q = "UPDATE kurser SET sluttid = '2014-2015:slut'";
	mysqli_query(Config::$DB_LINK, $q);
}

// function add start-slut-terminer till kurser
// LÄGG TILL URSPRUNGLIG DATA FÖRST!!!!!!!
function addTimeToKurser() {
	$q = "ALTER TABLE kurser ADD `starttermin` VARCHAR(20) NOT NULL";
	mysqli_query(Config::$DB_LINK, $q);
	$q = "ALTER TABLE kurser ADD `sluttermin` VARCHAR(20) NOT NULL";
	mysqli_query(Config::$DB_LINK, $q);
}

function moveTimeToKurser() {
	require_once("class_termin.php");

	// sätt alla kurser till det förvala start och slut
	$q = "UPDATE kurser SET starttermin = '2014-2015:ht', sluttermin = '2014-2015:vt'";
	//print "<p>$q</p>";
	$result0 = mysqli_query(Config::$DB_LINK, $q);


	// flytta över befintlig info från bokningar med uppdatering
	$q = "SELECT * FROM kurser_bocker";

	$result = mysqli_query(Config::$DB_LINK, $q);
	print mysqli_error(Config::$DB_LINK);

	while($bokning = mysqli_fetch_assoc($result)){
		$kursId = $bokning["kurs_id"];

		switch($bokning["ut_tid_id"]){
			case "vt":
				$startTermintyp = "vt";
				break;
			case "start":
				$startTermintyp = "ht";
				break;
			case "slut":
				$startTermintyp = "vt";
				break;
			default:
				$startTermintyp = "ht";
		}

		switch($bokning["in_tid_id"]){
			case "vt":
				$slutTermintyp = "vt";
				break;
			case "start":
				$slutTermintyp = "ht";
				break;
			case "slut":
				$slutTermintyp = "vt";
				break;
			default:
				$slutTermintyp = "ht";
		}

		$startTermin = new Termin("2014", $startTermintyp);
		$slutTermin = new Termin("2014", $slutTermintyp);

		$q = "UPDATE kurser SET starttermin = '".$startTermin->id."', sluttermin = '".$slutTermin->id."' WHERE id = '".$kursId."'";
		//print "<p>$q</p>";
		$result2 = mysqli_query(Config::$DB_LINK, $q);
		print mysqli_error(Config::$DB_LINK);

	}
}


// Byter namn på KURSER_BOCKER till Bokningar
function renameBokningar(){
	$q = "RENAME TABLE  `kurser_bocker` TO  `bokningar`";
	mysqli_query(Config::$DB_LINK, $q);
}

// tar bort gamla - ej längre använda - tids-fält i bokningar
function removeTimeFromBokningar() {
	print "<h2>Tar bort oanvända fält från bokningar</h2>";

	dropColumn("bokningar", "ut_tid_id");
	dropColumn("bokningar", "NOT_USED_ut_lasar_id");
	dropColumn("bokningar", "in_tid_id");
	dropColumn("bokningar", "in_lasar_id");
	dropColumn("bokningar", "ut_lasar_id");

}

// Skapa "enkla" id-fält för bokningar, kurser och bocker och "binder om"
function createIds(){
	
	print "<h2>Skapa unikt ID-fält</h2>";
	
	// bokningar
	print "<h3>Bokningar</h3>";
	print "<p>Tar bort existerande PK</p>";
	$q = "ALTER TABLE bokningar DROP PRIMARY KEY";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}
  	print "<p>Lägger till ID-fält</p>";
	$q = "ALTER TABLE bokningar add column id INT NOT NULL AUTO_INCREMENT FIRST, ADD primary KEY id(id)";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}

	// böcker
	print "<h3>Böcker</h3>";
	print "<p>Tar bort existerande PK</p>";
	$q = "ALTER TABLE bocker DROP PRIMARY KEY";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}
  	print "<p>Lägger till ID-fält</p>";
	$q = "ALTER TABLE bocker add column id INT NOT NULL AUTO_INCREMENT FIRST, ADD primary KEY id(id)";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}

	/* TAR BORT LÄGGA TILL EGET ID PÅ KURSER _ DÅLIG IDE PGA IMPORT

	// kurser
	print "<h3>Kurser</h3>";
	print "<p>Tar bort existerande PK</p>";
	$q = "ALTER TABLE kurser DROP PRIMARY KEY";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}
  	print "<p>Byternamn på gammalt id-fält till 'namn'</p>";
	//// byter namn på gamla id-kolumnen (som innehåller namn) till namn
	$q = "ALTER TABLE kurser CHANGE id namn varchar(60)";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}
	print "<p>Lägger till ID-fält</p>";
	$q = "ALTER TABLE kurser add column id INT NOT NULL AUTO_INCREMENT FIRST, ADD primary KEY id(id)";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}
  	*/
}

// "binder om" nya ids
function createNewIdsBinds(){
	print "<h2>Skriver om kopplingar med ny ID's från createIds()</h2>";

	require_once("class_bokning.php");
	require_once("class_bok.php");
	require_once("class_kurs.php");

	// bocker -> bokningar
	$bokningar = Bokning::getAll(null, false, true);

	foreach ($bokningar as $bokning) {
		$bok = new Bok();
		$bok->setFromISBN($bokning->bokId);

		/* TAR BORT LÄGGA TILL EGET ID PÅ KURSER _ DÅLIG IDE PGA IMPORT
		$kurs = new Kurs();
		$kurs->setFromName($bokning->kursId);

		$bokning->kursId = $kurs->id;
		*/
	
		$bokning->bokId = $bok->id;
		

		//$bokning->save();
		print "<h2>bokning-id: ".$bokning->id . "</h2>";
		print "<ul><li>bokning-BOKid: ".$bokning->bokId . "</li>";
		print "<li>bokning-KURSid: ".$bokning->kursId . "</li>";
		print "</ul>";

		try {
			$bokning->save();
			print "<p>UPPDATERAD</p>";
		} catch (Exception $e) {
    		print "<p class=\"warning\">".  $e->getMessage() . "</p>";
		}
	}
}

/* generella upd-funktion */

// ta bort fält
function dropColumn($table, $column){
	print "<h3>Tar bort '$column'</h3>";
	$q = "ALTER TABLE $table DROP COLUMN $column";
	mysqli_query(Config::$DB_LINK, $q);
	if (mysqli_connect_errno()){
  		echo "<p>FEL: " . mysqli_connect_error() . "</p>";
  	} else {
  		print "<p>KLAR</p>";
  	}
}

function createRandomKurser($antal, $minLasar = 2014, $maxLasar = 2017){
	require_once("class_kurs.php");

	for($i = 0; $i < $antal; $i++){
		
		$startLasar = round(rand($minLasar, $maxLasar));
		if(($startLasar + 1) < $maxLasar){
			$maxLasar = $startLasar + 1;
		}
		$slutLasar = ceil(rand($startLasar, $maxLasar));



		$startTermin = round(rand(0, 1));
		if(($startLasar == $slutLasar)&&($startTermin == 1)){
			$slutTermin = 1;
		} else {
			$slutTermin = round(rand(0, 1));
		}

		if($startTermin == 0){
			$startTermin = "ht";
		} else {
			$startTermin = "vt";
		}
		if($slutTermin == 0){
			$slutTermin = "ht";
		} else {
			$slutTermin = "vt";
		}

		$startLasar = "$startLasar-".($startLasar+1);
		$slutLasar = "$slutLasar-".($slutLasar+1);

		$k = new Kurs();

		$k->setFromDataToSave("TEST KURS $i", "$startLasar:$startTermin", "$slutLasar:$slutTermin");

		print "<h3>Skapa kurs ".$k->id."</h3>";
		try {
			$k->save();
			print "<p>SKAPAD $startLasar $slutLasar</p>";
		} catch (Exception $e) {
    		print "<p class=\"warning\">".  $e->getMessage() . "</p>";
		}
	}
}
?>