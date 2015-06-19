<?php
	require_once("class_abstract_dataobject.php");
	require_once("class_bok.php");
	require_once("class_larare.php");
	require_once("class_termin.php");
	//require_once("class_tillfalle.php");
	
	class Kurs extends Dataobject
	{
    	const TABLE = "kurser"; // tabellnamn
		const TABLE_BOKNINGAR = "bokningar"; // tabellnamn
		const TABLE_KURS_BOCKER = "kurser_bocker"; // tabellnamn
		const TABLE_KURS_LARARE = "kurser_larare";
		
		const FN_ID = "id"; // field name
		const FN_NAMN = "namn"; // field name
		const FN_ARKIVERAD = "arkiverad"; // field name
		const FN_STARTTERMIN = "starttermin"; // field name
		const FN_SLUTTERMIN = "sluttermin"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 
		const FK_ID = "kurs_id"; // fieldname FORIEGN key 

		const DEFAULT_SORT_BY = self::FN_NAMN;

		public $id = NULL;
		public $namn = "<ej namngiven>";
		public $startTermin_id = ""; 
		public $slutTermin_id = ""; 
		public $arkiverad = false;

		public $startTermin = null; // genereras - Tillfalle-objekt av ett $startTermin_id (oftast från läst från DB, lagrat i FN_STARTTERMIN)
		public $slutTermin = null; // genereras - Tillfalle-objekt av ett $slutTermin_id (oftast från läst från DB, lagrat i FN_SLUTTERMIN)
				
		public $isEmpty = true;
		
		// genererade props
		//public $antalElever = 0;  !! kör on demand istället!
		public $urlView = "";
		//public $larare = []; !! kör on demand istället!
		//public $bocker = []; !! kör on demand istället!

   	/*
		Statics
	*/
	
	public static function getAll($where = NULL, $idsOnly = false, $inkluderaArkiverade = false){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_STARTTERMIN.",".self::FN_ID, $inkluderaArkiverade);
		//$list = array(new Kurs(Self::OSPEC_ID));
		$list = [];

		while($fieldArray = mysqli_fetch_assoc($result)){

	
			if($idsOnly){
				$kurs = $fieldArray[Self::FN_ID];
			} else {
				$kurs = new Kurs();
				$kurs->setFromAssoc($fieldArray);
			}

			array_push($list, $kurs);

		}
		
		return $list;
	}

	public static function _obselute_getAllForLasar($terminId){
		$termin = new Termin();
		$termin->setFromId($terminId);

		$all = Self::getAll();

		$list = [];

		foreach($all as $kurs){
			//print "<p>";
			//print "start:". $kurs->startTid->year->value . ", year:". $year->value. ", slut:" .$kurs->slutTid->year->value;
			if(($kurs->startTermin->lasar->value <= $termin->lasar->value)&&($kurs->slutTermin->lasar->value >= $termin->lasar->value)){
				array_push($list, $kurs);
				//print " MATCH!!!";
			}
			//print "</p>";
		}

		return $list;
	}

	public static function _getAllForTermin($terminId, $forLasar = false, $onlyIds = true){
		$termin = new Termin();
		$termin->setFromId($terminId);

		$all = Self::getAll();

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
			//print "<p>";
			//print "start:". $kurs->startTid->year->value . ", year:". $year->value. ", slut:" .$kurs->slutTid->year->value;
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
		return Self::_getAllForTermin($terminId, $forLasar, false);
	}

	public static function getAllIdsForTermin($terminId, $forLasar = false){
		return Self::_getAllForTermin($terminId, $forLasar, true);
	}

	public static function getAllAsSelectAssoc($where = NULL, $inkluderaArkiverade = false){
		
		$list = [];
		//$list[Self::OSPEC_ID] = Self::OSPEC_DESC;
		foreach(Self::getAll($where) as $kurs){
			$list[$kurs->id] = $kurs->id;
		}
		
		return $list;
	}

	/*public static function getKurserWithBokningarAsSelectAssoc($where = ""){
		$q="SELECT DISTINCT kurser.id as kursid FROM kurser_bocker ";
		$q=$q . "JOIN kurser ON kurser_bocker.kurs_id = kurser.id ";
		$q=$q . "JOIN kurser_elever ON kurser.id = kurser_elever.kurs_id ";
		$q=$q . "JOIN elever ON kurser_elever.elev_id = elever.id ";
		$q=$q . "JOIN klasser ON klass_id = klasser.id ";
		if($where != ""){
			$q=$q . "WHERE $where ";
		}
		$q=$q . "ORDER BY kursid";
		
		$result = mysql_query($q);
		
		if(!$result){
			debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error(), "db_functions|getKlasserAsArray");
		}
		$result = mysql_query($q);
		//print "<p>".mysql_num_rows($result)."</p>";
		$kurser = array();
		while($kurs = mysql_fetch_assoc($result)){
			//print "<p>".$klass["klassid"]."</p>";
			$kurser[$kurs["kursid"]] =  $kurs["kursid"];
		}
		
		return $kurser;
	}*/

	public static function antal(){
		return _countRows(self::TABLE);
	}

	public static function getBocker($kursId){
		$q = "SELECT * FROM " . self::TABLE_BOKNINGAR . 
			" JOIN " . Bok::TABLE . 
			" ON " . self::TABLE_BOKNINGAR.".".Bok::FK_ID . " = " . Bok::TABLE . "." . Bok::PK_ID . 
			" WHERE " . self::TABLE_BOKNINGAR . "." . self::FK_ID . " =  '" . $kursId . "'" .
			" ORDER BY " . Bok::TABLE . "." . Bok::DEFAULT_ORDER_BY ;
		//print "<p>$q</p>";
		$result = mysqli_query(Config::$DB_LINK, $q);
		if($result === false){
			debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysqli_error(Config::$DB_LINK) , "Kurs->getBocker()");
		}
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
		
		if(!$result){
			debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysqli_error(Config::$DB_LINK) , "db_functions|getLarareForKursAsArray");
		}
		
		$lararList= array();
		while($larareAssoc = mysqli_fetch_assoc($result)){

			$larare = new Larare();
			$larare->setFromAssoc($larareAssoc);
			array_push($lararList, $larare);
		}
		
		return $lararList;
	}


	public static function getAntalElever($kursId){
		$q = "SELECT * FROM " . self::TABLE_BOKNINGAR . " WHERE " . self::FK_ID . " = '" . $kursId . "'"; 
		//debugLog(" q:$q" , "db_functions|antalEleverIKurs");
		$result = mysqli_query(Config::$DB_LINK, $q);
		//debugLog(" num_rows:" . mysql_num_rows($result) , "db_functions|antalEleverIKurs");
		if(empty($result)){
			debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysqli_error(Config::$DB_LINK) , "KURS|antalEleverIKurs");
		}
		//print "<p>Antal".mysql_num_rows($result)."</p>";
		return mysqli_num_rows($result);
	}
	
	

	/*
	Public
	 */
	
	public function setFromId($kursID){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::PK_ID . " = '" . $kursID . "'";
		
		$result = mysqli_query(Config::$DB_LINK, $q);
	
		if(mysqli_num_rows($result) == 1){
			$this->setFromAssoc(mysqli_fetch_assoc($result));
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromName($kursName){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::FN_NAMN . " = '" . $kursName . "'";
		
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
			$this->namn = $kursAccFieldArray[self::FN_NAMN];
			$this->arkiverad = $kursAccFieldArray[self::FN_ARKIVERAD];
			$this->startTermin_id = $kursAccFieldArray[self::FN_STARTTERMIN];
			$this->slutTermin_id = $kursAccFieldArray[self::FN_SLUTTERMIN];
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromDataToSave($kursName, $startTerminId, $slutTerminId){
		$this->id = -1;
		$this->namn = $kursName;
		$this->arkiverad = false;
		$this->startTermin_id = $startTerminId;
		$this->slutTermin_id = $slutTerminId;
		
		$this->generateProps();
		
		$this->isEmpty = false;

	}

	public function save(){

		if($this->isValid()){
			if($this->arkiverad){
				$arkiverad = 1;
			} else {
				$arkiverad = 0;
			}
			if($this->meExtists()){
				// update
				$q = "UPDATE " . self::TABLE . 
				" SET " . 
				self::FN_NAMN. "='" . $this->namn . "', " .
				self::FN_STARTTERMIN . "='" . $this->startTermin_id . "', " .
				self::FN_SLUTTERMIN . "='" . $this->slutTermin_id . "', " .
				self::FN_ARKIVERAD . "= " . $arkiverad . 
				"WHERE " . self::FN_ID . "=" . $this->id;

				$ret = mysqli_query(Config::$DB_LINK, $q);
				if ($ret === false){
					throw new Exception("Något gick vid fel vid <strong>uppdatering</strong>.
						<br>Query: $q
						<br>DB Error: ".mysqli_connect_error(Config::$DB_LINK));
				}
			} else {
				// add
				$q = "INSERT INTO " . self::TABLE . 
				" (" . 
				self::FN_NAMN . ", " .
				self::FN_STARTTERMIN . ", " .
				self::FN_SLUTTERMIN . ", " .
				self::FN_ARKIVERAD .
				")" .
				" VALUES (" . 
				"'" . $this->namn . "', " .
				"'" . $this->startTermin_id . "', " .
				"'" . $this->slutTermin_id . "', " .
				$arkiverad . 
				")";

				$ret = mysqli_query(Config::$DB_LINK, $q);
				if ($ret === false){
					throw new Exception("Något gick vid fel vid <strong>skapande av post</strong>.
						<br>Query: $q
						<br>DB Error: ".mysqli_connect_error(Config::$DB_LINK));
				}
			}
		} else {
			throw new Exception("Informationsobjektet är inte komplett för sparande");
		}

	}

	public function isValid(){
		$valid = true;

		if(empty($this->namn)){$valid = false;}
		if(empty($this->startTermin_id)){$valid = false;}
		if(empty($this->slutTermin_id)){$valid = false;}

		return $valid;
	}

	private function meExtists(){
		return Self::exists($this->id);
	}

	public static function exists($kursId){
		if(self::_countRows(self::TABLE, Self::FN_ID . "=".$kursId) > 0){
			return true;
		} else {
			return false;
		}
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

		$this->urlView = "?".CONFIG::PARAM_PRIM_NAV."=bokningar&".CONFIG::PARAM_SEC_NAV."=kurs&".CONFIG::PARAM_REF_ID."=".$this->id;
	}
	
	/*
		Privates
	*/
	
	
}
?>