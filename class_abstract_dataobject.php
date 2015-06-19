<?php
	abstract class Dataobject {
		
		const FN_ARKIVERAD = "arkiverad"; // field name
		
		private $arkivbar = false;
		
		public function __construct($isArkivbar = false){
			$this->arkivbar = $isArkivbar;
		}
		
		public static function _getAllAsResurs($table, $where = NULL, $orderByField = NULL, $isArkivbar = false, $inkluderaArkiverade = false){
			$compiledWhere = self::getCompiledWhere($where, $isArkivbar, $inkluderaArkiverade);
			$compiledOrderBy = self::getCompiledOrderBy($orderByField);
						
			$q = "SELECT * FROM ".$table." ".$compiledWhere.$compiledOrderBy;
			print "<p>$q</p>";
		
			$result = mysqli_query(CONFIG::$DB_LINK, $q);
			if(!$result){
				debugLog("<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysqli_error() , "$table|getAllAsArray");
			}
			
			return $result;
		}
		
		public static function _rowExist($table, $idField, $id, $idIsString = true){
			if($idIsString){
				$id = "'$id'";		
			}

			if(_countRows($table, "$idField = $id") > 0){
				return true;
			} else {
				return false;
			}
		}
		
		public static function _countRows($table, $where = NULL, $isArkivbar = false){
			$compiledWhere = getCompiledWhere($where, $isArkivbar, $inkluderaArkiverade);
			
			$q = "SELECT * FROM ".$table.$compiledWhere;
			return mysqli_num_rows(mysqli_query(CONFIG::$DB_LINK, $q));
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
		
		// slår ihop bifogad SQL-WHERE med ev arkiverings-WHERE (för arkivbara)
		private static function getCompiledWhere($bifogadWhere = NULL, $isArkivbar = false, $inkluderaArkiverade = false){
			
			$where = "";
			
			if($isArkivbar){
				$arkiverad_where = self::FN_ARKIVERAD." = false";
			
				if($inkluderaArkiverade){
					$arkiverad_where = $arkiverad_where . " OR arkiverad = true";
				}
				
				if($bifogadWhere){
					$where = " WHERE " . $bifogadWhere . " AND (" . $arkiverad_where . ")";
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
		
		public static function helloStaticAbstract(){
			print "<p>Hallå! (helloStaticAbstract)</p>";
		}
		
		public function helloAbstract(){
			print "<p>Hallå! (helloAbstract)</p>";
		}
		
	}
?>