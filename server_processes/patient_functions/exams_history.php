<?php
	// Detects and returns the patient's examinations that include a result.
	// The results returned start from a particular result value given from
	// the variable $limit1 and the number of the results returned is defined
	// from the variable $limit2.
	// The results returned are those that their date is between the $from,
	// and $to dates and correspond to the examination category $exams, ONLY if 
	// those variables are set. If some or all of those variables are not set
	// they are just ignored.
	
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
	$limit1 = $_POST['limit1'];
	$limit2 = $_POST['limit2'];	
	
	if(isset ($_POST['exams_types']))
	{
		$exams = $_POST['exams_types'];
	}
	else{
		$exams = 'default';
	}
	
	$from = $_POST['from'];	
	$to = $_POST['to'];	
	
	$exams_query = null;	
	$from_query = null;
	$to_query = null;	
	
	$data = null;
	
	if ($from != 'default' && $from != 'From' && $from !='')
	{
		$dateFromArray = explode( "/" , $from);						
		$fixed_date = $dateFromArray[2]."-".$dateFromArray[1]."-".$dateFromArray[0];
		$from_date_time = $fixed_date." 00:00:01";	
			
		$from_query = 'AND Start_time > ?'; 
	}																// Puts the date and time together to be
																	// compatible with the database format and 
	if ($to != 'default' && $to != 'To' && $to !='')				// puts the sql query arguments into variables
	{
		$dateToArray = explode( "/" , $to);
		$fixed_date = $dateToArray[2]."-".$dateToArray[1]."-".$dateToArray[0];
		$to_date_time = $fixed_date." 23:59:00";		

		$to_query = 'AND Start_time < ?';
	}
		
	if ($exams != 'default' && $exams !='')					// Puts the sql query arguments into variables
	{	
		$IN_query_values = implode(',', array_fill(0, count($exams), '?'));
		$exams_query = "AND Exam_type_id IN(".$IN_query_values.")"; 
	}
	
															
	$values = array($id);									// Creates array with the values for
															// the query and inserts the id valu
	if( $exams != 'default' && $exams !='')
	{
		foreach ($exams as $i => $exam)						// Values for the categories filter
		{
			array_push($values, $exam);
		}
	}	
	if( isset($from_query))									// If choice is set, set the value
	{
		array_push($values, $from_date_time);
	}
	if( isset($to_query))									// If choice is set, set the value
	{
		array_push($values, $to_date_time);
	}		
	array_push($values, $limit1);
	array_push($values, $limit2);

	$con = DB_Connect();											// Connecting to database	
			
																	// Exams and corresponding doctors selection
	$query ='SELECT examinations.Exam_id, examinations.Staff_id,
			examinations.Exam_type_id, examinations.Start_time,
			examinations.Results, medical_staff.Name, medical_staff.Surname
			FROM examinations
			INNER JOIN medical_staff ON examinations.Staff_id = medical_staff.Id
			WHERE Patient_id = ? AND Confirmed = 1 AND Viewed = 1
			'.$exams_query.' '.$from_query.' '.$to_query.'
			ORDER BY Start_time DESC LIMIT ?, ?'; 	
	try {			
		$stmt = $con->prepare($query);			
		
		$stmt->execute($values);
		
		$cnt=0;		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 			//Date and time to presentable form
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
			
			$cnt++;
		}	
	}
	catch (PDOException $e) { die($e); }
			
	$notif=sizeof($data);	
	
	if ($notif == 0)
	{
		if ($limit1 == 0)
		{
			$output = json_encode('NO NEW EXAMS');
		}
		else
		{			
			$output = json_encode('NO MORE EXAMS');
		}		
		$con=null;		
		
		exit($output);		
	}				

	$con=null;	
	
	$output = json_encode($data);	// Returns result to the client in JSON format
	
	exit($output);
?>