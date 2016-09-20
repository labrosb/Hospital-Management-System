<?php
	session_start();

	date_default_timezone_set('Europe/Athens');

	$exams_type = $_POST["exams_type"];
	$date = $_POST["date"];
	$time = $_POST["time"];
	$this_doctor = $_POST["doctor"];
	$insurance = $_POST["insurance"];
	$reason_doctor = '4';
	$reason_ward = '4';
	$confirmed = false;
	$viewed = false;
	$payment = false;
	$patient_id = $_SESSION['id'];
	$code = mt_rand(1000000000, 9999999999);
	//$unavailable_code = mt_rand(10000000, 99999999);
	
	$plus_worktime = ' +30 minutes';
	$first_worktime = '00:30:00';
//Μετατρέπει την insurance σε boolean	
	if 	($insurance == 'true'){
		$insurance = true;
	}else{
		$insurance = false;
	}
	
//Υλοποίηση ημερομηνίας DateTime	
	$dateArray = explode( "/" , $date);
	$fixed_date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];
	$date_time = $fixed_date." ".$time.":00";
//Υλοποίηση ημερομηνίας λήξης εξετάσεων	
	$end_date = date("Y-m-d H:i:s", strtotime($date_time.''. $plus_worktime));


	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	//Τύπος έξετάσεων - Ειδικότητα
	$sql = mysql_query("SELECT Id, Staff_category_id, Price FROM examination_types WHERE Name='$exams_type' LIMIT 1") or die("cannot connect to examination_types");
		while($row = mysql_fetch_assoc($sql)) {
			$examination_type_id[] = $row['Id'];
			$staff_category_id = $row['Staff_category_id'];
			$price = $row['Price'];
		}	
		//Ελεγχος υπαρξης τύπου εξετάσεων			
		if ( !isset($examination_type_id) ){
			echo "EXAM TYPE ERROR";
			exit;
		}
		$this_examination = $examination_type_id[0];
		
	if 	($this_doctor > 0){	
		//Ελεγχος αντιστοιχίας ιατρού.		
		$sql = mysql_query("SELECT Id, Name, Surname FROM medical_staff WHERE Id='$this_doctor' LIMIT 1") or die("cannot connect to medical_staff");
			while($row = mysql_fetch_assoc($sql)) {
				$doctor_id[] = $row;
				$this_doctor_name = $row['Name'];
				$this_doctor_surname = $row['Surname'];
			}								
			if ( !isset($doctor_id) ) {
				echo "DOCTOR ERROR";
				exit;
			}
			
		$sql = mysql_query("SELECT Staff_id FROM unavailable_staff WHERE((Staff_id='$this_doctor') AND
							(('$date_time' BETWEEN Start AND End) OR ('$end_date' BETWEEN Start AND End) OR
							(Start BETWEEN '$date_time' AND '$end_date') OR (End BETWEEN '$date_time' AND '$end_date'))) 
							ORDER BY Start DESC LIMIT 1")  or die("cannot connect to unavailable_staff");
		$num_rows = mysql_num_rows($sql);	
		if ( $num_rows > 0 ) {
			echo "AVAILABILITY ERROR";
			exit;
		}	
	}else{
		//Ελεγχος διαθεσιμοτητας ιατρων		
		$sql = mysql_query("SELECT Id, Name, Surname FROM medical_staff WHERE((Specialty_id='$staff_category_id') AND (Id NOT IN
							(SELECT Staff_id FROM unavailable_staff WHERE((Specialty_id='$staff_category_id') AND
							(('$date_time' BETWEEN Start AND End) OR ('$end_date' BETWEEN Start AND End) OR
							(Start BETWEEN '$date_time' AND '$end_date') OR (End BETWEEN '$date_time' AND '$end_date'))))) AND (Id IN 
							(SELECT Staff_id FROM work_shifts WHERE('$date_time' BETWEEN Start_date AND End_date ))))") 
				or die("cannot connect to medical_staff/unavailable_staff");
		$cnt = 0;
		while($row = mysql_fetch_assoc($sql)) {
			$doctors[] = $row;
			$doctors[$cnt]['Worktime'] = '00:00:00';
			$doctors_ids[]= $doctors[$cnt]['Id'];
			$cnt++;
		}
		if (!isset($doctors)) {
			echo "DOCTORS AVAILABILITY ERROR";
			exit;
		}
		
		$doc_ids_list = implode(",",$doctors_ids);
		$sql = mysql_query("SELECT Staff_id, Work_time FROM workload WHERE(Staff_id IN ($doc_ids_list) AND Date = '$fixed_date') ORDER BY Date DESC")  or die("cannot connect to workload");
			while($row = mysql_fetch_assoc($sql)) {
				$workload[]= $row;
			}

		for ($i = 0; $i <= sizeof($doctors)-1; $i++) {
			for ($j = 0; $j <= sizeof($workload)-1; $j++) {
				if ($doctors[$i]['Id'] == $workload[$j]['Staff_id']){
					$doctors[$i]['Worktime'] = $workload[$j]['Work_time'];
				}
			}
		}
		$min = $doctors[0]['Worktime']; 
		$this_doctor = $doctors[0]['Id'];
		$this_doctor_name = $doctors['0']['Name'];
		$this_doctor_surname = $doctors['0']['Surname'];
		foreach($doctors as $val) { 
			if ($val['Worktime'] < $min) {
				$min = $val['Worktime'];
				$this_doctor = $val['Id'];
				$this_doctor_name = $val['Name'];
				$this_doctor_surname = $val['Surname'];
			}
		}
	}	

	$sql = mysql_query("SELECT Id, Number, Unit_id FROM examimation_wards WHERE((Examination_type_id='$this_examination') AND (Id NOT IN
							(SELECT Examination_ward_id FROM unavailable_examination_wards WHERE(
							(Examination_type_id='$this_examination') AND (('$date_time' BETWEEN Start AND End) OR ('$end_date' BETWEEN Start AND End) OR
							(Start BETWEEN '$date_time' AND '$end_date') OR (End BETWEEN '$date_time' AND '$end_date'))))))") 
							or die("cannot connect to examimation_wards/unavailable_examination_wards"); 
				
	while($row = mysql_fetch_assoc($sql)) {
		$ward[] = $row;	
	}
	if ( !isset($ward) ) {
		echo "WARD AVAILABILITY ERROR";
		exit;
	}				
	$this_ward = $ward[0]['Id'];
	$this_ward_num = $ward[0]['Number'];
	$this_unit_id = $ward[0]['Unit_id'];
	
	$sql = mysql_query("SELECT Name, Building_id FROM units WHERE Id='$this_unit_id' LIMIT 1") or die("cannot connect to units");
	while($row = mysql_fetch_assoc($sql)) {
		$unit_name = $row['Name'];
		$building_id = $row['Building_id'];
	}
	
	$sql = mysql_query("SELECT Name, Address FROM buildings WHERE Id='$building_id' LIMIT 1") or die("cannot connect to buildings");
	while($row = mysql_fetch_assoc($sql)) {
		$building_name = $row['Name'];
		$building_address = $row['Address'];
	}

	$sql = mysql_query("SELECT Id, Work_time FROM workload WHERE(Staff_id='$this_doctor' AND Date = '$fixed_date') ORDER BY Date DESC")  or die("cannot connect to workload");
		while ($row = mysql_fetch_assoc($sql)){
			$work_time[] = $row;	
		}		
		if ( !isset($work_time)) {
			$workload_code = mt_rand(1000000000, 9999999999);
			$sql_insert_workload = "INSERT INTO workload VALUES('$workload_code', 
																'$fixed_date',
																'$this_doctor',
																'$first_worktime' )";	
														
			$res = mysql_query($sql_insert_workload) or die("ERROR_insert_workload");
		}else{
			$work_time_id = $work_time[0]['Id'];
			$this_work_time = $work_time[0]['Work_time'];
			$new_worktime = date("H:i:s", strtotime($this_work_time.''. $plus_worktime)); 
			mysql_query("UPDATE workload SET Work_time='$new_worktime' WHERE Id='$work_time_id'") or die("cannot update to workload");  
		}		

	$sql = "INSERT INTO examinations VALUES('$code',
											'$this_doctor',
											'$patient_id',
											'$this_examination', 
											'$date_time',
											'$end_date',
											'$this_ward',
											'$insurance',
											'$payment',
											'$confirmed',
											 NULL,
											'$viewed' )";	
											
	$res = mysql_query($sql) or die("ERROR1");
	
	$sql = "INSERT INTO unavailable_staff VALUES('$code',
												 '$this_doctor',
												 '$staff_category_id',
												 '$date_time',
												 '$end_date',									  
												 '$reason_doctor' )";
									  
	$res = mysql_query($sql) or die("ERROR");
	
	$sql = "INSERT INTO unavailable_examination_wards VALUES('$this_ward',
															 '$date_time',
															 '$end_date',									  
															 '$reason_ward',
															 '$code')";
									  
	$res = mysql_query($sql) or die("ERROR");	
	
	//e-mails disabled on this distribution for spam and privacy reasons
	//include("send_mail/mail_functions.php");
	
	newExamToPatient(4, $code, $date_time, $this_doctor, $patient_id, $this_ward_num, $unit_name, $building_name, $building_address);
	newExamToDoc(4, $code, $date_time, $this_doctor, $patient_id, $this_ward_num, $unit_name, $building_name, $building_address);

	mysql_close($con);	
	
	echo "DONE";
?>
