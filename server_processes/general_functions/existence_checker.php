<?php
	// Checks if a given entity exists on the system given
    // the type (doctor, patient) and the ID
	
	session_start();

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		

	check_if_manager();					// Checking session to prevent unauthorized access

	if(!check_and_update_session())		// Checking if session has expired and updates timout and id
	{	
		echo "EXPIRED";					// or destoys it and prevents access
		exit;				
	}
	
	$con = DB_Connect();				// Connecting to database
	
	$who = $_POST['who'];
	$Id = $_POST['id'];	
	
	if ($who == 'patient')				// If searching for patient
	{
		$query= 'SELECT * FROM patients WHERE Id = :id LIMIT 1';
	}
	else if ($who == 'doctor')			// If searching for doctor
	{
		$query ='SELECT * FROM medical_staff WHERE Id = :id LIMIT 1'; 	
	}	
	
	try {	
		$stmt = $con->prepare($query);	
		$stmt->bindParam(':id',$Id);	// Passes id parameter to query
		$stmt->execute();				// Executes search query
		
		$rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		$num_rows = count( $rows );		
		
		if ( $num_rows == 0 ) 			// Result
		{  
			exit("NOT EXISTS");
		}
		else{
			exit("EXISTS");
		}		
		$con=null;
	}		
	catch (PDOException $e) { die($e); }
?>