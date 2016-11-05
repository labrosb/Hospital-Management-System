<?php
	//Inserts the examination result in the database and sets the result as "confirmed" to be excuded from
	//the patients notifications and included in the patient's ones
 
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions
	
	session_start();
	
	check_if_doctor();					// Checking session to prevent unauthorized access
	
	if(!check_and_update_session())		// Checking if session has expired and updates timout and id
	{									// or destoys it and prevents access
		exit('EXPIRED');			
	}
	
	$tableId = $_POST['tableId'];
	$text = $_POST['text'];		

	$con = DB_Connect();				// Connecting to database 
	
	$query ='UPDATE examinations SET Confirmed = 1, Results = :result WHERE Exam_id = :exam'; 

	try {	
		$stmt = $con->prepare($query);
		$stmt->bindParam(':result',$text);
		$stmt->bindParam(':exam',$tableId);
		$stmt->execute();
	}
	catch (PDOException $e) { die($e); }
	
	$con=null;	
	
	exit("OK");
?>