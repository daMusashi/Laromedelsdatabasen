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
	if(!Config::SIMPLE_MODE){
		print "<div class=\"navbar-form navbar-right\">".Termin::getTerminSelectWidget("bocker", $activeTermin)."</div>";
		print "<p class=\"navbar-text navbar-right\">Visar tillgänglighet för terminen</p>";
	}
	
	print "</nav>";
?>

<table class="table main<?php if(isLoggedin()){ print " table-hover";} ?> table-striped bocker"><thead>
<tr>
<th>&nbsp;</th> <!-- boka-knapp -->
<th>&nbsp;</th> <!-- titel -->
<!-- <th>Författare</th> -->
<th></th> <!-- tillgänglighet -->
</tr></thead><tbody>

<?php

//$bocker = getBockerAsArray();
$bocker = Bok::getAll();
$index=0;

foreach($bocker as $bok){
	
	$statusClass = "";

	if(!Config::SIMPLE_MODE){
		$antalObj = $bok->getAntalBokade($activeTermin->id);
		
		if($antalObj->bokbar){
			$statusClass = "";
			$buttonClass = "success";
			if($antalObj->bokbara <= Config::BOK_INSTOCK_WARNING){
				$buttonClass = "warning";
			}	
		} else {
			$statusClass = "unavaible";
			$buttonClass = "danger";	
		}
	}
	print "<tr class=\"$statusClass\">";
	
	print "<td>";
	if(Config::SIMPLE_MODE){
		if(isLoggedin()){
			print HTML_FACTORY::getBokaKnappHTML("sm", "bok", $bok->id, "Boka boken!");
		}
	} else {
		if($antalObj->bokbar && isLoggedin()){
			print HTML_FACTORY::getBokaKnappHTML("sm", "bok", $bok->id, "Boka boken!");
		} 
	}
	print "</td>";
	
	print "<td class=\"major\">";
		if(Config::SIMPLE_MODE){
			print "".$bok->fullTitel."";
		} else {
			print HTML_FACTORY::getBokTdInfoSnippet($index, $bok, $antalObj);
		}
	print "</td>";

	if(!Config::SIMPLE_MODE){
		print "<td>";
		if(isLoggedin()){
			$bokningsLink = $bok->urlBoka;
		} else {
			$bokningsLink = "#";
		}
		print "<a href=\"".$bokningsLink."\" class=\"btn btn-$buttonClass btn-xs\">Tillgängliga ".$activeTermin->desc." <span class=\"badge\">$antalObj->bokbara</span></a> av ".$antalObj->antal;
		print "</td>";
	}

	print "</tr>";

	$index++;

} 

?>

</tbody></table>