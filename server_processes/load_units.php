<?php
	session_start();

	include("config.inc.php");
	

	$con = mysql_connect(dbServer,dbUser,dbPass) or die('Connection-ERROR');

	mysql_select_db(dbDatabase) or die('Connection-ERROR'.$dbDatabase.'.');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 

	
	$sql = mysql_query("SELECT Id, Name FROM units ORDER BY Name ASC") or die("cannot connect to units");
	$counter = 0;
	while($row = mysql_fetch_assoc($sql)) {
		$types[] = $row;

	}
	
	mysql_close($con);
	
	echo json_encode($types);

?>