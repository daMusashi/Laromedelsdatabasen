<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/jquery-1.11.0.min.js"></script>
<title>Dev toools</title>
<?php
	require_once("../config.php");
	require_once("../connect.php");
	require_once("../db_functions.php");
	require_once("../form_functions.php");
	require_once("../functions.php");
?>
<?php
	// temporära fixa funktioner
	
	// Kopierar utläsår till inläsår
	function putUtLasar2InLasar(){
		$q1 = "SELECT * FROM kurser_bocker";

		$result = mysql_query($q1);
		if(!$result){
			print "<strong>MYSL_QUERY-FEL!!</strong>, q:$q1, fel: " . mysql_error();
		}
		
		while ($row = mysql_fetch_assoc($result)) {
    		//var_dump($row);
			$q2 = "UPDATE kurser_bocker SET in_lasar_id='" . $row['ut_lasar_id'] . "' WHERE bok_id='" . $row['bok_id'] . "' AND kurs_id='" . $row['kurs_id'] . "'";
			print "<p>q2: " . $q2 . "</p>";
			print "<p>Uppdaterar <" . $row['bok_id'] . "|" . $row['kurs_id'] . ">";
			
			$result2 = mysql_query($q2);
			if(!$result2){
				print "<strong>MYSL_QUERY-FEL!!</strong>, q:$q, fel: " . mysql_error();
			}
		}
	}
?>
<style>
	.resultat{ background-color: yellow; }
</style>
</head>

<body>
<?php include("printTabeller.php") ?>
<div class="resultat">
<?php // HAR ANVÄNT, BEHÖVS NOG INTE IGEN putUtLasar2InLasar(); ?>
</div>
</body>
</html>