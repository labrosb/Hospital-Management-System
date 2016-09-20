<?php

	date_default_timezone_set('Europe/Athens');
	
	$currentDate =  date("Y-m-d");
	
	$name = $_POST["name"];
	$surname = $_POST["surname"];
	$sex = $_POST["sex"];
	$fathersName = $_POST["fathersName"];
	$mothersName = $_POST["mothersName"];
	$birthDate = $_POST["birthDate"];
	$address = $_POST["address"];
	$city = $_POST["city"];
	$postCode = $_POST["postCode"];
	$phone = $_POST["phone"];
	$cellPhone = $_POST["cellPhone"];
	$workPhone = $_POST["workPhone"];
	$email = $_POST["email"];
	$specialty = $_POST["specialty"];
	$biog = $_POST["biog"];
	
	$password = $_POST["password"];
	$rights = "doctor";

	$code = mt_rand(100000, 999999);
	
	$dateArray = explode( "/" , $birthDate);
	$fixed_date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];

	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR1");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
									

	$sql = "INSERT INTO medical_staff VALUES('$name',
											'$surname',
											'$code',
											'$password',
											'$specialty',
											'$sex', 
											'$fathersName',
											'$mothersName',
											'$workPhone',
											'$phone',
											'$cellPhone',
											'$address',
											'$city',
											'$postCode',
											'$email',
											'$fixed_date',
											'$currentDate',
											'$biog',
											'',
											'')";	
											
	$res = mysql_query($sql) or die("ERROR");
	
	$sql2 = "INSERT INTO users VALUES('$code',
									  '$password',
									  '$rights')";
									  
	$res2 = mysql_query($sql2) or die("ERROR2");
	
	echo "success";


?>
