<?php
	// Searches for the number of exams assigned to the patient, 
	// results have been inspected to them and returns and have
	// not viewed yet. 
	// The number of them to appear in the notifications
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");	// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		

	check_if_patient();					// Checking session to prevent unauthorized access							
	
	if(!check_session_timer())			// Checking if session has expired 
	{			
		$output = json_encode('EXPIRED');	
		exit($output);				
	}
	
	$id = $_SESSION['id'];
	
	$con = DB_Connect();	// Connecting to database 
	
	//Search query
	$query='SELECT Exam_type_id FROM examinations 
			WHERE Patient_id = :id AND Confirmed = 1 AND Viewed = 0';
	try {	
		$stmt = $con->prepare($query);		
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		
		$rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		$num_rows = count( $rows );		// Retrieves the number of notifications
	
		$con=null;
	}
	catch (PDOException $e) { die($e); }	
	
	$output = json_encode($num_rows);	// Returns result to the client in JSON format
	
	exit($output);	
?>
