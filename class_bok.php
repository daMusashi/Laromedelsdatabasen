<?php
	require_once("config.php");
	require_once("class_abstract_dataobject.php");
	require_once("class_kurs.php");
	require_once("class_bokning.php");
	
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
		public $urlSave = "#";
		public $urlEdit = "#";
		public $urlDelete = "#";
		public $urlBoka = "#";
		
		// misc
		public $isEmpty = true;

   	/*
		Statics
	*/
	public static function getAntalTitlar($inkluderaArkiverade = false){
		$where = "WHERE ".self::FN_ARKIVERAD." = false";
		if($inkluderaArkiverade){
			$where = $where . " OR arkiverad = true";
		}
		
		return _countRows(self::TABLE, $where);
	}
	
	public static function getAll($where = NULL, $inkluderaArkiverade = false){
		
		$result = self::_getAllAsResurs(self::TABLE, $where, self::FN_TITEL, $inkluderaArkiverade);
		
		$list = array();
		while($fieldArray = mysqli_fetch_assoc($result)){
			$bok = new Bok($fieldArray);
			array_push($list, $bok);
		}
		
		return $list;
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

		$this->urlSave = "?".CONFIG::PARAM_PRIM_NAV."=bocker-save";
		$this->urlView = "?".CONFIG::PARAM_PRIM_NAV."=bocker-view&".CONFIG::PARAM_REF_ID."=".$this->isbn;
		$this->urlEdit = "?".CONFIG::PARAM_PRIM_NAV."=bocker-edit&".CONFIG::PARAM_REF_ID."=".$this->isbn;
		$this->urlDelete = "?".CONFIG::PARAM_PRIM_NAV."=bocker-delete&".CONFIG::PARAM_REF_ID."=".$this->isbn;
		$this->urlBoka = "?" . CONFIG::PARAM_NAV. "=bokningar-add&".CONFIG::PARAM_REF_TYP . "=bok&".CONFIG::PARAM_REF_ID . "=".$this->isbn;
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

		if($this->isValid()){
			if(self::_rowExist(self::TABLE, self::FN_ISBN , $this->isbn, true)){
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

				print "<p>INSERT: $q</p>";
				$success = mysqli_query(Config::$DB_LINK. $q);
			}
			print "<p>ERROR: ".mysqli_error()."</p>";
		} else {
			return false;
		}

		if($success == 1){
			return true;
		} else {
			return false;
		}
	}

	public function delete(){
		if($this->isValid()){
			$q = "DELETE FROM bocker WHERE ".self::FN_ID." = '" . $this->iid. "'";
			
			if(mysqli_query(Config::$DB_LINK, $q) == 1){
				return true;
			} else {
				return false;
			} 
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

	

	public function getAntalBokade($terminId, $forLasar = false){
		//print "<h4>Bok::getAntalBokade</h4>";
		$num = 0;
		// TODO få en id-lista istället för objekt
		$bokningarForBok = Bokning::getForBok($this->id, $terminId, $forLasar); //getForBok($this->isbn);
		foreach($bokningarForBok as $bokning){
			//print "<br>--b kursId=".$bokning->kursId;
			//$kurs = new Kurs($bokning->kursId);
			$num = $num + Kurs::getAntalElever($bokning->kursId);
		}
		return new Bokantal($this->antal, $num);
	}
	
	

	//public function antalBokbara(){
		//return $this->antal - antalBokade();
	//}
	
	public function getHtmlTdSnippet($index, $terminId, $forLasar = false, $linkBokning = false, $bokningsKursId = null){ // om bokning måste kursId med
		
		//if(empty($antalBokadeObj)){
			$antal = $this->getAntalBokade($terminId, $forLasar);
		//} else {
			//$antal = $antalBokadeObj;
		//}

		$collapseHTML = "";
		$collapseId = "bok-info-$index";

		if(isLoggedIn()){
			
			
			$collapseHTML .= "<div class=\"collapse bok-info\" id=\"$collapseId\">";
			$collapseHTML .= "<p>Bokade: <strong>" . $antal->bokade . "</strong> av " . $antal->antal . "</p>";
			$collapseHTML .= "<div class=\"btn-group btn-group-sm\" role=\"group\">";
			if($linkBokning){
				// bokningsknappar
				$bokningsId = Bokning::makeUrlId($this->id, $bokningsKursId);
				$collapseHTML .= $this->getHtmlButton($label = "Visa bokning", $type = "primary", $link = "?" . CONFIG::PARAM_NAV. "=bokningar-view&".CONFIG::PARAM_REF_ID . "=$bokningsId");
				if(isAdmin()){
					$collapseHTML .= $this->getHtmlButton($label = "Redigera bokning", $type = "warning", $link = "#");
					$collapseHTML .= $this->getHtmlButton($label = "Radera bokning", $type = "danger", $link = "#");
				}
			} else {
				// bokknappar
				$collapseHTML .= $this->getHtmlButton($label = "Visa bok", $type = "primary", $link = $this->urlView);
				if(isAdmin()){
					$collapseHTML .= $this->getHtmlButton($label = "Redigera bok", $type = "warning", $this->urlEdit);
					$collapseHTML .= $this->getHtmlButton($label = "Radera bok", $type = "danger", $link = $this->urlDelete);
				}
			}
			$collapseHTML .= "</div></div>";
		}
	

		$html = "<div class=\"bok-titel\">";		
			if(isLoggedIn()){
				$html .= "<a data-toggle=\"collapse\" href=\"#$collapseId\" aria-expanded=\"false\" aria-controls=\"$collapseId\" class=\"titel-link collapsed\">";
					$html .= "<strong>" . $this->fullTitel . "</strong>";
				$html .= "</a>";
			} else {
				$html .= "<strong>" . $this->fullTitel . "</strong>";
			}
		$html .= "</div>";
		$html .= $collapseHTML;

		return $html;

	}
	
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

	/*
	Privates
	 */
	private function getHtmlButton($label = "#", $type = "primary", $link = "#"){
		return "<a class=\"btn btn-$type btn-sm\" href=\"$link\" role=\"button\">$label</a>";
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