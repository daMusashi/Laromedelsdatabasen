<?php

require_once("config.php");

Config::$DB_LINK  = mysqli_connect(Config::DB_HOST, Config::DB_USER, Config::DB_PASS, Config::DB_NAME);


if(mysqli_connect_errno()){
	Config::$DB_LINK = null;
	$DB_SUCCESS = false;	
	$db_error = $db_error . mysqli_error();
} else {
	mysqli_query(Config::$DB_LINK, 'SET character_set_results=utf8');
	mysqli_query(Config::$DB_LINK, 'SET names=utf8');
	mysqli_query(Config::$DB_LINK, 'SET character_set_client=utf8');
	mysqli_query(Config::$DB_LINK, 'SET character_set_connection=utf8');
	mysqli_query(Config::$DB_LINK, 'SET character_set_results=utf8');
	mysqli_query(Config::$DB_LINK, 'SET collation_connection=utf8_general_ci');
}

?>