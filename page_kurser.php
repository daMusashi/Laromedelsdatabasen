<?php
	global $CONFIG;

	require_once("class_kurs.php");
	require_once("class_bok.php");
	require_once("class_larare.php");
	require_once("class_html_factory.php");
	require_once("class_tillfalle.php");
	require_once("class_tillfalle_occasion.php");
?>
<h1>Kurser</h1>

<div class="info-box">
<p>Välj en kurs för att se detaljerad information om bokningningarna för den (kurser utan bokningar är inte valbara)</p>
</div>

<?php

if(isset($_GET[Config::PARAM_ID])){
	$activeLasar = $_GET[Config::PARAM_ID];
} else {
	$activeLasar = Tillfalle_year::getCurrentLasarId();
}

$kurser = Kurs::getAll();

$lasar = [];
foreach($kurser as $kurs){
	$startYear = $kurs->startTid->year;
	$slutYear = $kurs->slutTid->year;
	
	if (!array_key_exists($startYear->id, $lasar)){
		
		$lasar[$startYear->id] = $startYear->desc;
	}
	if (!array_key_exists($slutYear->id, $lasar)){
		
		$lasar[$slutYear->id] = $slutYear->desc;
	}
}

print "<p>$activeLasar</p>";

print "<ul class=\"nav nav-tabs\">";
foreach($lasar as $id=>$desc){
	$active = "";
	if($id == $activeLasar){
		$active = " class=\"active\"";
	}

	$a = "<a href=\"?".Config::PARAM_NAV."=kurser&".Config::PARAM_ID."=$id\">$desc</a>";
	print "<li role=\"presentation\"$active>$a</li>";
}
print "</ul>";

$Selectedkurser = Kurs::getAllForLasar($activeLasar);


?>

<table class="table main<?php if(isLoggedin()){ print " table-hover";} ?> table-striped kurser"><thead>
<tr class="info">
<th>&nbsp;</th>
<th>Namn</th>
<th>Antal <br />elever</th>
<th>Start</th>
<th>Slut</th>
<th>Lärare</th>
<th>Bokade böcker</th>
</tr></thead><tbody>
<?php

$rowIndex = 0;
$bokIndex = 0;

foreach($Selectedkurser as $kurs){
	
	print "<tr>";
	
	print "<td>";
	if(isLoggedin()){
		print HTML_FACTORY::getBokaKnappHTML("sm", "kurs", $kurs->id, "Boka en bok för kursen");
	}
	print "</td>";
	
	print "<td class=\"major\">" . $kurs->id . "</td>";
	
	print "<td class=\"minor\">" .  $kurs->antalElever . "</td>";

	print "<td class=\"minor\">" .  $kurs->startTid->desc. "</td>";
	print "<td class=\"minor\">" .  $kurs->slutTid->desc. "</td>";

	
	$lararStr = "";
	if(count($kurs->larare) > 0){
		foreach($kurs->larare as $larare){
			$lararStr = $lararStr . $larare->id . "<br />";
		}
	} else {
		$lararStr = "Ingen lärare<br />knuten till kursen än";
	}
	print "<td class=\"minor\">$lararStr</td>";
	
	print "<td>";
	if(count($kurs->bocker) > 0){
		print "<div>";
		foreach($kurs->bocker as $bok){
			print $bok->getHtmlTdSnippet($bokIndex, null, true, $kurs->id);
			$bokIndex++;	
		}
		print "</div>";
	} else {
		print "<em>Inga bokningar än</em>";
	}
	print "</td>";
	
	print "</tr>";
	$rowIndex++;
}

?>
</tbody></table>