<?php
	require_once("class_bok.php");
	require_once("class_termin.php");
?>

<div class="page-header">
	<h1>Böcker</h1>
</div>


<?php

	$infoContent = "<ul>
						<li>Klicka på Boka-knappen vid en bok du vill boka för en kurs</li>
						<li>Välj en bok för att se information och länk för detaljblad om boken</li>
					</ul>";
	if(isAdmin()){
		$infoContent .= "<p><strong>Administratör</strong>: När en bok väljs får du även upp val för att radera och redigera boken</p>";
	}

	HTML_FACTORY::printPanel("info", "Så här gör du", $infoContent);

	

	if(isset($_GET[Config::PARAM_ID])){
		$activeTermin = new Termin();
		$activeTermin->setFromId($_GET[Config::PARAM_ID]);
	} else {
		$activeTermin = Termin::getCurrentTermin();
	}

	print "<nav class=\"navbar\" id=\"nav-bok\" role=\"navigation\">";
	if(isAdmin()){
		print HTML_FACTORY::getKnappHTML("bocker-add", "Lägg till en bok", "success", "Lägg till en ny bok");
										
	}
	print "<div class=\"navbar-form navbar-right\">".Termin::getTerminSelectWidget("bocker", $activeTermin)."</div>";
	print "<p class=\"navbar-text navbar-right\">Visar tillgänglighet för terminen</p>";
	
	print "</nav>";
?>

<table class="table main<?php if(isLoggedin()){ print " table-hover";} ?> table-striped bocker"><thead>
<tr>
<th>&nbsp;</th>
<th>&nbsp;</th>
<!-- <th>Författare</th> -->
<th></th>
</tr></thead><tbody>

<?php

//$bocker = getBockerAsArray();
$bocker = Bok::getAll();
$index=0;

foreach($bocker as $bok){
	
	$antalObj = $bok->getAntalBokade($activeTermin->id);
	
	if($antalObj->bokbar){
		$statusClass = "";
		$buttonClass = "success";	
	} else {
		$statusClass = "unavaible";
		$buttonClass = "danger";	
	}
	print "<tr class=\"$statusClass\">";
	
	print "<td>";
	if($antalObj->bokbar && isLoggedin()){
		print HTML_FACTORY::getBokaKnappHTML("sm", "bok", $bok->isbn, "Boka boken!");
	} 	
	print "</td>";
	
	print "<td>";
		print $bok->getHtmlTdSnippet($index, $activeTermin->id, $antalObj, true);
	print "</td>";


	print "<td><button class=\"btn btn-$buttonClass btn-xs\" type=\"button\">Tillgängliga <span class=\"badge\">$antalObj->bokbara</span></button></td>";
	
	print "</tr>";

	$index++;

} 

?>

</tbody></table>