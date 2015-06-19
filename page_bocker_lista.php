<?php
	require_once("class_bok.php");
?>

<h1>Böcker</h1>

<div class="panel panel-info">
<div class="panel-heading">Så här gör du</div>
<div class="panel-body">
<ul>
<li>Klicka på Boka-knappen vid en bok du vill boka för en kurs</li>
<li>Välj en bok för att se information och länk för detaljblad om boken</li>
</ul>
<?php if(isAdmin()){ ?>
<p><strong>Administratör</strong>: När en bok väljs får du även upp val för att radera och redigera boken</p> 
<?php } ?>
</div></div>

<?php
	if(isAdmin()){
		print "<div>".HTML_FACTORY::getBokKnappHTML("add", "Lägg till en bok", "", "Skapa en ny bok", "button-green button-small-icon-add")."</div>";	
	}
?>


<table class="table main<?php if(isLoggedin()){ print " table-hover";} ?> bocker"><thead>
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
	$antal = $bok->getAntalBokade();
	if($antal->bokbar){
		$statusClass = "";
		$buttonClass = "success";	
	} else {
		$statusClass = "unavaible";
		$buttonClass = "danger";	
	}
	
	print "<tr class=\"$statusClass\">";
	
	print "<td>";
	if($antal->bokbar && isLoggedin()){
		print HTML_FACTORY::getBokaKnappHTML("sm", "bok", $bok->isbn, "Boka boken!");
	} 	
	print "</td>";
	
	print "<td>";
		print $bok->getHtmlTdSnippet($index, $antal);	
	print "</td>";


	print "<td><button class=\"btn btn-$buttonClass btn-xs\" type=\"button\">Tillgängliga <span class=\"badge\">$antal->bokbara</span></button></td>";
	
	print "</tr>";

	$index++;

} 

?>

</tbody></table>