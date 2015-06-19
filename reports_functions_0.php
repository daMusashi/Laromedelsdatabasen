<?php 
function printBockerForKurs($kursId){
	$bokArr = getBockerForKursAsArray($kursId);
	$bokCount = antalEleverIKurs($kursId);
	insertHeader();
	print "<h1>Litteraturlista för kurs $kursId</h1>";
	print "<h2>Antal</h2>";
	print "<strong>$bokCount</strong> elever i kursen = <strong>$bokCount</strong> exemplar av varje bok nedan bokade";
	print "<h2>Bokade böcker</h2>";
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr><th>Titel</th><th>Författare</th></tr>";
	foreach($bokArr as $bok){
		print "<tr><td>" . getFullBokTitelFromFieldAssoc($bok) . "</td>";
		print "<td>" . $bok["forf_fornamn"] . " " . $bok["forf_efternamn"] . "</td></tr>";
	}
	print "</table>";
	insertPrintButton("kurs-print-data");
	insertFooter();
}

function printBockerForKlass($klassId){
	$bokArr = getBockerForKlassAsArray($klassId);
	insertHeader();
	print "<h1>Litteraturlista för klass $klassId</h1>";
	print "<h2>Böcker</h2>";
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr><th>Titel</th><th>Författare</th><th>För kurs</th></tr>";
	foreach($bokArr as $bok){
		print "<tr><td>" . getFullBokTitelFromId($bok["isbn"]) . "</td>";
		print "<td>" . $bok["forf_fornamn"] . " " . $bok["forf_efternamn"] . "</td>";
		print "<td>" . $bok["kursid"] . "</td></tr>";
	}
	print "</table>";
	insertPrintButton("klass-print-data");
	insertFooter();
}

function printBockerForElev($elevId){
	$bokArr = getBockerForElevAsArray($elevId);
	$elevNamn = getElevnamn($elevId, true);
	insertHeader();
	print "<h1>Litteraturlista för $elevNamn</h1>";
	print "<h2>Böcker</h2>";
	print "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	print "<tr><th>Titel</th><th>Författare</th><th>För kurs</th></tr>";
	foreach($bokArr as $bok){
		print "<tr><td>" . getFullBokTitelFromId($bok["isbn"]) . "</td>";
		print "<td>" . $bok["forf_fornamn"] . " " . $bok["forf_efternamn"] . "</td>";
		print "<td>" . $bok["kursid"] . "</td></tr>";
	}
	print "</table>";
	//insertPrintButton("elev-print-data");
	print getSkrivutKnapp("elev-print-data", "Skriv ut vald elev", "Skriv ut litteraturlisa för vald elev", "big", "green");
	insertFooter();
}

function printBockerForEleverIKlass($klassId){
	$elevArr = getEleverFromKlassAsArray($klassId);	
	foreach($elevArr as $elev){
		print "<div class=\"print-elev\">";
		printBockerForElev($elev["elevid"]);
		print "</div>";
	}
}

function insertHeader(){
	//print "<div class=\"print\">";
}

function insertPrintButton($printElement){
	print getSkrivutKnapp($printElement, "Skriv ut", "Skriv ut den här litteraturlistan", "big", "green");
}

function insertFooter(){
	//print "</div>";
}
?>
