<?php session_start();
 
	 //Retrieve user's e-mail, connects to the mail server and sends the text included 
	 //(disabled on this distribution for privacy and spam reasons)
	
	require_once('C:\xampp\php\PEAR\Mail.php');
	require_once('C:\xampp\php\PEAR\Mail\mime.php');	 
	
	$select_patient ='SELECT Email FROM patients WHERE Id = :id LIMIT 1';

	try{
		$stmt = $con->prepare($select_patient);
		$stmt->bindParam(':id',$patient_id);
		$stmt->execute();	 
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$email = $row_patient['Email'];
		}
	}
	catch (PDOException $e) { die($e); }					

	$from = 'Hospital xxx'; 
	$to      = $email;
	$subjectText = 'Confirmation'; 

	$host = "stmp.live.com"; 
	$username = 'labros_b@hotmail.com';		//removed on this distribution for privacy reasons
	$password = 'labros1986';				//removed on this distribution for privacy reasons

	include 'email_functions/mail_contents/exams_patient_mail.php';

	// Create the Mail_Mime object:
	$mime = new Mail_Mime();

	// Set the email body:
	$mime->setHTMLBody($html);

	// Set the headers:
	$mime->setFrom($from);
	$subject = $mime->setSubject($subjectText);

	// Get the formatted code:
	$body = $mime->get( array('html_charset' => 'utf-8',
							  'text_charset' => 'utf-8') );

	$subject = $mime->get( array('text_charset' => 'utf-8') );

	$headers = array ('From' => $from,
					  'To' => $to,
					  'Subject' => $subject,
				);

	$smtp = Mail::factory('smtp',
	   array ('host' => $host,
			  'auth' => true,
			  'username' => $username,
			  'password' => $password));

	$headers = $mime->headers();


	 $mail = $smtp->send($to, $headers, $body);
 
		
?>