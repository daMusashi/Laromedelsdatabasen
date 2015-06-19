<?php
	require_once("class_abstract_dataobject.php");
	require_once("class_bok.php");
	//require_once("class_bok.php");
	//
	
	/*
		BINDER-CLASS med enbart ID's som objekt får tillverkas av vid behov
		Skapa inte i objekt i klassen, då de andra klasserna använder den här hårt själva 
		och CIRKEL-LOOPAR UPPSTÅR (bok skapar prop antal via denna klass, som i sin tur skapar böcker, som via antal kör den här, som skapar böcket etc)

		Behåller dock tillfälle-objekten så länge

	 */
	
	class Bokning extends Dataobject
	{
    	const TABLE = "bokningar"; // tabellnamn
		
		const FN_ID = "id"; // field name
		const FN_KURSID = "kurs_id"; // field name
		const FN_BOKID = "bok_id"; // field name
		const FN_BOKARE = "larar_id"; // field name
		const FN_KOMMENTAR = "kommentar"; // field name
		const FN_ARKIVERAD = "arkiverad"; // field name
		const FN_DEMO = "demo"; // field name
		const FN_DATUM = "datum"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 
		const FK_ID = "boknings_id"; // fieldname FORIEGN key 
		
		public $id = NULL;
		public $kursId = NULL;
		public $bokId = NULL;
		public $inTid = NULL; // Sparat Tillfalle-id för in-tid
		public $utTid = NULL; // Sparat Tillfalle-id för ut-tid
		public $bokare = NULL;
		public $kommentar = NULL;
		public $arkiverad = NULL;
		public $datum = NULL;
		public $demo = NULL;

		public $urlView = "#"; // genereras
		public $urlVEdit = "#"; // genereras
		public $urlDelete= "#"; // genereras
		
		public $isEmpty = true;

		//public $where = "";

		private static $DEV_getAllCalls = 1;
		private static $DEV_created = 1;

   	/*
		Statics
	*/
	
	public static function _OBSLEUTE_getAll($where = NULL, $idsOnly = false, $inkluderaArkiverade = false){
		//print "<p>BOKNING getall (call ".Self::$DEV_getAllCalls.")</p>";
		
		if($inkluderaArkiverade){
			$end_where = self::FN_ARKIVERAD." = true";
		} else {
			$end_where = self::FN_ARKIVERAD." = false";
		}
		
		if(!empty($where)){
			$where = " WHERE " . $where . " AND (" . $end_where . ")";
		} else {
			//$where = " WHERE " . $default_where;
			$where = " WHERE " . $end_where;
		}
		
		$q = "SELECT * FROM ".self::TABLE." $where ORDER by ".self::FN_DATUM;
		//print "<p>$q</p>";
	
		$result = mysqli_query(Config::$DB_LINK, $q);
		if(!$result){
			debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysqli_error() , "BOKNING|getAllAsArray");
		}
		
		$list = array();
		while($fieldArray = mysqli_fetch_assoc($result)){
			$bokning = new Bokning($fieldArray);
			array_push($list, $bokning);
		}

		Self::$DEV_getAllCalls++;
		
		return $list;
	}

	public static function getAll($where = NULL, $idsOnly = false, $inkluderaArkiverade = false){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_DATUM.",".self::FN_ID, $inkluderaArkiverade);
		//$list = array(new Kurs(Self::OSPEC_ID));
		$list = [];

		while($fieldArray = mysqli_fetch_assoc($result)){

			if($idsOnly){
				$bokning = $fieldArray[Self::makeUrlId($fieldArray[Self::FN_BOKID], $fieldArray[Self::FN_KURSID])];
			} else {
				$bokning = new Bokning($fieldArray);
			}

			array_push($list, $bokning);

		}
		
		return $list;
	}

	
	public static function getForBok($bokId, $terminId = null, $forLasar = false){
		

		if(empty($terminId)){
			return self::getAll(Bok::FK_ID . " = '$bokId'");
		} else {
			$kursIdsUnderTermin = Kurs::getAllIdsForTermin($terminId, $forLasar);
			$where = Bok::FK_ID . " = '$bokId' AND (";
			foreach($kursIdsUnderTermin as $kursId){
				$where .= Kurs::FK_ID . " = '$kursId' OR ";
			}
			$where = substr($where, 0, strlen($where)-4);
			$where .= ")";
			//print "<p>$where</p>";

			return self::getAll($where);
		}
	}
	
	public static function getForKurs($kursId){
		return self::getAll(Kurs::FK_ID . " = '$kursId'");
	}
	
	public static function antal(){
		return _countRows(self::TABLE);
	}
	
	public static function helloStaticClass(){
		print "<p>Hallå! (helloStaticClass)</p>";
	}

	public static function makeUrlId($bokId, $kursId){
		return $bokId . "|". $kursId;
	}

	public static function parseUrlId($bokningsId){
		$arr = explode("|", $bokningsId);
		$id["bokId"] = $arr[0];
		$id["kursId"] = $arr[1];
		return $id;
	}

	public static function exists($bokningsId){
		if(self::_countRows(self::TABLE, Self::FN_ID . "=".$bokningsId) > 0){
			return true;
		} else {
			return false;
		}
	}

	private static function generateWhere($bokId, $kursId){
		return Self::FN_BOKID . "='".$bokId."' AND ".Self::FN_KURSID."='".$kursId."'";
	}
   
   
    // Konstruktor
    public function __construct($bokningAccFieldArray = NULL) {
		parent::__construct(true);
		if(isset($bokningAccFieldArray)){
			$this->setFromAssoc($bokningAccFieldArray);
		}
		//print "<p>BOKNING created (#".Self::$DEV_created.")</p>";
		Self::$DEV_created++;
    }
	
	public function setFromAssoc($bokningAccFieldArray = NULL){
		if($bokningAccFieldArray){
			$this->id = $bokningAccFieldArray[self::FN_ID];
			$this->kursId = $bokningAccFieldArray[self::FN_KURSID];
			$this->bokId = $bokningAccFieldArray[self::FN_BOKID];
			$this->bokare  = $bokningAccFieldArray[self::FN_BOKARE];
			$this->kommentar = $bokningAccFieldArray[self::FN_KOMMENTAR];
			$this->arkiverad = $bokningAccFieldArray[self::FN_ARKIVERAD];
			$this->datum = $bokningAccFieldArray[self::FN_DATUM];
			$this->demo = $bokningAccFieldArray[self::FN_DEMO];
			
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromId($bokningsId){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::PK_ID . " = '" . $bokningsId . "'";
		
		$result = mysqli_query(Config::$DB_LINK, $q);
	
		if(mysqli_num_rows($result) == 1){
			$this->setFromAssoc(mysqli_fetch_assoc($result));
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}
	
	private function generateProps(){
		//$this->id = Self::makeUrlId($this->bokId, $this->kursId);
		//$this->where = Self::generateWhere($this->bokId, $this->kursId);

		$this->urlView =  "?" . CONFIG::PARAM_NAV . "=bokningar-view&" . Config::PARAM_REF_ID  . "=" . $this->id;
		$this->urlEdit = "?".CONFIG::PARAM_PRIM_NAV."=bokningar-edit&".CONFIG::PARAM_REF_ID."=".$this->id;
		$this->urlDelete = "?".CONFIG::PARAM_PRIM_NAV."=bokningar-delete&".CONFIG::PARAM_REF_ID."=".$this->id;
	}
	
	/*
		Publika
	*/

	public function save(){

		if($this->isValid()){
			if($this->meExtists()){
				// update
				$q = "UPDATE " . self::TABLE . 
				" SET " . 
				self::FN_KURSID. "=" . $this->kursId . ", " .
				self::FN_BOKID . "=" . $this->bokId . ", " .
				self::FN_BOKARE . "='" . $this->bokare. "', " .
				self::FN_KOMMENTAR . "='" . $this->kommentar. "', " .
				self::FN_ARKIVERAD . "= " . $this->arkiverad. ", " . 
				self::FN_DATUM . "='" . $this->datum. "', " .
				self::FN_DEMO . "=" . $this->demo. " " .
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
				self::FN_KURSID . ", " .
				self::FN_BOKID . ", " .
				self::FN_BOKARE . ", " .
				self::FN_KOMMENTAR . ", " .
				self::FN_ARKIVERAD . ", " .
				self::FN_DATUM . ", " .
				self::FN_DEMO .
				")" .
				" VALUES (" . 
				$this->kursId . ", " .
				$this->bokId . ", " .
				"'" . $this->bokare . "', " .
				"'" . $this->kommentar . "', " .
				$this->arkiverad . ", " .
				"'" . $this->datum . "', " .
				$this->demo . ", " .
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

	public function delete(){
		if($this->isValid()){
			$q = "DELETE FROM ".Self::TABLE . "WHERE " . $this->where;
			print "RADERAR BOKNING $q";
			/*if(mysqli_query(Config::$DB_LINK, $q) == 1){
				return true;
			} else {
				return false;
			} */
			return true;
		} else {
			return false;
		}
	}

	public function isValid(){
		$valid = true;

		if(empty($this->bokId)){$valid = false;}
		if(empty($this->kursId)){$valid = false;}

		return $valid;
	}

	private function meExtists(){
		return Self::exists($this->id);
	}
}
?>