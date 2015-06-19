<?php 
			
function getLogoutHTML(){
	print "<span>Du är inloggad som APA</span><a href=\"#\" onclick=\"logout();\">Logga ut</a>";
}
function getLoginHTML(){
	print "<button type=\"button\" class=\"btn btn-success btn-sm\" data-toggle=\"modal\" data-target=\"#loginModal\">Logga in</button>";
}

function logout_user(){
	session_unset();     // töm alla variabler
    session_destroy();   // förstör disksparad session-data
	
	return true;
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