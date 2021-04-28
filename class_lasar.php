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
		$this->desc = "LÅ " . substr($this->startYear, 2, 2) . "/" . substr($this->endYear, 2, 2);
		$this->descLong = "Läsåret " . $this->startYear . "/" . substr($this->endYear, 2, 2);;
		$this->descShort = substr($this->startYear, 2, 2) . "/" . substr($this->endYear, 2, 2);
		$this->value = (int)$this->startYear;
	}

    public function getFirstTermin(){
    	$termin = new Termin($this->startYear, "ht");
    	return $termin;
    }

    public function getLastTermin(){
    	$termin = new Termin($this->startYear, "vt");
    	return $termin;
    }

    public function getFirstTerminId(){
    	$termin = $this->getFirstTermin();
    	return $termin->id;
    }

    public function getLastTerminId(){
    	$termin = $this->getLastTermin();
    	return $termin->id;
    }

	public static function getLasarId($modification = 0){
		$t = date_create();
		$yStart = date_format($t,"Y");
		$yStart += $modification;
		$w = date_format($t,"W");
		
		if($w < 26){
			$yStart--;
		}
		$yEnd = $yStart + 1;

		return "$yStart-$yEnd";
	}

	public static function getCurrentLasarId($modificationFromCurrent = 0){
		$t = date_create();
		$yStart = date_format($t,"Y");
		$yStart += $modificationFromCurrent;
		$w = date_format($t,"W");
		
		if($w < 26){
			$yStart--;
		}
		$yEnd = $yStart + 1;

		return "$yStart-$yEnd";
	}

	public static function getLasar($modificationFromCurrent = 0){
		$currentLasar = new Lasar();
		$currentLasar->setFromId(self::getCurrentLasarId($modificationFromCurrent));
		return $currentLasar;
	}

	public static function getCurrentLasar(){
		return Lasar::getLasar(0);
	}

	public static function getPrevLasar(){
		return Lasar::getLasar(-1);
	}

	public static function getNextLasar(){
		return Lasar::getLasar(1);
	}

	public function getLasarAfterThis(){
		$nextLasar = new Lasar($this->startYear+1);
		return $nextLasar;
	}

}
?>