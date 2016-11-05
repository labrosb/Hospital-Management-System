<?php
	session_start();

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions		
		
	$name = $_POST['name'];
	$email = $_POST['email'];
	$text = $_POST['text'];

	include_once("mail_functions.php");
	if (isset($_SESSION['Rights'])){
		if(!check_and_update_session()){	// Checking if session has expired and updates timout and id
			echo "EXPIRED";					// or destoys it and prevents access
			exit;
		}	
		if($_SESSION['Rights'] == 'asth'){
			$subj = 'Message from Patient : '.$name;
		}else if($_SESSION['Rights'] == 'doctor'){
			$subj = 'Message from Doctor : '.$name;
		}else if($_SESSION['Rights'] == 'manager'){
			$subj = 'Message from Administrative staff : '.$name;
		}
	}else{
		$subj = 'Message from User : '.$name;
	}
		
	generalMail($name, $email, $subj, $text);
	
	echo "OK";
?>
