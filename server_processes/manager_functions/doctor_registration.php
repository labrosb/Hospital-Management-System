<?php
	// Registers the new patient

	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		

	check_if_manager();						// Checking session to prevent unauthorized access

	if(!check_and_update_session()){		// Checking if session has expired and updates timout and id
		exit("EXPIRED");					// or destoys it and prevents access
	}	
	
	date_default_timezone_set('Europe/Athens');
	
	$currentDate =  date("Y-m-d");
							
	$name = $_POST["name"];					// Inputs
	$surname = $_POST["surname"];
	$sex = $_POST["sex"];
	$fathers_name = $_POST["fathersName"];
	$mothers_name = $_POST["mothersName"];
	$birth_date = $_POST["birthDate"];
	$address = $_POST["address"];
	$city = $_POST["city"];
	$post_code = $_POST["postCode"];
	$phone = $_POST["phone"];
	$cell_phone = $_POST["cellPhone"];
	$work_phone = $_POST["workPhone"];
	$email = $_POST["email"];
	$specialty = $_POST["specialty"];
	$biog = $_POST["biog"];
	
	$password = $_POST["password"];
	$hashed_pass = password_hash($password, PASSWORD_BCRYPT);	
	$rights = "doctor";

	$code = mt_rand(10000, 99999);			// Doctors's id generator
	
	$dateArray = explode( "/" , $birth_date);
	$fixed_date = $dateArray[2]."-".$dateArray[1]."-".$dateArray[0];

	$con = DB_Connect();					// Connecting to database	

																// Registers doctor's personal information
	$query_1 = 'INSERT INTO medical_staff(Name, Surname, Id, Specialty_id, sex, Fathers_name, Mothers_name, 
				Work_phone, Home_Phone, Mobile_phone, Address, City, Postal_code, Email, Birth_date, Hire_date, Resume ) 
				VALUES(:name, :surname, :id, :speciality, :sex, :fathers_name, :mothers_name, :work_phone, :phone,
				:cell_phone, :address, :city, :post_code, :email, :fixed_date, :currentDate, :biog)';
													
																// Registers log-in credentials
	$query_2 ='INSERT INTO users VALUES(:id,:pass,:rights)';
	
	try {	
		$stmt = $con->prepare($query_1);						// Occupying the selected doctor 
		
		$stmt->bindParam(':name',$name);						// for the specific date/time		
		$stmt->bindParam(':surname',$surname);	
		$stmt->bindParam(':id',$code);	
		$stmt->bindParam(':speciality',$specialty);				
		$stmt->bindParam(':sex',$sex);	
		$stmt->bindParam(':fathers_name',$fathers_name);
		$stmt->bindParam(':mothers_name',$mothers_name);
		$stmt->bindParam(':work_phone',$work_phone);	
		$stmt->bindParam(':phone',$phone);	
		$stmt->bindParam(':cell_phone',$cell_phone);	
		$stmt->bindParam(':address',$address);	
		$stmt->bindParam(':city',$city);	
		$stmt->bindParam(':post_code',$post_code);			
		$stmt->bindParam(':email',$email);
		$stmt->bindParam(':fixed_date',$fixed_date);
		$stmt->bindParam(':currentDate',$currentDate);
		$stmt->bindParam(':biog',$biog);						
		
		$stmt->execute();	

		$stmt = $con->prepare($query_2);						// Occupying the selected doctor 
		
		$stmt->bindParam(':id',$code);							// for the specific date/time		
		$stmt->bindParam(':pass',$hashed_pass);	
		$stmt->bindParam(':rights',$rights);			
		
		$stmt->execute();
	}				
	catch (PDOException $e) { die($e); }									  
	
	$con=null;
	
	exit("success");
?>
