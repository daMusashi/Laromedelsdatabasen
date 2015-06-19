<?php
/*
	dataklass för att tillfällen. 
	Håller alla tillfällen

	Instansera (data genereras) och spara i Config eller session för att spara prestanda
 */

require_once("class_tillfalle_occasion.php");
require_once("class_tillfalle_year.php");
require_once("class_tillfalle.php");

class Tillfalle_data {

	public $occasions = []; // genereras - en lista med alla tillfällen under ett år 
	public $years = []; // genereras - en lista med alla läsår 
	public $tillfallen = []; // genereras - en lista med alla tillfällen (alla year med resp occasion), indexerade efter tillfälle-index

	// Konstruktor
    public function __construct() {
		$this->generateProps();
    }
	
	

	private function generateProps(){
		$this->occasions = Tillfalle_occasion::generateData(); 
		$this->years = Tillfalle_year::generateData(); 
		$this->generateTillfallen(); 
	}

	

	public function generateTillfallen(){
		$index = 0;
		$this->tillfallen = [];
		foreach($this->years as $year){
			foreach($this->occasions as $occasion){
				$tillfalle = new Tillfalle();
				$tillfalle->setFromObjects($occasion, $year);
				$this->tillfallen[$index] = $tillfalle;
			}
		}
	}

	public function getIndexForId($tillfalleId){
		$index = 0;
		foreach($this->tillfallen as $idx=>$tillfalle){
			if($tillfalle->id == $tillfalleId){
				$index = $idx;
				exit;
			}
		}
		return $index;
	}

	public function getOccasionForId($occasionId){
		$index = 0;
		foreach($this->occasions as $occasion){
			if($occasion->id == $occasionId){
				return $occasion;
			}
		}
		return null;
	}

	// föeslår ett tillfalle-id baserat på aktuell tid - att användes vid ny bokning
	public function getDefaultTillefalleId(){
		// TODO
		if($selectedId == ""){
			$y = date("y");
			$m = date("m");
			if($m > 02){
				$selectedId = "20" . $y . "-" . ($y+1);
			} else {
				$selectedId = "20" . ($y-1) . "-" . $y;
			}
		}

		return "";
	}

}
?>