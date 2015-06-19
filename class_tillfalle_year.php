<?php
/*
	Hjälpklass för att sköta läsår. Används tillsammans med class occasion i class Tillfallen för att skapa tillfällen
 */

require_once("class_tillfalle_occasion.php");

class Tillfalle_year {

	public $startYear = "";
	public $endYear = ""; // genereras
	public $id = ""; // genereras - 'startyear-endyear'
	public $desc = ""; // genereras
	public $descLong = ""; // genereras
	public $value = 0; // genereras - samma som year men som int, används för storleksjämförelser

	// Konstruktor
    public function __construct($_startYear = null) {
		if(isset($_startYear)){
			$startYear = $_startYear;
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
		$this->value = (int)$this->startYear;
	}

	public static function generateData(){
		$years = [];
		//print "<p class=\"alert alert-info\">Tillfalle_year::generateData()</p>";

		for($i = Config::TILLFALLEN_START_YEAR; $i < Config::TILLFALLEN_END_YEAR; $i++){
			//print "$i <br>";
			$y = new Tillfalle_year($i . "");
			$years[$y->id] = $y;
		}

		return $years;
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

	public static function getCurrentLasarStartId(){
		$startOcc = Tillfalle_occasion::generateStart();

		return Self::getCurrentLasarId().":".$startOcc->id;
	}

}
?>