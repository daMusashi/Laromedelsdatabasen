<?php
 include_once("import_functions.php");

?>
<h1>Import av böcker</h1>

<?php
if(isset($_GET["upload"])){
if(isset($_FILES["bokfil"])){
	
	// Kolla även http://www.php.net/manual/en/features.file-upload.php
	
	importLog("<div><h2>Rapport från import</h2>");
	
	$filesSucces = true;
	importLog("<h3>Läser in bokfil</h3>");
	if(!$importfil_bocker = getFile("bokfil")){
		$filesSucces = false;
	} else {
		importLog("<p>Bokfil inläst utan problem</p>");
	}
	
	
 	if($filesSucces){
		
		importLog("<h3>Raderar gammal import-data..</h3>");
  		importLog(EMPTY_ALL_BOK_DATA_TABLES());
		
		importLog("<h3>Behandlar och lagrar bokfilen</h3>");
		parseBocker($importfil_bocker);
		
		print "<h2>Importen lyckades</h2>";
  		print "<p>Datan i rapporterna är inläst</p>";
		print "<p><strong>Importerat:</strong></p>";
		print "<h3>böcker: ". countBocker() . " </h3>";
		printAllBockerAsList();
		
	} else {
 		print "<h3>Importen misslyckades</h3>";
  		print "<p>Uppladdning eller inläsning av bokfilen misslycka :'(</p>";
	}
  


} else { // om inte alla filer med
	print "<div class=\"error\"><h3>Gör om, gör rätt</h3><p><strong>En fil mmåste vara angiven :)</p></div>";
}

} else { // inget posta form
?>
<form method="post" action="?<?php print $CONFIG["primNavParam"]; ?>=import-bocker&upload=1" enctype="multipart/form-data">
	<label>Importfil för <strong>böcker:</strong> <input type="file" name="bokfil" /></label><br />
    <!-- <input type="file" name="importfil" accept="text/*" /> -->
    <input type="submit" name="upload" value="Ladda upp"/>
</form>
<h3>Förväntat format</h3>
<p><strong>På enskilda rader: <em>titel; undertitel; författare (efternamn, förnamn); förlag; upplaga; ISBN; pris;</em></strong></p>
<?php } ?>