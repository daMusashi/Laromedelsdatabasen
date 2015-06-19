<?php

// undersöker vilken config-nav GET-nav motsvaras av
// Hittas ingen används förvald, $CONFIG["defaultPrimNav"] ("hemsidan")

if(isset($_GET[CONFIG::PARAM_NAV])){
	$_SESSION["currentNavKey"] = false;
	foreach($NAV as $key => $navItem){
		if($key == $_GET[CONFIG::PARAM_NAV]){
			$_SESSION["currentNavKey"] = $key;
		}
	}
	// Om felaktig GET-nav
	if(!$_SESSION["currentNavKey"]){
		$_SESSION["currentNavKey"] = $CONFIG["defaultPrimNav"];
	}
} else {
	// Om ingen GET-nav
	$_SESSION["currentNavKey"] = $CONFIG["defaultPrimNav"];
}



// Skapar menu-items, delar uppp i menyer, sorterar bort hidden och ej behöriga
$menu = [];

foreach ($NAV as $key => $item) {
	
	//print "<p>".$item["label"]."</p>";
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
//print_r($menu);
// skriver ut navbar
print "<nav  class=\"navbar navbar-default\" id=\"nav-main\" role=\"navigation\">";

print "<ul class=\"nav navbar-nav\" role=\"menu\">";
//var_dump($menu);
printMenu($menu["main"]);

if(count($menu["admin"]) > 0){
	// skriver ut admin som dropdown
	print "<li>";
	print "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">Admin <span class=\"caret\"></span></a>";
	print "<ul class=\"dropdown-menu navbar-right\" role=\"menu\">";
	printMenu($menu["admin"]);
	print "</ul>";
	print "</li>";
}
print "<li id=\"nav-login\">".navLoginHTML()."</li>";
print "</ul>";
print "</nav>";




function navLoginHTML(){
	include("admin/login_functions.php");
	if($_SESSION["isLoggedin"] == true){
		getLogoutHTML();
	} else {
		getLoginHTML();
	}
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


