<?php
	session_start();

	$id=$_SESSION['id'];
	
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
	
	////Exams type - Speciality
	$sql = mysql_query("SELECT Exam_type_id FROM examinations WHERE Staff_id='$id' && Comfirmed='0' && End_time < '$currentDate'") or die("cannot connect to examination_types");
	$num_rows = mysql_num_rows($sql);

	mysql_close($con);	
	
	echo json_encode($num_rows);
?>
