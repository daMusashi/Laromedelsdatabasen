<?php

//require_once("class_lasar.php");


// arkiverar kurser och bokningar från förgående läsår och äldre
function arkivera_allt_older(){

	$kurser = Kurs::getAll();

	print "<h3>Arkivera kurser</h3>";
	
	foreach($kurser as $kurs){
		
		if($kurs->isOld()){
			print "<p><strong>Akriverar ".$kurs->id."</strong></p>";
			
			$bokningar = Bokning::getForKurs($kurs->id);

			if(count($bokningar) > 0){
				print "<ul>";
				foreach($bokningar as $bokning){
					$bokning->arkivera();
					print "<li>arkiverat bokning ".$bokning->id."</li>";
				}
				print "</ul>";
			} else {
				print "<p><em>Kursen har inga bokningar</em></p>";
			}

			if($kurs->arkiveraIfOld()){
				print "<p>Kursen <strong>akriverad!</strong></p>";
			} else {
				print "<p>Kursen <strong>inte</strong> arkiverad</p>";
			}
		}
	}
}




?>