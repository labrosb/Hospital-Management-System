<?php
	// Retrieves doctor's data
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
	
	check_if_doctor();			// Checking session to prevent unauthorized access

	$ID = $_SESSION['id'];
	
	$con = DB_Connect();		// Connecting to database		

	$query = 'SELECT Address, City, Postal_code, Home_phone, Mobile_phone, Email, Resume 
			  FROM medical_staff WHERE Id = :id LIMIT 1';
	
	try {	
		$stmt = $con->prepare($query);		
		$stmt->bindParam(':id',$ID);
		$stmt->execute();
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))		// Retrieves users data		
		{	
			$staffData = $row;
		}
		
		$con=null;
	}
	catch (PDOException $e) { die($e); }
	
	$output = json_encode($staffData);
	
	exit($output);
?>