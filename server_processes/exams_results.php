<?php
	session_start();

	$id = $_SESSION['id'];
	
	if(isset( $_POST['limit1'])){
		$limit1 = $_POST['limit1'];
		echo $limit1;
		echo "============================";
	}
	
	$data = null;
	$all_exams = null;
	
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
	$sql = mysql_query("SELECT Exam_id, Staff_id, Exam_type_id, Start_time, Results FROM examinations WHERE Patient_id='$id' AND Comfirmed='1' AND Viewed='0' ORDER BY Start_time DESC LIMIT 3") or die("cannot connect to examinations");
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
			if (!isset($all_exams)){
				$all_exams = "'".$row['Exam_id']."'";
			}else{
				$all_exams = $all_exams .",'".$row['Exam_id']."'";
			}
			$cnt++;
		}	

	$notif=sizeof($data);
	if ($notif == 0){
		if (!isset($limit1) || $limit1 == 0){
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
		
	$sql = mysql_query("UPDATE examinations SET Viewed='1' WHERE Exam_id IN($all_exams)") or die("cannot insert to examinations");

	mysql_close($con);	
	
	echo json_encode($data);
	
	//print_r($data);

?>