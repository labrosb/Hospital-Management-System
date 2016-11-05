<?php
	// Registers the new patient
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");	// Connection to database

	$name = $_POST["name"];						// Inputs
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
	$hashed_pass = password_hash($password, PASSWORD_BCRYPT);
	$rights = "asth";

	$code = mt_rand(10000000, 99999999); 			// patient's id generator
	
	$dateArray = explode( "/" , $birthDate);		// Date to understandable by the server form
	$fixed_date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];

	
	$con = DB_Connect();							// Connecting to database
													
													// Query
	$query ='INSERT INTO patients(Name, Surname, Id, Sex, Fathers_name, Home_phone, Mobile_phone,
								Email, Address, City, Postal_code, Birth_date, Insurance, Insurance_code) 		
			VALUES( :name, :surname, :code, :sex, :fathersName, :phone, :cellPhone, :email, :address, :city, :postCode, :fixed_date, :insurer, :insuranceCode)';
													
													// Query2
	$query_2='INSERT INTO users(Username, Password, Rights)VALUES(:email, :password, :rights)';
			
			
	try {
		$stmt = $con->prepare($query);				// Registers patient's personal information	
		
		$stmt->bindParam(':name',$name);
		$stmt->bindParam(':surname',$surname);
		$stmt->bindParam(':code',$code);
		$stmt->bindParam(':sex', $sex);
		$stmt->bindParam(':fathersName',$fathersName);
		$stmt->bindParam(':phone',$phone);
		$stmt->bindParam(':cellPhone',$cellPhone);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':city', $city);
		$stmt->bindParam(':postCode', $postCode);
		$stmt->bindParam(':fixed_date', $fixed_date);
		$stmt->bindParam(':insurer', $insurer);
		$stmt->bindParam(':insuranceCode', $insuranceCode);
		
		$stmt->execute();
		
		$stmt = $con->prepare($query_2);				// Registers values for login

		$stmt->bindParam(':email', $email);		
		$stmt->bindParam(':password',$hashed_pass);
		$stmt->bindParam(':rights',$rights);
		
		$stmt->execute();		
	} 
	catch (PDOException $e) { die("Error"); }													
	
	$con=null;
	
	exit("succeed");

?>
