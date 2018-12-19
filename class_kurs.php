<?php
	require_once("class_abstract_dataobject.php");
	require_once("class_bok.php");
	require_once("class_larare.php");
	require_once("class_elev.php");
	require_once("class_termin.php");
	require_once("page_functions_navs.php");
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
		const FN_CREATED = "created"; // field name

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
			if(!$kurs->isOld()){
				$kurserSelectArr[$kurs->id] = $kurs->id;
			}
		}

		return HTML_FACTORY::getAssocArrayAsSelectHTML($kurserSelectArr, $elementId, "Välj en kurs...", "Kurs", $fieldDescription, $selectedId, "300", $elementId);
	}

	public static function getAll($where = NULL, $idsOnly = false, $inkluderaArkiverade = false){
		
		//$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_STARTTERMIN.",".self::FN_ID, true, $inkluderaArkiverade);
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_ID, true, $inkluderaArkiverade);
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

		//print "<p>".count($list)."</p>";
		//print var_dump($list[0]);

		return $list;
	}

	public static function getAllForLasar($lasarId, $onlyIds = false){
		$lasar = new Lasar();
		$lasar->setFromId($lasarId);

		return Kurs::_getAllForTermin($lasar->getFirstTerminId(), true, $onlyIds);
	}

	public static function getAllForTermin($terminId, $forLasar = false){
		return Kurs::_getAllForTermin($terminId, $forLasar, false);
	}

	public static function getAllIdsForTermin($terminId, $forLasar = false){
		return Kurs::_getAllForTermin($terminId, $forLasar, true);
	}

	public static function getAllAsSelectAssoc($where = NULL, $baraBokade = false, $inkluderaArkiverade = false){
		
		$list = [];
		//$list[self::OSPEC_ID] = self::OSPEC_DESC;
		foreach(Kurs::getAll($where) as $kurs){
			if(!$kurs->isOld()){
				if($baraBokade){
					if(Bokning::kursExists($kurs->id)){
						$list[$kurs->id] = $kurs->id;
					}
				} else {
					$list[$kurs->id] = $kurs->id;
				}
			}
		}

		ksort($list);
		
		return $list;
	}

	public static function importSave($id, $period, $lasarObj){ 

		$id = utf8_encode($id);

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

		if(!self::_rowExist(self::TABLE, self::FN_ID, $id, true)){
			self::_save(self::TABLE, $id, $dataArr, true, false);
			return true;
		} else {
			print "<p>Kursen finns redan. Uppdaterar information (tid)</p>";
			self::_save(self::TABLE, "'".$id."'", $dataArr, true, true);
			return true;
		}

	}

	public static function importSaveAddElever($kursId, $elevIdArr){
		$kursId = utf8_encode($kursId);

		print "<p>importSaveAddElever antal elever att lägga till: ".count($elevIdArr)."</p>";
		foreach($elevIdArr as $elevId){
			// ska inte behöva kolla om relation existerar då datan kommer från annan db där dubletter av detta slag inte ska kunna finnas
			if($elevId != "" && $elevId != " "){
				
				$dataArr[Kurs::FK_ID] = "'" . $kursId. "'";
				$dataArr[Elev::FK_ID] = "'" . $elevId . "'";

				print "<p>importSaveAddElever lägger till elev ".$elevId."</p>";

				self::_save(Kurs::TABLE_KURS_ELEVER, "HACK_BEHÖVS_EJ_HÄR", $dataArr, true, false);

			} else {
				//throw new Exception("Felaktigt elev-id [$elevId]. INTE knuten till kurs $kursId");
			}
		} 
	}

	public static function importSaveAddlarare($kursId, $lararId){

		$kursId = utf8_encode($kursId);
		$lararId = utf8_encode($lararId);

		// ska inte behöva kolla om relation existerar då datan kommer från annan db där dubletter av detta slag inte ska kunna finnas
		if($lararId != "" && $lararId != " "){
			
			$dataArr[Kurs::FK_ID] = "'" . $kursId . "'";
			$dataArr[Larare::FK_ID] = "'" . $lararId . "'";

			self::_save(Kurs::TABLE_KURS_LARARE, "HACK_BEHÖVS_EJ_HÄR", $dataArr, true, false);

			return true;
		} else {
			throw new Exception("Felaktigt lärar-id [$lararId]. INTE knuten till kurs $kursId");
			return false;
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
		if(empty($result)){
			$antal = 0;
		} else {
			$antal = mysqli_num_rows($result);
		}
		return $antal;
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
			//var_dump($kursAccFieldArray);
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

	// Om kursen är äldre än aktuell termin
	public function isOld(){
		$currentTermin = Termin::getCurrentTermin();
		//print "<p>this: ".$this->slutTermin->value."(".$this->slutTermin->id."), current: ".$currentTermin->value."(".$currentTermin->id.")</p>";
		if($this->slutTermin->value < $currentTermin->value){
			return true;
		} else {
			return false;
		}
	}

	public function arkiveraIfOld(){
		if($this->isOld()){
			$this->arkiverad = true;
			$this->save();
			return true;
		} else {
			return false;
		}
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

	/* TD-snippet som expanderar vid vidare val i tabell i lista */
	public static function getTdInfoSnippet($index, $kursObj, $startTerminId, $slutTerminId){
		

		$collapseHTML = "";
		$collapseId = "kurs-info-$index";



		if(isAdmin()){
			
			
			$collapseHTML .= "<div class=\"collapse info kurs-info\" id=\"$collapseId\">";
			$collapseHTML .= "<p>Kursens start/slut bestämmer bokningens start/slut</p>";
			
			$collapseHTML .= "<div class=\"btn-group btn-group-sm\" role=\"group\">";
			//$collapseHTML .= getTerminSelectWidget("kurs-termin-select", "null", $startTerminId, true, "set-kurs-start-termin", null, $kursObj->id);
			//$collapseHTML .= getTerminSelectWidget("kurs-termin-select", "null", $slutTerminId, true, "set-kurs-start-termin", null, $kursObj->id);
			$collapseHTML .= "<button data-kursid=\"".$kursObj->id."\" data-startid=\"".$startTerminId."\" data-slutid=\"".$slutTerminId."\" class=\"btn btn-primary\" type=\"button\">Ändra kursens start/slut</button>";
			$collapseHTML .= "</div></div>";
		} 
	

		$html = "<div class=\"kurs-titel\">";		
		if(isAdmin()){
			$html .= "<a data-toggle=\"collapse\" href=\"#$collapseId\" aria-expanded=\"false\" aria-controls=\"$collapseId\" class=\"titel-link collapsed\">";
				$html .= "<strong>" . $kursObj->id . "</strong>";
			$html .= "</a>";
		} else {
			$html .= "<strong>" . $kursObj->id . "</strong>";
		}
		$html .= "</div>";
		$html .= $collapseHTML;

		return $html;

	}

		// Tids-prylar

	public static function getTimeSinceLastUpdate(){
		$timeDiff =  self::getTimediffSinceLastUpdate();
		//print_r($timeDiff);

		$d = round(abs($timeDiff/(24*60*60)));
		$t = round(abs($timeDiff/(60*60)));
		$m = round(abs($timeDiff/(60)));

		if($d > 0){
			return $d." dagar gammalt";
		}
		if($t > 0){
			return $t." timmar gammalt";
		}
		if($m > 0){
			return $m." minuter gammalt";
		}
		return "av okänd ålder";
	}

	public static function getTimestringSinceLastUpdate(){
		$timeDiff =  self::getTimediffSinceLastUpdate();
		//print_r($timeDiff);

		$d = round(abs($timeDiff/(24*60*60)));
		$t = round(abs($timeDiff/(60*60)));
		$t -= $d * 24;
		$m = round(abs($timeDiff/(60)));
		$m -= $t * 60;

		return "$d dagar $t timmar $m minuter";
	}

	public static function getTimediffSinceLastUpdate(){
		$timeUpdate = self::getTimeLastUpdate();
		//print_r($timeUpdate);

		$timeNow = strtotime("now");
		//print_r($timeNow);

		$timeDiff = abs($timeNow - $timeUpdate); // http://php.net/manual/en/datetime.diff.php
		//print_r($timeDiff);

		return $timeDiff;
	}

	public static function getTimeLastUpdate(){
		$result = self::_getAllAsResurs(self::TABLE);
		$kurs = mysqli_fetch_assoc($result);
		//print_r($kurs[self::TABLE_KURS_FN_CREATED]);
		$timeUpdate = strtotime($kurs[self::FN_CREATED]);

		return $timeUpdate;
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