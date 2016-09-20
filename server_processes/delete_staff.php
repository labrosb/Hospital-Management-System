<?php
	session_start();

	$id=$_POST['id'];
	
	include("config.inc.php");
	

	$con = mysql_connect(dbServer,dbUser,dbPass) or die('cannot connect to database');

	mysql_select_db(dbDatabase) or die('cannot connect to database');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 

	mysql_query("DELETE FROM medical_staff WHERE Id = $id ") or die("delete medical_staff");
	mysql_query("DELETE FROM unavailable_staff WHERE Id = $id ") or die("delete unavailable_staff");

	echo 'ok';
	
	mysql_close($con);
?>