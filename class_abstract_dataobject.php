<?php
	require_once("class_html_factory.php");

	abstract class Dataobject {
		
		const FN_ARKIVERAD = "arkiverad"; // field name
		
		private $arkivbar = false;

		static $sqlCallsSelect = 0;
		static $sqlCallsCount = 0;
		static $sqlCallsTablesUsed = "";
		
		public function __construct($isArkivbar = false){
			$this->arkivbar = $isArkivbar;
		}
		
		public static function _getAllAsResurs($table, $where = NULL, $orderByField = NULL, $isArkivbar = false, $inkluderaArkiverade = false){
			$compiledWhere = self::getCompiledWhere($where, $isArkivbar, $inkluderaArkiverade);
			//print "<p>compiledWhere: [$compiledWhere]</p>";
			$compiledOrderBy = self::getCompiledOrderBy($orderByField);
			//print "<p>compiledOrderBy: [$compiledOrderBy]</p>";
						
			$q = "SELECT * FROM ".$table." ".$compiledWhere.$compiledOrderBy;
		
			$result = mysqli_query(CONFIG::$DB_LINK, $q);
			
			self::checkError($result, $q, "abstract_dataobject::_getAllAsResurs");

			self::$sqlCallsSelect++;
			self::$sqlCallsTablesUsed .= ", ".$table;

			return $result;
		}
		
		public static function _rowExist($table, $idField, $id, $idIsString = false){
			if($idIsString){
				$id = "'$id'";		
			}

			if(self::_countRows($table, "$idField = $id") > 0){
				return true;
			} else {
				return false;
			}
		}
		
		public static function _countRows($table, $where = NULL, $isArkivbar = false, $inkluderaArkiverade = false){
			$compiledWhere = self::getCompiledWhere($where, $isArkivbar, $inkluderaArkiverade);
			
			$q = "SELECT * FROM ".$table.$compiledWhere;
			//print "<p>abstract _countRows: $q</p>";
			self::$sqlCallsCount++;
			self::$sqlCallsTablesUsed .= ", ".$table;
			return mysqli_num_rows(mysqli_query(CONFIG::$DB_LINK, $q));
		}

		public static function _save($table, $id, $saveDataArr, $isValid = true, $exists = false, $idIsString = false){

			if($isValid){

				if($exists){
					// update
					$q = "UPDATE $table SET "; 
					
					foreach ($saveDataArr as $fld => $value) {
						if($value != $id){
							$q .= "$fld = $value, ";
						}
					}
					$q = substr($q, 0, strlen($q) - 2); // tar bort sista ,

					if($idIsString){
						$q .= " WHERE id ='" . $id . "''";
					} else {
						$q .= " WHERE id =" . $id;
					}

					$ret = mysqli_query(Config::$DB_LINK, $q);
					if (empty($ret)){
						throw new Exception("Något gick vid fel vid <strong>uppdatering</strong>.
							<br>Query: $q
							<br>DB Error: ".mysqli_error(Config::$DB_LINK));
					}
				} else {
					// add
					$q = "INSERT INTO $table ("; 
					
					foreach (array_keys($saveDataArr) as $fld) {
						$q .= "$fld, ";
					}
					$q = substr($q, 0, strlen($q) - 2); // tar bort sista ,
	
					$q .=  ") VALUES (";

					foreach (array_values($saveDataArr) as $value) {
						$q .= "$value, ";
					}
					$q = substr($q, 0, strlen($q) - 2); // tar bort sista ,
					
					$q .=  ")";

					$ret = mysqli_query(Config::$DB_LINK, $q);
					if (empty($ret)){
						throw new Exception("<p>Något gick vid fel vid <strong>skapande av post</strong>.
							<br>Query: $q
							<br>DB Error: [".mysqli_error(Config::$DB_LINK)."]</p>");
					}
				}
			} else {
				throw new Exception("Informationsobjektet är inte komplett för sparande");
			}

		}

		public static function _propToString($prop){
			if(!isset($prop)){
				return "INTE SATT!";
			} else {
				if(empty($prop)){
					switch($prop){
						case 0:
							return "0";
						case "":
							return "\"\"";
						case false:
							return "FALSE";
						default:
							return "TOM (men inte NULL)";
					}
					
				} else {
					return $prop;
				}
			}
		}

		public static function getDbResursHTML($resurs){
			$html = "";


			if(mysqli_num_rows($resurs) > 0){

				$html = $html . "<table cellspacing=\"0\" border=\"1\" class=\"border tiny-data\">";

				$firstRow = true;
				while($row = mysqli_fetch_assoc($resurs)){
					if($firstRow){
						$html = $html . "<tr>";
						foreach($row as $fname => $fvalue){
							$html = $html . "<th>$fname</th>";
						}
						$html = $html . "</tr>";
					}

					$html = $html . "<tr>";
					foreach($row as $fname => $fvalue){
						$html = $html . "<td>$fvalue</td>";
					}
					$html = $html . "</tr>";

					$firstRow = false;
				}

				$html = $html . "</table>";

			} else {

				$html = $html . "<p>Det finns ingen data i tabellen :(</p>";

			}

			return $html;
		}

		public static function _propToStringListitem($propName, $propValue){
			return "<li>$propName: ".self::_propToString($propValue)."</li>";
		}

		protected function debugLog($text, $source = ""){
			global $CONFIG;
			if($CONFIG["outputDebug"]){
				if($source != ""){
					$source = "[$source]";
				}
				print "<p class=\"debug-item\">DEBUG$source: $text</p>";
			}
		}
		
		
		public static function checkError($dbResultat, $q, $source = "[okänd källa]", $msg = ""){
			if(empty($dbResultat)){
				if(Config::DEBUG){
					$content = "<br>KÄLLA: $source";
					$content .= "<br>QUERY: $q";
					$content .= "<br>FEL: [<em>" . mysqli_error(Config::$DB_LINK)."</em>]";
				} else {
					$content = "Något gick fel i källa <em>$source</em>";
				}
				
				HTML_FACTORY::printErrorAlert("Databasfel!", $content);
			}
		}

		// slår ihop bifogad SQL-WHERE med ev arkiverings-WHERE (för arkivbara)
		private static function getCompiledWhere($bifogadWhere = NULL, $isArkivbar = false, $inkluderaArkiverade = false){
			
			$where = "";
			
		
			if($isArkivbar){
				$arkiverad_where = self::FN_ARKIVERAD." = 0";
			
				if($inkluderaArkiverade){
					$arkiverad_where = $arkiverad_where . " OR arkiverad = 1";
				}
				
				if($bifogadWhere){
					$where = " WHERE " . $bifogadWhere . " AND (" . $arkiverad_where . ")";
				} else {
					$where = " WHERE " . $arkiverad_where;
				}
				
			} else {
				if($bifogadWhere){
					$where = " WHERE " . $bifogadWhere;
				}
			}

			return $where;
		}
		
		private static function getCompiledOrderBy($orderByField = NULL){
			if($orderByField){
				return " ORDER BY $orderByField";	
			} else {
				return "";	
			}
		}

		public static function getDebugSqlCalls(){
			$html = "";
			$sessionid = session_id();
			$html .= "<p>SQL SELECT calls for [$sessionid]; ".self::$sqlCallsSelect."</p>";
			$html .= "<p>SQL COUNT calls for [$sessionid]; ".self::$sqlCallsCount."</p>";
			$html .= "<p>Tables used for [$sessionid]; ".self::$sqlCallsTablesUsed."</p>";

			return $html;
		}
		
		public static function helloStaticAbstract(){
			print "<p>Hallå! (helloStaticAbstract)</p>";
		}
		
		public function helloAbstract(){
			print "<p>Hallå! (helloAbstract)</p>";
		}
		
	}
?>