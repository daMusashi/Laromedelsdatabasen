<?php
	
	require_once("class_html_factory.php");
	require_once("class_lasar.php");
	require_once("page_functions_navs.php");

?>

<div class="page-header">
	<h1>Kurser <button id="main-help" type="button" class="btn btn-primary btn-xs" aria-label="Left Align">
  <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Hjälp
</button></h1>
</div>

<div class="info-box">
<!-- nothing to see here - yet -->
</div>

<?php

/*if(isset($_GET[Config::PARAM_ID])){
	$activeLasar = new Lasar();
	$activeLasar->setFromId($_GET[Config::PARAM_ID]);
} else {
	$activeLasar = Lasar::getCurrentLasar();
}*/

$activeTermin = new Termin();
$activeTermin->setFromId($_SESSION["active-termin"]);

print getTabsAjaxHTML("kurser-lasar-tab", "kurser", $activeTermin->id, "get-kurser-pagelist", "ajax-list-container", true);


//$Selectedkurser = Kurs::getAllForTermin($activeLasar->getFirstTerminId(), true);


?>
<div id="ajax-list-container" class="well">

</div>

<?php include "include_changetermin_modal.php" ?>

<script>
$(document).ready(function(){
	$('#ajax-list-container').html('<?php print Config::LOADING_HTML; ?>');
	$.get('ajax.php?<?php print Config::PARAM_AJAX; ?>=get-kurser-pagelist&<?php print Config::PARAM_ID; ?>=<?php print $_SESSION["active-termin"]; ?>', function(data){
		$('#ajax-list-container').html(data);
	});

});
</script>



<?php
	include("include_help_text_kurser.php");
	include("include_help_modals.php");
?>