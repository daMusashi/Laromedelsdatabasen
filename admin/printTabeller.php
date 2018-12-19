<?php

	// TÄNKT ATT KÖRAS FRÅN DEVTOOLS:PHP

?>
<?php global $CONFIG ?>
<h1>Tabeller</h1>
<script language="javascript" type="text/javascript">
<?php $baseUrl = "?".$CONFIG["primNavParam"]."=dev&".$CONFIG["secNavParam"]."=tabeller"; ?>
function axGetTable(selectObj){
	tableName = selectObj.options[selectObj.selectedIndex].value;
	$.get("ajax_tabeller.php?id="+tableName, function(data) {
  		$("#output-tabeller" ).html(data);
	});
}

</script>
<?php
$tables = getTableNamesAsArr();
createAjaxSelect("Välj tabell...", $tables, "axGetTable(this);", "table-select");

?>

<div id="output-tabeller">
Ingen tabell vald...
</div>