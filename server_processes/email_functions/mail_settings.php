<?php 

$from = ''; //Sender's address
$to      = $email;

$host = ""; //E-mail client here
$username = ""; //E-mail username
$password = ""; //e-mail password here


// Create the Mail_Mime object:
$mime = new Mail_Mime();

// Set the email body:
$mime->setHTMLBody($html);

// Set the headers:
$mime->setFrom($from);
$subject = $mime->setSubject("=?utf-8?B?".base64_encode($subjectText)."?=");

// Get the formatted code:
$body = $mime->get( array('html_charset' => 'utf-8',
						  'text_charset' => 'utf-8') );

$subject = $mime->get( array('text_charset' => 'utf-8') );

$headers = array ('From' => $from,
				  'To' => $to,
				  'Subject' => $subject
			);

$smtp = Mail::factory('smtp',
   array ('host' => $host,
		  'auth' => true,
		  'username' => $username,
		  'password' => $password));

$headers = $mime->headers();


 $mail = $smtp->send($to, $headers, $body);
 
		
?>