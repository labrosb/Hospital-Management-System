<?php
	// - Execute the event action selected (create, update or delete event)
	// - Deleting an event includes deleting all the aspects of it, such as decreasing workload, 
	//   release wards etc.
	// - Execute takes care of all the cases such as overwriting events by deleting all the
	//   evens existing in the time that the new one will take place.
	// - Finally In case of new call duty insertion with corresponding day off, the first
	//   available day is detected and the day off is assigned in it along with the call-duty
	// - If a call-duty is deleted, days off connected to it are deleted too.
	
	session_start();

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/email_functions/mail_functions.php");				
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	
	
	check_if_manager();						// Checking session to prevent unauthorized access

	if(!check_and_update_session())			// Checking if session has expired and updates timout and id
	{	
		$output=json_encode('EXPIRED');		// or destoys it and prevents access
		exit($output);
	}

	$con = DB_Connect();					// Connecting to database
	
	$action = $_POST['action'];				// The action that will be executed
	
	$event = $_POST['event'];				// The event that the action will directly affect
				
	$result['result'] = 'error';

	$subReason = null;
	
	// ...If creating new event...
	if (isset($_POST['subReason'])){
		$subReason = $_POST['subReason'];
	}
	if (isset($_POST['comments'])){
		$comments = $_POST['comments'];
	}
	if (isset($_POST['startDate'])){
		$startDate = $_POST['startDate'];
	}	
	if (isset($_POST['endDate'])){
		$endDate = $_POST['endDate'];
	}		
	if (isset($_POST['patient_id'])){
		$patient_id = $_POST['patient_id'];
	}else{
		$patient_id = null;
	}	
	if (isset($_POST['staff_id'])){
		$staff_id = $_POST['staff_id'];
	}	
	if (isset($_POST['events'])){
		$events = $_POST['events'];
	}		
	if (isset($_POST['id'])){
		$id = $_POST['id'];
	}	
	if (isset($_POST['auto_day_off'])){
		$auto_day_off = $_POST['auto_day_off'];
		$result = setAutoDayOff($auto_day_off);
		$dayOff = setAutoDayOff($auto_day_off);
	}	
	
	$result['result'] = 'error';

	// If inserting new event
	if($action == 'insert')
	{
		insertData($staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $dayOff, $con);
	}
	// If deleting event
	else if($action == 'delete')
	{
		$result = deleteData($id, $staff_id, $con);
	}
	// If deleting event
	else if($action == 'update')
	{
		$result = deleteData($id, $staff_id, $con);
		insertData($staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $dayOff, $con);	
	}
	// If overwriting events
	else if($action == 'delete&insert')
	{
		insertData($staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $dayOff, $con);
		
		foreach ($events as &$eventId) 
		{
			$thisId = $eventId['Id'];
			deleteData($thisId, $staff_id, $con);
		}
		// To notify that many events deleted
		$result['multiDelete'] = true;
		
		// Events that are deleted
		$result['deleted'] = $events;	
	}
		
	$con=null;
	
	$result['result'] = 'done';
	
	$output=json_encode($result);	// Returns result to the client in JSON format
	exit($output);
	
///////////// Functions///////////////////////

	//// Function setting flags for the automated day-off to be implemented later on
	function setAutoDayOff($auto_day_off) {
		if($auto_day_off != null)
		{
			if($auto_day_off['dateExistance'] == "true")
			{
				$dayOffStart = $auto_day_off['dayOffStart'];
				$dayOffEnd = $auto_day_off['dayOffEnd'];
				$result['dayOffStart'] = $auto_day_off['dayOffStart'];
				$result['dayOffEnd'] = $auto_day_off['dayOffEnd'];			
				$result['dayOffDate'] = $auto_day_off['dayOffDate'];
				$result['dayOffday'] = $auto_day_off['day'];
				$result['dayOffdaysAfter'] = $auto_day_off['daysAfter'];
				$result['dayOffExistance'] = $auto_day_off['dateExistance'];
			}
			else{
				$result['dayOffExistance'] = $auto_day_off['dateExistance'];				
			}
			return $result;
		}
	}
	
	//// Delete data function
	function deleteData($id, $staff_id, $con) 
	{		
		// Searches in the unavailable staff db table to identify the event type
		// along with the start and end time for the event to be deleted
		$select_query ='SELECT Start, End, Reason FROM unavailable_staff WHERE Id = :id LIMIT 1';
		
		// Searches also in the work-shifts db table because is the only event time
		// that is not included in the unavailable staff
		$select_query_2 ='SELECT Start_date, End_date FROM work_shifts WHERE Id = :id LIMIT 1'; 	
					
		try {
			$stmt = $con->prepare($select_query);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
						
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$start = $row['Start'];
				$end = $row['End'];
				$thisEvent = $row['Reason'];
			}

			$stmt = $con->prepare($select_query_2);
			$stmt->bindParam(':id',$id);
			$stmt->execute();
						
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$start = $row['Start_date'];
				$end = $row['End_date'];
				$thisEvent = 6;
			}
		
		
			// If event is NOT a work shift
			if($thisEvent != 6)
			{
				$delete_query ='DELETE FROM unavailable_staff WHERE Id = :id';
				
				$stmt = $con->prepare($delete_query);
				$stmt->bindParam(':id',$id);
				$stmt->execute();
			}			

			// If event is a leave of a sick leave
			if($thisEvent == 1 || $thisEvent == 2)
			{
				$delete_query ='DELETE FROM vacation WHERE Id = :id';
				
				$stmt = $con->prepare($delete_query);
				$stmt->bindParam(':id',$id);
				$stmt->execute();					
			}
			
			// If event is a day off
			else if($thisEvent == 3)
			{
				$delete_query ='DELETE FROM days_off WHERE Id = :id';			
			
				$stmt = $con->prepare($delete_query);
				$stmt->bindParam(':id',$id);
				$stmt->execute();				
			}
			
			// If event is an examination
			else if($thisEvent == 4)
			{
				//e-mails disabled on this distribution for spam and privacy reasons
				deletedEmailPatient($start, $id, $con); 
				
				// Deletes the examination registration			
				$delete_query ='DELETE FROM examinations WHERE Exam_id = :id';
				
				// Deletes the registration that keeps the ward occupied
				$delete_query_2 ='DELETE FROM unavailable_examination_wards WHERE Event_id = :id';
				
				$stmt = $con->prepare($delete_query);
				$stmt->bindParam(':id',$id);
				$stmt->execute();				
				
				$stmt = $con->prepare($delete_query_2);
				$stmt->bindParam(':id',$id);
				$stmt->execute();	
				
				// Calls the function to decrease the doctor's workload
				setWorkload("dec", $staff_id, $start, $end, $con);			
			}		
			
			//If event is a call-duty
			else if($thisEvent == 5)
			{		
				// Deletes the call-duty
				$delete_query ='DELETE FROM on_duty WHERE Id = :id';
				
				$stmt = $con->prepare($delete_query);
				$stmt->bindParam(':id',$id);
				$stmt->execute();			
				
				// Searches for day-off connected to the call duty
				$sql_setId = 'SELECT Id, Leave_date FROM days_off WHERE Parent_id = :id LIMIT 1'; 
				
				$stmt = $con->prepare($sql_setId);
				$stmt->bindParam(':id',$id);
				$stmt->execute();					
				
				$found = false;			
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					$event_id = $row['Id'];
					$dayOffTime = $row['Leave_date'];
					$found = true;
				}
				//If day-off exists
				if($found)
				{			
					// Deletes the day off
					$delete_query_2= 'DELETE FROM days_off WHERE Parent_id = :id ';
					
					$stmt = $con->prepare($delete_query_2);
					$stmt->bindParam(':id',$id);
					$stmt->execute();		
					
					// Deletes the unavailable staff registration (sets doctor available)
					$delete_query_3= 'DELETE FROM unavailable_staff WHERE Id = :id';
				
					$stmt = $con->prepare($delete_query_3);
					$stmt->bindParam(':id',$event_id);
					$stmt->execute();					
					
					//Defines variables to be used for the notification to the user
					$result['childDate'] = date('Y-m-d', strtotime($dayOffTime));
					$result['childDelete'] = true;
					$result['childDeleted'] = $event_id;	
				}
				//e-mails disabled on this distribution for spam and privacy reasons
				   deletedEventEmail($staff_id, $start, $end, $thisEvent, null, $id, $con);
				
			}
			//If event is a work shift
			else if($thisEvent == 6)
			{ 
				$delete_query= 'DELETE FROM work_shifts WHERE Id = :id';			

				$stmt = $con->prepare($delete_query);
				$stmt->bindParam(':id',$id);
				$stmt->execute();				
			}
		}
		catch (PDOException $e) { die($e); }	
		
		//e-mails disabled on this distribution for spam and privacy reasons
		deletedEventEmail($staff_id, $start, $end, $thisEvent, null, null, $con);
		
		if (isset($result))
		{
			return $result;
		}		
	}
	
	
	//// Insert data function
	function insertData ( $staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $autoDayOff, $con ) 
	{		
		// Generates a random ID for the event
		$Id = mt_rand(1000000000, 9999999999);
		try {
			// If event is NOT a work-shift
			if($event != 6)
			{			
				// Identifies the doctor's specialty
				$select_query = 'SELECT Specialty_id FROM medical_staff WHERE Id = :id LIMIT 1';
				
				$stmt = $con->prepare($select_query);
				$stmt->bindParam(':id',$staff_id);
				$stmt->execute();			
				
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					$staff_category_id = $row['Specialty_id'];
				}	
				
				// Sets the doctor unavailable for the time of the event
				$insert_query ='INSERT INTO unavailable_staff(Id, Staff_id, Specialty_id, Start, End, Reason ) 
								VALUES(?,?,?,?,?,?)';
								
				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($Id, $staff_id, $staff_category_id, $startDate, $endDate, $event));
			}	
			//If automated day-off of is set	
			if ($autoDayOff['dayOffExistance'] == 'true')
			{			
				// Generates a random ID for the event
				$child_Id = mt_rand(1000000000, 9999999999);
				
				// Sets an parent_id to associate it with the corresponding call-duty
				$parent = $Id;
				
				$dayOffStart = $autoDayOff['dayOffStart'];
				$dayOffEnd = $autoDayOff['dayOffEnd'];
				
				// Registers the day off
				$insert_query ='INSERT INTO days_off(Id,Staff_id,Leave_date,Return_date, Comments, Parent_id)
								VALUES(?,?,?,?,?,?)'; 
				
				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($child_Id, $staff_id, $dayOffStart, $dayOffEnd, $comments, $parent));
				
				// Sets the doctor unavailable for the time of the day off
				$insert_query ='INSERT INTO unavailable_staff(Id, Staff_id, Specialty_id, Start, End, Reason)
								VALUES(?,?,?,?,?,"3")';

				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($child_Id, $staff_id, $staff_category_id, $dayOffStart, $dayOffEnd));
				
				//e-mails disabled on this distribution for spam and privacy reasons
				   new_dayOff_curacy_workShift_email(3, $startDate, $endDate, $staff_id, $con);
				
			}
			else{
				$thisParent = 0;
			}	
			
			// If event is a leave or sick leave
			if($event == 1 || $event == 2)
			{ 
				$insert_query ='INSERT INTO vacation (Id, Type, Staff_id, Leave_date, Return_date, Comments)
								VALUES(?,?,?,?,?,?)';
				
				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($Id ,$event, $staff_id, $startDate, $endDate, $comments));
				
				//e-mails disabled on this distribution for spam and privacy reasons
			newstartVacationEmail($event, $startDate, $endDate, $staff_id, $con);
			}
			//If event is a day-off
			else if($event == 3)
			{ 
				$insert_query ='INSERT INTO days_off(Id, Staff_id, Leave_date, Return_date, Comments, Parent_id)
								VALUES(?,?,?,?,?,?)';
				
				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($Id, $staff_id, $startDate, $endDate, $comments, $thisParent));
				
				//e-mails disabled on this distribution for spam and privacy reasons
				   new_dayOff_curacy_workShift_email($event, $startDate, $endDate, $staff_id, $con);
			}
			// If event is an exam
			else if($event == 4)
			{ 		
				// Calls function to find an available ward
				$ward_details = find_available_ward($staff_category_id, $startDate, $endDate, $event, $Id, $con);
				$ward_id = $ward_details['ward_id'];
				$ward_number = $ward_details['ward_number'];
				$unit_name = $ward_details['unit_name'];
				$building_name = $ward_details['building_name'];
				$address = $ward_details['Address'];			
				
				// Makes the registration for the examination
				$insert_query ='INSERT INTO examinations(Exam_id, Staff_id, Patient_id, Exam_type_id, 
								Start_time, End_time, Ward_id, Insurance, Confirmed, Results, Viewed, Comments )
								VALUES(?,?,?,?,?,?,?,"0","0",NULL,"0",?)'; 
				
				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($Id, $staff_id, $patient_id, $subReason, $startDate, $endDate, $ward_id, $comments ));
				
				// Calls function to update doctors workload
				setWorkload("inc", $staff_id, $startDate, $endDate, $con);	
				
				// Sends mails to all (disabled in this distribution for privacy and spam reasons)
				   newExamToPatient($event, $Id, $startDate, $staff_id, $patient_id, $ward_number, $unit_name, $building_name, $address, $con);
				   newExamToDoc($event, $Id, $startDate, $staff_id, $patient_id, $ward_number, $unit_name, $building_name, $address, $con);
			}				
			// If event is a call-duty
			else if($event == 5)
			{ 
				$insert_query ='INSERT INTO on_duty (Id, Staff_id, Start_date, End_date, Unit_id, Comments)
								VALUES(?,?,?,?,?,?)';

				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($Id, $staff_id, $startDate, $endDate, $subReason, $comments));
				
				//e-mails disabled on this distribution for spam and privacy reasons
					new_dayOff_curacy_workShift_email($event, $startDate, $endDate, $staff_id, $con);
			}
			// If event is a work-shift
			else if($event == 6)
			{
				$insert_query ='INSERT INTO work_shifts(Id, Staff_id, Start_date, End_date, Unit_id, Comments)
								VALUES(?,?,?,?,?,?)';

				$stmt = $con->prepare($insert_query);				
				$stmt->execute(array($Id, $staff_id, $startDate, $endDate, $subReason, $comments));
				
				//e-mails disabled on this distribution for spam and privacy reasons
					new_dayOff_curacy_workShift_email($event, $startDate, $endDate, $staff_id, $con);
			}
		}
		catch (PDOException $e) { die($e); }
	}

	//// Find available ward function
	function find_available_ward($staff_category_id, $startDate, $endDate, $event, $event_id, $con)
	{	
		// Checks examination ward availability between those that correspond
		// to the exam type (exams are taking place to specific wards)
		$query ='SELECT Id, Number, Unit_id FROM examimation_wards 
				WHERE((Examination_type_id = ?) 
					AND (Id NOT IN
						(SELECT Examination_ward_id FROM unavailable_examination_wards 
						WHERE((Examination_type_id = ?) 
							AND (( ? BETWEEN Start AND End) 
								OR ( ? BETWEEN Start AND End) 
								OR(Start BETWEEN  ? AND  ? ) 
								OR (End BETWEEN  ? AND  ? ))))))
				LIMIT 1';
				
		$stmt = $con->prepare($query);				
		$stmt->execute(array($staff_category_id, $staff_category_id, $startDate,
							$endDate, $startDate, $endDate, $startDate, $endDate));
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$ward = $row;	
		}
		
		if ( !isset($ward) )
		{
			$result['result'] = "WARD AVAILABILITY ERROR";
			$con=null;
			$output=json_encode($result);						// Returns result to the client in JSON format
			exit($output);
		}		
		
		$this_ward = $ward['Id'];
		$result['ward_id'] = $this_ward;
		$this_ward_num = $ward['Number'];
		$result['ward_number'] = $this_ward_num;
		$this_unit_id = $ward['Unit_id'];
		
		// The unit's name and the building ID
		$unit_name = get_unit_name_by_id($this_unit_id , 'en');
		$result['unit_name'] = $unit_name;
		
		$building_id = get_building_Id_from_unit($this_unit_id);				
		$buildings = get_building_Info($building_id);
		$result['building_name'] = $buildings['en'];			 // Building's name
		$result['Address'] = $buildings['Address'];		
		
		$insert_query='INSERT INTO unavailable_examination_wards(Examination_ward_id, Start, End, Reason, Event_id)
						VALUES(?,?,?,?,?)';
		
		$stmt = $con->prepare($insert_query);				
		$stmt->execute(array($this_ward, $startDate, $endDate, $event, $event_id));
									  		
		return $result;
	}	
	
	// Set workload function
	function setWorkload($action, $this_doctor, $startDate, $endDate, $con)
	{			
		// increase or decrease workload
		if ($action == "inc"){
			$do = "+";
		}
		else if($action == "dec"){
			$do = "-";	
		}
		
		//Defines times
		$start_date = new DateTime($startDate);
		$since_start = $start_date->diff(new DateTime($endDate));
		$hours = $since_start->h;
		$minutes = $since_start->i;
		$workTime = $hours.":".$minutes.":00";	
		$workDateArray = explode(" " , $startDate);
		$workDate = $workDateArray[0];
		$extraMinutes = $hours*60 + $minutes. " minutes";
	
		// Retrieves all the workload for the selected day
		$workload_query ='SELECT Id, Work_time FROM workload WHERE Staff_id = :id AND Date = :work_date 
						ORDER BY Date DESC'; 
		
		$stmt = $con->prepare($workload_query);
		$stmt->bindParam(':id',$this_doctor);
		$stmt->bindParam(':work_date',$workDate);
		$stmt->execute();			
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$work_time[] = $row;	
		}		
		
		// If no workload exists and needs to be added
		if ( !isset($work_time) && $action == "inc" ) 
		{			
			// Creates a new registration
			$workload_code = mt_rand(1000000000, 9999999999);
			
			$insert_workload ='INSERT INTO workload VALUES('.$workload_code.',?,?,?)';
			
			$stmt = $con->prepare($insert_workload);				
			$stmt->execute(array($workDate, $this_doctor, $workTime));
		}
		else{
		// Updates the workload
			$work_time_id = $work_time[0]['Id'];
			$this_work_time = $work_time[0]['Work_time'];
			$new_worktime = date("H:i:s", strtotime($this_work_time.''.$do.''.$extraMinutes)); 
			
			$update_workload ='UPDATE workload SET Work_time = :new_worktime WHERE Id = :work_time_id';
			
			$stmt = $con->prepare($update_workload);				
			$stmt->bindParam(':new_worktime',$new_worktime);
			$stmt->bindParam(':work_time_id',$work_time_id);
			$stmt->execute();		
		}	
	}
	
?>