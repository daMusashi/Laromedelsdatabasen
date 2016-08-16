<?php
require_once("config.php");
require_once("class_abstract_dataobject.php");
require_once("class_kurs.php");
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2015-11-04
 * Time: 00:21
 */
class Datalager extends Dataobject
{
    const TABLE_KURS = "dl_kurser"; // tabellnamn
    const TABLE_KURS_FN_ID = "id"; // field name
    const TABLE_KURS_FN_ANTAL_ELEVER = "antal_elever"; // field name
    const TABLE_KURS_FN_CREATED = "created"; // field name

    const TABLE_BOKNING = "dl_bokningar"; // tabellnamn
    const TABLE_BOKNING_FN_ID = "id"; // field name
    const TABLE_BOKNING_FN_ANTAL_BOCKER = "antal_bocker"; // field name
    const TABLE_BOKNING_FN_CREATED = "created"; // field name

    const TABLE_ELEVBOCKER = "dl_elevbocker"; // tabellnamn
    const TABLE_ELEVBOCKER_FN_ELEVID = "elev_id"; // field name
    const TABLE_ELEVBOCKER_FN_BOKNINGSID = "boknings_id"; // field name
    const TABLE_ELEVBOCKER_FN_BOKID = "bok_id"; // field name
    const TABLE_ELEVBOCKER_FN_KURSID = "kurs_id"; // field name
    const TABLE_ELEVBOCKER_FN_ELEVNAMN = "elev_namn"; // field name
    const TABLE_ELEVBOCKER_FN_ELEVKLASS = "elev_klass"; // field name
    const TABLE_ELEVBOCKER_FN_IN = "in_tillfalle"; // field name
    const TABLE_ELEVBOCKER_FN_UT = "ut_tillfalle"; // field name

    public function __construct() {
        parent::__construct(false);

    }

    public static function getAntalEleverForKurs($kursId){
        $result = self::_getAllAsResurs(self::TABLE_KURS, Datalager::TABLE_KURS_FN_ID." = '$kursId'");
        $fieldArray = mysqli_fetch_assoc($result);
        //var_dump($fieldArray);
        $antal = $fieldArray[self::TABLE_KURS_FN_ANTAL_ELEVER];
        if(empty($antal)){
            if("$antal" != "0") {
                $antal = -1;
            }
        }
        //print "*".$antal."*";
        return $antal;
    }

    /*public static function _getBokningarForElev($elevId){
        $result = self::_getAllAsResurs(self::TABLE_ELEVBOCKER, Datalager::TABLE_ELEVBOCKER_FN_ELEVID." = '$elevId'");
        $bokningar = [];
        while($fieldArray = mysqli_fetch_assoc($result)){
            $bokning = new Bokning();
            $bokning->setFromId($fieldArray[self::TABLE_ELEVBOCKER_FN_BOKNINGSID]);
            array_push($bokningar, $bokning);
        }
        return $bokningar ;
    }*/

    public static function getBokningarForElev($elevId){
        $result = self::_getAllAsResurs(self::TABLE_ELEVBOCKER, Datalager::TABLE_ELEVBOCKER_FN_ELEVID." = '$elevId'");
        $bokningar = [];
        while($fieldArray = mysqli_fetch_assoc($result)){
            array_push($bokningar, $fieldArray);
        }
        return $bokningar;
    }

    public static function getAntalBockerForKurs($kursId){
        $result = self::_getAllAsResurs(self::TABLE_ELEVBOCKER, self::TABLE_ELEVBOCKER_FN_KURSID." = '$kursId'");
        return mysqli_num_rows($result);
    }

    public static function getAntalBockerForBokning($bokningsId){
        $result = self::_getAllAsResurs(self::TABLE_ELEVBOCKER, self::TABLE_ELEVBOCKER_FN_BOKNINGSID." = '$bokningsId'");
        return mysqli_num_rows($result);
    }

    public static function getElevbockerForElev($elevId){
        $result = self::_getAllAsResurs(self::TABLE_ELEVBOCKER, self::TABLE_ELEVBOCKER_FN_ELEVID." = '$elevId'");
        $elevBocker = [];
        while($fieldArray = mysqli_fetch_assoc($result)){
            array_push($elevBocker, $fieldArray);
        }
        return $elevBocker;
    }

    /* TODO
        VÄLDIGT LÅNGSAM - ANVÄND ALTERNATIV!!
    */
    public static function getAntalBokningarForBok($bokId, $terminId, $useLasar = false){
        $time_pre = microtime(true);
        $result = self::_getAllAsResurs(self::TABLE_ELEVBOCKER, self::TABLE_ELEVBOCKER_FN_BOKID." = '$bokId'");

        $bocker = [];
        while($fieldArray = mysqli_fetch_assoc($result)){
            self::arrayPushIfInTermin($bocker, $fieldArray, $terminId, null, $useLasar);
        }
        $time_post = microtime(true);
        return count($bocker);
}
    public static function getBokadeBockerForBokningsArr($bokningsArr, $terminId, $useLasar = false){
        $bokadeBocker = [];
        foreach($bokningsArr as $bokning){
            if(!array_key_exists($bokning->bokId, $bokadeBocker)){
                $bokadeBocker[$bokning->bokId] = Datalager::getAntalBokningarForBok($bokning->bokId, $terminId);
            }
        }
        return $bokadeBocker;
    }

    public static function getBokningsIdsForTermin($terminId, $useLasar = false){
        $time_pre = microtime(true);
        $result = self::_getAllAsResurs(self::TABLE_ELEVBOCKER, null, self::TABLE_ELEVBOCKER_FN_KURSID);
        //getDbResursHTML($result);
        $bokningar = [];
        while($fieldArray = mysqli_fetch_assoc($result)){
            self::arrayPushIfInTermin($bokningar, $fieldArray, $terminId, self::TABLE_ELEVBOCKER_FN_BOKNINGSID, $useLasar);
        }
        //$time_post = microtime(true);
        return $bokningar;
    }

    private static function arrayPushIfInTermin(&$arr, $elevBockerFieldArr, $terminId, $fieldToAdd = null, $useLasar = false){
        // omvandlar till jämförbara värden
        $start = new Termin();
        $start->setFromId($elevBockerFieldArr[self::TABLE_ELEVBOCKER_FN_UT]);
        $slut = new Termin();
        $slut->setFromId($elevBockerFieldArr[self::TABLE_ELEVBOCKER_FN_IN]);
        $wanted = new Termin();
        $wanted->setFromId($terminId);

        if($useLasar){
            $start = $start->lasar->value;
            $slut = $slut->lasar->value;
            $wanted = $wanted->lasar->value;
        } else {
            $start = $start->value;
            $slut = $slut->value;
            $wanted = $wanted->value;
        }

        //print "<p>start: $start</p>";
        //print "<p>slut: $slut</p>";
        //print "<p>wanted: $wanted</p>";
        //print "<p>bokning: ".$elevBockerFieldArr[$fieldToAdd]."</p>";


        $result = "INTE i läsår";
        if(($start <= $wanted)&&($slut >= $wanted)) {
            if ($fieldToAdd) {
                self::arrayPushIfNotExists($arr, $elevBockerFieldArr[$fieldToAdd]);
            } else {
                self::arrayPushIfNotExists($arr, $elevBockerFieldArr);
            }
            $result = "I läsår";
        }
        //print "<p>$result</p>";
        //print "<p>-------</p>";
    }

    private static function arrayPushIfNotExists(&$arr, $item){
        if(!in_array($item, $arr)) {
            array_push($arr, $item);
        }
    }

    public static function _flushKurser(){
        // töm dl_kurse
        $q = "DELETE FROM ". self::TABLE_KURS;
        mysqli_query(Config::$DB_LINK, $q);
    }
    public static function _flushBokningar(){
        // töm dl_kurse
        $q = "DELETE FROM ". self::TABLE_BOKNING;
        mysqli_query(Config::$DB_LINK, $q);
    }
    public static function _flushElevbocker(){
        // töm dl_kurse
        $q = "DELETE FROM ". self::TABLE_ELEVBOCKER;
        mysqli_query(Config::$DB_LINK, $q);
        // resettar auto increment för id (som ändå inte används till något)
        $q = "ALTER TABLE ".self::TABLE_ELEVBOCKER." AUTO_INCREMENT = 1";
        mysqli_query(Config::$DB_LINK, $q);
    }

    // Kör FÖRE createBokningsData - då den använder dl_kurs
    public static function createKursData(){
        $time_pre = microtime(true);
        self::_flushKurser();

        $kursIds = Kurs::getAll(null, true);

        foreach($kursIds as $kursId){
            $antalElever = Kurs::getAntalElever($kursId);

            $dataArr[self::TABLE_KURS_FN_ID] = "'" . $kursId . "'";
            $dataArr[self::TABLE_KURS_FN_ANTAL_ELEVER] = $antalElever;

            try {
                self::_save(self::TABLE_KURS, "'" . $kursId . "'", $dataArr);
            } catch(Exception $e) {
                print $e->getMessage();
            }
        }
        $time_post = microtime(true);
        return $time_post - $time_pre;

    }

    public static function createBokningsData(){
        $time_pre = microtime(true);
        self::_flushBokningar();

        $bokningar = Bokning::getAll();

        foreach($bokningar as $bokning){
            $antalElever = self::getAntalEleverForKurs($bokning->kursId);
            //print "+".$antalElever."+";

            $dataArr[self::TABLE_BOKNING_FN_ID] = $bokning->id;
            $dataArr[self::TABLE_BOKNING_FN_ANTAL_BOCKER] = $antalElever;

            try {
                self::_save(self::TABLE_BOKNING, $bokning, $dataArr);
            } catch(Exception $e) {
                print $e->getMessage();
            }
        }
        $time_post = microtime(true);
        return $time_post - $time_pre;

    }

    public static function createElevboksData(){
        $time_pre = microtime(true);
        self::_flushElevbocker();

        $elever = Elev::getAll();

        foreach($elever as $elev){
            $kurser = [];

            $elevKurser = Elev::getKurser($elev->id);
            foreach($elevKurser as $kurs){
                if(!in_array($kurs, $kurser)){
                    array_push($kurser, $kurs);
                }
            }

            $boklist = [];
            foreach($kurser as $kurs){
                if(!$kurs->isOld()){
                    $kursBocker = Kurs::getBocker($kurs->id);
                    foreach($kursBocker as $bok){
                        $item = ["kurs" => $kurs, "bok" => $bok];
                        if(!in_array($item, $boklist)){
                            array_push($boklist, $item);
                        }
                    }
                }
            }

            foreach($boklist as $item){
                $bokningsID = Bokning::getId($item["kurs"]->id, $item["bok"]->id);

                $dataArr[self::TABLE_ELEVBOCKER_FN_ELEVID] = "'".$elev->id."'";
                $dataArr[self::TABLE_ELEVBOCKER_FN_BOKNINGSID] = $bokningsID;
                $dataArr[self::TABLE_ELEVBOCKER_FN_BOKID] = "'".$item["bok"]->id."'";
                $dataArr[self::TABLE_ELEVBOCKER_FN_KURSID] = "'".$item["kurs"]->id."'";
                $dataArr[self::TABLE_ELEVBOCKER_FN_ELEVNAMN] = "'".$elev->namn."'";
                $dataArr[self::TABLE_ELEVBOCKER_FN_ELEVKLASS] = "'".$elev->klass."'";
                $dataArr[self::TABLE_ELEVBOCKER_FN_UT] = "'".$item["kurs"]->startTermin->id."'";
                $dataArr[self::TABLE_ELEVBOCKER_FN_IN] = "'".$item["kurs"]->slutTermin->id."'";

                try {
                    self::_save(self::TABLE_ELEVBOCKER, null, $dataArr);
                } catch(Exception $e) {
                    print $e->getMessage();
                }
            }
        }

        $time_post = microtime(true);
        return $time_post - $time_pre;

    }

    public static function update(){
        if(self::isUpdatable()){
            self::createKursData();
            self::createBokningsData();
            self::createElevboksData();
        }
    }

    public static function isUpdatable(){
        $timeDiff =  self::getTimediffSinceLastUpdate();
        $mins = round(abs($timeDiff/(60)));;

        if($mins > Config::DATALAGER_MIN_CACHE_TIME){
            return true;
        } else {
            return false;
        }
    }

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
        $result = self::_getAllAsResurs(self::TABLE_KURS);
        $kurs = mysqli_fetch_assoc($result);
        //print_r($kurs[self::TABLE_KURS_FN_CREATED]);
        $timeUpdate = strtotime($kurs[self::TABLE_KURS_FN_CREATED]);

        return $timeUpdate;
    }
}