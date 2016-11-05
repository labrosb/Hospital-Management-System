<?php
	// Retrieves the nesesairy information from the database
	// to send the corresponding information to the client
	// to struct the new event insertion form accordingly
	
	session_start();	
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	
	
	check_if_doctor_manager();			// Checking session to prevent unauthorized access

	if(!check_and_update_session())		// Checking if session has expired and updates timout and id
	{	
		echo json_encode('EXPIRED');	// or destoys it and prevents access
		exit;
	}
	
	$action = $_POST['action'];	
	
	$con = DB_Connect();					// Connecting to database

	
	if($action == "basic_lists")			// If the basic form is to be stuctured
	{
		$event_types = event_types();		// Retrieves all event types
		$cnt=0;
		foreach ($event_types as $reason) 	
		{		
			$reasons[$cnt]['Id'] = $reason['id'];								// Isolates id and name
			$reasons[$cnt]['name'] = $reason['en'];	
			
			if($reasons[$cnt]['Id'] == 4 || $reasons[$cnt]['Id'] == 5 || $reasons[$cnt]['Id'] == 6)
			{
				$reasons[$cnt]['option1'] = true;								// Defines option boolean variables for each 
			}																	// of the reasons retrieved to hide or show to the 
			else{ 																// to hide or show to the  corresponding sub form options  
				$reasons[$cnt]['option1'] = false;								// upon selection	
			}
			if($reasons[$cnt]['Id'] == 5 || $reasons[$cnt]['Id'] == 6 )
			{
				$reasons[$cnt]['option2'] = true;
			}
			else{
				$reasons[$cnt]['option2'] = false;
			}
			if($reasons[$cnt]['Id'] == 4)
			{
				$reasons[$cnt]['patient'] = true;
				$reasons[$cnt]['ward_details'] = true;				
			}
			else{
				$reasons[$cnt]['patient'] = false;
			}
			$reasons[$cnt]['parentId'] = 0;
			$reasons[$cnt]['label'] = 'Event';
			$cnt++;
		}
		
		$choicelist['parent'] = $reasons;				// The parent choice variable - the previous choices assigned	

		$exams = exam_types();							// Retrieves all examination types 
		usort($exams, 'compareByNameENG');
		
		$cnt=0;
		foreach ($exams as $exam) 						// isolates exams info
		{	
			$examination_types[$cnt]['Id'] = $exam['id'];
			$examination_types[$cnt]['name'] = $exam['en'];
			$examination_types[$cnt]['parentId'] = 4;	
			$examination_types[$cnt]['label'] = 'Exam type';						
			$cnt++;			
		}
		
		$choicelist['child'] = $examination_types;		// The child choice variable - Examination types assigned

		$units = units();								// Retrieves all examination types 
		usort($units, 'compareByNameENG');
		$cnt2=0;
		foreach ($units as $unit) 						// Constracting 5th and 6th choice together
		{						
			$units[$cnt]['Id'] = $unit['id'];
			$units[$cnt]['name'] = $unit['en'];
			$units[$cnt]['parentId'] = 5;	
			$units[$cnt]['label'] = 'Unit';
			$buildings[0]['building_id'] = 1;			// I set all examination wards to Building A
			$buildings[0]['parentId'] = 5;				// If more buildings exist, an additional query will be added here
			$buildings[0]['name'] = "Building Α";
			$buildings[0]['label'] = 'Building';
			$cnt++;		
			$units[$cnt]['Id'] = $unit['id'];
			$units[$cnt]['name'] = $unit['en'];
			$units[$cnt]['parentId'] = 6;	
			$units[$cnt]['label'] = 'Unit';
			$buildings[1]['building_id'] = 1;		 
			$buildings[1]['parentId'] = 6;			 
			$buildings[1]['name'] = "Building Α";
			$buildings[1]['label'] = 'Building';
			$cnt++;			
		}

		$choicelist['child'] = $choicelist['child'] + $units;	// The child choice variable - units info from the above variables included		
		
		$choicelist['child2'] = $buildings;						// The sub-child choice variable - Buildings info from the above variables assigned		
			
	}
	else if ($action == "child2")								// Third-level list
	{		 
		$child_choice = $_POST['child_choice'];
											
		$building_id = get_building_Id_from_unit($child_choice);	
								
		$cnt2=0;
		$buildings = get_building_Info($building_id);
						
		$types[$cnt]['building_name'] = $buildings['en'];
		$types[$cnt]['building_address'] = $buildings['Address'];
		
		$cnt2++;
		
	}
	
	$choicelist['child2'] = $buildings;		// The sub-child choice variable - Buildings info from the above variables assigned
	
	$choicelist['length'] = $cnt2;			// The full choices variable length

	$con=null;
	
	$output=json_encode($choicelist);			// Returns results to the client in JSON format
	
	exit($output);
?>