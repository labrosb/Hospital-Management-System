<?php
	// Detects and returns the doctors who's specialty corresponds to the selected examination types
	// and have no other tasks, or its planned to be absent in the selected date/time
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	
	
	check_if_patient();									// Checking session to prevent unauthorized access

	if(!check_and_update_session())						// Checking if session has expired and updates timout and id
	{													// or destoys it and prevents access
		$output = json_encode('EXPIRED');					
		exit($output);
	}
	
	date_default_timezone_set('UTC');
	$thisYear = date("Y");

	$lang=$_POST['lang'];								// The current system's language
	
	$mydate=$_POST['date'];
	$time=$_POST['time'];
	$exams_type=$_POST['exams_type'];
	$doctors = null;	
	$dateArray = explode( "/" , $mydate);
	$timeArray = explode( ":" , $time);
	
														// Merges the date and time and tranforms them 
														// to a recognizable from the database form
	if(isset($dateArray[2]) &&  isset($timeArray[1]) )
	{
		$date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0]." ".$timeArray[0].":".$timeArray[1].":00";
	}
	if (!isset($date))
	{
		$output = json_encode('DATE ERROR');			// Returns result to the client in JSON format
		exit($output);
	}	
														// Gets exam id using the exam name
														// and speciality id using the exam id
	$exam_id = get_examination_id_by_name($exams_type, $lang);			
	$staff_category_id = get_speciality_id_from_exam_id($exam_id, $lang);
	
	if (!$staff_category_id)
	{
		$output = json_encode('EXAMS ERROR');			// Returns result to the client in JSON format
		exit($output);
	}
	
	$con = DB_Connect();								// Connecting to database
	
														// Selects the doctors who correspond to the
														// exam type, have a work shift scheduled in the
														// selected time and have no other tasks scheduled
														// during that time, all in a single query
	$query ='SELECT Id, Name, Surname, Photo, Specialty_id, Sex, Birth_Date, Resume FROM medical_staff 
			WHERE Specialty_id = ? 
				AND Id NOT IN (
					SELECT Staff_id FROM unavailable_staff 
					WHERE Specialty_id = ? AND ? BETWEEN Start AND End ) 
				AND Id IN ( 
					SELECT Staff_id FROM work_shifts 
						WHERE ? BETWEEN Start_date AND End_date )'; 
									
	try {	
		$stmt = $con->prepare($query);		
		$stmt->execute(array($staff_category_id, $staff_category_id, $date, $date));
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))		// Retrieves users data		
		{	
			$doctors[] = $row;
		}
		
		$con=null;
	}
	catch (PDOException $e) { die($e); }	
	
	if ( sizeof($doctors) == 0 ) 						// Id no doctor is returned
	{
		$con=null;
		$output = json_encode('NULL');
		exit($output);
	}
	
	$cnt = 0;
	foreach ($doctors as $value) 						// Re-structs birth date to a presentable form
	{						
		$birthDateArray = explode( " " , $value['Birth_Date']);
		$birthYear = $birthDateArray[0];	
		$doctors[$cnt]['Age'] = $thisYear - $birthYear;
		$specialty_id = $value['Specialty_id'];
				
		$doctors[$cnt]['Specialty'] = get_speciality_name_from_id($specialty_id, $lang);	// Retrieves speciality name
						
		$cnt++;
	}

	$con=null;	
	
	$output = json_encode($doctors);					// Returns result to the client in JSON format	
	exit($output);;
?>