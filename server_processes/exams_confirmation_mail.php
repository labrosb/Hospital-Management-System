<?php session_start();
 
 //Ανάκτηση e-mail χρήστη.
 $sql_patient = mysql_query("SELECT Email FROM patients WHERE Id='$patient_id' LIMIT 1") or die("cannot connect to patients");
	while($row_patient = mysql_fetch_assoc($sql_patient)) {
		$email = $row_patient['Email'];
	}
					
require_once('C:\xampp\php\PEAR\Mail.php');
require_once('C:\xampp\php\PEAR\Mail\mime.php');

$from = 'labros_b@yahoo.com';
$to      = $email;
$subjectText = 'Confirmation';

$host = "smtp.mail.yahoo.com";
$username = 'labros_b';
$password = 'olympiakos1925';

include 'send_mail/mail_contents/exams_patient_mail.php';

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