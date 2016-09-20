<?php

	session_start();

	include("config.inc.php");
	
	$usr=$_POST['username'];
	$pwd=$_POST['password'];

	$con = mysql_connect(dbServer,dbUser,dbPass) or die('Connection-ERROR');

	mysql_select_db(dbDatabase) or die('Connection-ERROR'.$dbDatabase.'.');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 

	
	$sql = mysql_query("SELECT * FROM users") or die("cannot connect to users");

	while($row = mysql_fetch_array($sql)) {
		if ( $row['Username'] == $usr && $row['Password'] == $pwd ){ 
			$_SESSION['Rights']=$row['Rights'];
	
			if ( $row['Rights'] == 'asth' ) {
				
				$sql_2 = mysql_query("SELECT * FROM patients WHERE Email='$usr'") 
							or die("cannot connect to patients");							
				$row = mysql_fetch_array($sql_2);
				
				$_SESSION['name']=$row['Name'] ;
				$_SESSION['surname']=$row['Surname'] ;
				$_SESSION['id']=$row['Id'] ;	
				
			}
			else if ( $row['Rights'] == 'staff' ) {
				
				$sql_2 = mysql_query("SELECT * FROM medical_staff WHERE Id='$usr'") 
							or die("cannot connect to patients");
				$row = mysql_fetch_array($sql_2);

				$_SESSION['name']=$row['Name'] ;
				$_SESSION['surname']=$row['Surname'] ;
				$_SESSION['id']=$row['Id'] ;
				$Specialty_id=$row['Specialty_id'] ;
				
				$sql_3 = mysql_query("SELECT * FROM medical_staff_categories WHERE Id='$Specialty_id'") 
							or die("cannot connect to patients");
				$row_2 = mysql_fetch_array($sql_3);	
				
				$_SESSION['specialty']=$row_2['Specialty_name'] ;	
				
			}			
			else if ( $row['Rights'] == 'manager' ) {
				$sql_2 = mysql_query("SELECT * FROM managers WHERE Id='$usr'") 
							or die("cannot connect to managers");
				$row = mysql_fetch_array($sql_2);

				$_SESSION['name']=$row['Name'] ;
				$_SESSION['surname']=$row['Surname'] ;
				$_SESSION['id']=$row['Id'] ;			
			}
			echo $_SESSION['Rights'];
			exit;
		}

	}
	
	echo "false";
?>