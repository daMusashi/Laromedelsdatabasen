<?php
	require_once("class_abstract_dataobject.php");
	
	class Elev extends Dataobject
	{
    	const TABLE = "datalager_elev_bok"; // tabellnamn elev
		
		const FN_ID = "id"; // personnummer
		const FN_ELEVID= "elev_id"; // field name
		const FN_ELEVNAMN = "elev_namn"; // field name
		const FN_KLASS = "elev_klass"; // field name
		const FN_KURSID = "kurs_id"; // field name
		const FN_BOKID = "bok_id"; // field name
		const FN_IN = "in"; // field name - terminId 
		const FN_UT = "ut"; // field name - TerminId
		const FN_CREATED = "created"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 

		const DEFAULT_ORDER_BY = self::FN_CREATED;
		
		public $id = NULL;
		public $elevid = NULL;
		public $elevnamn = NULL;
		public $klass = NULL;  
		public $bokid = NULL;
		public $kursid = NULL;
		
		public $isEmpty = true;
		
		// genererade props
		public $boktitel = NULL; // genereras

   	/*
		Statics
	*/
	
	public static function createData($elevId, $elevNamn, $elevKlass, $bokId, $kursId, $inTerminId, $utTerminut){
		
		if(!self::dataExists($elevId, $bokId)){

			$dataArr[self::FN_ELEVID] = "'" . $elevId . "'";
			$dataArr[self::FN_ELEVNAMN] = "'" . $elevNamn . "'";
			$dataArr[self::FN_KLASS] = "'" . $elevKlass . "'";
			$dataArr[self::FN_KURSID] = "'" . $kursId . "'";
			$dataArr[self::FN_BOKID] = $bokId;
			$dataArr[self::FN_IN] = "'" . $inTerminId . "'";
			$dataArr[self::FN_UT] = "'" . $utTerminut . "'";

			self::_save(self::TABLE, $personnummer, $dataArr, true, false);

			return "Datalager för Elev med id $personnummer SKAPAD";
		} else {
			return "Datalager för Elev med id $personnummer finns redan. INTE skapad.";
		}

		// TODO Add bok om ny bok - eller INTE - gör kollen i datalager-klass
	}

	public static function deleteElevData($elevId){
		Self::_delete(self::FN_ELEVID." = '" . $elevId. "'");
	}

	public static function empty(){
		Self::_delete(self::FN_ELEVID." = '" . $elevId. "'");
	}

	public static function getAll($where = NULL){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::DEFAULT_ORDER_BY, true);

		$list = array();
		while($fieldArray = mysqli_fetch_assoc($result)){
			$larare = new Larare();
			$larare->setFromAssoc($fieldArray);
			array_push($list, $larare);
		}
		
		return $list;
	}

	public static function getAllAsSelectAssoc($where = NULL){
		
		$list = [];
		foreach(self::getAll($where) as $larare){
			$list[$larare->id] = $larare->namn;
		}
		
		return $list;
	}

	public function setFromId($ElevId){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::PK_ID . " = '" . $ElevId . "'";
		
		$result = mysqli_query(Config::$DB_LINK, $q);
	
		if(mysqli_num_rows($result) == 1){
			$this->setFromAssoc(mysqli_fetch_assoc($result));
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}


	public function setFromAssoc($elevAccFieldArray = NULL){
		if($elevAccFieldArray){
			$this->id = $larareAccFieldArray[self::FN_ID];
			$this->fornamn = $larareAccFieldArray[self::FN_FORNAMN];
			$this->efternamn = $larareAccFieldArray[self::FN_EFTERNAMN];
			$this->klass_id = $larareAccFieldArray[self::FN_KLASSID];
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}
	
   
    // KOnstruktor
    public function __construct($elevId = NULL) {
		parent::__construct(true);
		if($elevId){
			$this->setFromAssoc($elevId);
		} else {
			$this->isEmpty = true;
		}
    }
	
	private function generateProps(){
		$fn = "";
		$en = "";
		if(strlen($this->fornamn ) > 0){
			$fn = $this->fornamn . " ";
		}
		if(strlen($this->efternamn ) > 0){
			$en = $this->efternamn;
		}
		$namn = $fn.$en;
		if(empty($namn)){
			$this->namn = $this->id;
		} else {
			$this->namn = $fn . $en;
		}
	}

	public static function dataExists($elevId, $bokId){
		if(Self::_countRows(Self::TABLE, Self::FN_ELEVID." = '$elevId' AND ".Self::FN_BOKID." = $bokId") > 0){
			return true;
		} else {
			return false;
		}
	}
}