<?php
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");
	
	check_if_manager();						// Checking session to prevent unauthorized access		

	if(!check_and_update_session())			// Checking if session has expired and updates timout and id
	{	
		echo json_encode('EXPIRED');		// or destoys it and prevents access
		exit;	
	}
	
	$con = DB_Connect();					// Connecting to database 
	
	$unit_id = $_GET['id'];
	$types = null;
															// Retrieves all the call-duty events of the chosen unit
	$query ='SELECT unavailable_staff.Id, unavailable_staff.Staff_id, unavailable_staff.Start, 
			unavailable_staff.End, unavailable_staff.Reason, medical_staff.Name, medical_staff.Surname,
			on_duty.Comments
			FROM unavailable_staff 
			INNER JOIN medical_staff ON medical_staff.Id = unavailable_staff.Staff_id
			INNER JOIN on_duty ON on_duty.Id = unavailable_staff.Id
			WHERE unavailable_staff.Reason = 5 AND unavailable_staff.Specialty_id = :id'; 
	try {	
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$unit_id);
		$stmt->execute();
		
		$cnt=0;
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 	 
		{			
			$types[$cnt]['id'] = $row['Id'];					// Creates the results in the needed form
			$types[$cnt]['start_date'] = $row['Start'];			// to create the lists on the client
			$types[$cnt]['end_date'] = $row['End'];
			$types[$cnt]['staff_id'] = $row['Staff_id'];		
			$types[$cnt]['parent'] = $row['Reason'];
			$types[$cnt]['more'] = $row['Comments'];
																
			$types[$cnt]['parent_text'] = get_event_name_by_id($row['Reason'], 'en');	// Event's name
			$types[$cnt]['text'] = $types[$cnt]['parent_text'];

			
			$types[$cnt]['name'] = $row['Name'];				// Retrieves the doctors name of each event
			$types[$cnt]['surname'] = $row['Surname'];
			
			$types[$cnt]['building_id'] = get_building_Id_from_unit($unit_id);		// Gets building id from unit id
											
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['name']." ".$types[$cnt]['surname'];				
			$cnt++;
		}
		
	}
	catch (PDOException $e) { die($e); }	


																// Retrieves all the Work shift events of the chosen unit
	$query ='SELECT work_shifts.Id, work_shifts.Staff_id, work_shifts.Start_date,
			work_shifts.End_date, work_shifts.Comments, medical_staff.Name, medical_staff.Surname
			FROM work_shifts
			INNER JOIN medical_staff ON medical_staff.Id = work_shifts.Staff_id
			WHERE work_shifts.unit_id = :id';

	try {	
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$unit_id);
		$stmt->execute();
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 	 
		{												// Creates the results in the needed form
			$types[$cnt]['id'] = $row['Id'];			// to create the lists on the client
			$types[$cnt]['start_date'] = $row['Start_date'];
			$types[$cnt]['end_date'] = $row['End_date'];
			$types[$cnt]['staff_id'] = $row['Staff_id'];				
			$types[$cnt]['parent'] = 6;
			$types[$cnt]['parent_text'] = 'Work shift';
			$types[$cnt]['text'] = 'Work shift';
			$types[$cnt]['more'] = $row['Comments'];
			$types[$cnt]['name']= $row['Name'];			// Retrieves the doctors name of each event
			$types[$cnt]['surname'] = $row['Surname'];	// to be shown instead of doctor's code)
																						
			$types[$cnt]['building_id'] = get_building_Id_from_unit($unit_id);		// Gets building id from unit id
					
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['name'].' '.$types[$cnt]['surname'];
			
			$cnt++;
		}
	}
	catch (PDOException $e) { die($e); }	
	
	$con=null;

	$output=json_encode($types);
	
	exit($output);
?>