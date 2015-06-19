<?php
	require_once("class_abstract_dataobject.php");
	require_once("class_bok.php");
	require_once("class_larare.php");
	require_once("class_tillfalle.php");
	
	class Kurs extends Dataobject
	{
    	const TABLE = "kurser"; // tabellnamn
		const TABLE_KURS_ELEVER = "kurser_elever"; // tabellnamn
		const TABLE_KURS_BOCKER = "kurser_bocker"; // tabellnamn
		const TABLE_KURS_LARARE = "kurser_larare";
		
		const FN_ID = "id"; // field name
		const FN_ARKIVERAD = "arkiverad"; // field name
		const FN_STARTTID = "starttid"; // field name
		const FN_SLUTTID = "sluttid"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 
		const FK_ID = "kurs_id"; // fieldname FORIEGN key 

		const DEFAULT_SORT_BY = self::FN_ID;

		const OSPEC_ID = "-ospec-"; // kursId för ospecifierad kurs
		const OSPEC_DESC = "(Ospecifierad kurs)"; // beskeivning för ospecifierad kurs
		
		public $id = NULL;
		public $isOspec = false; // om en ospecifierad kurs
		public $arkiverad = false;
		public $startTid_id = ""; // tillfalle-id för starttid
		public $slutTid_id = ""; // tillfalle-id för sluttid

		public $startTid = null; // genereras - Tillfalle-objekt av $startTid_id
		public $slutTid = null; // genereras - Tillfalle-objekt av $slutTid_id
				
		public $isEmpty = true;
		
		// genererade props
		public $antalElever = 0;
		public $urlView = "";
		public $larare = [];
		public $bocker = [];

   	/*
		Statics
	*/
	
	public static function getAll($where = NULL, $inkluderaArkiverade = false){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_STARTTID.",".self::FN_ID, $inkluderaArkiverade);
		//$list = array(new Kurs(Self::OSPEC_ID));
		$list = [];
		$index = 0;
		while($fieldArray = mysqli_fetch_assoc($result)){

			// fix för kurser utan värde i arkiverad
			if(!is_array($fieldArray)){
				// $fieldArray innehåller du bara id utan array (om inte schemat förändrats)
				//$fieldArray["id"] = $fieldArray;
				//$fieldArray["arkiverad"] = 0;

			}
			
			$kurs = new Kurs();
			
			$kurs->setFromAssoc($fieldArray);

			array_push($list, $kurs);

			$index++;
		}
		
		return $list;
	}

	public static function getAllForLasar($yearId){
		$year = new Tillfalle_year();
		$year->setFromId($yearId);

		$all = Self::getAll();

		$list = [];

		foreach($all as $kurs){
			//print "<p>";
			//print "start:". $kurs->startTid->year->value . ", year:". $year->value. ", slut:" .$kurs->slutTid->year->value;
			if(($kurs->startTid->year->value <= $year->value)&&($kurs->slutTid->year->value >= $year->value)){
				array_push($list, $kurs);
				//print " MATCH!!!";
			}
			//print "</p>";
		}

		return $list;
	}

	public static function getAllAsSelectAssoc($where = NULL, $inkluderaArkiverade = false){
		
		$list = [];
		$list[Self::OSPEC_ID] = Self::OSPEC_DESC;
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
		$q = "SELECT * FROM " . self::TABLE_KURS_BOCKER . 
			" JOIN " . Bok::TABLE . 
			" ON " . self::TABLE_KURS_BOCKER.".".Bok::FK_ID . " = " . Bok::PK_ID . 
			" WHERE " . self::TABLE_KURS_BOCKER . "." . self::FK_ID . " =  '" . $kursId . "'" .
			" ORDER BY " . Bok::TABLE . "." . Bok::DEFAULT_ORDER_BY ;
		//print "<p>$q</p>";
		$result = mysqli_query(Config::$DB_LINK, $q);
		if(!$result){
			debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysqli_error(Config::$DB_LINK) , "Kurs->getBocker()");
		}
		
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
		$q = "SELECT * FROM " . self::TABLE_KURS_ELEVER . " WHERE " . self::FK_ID . " = '" . $kursId . "'"; 
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
	
	public function setFromId($kursID = NULL){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::PK_ID . " = '" . $kursID . "'";
		
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
			$this->startTid_id = $kursAccFieldArray[self::FN_STARTTID];
			$this->slutTid_id = $kursAccFieldArray[self::FN_SLUTTID];
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
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
		if($this->id == Self::OSPEC_ID){
			$this->antalElever = 0;
			$this->bocker = $this::getBocker($this->id);
			$this->larare = [];
		} else {
			$this->antalElever = $this->getAntalElever($this->id);
			$this->bocker = $this::getBocker($this->id);
			$this->larare = $this->getLarare($this->id);
		}
		$this->startTid = new Tillfalle($this->startTid_id);
		$this->slutTid = new Tillfalle($this->slutTid_id);

		$this->urlView = "?".CONFIG::PARAM_PRIM_NAV."=bokningar&".CONFIG::PARAM_SEC_NAV."=kurs&".CONFIG::PARAM_REF_ID."=".$this->id;
	}
	
	/*
		Privates
	*/
	
	
}
?>