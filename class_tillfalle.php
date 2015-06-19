<?php
	require_once("class_tillfalle_occasion.php");
	require_once("class_tillfalle_year.php");



/*
	Lagrar och hanterar ett utlångings- ELLER återlämnnings-tillfälle
	id-egenskapen är datan som som lagras i db, classen gör datan användabar
	Om det är ett in- eller ut-tillfälle avgörs av i vilket fält id (som motsvarar ett tillfälle) sparats (i bokningstabellen)
 */
class Tillfalle 
{
	
	public $id = ""; // genereras year-occasion, sparas i db
	public $index = -1; // genereras index i alla tillfallen, används för sortering och filtrering
	public $occasion = NULL; // objekt med Occasion (Tillfalle_occasion)
	public $year = NULL; // objekt med year (Tillfalle_year)
	public $desc = ""; // genereras - beskriving för utskrifter


	public static function makeId($_occasionId, $_yearId){
		return $_yearId . ":" . $_occasionId;
	}
	
	public static function parseId($tillfalleId){
		$arr = explode(":", $tillfalleId);
		$id["year"] = $arr[0];
		$id["occasion"] = $arr[1];
		return $id;
	}
   
    // Konstruktor
    public function __construct($tillfalleId = null) {
		if(isset($tillfalleId)){
			$id = Self::parseId($tillfalleId);
	    	$this->makeObjects($id["occasion"], $id["year"]);
	    	$this->generateProps();
    	}
    }

    public function setFromPartIds($_yearId, $_occasionId){
    	$this->makeObjects($_occasionId, $_yearId);
	    $this->generateProps();
    }

    // MÅSTE användas från Tillfalle_data för inte skapa cirkel-kod
    public function setFromObjects($_occasionObj, $_yearObj){
    	$this->occasion = $_occasionObj;
		$this->year = $_yearObj;
    }
	
	

	private function generateProps(){
		$this->id = Self::makeId($this->occasion->id, $this->year->id);
		$this->desc = $this->year->desc . ": <strong>" .$this->occasion->desc . "</strong>";
		$this->index = CONFIG::$TILLFALLEN_DATA->getIndexForId($this->id);
	}

	private function makeObjects($_occasionId, $_yearId){
		// vid sekvens-start körs också detta för att skapa datan till CONFIG::$TILLFALLEN_DATA - och den finns inte då, SKAPA FRÅN TILLFALLE_DATTA MED SETFROM OBJECTS ISTÄLLET
		$this->occasion = CONFIG::$TILLFALLEN_DATA->getOccasionForId($_occasionId);
		
		$year = new Tillfalle_year();
		$year->setFromId($_yearId);
		$this->year = $year;
	}

	
}

?>
