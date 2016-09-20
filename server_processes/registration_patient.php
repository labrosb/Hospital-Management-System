<?php

	$name = $_POST["name"];
	$surname = $_POST["surname"];
	$sex = $_POST["sex"];
	$fathersName = $_POST["fathersName"];
	$birthDate = $_POST["birthDate"];
	$address = $_POST["address"];
	$city = $_POST["city"];
	$postCode = $_POST["postCode"];
	$phone = $_POST["phone"];
	$cellPhone = $_POST["cellPhone"];
	$email = $_POST["email"];
	$insurer = $_POST["insurer"];
	$insuranceCode = $_POST["insuranceCode"];
	$password = $_POST["password"];
	$rights = "asth";

	$code = mt_rand(10000000, 99999999);
	
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
									

	$sql = "INSERT INTO patients VALUES('$name',
										'$surname',
										'$code',
										'$password',
										'$sex', 
										'$fathersName',
										'$phone',
										'$cellPhone',
										'$email',
										'$address',
										'$city',
										'$postCode',
										'$fixed_date',
										'$insurer',
										'$insuranceCode')";	
											
	$res = mysql_query($sql) or die("ERROR2");
	
	$sql2 = "INSERT INTO users VALUES('$email',
									  '$password',
									  '$rights')";
									  
	$res2 = mysql_query($sql2) or die("ERROR3");
	
	echo "succeed";
	mysql_close($con);

?>
