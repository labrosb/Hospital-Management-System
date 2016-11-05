<?php
	//Detects and returns the examinations schedule in past date (so no future ones will be included)
	//that the doctor hasn't yet included a diagnosis to. The number of the results presented per time
	//are determined by the variables $limit1 and $limit2 (start from the $limit1 exam until the $limit2
	//exam ($limit1 and $limit2 are integers)
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");	// Retrieves exam-names
	
	check_if_doctor();							// Checking session to prevent unauthorized access

	if(!check_and_update_session())				// Checking if session has expired and updates timout and id
	{	
		echo $output= json_encode('EXPIRED');	// or destoys it and prevents access
		exit($output);
	}
	
	$id = $_SESSION['id'];
	$limit1 = $_POST['limit1'];
	$limit2 = $_POST['limit2'];

	date_default_timezone_set('Europe/Athens');

	$currentDate =  date("Y-m-d H:i:s");

	$con = DB_Connect();				// Connecting to database
	
																// Selects the examinations
																// and corresponding patients
	$query='SELECT examinations.Exam_id, examinations.Patient_id,
			examinations.Exam_type_id, examinations.Start_time,
			examinations.Results, patients.Id, patients.Name, Patients.Surname
			FROM examinations 
			INNER JOIN patients ON examinations.Patient_id = patients.Id
			WHERE Staff_id = :id AND Confirmed = 0 AND End_time < :date 
			ORDER BY Start_time DESC LIMIT :limit1, :limit2'; 
	
	try {	
		$stmt = $con->prepare($query);
		$stmt->bindParam(':id',$id);
		$stmt->bindParam(':date',$currentDate);
		$stmt->bindParam(':limit1',$limit1);
		$stmt->bindParam(':limit2',$limit2);
		$stmt->execute();
		
		$cnt=0;			
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 	// Date and time to presentable form
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
			$data[$cnt]['data_inter'] = get_examination_data_inter($exam_type_id);
			$data[$cnt]['patient_id'] = $row['Id'];			
			$data[$cnt]['patient_name'] = $row['Name'];	
			$data[$cnt]['patient_surname'] = $row['Surname'];				
			
			$cnt++;
		}	
	}
	catch (PDOException $e) { die($e); }
	
	$notif=empty($data);
	
	if ($notif)
	{
		if ($limit1 == 0)
		{
			$output = json_encode('NO NEW EXAMS');	// Returns result to the client in JSON format
		}
		else{			
			$output = json_encode('NO MORE EXAMS');	// Returns result to the client in JSON format
		}		
		$con=null;	
		
		exit($output);
	}
		
	$con=null;		
	
	$output=json_encode($data);
	
	exit($output);
?>