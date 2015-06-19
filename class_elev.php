<?php
	require_once("class_abstract_dataobject.php");
	
	class Elev extends Dataobject
	{
    	const TABLE = "elever"; // tabellnamn
		
		const FN_ID = "id"; // personnummer
		const FN_FORNAMN = "fornamn"; // field name
		const FN_EFTERNAMN = "efternamn"; // field name
		const FN_KLASSID = "klass_id"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 
		const FK_ID = "elev_id"; // fieldname FORIEGN key 

		const DEFAULT_ORDER_BY = self::FN_EFTERNAMN;
		
		public $id = NULL;
		public $fornamn = NULL;
		public $efternamn = NULL;
		public $klassid = NULL;
		
		public $isEmpty = true;
		
		// genererade props
		public $namn = null;

   	/*
		Statics
	*/
	
	public static function importSave($personnummer, $fnamn, $enamn, $klassid){
		
		if(!self::_rowExist(self::TABLE, self::FN_ID, $personnummer, true)){

			$dataArr[self::FN_ID] = "'" . $personnummer . "'";
			$dataArr[self::FN_FORNAMN] = "'" . $fnamn . "'";
			$dataArr[self::FN_EFTERNAMN] = "'" . $enamn . "'";
			$dataArr[self::FN_KLASSID] = "'" . $klassid . "'";

			self::_save(self::TABLE, $personnummer, $dataArr, true, false);

			return "Elev med id $personnummer IMPORTERAD";
		} else {
			return "Elev med id $personnummer finns redan. INTE importerad.";
		}
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
}