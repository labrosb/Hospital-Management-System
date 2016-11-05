<?php
	// Retrieves and returns all the events (past and future) assigned to the given doctor.
	// Time limits have not been considered but the code is structed so it will be easily
	// implemented if nesesairy.

	session_start();	
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	
	
	check_if_doctor_manager();			// Checking session to prevent unauthorized access

	if(!check_and_update_session())		// Checking if session has expired and updates timout and id
	{	
		$output=json_encode('EXPIRED');	// or destoys it and prevents access
		exit($output);
	}
	
	$con = DB_Connect();						// Connecting to database 
	
	$id = $_GET['id'];   						//Doctor's id
	
												// Query retrieves all events for the given doctor
												// along with the events information to be presented
												// depending on the type of the event
												//(time, patients info, wards, units etc)
	$query='SELECT unavailable_staff.Id, unavailable_staff.Start, 
			unavailable_staff.End, unavailable_staff.Reason,
			examinations.Exam_type_id, examinations.Patient_id, examinations.Comments AS examComm,
			examimation_wards.Number, examimation_wards.Unit_id,
			patients.Name, patients.Surname,
			on_duty.Unit_id AS Unit_id_b, on_duty.Comments AS dutyComm,
			vacation.Comments AS leaveComm, days_off.Comments AS dayOffComm
			FROM unavailable_staff 
			LEFT JOIN examinations ON examinations.Exam_id = unavailable_staff.Id
			LEFT JOIN patients ON patients.Id =  examinations.Patient_id
			LEFT JOIN examimation_wards ON examimation_wards.Id = examinations.ward_id
			LEFT JOIN on_duty ON on_duty.Id = unavailable_staff.Id
			LEFT JOIN vacation ON vacation.Id = unavailable_staff.Id
			LEFT JOIN days_off ON days_off.Id = unavailable_staff.Id
			WHERE unavailable_staff.Staff_id = :id';

	try {
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$id);
		$stmt->execute();

		$cnt=0;
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 					
		{
			$types[$cnt]['id'] = $row['Id'];											 //	Retrieves main event info
			$types[$cnt]['start_date'] = $row['Start'];
			$types[$cnt]['end_date'] = $row['End'];
			$types[$cnt]['parent'] = $row['Reason'];
			
			
			$types[$cnt]['parent_text'] = get_event_name_by_id($row['Reason'],'en');	 // Event's name
			$types[$cnt]['text'] = $types[$cnt]['parent_text'];			

			if($types[$cnt]['parent'] == 1 || $types[$cnt]['parent'] == 2)
			{
				$types[$cnt]['more'] = $row['leaveComm'];
			}
			else if($types[$cnt]['parent'] == 3)
			{
				$types[$cnt]['more'] = $row['dayOffComm'];
			}
			else if($types[$cnt]['parent'] == 4)
			{
				$exam_type_id = $row['Exam_type_id'];										 // Exam type id				
				$types[$cnt]['exam_type_id'] = $exam_type_id;
				$types[$cnt]['exam_name'] = get_examination_name_by_id($exam_type_id,'en');  // Exam name	
				$types[$cnt]['more'] = $row['examComm'];
				
				$types[$cnt]['patient_id'] = $row['Patient_id'];
				$types[$cnt]['patient_name'] = $row['Name'];								 // Patient Name
				$types[$cnt]['patient_surname'] = $row['Surname'];			
				
				$types[$cnt]['number'] = $row['Number'];									 // Ward number	
					
				$unit_id = $row['Unit_id'];													 // Unit id
				$types[$cnt]['unit_name'] = get_unit_name_by_id($unit_id , 'en');			 // Gets unit name from the id

				$building_id = get_building_Id_from_unit($unit_id);							 // Gets building id from unit id
				$buildings = get_building_Info($building_id);
				$types[$cnt]['building_name'] = $buildings['en'];							 // Building's name
				$types[$cnt]['building_address'] = $buildings['Address'];				
				
				$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['exam_name'];	
			}
			else if($types[$cnt]['parent'] == 5)
			{
				$unit_id = $row['Unit_id_b'];													 // Unit id
				$types[$cnt]['unit_id'] = $row['Unit_id_b'];
				$types[$cnt]['unit_name'] = get_unit_name_by_id($unit_id , 'en');				 // Gets unit name from the id
				$types[$cnt]['more'] = $row['dutyComm'];
				
				$building_id = get_building_Id_from_unit($unit_id);								 // Gets building id from unit id
				$buildings = get_building_Info($building_id);
				
				$types[$cnt]['building_name'] = $buildings['en'];								 // Building's name
				$types[$cnt]['building_address'] = $buildings['Address'];		
																								// Merging info
				$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['unit_name'];	// Event's outer text
				$types[$cnt]['text'] = $types[$cnt]['text']. ' , '.$types[$cnt]['building_name'];				
			}

			$cnt++;
		}
		
	}
	catch (PDOException $e) { die($e); }

	
	//---Work shifts---// 
					
	$query = 'SELECT Id, Start_date, End_date, Unit_id, Comments FROM work_shifts WHERE Staff_id = :id';

	try {
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
					
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))									// Event main info 
		{
			$types[$cnt]['id'] = $row['Id'];
			$types[$cnt]['start_date'] = $row['Start_date'];
			$types[$cnt]['end_date'] = $row['End_date'];

			$types[$cnt]['parent'] = 6;
			$types[$cnt]['parent_text'] = 'Work shift';
			$types[$cnt]['text'] = 'Work shift';
			$types[$cnt]['more'] = $row['Comments'];				

			$types[$cnt]['unit_id'] = $row['Unit_id'];
			$unit_id = $row['Unit_id'];
			
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

	$output=json_encode($types);	// Returns result to the client in JSON format
	
	exit($output);
?>