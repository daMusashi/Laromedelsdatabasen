<?php


//http://davidwalsh.name/backup-mysql-database-php
function backup_tables($tables = '*')
{
	global $DB_HOST, $DB_USER, $DB_PASSW, $DB_NAMN;
	
	$host = $DB_HOST;
	$user = $DB_USER;
	$pass= $DB_PASSW;
	$name = $DB_NAMN;
	$exportpath = "admin/backups/";
	
	if($tables == "*"){
		$tname = "";
	} else {
		$tname = "-".$tables;
	}
	$exportfilename = $DB_NAMN . $tname . "_" . date("Y-m-d_H.i.s").".sql";
	
	$respons = array();
	
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name, $link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	$return = "";
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		$num_rows = mysql_num_rows($result);
		$respons[$table] = $num_rows;
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					//$row[$j] = preg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
			
		}
		$return.="\n\n\n";
	}
	
	//save file
	$handle = fopen($exportpath.$exportfilename,'w+');
	fwrite($handle,$return);
	fclose($handle);
	
	print "<p>Läst av följande data (tabell (antal poster)):</p><ul>";
	foreach($respons as $table=>$num)
	{
		print "<li>$table ($num)</li>";
	}
	print "</ul><p>...och sparat i backuppfilen <strong>".$exportpath.$exportfilename."</strong> (ett SQL-Create-skript)</p>";
	//var_dump($respons);
}


// https://help.1and1.com/hosting-c37630/databases-c85147/mysql-database-c37730/importing-and-exporting-mysql-databases-using-php-a777072.html
function testBackup2(){
	global $DB_HOST, $DB_USER, $DB_PASSW, $DB_NAMN;
	
	$mysqlDatabaseName = $DB_NAMN;
	$mysqlUserName = $DB_USER;
	$mysqlPassword = $DB_PASSW;
	$mysqlHostName = $DB_HOST;
	$mysqlExportPath = getcwd()."/admin/backups/".$DB_NAMN . "_" . date("Y-m-d_H-i-s").".sql";
	//$mysqlExportPath = $DB_NAMN . "_" . date("Y-m-d_H.i.s").".sql";

	//DO NOT EDIT BELOW THIS LINE
	//Export the database and output the status to the page
	$command='mysqldump --opt -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' > ' .$mysqlExportPath;
	$output = array();
	//print "<p>".getcwd()."</p>";
	//print "<p>".$mysqlExportPath."</p>";
	exec($command,$output,$worked);
	switch($worked){
		case 0:
			echo 'Database <b>' .$mysqlDatabaseName .'</b> successfully exported to <b>~/' .$mysqlExportPath .'</b>';
			break;
		case 1:
			echo 'There was a warning during the export of <b>' .$mysqlDatabaseName .'</b> to <b>' .$mysqlExportPath .'</b>';
			break;
		case 2:
			echo 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
			break;
	}
}

?>