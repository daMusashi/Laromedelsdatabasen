<?php
/*
	Hjälpklass för att sköta läsår. Används tillsammans med class occasion i class Tillfallen för att skapa tillfällen
 */

require_once("class_termin.php");

class Lasar {

	public $startYear = "";
	public $endYear = ""; // genereras
	public $id = ""; // genereras - 'startyear-endyear'
	public $desc = ""; // genereras
	public $descLong = ""; // genereras
	public $descShort = ""; // genereras
	public $value = 0; // genereras - samma som year men som int, används för storleksjämförelser

	// Konstruktor
    public function __construct($_startYear = null) {
		if(!empty($_startYear)){
			$this->startYear = $_startYear;
			$this->generateProps();
		}

    }
	
	public function setFromId($yearId){ // id-format startyear-endyear
		$arr = explode("-", $yearId);
		$this->startYear = $arr[0];
		$this->generateProps();
	}

	private function generateProps(){
		$this->endYear = $this->startYear + 1; 
		$this->id = $this->startYear . "-" . $this->endYear; 
		$this->desc = "LÅ " . substr($this->startYear, 2, 2) . "-" . substr($this->endYear, 2, 2); 
		$this->descLong = "Läsåret " . $this->id;
		$this->descShort = substr($this->startYear, 2, 2) . "-" . substr($this->endYear, 2, 2);
		$this->value = (int)$this->startYear;
	}


    public static function getTabsHTML($nav_value, $activeTidId){
    	return Termin::_getTerminNavHTML($nav_value, "nav nav-tabs", $activeTidId, true);
    }

    public function getFirstTerminId(){
    	$first = new Termin($this->startYear, "ht");
    	return $first->id;
    }


	public static function getCurrentLasarId(){
		$t = date_create();
		$yStart = date_format($t,"Y");
		$w = date_format($t,"W");
		
		if($w < 26){
			$yStart--;
		}
		$yEnd = $yStart + 1;

		return "$yStart-$yEnd";
	}

	public static function getCurrentLasar(){
		$currentLasar = new Lasar();
		$currentLasar->setFromId(Self::getCurrentLasarId());
		return $currentLasar;
	}

}
?>