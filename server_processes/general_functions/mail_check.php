<?php
	// Checks if e-mail already exists
	
	session_start();

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");	// Connection to database
	
	$email=$_POST['email'];
		
	$con = DB_Connect();	// Connecting to database 
	
	try {					// Search query
		$stmt=$con->prepare('SELECT * FROM users WHERE Username = :username LIMIT 1');		
		$stmt->execute(array('username' => $email));		
					
		$rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		$num_rows = count( $rows );	
	
		$con = null;
		
		if($num_rows == 0)
		{
			exit("NO");
		}
		else{
			exit("YES");
		}							
	} 
	catch (PDOException $e) { die("Cannot connect to examination_types"); }				
?>
