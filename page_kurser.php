<?php
	require_once("class_kurs.php");
	require_once("class_bok.php");
	require_once("class_larare.php");
	require_once("class_html_factory.php");
	require_once("class_lasar.php");

?>
<div class="page-header">
	<h1>Kurser</h1>
</div>

<div class="info-box">
<p>Välj en kurs för att se detaljerad information om bokningningarna för den (kurser utan bokningar är inte valbara)</p>
</div>

<?php

if(isset($_GET[Config::PARAM_ID])){
	$activeLasar = new Lasar();
	$activeLasar->setFromId($_GET[Config::PARAM_ID]);
} else {
	$activeLasar = Lasar::getCurrentLasar();
}

print Lasar::getTabsHTML("kurser", $activeLasar->id, true);

$Selectedkurser = Kurs::getAllForTermin($activeLasar->getFirstTerminId(), true);


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
	
	print "<td class=\"major\">" . $kurs->namn . "</td>";
	
	print "<td class=\"minor\">" .  Kurs::getAntalElever($kurs->id) . "</td>";

	print "<td class=\"minor\">" .  $kurs->startTermin->desc. "</td>";
	print "<td class=\"minor\">" .  $kurs->slutTermin->desc. "</td>";

	
	$lararStr = "";
	$lararList = Kurs::getLarare($kurs->id);
	if(count($lararList) > 0){
		foreach($lararList as $larare){
			$lararStr = $lararStr . $larare->id . "<br />";
		}
	} else {
		$lararStr = "Ingen lärare<br />knuten till kursen än";
	}
	print "<td class=\"minor\">$lararStr</td>";
	
	print "<td>";
	$bokList = Kurs::getBocker($kurs->id);
	if(count($bokList) > 0){
		print "<div>";
		foreach($bokList as $bok){
			print $bok->getHtmlTdSnippet($bokIndex, $activeLasar->getFirstTerminId(), true, true, $kurs->id);
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