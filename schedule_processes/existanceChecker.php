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
	
	
	$who = $_POST['who'];
	$Id = $_POST['id'];	
	
	if ($who == 'patient'){
		$sql = mysql_query("SELECT * FROM patients WHERE Id='$Id' LIMIT 1") or die("cannot connect to patients");
		$num_rows = mysql_num_rows($sql);	
		if ( $num_rows == 0 ) {  
			echo "NOT EXISTS";
		}
		else{
			echo "EXISTS";
		}
	}
	else if ($who == 'doctor'){
		$sql = mysql_query("SELECT * FROM medical_staff WHERE Id='$Id' LIMIT 1") or die("cannot connect to medical_staff");
		$num_rows = mysql_num_rows($sql);	
		if ( $num_rows == 0 ) {  
			echo "NOT EXISTS";
		}
		else{
			echo "EXISTS";
		}
	}
	else if ($who == 'doctor_sess'){
		$sql = mysql_query("SELECT * FROM medical_staff WHERE Id='$Id' LIMIT 1") or die("cannot connect to medical_staff");
		$num_rows = mysql_num_rows($sql);	
		if ( $num_rows == 0 ) {  
			echo "NOT EXISTS";
		}
		else{
			echo "EXISTS";
			$_SESSION['doctor_id'] = $Id;
		}
	}
	
	
	mysql_close($con);
	exit;