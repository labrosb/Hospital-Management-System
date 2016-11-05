<?php
	// Deletes all doctor's information from the system
	
	session_start();	
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions

	check_if_manager();					// Checking session to prevent unauthorized access
	
	if(!check_and_update_session())
	{									// Checking if session has expired and updates timout and id
		exit("EXPIRED");				// or destoys it and prevents access
	}
	
	$id=$_POST['id'];
	
	$con = DB_Connect();				// Connecting to database	

	// Deletes doctor's personal information
	$query_1 ='DELETE FROM medical_staff WHERE Id = :id';

	// Deletes log-in registration
	$query_2 ='DELETE FROM users WHERE Username = :id';
	
	// Deletes doctor's past and future unavailable times
	$query_3 ='DELETE FROM unavailable_staff WHERE Id = :id';

	try {	
		$stmt = $con->prepare($query_1);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		
		$stmt = $con->prepare($query_2);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		
		$stmt = $con->prepare($query_3);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
	}
	catch (PDOException $e) { die($e); }	
	
	$con=null;
	
	exit("ok");							// Succeed
?>