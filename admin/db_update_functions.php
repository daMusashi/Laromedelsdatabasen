<?php

// konverterar 1.0 Lasar + in/ut-id till class Tillfälle-id
function toTillfalle(){
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

// Fix till ovan där en del att null och blev 2014-2015:null, ändras till 2014-2015:slut
function toTillfalleNullFix(){
		
		$q = "UPDATE kurser_bocker SET in_tid_id = '2014-2015:slut' WHERE in_tid_id = '2014-2015:null'";
		$result2 = mysqli_query(Config::$DB_LINK, $q);
		print "<p><strong>$result2</strong> rader uppdaterades med $q</p>";
}

// konverterar null för kurs i bokningar till ospec
function toOspec(){
	require_once("class_kurs.php");

	$qq = "UPDATE kurser_bocker SET kurs_id = '".Kurs::OSPEC_ID."' WHERE kurs_id = 'null'";
	$result2 = mysqli_query(Config::$DB_LINK, $qq);
	
	print "<p><strong>$result2</strong> rader uppdaterades med $qq </p>";

}

// function add start-slut-tid till kurser
function addTimeToKurser() {
	$q = "ALTER TABLE kurser ADD `starttid` VARCHAR(20) NOT NULL";
	mysqli_query(Config::$DB_LINK, $q);
	$q = "ALTER TABLE kurser ADD `sluttid` VARCHAR(20) NOT NULL";
	mysqli_query(Config::$DB_LINK, $q);

	$q = "UPDATE kurser SET starttid = '2014-2015:start'";
	mysqli_query(Config::$DB_LINK, $q);
	$q = "UPDATE kurser SET sluttid = '2014-2015:slut'";
	mysqli_query(Config::$DB_LINK, $q);
}
?>