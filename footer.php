<footer>
	<div class="container">
		<div class="bs-component">
			<?php if(Config::DEBUG){ ?>
				<div id="ajax-debug"></div>
				<p>DEBUG: session_activeTermin [<?php print $_SESSION["active-termin"]?>] | session-bokningBokare[<?php print $_SESSION["bokning-bokare"] ?>]</p>
			<?php } ?>
			<p>Produktion: <a href="https://twitter.com/gosuMartin" target="_blank">Martin Nilsson</a> - vid problem eposta <strong>martin.nilsson@karlstad.se</strong> eller ring 0704- 54 64 23 (privat)</p>
			<p>Teknik: HTML/CSS/Javascript-framework - <a href="http://getbootstrap.com/" target="_blank">Bootstrap</a> (SASS-versionen) | Javascript-framework - <a href="https://jquery.com/" target="_blank">jQuery</a> | Stilmallsspråk - <a href="http://sass-lang.com/" target="_blank">SASS</a> | SASS-framework - <a href="http://compass-style.org/" target="_blank">Compass</a> | Webbserverspråk - <a href="http://www.php.net/" target="_blank">PHP</a> | Databashanterare - <a href="https://www.mysql.com/" target="_blank">MySQL</a></p>
		</div>

	</div>
</footer>