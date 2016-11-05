<?php
	// Updates the patient's information. 
	// The variable step defines which information will be changed.
	
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		

	check_if_patient();					// Checking session to prevent unauthorized access	

	if(!check_and_update_session()){	// Checking if session has expired and updates timout and id
		exit("EXPIRED");				// or destoys it and prevents access
	}
	
	$step = $_POST['step'];							//Inputs

	$address = $_POST['address'];
	$city = $_POST['city'];
	$postCode = $_POST['postCode'];
	$phone = $_POST['phone'];
	$cellPhone = $_POST['cellPhone'];
	$email = $_POST['email'];
	
	$password = $_POST['password'];	
	$oldPassword = $_POST['oldpass'];
	$newPassword = $_POST['newPassword'];	

	$hashed_password = password_hash($newPassword, PASSWORD_BCRYPT);

	$patient_id = $_SESSION['id'];
	
		
	$con = DB_Connect();							// Connecting to database
	
	if($step == 1)						// If data comes from the first form
	{						
		$query ='UPDATE patients SET Home_Phone = :home_phone, Mobile_phone = :cell_phone,
				 Address = :address, City = :city, Postal_code = :post_code WHERE Id=:id';
				 
		try {	
			$stmt = $con->prepare($query);			// Updates contact information
			
			$stmt->bindParam(':home_phone',$phone);
			$stmt->bindParam(':cell_phone',$cellPhone);
			$stmt->bindParam(':address',$address);
			$stmt->bindParam(':city',$city);
			$stmt->bindParam(':post_code',$postCode);		
			$stmt->bindParam(':id',$patient_id);
		
			$stmt->execute();
			
			$con=null;
			exit("DONE");
		} 
		catch (PDOException $e) { die($e); }			
	}
	else if($step == 2)								// If data comes from the second form
	{					
		$query ='SELECT patients.Email, users.Password FROM patients 
				INNER JOIN users ON patients.Email = users.Username
				WHERE Id = :id  LIMIT 1';
				
		$query_2 ='UPDATE patients SET Email = :email WHERE Id = :id';
		$query_3 ='UPDATE users SET Username = :email WHERE Username = :username';
		
		try {	
			//Query
			$stmt = $con->prepare($query);			// retrieves the e-mail (is also the username)
			$stmt->bindParam(':id',$patient_id); 
			$stmt->execute();	
			
			$old_email=null;
			$pass_verified=null;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$old_email = $row['Email'];
				$pass_verified = password_verify($password , $row['Password']);	
			}	
			
			if (!$pass_verified)					// Checks if the password is correct
			{ 
				$con=null;
				exit("WRONG PASS");
			}
			else{									// If password is correct, updates the e-mail
				//Query 2
				$stmt = $con->prepare($query_2);	// Updates the email inpatients table
				$stmt->bindParam(':email',$email);
				$stmt->bindParam(':id',$patient_id);
				$stmt->execute();
				
				//Query 3
				$stmt = $con->prepare($query_3);	// Updates the username in users table
				$stmt->bindParam(':email',$email);
				$stmt->bindParam(':username',$old_email);
				$stmt->execute();				

				$con=null;
				exit("DONE");			
			}
		}	
		catch (PDOException $e) { die($e); } 
	}	
	else if($step == 3)								// If data comes from the third form
	{				
		$query ='SELECT patients.Email, users.Password FROM patients 
				INNER JOIN users ON patients.Email = users.Username
				WHERE Id = :id  LIMIT 1';	
				
		$query_2 ='UPDATE users SET Password = :hashed_pass WHERE Username = :username';

		try {	
			$stmt = $con->prepare($query);			// Query to patients table to retrieve 
			$stmt->bindParam(':id',$patient_id);	// the e-mails (is also the username)
			$stmt->execute();
			
			$username=null;	
			$pass_verified=false;			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$username = $row['Email']; 		
				$pass_verified=password_verify($oldPassword , $row['Password']);	
			}
			
			if (!$pass_verified)					// Checks if the password is correct
			{ 
				$con=null;
				exit("WRONG PASS");
			}
			
			// Query 2
			$stmt = $con->prepare($query_2);		
			$stmt->bindParam(':hashed_pass',$hashed_password);
			$stmt->bindParam(':username',$username);			
			$stmt->execute();
			
			$con=null;
			exit("DONE");
		}	
		catch (PDOException $e) { die($e); } 
	}					
?>