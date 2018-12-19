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

			self::_save(self::TABLE, "HACK_BEHÖVS_EJ_HÄR", $dataArr, true, false);

			return "Klass med id $id IMPORTERAD";
		} else {
			throw new Exception("INGEN import skedde. Klassen finns redan");
			return false;
		}
	}

	public static function getAll($where = NULL){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::DEFAULT_ORDER_BY, false);

		$list = array();
		while($fieldArray = mysqli_fetch_assoc($result)){
			$klass = new Klass();
			$klass->setFromAssoc($fieldArray);
			array_push($list, $klass);
		}
		
		return $list;
	}

	public static function getAllAsSelectAssoc($where = NULL){
		
		$list = [];
		foreach(self::getAll($where) as $klass){
			$list[$klass->id. " (".Klass::getAntalElever($klass->id).")"] = $klass->id;
		}
		
		return $list;
	}


	public static function getAntalElever($klassId){
		return count(Klass::getElever($klassId));
	}

	public static function getElever($klassId){
		$elever = Elev::getAll(Elev::FN_KLASSID . " = '" . $klassId . "'");

		return $elever;
	}

	public function setFromAssoc($fieldArray = NULL){
		if($fieldArray){
			$this->id = $fieldArray[self::FN_ID];

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