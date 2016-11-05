<?php
	// Takes care of all the functions required to complete the examinations 
	// appointment registration. Specifically:
	//   - Checks for valid exam category and if selected doctor corresponds to the category	
	// 	 	(those cannot occur anyway as long as js is enabled)
	//   - Checks if doctor is STILL available right before the registration	
	//	 - Checks which wards are available among the ones appropreate for the examination.
	//   - Sets doctor and ward occupied for the selected time
	//   - Updates the doctors workload for the particular day
	//   - Registers the examination
	//   - Sends e-mail to both patient and doctor (disabled for this distribution for privacy reasons)
	
	// In case that no doctor is manually selected, the system:
	//   - Detets all the available doctors who corresponds to the corresponding to the exam speciality
	//   - Detects the workload for one each of them in for particular day 
	//   - Calculates the minimum workload and selects the corresponding doctor to complete the registration
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/general_functions/exams_specialities_units.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/email_functions/mail_functions.php");
	//E-mails disabled on this distribution for spam and privacy reasons
	
	check_if_patient();					// Checking session to prevent unauthorized access

	if(!check_and_update_session()){	// Checking if session has expired and updates timout and id
		echo "EXPIRED";					// or destoys it and prevents access
		exit;
	}
								
	date_default_timezone_set('Europe/Athens');

	$exams_type = $_POST["exams_type"];
	$date = $_POST["date"];
	$time = $_POST["time"];
	$this_doctor = $_POST["doctor"];
	$insurance = $_POST["insurance"];
	$reason_doctor = '4';
	$reason_ward = '4';
	$patient_id = $_SESSION['id'];
	$code = mt_rand(1000000000, 9999999999);
	
	$lang=$_POST['lang'];							// The current system's language
	
	$plus_worktime = ' +30 minutes';
	$first_worktime = '00:30:00';
	
	if 	($insurance == 'true')						//Converts insurance to boolean	
	{				
		$insurance = true;
	}
	else{
		$insurance = false;
	}
													// DateTime	implementation
	$dateArray = explode( "/" , $date);		
	$fixed_date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
	$date_time = $fixed_date." ".$time.":00";	
																
	
	$end_date = date("Y-m-d H:i:s", strtotime($date_time.''. $plus_worktime));	// Expiring exams date implementation
								
	$examination_type_id = get_examination_id_by_name($exams_type, $lang);		// Retrieves exam type id and corresponding		
	$staff_category_id = get_speciality_id_from_exam_id($examination_type_id);	// staff_category_id of the selected exams category
						
	if ( !$examination_type_id ) 								// Exam category existence check
	{					
		exit("EXAM TYPE ERROR");
	}
	
	$this_examination = $examination_type_id;

	$con = DB_Connect();										// Connecting to database	
	
	if 	($this_doctor > 0)
	{		
		$query ='SELECT Id, Name, Surname FROM medical_staff WHERE Id =:id LIMIT 1'; 
										
		try {	
			$stmt = $con->prepare($query);		
			$stmt->bindParam(':id',$this_doctor);
			$stmt->execute();
				
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))			// Checks if doctor corresponds to the category	
			{
				$doctor_id[] = $row;
				$this_doctor_name = $row['Name'];
				$this_doctor_surname = $row['Surname'];				
			}
		}	
		catch (PDOException $e) { die($e); }
		
		if ( !isset($doctor_id) ) 
		{
			$con=null;
			exit("DOCTOR ERROR");
		}		
																			// Checks if doctor is STILL available
																			// --there is the chance that the doctor will 
																			// be available during the election time, but during 
																			// the exam boking not to be available anymore--
		$query='SELECT Staff_id FROM unavailable_staff 
				WHERE((Staff_id = ?) 
				AND ((? BETWEEN Start AND End) 
				OR (? BETWEEN Start AND End) 
				OR (Start BETWEEN ? AND ?) 
				OR (End BETWEEN ? AND ?))) 
				ORDER BY Start DESC LIMIT 1';	
				
		try {	
			$stmt = $con->prepare($query);		
			$stmt->execute(array($this_doctor, $date_time, $date_time, $end_date, 
								 $date_time, $end_date, $date_time));			
			$stmt->execute();
			
			$rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);	
			$num_rows = count( $rows );							
		}
		catch (PDOException $e) { die($e); }		
		
		if ( $num_rows > 0 ) 									// If doctor is not available
		{
			$con=null;
			exit("AVAILABILITY ERROR");
		}	
	}
	else{	
																// Selects the doctors who correspond to the exam type,
																// have a work shift scheduled in the selected time and
																// have no other tasks scheduled during that time,
																// all in a single query
		$query='SELECT Id, Name, Surname FROM medical_staff  
				WHERE Specialty_id= ? 
					AND Id NOT IN
						(SELECT Staff_id FROM unavailable_staff 
						WHERE Specialty_id = ?
							AND( ? BETWEEN Start AND End
								OR ? BETWEEN Start AND End 
								OR Start BETWEEN ? AND ? 
								OR End BETWEEN ? AND ? )) 
					AND Id IN 
						(SELECT Staff_id FROM work_shifts 
						WHERE ? BETWEEN Start_date AND End_date)';		// Using the '?' because the 'bindParam'
																		// causes problems in nested queries cas		
		try {	
			$stmt = $con->prepare($query);		
			$stmt->execute(array($staff_category_id, $staff_category_id, $date_time, $end_date, 
								 $date_time, $end_date, $date_time, $end_date, $date_time ));			
			$stmt->execute();
	
			$cnt = 0;	
			while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 
			{
				$doctors[] = $row;
				$doctors[$cnt]['Worktime'] = '00:00:00';
				$doctors_ids[]= $doctors[$cnt]['Id'];
				$cnt++;
			}			
		}
		catch (PDOException $e) { die($e); }		
				
		if (!isset($doctors)) 
		{
			$con=null;
			exit("DOCTORS AVAILABILITY ERROR");
		}
	
		$doc_ids_list = implode(",",$doctors_ids);
		
		$query='SELECT Staff_id, Work_time FROM workload WHERE Staff_id IN (:ids_list) 
				AND Date = :date ORDER BY Date DESC';  

		try {	
			$stmt = $con->prepare($query);		
			$stmt->bindParam(':ids_list',$doc_ids_list);
			$stmt->bindParam(':date',$fixed_date);			
			$stmt->execute();
				
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))					// Retrieves the workload of the doctors selected 
			{															// previously during the exam's date
				$workload[]= $row;									
			}
		}catch (PDOException $e) { die($e); }									
								
		for ($i = 0; $i <= sizeof($doctors)-1; $i++) 
		{																// Adds to each doctor his workload,
			for ($j = 0; $j <= sizeof($workload)-1; $j++) 				// in the table with the selected doctors 
			{
				if ($doctors[$i]['Id'] == $workload[$j]['Staff_id'])
				{
					$doctors[$i]['Worktime'] = $workload[$j]['Work_time'];
				}
			}
		}
		
		$min = $doctors[0]['Worktime']; 
		$this_doctor = $doctors[0]['Id'];
		$this_doctor_name = $doctors['0']['Name'];
		$this_doctor_surname = $doctors['0']['Surname'];
		
		foreach($doctors as $val) 										// Finds the doctor with the min workload
		{ 						
			if ($val['Worktime'] < $min) 
			{
				$min = $val['Worktime'];
				$this_doctor = $val['Id'];
				$this_doctor_name = $val['Name'];
				$this_doctor_surname = $val['Surname'];
			}
		}
	}	
																		// Checks examination ward availability 
																		// between those that correspond to the exam type 
																		// (exams are taking place to particular wards)
	$query='SELECT Id, Number, Unit_id FROM examimation_wards 
			WHERE Examination_type_id = ? 
				AND Id NOT IN
					(SELECT Examination_ward_id FROM unavailable_examination_wards 
					 WHERE Examination_type_id = ?
						AND ( ? BETWEEN Start AND End
							OR ? BETWEEN Start AND End
							OR Start BETWEEN ? AND ?
							OR End BETWEEN ? AND ? ))';			// Using the '?' because the 'bindParam'
																// causes problems in nested queries case		
	try {	
		$stmt = $con->prepare($query);		
		$stmt->execute(array($this_examination, $this_examination, $date_time, $end_date, 
							 $date_time, $end_date, $date_time, $end_date));
			
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 
		{	
			$ward[] = $row;	
		}						
	}
	catch (PDOException $e) { die($e); }
	
	if ( !isset($ward) ) 
	{
		$con=null;
		exit("WARD AVAILABILITY ERROR");
	}	
	
	$this_ward = $ward[0]['Id'];
	$this_ward_num = $ward[0]['Number'];
	$this_unit_id = $ward[0]['Unit_id'];
																		// The unit's name and the building ID
	$unit_name = get_unit_name_by_id($this_unit_id, $lang);		
	$building_id = get_building_Id_from_unit($this_unit_id);
	
	$query ='SELECT Name, Address FROM buildings WHERE Id = :id LIMIT 1'; 

	try {	
		$stmt = $con->prepare($query);		
		$stmt->bindParam(':id',$building_id);
		$stmt->execute();
			
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))  					// The Building's name and address
		{						
			$building_name = $row['Name'];
			$building_address = $row['Address'];
		}						
	}
	catch (PDOException $e) { die($e); }						
					
	
	$query='SELECT Id, Work_time FROM workload WHERE Staff_id = :id 
			AND Date = :date ORDER BY Date DESC';  
	try {	
		$stmt = $con->prepare($query);		
		$stmt->bindParam(':id',$this_doctor);
		$stmt->bindParam(':date',$fixed_date);		
		$stmt->execute();
			
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 						// Retrieves the selected doctor's 
		{																// workload on the exam's date to add
			$work_time[] = $row;										// the additional workload of the new exam  
		}						
	}							
	catch (PDOException $e) { die($e); }	
	
	if ( !isset($work_time)) 									// If no workload exists, creates the whole database registration
	{									
		$workload_code = mt_rand(10000000, 99999999);
		
		$query ='INSERT INTO workload ( Id, Staff_id, Date, Work_time)
								VALUES(:workload_id, :doctor, :fixed_date, :first_worktime )';	
		try {	
			$stmt = $con->prepare($query);		
			$stmt->bindParam(':workload_id',$workload_code);				
			$stmt->bindParam(':fixed_date',$fixed_date);	
			$stmt->bindParam(':doctor',$this_doctor);	
			$stmt->bindParam(':first_worktime',$first_worktime);		
			$stmt->execute();					
		}							
		catch (PDOException $e) { die($e); }
	}
	else{														// If some workload already exists, adds the 
		$work_time_id = $work_time[0]['Id'];					// additional workload to the existing one
		$this_work_time = $work_time[0]['Work_time'];
		$new_worktime = date("H:i:s", strtotime($this_work_time.''. $plus_worktime)); 
		
		$query ='UPDATE workload SET Work_time = :new_time WHERE Id = :time_id'; 
		
		try {	
			$stmt = $con->prepare($query);		// Updates workload
			$stmt->bindParam(':new_time',$new_worktime);
			$stmt->bindParam(':time_id',$work_time_id);		
			$stmt->execute();
		} 
		catch (PDOException $e) { die($e); }		
	}		
																
	$query='INSERT INTO examinations(Exam_id, Staff_id, Patient_id, Exam_type_id, Start_time, 
									 End_time, Ward_id, Insurance, Confirmed, Results, Viewed) 
			VALUES(:id, :doctor_id, :patient_id, :exam_id, :start_date, :end_date, :ward_id, :insurance, 0, NULL, 0 )';	
											
	try {	
		$stmt = $con->prepare($query);							// Exam registration
		$stmt->bindParam(':id',$code);				
		$stmt->bindParam(':doctor_id',$this_doctor);	
		$stmt->bindParam(':patient_id',$patient_id);	
		$stmt->bindParam(':exam_id',$this_examination);
		$stmt->bindParam(':start_date',$date_time);				
		$stmt->bindParam(':end_date',$end_date);	
		$stmt->bindParam(':ward_id',$this_ward);	
		$stmt->bindParam(':insurance',$insurance);
		$stmt->execute();					
	}							
	catch (PDOException $e) { die($e); }
	
																
	$query='INSERT INTO unavailable_staff (Id, Staff_id, Specialty_id, Start, End, Reason) 
			VALUES(:id, :doctor_id, :speciality, :start_date, :end_date, :reason )';
									  
	try {	
		$stmt = $con->prepare($query);							// Occupying the selected doctor 
		$stmt->bindParam(':id',$code);							// for the specific date/time		
		$stmt->bindParam(':doctor_id',$this_doctor);	
		$stmt->bindParam(':speciality',$staff_category_id);	
		$stmt->bindParam(':start_date',$date_time);				
		$stmt->bindParam(':end_date',$end_date);	
		$stmt->bindParam(':reason',$reason_doctor);	
		$stmt->execute();					
	}							
	catch (PDOException $e) { die($e); }
															
	$query ='INSERT INTO unavailable_examination_wards(Examination_ward_id, Start, End, Reason, Event_id) 
			VALUES(:id, :start_time, :end_time, :reason, :event_id)';
									  
	try {	
		$stmt = $con->prepare($query);							// Occupying the selected ward 
		$stmt->bindParam(':id',$this_ward);						// for the specific date/time 	
		$stmt->bindParam(':start_time',$date_time);	
		$stmt->bindParam(':end_time',$end_date);	
		$stmt->bindParam(':reason',$reason_ward);				
		$stmt->bindParam(':event_id',$code);	
		$stmt->execute();					
	}							
	catch (PDOException $e) { die($e); }
	
	//E-mails disabled on this distribution for spam and privacy reasons
	
	//Sends E-mail to patient
	newExamToPatient(4, $code, $date_time, $this_doctor, $patient_id, $this_ward_num, $unit_name, $building_name, $building_address, $con);
	
	//Sends E-mail to Doctor
	newExamToDoc(4, $code, $date_time, $this_doctor, $patient_id, $this_ward_num, $unit_name, $building_name, $building_address, $con);

	$con=null;
	
	exit("DONE");
?>