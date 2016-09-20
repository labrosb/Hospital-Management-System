<?php
	session_start();

	include("../server_processes/config.inc.php");
	
	include("../server_processes/send_mail/mail_functions.php");

	
	$con = mysql_connect(dbServer,dbUser,dbPass) or die('Connection-ERROR');

	mysql_select_db(dbDatabase) or die('Connection-ERROR'.$dbDatabase.'.');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	
	$action = $_POST['action'];
	$event = $_POST['event'];
				
	$result['result'] = 'error';
	$subReason = null;
	
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

	
	if($action == 'insert'){
		insertData($staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $dayOff);
	}
	else if($action == 'delete'){
		$result = deleteData($id, $staff_id);
	}
	else if($action == 'update'){
		$result = deleteData($id, $staff_id);
		insertData($staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $dayOff);	
	}
	else if($action == 'delete&insert'){
		insertData($staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $dayOff);		
		foreach ($events as &$eventId) {
			$thisId = $eventId['Id'];
			deleteData($thisId, $staff_id);
		}
		$result['multiDelete'] = true;
		$result['deleted'] = $events;	
	}
	
	
	mysql_close($con);
	$result['result'] = 'done';
	echo json_encode($result);	
	exit;
	

	function setAutoDayOff($auto_day_off) {
		if($auto_day_off != null){
			if($auto_day_off['dateExistance'] == "true"){
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
	

	function deleteData($id, $staff_id) {	
		$sql = mysql_query("SELECT Start, End, Reason FROM unavailable_staff WHERE Id ='$id' LIMIT 1") or die("Cannot connect to unavailable_staff");
		while($row = mysql_fetch_assoc($sql)) {
			$start = $row['Start'];
			$end = $row['End'];
			$thisEvent = $row['Reason'];
		}
		$sql_2 = mysql_query("SELECT Start_date, End_date FROM work_shifts WHERE Id ='$id' LIMIT 1") or die("Cannot connect to work_shifts");
		while($row = mysql_fetch_assoc($sql_2)) {
			$start = $row['Start_date'];
			$end = $row['End_date'];
			$thisEvent = 6;
		}
		if($thisEvent != 6){
			mysql_query("DELETE FROM unavailable_staff WHERE Id = $id ") or die("delete error 1");
		}	
		if($thisEvent == 1 || $thisEvent == 2){
			mysql_query("DELETE FROM vacation WHERE Id = $id ") or die("delete error 2");	
		}
		else if($thisEvent == 3){
			mysql_query("DELETE FROM days_off WHERE Id = $id ") or die("delete error 3");	

		}
		else if($thisEvent == 4){
			//deletedEmailPatient($start, $id);  //e-mails disabled on this distribution for spam and privacy reasons
			mysql_query("DELETE FROM examinations WHERE Exam_id = $id ") or die("delete error 4");
			mysql_query("DELETE FROM unavailable_examination_wards WHERE Event_id = $id ") or die("delete error 5");
			setWorkload("dec", $staff_id, $start, $end);			
		}		
		else if($thisEvent == 5){
			mysql_query("DELETE FROM on_duty WHERE Id = $id") or die("delete error 6");		
			$sql_setId = mysql_query("SELECT Id, Leave_date FROM days_off WHERE Parent_id = '$id' LIMIT 1") or die("cannot connect to days_off");
			$i = 0;
			while($row = mysql_fetch_assoc($sql_setId)) {
				$un_staff_id = $row['Id'];
				$dayOffTime = $row['Leave_date'];
				$i++;
			}
			if($i > 0){		
				//e-mails disabled on this distribution for spam and privacy reasons
				//deletedEventEmail($staff_id, $start, $end, $thisEvent, null, $id); 
				mysql_query("DELETE FROM days_off WHERE Parent_id = $id ") or die("delete error 7");
				mysql_query("DELETE FROM unavailable_staff WHERE Id = $un_staff_id ") or die("delete error 8");
				$result['childDate'] = date('Y-m-d', strtotime($dayOffTime));
				$result['childDelete'] = true;
				$result['childDeleted'] = $un_staff_id;	
			}
			
		}
		else if($thisEvent == 6){ // χωρίς να διαγραφούν οι εξετάσεις...
			mysql_query("DELETE FROM work_shifts WHERE Id = $id") or die("delete error 6");				
		}
		//e-mails disabled on this distribution for spam and privacy reasons
		//deletedEventEmail($staff_id, $start, $end, $thisEvent, null, null);
		if (isset($result)){
			return $result;
		}
		
	}
	
	
	
	function insertData ( $staff_id, $patient_id, $startDate, $endDate, $event, $subReason, $comments, $autoDayOff ) {
	
		$Id = mt_rand(1000000000, 9999999999);

		if($event != 6){
			$sql_1 = mysql_query("SELECT Specialty_id FROM medical_staff WHERE Id='$staff_id' LIMIT 1") or die("cannot connect to medical_staff");
			while($row = mysql_fetch_assoc($sql_1)) {
				$staff_category_id = $row['Specialty_id'];
			}	
			mysql_query("INSERT INTO unavailable_staff VALUES('$Id','$staff_id','$staff_category_id','$startDate','$endDate','$event' )") or die("cannot insert to unavailable_staff 2");
		
		}
		if ($autoDayOff['dayOffExistance'] == 'true'){
			$child_Id = mt_rand(1000000000, 9999999999);
			$parent = $Id;
			$dayOffStart = $autoDayOff['dayOffStart'];
			$dayOffEnd = $autoDayOff['dayOffEnd'];
			mysql_query("INSERT INTO unavailable_staff VALUES('$child_Id','$staff_id','$staff_category_id','$dayOffStart','$dayOffEnd','3' )") or die("cannot insert to unavailable_staff 1");
			mysql_query("INSERT INTO days_off VALUES('$child_Id','$staff_id','$dayOffStart','$dayOffEnd','','$parent' )") or die("ERROR auto day off");
			//e-mails disabled on this distribution for spam and privacy reasons
			//new_dayOff_curacy_workShift_email(3, $startDate, $endDate, $staff_id);
			
		}else{
			$thisParent = 0;
		}	
		if($event == 1 || $event == 2){ //άδεια
			mysql_query("INSERT INTO vacation VALUES('$Id','$event','$staff_id','$startDate','$endDate','$comments' )") or die("ERROR1-2");	
			//e-mails disabled on this distribution for spam and privacy reasons
			//newstartVacationEmail($event, $startDate, $endDate, $staff_id);
		}
		else if($event == 3){ //ρεπό
			mysql_query("INSERT INTO days_off VALUES('$Id','$staff_id','$startDate','$endDate','$comments','$thisParent' )") or die("ERROR3");
			//e-mails disabled on this distribution for spam and privacy reasons
			//new_dayOff_curacy_workShift_email($event, $startDate, $endDate, $staff_id);
		}
		else if($event == 4){ //εξετάσεις
			$ward_details = find_available_ward($staff_category_id, $startDate, $endDate, $event, $Id);
			$ward_id = $ward_details['ward_id'];
			$ward_number = $ward_details['ward_number'];
			$unit_name = $ward_details['unit_name'];
			$building_name = $ward_details['building_name'];
			$address = $ward_details['Address'];			
			mysql_query("INSERT INTO examinations VALUES('$Id','$staff_id','$patient_id','$subReason','$startDate','$endDate','$ward_id','0','0','0',NULL,'0')") or die("ERROR4");	;													
			setWorkload("inc", $staff_id, $startDate, $endDate);	
			newExamToPatient($event, $Id, $startDate, $staff_id, $patient_id, $ward_number, $unit_name, $building_name, $address);
			newExamToDoc($event, $Id, $startDate, $staff_id, $patient_id, $ward_number, $unit_name, $building_name, $address);
		}		
		else if($event == 5){ //εφημερία
			mysql_query("INSERT INTO on_duty VALUES('$Id','$staff_id','$startDate','$endDate','$subReason')") or die("ERROR5");
			//e-mails disabled on this distribution for spam and privacy reasons
			//new_dayOff_curacy_workShift_email($event, $startDate, $endDate, $staff_id);
		}
		else if($event == 6){ //βάρδια
			mysql_query("INSERT INTO  work_shifts VALUES('$Id','$staff_id','$startDate','$endDate','$subReason')") or die("ERROR6");
			//e-mails disabled on this distribution for spam and privacy reasons
			//new_dayOff_curacy_workShift_email($event, $startDate, $endDate, $staff_id);
		}

	}

	
	function find_available_ward($staff_category_id, $startDate, $endDate, $event, $event_id){
	
		$sql_1 = mysql_query("SELECT Id, Number, Unit_id FROM examimation_wards WHERE((Examination_type_id='$staff_category_id') AND (Id NOT IN
								(SELECT Examination_ward_id FROM unavailable_examination_wards WHERE(
								(Examination_type_id='$staff_category_id') AND (('$startDate' BETWEEN Start AND End) OR ('$endDate' BETWEEN Start AND End) OR
								(Start BETWEEN '$startDate' AND '$endDate') OR (End BETWEEN '$startDate' AND '$endDate')))))) LIMIT 1") 
					or die("cannot connect to examimation_wards/unavailable_examination_wards"); 					
		while($row = mysql_fetch_assoc($sql_1)) {
			$ward = $row;	
		}
		if ( !isset($ward) ) {
			$result['result'] = "WARD AVAILABILITY ERROR";
			echo json_encode($result);	
			exit;
		}				
		$this_ward = $ward['Id'];
		$result['ward_id'] = $this_ward;
		$this_ward_num = $ward['Number'];
		$result['ward_number'] = $this_ward_num;
		$this_unit_id = $ward['Unit_id'];
		
		$sql_2 = mysql_query("SELECT Name, Building_id FROM units WHERE Id='$this_unit_id' LIMIT 1") or die("cannot connect to units");
		while($row = mysql_fetch_assoc($sql_2)) {
			$unit_name = $row['Name'];
			$result['unit_name'] = $unit_name;
			$building_id = $row['Building_id'];
		}	
		$sql_3 = mysql_query("SELECT Name, Address FROM buildings WHERE Id='$building_id' LIMIT 1") or die("cannot connect to buildings");
		while($row = mysql_fetch_assoc($sql_3)) {
			$result['building_name'] = $row['Name'];
			$result['Address'] = $row['Address'];
		}
		
		mysql_query("INSERT INTO unavailable_examination_wards VALUES('$this_ward','$startDate','$endDate','$event','$event_id')") or die("ERROR");;
									  		
		return $result;
	}	
	
	
	function setWorkload($action, $this_doctor, $startDate, $endDate){	

		if ($action == "inc"){
			$do = "+";
		}else if($action == "dec"){
			$do = "-";	
		}
		$start_date = new DateTime($startDate);
		$since_start = $start_date->diff(new DateTime($endDate));
		$hours = $since_start->h;
		$minutes = $since_start->i;
		$workTime = $hours.":".$minutes.":00";	
		$workDateArray = explode(" " , $startDate);
		$workDate = $workDateArray[0];
		$extraMinutes = $hours*60 + $minutes. " minutes";
	

		$sql_workload = mysql_query("SELECT Id, Work_time FROM workload WHERE(Staff_id='$this_doctor' AND Date = '$workDate') ORDER BY Date DESC")  or die("cannot connect to workload");		
		while ($row = mysql_fetch_assoc($sql_workload)){
			$work_time[] = $row;	
		}		
		if ( !isset($work_time) ) {
			$workload_code = mt_rand(1000000000, 9999999999);
			mysql_query("INSERT INTO workload VALUES('$workload_code','$workDate','$this_doctor','$workTime' )") or die("ERROR_insert_workload");														
		}else{
			$work_time_id = $work_time[0]['Id'];
			$this_work_time = $work_time[0]['Work_time'];
			$new_worktime = date("H:i:s", strtotime($this_work_time.''.$do.''.$extraMinutes)); 
			mysql_query("UPDATE workload SET Work_time='$new_worktime' WHERE Id='$work_time_id'") or die("cannot update to workload");  
		}	
	
	}
	
?>