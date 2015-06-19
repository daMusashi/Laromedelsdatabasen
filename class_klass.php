<?php
	require_once("class_abstract_dataobject.php");
	
	class Klass extends Dataobject
	{
    	const TABLE = "klasser"; // tabellnamn
		
		const FN_ID = "id"; // personnummer

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 
		const FK_ID = "klass_id"; // fieldname FORIEGN key 

		const DEFAULT_ORDER_BY = self::FN_ID;
		
		public $id = NULL;
		
		public $isEmpty = true;

   	/*
		Statics
	*/
	
	public static function importSave($id){
		
		if(!self::_rowExist(self::TABLE, self::FN_ID, $id, true)){

			$dataArr[self::FN_ID] = "'" . $id . "'";

			self::_save(self::TABLE, $id, $dataArr, true, false);

			return "Klass med id $id IMPORTERAD";
		} else {
			return "Klass med id $id finns redan. INTE importerad.";
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
    public function __construct($klassId = NULL) {
		parent::__construct(true);
		if($klassId){
			$this->setFromAssoc($klassId);
		} else {
			$this->isEmpty = true;
		}
    }
	
	private function generateProps(){
		//
	}
}