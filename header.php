<?php
	require_once("datalager.php");
?>
<header class="site-header">
	<section class="container">
	<div id="page-top">
		<!-- space for fixed top menu -->
    </div>

	<nav  class="navbar navbar-default navbar-fixed-top" id="nav-main" role="navigation">
	<?php
		if(isLoggedin()) {
			$datalager = '<span id="datalagerr" class="datalager minor">(Datalagret är ' . Datalager::getTimeSinceLastUpdate() . ')</span>';
		} else {
			$datalager = "";
		}

		print "<div class=\"branding\"><div  class=\"container\">";
		print "<h1>".TEXT::TITEL." <span class=\"version minor\">v.".Config::VERSION."</span> $datalager</h1>";
		print "</div></div>";


		if(CONFIG::$DB_SUCCESS){
			include("navigering.php");
		}
	?>
	</nav>
     <!-- SLUT Navigering --> 
     </section>
</header>


</div>