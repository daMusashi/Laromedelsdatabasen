<?php

require_once("class_html_factory.php");

if(isset($_GET[Config::PARAM_AJAX])){
	switch($_GET[Config::PARAM_AJAX]){
		case "html-bok-info":
			break;

		default:
			printAjaxError("Felaktig html-anrop (AJAX)", "[" . $_GET[Config::PARAM_AJAX]  . "]");
	}
}
?>