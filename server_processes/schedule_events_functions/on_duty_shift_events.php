<?php

	session_start();	
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	
	
	check_if_doctor();						// Checking session to prevent unauthorized access

	if(!check_and_update_session())			// Checking if session has expired and updates timout and id
	{	
		echo json_encode('EXPIRED');		// or destoys it and prevents access
		exit;
	}
	
	$con = DB_Connect();					// Connecting to database
	
	$doc_id = $_GET['id'];
	
	try {
		$query ='SELECT unavailable_staff.Id, unavailable_staff.Start, unavailable_staff.End, unavailable_staff.Reason, 
				on_duty.Unit_id, on_duty.Comments
				FROM unavailable_staff
				LEFT JOIN on_duty ON on_duty.Id = unavailable_staff.Id
				WHERE unavailable_staff.Staff_id = :id AND unavailable_staff.Reason = 5';
		
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$doc_id);
		$stmt->execute();
				
		$cnt=0;
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))				// Selects all the events of the selected doctor
		{
			$types[$cnt]['id'] = $row['Id'];
			$types[$cnt]['start_date'] = $row['Start'];
			$types[$cnt]['end_date'] = $row['End'];
			$types[$cnt]['parent'] = $row['Reason'];
			$types[$cnt]['more'] = $row['Comments'];
			
			$types[$cnt]['parent_text'] = get_event_name_by_id($row['Reason'],'en');	// Event's name
			$types[$cnt]['text'] = $types[$cnt]['parent_text'];	
					
			$unit_id = $row['Unit_id'];	
			$types[$cnt]['unit_id'] = $row['Unit_id'];			
			$types[$cnt]['unit_name'] = get_unit_name_by_id($unit_id , 'en');			// Gets unit name from the id
			$types[$cnt]['building_id'] = get_building_Id_from_unit($unit_id);			// Gets building id from unit id			
				
			$building_id = $types[$cnt]['building_id'];		

			$buildings = get_building_Info($building_id);								// Building info
			$types[$cnt]['building_name'] = $buildings['en'];	
			$types[$cnt]['building_address'] = $buildings['Address'];
																						// Merging results
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['unit_name'];
			$types[$cnt]['text'] = $types[$cnt]['text']. ' , '.$types[$cnt]['building_name'];		
		
			$cnt++;
		}
	}
	catch (PDOException $e) { die($e); }

	//---Work-shifts---// 

	$query = 'SELECT Id, Start_date, End_date, Unit_id, Comments FROM work_shifts WHERE Staff_id = :id';

	try {
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$doc_id);
		$stmt->execute();
					
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))									// Event main info 
		{
			$types[$cnt]['id'] = $row['Id'];
			$types[$cnt]['start_date'] = $row['Start_date'];
			$types[$cnt]['end_date'] = $row['End_date'];
			$types[$cnt]['more'] = $row['Comments'];

			$types[$cnt]['parent'] = 6;
			$types[$cnt]['parent_text'] = 'Work shift';
			$types[$cnt]['text'] = 'Work shift';

			$types[$cnt]['unit_id'] = $row['Unit_id'];
			$unit_id = $types[$cnt]['unit_id'];
			
			$types[$cnt]['unit_name'] = get_unit_name_by_id($unit_id ,'en');		// Gets unit name from the id
			$types[$cnt]['building_id'] = get_building_Id_from_unit($unit_id);		// Gets building id from unit id;	
			$building_id = get_building_Id_from_unit($unit_id);								
						
			$buildings = get_building_Info($building_id);
							
			$types[$cnt]['building_name'] = $buildings['en'];
			$types[$cnt]['building_address'] = $buildings['Address'];
				
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['unit_name'];		// Event's outer text
			$types[$cnt]['text'] = $types[$cnt]['text']. ' , '.$types[$cnt]['building_name'];
			
			$cnt++;
		}
		
	}
	catch (PDOException $e) { die($e); }
			
	$con=null;
	
	$output=json_encode($types);		// Returns result to the client in JSON format
	
	exit($output);
?>