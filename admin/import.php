<?php
 include_once("import_functions.php");
 //global $CONFIG;
?>
<h1>Import av data</h1>

<h3>GÖR DETTA FÖRST</h3>
<ol>
	<li>Gör backup</li>
	<li>Kör update.php</li>

</ol>

<?php
if(isset($_GET["upload"])){

	if(isset($_FILES["rapport-elever"])&&isset($_FILES["rapport-grupper"])){
		
		// Kolla även http://www.php.net/manual/en/features.file-upload.php
		
		importLog("<div><h2>Rapport från Elev/grupp-import</h2>");
		
		$filesSucces = true;
		importLog("<h3>Läser in Elev-rapport</h3>");
		if(!$importfil_elever = getFile("rapport-elever")){
			$filesSucces = false;
		} else {
			importLog("<p>Elev-rapport inläst utan problem</p>");
		}
		
		importLog("<h3>Läser in Grupp-rapport</h3>");
		if(!$importfil_grupper = getFile("rapport-grupper")){
			$filesSucces = false;
		} else {
			importLog("<p>Grupp-rapport inläst utan problem</p>");
		}
		
		/*
		importLog("<h3>Läser in Lärar-rapport</h3>");
		if(!$importfil_larare = getFile("rapport-larare")){
			$filesSucces = false;
		} else {
			importLog("<p>Lärarrapport inläst utan problem</p>");
		}
		*/
		
	 	if($filesSucces){
			
			$lasarObj = new Lasar(2015);

			importLog("<h3>Raderar gammal import-data..</h3>");
	  		// TODO ARKIVERA ISTÄLLET
	  		//importLog(EMPTY_ALL_IMPORT_DATA_TABLES());
			
			importLog("<h3>Behandlar och lagrar Elev-rapporten</h3>");
			parseElever($importfil_elever);
			
			importLog("<h3>Behandlar och lagrar Grupp/kurs-rapporten</h3>");
			parseKurser($importfil_grupper, $lasarObj);
			
			print "<h2>Importen lyckades</h2>";
	  		print "<p>Datan i rapporterna är inläst (och ersatt eventuella äldre importer)</p>";
			
		} else {
	 		print "<h3>Importen misslyckades</h3>";
	  		print "<p>Uppladdning eller inläsning av en eller flera av rapportfilerna misslycka :'(</p>";
		}
	  


	} else { // om inte alla filer med
		print "<div class=\"error\"><h3>Gör om, gör rätt</h3><p><strong>Alla</strong> rapport-filer måste laddas upp :)</p></div>";
	}

	if(isset($_FILES["rapport-ind-elever"])){
		importLog("<div><h2>Rapport från Individuella kurser-import</h2>");
		
		importLog("<h3>Läser in Individuella kurser-rapporten-rapport</h3>");
		if(!$importfil_indElever = getFile("rapport-ind-elever")){
			importlLog("<div class=\"error\"><h3>Något gick fel vid inläsning</h3></div>");
			

		} else {
			importLog("<p>Individuella kurser-rapport inläst utan problem</p>");
			parseIndKurser($importfil_indElever);
		}
		
	} else { // om inte alla filer med
		print "<div class=\"error\"><h3>Gör om, gör rätt</h3><p>Rapport-filen måste laddas upp :)</p></div>";
	}	

} else { // om inget postat form - visa form
	
?>
<form method="post" action="?<?php print Config::PARAM_NAV."=admin-import&upload=1" ?>" enctype="multipart/form-data">
	<fieldset><legend>Import av grundadata</legend>
    	<p>OBS! Inläsning börjar på rad 2 (första innehåller kolumnrubriker)</p>
    	<label>CSV-fil för <strong>Elev-rapport:</strong> <input type="file" name="rapport-elever" /></label><br />
    	<label>CSV-fil för <strong>Grupp-rapport:</strong> <input type="file" name="rapport-grupper" /></label><br />
    </fieldset>

    <input type="submit" name="upload" value="Ladda upp"/>
</form>
<h3>Förväntat format</h3>
<p><strong>På egna rader:<em>Klass;Personnummer;Efternamn;Förnamn;Ansvarig lärare*;Grupp*</em></strong></p>
<p>* Kan innehålla flera värden sperarerade med komma(,). Grupper lär alltid innehålla flera värden.</p>
<?php } ?>