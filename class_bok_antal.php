<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2015-11-05
 * Time: 00:55
 */
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