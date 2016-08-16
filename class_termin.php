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

	// nedan används för att beräkna current-termin - now närmast passerat
	const calc_start_mmdd = "08-01"; // drar tillbaka lite från 08-16'ish för att täcka slutet sommaren
	const calc_mellan_mmdd = "01-30"; // drar fram lite från 01-20'ish för att bättre träff på HT
	//const calc_slut_mmdd = "06-10";
	

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
    	$tDesc = "HÖSTTERMIN";
    	$tillfalleText = "HÖST-terminens";

    	if($this->terminTyp == "vt"){ 
    		$tv = "1";
    		$tDesc = "VÅRTERMIN";
    		$tillfalleText = "VÅR-terminens";
    	}
    	
    	$this->value = (int)$this->lasar->value.$tv;
    	$this->desc = $this->lasar->descShort.":".$tDesc;
    	$this->descLong = $this->lasar->descLong." - ".$tDesc;
    	$this->hamtasDesc = $tillfalleText." START ".$this->lasar->descShort;
    	$this->lamnasDesc = $tillfalleText." SLUT ".$this->lasar->descShort;

    }

	/*public function getCalculationDate($tillfalle = "start"){ // hämtas/ut vid start, lämnas/in vid slut
		$start = "08-16";
		$mellan = "01-20";
		$slut = "06-10";

		if($tillfalle == "start") {
			if ($this->terminTyp == "ht") {
				$yyyy = $this->lasar->startYear . "-";
			} else {
				$yyyy = $this->lasar->endYear . "-";
			}
			if ($this->terminTyp == "ht") {
				$mmdd = $start;
			} else {
				$mmdd = $mellan;
			}
		} else {
			$yyyy = $this->lasar->endYear . "-";

			if ($this->terminTyp == "ht") {
				$mmdd = $mellan;
			} else {
				$mmdd = $slut;
			}
		}

		return date_create($yyyy.$mmdd);
	}*/

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
		$m = date_format($t,"m");
		//print "<p>månad:$m</p>";
		$terminTyp = "ht";

		// växlar till VT i februari och tom juli
		if($m > 1 && $m < 8){
			$terminTyp = "vt";
		}
		if($m < 8){
			$startYear--;
		}
		/*if($m < 32){
			$startYear--;
			$terminTyp = "vt";
		}*/
		
		return new Termin($startYear, $terminTyp);
	}

	public static function getCurrentTerminId($modifier = 0){
		$termin = self::getCurrentTermin($modifier);
		
		return $termin->id;
	}

}
?>