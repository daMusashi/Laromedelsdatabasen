<?php
	require_once("class_abstract_dataobject.php");
	
	class Larare extends Dataobject
	{
    	const TABLE = "larare"; // tabellnamn
		
		const FN_ID = "id"; // field name
		const FN_FORNAMN = "fornamn"; // field name
		const FN_EFTERNAMN = "efternamn"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key 
		const FK_ID = "larar_id"; // fieldname FORIEGN key 

		const DEFAULT_ORDER_BY = self::FN_ID;
		
		public $id = NULL;
		public $fornamn = NULL;
		public $efternamn = NULL;
		
		public $isEmpty = true;
		
		// genererade props
		public $namn = null;
		public $dataLink = null;

   	/*
		Statics
	*/
	
	public static function getAll($where = NULL){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_ID, true);

		$list = array();
		while($fieldArray = mysqli_fetch_assoc($result)){
			$larare = new Larare();
			$larare->setFromAssoc($fieldArray);
			array_push($list, $larare);
		}
		
		return $list;
	}

	public static function getAllAsSelectAssoc($where = NULL, $inkluderaArkiverade = false){
		
		$list = [];
		foreach(self::getAll($where) as $larare){
			if(!empty($larare->id)){
				$list[$larare->id] = $larare->namn;
			}
		}
		
		return $list;
	}

	public static function getSelectHTML($fieldDescription = "", $selectedId = "", $elementId = "select-larare"){
		$larare = self::getAll();
		$larareSelectArr = [];

		foreach($larare as $lar){
			if(!empty($lar->id)){
				$larareSelectArr[$lar->id] = $lar->namn;
			}
		}

		return HTML_FACTORY::getAssocArrayAsSelectHTML($larareSelectArr, $elementId, "Välj en lärare...", "Bokningslärare", $fieldDescription, $selectedId, "300", $elementId);
	}

	public static function importSave($id, $fnamn, $enamn){
		
		if(!self::_rowExist(self::TABLE, self::FN_ID, $id, true)){

			$dataArr[self::FN_ID] = "'" . $id . "'";
			$dataArr[self::FN_FORNAMN] = "'" . $fnamn . "'";
			$dataArr[self::FN_EFTERNAMN] = "'" . $enamn . "'";

			self::_save(self::TABLE, $id, $dataArr, true, false);

			return "Lärare med id $id IMPORTERAD";
		} else {
			return "Lärare med id $id finns redan. INTE importerad.";
		}
	}


	public static function antal(){
		return _countRows(self::TABLE);
	}

	public function setFromId($lararID = NULL){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::PK_ID . " = '" . $lararID . "'";
		
		$result = mysqli_query(Config::$DB_LINK, $q);
	
		if(mysqli_num_rows($result) == 1){
			$this->setFromAssoc(mysqli_fetch_assoc($result));
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromAssoc($larareAccFieldArray = NULL){
		if($larareAccFieldArray){
			$this->id = $larareAccFieldArray[self::FN_ID];
			$this->fornamn = $larareAccFieldArray[self::FN_FORNAMN];
			$this->efternamn = $larareAccFieldArray[self::FN_EFTERNAMN];
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}
	
   
    // KOnstruktor
    public function __construct($lararID = NULL) {
		parent::__construct(true);
		if($lararID){
			$this->setFromAssoc($lararID);
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