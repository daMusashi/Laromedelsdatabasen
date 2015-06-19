<?php
	require_once("class_abstract_dataobject.php");
	require_once("class_bok.php");
	require_once("class_larare.php");
	require_once("class_elev.php");
	require_once("class_termin.php");
	//require_once("class_tillfalle.php");
	
	class Kurs extends Dataobject
	{
    	const TABLE = "kurser"; // tabellnamn
		const TABLE_BOKNINGAR = "bokningar"; // tabellnamn
		const TABLE_KURS_BOCKER = "kurser_bocker"; // tabellnamn
		const TABLE_KURS_LARARE = "kurser_larare";
		const TABLE_KURS_ELEVER = "kurser_elever";
		
		const FN_ID = "id"; // field name
		const FN_ARKIVERAD = "arkiverad"; // field name
		const FN_STARTTERMIN = "starttermin"; // field name
		const FN_SLUTTERMIN = "sluttermin"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 
		const FK_ID = "kurs_id"; // fieldname FORIEGN key 

		const DEFAULT_SORT_BY = self::FN_ID;

		public $id = NULL;
		public $startTermin_id = ""; 
		public $slutTermin_id = ""; 
		public $arkiverad = false;

		public $startTermin = null; // genereras - Tillfalle-objekt av ett $startTermin_id (oftast från läst från DB, lagrat i FN_STARTTERMIN)
		public $slutTermin = null; // genereras - Tillfalle-objekt av ett $slutTermin_id (oftast från läst från DB, lagrat i FN_SLUTTERMIN)
		public $namn = ""; // genereras, samma som id om inget händer med skoldatan
				
		public $isEmpty = true;
		
		// genererade props
		//public $antalElever = 0;  !! kör on demand istället!
		public $urlView = "";
		//public $larare = []; !! kör on demand istället!
		//public $bocker = []; !! kör on demand istället!

   	/*
		Statics
	*/
	
	public static function getSelectHTML($where = "", $fieldDescription = "", $selectedId = "", $elementId = "select-kurs"){
		$kurser = self::getAll($where);
		$kurserSelectArr = [];

		foreach($kurser as $kurs){
			$kurserSelectArr[$kurs->id] = $kurs->id;
		}

		return HTML_FACTORY::getAssocArrayAsSelectHTML($kurserSelectArr, $elementId, "Välj en kurs...", "Kurs", $fieldDescription, $selectedId, "300", $elementId);
	}

	public static function getAll($where = NULL, $idsOnly = false, $inkluderaArkiverade = false){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_STARTTERMIN.",".self::FN_ID, $inkluderaArkiverade);
		//$list = array(new Kurs(self::OSPEC_ID));
		$list = [];

		while($fieldArray = mysqli_fetch_assoc($result)){

	
			if($idsOnly){
				$kurs = $fieldArray[self::FN_ID];
			} else {
				$kurs = new Kurs();
				$kurs->setFromAssoc($fieldArray);
			}

			array_push($list, $kurs);

		}
		
		return $list;
	}

	public static function _getAllForTermin($terminId, $forLasar = false, $onlyIds = true){
		$termin = new Termin();
		$termin->setFromId($terminId);

		$all = self::getAll();

		$list = [];

		foreach($all as $kurs){
			if($forLasar){
				$start = $kurs->startTermin->lasar->value;
				$slut = $kurs->slutTermin->lasar->value;
				$wanted = $termin->lasar->value;
			} else {
				$start = $kurs->startTermin->value;
				$slut = $kurs->slutTermin->value;
				$wanted = $termin->value;
			}

			//print "<p>start: $start, wanted: $wanted, slut: $slut ";
			if(($start <= $wanted)&&($slut >= $wanted)){
				if($onlyIds){
					array_push($list, $kurs->id);
					//print " MATCH!!!";
				} else {
					
					array_push($list, $kurs);
				}
			}
			//print "</p>";
		}

		return $list;
	}

	public static function getAllForTermin($terminId, $forLasar = false){
		return self::_getAllForTermin($terminId, $forLasar, false);
	}

	public static function getAllIdsForTermin($terminId, $forLasar = false){
		return self::_getAllForTermin($terminId, $forLasar, true);
	}

	public static function getAllAsSelectAssoc($where = NULL, $inkluderaArkiverade = false){
		
		$list = [];
		//$list[self::OSPEC_ID] = self::OSPEC_DESC;
		foreach(self::getAll($where) as $kurs){
			$list[$kurs->id] = $kurs->id;
		}
		
		return $list;
	}

	public static function importSave($id, $period, $lasarObj){ 

		if(!self::_rowExist(self::TABLE, self::FN_ID, $id, true)){

			$firstTermin = $lasarObj->getFirstTermin();
			$lastTermin = $lasarObj->getLastTermin();

			$startTermin = $firstTermin;
			$slutTermin = $lastTermin;

			if($period == "HT"){
				$slutTermin = $startTermin;
			}

			if($period == "VT"){
				$startTermin = $slutTermin;
			}

			$dataArr[self::FN_ID] = "'" . $id . "'";
			$dataArr[self::FN_STARTTERMIN] = "'" . $startTermin->id . "'";
			$dataArr[self::FN_SLUTTERMIN] = "'" . $slutTermin->id . "'";
			$dataArr[self::FN_ARKIVERAD] = "0";

			self::_save(self::TABLE, $id, $dataArr, true, false);

			return "Kurs med id $id IMPORTERAD";
		} else {
			return "Kurs med id $id finns redan. INTE importerad.";
		}

	}

	public static function importSaveAddElever($kursId, $elevIdArr){
		foreach($elevIdArr as $elevId){
			// ska inte behöva kolla om relation existerar då datan kommer från annan db där dubletter av detta slag inte ska kunna finnas
			if($elevId != "" && $elevId != " "){
				
				$dataArr[self::FK_ID] = "'" . $kursId . "'";
				$dataArr[Elev::FK_ID] = "'" . $elevId . "'";

				self::_save(self::TABLE_KURS_ELEVER, null, $dataArr, true, false);

				return "Elev [$elevId] KNUTEN till kurs $kursId";
			} else {
				return "Felaktigt elev-id [$elevId]. INTE knuten till kurs $kursId";
			}
		} 
	}

	public static function importSaveAddlarare($kursId, $lararId){

		// ska inte behöva kolla om relation existerar då datan kommer från annan db där dubletter av detta slag inte ska kunna finnas
		if($lararId != "" && $lararId != " "){
			
			$dataArr[self::FK_ID] = "'" . $kursId . "'";
			$dataArr[Larare::FK_ID] = "'" . $lararId . "'";

			self::_save(self::TABLE_KURS_LARARE, null, $dataArr, true, false);

			return "Larare [$lararId] KNUTEN till kurs $kursId";
		} else {
			return "Felaktigt larar-id [$lararId]. INTE knuten till kurs $kursId";
		}

	}

	

	public static function getBocker($kursId){
		$q = "SELECT * FROM " . self::TABLE_BOKNINGAR . 
			" JOIN " . Bok::TABLE . 
			" ON " . self::TABLE_BOKNINGAR.".".Bok::FK_ID . " = " . Bok::TABLE . "." . Bok::PK_ID . 
			" WHERE " . self::TABLE_BOKNINGAR . "." . self::FK_ID . " =  '" . $kursId . "'" .
			" ORDER BY " . Bok::TABLE . "." . Bok::DEFAULT_ORDER_BY ;
		//print "<p>$q</p>";
		$result = mysqli_query(Config::$DB_LINK, $q);
		self::checkError($result, $q, "kurs->getBocker");
		//print "<p>$q</p>";
		$bocker = array();
		while($bokAssoc = mysqli_fetch_assoc($result)){
			$bok = new Bok($bokAssoc);

			array_push($bocker, $bok);
		}
		
		return $bocker;
	}

	public static function getLarare($kursId){
		$q = "SELECT * FROM " . self::TABLE_KURS_LARARE . 
				" JOIN " . Larare::TABLE . 
				" ON " . self::TABLE_KURS_LARARE.".".Larare::FK_ID . " = " . Larare::PK_ID . 
				" WHERE " . self::TABLE_KURS_LARARE.".".self::FK_ID . " =  '" . $kursId . "'" . 
				" ORDER BY " . Larare::TABLE . "." . Larare::DEFAULT_ORDER_BY ;

		//print "<p>$q</p>";
		$result = mysqli_query(Config::$DB_LINK, $q);
		
		self::checkError($result, $q, "kurs->getLarare");
		
		$lararList= array();
		while($larareAssoc = mysqli_fetch_assoc($result)){

			$larare = new Larare();
			$larare->setFromAssoc($larareAssoc);
			array_push($lararList, $larare);
		}
		
		return $lararList;
	}


	public static function getAntalElever($kursId){
		$q = "SELECT * FROM " . self::TABLE_KURS_ELEVER . " WHERE " . self::FK_ID . " = '" . $kursId . "'"; 
		
		$result = mysqli_query(Config::$DB_LINK, $q);
		//print "<p>$q (".mysqli_num_rows($result).")</p>";

		//print "<p>Antal".mysql_num_rows($result)."</p>";
		return mysqli_num_rows($result);
	}
	
	

	/*
	Public
	 */
	
	public function setFromId($kursID){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::PK_ID . " = '" . $kursID . "'"; ;
		
		$result = mysqli_query(Config::$DB_LINK, $q);
	
		if(mysqli_num_rows($result) == 1){
			$this->setFromAssoc(mysqli_fetch_assoc($result));
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}


	public function setFromAssoc($kursAccFieldArray = NULL){
		
		
		if(empty($kursAccFieldArray[self::FN_ID])){
			// 
		}
		if(isset($kursAccFieldArray)){
			//$this->id = $kursAccFieldArray[self::PK_ID];
			$this->id = $kursAccFieldArray[self::FN_ID];
			$this->arkiverad = $kursAccFieldArray[self::FN_ARKIVERAD];
			$this->startTermin_id = $kursAccFieldArray[self::FN_STARTTERMIN];
			$this->slutTermin_id = $kursAccFieldArray[self::FN_SLUTTERMIN];
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromDataToSave($kursId, $startTerminId, $slutTerminId){
		$this->id = $kursId;
		$this->arkiverad = false;
		$this->startTermin_id = $startTerminId;
		$this->slutTermin_id = $slutTerminId;
		
		$this->generateProps();
		
		$this->isEmpty = false;

	}

	public function save(){

		$dataArr[self::FN_ID] = "'" . $this->id . "'";
		$dataArr[self::FN_STARTTERMIN] = "'" . $this->startTermin_id . "'";
		$dataArr[self::FN_SLUTTERMIN] = "'" . $this->slutTermin_id . "'";
		if($this->arkiverad){
			$arkiverad = 1;
		} else {
			$arkiverad = 0;
		}
		$dataArr[self::FN_ARKIVERAD] = $arkiverad;

		self::_save(self::TABLE, "'".$this->id."'", $dataArr, $this->isValid(), $this->meExtists());

	}

	public function isValid(){
		$valid = true;

		if(empty($this->id)){$valid = false;}
		if(empty($this->startTermin_id)){$valid = false;}
		if(empty($this->slutTermin_id)){$valid = false;}

		return $valid;
	}

	private function meExtists(){
		return self::_rowExist(self::TABLE, self::FN_ID, $this->id, true);
	}

  
   
    // KOnstruktor
    public function __construct($kursID = NULL) {
		parent::__construct(true);
		if(isset($kursID)){
			$this->setFromId($kursID);
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
    }
	
	private function generateProps(){
		// Gör on-demand $this->antalElever = $this->getAntalElever($this->id);
		// Gör on-demand $this->bocker = $this::getBocker($this->id);
		// gör on-demand $this->larare = $this->getLarare($this->id);

		$startTerm = new Termin();
		$startTerm->setFromId($this->startTermin_id);

		$slutTerm = new Termin();
		$slutTerm->setFromId($this->slutTermin_id);

		$this->startTermin = $startTerm;
		$this->slutTermin = $slutTerm ;
		$this->namn = $this->id;

		$this->urlView = "#";
	}
	
	/*
		Privates
	*/
	
	
}
?>