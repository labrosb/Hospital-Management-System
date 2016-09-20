<?php
	session_start();
	
	include("../server_processes/config.inc.php");
	
	$con = mysql_connect(dbServer,dbUser,dbPass) or die('Connection-ERROR');

	mysql_select_db(dbDatabase) or die('Connection-ERROR'.$dbDatabase.'.');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 	
	
	$ID = $_SESSION['id'];
	
	$sql = mysql_query("SELECT Home_phone, Mobile_phone, Email, Address, City, Postal_code FROM patients WHERE Id=$ID LIMIT 1");

	while($row = mysql_fetch_assoc($sql)) {
		$patientData[] = $row; 
	}	
	echo json_encode($patientData);
?>