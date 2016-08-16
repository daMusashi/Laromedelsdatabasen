<footer>
	<div class="container">
		<div class="bs-component">
			<p>Skoldatans ålder <strong><?php print Kurs::getTimestringSinceLastUpdate() ?></strong> | Datalagrets ålder: <strong><?php print Datalager::getTimestringSinceLastUpdate() ?></strong></p>
			<p>Produktion: <a href="https://twitter.com/gosuMartin" target="_blank">Martin Nilsson</a> - vid problem eposta <strong>martin.nilsson@karlstad.se</strong> eller ring 0704- 54 64 23 (privat)</p>
			<p>Teknik: HTML/CSS/Javascript-framework - <a href="http://getbootstrap.com/" target="_blank">Bootstrap</a> (SASS-versionen) | Javascript-framework - <a href="https://jquery.com/" target="_blank">jQuery</a> | Stilmallsspråk - <a href="http://sass-lang.com/" target="_blank">SASS</a> | SASS-framework - <a href="http://compass-style.org/" target="_blank">Compass</a> | Webbserverspråk - <a href="http://www.php.net/" target="_blank">PHP</a> | Databashanterare - <a href="https://www.mysql.com/" target="_blank">MySQL</a></p>
			<?php if(Config::DEBUG){ ?>
				<div class="debug">
					<div id="ajax-debug"></div>
					<h4>Debug</h4>
					<div id="db-debug"><?php print Datalager::getDebugSqlCalls() ?></div>
					<p><?php var_dump($_SESSION); ?></p>
				</div>
			<?php } ?>
		</div>

	</div>
</footer>