<?php
	require_once("config.php");
	require_once("class_lasar.php");
	require_once("class_kurs.php");

	/*
	Lagrar och haterar en period, en termin, med ett start-tillfälle till slut-tillfälle
 */
Class Termin {

	public $id = ""; // genereras
	public $lasar = null; // Lasar-objekt
	public $terminTyp = ""; // vt/ht
	public $desc = ""; // genereras
	public $descLong = ""; // genereras
	public $value = 0; // genereras - samma som year men som int, används för storleksjämförelser
	public $useAsLasar = false; // AFör att markera om bara läsårets ska användas när passas till funktioner
	public $terminTypDesc = ""; // genereras

	public $hamtasDesc = ""; // genereras - text för lämnas ut-tillfälle
	public $lamnasDesc = ""; // genereras - text för lämnas in-tillfälle
	

	// Konstruktor
    public function __construct($lasarStart = null, $terminTyp = null) { //lasarStart = första året i läsåret, $terminTyp = vt/ht
		if(!empty($lasarStart)&&!empty($terminTyp)){
			$this->lasar = new Lasar($lasarStart);
			$this->terminTyp = $terminTyp;
			$this->generateProps();
		}
    }

    public static function makeId($lasarId, $terminTyp){
		return $lasarId.":".$terminTyp;
	}
	
	public static function parseId($terminId){
		$arr = explode(":", $terminId);
		$id["lasar"] = $arr[0];
		$id["termintyp"] = $arr[1];
		return $id;
	}

    public function setFromId($terminId){
    	$ids = self::parseId($terminId);
    	$lasar = new Lasar();
    	$lasar->setFromId($ids["lasar"]);

    	$this->lasar = $lasar;
    	$this->terminTyp = $ids["termintyp"];

    	$this->generateProps();
    }

    private function generateProps(){
    	$this->id = $this->lasar->id.":".$this->terminTyp;

    	$tv = "0";
    	$tDesc = "HÖST";
    	$tillfalleText = "HÖST-terminens";

    	if($this->terminTyp == "vt"){ 
    		$tv = "1";
    		$tDesc = "VÅR";
    		$tillfalleText = "VÅR-terminens";
    	}
    	
    	$this->value = (int)$this->lasar->value.$tv;
    	$this->desc = $this->lasar->descShort.":".$tDesc;
    	$this->descLong = $this->lasar->descLong." - ".$tDesc;
    	$this->hamtasDesc = $tillfalleText." START ".$this->lasar->descShort;
    	$this->lamnasDesc = $tillfalleText." SLUT ".$this->lasar->descShort;
    }

    public function getNextTermin(){
    	if($this->terminTyp == "vt"){
    		$nextLasar = $this->lasar->getLasarAfterThis();
    		return $nextLasar->getFirstTermin();
    	} else {
    		return $this->lasar->getLastTermin();
    	}
    }

    public function isInCurrentLasar(){
    	$currentLasar = Lasar::getCurrentLasar();
    	if($this->lasar->value == $currentLasar->value){
    		return true;
    	} else {
    		return false;
    	}
    }

    public static function getAll(){
    	$kurser = Kurs::getAll();

		$terminer = [];
		foreach($kurser as $kurs){
			
			if (!array_key_exists($kurs->startTermin->id, $terminer)){
				
				$terminer[$kurs->startTermin->id] = $kurs->startTermin;
			}
			if (!array_key_exists($kurs->slutTermin->id, $terminer)){
				
				$terminer[$kurs->slutTermin->id] = $kurs->slutTermin;
			}
		}

		return $terminer;
    }

    public static function getCurrentTermin($modifier = 0){
		$t = date_create();
		$startYear = date_format($t,"Y");
		$w = date_format($t,"W");
		$terminTyp = "ht";
		
		if($w < 32){
			$startYear--;
			$terminTyp = "vt";
		}
		
		return new Termin($startYear, $terminTyp);
	}

	public static function getCurrentTerminId($modifier = 0){
		$termin = getCurrentTermin($modifier);
		
		return $termin->id;
	}

}
?>