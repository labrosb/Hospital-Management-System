<?php
	session_start();

	$id=$_SESSION['id'];
	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	//Exams type - Speciality
	$sql = mysql_query("SELECT Exam_type_id FROM examinations WHERE Patient_id='$id' && Comfirmed='1' && Viewed='0'") or die("cannot connect to examination_types");
	$num_rows = mysql_num_rows($sql);

	mysql_close($con);	
	
	echo json_encode($num_rows);
?>
