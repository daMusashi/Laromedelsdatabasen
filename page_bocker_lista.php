<?php
	require_once("class_bok.php");
	require_once("class_termin.php");
	require_once("page_functions.php");
	require_once("page_functions_navs.php");

	$activeTermin = new Termin();
	$activeTermin->setFromId($_SESSION["bok-termin"]);
?>

<div class="page-header">
	<h1>Läromedel <button id="main-help" type="button" class="btn btn-primary btn-xs" aria-label="Left Align">
  <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Hjälp
</button></h1>
</div>


<?php


	/* if(isset($_GET[Config::PARAM_ID])){
		$activeTermin = new Termin();
		$activeTermin->setFromId($_GET[Config::PARAM_ID]);
	} else {
		$activeTermin = Termin::getCurrentTermin();
	}*/

	print "<nav class=\"navbar\" id=\"nav-bok\" role=\"navigation\">";
	if(isAdmin()){
		print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bocker-add", "Lägg till ett läromedel", "success", "Lägg till ett nytt läromedel till databasen", "bottom");
										
	}

	print "<div class=\"navbar-form navbar-right\">";
		print getTerminSelectWidget("bocker-termin-select", "bocker", $activeTermin, true, "get-bocker-pagelist-termin", "ajax-list-container");
	print "</div>";
	print "<p class=\"navbar-text navbar-right\">Visar tillgänglighet för terminen</p>";
	
	print "</nav>";

	print getBockerAjaxCharTab("get-bocker-pagelist-urval", "ajax-list-container");
?>
<div id="ajax-list-container" class="well">

</div>

<?php include "include_delete_modal.php" ?>

<script type="text/javascript">
	function deleteMe(url, namn){
		$('#validate-delete-modal-body').html("Bekräfta att du vill radera läromedlet <strong>"+namn+"</strong>");
		$('#validate-delete-modal-confirm').click(function(e){
			e.preventDefault();
			window.location.href = url;
		});
		$('#validate-delete-modal').modal();
	}
	$(document).ready(function(){
		$('#ajax-list-container').html('<?php print Config::LOADING_HTML; ?>');
		$.get('ajax.php?<?php print Config::PARAM_AJAX; ?>=get-bocker-pagelist-urval&<?php print Config::PARAM_ID; ?>=<?php print $_SESSION["bok-urval"]; ?>', function(data){
			$('#ajax-list-container').html(data);
		});
	});
</script>

<?php
	include("include_help_text_bocker.php");
	include("include_help_modals.php");
?>