<?php
	
	$tableId = $_POST['tableId'];
	$text = $_POST['text'];
	
	
	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	$sql = mysql_query("UPDATE examinations SET Comfirmed='1', Results='$text' WHERE Exam_id='$tableId'") or die("cannot insert to examinations");

	mysql_close($con);	
	
	echo "OK";
	
	//print_r($data);

?>