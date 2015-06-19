<?php
	require_once("class_tillfalle_year.php");

	/*
	Lagrar och haterar en period, en termin, med ett start-tillfälle till slut-tillfälle
 */
Class Period {

	public $id = "";
	public $lasar = null; // Tillfalle-objekt
	

	// Konstruktor
    public function __construct($lasarStart, $termin) { //lasarStart = första året i läsåret, termin = vt/ht
		$this->lasar= new Tillfalle_year($lasarStart);
		$this->start->setFromId($startId);
		$this->$end = new Tillfalle();
		$this->$end->setFromId($endId);
		//$this->$allTillfallen = Tillfalle::getTillfallen();
    }

    public function inPeriod($tillfalleId){
    	if(($tillfalleId >= $this->start->id)&&($tillfalleId <= $this->end->id)){
    		return true;
    	} else {
    		return false;
    	}
    }
}
?>