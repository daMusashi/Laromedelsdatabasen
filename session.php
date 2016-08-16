<?php
session_start();

// cron och datalager
if(empty($_SESSION["datalagerDataChanged"])){ // sätts till true vid dataändringar, som aktiverar en uppdatering i bakgrunden
    $_SESSION["datalagerDataChanged"] = false;
}

// undersöker vilken config-nav GET-nav motsvaras av
// Hittas ingen används förvald, $CONFIG["defaultPrimNav"] ("hemsidan")

$default_nav = CONFIG::PARAM_DEFAULT_NAV;
if(Config::SIMPLE_MODE){
    $default_nav = CONFIG::PARAM_DEFAULT_NAV_SIMPLE_MODE;
}


if(isset($_GET[CONFIG::PARAM_NAV])){
    $_SESSION["currentNavKey"] = false;
    foreach($NAV as $key => $navItem){
        if($key == $_GET[CONFIG::PARAM_NAV]){
            $_SESSION["currentNavKey"] = $key;
        }
    }
    // Om felaktig GET-nav
    if(!$_SESSION["currentNavKey"]){
        $_SESSION["currentNavKey"] = $default_nav;
    }
} else {
    // Om ingen GET-nav
    $_SESSION["currentNavKey"] = $default_nav;
}

// sid-specifika sessionsvariabler
if(!isset($_SESSION["bok-urval"])){
    $_SESSION["bok-urval"] = "*";
}
if(!isset($_SESSION["active-termin"])){
    $activeTermin = Termin::getCurrentTermin();
    $_SESSION["active-termin"] = $activeTermin->id;
}
if(!isset($_SESSION["bok-termin"])){ // för tillgänglighet
    $activeTermin = Termin::getCurrentTermin();
    $_SESSION["bok-termin"] = $activeTermin->id;
}
if(!isset($_SESSION["bokning-bokare"])){ // för tillgänglighet
    $_SESSION["bokning-bokare"] = "*";
}

?>