<div id="footer">
	<div class="page-width">Produktion: MartinuS</div>
    <?php
	if($CONFIG["outputDebug"]){
		//print "<div class=\"debug\">";	
		//print "<h2>Ajax-resultat</h2>";
		//print "<div id=\"ajax-debug\"></div>";	
		print "<h2>Session</h2>";
		print var_dump($_SESSION);
		print "</div>";
		//phpinfo();
	}
	?>
</div>