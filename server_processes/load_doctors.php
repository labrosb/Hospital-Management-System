<?php
	session_start();
	
	date_default_timezone_set('UTC');
	$thisYear = date("Y");

	$mydate=$_POST['date'];
	$time=$_POST['time'];
	$exams_type=$_POST['exams_type'];
	$doctors = null;	
	$dateArray = explode( "/" , $mydate);
	$timeArray = explode( ":" , $time);
	if(isset($dateArray[2]) &&  isset($timeArray[1]) ){
		$date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0]." ".$timeArray[0].":".$timeArray[1].":00";
	}
	if (!isset($date)){
		echo json_encode('DATE ERROR');
		exit;
	}
	include("config.inc.php");
	

	$con = mysql_connect(dbServer,dbUser,dbPass) or die('cannot connect to database1');

	mysql_select_db(dbDatabase) or die('cannot connect to database2');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
//this can be replaced in the client	
	$sql = mysql_query("SELECT Id, Staff_category_id FROM examination_types WHERE Name='$exams_type'") or die("cannot connect to database1");
		while($row = mysql_fetch_assoc($sql)) {
			$all[] = $row;
			$staff_category_id = $row['Staff_category_id'];
	}	

	if (!isset($all)){
		echo json_encode('EXAMS ERROR');
		exit;
	}
//-------------------------------------------	
	$sql_2 = mysql_query("SELECT Id, Name, Surname, Photo, Specialty_id, Sex, Birth_Date, Biography FROM medical_staff WHERE( (Specialty_id='$staff_category_id') AND (Id NOT IN
						(SELECT Staff_id FROM unavailable_staff WHERE((Specialty_id='$staff_category_id') AND ('$date' BETWEEN Start AND End)))) AND (Id IN 
						(SELECT Staff_id FROM work_shifts WHERE('$date' BETWEEN Start_date AND End_date ))))") 
	or die("cannot connect to database3");
	while($row_2 = mysql_fetch_assoc($sql_2)) {
		$doctors[] = $row_2;
	}
	if ( sizeof($doctors) == 0 ) {
		echo json_encode('NULL');
		exit;
	}
	$cnt = 0;
	foreach ($doctors as $value) {
		$birthDateArray = explode( " " , $value['Birth_Date']);
		$birthYear = $birthDateArray[0];	
		$doctors[$cnt]['Age'] = $thisYear - $birthYear;
		$specialty_id = $value['Specialty_id'];
		$sql = mysql_query("SELECT Specialty_name FROM  medical_staff_categories WHERE id='$specialty_id' ") or die("cannot connect to database4");
		while($row = mysql_fetch_assoc($sql)){		
			$doctors[$cnt]['Specialty'] = $row['Specialty_name'];
			
		}
		
		unset ($doctors[$cnt]['Birth_Date']);
		unset ($doctors[$cnt]['Specialty_id']);
		
		$cnt++;
	}

	echo json_encode($doctors);
	//print_r ($doctors);	
	
	mysql_close($con);
?>