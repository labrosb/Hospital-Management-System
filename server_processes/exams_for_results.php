<?php
	session_start();

	$id = $_SESSION['id'];
	$limit1 = $_POST['limit1'];
	$limit2 = $_POST['limit2'];

	date_default_timezone_set('Europe/Athens');

	$currentDate =  date("Y-m-d H:i:s");
	
	
	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	//Exams type - specialty Staff_id
	$sql = mysql_query("SELECT Exam_id, Patient_id, Exam_type_id, Start_time, Results FROM examinations WHERE Staff_id='$id' AND Comfirmed='0' AND End_time < '$currentDate' ORDER BY Start_time DESC LIMIT $limit1, $limit2") or die("cannot connect to examinations");
		$cnt=0;		
		while($row = mysql_fetch_assoc($sql)) {
			$data[] = $row;
			//$data[$cnt][examId] = 
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
			if (!isset($all_exams )){
				$all_exams = "'".$row['Exam_id']."'";
			}else{
				$all_exams = $all_exams .",'".$row['Exam_id']."'";
			}
			$cnt++;
		}	

	$notif=empty($data);
	if ($notif){
		if ($limit1 == 0){
			echo json_encode('NO NEW EXAMS');
		}else{			
			echo json_encode('NO MORE EXAMS');
		}		
		mysql_close($con);	
		exit;		
	}
	
	$cnt=0;
	foreach ($data as &$value) {
		$type_id = $value['Exam_type_id'];
		$patient_id = $value['Patient_id'];
		$sql = mysql_query("SELECT Name FROM examination_types WHERE Id='$type_id' LIMIT 1") or die("cannot connect to examination_types");
		while($row = mysql_fetch_assoc($sql)) {
			$data[$cnt]['Examination_name'] = $row['Name'];
		}
		$sql = mysql_query("SELECT Name, Surname, Id FROM patients WHERE Id='$patient_id' LIMIT 1") or die("cannot connect to patients");	
		while($row = mysql_fetch_assoc($sql)) {		
			$data[$cnt]['patient_id'] = $row['Id'];		
			$data[$cnt]['patient_name'] = $row['Name'];	
			$data[$cnt]['patient_surname'] = $row['Surname'];				
		}	
		$cnt++;		
	}
		
	mysql_close($con);	
	
	echo json_encode($data);
	
	//print_r($data);

?>