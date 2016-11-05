<?php
	// Retrieves doctor's data
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	// To retrieve speciality name		
	
	check_if_manager();						// Checking session to prevent unauthorized access

	if(!check_and_update_session())			// Checking if session has expired and updates timout and id
	{										// or destoys it and prevents access	
		$output=json_encode('EXPIRED');	
		exit($output);
	}
	
	$id = $_POST['id'];
	
	$con = DB_Connect();					// Connecting to database

	$query ='SELECT * FROM medical_staff WHERE Id = :id LIMIT 1';

	try {	
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
				
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 										// Date and time to presentable form
		{
			$doctors[] = $row;
			$specialty_id = $row['Specialty_id'];
			$doctors[0]['Specialty'] = get_speciality_data_inter($specialty_id);		// Retrieves specialty's data-inter 
		}	
	}
	catch (PDOException $e) { die($e); }	

	$con=null;
	
	$output=json_encode($doctors);			//Returnes to the client in JSON format
	
	exit($output);
?>