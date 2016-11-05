<?php
	// Detects and returns the 3 first patient's examinations 
	// that include a result and have not been viewed by the patient yet!
	// Also sets the examinations returned as viewed.
	// If called repeatively, 3 new examinations will be returned each time
	// and added to the previous ones, since the previous ones will have
	// been already marked as "viewed"
	
	session_start();

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	// To retrieve exam-names		

	check_if_patient();					// Checking session to prevent unauthorized access
	
	if(!check_and_update_session())		// Checking if session has expired and updates timout and id
	{	
		echo json_encode('EXPIRED');	// or destoys it and prevents access
		exit;
	}
	
	$id = $_SESSION['id'];
	
	if(isset( $_POST['limit1']))
	{
		$limit1 = $_POST['limit1'];
	}
	
	$data = null;
	$all_exams = null;
	
	$con = DB_Connect();				// Connecting to database
																	// Selection of the 3 first exams 
																	// and corresponding doctors
	$query ='SELECT examinations.Exam_id, examinations.Staff_id, 
			examinations.Exam_type_id, examinations.Start_time,
			examinations.Results, medical_staff.Name, medical_staff.Surname
			FROM examinations
			INNER JOIN medical_staff ON examinations.Staff_id = medical_staff.Id
			WHERE Patient_id = :id AND Confirmed = 1 AND Viewed = 0
			ORDER BY Start_time DESC LIMIT 3';
	try {	
		$stmt = $con->prepare($query);		
		$stmt->bindParam(':id',$id);
		$stmt->execute();		
		$cnt=0;			
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{														
			$data[] = $row;
			$this_date = $row['Start_time'];
			$exam_type_id = $row['Exam_type_id'];
			
			$split_date_time = explode( " " , $this_date);
			$mydate = $split_date_time[0];
			$mytime = $split_date_time[1];
			$dateArray = explode( "-" , $mydate);  
			$timeArray = explode( ":" , $mytime);  
			$date = $dateArray[2]."/".$dateArray[1]."/".$dateArray[0];
			$time = $timeArray[0].":".$timeArray[1];
			
			$data[$cnt]['Date'] = $date;
			$data[$cnt]['Time'] = $time;
			$data[$cnt]['Doctor_name'] = $row['Name'];	
			$data[$cnt]['Doctor_surname'] = $row['Surname'];		
			$data[$cnt]['Examination_data_inter'] = get_examination_data_inter($exam_type_id);
			
			if (!isset($all_exams))
			{
				$all_exams = $row['Exam_id'];
			}
			else{
				$all_exams = $all_exams .",".$row['Exam_id'];
			}
			
			$cnt++;						
		}
			
	}
	catch (PDOException $e) { die($e); }
			
	$notif=sizeof($data);	
	
	if ($notif == 0)
	{
		if (!isset($limit1) || $limit1 == 0)
		{
			$output = json_encode('NO NEW EXAMS');
		}
		else
		{			
			$output = json_encode('NO MORE EXAMS');
		}		
		$con=null;		
		
		exit($output);		
	}																//Sets the exams as viewed
												
	$query = 'UPDATE examinations SET Viewed = 1 WHERE Exam_id IN( '.$all_exams.' )';	// Non-input value

	$stmt = $con->prepare($query);		
	$stmt->execute();		

	$con=null;	
	
	$output=json_encode($data);
	
	exit($output);
?>