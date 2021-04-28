<?php 

function isLoggedin(){
	if(isset($_SESSION["isLoggedin"])&&$_SESSION["isLoggedin"] == true)	{
		return true;	
	} else {
		return false;	

	}
}

function isAdmin(){
	if(isLoggedin()&&isset($_SESSION["isAdmin"])&&$_SESSION["isAdmin"] == true)	{
		return true;	
	} else {
		return false;	
	}
}

function isDev(){
	if(isLoggedin()&&isset($_SESSION["isDev"])&&$_SESSION["isDev"] == true)	{
		return true;	
	} else {
		return false;	
	}
}


function printNoRights($navRoll){
	$content = "Rättighetsnivå '".getRightsLabelOfNavRoll($navRoll)."' krävs för innehållet. Du har har '".getCurrentRightsLabel();
	HTML_FACTORY::printPanel("danger", "Rättigheter saknas för sidan", $content);
}


function logout_user(){
	session_unset();     // töm alla variabler
    session_destroy();   // förstör disksparad session-data
	
	return true;
}

function getRightsLabelOfNavRoll($navRoll){
	switch($navRoll){
		case "admin":
			$label = "Administratör";
			break;
		case "dev":
			$label = "Utvecklare";
			break;
		case "user":
			$label = "Lärare";
			break;
		default:
			$label = "Ej inloggad";
	}
	return $label;
}
function getCurrentRightsLabel(){
	$label ="ej inloggad";
	if(isLoggedin()){
		$label = "Lärare";	
	}
	if(isAdmin()){
		$label = "Adminstratör";	
	}
	if(isDev()){
		$label = "Utvecklare";	
	}
	
	return $label;
}
			
function login_user($user, $pass){
	$q = "SELECT * FROM users, roller WHERE roll_id = roller.id AND user = '$user' AND pass = '$pass'";
	
	$result = mysqli_query(CONFIG::$DB_LINK, $q);
	
	$_SESSION["isDev"] = false;
	$_SESSION["isAdmin"] = false;
	$_SESSION["isLoggedin"] = false;
	//print "<p>$q</p>";
	
	if(mysqli_num_rows($result) == 1){
		$inloggning = mysqli_fetch_assoc($result);
		
		$_SESSION["loggedinNamn"] = $inloggning["fullt_namn"];
		$_SESSION["loggedinRoll"] = $inloggning["beskrivning"];
	
		$_SESSION["isLoggedin"] = true;
		
		//print var_dump($inloggning);

		switch($inloggning["roll_id"]){
			case "dev":
				$_SESSION["isDev"] = true;
				$_SESSION["isAdmin"] = true;
				break;
			case "admin":
				$_SESSION["isAdmin"] = true;
				break;
		}
		
	
		return true;
	} else {
		return false;
	}
}