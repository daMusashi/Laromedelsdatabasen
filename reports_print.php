<?php 
// Returnerar bara HTML
session_start();
?>
<?php
require_once("config.php");
require_once("connect.php");
require_once("db_functions.php");
require_once("functions.php");
require_once("admin/dev_functions.php");


function printBockerForKurs($kursId){
	$bokArr = getBockerForKursAsArray($kursId);
	print "<h1>Böcker bokade för kurs $kursId</h1>";
	print "<h2>Antal</h2>";
	print "XX elever i kursen = XX exmplear av varje bok nedan bokade";
	print "<h2>Bokade böcker</h2>";
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr><th>Titel</th><th>Författare</th></tr>";
	foreach($bokArr as $bok){
		print "<tr><td>" . getFullBokTitelFromFieldAssoc($bok) . "</td>";
		print "<td>" . $bok["forf_fornamn"] . " " . $bok["forf_efternamn"] . "</td></tr>";
	}
	print "</table>";
}

function printBockerForKlass($klassId){
	$bokArr = getBockerForKlassAsArray($klassId);
	print "<h1>Böcker bokade för klass $klassId</h1>";
	print "<h2>Antal</h2>";
	print "XX elever i kursen = XX exmplear av varje bok nedan bokade";
	print "<h2>Bokade böcker</h2>";
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr><th>Titel</th><th>Författare</th><th>För kurs</th></tr>";
	foreach($bokArr as $bok){
		print "<tr><td>" . getFullBokTitelFromId($bok["isbn"]) . "</td>";
		print "<td>" . $bok["forf_fornamn"] . " " . $bok["forf_efternamn"] . "</td>";
		print "<td>" . $bok["kursid"] . "</td></tr>";
	}
	print "</table>";
}

function printBockerForElev($elevId){
	$bokArr = getBockerForElevAsArray($elevId);
	print "<h1>Böcker bokade för elev $elevId</h1>";
	print "<h2>Antal</h2>";
	print "XX elever i kursen = XX exmplear av varje bok nedan bokade";
	print "<h2>Bokade böcker</h2>";
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr><th>Titel</th><th>Författare</th><th>För kurs</th></tr>";
	foreach($bokArr as $bok){
		print "<tr><td>" . getFullBokTitelFromId($bok["isbn"]) . "</td>";
		print "<td>" . $bok["forf_fornamn"] . " " . $bok["forf_efternamn"] . "</td>";
		print "<td>" . $bok["kursid"] . "</td></tr>";
	}
	print "</table>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rapport</title>

<link href="css/print.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
if(isset($_GET[$CONFIG["primNavParam"]])){
	switch($_GET[$CONFIG["primNavParam"]]){
		case "bocker-i-kurs":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				printBockerForKurs($_GET[$CONFIG["refIdParam"]]);
			}
			break;
		case "bocker-i-klass":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				printBockerForKlass($_GET[$CONFIG["refIdParam"]]);
			}
			break;
		case "bocker-for-elev":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				printBockerForElev($_GET[$CONFIG["refIdParam"]]);
			}
			break;
	}
}
?>
</body>
</html>