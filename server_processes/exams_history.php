<?php
	session_start();
	
	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 	
	
	$id = $_SESSION['id'];
	$limit1 = $_POST['limit1'];
	$limit2 = $_POST['limit2'];
		
	$exams = $_POST['exams_types'];
	$from = $_POST['from'];	
	$to = $_POST['to'];	

	$exams_query = null;	
	$from_query = null;
	$to_query = null;	
	
	$data = null;
		
		if ($from != 'default' && $from != 'From' && $from !=''){
		
			$dateFromArray = explode( "/" , $from);
			$fixed_date = $dateFromArray[2]."-".$dateFromArray[1]."-".$dateFromArray[0];
			$from_date_time = $fixed_date." 00:00:01";	
			
			$from_query =  "AND Start_time > '".$from_date_time."'"; 
		}
		
		if ($to != 'default' && $to != 'To' && $to !=''){

			$dateToArray = explode( "/" , $to);
			$fixed_date = $dateToArray[2]."-".$dateToArray[1]."-".$dateToArray[0];
			$to_date_time = $fixed_date." 23:59:00";		

			$to_query =  "AND Start_time < '".$to_date_time."'";
		}
		
		if ($exams != 'default' && $exams !=''){
			$exams_query = "AND Exam_type_id IN(".$exams.")"; 
		}
		
		$sql = mysql_query("SELECT Exam_id, Staff_id, Exam_type_id, Start_time, Results FROM examinations 
							WHERE (Patient_id='$id' AND Comfirmed='1' AND Viewed='1' 
							".$exams_query." ".$from_query." ".$to_query.") 
							ORDER BY Start_time DESC LIMIT $limit1, $limit2") 
				or die("cannot connect to examinations");
		
		$cnt=0;		
		while($row = mysql_fetch_assoc($sql)) {
			$data[] = $row;
			$this_date = $row['Start_time'];
			$split_date_time = explode( " " , $this_date);
			$mydate = $split_date_time[0];
			$mytime = $split_date_time[1];
			$dateArray = explode( "-" , $mydate);
			$timeArray = explode( ":" , $mytime);
			$date = $dateArray[2]."/".$dateArray[1]."/".$dateArray[0];
			$time = $timeArray[0].":".$timeArray[1];
			
			$data[$cnt]['Date'] = $date;
			$data[$cnt]['Time'] = $time;
			$cnt++;
		}	
		
	$notif=sizeof($data);
	if ($notif == 0){
		if ($limit1 == 0){
			echo json_encode('NO NEW EXAMS');
		}else{			
			echo json_encode('NO MORE EXAMS');
		}		
		exit;		
	}
	
	$cnt=0;
	foreach ($data as &$value) {
		$type_id = $value['Exam_type_id'];
		$doctor_id = $value['Staff_id'];
		$sql = mysql_query("SELECT Name FROM examination_types WHERE Id='$type_id' LIMIT 1") or die("cannot connect to examination_types");
		while($row = mysql_fetch_assoc($sql)) {
			$data[$cnt]['Examination_name'] = $row['Name'];
		}
		$sql = mysql_query("SELECT Name, Surname FROM medical_staff WHERE Id='$doctor_id' LIMIT 1") or die("cannot connect to medical_staff");	
		while($row = mysql_fetch_assoc($sql)) {		
			$data[$cnt]['Doctor_name'] = $row['Name'];	
			$data[$cnt]['Doctor_surname'] = $row['Surname'];				
		}	
		$cnt++;		
	}

	mysql_close($con);	
	
	echo json_encode($data);
	//print_r($data);

?>