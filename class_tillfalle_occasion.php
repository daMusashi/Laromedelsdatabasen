<?php	
/*
	Hjälpklass för att sköta in/ut-tillfällen under ett läsår. Används tillsammans med class year i class Tillfallen för att skapa tillfällen
 */
class Tillfalle_occasion {

	public $id = "";
	public $desc = "";
	public $default_date = "";

	public static function generateData(){
		$occasions = [];

		$o1 = Self::generateStart();
		$occasions[$o1->id] = $o1;

		$o2 = Self::generateMid();
		$occasions[$o2->id] = $o2;

		$o3 = Self::generateEnd();
		$occasions[$o3->id] = $o3;

		return $occasions;
	}

	public static function generateStart(){
		$o = new Tillfalle_occasion();
		$o->id = Config::TILLFALLEN_LASAR_GENERIC_START_ID;
		$o->desc = "Läsårets START";
		$o->date = Config::TILLFALLEN_LASAR_GENERIC_START_DATE;

		return $o;
	}

	public static function generateMid(){
		$o = new Tillfalle_occasion();
		$o->id = Config::TILLFALLEN_LASAR_GENERIC_MID_ID;
		$o->desc = "Vårterminens START";
		$o->date = Config::TILLFALLEN_LASAR_GENERIC_MID_DATE;

		return $o;
	}

	public static function generateEnd(){
		$o = new Tillfalle_occasion();
		$o->id = Config::TILLFALLEN_LASAR_GENERIC_END_ID;
		$o->desc = "Läsårets SLUT";
		$o->date = Config::TILLFALLEN_LASAR_GENERIC_END_DATE;

		return $o;
	}
}
?>