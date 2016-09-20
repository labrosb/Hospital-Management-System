<?php
	session_start();
	
	$step = $_POST["step"];

	$birthDate = $_POST["birthDate"];
	$address = $_POST["address"];
	$city = $_POST["city"];
	$postCode = $_POST["postCode"];
	$phone = $_POST["phone"];
	$cellPhone = $_POST["cellPhone"];
	$email = $_POST["email"];
	
	$password = $_POST["password"];
	$oldPassword = $_POST["oldpass"];

	
	$patient_id = $_SESSION['id'];
	

	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	if($step == 1){
		$sql = "UPDATE patients SET Home_phone='$phone', Mobile_phone='$cellPhone', Address='$address', City='$city', Postal_code='$postCode' WHERE Id ='$patient_id'";
		$res = mysql_query($sql) or die ("ERROR");
		echo "DONE";
	}
	else if($step == 2){
		$sql = mysql_query("SELECT * FROM patients WHERE( Id='$patient_id' AND Password='$password' ) LIMIT 1")  
							or die("ERROR");			
		$num_rows = mysql_num_rows($sql);	
		if ( $num_rows == 0 ) {  
			echo "WRONG PASS";
		}else{
			$sql_2 = "UPDATE patients SET Email ='$email' WHERE Id ='$patient_id'";
			$res = mysql_query($sql_2) or die ("ERROR");
			echo "DONE";

		}		
		
	}	
	else if($step == 3){
		$sql = mysql_query("SELECT * FROM patients WHERE( Id='$patient_id' AND Password='$oldPassword' ) LIMIT 1")  
							or die("ERROR");			
		$num_rows = mysql_num_rows($sql);	
		if ( $num_rows == 0 ) {  
			echo "WRONG PASS";
		}else{
			$sql_2 = "UPDATE patients SET Password ='$password' WHERE Id ='$patient_id'";
			$res = mysql_query($sql_2) or die ("ERROR");
			echo "DONE";

		}	
	}		
	mysql_close($con);	
	
?>