<?php
require_once("reports_functions.php");



if(isset($_GET[$CONFIG["primNavParam"]])){
	switch($_GET[$CONFIG["primNavParam"]]){
		case "kurs":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				printBockerForKurs($_GET[$CONFIG["refIdParam"]]);
			}
			break;
		case "klass":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				printBockerForKlass($_GET[$CONFIG["refIdParam"]]);
			}
			break;
		case "klass-elever":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				printBockerForEleverIKlass($_GET[$CONFIG["refIdParam"]]);
			}
			break;
		case "elev":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				printBockerForElev($_GET[$CONFIG["refIdParam"]]);
			}
			break;
		case "elev-select":
			if(isset($_GET[$CONFIG["refIdParam"]])){
				print "<div id=\"print-data-alla-klasselever\" class=\"invisible\">";
				printBockerForEleverIKlass($_GET[$CONFIG["refIdParam"]]);
				print "</div>";
				print getSkrivutKnapp("print-data-alla-klasselever", "Skriv ut alla elever", "Skriv ut litteraturlistor för alla elever i klassen", "big", "green");
				print getSelectElevFromKlassHTML($_GET[$CONFIG["refIdParam"]], "Välj en enstaka elev."); // ($klassId, $fieldDescription = "", $selectedId = "", $elementId = "")
			}
			break;
	}
}
?>
