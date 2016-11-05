<?php
	// Updates the doctor's information. 
	// The variable step defines which information will be changed.
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		
	
	check_if_doctor_manager();			// Checking session to prevent unauthorized access

	if(!check_and_update_session())
	{									// Checking if session has expired and updates timout and id
		exit("EXPIRED");					// or destoys it and prevents access
	}
	
	$step = $_POST["step"];				// Inputs

	$address = $_POST["address"];
	$city = $_POST["city"];
	$postCode = $_POST["postCode"];
	$phone = $_POST["phone"];
	$cellPhone = $_POST["cellPhone"];
	$email = $_POST["email"];
	
	$biog = $_POST["biog"];
	
	$password = $_POST["password"];
	$oldPassword = $_POST["oldpass"];

	$hashed_password = password_hash($password, PASSWORD_BCRYPT);

	$staff_id = $_SESSION['id'];	
	
	$con = DB_Connect();				// Connecting to database
	
	if($step == 1)
	{		
		$query ='UPDATE medical_staff SET Home_Phone = :home_phone, Mobile_phone = :cell_phone,
				Address = :address, City = :city,Postal_code = :post_code, Email = :email WHERE Id=:id';
				 
		try {	
			$stmt = $con->prepare($query);					// Updates contact information
			
			$stmt->bindParam(':home_phone',$phone);
			$stmt->bindParam(':cell_phone',$cellPhone);
			$stmt->bindParam(':address',$address);
			$stmt->bindParam(':city',$city);
			$stmt->bindParam(':post_code',$postCode);
			$stmt->bindParam(':email',$email);
			$stmt->bindParam(':id',$staff_id);
		
			$stmt->execute();
			
			$con = null;
			exit("DONE");
		} 
		catch (PDOException $e) { die($e); }			
	}
	else if($step == 2)
	{		
		$query = 'UPDATE medical_staff SET Resume= :biog WHERE Id = :id';
		
		try {	
			$stmt = $con->prepare($query);					// Updates resume
			
			$stmt->bindParam(':biog',$biog);
			$stmt->bindParam(':id',$staff_id);
		
			$stmt->execute();
			
			$con = null;
			exit("DONE");
		} 
		catch (PDOException $e) { die($e); }			
	}
	else if($step == 3)
	{		
		$query = 'SELECT Password FROM users WHERE Username = :username LIMIT 1';  
				
		try {	
			$stmt = $con->prepare($query);	 						
			
			$stmt->bindParam(':username',$staff_id);			
			$stmt->execute();
			
			$pass_verified=false;		
			while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 		// Checks if the password is correct
			{
				$pass_verified = password_verify ($oldPassword, $row['Password'] );
			}
			if(!$pass_verified)
			{
				$con = null;
				exit("WRONG PASS");
			}
			else{
				$query = 'UPDATE users SET Password = :password WHERE Username = :id';
			
				$stmt = $con->prepare($query);	 			// Updates the password		
					
				$stmt->bindParam(':password',$hashed_password);
				$stmt->bindParam(':id',$staff_id);	

				$stmt->execute();		
					
				$con = null;
				exit("DONE");
			}
			
		}	
		catch (PDOException $e) { die($e); }
	}		
?>