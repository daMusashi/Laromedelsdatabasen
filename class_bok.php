<?php
	require_once("config.php");
	require_once("class_abstract_dataobject.php");
	require_once("class_kurs.php");
	require_once("class_bokning.php");
	require_once("class_html_factory.php");
	
	class Bok extends Dataobject
	{
    	const TABLE = "bocker"; // tabellnamn
		const TABLE_BOKNING = Bokning::TABLE; // tabellnamn
		
		const FN_ID = "id"; // field name
		const FN_ISBN = "isbn"; // field name
		const FN_TITEL = "titel"; // field name
		const FN_UPPLAGA = "upplaga"; // field name
		const FN_FORF_ENAMN = "forf_efternamn"; // field name
		const FN_FORF_FNAMN = "forf_fornamn"; // field name
		const FN_ANTAL = "antal"; // field name
		const FN_ARKIVERAD = "arkiverad"; // field name
		const FN_UNDERTITEL = "undertitel"; // field name
		const FN_PRIS = "pris"; // field name
		const FN_FORLAG = "forlag"; // field name
		const FN_UTGIVNING = "utgivnings_ar"; // field name

		const PK_ID = self::FN_ID; // fieldname PRIMARY key
		const FK_ID = "bok_id"; // fieldname FOREIGN key

		const DEFAULT_ORDER_BY = self::FN_TITEL ; // standardsortering
		
		public $id = NULL;
		public $isbn = NULL;
		public $titel = NULL;
		public $upplaga = NULL;
		public $forf_fornamn = NULL;
		public $forf_efternamn = NULL;
		public $antal = 0;
		public $arkiverad  = 0;
		public $undertitel = NULL;
		public $pris = 0;
		public $forlag = NULL;
		public $utg_ar = NULL;
		
		// genererade props
		public $fullTitel = "";

		public $urlView = "#";
		public $urlEdit = "#";
		public $urlDelete = "#";
		public $urlBoka = "#";
		
		// misc
		public $isEmpty = true;

   	/*
		Statics
	*/
	
	public static function getAddUrl(){
		return "?".CONFIG::PARAM_NAV."=bocker-add";
	}

	public static function getSaveUrl(){
		return "?".CONFIG::PARAM_NAV."=bocker-save";
	}

	public static function getViewUrl($bokId){
		return "?".CONFIG::PARAM_NAV."=bocker-view&".CONFIG::PARAM_REF_ID."=".$bokId;
	}

	public static function getSelectHTML($fieldDescription = "", $selectedId = "", $elementId = "select-bok"){
		$bocker = self::getAll();
		$bockerSelectArr = [];

		foreach($bocker as $bok){
			$bockerSelectArr[$bok->id] = $bok->fullTitel;
		}

		return HTML_FACTORY::getAssocArrayAsSelectHTML($bockerSelectArr, $elementId, "Välj ett läromedel...", "Läromedel", $fieldDescription, $selectedId, "300", $elementId);
	}

	public static function getAntalTitlar($inkluderaArkiverade = false){
		$where = "WHERE ".self::FN_ARKIVERAD." = false";
		if($inkluderaArkiverade){
			$where = $where . " OR arkiverad = true";
		}
		
		return _countRows(self::TABLE, $where);
	}
	
	public static function getAll($where = NULL){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_TITEL, true);
		
		$list = array();
		while($fieldArray = mysqli_fetch_assoc($result)){
			$bok = new Bok($fieldArray);
			array_push($list, $bok);
		}
		
		return $list;
	}

	public static function getAllIds($where = NULL){
		
		$bocker = getAll($where);
		$ids = [];
		foreach($list as $bok){
			array_push($ids, $bok->id);
		}
		
		return $ids;
	}
	
	public static function helloStaticClass(){
		print "<p>Hallå! (helloStaticClass)</p>";
	}
   
   
    /**
     * Konstruktor
     * @param [type] $bokAccFieldArray [description]
     */
    public function __construct($bokAccFieldArray = NULL) {
        parent::__construct(true);
		if(isset($bokAccFieldArray)){
			$this->setFromAssoc($bokAccFieldArray);
		}
    }
	
	private function generateProps(){
	
		$this->fullTitel = $this->getFullBokTitel();

		$this->urlSave = "?".CONFIG::PARAM_NAV."=bocker-save";
		$this->urlView = Bok::getViewUrl($this->id);
		$this->urlEdit = "?".CONFIG::PARAM_NAV."=bocker-edit&".CONFIG::PARAM_REF_ID."=".$this->id;
		$this->urlDelete = "?".CONFIG::PARAM_NAV."=bocker-delete&".CONFIG::PARAM_REF_ID."=".$this->id;
		$this->urlBoka = "?" . CONFIG::PARAM_NAV. "=bokningar-add&".CONFIG::PARAM_REF_TYP . "=bok&".CONFIG::PARAM_REF_ID . "=".$this->id;
	}
	
	public function setFromAssoc($bokAccFieldArray = NULL){
		if($bokAccFieldArray){
			$this->id = $bokAccFieldArray[self::FN_ID];
			$this->isbn = $bokAccFieldArray[self::FN_ISBN];
			$this->titel = $bokAccFieldArray[self::FN_TITEL];
			$this->upplaga = $bokAccFieldArray[self::FN_UPPLAGA];
			$this->forf_fornamn = $bokAccFieldArray[self::FN_FORF_FNAMN];
			$this->forf_efternamn = $bokAccFieldArray[self::FN_FORF_ENAMN];
			$this->antal = $bokAccFieldArray[self::FN_ANTAL];
			$this->arkiverad = $bokAccFieldArray[self::FN_ARKIVERAD];
			$this->undertitel = $bokAccFieldArray[self::FN_UNDERTITEL];
			// pris hoppas över så länge
			$this->forlag = $bokAccFieldArray[self::FN_FORLAG];
			$this->utg_ar = $bokAccFieldArray[self::FN_UTGIVNING];
			
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromForm($formData = NULL){
		if($formData){
			$this->id = $formData[self::FN_ID];
			$this->isbn = $formData[self::FN_ISBN];
			$this->titel = $formData[self::FN_TITEL];
			$this->upplaga = $formData[self::FN_UPPLAGA];
			$this->forf_fornamn = $formData[self::FN_FORF_FNAMN];
			$this->forf_efternamn = $formData[self::FN_FORF_ENAMN];
			$this->antal = $formData[self::FN_ANTAL];
			$this->undertitel = $formData[self::FN_UNDERTITEL];
			// pris hoppas över så länge
			$this->forlag = $formData[self::FN_FORLAG];
			$this->utg_ar = $formData[self::FN_UTGIVNING];
			
			$this->generateProps();
			
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}
	
	public function setFromId($id){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::PK_ID . " = '" . $id . "'";
		
		$result = mysqli_query(Config::$DB_LINK, $q);
	
		if(mysqli_num_rows($result) == 1){
			$this->setFromAssoc(mysqli_fetch_assoc($result));
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}

	public function setFromISBN($isbn){
		$q = "SELECT * FROM " . self::TABLE . " WHERE " . self::FN_ISBN . " = '" . $isbn . "'";
		
		$result = mysqli_query(Config::$DB_LINK, $q);
	
		if(mysqli_num_rows($result) == 1){
			$this->setFromAssoc(mysqli_fetch_assoc($result));
			$this->isEmpty = false;
		} else {
			$this->isEmpty = true;
		}
	}
	
	public function save(){
		$success = false;
		//var_dump($this);
		if($this->isValid()){
			if(self::_rowExist(self::TABLE, self::FN_ID , $this->id)){
				// update
				$q = "UPDATE " . self::TABLE . 
				" SET " . 
				self::FN_TITEL . "='" . $this->titel . "', " .
				self::FN_UPPLAGA . "='" . $this->upplaga . "', " .
				self::FN_FORF_FNAMN . "='" . $this->forf_fornamn . "', " .
				self::FN_FORF_ENAMN . "='" . $this->forf_efternamn . "', " .
				self::FN_ANTAL . "=" . $this->antal . ", " .
				self::FN_ARKIVERAD . "=" . $this->arkiverad . ", " .
				self::FN_UNDERTITEL . "='" . $this->undertitel . "', " .
				self::FN_FORLAG . "='" . $this->forlag . "', " .
				self::FN_UTGIVNING . "='" . $this->utg_ar. "' " .
				"WHERE " . self::FN_ID  . " = '" . $this->id . "'";

				//print "<p>UPDATE: $q</p>";
				$success = mysqli_query(Config::$DB_LINK, $q);

			} else {
				// add
				$q = "INSERT INTO " . self::TABLE . 
				" (" . 
				self::FN_ISBN . ", " .
				self::FN_TITEL . ", " .
				self::FN_UPPLAGA . ", " .
				self::FN_FORF_FNAMN . ", " .
				self::FN_FORF_ENAMN . ", " .
				self::FN_ANTAL . ", " .
				self::FN_ARKIVERAD . ", " .
				self::FN_UNDERTITEL . ", " .
				self::FN_FORLAG . ", " .
				self::FN_UTGIVNING .
				")" .
				" VALUES (" . 
				"'" . $this->isbn . "', " .
				"'" . $this->titel . "', " .
				"'" . $this->upplaga . "', " .
				"'" . $this->forf_fornamn . "', " .
				"'" . $this->forf_efternamn . "', " .
				$this->antal . ", " .
				$this->arkiverad . ", " .
				"'" . $this->undertitel . "', " .
				"'" . $this->forlag . "', " .
				"'" . $this->utg_ar. "'" .
				")";

				//print "<p>INSERT: $q</p>";
				$success = mysqli_query(Config::$DB_LINK, $q);
			}

		} else {
			throw new Exception("Läromedlet saknar essentiella värden och sparas inte.");
			return false;
		}

		if($success == 1){
			return true;
		} else {
			throw new Exception("Något gick fel vid sparande av läromedlet.");
			return false;
		}
	}

	public function delete(){
		//Self::_delete(self::FN_ID." = " . $this->id);

		if($this->isValid()){
			$q = "DELETE FROM ".self::TABLE . " WHERE id = " . $this->id;
			//print "RADERAR BOKNING $q";
			if(mysqli_query(Config::$DB_LINK, $q) == 1){
				return true;
			} else {
				return false;
			} 
			return true;
		} else {
			return false;
		}
	}
	
	public function isValid(){
		$valid = true;

		if(empty($this->isbn)){$valid = false;}
		if(empty($this->titel)){$valid = false;}

		return $valid;
	}

	/* TD-snippet som expanderar vid vidare val i tabell i lista */
	public static function getTdInfoSnippet($index, $bokObj, $antalObj = null, $bokningsObj = null){
		

		$collapseHTML = "";
		$collapseId = "bok-info-$index";



		if(isLoggedIn()){
			
			
			$collapseHTML .= "<div class=\"collapse info bok-info\" id=\"$collapseId\">";
			if(!empty($antalObj)){
				$collapseHTML .= "<p>Bokade: <strong>" . $antalObj->bokade . "</strong> av " . $antalObj->antal . "</p>";
			}
			$collapseHTML .= "<div class=\"btn-group btn-group-sm\" role=\"group\">";
			if(!empty($bokningsObj)){
				// bokningsknappar
				$collapseHTML .= HTML_FACTORY::getKnappHTML($bokningsObj->urlView, "Visa bokning", "sm", "primary");
				if(isAdmin()){
					$collapseHTML .= HTML_FACTORY::getKnappHTML($bokningsObj->urlEdit, "Redigera bokning", "sm", "warning");
					$collapseHTML .= HTML_FACTORY::getKnappHTML($bokningsObj->urlDelete, "Radera bokning", "sm", "danger");
				}
			} else {
				// bokknappar
				$collapseHTML .= HTML_FACTORY::getKnappHTML($bokObj->urlView, "Visa info & bokningar", "sm", "primary");
				if(isAdmin()){
					$collapseHTML .= HTML_FACTORY::getKnappHTML($bokObj->urlEdit, "Redigera läromedel", "sm", "warning");
					$collapseHTML .= HTML_FACTORY::getDeleteKnappHTML($bokObj->urlDelete, $bokObj->fullTitel, "Radera läromedel", "sm", "danger");
				}
			}
			$collapseHTML .= "</div></div>";
		} 
	

		$html = "<div class=\"bok-titel\">";		
			if(isLoggedIn()){
				$html .= "<a data-toggle=\"collapse\" href=\"#$collapseId\" aria-expanded=\"false\" aria-controls=\"$collapseId\" class=\"titel-link collapsed\">";
					$html .= "<strong>" . $bokObj->fullTitel . "</strong>";
				$html .= "</a>";
			} else {
				$html .= "<strong>" . $bokObj->fullTitel . "</strong>";
			}
		$html .= "</div>";
		$html .= $collapseHTML;

		return $html;

	}

	public function toString(){
		$antal = $this->getAntalBokade();

		$output = "<h3>* Bok-instans *</h3>";
		$output .= "<ul>";
		$output .= self::_propToStringListitem("isbn", $this->id);
		$output .= self::_propToStringListitem("isbn", $this->isbn);
		$output .= self::_propToStringListitem("upplaga", $this->upplaga);
		$output .= self::_propToStringListitem("forf_fornamn", $this->forf_fornamn);
		$output .= self::_propToStringListitem("forf_efternamn", $this->forf_efternamn);
		$output .= self::_propToStringListitem("antal", $this->antal);
		$output .= self::_propToStringListitem("arkiverad", $this->arkiverad);
		$output .= self::_propToStringListitem("undertitel", $this->undertitel);
		$output .= self::_propToStringListitem("forlag", $this->forlag);
		$output .= self::_propToStringListitem("utg_ar", $this->utg_ar);
		$output .= self::_propToStringListitem("antalBokade (genererad)", $antal->bokade);
		$output .= self::_propToStringListitem("antalBokbara (genererad)", $antal->bokbara);
		$output .= self::_propToStringListitem("bokbar (genererad)", $antal->bokbar);
		$output .= self::_propToStringListitem("urlView (genererad)", $this->urlView);
		$output .= self::_propToStringListitem("urlEdit (genererad)", $this->urlEdit);
		$output .= self::_propToStringListitem("urlDelete (genererad)", $this->urlDelete);
		$output .= self::_propToStringListitem("urlBoka (genererad)", $this->urlBoka);
		$output .= "</ul>";

		return $output;
	}

	public static function exists($bokId){
		if(self::_countRows(self::TABLE, self::FN_ID . " = " . $bokId) > 0){
			return true;
		} else {
			return false;
		}
	}

	public static function getGhostBok($bokId = -999){
		$ghost = new Bok();

		$ghost->id = $bokId;
		$ghost->isbn = $ghost->id;
		$ghost->titel = "** !!RADERAD BOK ID=$bokId!! **";
		$ghost->forf_efternamn = "ADMIN";
		$ghost->antal = 0;
		$ghost->arkiverad  = 0;
		$ghost->undertitel = "Radera bokningen och gör en ny!";
		$ghost->pris = 0;

		$ghost->generateProps();

		return $ghost;

	}

	public function getAntalBokade($terminId, $forLasar = false){
		//print "<h4>Bok::getAntalBokade</h4>";
		$num = 0;

		//print "<p>bok-id: ".$this->id."</p>";
		//print "<p>bok.getAntalBokade bokid[".$this->id."]";
		$bokningarForBok = Bokning::getForBok($this->id, $terminId, $forLasar); //getForBok($this->isbn);
		//print "<p>Antal bokningar: ".count($bokningarForBok)."</p>";
		foreach($bokningarForBok as $bokning){
			$num = $num + Kurs::getAntalElever($bokning->kursId);
			//print "<p>elever/böcker: $num</p>";
		}
		return new Bokantal($this->antal, $num);
	}
	
	

	//public function antalBokbara(){
		//return $this->antal - antalBokade();
	//}
	
	
	
	public function getForfattarNamn(){
		return $this->forf_fornamn . " " . $this->forf_efternamn;
	}
	
	public function getFullBokTitel(){
		if($this->upplaga == ""){
			$upplaga = "";
		} else {
			$upplaga = " (" . $this->upplaga . ")";
		}

		if($this->undertitel == ""){
			$undertitel = "";
		} else {
			$undertitel = " - " . $this->undertitel;
		}

		return $this->titel . "$undertitel$upplaga";
	}


}

class Bokantal {
	public $bokbar= false;
	public $antal = 0;
	public $bokade = 0;
	public $bokbara = 0;

		

	public function __construct($_antal = null, $_bokade = null) {
		if(isset($_antal)&&isset($_bokade)){
			$this->antal = $_antal;
			$this->bokade = $_bokade;
			$this->bokbara = $this->antal - $this->bokade;
			if($this->bokbara > 0){
				$this->bokbar = true;
			}
		}
    }

}
?>