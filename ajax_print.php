<?php
	require_once("db_connect.php");
	require_once("class_html_factory_print.php");

	switch($_GET[Config::PARAM_AJAX]){
		case "view-kurs":
			//var_dump($_GET);
			if((isset($_GET["id"]))&&($_GET["id"] != Config::NULL)){
				print HTML_FACTORY_PRINT::getBokTableForKurs($_GET["id"]);
			} else {
				print "";
			}
			break;
		case "view-klass":
			//var_dump($_GET);
			print HTML_FACTORY_PRINT::getBokTableForKlass($_GET["id"]);
			break;
		case "view-elev-ind":
			//var_dump($_GET);
			print HTML_FACTORY_PRINT::getBokTableForElevInd($_GET["id"]);
			break;
		case "view-elev-klass":
			//var_dump($_GET);
			print HTML_FACTORY_PRINT::getBokTableForElevklass($_GET["id"]);
			break;
		case "select-elev":
			//var_dump($_GET);
			print HTML_FACTORY_PRINT::getElevAjaxSelect($_GET["id"]);
			break;
	}

?>