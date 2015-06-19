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
		
		public $id = -1;
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
		public $urlEdit = "#"; // genereras
		public $urlDelete = "#"; // genereras
		
		
		public $isEmpty = true;

		//public $where = "";

		private static $DEV_getAllCalls = 1;
		private static $DEV_created = 1;

   	/*
		Statics
	*/
	
	public static function getAddUrl(){
		return "?".CONFIG::PARAM_NAV."=bokningar-add";
	}

	public static function getSaveUrl(){
		return "?".CONFIG::PARAM_NAV."=bokningar-save";
	}


	public static function getAll($where = NULL, $idsOnly = false, $inkluderaArkiverade = false){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_DATUM.",".self::FN_ID, $inkluderaArkiverade);
		//print "<p> bokning_get_all num-rows: ".mysqli_num_rows($result)."</p>";
		$list = [];

		while($fieldArray = mysqli_fetch_assoc($result)){

			if($idsOnly){
				$bokning = $fieldArray[self::FN_ID];
				//print "$bokning ";
			} else {
				$bokning = new Bokning($fieldArray);
				//print " *".$bokning->id;
			}

			if($bokning->isValid()){
				array_push($list, $bokning);
			}

		}
		
		return $list;
	}

	
	public static function getForBok($bokId, $terminId = null, $forLasar = false){
		

		if(empty($terminId)){
			return self::getAll(Bok::FK_ID . " = $bokId");
		} else {
			$kursIdsUnderTermin = Kurs::getAllIdsForTermin($terminId, $forLasar);
			$where = Bok::FK_ID . " = $bokId AND (";
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

	// kollar om den exakta posten (id't) finns
	public static function exists($id){
		if(self::_countRows(self::TABLE, self::FN_ID . " = " . $id) > 0){
			return true;
		} else {
			return false;
		}
	}

	// kollar om bokningen - komb kurs-bok - finns
	public static function bokningExists($bokId, $kursId){
		if(self::_countRows(self::TABLE, self::generateWhere($bokId, $kursId)) > 0){
			return true;
		} else {
			return false;
		}
	}

	private static function generateWhere($bokId, $kursId){
		return self::FN_BOKID . "='".$bokId."' AND ".self::FN_KURSID."='".$kursId."'";
	}
   
   
    // Konstruktor
    public function __construct($bokningAccFieldArray = NULL) {
		parent::__construct(true);
		if(isset($bokningAccFieldArray)){
			$this->setFromAssoc($bokningAccFieldArray);
		}
		//print "<p>BOKNING created (#".self::$DEV_created.")</p>";
		self::$DEV_created++;
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
			
			// fix för om en bokning gått fel och har kurs/bok-id null i db
			if($this->bokId == "null"){
				$this->bokId = null;
			}
			if($this->kursId == "null"){
				$this->kursId = null;
			}

			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromData($kursId, $bokId, $bokare, $kommentar){

		$this->kursId = $kursId;
		$this->bokId = $bokId;
		$this->bokare  = $bokare;
		$this->kommentar = $kommentar;

		$this->isEmpty = true;

		$this->generateProps();
			
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
		//$this->id = self::makeUrlId($this->bokId, $this->kursId);
		//$this->where = self::generateWhere($this->bokId, $this->kursId);

		$this->urlView =  "?" . CONFIG::PARAM_NAV . "=bokningar-view&" . Config::PARAM_REF_ID  . "=" . $this->id;
		$this->urlEdit = "?".CONFIG::PARAM_NAV."=bokningar-edit&".CONFIG::PARAM_REF_ID."=".$this->id;
		$this->urlDelete = "?".CONFIG::PARAM_NAV."=bokningar-delete&".CONFIG::PARAM_REF_ID."=".$this->id;
	}
	
	/*
		Publika
	*/

	public function save(){

		$dataArr[self::FN_KURSID] = "'" . $this->kursId . "'";
		$dataArr[self::FN_BOKID] = "'" . $this->bokId . "'";
		$dataArr[self::FN_BOKARE] = "'" . $this->bokare . "'";
		$dataArr[self::FN_KOMMENTAR] = "'" . $this->kommentar . "'";
		//$dataArr[self::FN_DATUM] = "'" . $this->datum . "'";

		if($this->arkiverad){
			$arkiverad = 1;
		} else {
			$arkiverad = 0;
		}

		$dataArr[self::FN_ARKIVERAD] = $arkiverad;

		self::_save(self::TABLE, $this->id, $dataArr, $this->isValid(), $this->meExtists());

	}

	public function delete(){
		if($this->isValid()){
			$q = "DELETE FROM ".self::TABLE . "WHERE " . $this->where;
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
		return self::exists($this->id);
	}
}
?>