<?php
	//Detects and returns the 3 first patient's examinations 
	//that include a result and have not been viewed by the patient yet!
	//Of called repeatively, 3 new examinations will be return each time
	//since the previous ones will have been already marked as "viewed"
	
	session_start();

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");		// Connection to database	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_patient();		// Checking session to prevent unauthorized access			
	
	$ID = $_SESSION['id'];
	
	$con = DB_Connect();	// Connecting to database	

	$query = 'SELECT Home_phone, Mobile_phone, Email, Address, City, Postal_code 
			  FROM patients WHERE Id = :id LIMIT 1';
	
	try {	
		$stmt = $con->prepare($query);		
		$stmt->bindParam(':id',$ID);
		$stmt->execute();
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))		// Retrieves users data		
		{	
			$patientData = $row;
		}
	}
	catch (PDOException $e) { die($e); }
		
	$con=null;
	
	$output = json_encode($patientData);
	
	exit($output);	
?>