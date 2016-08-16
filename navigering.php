<?php

// Skapar menu-items, delar uppp i menyer, sorterar bort hidden och ej behöriga
$menu = [];

$whitelist = array_keys($NAV);
if(Config::SIMPLE_MODE){
	$whitelist = Config::$SIMPLE_MODE_NAVS;
}

//var_dump($whitelist);

foreach ($NAV as $key => $item) {

	//print "<p>item_label:".$item["label"]."</p>";
	//print "<p>item_key:".$key."</p>";

	if(in_array($key, $whitelist)){

		
		if(($item["label"] != "<hidden>") && (isset($item["place"]))){
			//print "<p>".$item["place"]."</p>";
			if(!isset($menu[$item["place"]])){
				$menu[$item["place"]] = [];
			}
			$item["key"] = $key;

			
			
			switch($item["roll"]){
				case "admin":
					if(isAdmin()){
						array_push($menu[$item["place"]], $item);
					}
					break;
				case "dev":
					if(isDev()){
						array_push($menu[$item["place"]], $item);
					}
					break;
				case "user":
					if(isLoggedin()){
						array_push($menu[$item["place"]], $item);
					}
					break;
				default:
					array_push($menu[$item["place"]], $item);
			}
		}
	}

}
//var_dump($menu);
// skriver ut navbar
print "<div  class=\"container\">";

print "<ul class=\"nav navbar-nav navbar-left\" role=\"menu\">";
//var_dump($menu);
printMenu($menu["main"]);
print "</ul>";


print "<ul class=\"nav navbar-nav navbar-right\">";
	if(count($menu["admin"]) > 0){
		print "<li class=\"dropdown\">";
			print "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">Administratör <span class=\"caret\"></span></a>";
			print "<ul class=\"dropdown-menu\" role=\"menu\">";
				printMenu($menu["admin"]);
			print "</ul>";
		print "</li>";
	}
	print "<li>";
		print getNavLoginHTML();
	print "</li>";
print "</ul>";


print "</div>";



function getNavLoginHTML(){
	if(isLoggedin()){
		return _getNavlogoutHTML();
	} else {
		return _getNavLoginHTML();
	}
}

function _getNavlogoutHTML(){
	return "<p class=\"navbar-text navbar-right\">Du är inloggad med <strong>rollen ".getCurrentRightsLabel()."</strong> - <a href=\"#\" onclick=\"logout();\">Logga ut</a></p>";
}
function _getNavLoginHTML(){
	return "<button type=\"button\" class=\"btn btn-success navbar-btn navbar-right\" data-toggle=\"modal\" data-target=\"#loginModal\">Logga in</button>";
}


function createNavLink($navKey, $label, $linkWrapper = ""){
	global $CONFIG;

	$class="";
	$wrappStart = "";
	$wrappEnd = "";
	
	if($_SESSION["currentNavKey"] == $navKey){
			$class=" class=\"active\"";
	}
	if(isset($linkWrapper)){
		$wrappStart = "<$linkWrapper$class role=\"presentation\">";
		$wrappEnd = "</$linkWrapper>";
	}

	$html = "$wrappStart<a$class href=\"" . CONFIG::BASE_URL ."?" . CONFIG::PARAM_NAV  . "=$navKey\">$label</a>$wrappEnd";
	return $html;
}

function printMenu($itemArray, $linkWrapper = "li"){
	
	foreach($itemArray as $item){
		//var_dump($item);
		print createNavLink($item["key"], $item["label"], $linkWrapper);
	}
}

?>


