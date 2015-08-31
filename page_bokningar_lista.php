<?php 
	require_once("class_termin.php");
	require_once("class_lasar.php");
	require_once("page_functions_navs.php");

?>


<div class="page-header">
	<h1>Bokningar <button id="main-help" type="button" class="btn btn-primary btn-xs" aria-label="Left Align">
  <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Hjälp
</button></h1>
</div>

<?php



$activeTermin = new Termin();
$activeTermin->setFromId($_SESSION["active-termin"]);

//print Termin::getTabsHTML("bokningar", $activeTermin->id);


//$output = Bokning::getListHTML($activeTermin);

HTML_FACTORY::printInfoAlert("OBS", "Bara biblioteket kan <strong>ta bort</strong> eller <strong>redigera</strong> en bokning, Detta för förhindra misstag. Eposta biblioteket eller martin.nilsson@karlstad.se om en bokning behöver ändras (ange bok och kurs)");

print "<nav class=\"navbar\" id=\"nav-bokning\" role=\"navigation\">";
if(isLoggedIn()){
	print HTML_FACTORY::getKnappHTML("?".Config::PARAM_NAV."=bokningar-add", "Gör en bokning", "lg", "success", "Skapa en bokning");
}
print "<div class=\"navbar-form navbar-right\">";
	print getBokareNavHTML("bokningar-bokare-select", "get-bokningar-bokarelist", "ajax-list-container");
print "</div>";
print "<p class=\"navbar-text navbar-right\">Visar bokningar gjorda av:</p>";

print "</nav>";

//print getTabsHTML("bokningar", $activeTermin->lasar->id, true);
print getTabsAjaxHTML("bokningar-termniner-tab", "bokningar", $activeTermin->id, "get-bokningar-pagelist", "ajax-list-container", true);
//print getTabsAjaxHTML($htmlId, $nav_value, $activeTidId, $ajaxNav, $ajaxTarget, $useLasar = true)

?>

<div id="ajax-list-container" class="well"></div>

<?php include "include_delete_modal.php" ?>

<script>
	function deleteMe(url, namn){
		$('#validate-delete-modal-body').html("Bekräfta att du vill radera bokningen <strong>"+namn+"</strong>");
		$('#validate-delete-modal-confirm').click(function(e){
			e.preventDefault();
			window.location.href = url;
		});
		$('#validate-delete-modal').modal();
	}
	$(document).ready(function(){
		$('#ajax-list-container').html('<?php print Config::LOADING_HTML; ?>');
		$.get('ajax.php?<?php print Config::PARAM_AJAX; ?>=get-bokningar-pagelist&<?php print Config::PARAM_ID; ?>=<?php print $_SESSION["active-termin"]; ?>', function(data){
			$('#ajax-list-container').html(data);
		});
	});
</script>

<?php
	include("include_help_text_bokningar.php");
	include("include_help_modals.php");
?>