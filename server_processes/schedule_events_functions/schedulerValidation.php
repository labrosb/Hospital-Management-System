<?php
	// Checks for restrictions and special conditions before executing the command
	// Is decided if the action will be executed even if other events exists in the 
	// same time, if the user should decide of if the action will be prevented.
	// Also the first available day for a day-off to be set is set if detected if required

	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
			
	check_if_doctor_manager();			// Checking session to prevent unauthorized access	

	if(!check_and_update_session())		// Checking if session has expired and updates timout and id
	{
		$output=json_encode('EXPIRED');	// or destoys it and prevents access
		exit($output);
	}		
	
	$con = DB_Connect();				// Connecting to database
	
	date_default_timezone_set('Europe/Athens');

	$this_doctor = $_POST['staff_id'];
	
	$event = $_POST['event'];
	$action = $_POST['action'];
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$day_off_search = $_POST['dayOff'];
	
	$result['dayOff'] = null;
	
	if ($action == 'insert')			// If the action to be executed is an event insertion		
	{			
	
		$existingEvents = getEvents($startDate, $endDate, $this_doctor, $con);	// Calls function to search for other events in different
																			//db tables and return their ids
																					
		if ($existingEvents > 0) 		// If events found (means that will overlay with the event that is to be inserted)
		{		
			$i=0;	
			$ids=null;		
			foreach($existingEvents as &$existingEvent)
			{	
				$ids[$i]['Id'] = $existingEvent['id'];						// Isolates the event ids and event types to be used later
				$types[$i]['Reason'] = $existingEvent['reason'];
				$i++;	
			}			
			switch($event) 						// $event is the event that is to be added
			{					
				case 1:							// If its a leave, ask if should continue (and delete the existing events)
					$result['do'] = "ASK";							
					$result['events'] = $ids;	// And assign the existing events to be deleted -if decided- 		
					break;
				case 2: 						// If its a sick leave, force execution (deleting the existing events)
					$result['do'] = "FORCE";
					$result['events'] = $ids;
					break;
				case 3: 						// If its a day-off, ask if should continue
					$result['do'] = "ASK";
					$result['events'] = $ids;
					break;
				case 4: 						// If its an exam, prevent execution
					$eventsType = $types;
					$result['do'] = "NTN";
					foreach($eventsType as &$ExistingEvent)
					{
						if($ExistingEvent['Reason'] != 6 )		// Except if the event existing is a work-shift. 
						{		
							$result['do'] = "NOT";				// In that case register event without deleting the existing ones
							break;
						}
					}						
					break;
				case 5: 						// If is call-duty, prevent execution 
					$result['do'] = "NOT";	
					break;
				case 6: 						// If is a work-shift --> if existing event is examination, resisters the shift
												// resisters the shift without deleting the exams, otherwise prevents the execution
					$eventsType = $types;
					$result['do'] = "NTN";
					foreach($eventsType as &$ExistingEvent)
					{							
						if($ExistingEvent['Reason'] != 4 )
						{
							$result['do'] = "NOT";	
							break;
						}
					}	
					break;					
			}
		}	
		else{ 								// if no events exist in the selected time, continue to execusion
			 $result['do'] = "NTN";
			 $result['eventsNum'] = 0;
		}
		if($day_off_search == "true")		// If automated day off is set, calls the function to find the first available day
		{		
			$result['dayOff'] = findDayOff($startDate, $endDate, $this_doctor, $con );							
		}
		
		$con=null;
		
		$output=json_encode($result);		// Returns results		
		
		exit($output);
	}
	
	//////////////// Functions ///////////////////

	//// Function that formats the start/end times to the valid hospital start-end time
	function repoStartEnd($startDate)
	{
		$startDateArray = explode( " " , $startDate);		
		$repoDate["start"] = $startDateArray[0]." 7:00:00";
		$repoDate["end"] = $startDateArray[0]." 22:55:00";
		return $repoDate;
	}	
	
	//// Find day-off date function
	function findDayOff($startDate, $endDate, $this_doctor, $con) 
	{
															// Defines the date variables to search for available day
		$newDate = date('Y-m-d H:i:s', strtotime($startDate. ' + 1 days'));
		$daysNum = 1;
		$availableDate = false;	
		
		while ($daysNum <= 7 && $availableDate == false)	// Searches in the next 7 days
		{	
			$day = date('D', strtotime( $newDate));		
			
			if ( $day == "Sat" || $day == "Sun"  ) 			// Excluding the weekends from the counter
			{		
				$newDate = date('Y-m-d H:i:s', strtotime($newDate. ' + 1 days'));
				$daysNum++;
			}
			else{											// Sets the variables to search
				$repoDate = repoStartEnd($newDate);
				$repoStart = $repoDate["start"];
				$repoEnd = $repoDate["end"];							
															// Calls function to check if other events are scheduled for that day
				$eventsExist = check_If_Event($repoStart, $repoEnd, $this_doctor, $con);
								
				if ($eventsExist)							// If events are scheduled, move to the next day
				{						
					$newDate = date('Y-m-d H:i:s', strtotime($newDate. ' + 1 days'));
					$day = date('D', strtotime( $newDate));
					$daysNum++;
				}
				else{										// Else flag this date to add the day-off
					$availableDate = true;
					$result['dayOffDate'] = date('Y-m-d', strtotime($repoStart));
					$result['dayOffStart'] = $repoStart;	
					$result['dayOffEnd'] = $repoEnd;					
				}			
			}								
		}	
		$result['dateExistance'] = $availableDate;			// Variables to be returned to the client
		$result['daysAfter'] = $daysNum;	
		$result['day'] = $day;		
		
		return $result;
	}

	//// Function checking if doctor has scheduled events in the given time
	function check_If_Event($start_Date, $end_Date, $this_doctor, $con)
	{
		$exists = false;	
															// Checks the unavailable staff registrations
		$query= 'SELECT Id FROM unavailable_staff WHERE Staff_id = ? 	
					AND( ? BETWEEN Start AND End 
					OR ? BETWEEN Start AND End 
					OR ? <=Start AND ? >= End)  
					ORDER BY Start DESC';

		try {	
			$stmt = $con->prepare($query);
			$stmt->execute(array($this_doctor, $start_Date, $end_Date, $start_Date, $end_Date));
			
			$rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);	
			$num_rows = count( $rows );	
			
			if (($num_rows > 0))							// If no event exists in unavailable staff regs
			{ 												// checks the unavailable staff registrations
				$exists = true; 
			}
			
			return $exists;
		}
		catch (PDOException $e) { die($e); }

	}
	
	//// Function returns all the events of the doctor in the given time with their details 
	function getEvents($start_Date, $end_Date, $this_doctor, $con)
	{	
		$result=null;
		$i=0;
		$query ='SELECT * FROM unavailable_staff WHERE Staff_id = ? 
					AND ( ? BETWEEN Start AND End
						OR ? BETWEEN Start AND End 
						OR ? <=Start AND ? >= End)  
						ORDER BY Start DESC';
					
		$query_2 ='SELECT * FROM work_shifts WHERE Staff_id = ?
					AND( ? BETWEEN Start_date AND End_date 
						OR ? BETWEEN Start_date AND End_date 
						OR ? <= Start_date AND ? >= End_date)  
					ORDER BY Start_date DESC';
					
		try {
			$stmt = $con->prepare($query);
			$stmt->execute(array($this_doctor, $start_Date, $end_Date, $start_Date, $end_Date));
			$stmt->execute();
						
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$result[$i]['id'] = $row['Id'];
				$result[$i]['startDate'] = $row['Start'];
				$result[$i]['endDate'] = $row['End'];
				$result[$i]['reason'] = $row['Reason'];	
				$i++;
			}
			
			$stmt = $con->prepare($query_2);
			$stmt->execute(array($this_doctor, $start_Date, $end_Date, $start_Date, $end_Date));
			$stmt->execute();
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$result[$i]['id'] = $row['Id'];
				$result[$i]['startDate'] = $row['Start_date'];
				$result[$i]['endDate'] = $row['End_date'];
				$result[$i]['reason'] = 6;	
				$i++;
			}			
		}
		catch (PDOException $e) { die($e); }	
		
		return $result;
	}
?>