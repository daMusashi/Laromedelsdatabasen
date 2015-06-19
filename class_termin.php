<?php
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
    	$tillfalleText = " höstterminen ";

    	if($this->terminTyp == "vt"){ 
    		$tv = "1";
    		$tDesc = "VÅR";
    		$tillfalleText = " vårterminen ";
    	}
    	
    	$this->value = (int)$this->lasar->value.$tv;
    	$this->desc = $this->lasar->descShort.":".$tDesc;
    	$this->descLong = $this->lasar->desc." - ".$tDesc;
    	$this->hamtasDesc = "START".$tillfalleText.$this->lasar->descShort;
    	$this->lamnasDesc = "SLUT".$tillfalleText.$this->lasar->descShort;
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

    public static function _getTerminNavHTML($nav_value, $typClass, $activeTidId, $isLasar = false){

		$terminer = self::getAll();

		$lasar = [];

		$html = "<ul class=\"$typClass\">";
		foreach($terminer as $termin){
			
			$id = $termin->id;
			$desc = $termin->desc;

			if(!array_key_exists($id, $lasar)){
				if($isLasar){
					$id = $termin->lasar->id;
					$desc = $termin->lasar->desc;
				}

				$active = "";
				if($id == $activeTidId){
					$active = " class=\"active\"";
				}

				$a = "<a href=\"?".Config::PARAM_NAV."=$nav_value&".Config::PARAM_ID."=".$id."\">".$desc."</a>";
				$lasar[$id] = "<li role=\"presentation\"$active>$a</li>";
				//print "<p>$a</p>";
			}
		}

		ksort($lasar);

		foreach($lasar as $nav){
			$html .=  $nav;
		}

		$html .=  "</ul>";

		return $html;
    }

    public static function getTabsHTML($nav_value, $activeTidId){
    	return self::_getTerminNavHTML($nav_value, "nav nav-tabs", $activeTidId);
    }

    public static function _obselute_getTerminSelectHTML($nav_value, $activeTidId){
    	$html = "<div class=\"navbar-form navbar-right\">
    		<button class=\"btn dropdown-toggle\" type=\"button\" id=\"dropdownTerminer\" data-toggle=\"dropdown\" aria-expanded=\"true\">
    			Byt termin <span class=\"caret\"></span>
  			</button>
    	";
    	$html .= self::_getTerminNavHTML($nav_value, "dropdown-menu", $activeTidId, false);
    	$html .= "</div>";

    	return $html;
    }

    public static function getTerminSelectWidget($nav_value, $terminObj){
    	$html = "<button class=\"btn btn-primary\" type=\"button\" id=\"dropdownTerminer\" data-toggle=\"dropdown\" aria-expanded=\"true\">
    			<span>".$terminObj->descLong."</span>
    			<span class=\"glyphicon glyphicon-chevron-down\"></span>
  			</button>
    	";
    	$html .= self::_getTerminNavHTML($nav_value, "dropdown-menu", $terminObj->id, false);

    	return $html;
    }

    public static function getCurrentTermin(){
		$t = date_create();
		$startYear = date_format($t,"Y");
		$w = date_format($t,"W");
		$terminTyp = "ht";
		
		if($w < 26){
			$startYear--;
			$terminId = "vt";
		}
		
		return new Termin($startYear, $terminTyp);
	}

}
?>