<?php

require_once("config.php");

mysqli_report(MYSQLI_REPORT_STRICT);

try {
	CONFIG::$DB_LINK  = new mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASS, Config::DB_NAME);

	mysqli_query(CONFIG::$DB_LINK, 'SET character_set_results=utf8');
	mysqli_query(CONFIG::$DB_LINK, 'SET names=utf8');
	mysqli_query(CONFIG::$DB_LINK, 'SET character_set_client=utf8');
	mysqli_query(CONFIG::$DB_LINK, 'SET character_set_connection=utf8');
	mysqli_query(CONFIG::$DB_LINK, 'SET character_set_results=utf8');
	mysqli_query(CONFIG::$DB_LINK, 'SET collation_connection=utf8_swedish_ci');

} catch (Exception $e) {
	
	CONFIG::$DB_LINK = null;
	CONFIG::$DB_SUCCESS = false;	
	Config::$DB_ERROR = utf8_encode($e->getMessage());
}

?>