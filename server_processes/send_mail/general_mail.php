<?php
	session_start();

	$name = $_POST['name'];
	$email = $_POST['email'];
	$text = $_POST['text'];

	include("mail_functions.php");
	if (isset($_SESSION['Rights'])){
		if($_SESSION['Rights'] == 'asth'){
			$subj = 'Message from Patient : '.$name;
		}else if($_SESSION['Rights'] == 'staff'){
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
