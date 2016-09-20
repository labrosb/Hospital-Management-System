<?php
	session_start();
	
	$id = $_POST['id'];

	include("config.inc.php");
	

	$con = mysql_connect(dbServer,dbUser,dbPass) or die('cannot connect to database');

	mysql_select_db(dbDatabase) or die('cannot connect to database');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 


	$sql = mysql_query("SELECT * FROM  medical_staff WHERE Id='$id' LIMIT 1") or die("cannot connect to medical_staff");
	while($row= mysql_fetch_assoc($sql)) {
		$doctors[] = $row;
		$specialty_id = $row['Specialty_id'];
	}

	$sql = mysql_query("SELECT Specialty_name FROM  medical_staff_categories WHERE id='$specialty_id' LIMIT 1") or die("cannot connect to database2");
		while($row = mysql_fetch_assoc($sql)){		
			$doctors[0]['Specialty'] = $row['Specialty_name'];
			
		}

	echo json_encode($doctors);
	
	mysql_close($con);
?>