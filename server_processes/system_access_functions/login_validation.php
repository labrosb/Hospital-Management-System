<?php
	// Checks if the given credentials corresponds to a user 
	// and gives the corresponding rights to the user or denies further access.
	// Also saves to session the ID to remain logged in and the name, surname 
	// and proffesion to be presented in the log-in info
		
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");	// Connection to database
	
	$con = DB_Connect();									// Connecting to database
	
	$usr=$_POST['username'];
	$pwd=$_POST['password'];

	// Checks if username and password correspond to a registration
	try {
		$stmt=$con->prepare('SELECT * FROM users WHERE Username = :username LIMIT 1');
		$stmt->execute(array('username' => $usr));
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$pass_verified=password_verify ($pwd , $row['Password'] );
		
			if ($pass_verified )
			{ 
				$_SESSION['Rights'] = $row['Rights'];
				$_SESSION['timeout'] = time();				// This is used to be able to destroy the session
				$_SESSION['changed'] = time();				// This is used to be able to regenerate the session
				get_user_data($con, $usr);
			}
		}
		$con=null;
		
	} catch (PDOException $e) { die("Cannot connect to users"); }

	
	function get_user_data($con, $usr)
	{
		if ($_SESSION['Rights'] == 'asth')						// If registration found gives patient rights
		{
			try {
				$stmt=$con->prepare('SELECT Id, Name, Surname FROM patients WHERE Email = :email LIMIT 1');
				$stmt->execute(array('email' => $usr));		
					
				while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 
				{
					$_SESSION['id']=$row['Id']; 					
					$_SESSION['name']=$row['Name'];
					$_SESSION['surname']=$row['Surname'];
				}			
				$con = null;
				exit("is_patient");
					
			} catch (PDOException $e) { die("Cannot connect to patients");}			
		}
		else if ($_SESSION['Rights'] == 'doctor')				// If registration found gives doctor rights
		{
			try {
				$stmt = $con->prepare('SELECT Id, Name, Surname, Specialty_id FROM medical_staff WHERE Id= :id LIMIT 1');
				$stmt->execute(array('id' => $usr));		
					
					while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 
					{
						$_SESSION['id']=$row['Id']; 					
						$_SESSION['name']=$row['Name'];
						$_SESSION['surname']=$row['Surname'];
						$_SESSION['specialty'] = $row['Specialty_id'];
					}			
					$con = null;
					exit("is_doctor");
					
			} catch (PDOException $e) { die("Cannot connect to medical_staff");}					
		}
		else if ($_SESSION['Rights'] == 'manager')				// If the registration found gives manager rights				
		{
			try {
				$stmt=$con->prepare('SELECT Id, Name, Surname FROM managers WHERE Id = :id LIMIT 1');
				$stmt->execute(array('id' => $usr));		
					
				while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 
				{
					$_SESSION['id']=$row['Id']; 					
					$_SESSION['name']=$row['Name'];
					$_SESSION['surname']=$row['Surname'];
				}			
				$con = null;
				exit("is_manager");
					
			} catch (PDOException $e) { die("Cannot connect to managers");}					
		}
	}	
	$con = null;				
	exit("false");
?>