<?php

	date_default_timezone_set('Europe/Athens');
	
	function generalMail($name, $mail, $subject, $content){
		$email = 'labros_b@hotmail.com';
		$subjectText = $subject;
		$html = '<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<style type="text/css">	
						 h2{
							font-size: 18px;
							font-weight: bold; 
							padding: 2px;
							background-color: #d1dafc;
							-webkit-border-radius: 20px;
							-moz-border-radius: 20px;	
							border-radius: 20px;
						 }
						.mail_content { 
								margin-top:10px;
								color: #404040;
								width:500px;
								padding-bottom:10px;
								text-align:center;
								background-color: #e8edff;
								border:20px solid #e8edff;
								-webkit-border-radius: 20px;
								-moz-border-radius: 20px;	
								border-radius: 20px;								
							}
						p{font-size:16px;}
					</style>						
				</head>
				<body>	<div class="mail_content">
					<h2>'.$name.' ('.$email.')</h2></br>
					<p>'.$content.'</p>
				</div>';


		require_once('C:\xampp\php\PEAR\Mail.php');
		require_once('C:\xampp\php\PEAR\Mail\mime.php');
		
		include 'mail_settings.php';
		
	}


	function newExamToPatient($event, $code, $startDate, $staff_id, $patient_id, $ward_number, $unit_name, $building_name, $address){
		 //Ανάκτηση e-mail χρήστη.
		 $sql_patient = mysql_query("SELECT Email FROM patients WHERE Id='$patient_id' LIMIT 1") or die("cannot connect to patients");
			while($row_patient = mysql_fetch_assoc($sql_patient)) {
				$email = $row_patient['Email'];
			}
			
		$sql2 = mysql_query("SELECT Name, Surname  FROM medical_staff WHERE Id = '$staff_id' LIMIT 1") or die("cannot connect to medical_staff");
			while($row = mysql_fetch_assoc($sql2)) {
				$doctor_name = $row['Name'];
				$doctor_surname = $row['Surname'];
			}
			
		$date = date('d-m-Y', strtotime($startDate));
		$time = date('H:i', strtotime($startDate));
		
		$this_ward_num = $ward_number;
		// $building_name απο όρισμα
		$building_address = $address;
		
		require_once('C:\xampp\php\PEAR\Mail.php');
		require_once('C:\xampp\php\PEAR\Mail\mime.php');

		$subjectText = 'New examination appointment on '.$date.' '.$time;

		$html = newExamToPatientContent($code, $date, $time, $doctor_name, $doctor_surname, $ward_number, $unit_name, $building_name, $address);	
		
		//include 'mail_contents/exams_patient_mail.php';
		include 'mail_settings.php';
	}

	function newExamToDoc($event, $code, $startDate, $staff_id, $patient_id, $ward_number, $unit_name, $building_name, $address){
		
		$sql_patient = mysql_query("SELECT Name, Surname FROM patients WHERE Id='$patient_id' LIMIT 1") or die("cannot connect to patients");
			while($row = mysql_fetch_assoc($sql_patient)) {
				$patient_name = $row['Name'];
				$patient_surname = $row['Surname'];
			}
		//Ανάκτηση e-mail Ιατρού.	
		$sql_doctor = mysql_query("SELECT Email FROM medical_staff WHERE Id='$staff_id' LIMIT 1") or die("cannot connect to medical_staff");
			while($row = mysql_fetch_assoc($sql_doctor)) {
				$email = $row['Email'];
			}
			
		$date = date('d-m-Y', strtotime($startDate));
		$time = date('H:i', strtotime($startDate));

		
		require_once('C:\xampp\php\PEAR\Mail.php');
		require_once('C:\xampp\php\PEAR\Mail\mime.php');
		
		$subjectText = 'New exams appointment on '.$date.' '.$time;

		$html = newExamToDocContent($code, $date, $time, $patient_name, $patient_surname, $patient_id, $ward_number, $unit_name, $building_name, $address);	
		
		//include 'mail_contents/exams_doctor_mail.php';
		include 'mail_settings.php';
		
	 }
	 
	function newstartVacationEmail($event, $startDate, $endDate, $staff_id){

		$sql_doctor = mysql_query("SELECT Email FROM medical_staff WHERE Id='$staff_id' LIMIT 1") or die("cannot connect to medical_staff");
		while($row = mysql_fetch_assoc($sql_doctor)) {
			$email = $row['Email'];
		}	
		$startDay = date('d-m-Y', strtotime($startDate));
		$startTime = date('H:i', strtotime($startDate));
		
		$endDay = date('d-m-Y', strtotime($endDate));
		$endTime = date('H:i', strtotime($endDate));
		
		if($event == 1){
			$title = "Leave";
		}
		else if($event == 2){
			$title = "Sick leave";
		} 		

		require_once('C:\xampp\php\PEAR\Mail.php');
		require_once('C:\xampp\php\PEAR\Mail\mime.php');
		
		$subjectText = 'Schedule update: Leave.';

		$html = vacationEmailContent($title, $startDay, $startTime, $endDay, $endTime);	
		
		//include 'mail_contents/exams_doctor_mail.php';
		include 'mail_settings.php';		
  }
  
	function new_dayOff_curacy_workShift_email($event, $startDate, $endDate, $staff_id){

		$sql_doctor = mysql_query("SELECT Email FROM medical_staff WHERE Id='$staff_id' LIMIT 1") or die("cannot connect to medical_staff");
		while($row = mysql_fetch_assoc($sql_doctor)) {
			$email = $row['Email'];
		}	
		
		$start_date = date('d-m-Y', strtotime($startDate));
		$start_time = date('H:i', strtotime($startDate));
		
		$end_time = date('H:i', strtotime($endDate));		
	
		if($event == 3){
			$subjectText = 'Schedule update: Day off on '.$start_date.'.';	
			$title = "Day off";
			$word = "day off";			
		}	
		else if($event == 5){
			$subjectText = 'Schedule update: Call duty on '.$start_date.'.';	
			$title = "Call duty";
			$word = "call duty";	
		}	
		else if($event == 6){
			$subjectText = 'Schedule update: Work shift on '.$start_date.'.';	
			$title = "Work shift";
			$word = "work shift";				
		}	
		
		require_once('C:\xampp\php\PEAR\Mail.php');
		require_once('C:\xampp\php\PEAR\Mail\mime.php');
		

		$html = new_dayOff_curacy_workShift_content($title, $word, $start_date, $start_time, $end_time);	
		
		//include 'mail_contents/exams_doctor_mail.php';
		include 'mail_settings.php';		
  }  
  
	function deletedEventEmail($staff_id, $start, $end, $thisEvent, $patient, $parent ){
		//Ανάκτηση e-mail Ιατρού.	
		$sql_doctor = mysql_query("SELECT Email FROM medical_staff WHERE Id='$staff_id' LIMIT 1") or die("cannot connect to medical_staff");
		while($row = mysql_fetch_assoc($sql_doctor)) {
			$email = $row['Email'];
		}
		
		if ($parent != null){
		
			$sql_extraId = mysql_query("SELECT Id FROM days_off WHERE Parent_id='$parent' LIMIT 1" ) or die("delete error 7");
			while($row = mysql_fetch_assoc($sql_extraId)) {
				$Id = $row['Id'];
			}
			$sql_extraDay = mysql_query("SELECT Start, End, Reason FROM unavailable_staff WHERE Id='$Id' LIMIT 1" ) or die("delete error 7");
			while($row = mysql_fetch_assoc($sql_extraDay)) {
				$start = $row['Start'];
				$end = $row['End'];
				$thisEvent = $row['Reason'];
			}
		}
		

				
		$startDate = date('d-m-Y', strtotime($start));
		$startTime = date('H:i', strtotime($start));
		
		$endDate = date('d-m-Y', strtotime($end));
		$endTime = date('H:i', strtotime($end));		
		if($thisEvent == 1){		
			$subjectText = "Schedule update: Leave canceled";
			$content = "We inform you that the <b>leave</b> that was set from <b>".$start."</b> up to <b>".$end."</b> <b>canceled</b>!";
		}
		else if($thisEvent == 2){
			$subjectText = "Schedule update: Sick leave canceled";
			$content = "We inform you that the <b>sick leave</b> that was set from <b>".$start."</b> up to <b>".$end."</b> <b>canceled</b>!";
		}
		else if($thisEvent == 3){
			$subjectText = "Schedule update: The day off on ".$startDate." canceled";
			$content = "We inform you that the <b>day off</b> that was set for <b>".$startDate."</b> <b>canceled</b>!";
		}
		else if($thisEvent == 4){
	
			$subjectText = "Schedule update: The appointment on ".$startDate."is canceled";
			$content = "We inform you that the <b>appointment</b> that was set for <b>".$startDate."</b> <b>canceled</b>!";
		}
		else if($thisEvent == 5){
			$subjectText = "Schedule update: The call duty on ".$startDate." canceled";
			$content = "We inform you that the <b>call duty</b> that was set from <b>".$start."</b>up to <b>".$end."</b> <b>canceled</b>!";
		}
		else if($thisEvent == 6){
			$subjectText = "Schedule update: The work shift on ".$startDate." canceled";
			$content = "We inform you that the <b>work shift</b> that was set from <b>".$start."</b> up to <b>".$end."</b> <b>canceled</b>!";
		}			
		require_once('C:\xampp\php\PEAR\Mail.php');
		require_once('C:\xampp\php\PEAR\Mail\mime.php');
		
		$html ="<html>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					<style type='text/css'>	
						 h1{font-size:24px;
							font-weight:bold; 
						 }
						.cont { 
								margin-top:10px;
								color: #404040;
								width:500px;
								padding-bottom:10px;
								text-align:center;
								background-color: #e8edff;
								border:20px solid #e8edff;
								-webkit-border-radius: 20px;
								-moz-border-radius: 20px;	
								border-radius: 20px;								
								}
					</style>						
				</head>
				<body>	
					<div class = 'cont'>
						<h1> Schedule update </h1>
						<p>".$content."</p>
					</div>
				</body>		
			</html>";
		include 'mail_settings.php';	
	}
	
	function deletedEmailPatient($start, $eventId){
		$sql = mysql_query("SELECT Patient_id FROM examinations WHERE Exam_id='$eventId' LIMIT 1") or die("cannot connect to examinations");
		while($row_patient = mysql_fetch_assoc($sql)) {
			$patient_id = $row_patient['Patient_id'];
		}			
		
		$sql_patient = mysql_query("SELECT Email FROM patients WHERE Id='$patient_id' LIMIT 1") or die("cannot connect to patients");
		while($row_patient = mysql_fetch_assoc($sql_patient)) {
			$email = $row_patient['Email'];
		}

		$subjectText = "The appointment on ".$start." canceled";
		$content = "We inform you that the <b>examinations appointment</b> that was set in <b>".$start."</b> <b>canceled</b>!";

		require_once('C:\xampp\php\PEAR\Mail.php');
		require_once('C:\xampp\php\PEAR\Mail\mime.php');
		
		$html ="<html>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					<style type='text/css'>	
						 h1{font-size:24px;
							font-weight:bold; 
						 }
						.cont { 
								margin-top:10px;
								color: #404040;
								width:500px;
								padding-bottom:10px;
								text-align:center;
								background-color: #e8edff;
								border:20px solid #e8edff;
								-webkit-border-radius: 20px;
								-moz-border-radius: 20px;	
								border-radius: 20px;								
								}
					</style>						
				</head>
				<body>	
					<div class = 'cont'>
						<h1> Appointment canceled </h1>
						<p>".$content."</p>
					</div>
				</body>		
			</html>";	

		include 'mail_settings.php';				
	}	
	
	function new_dayOff_curacy_workShift_content($title, $word, $startDate, $startTime, $endTime ){
	
		$html = "<html>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					<style type='text/css'>	
						.title{font-size:20px;}
						.examDetails,.examDetails2 { 
										color: #404040;
										background-color: #dfe2e2;
										border-spacing: 0px;
										width:500px;
									}
						.examDetails { 
							border:15px solid #b9c9fe;
							-webkit-border-top-left-radius: 15px;
							-webkit-border-top-right-radius: 15px;
							-moz-border-radius-topleft: 15px;
							-moz-border-radius-topright: 15px;
							border-top-left-radius: 15px;
							border-top-right-radius: 15px;
						}			
						.examDetails2{ 
							border:20px solid #e8edff;
							-webkit-border-bottom-right-radius: 20px;
							-webkit-border-bottom-left-radius: 15px;
							-moz-border-radius-bottomright: 15px;
							-moz-border-radius-bottomleft: 15px;
							border-bottom-right-radius: 15px;
							border-bottom-left-radius: 15px;
						}
						.top{text-align:center;
							 background-color: #b9c9fe;
							}
						.bottom{background-color: #e8edff;
								height:30px;
								text-align:left;
								}							
						.examDetails2 tr td {border-bottom: 1px solid white;}	
						.firstTd{ padding-left:130px; }
						.firstTr{ padding-left:210px;
								  background-color: #cbd7fe;
								border-radius: 15px;						 
								 }
						.sep{width: 70px;
							text-align: center;
							border-bottom: 1px solid white;
							}
					</style>
							
				</head>
				<body>	
					<div>
						<table class = 'examDetails'>
							<tr class = 'top'>
								<th colspan='3' class='title' height='30px'>  ".$title." </th>
							</tr>
							<tr class = 'top'>
								<th colspan='3'> New ".$word." introduced in your schedule..</th>
							</tr>
						</table> 
						<table class = 'examDetails2'>	
							<tr class = 'bottom'> 
								 <td class='firstTd' width='55px'> Date </td> 
								 <td class='sep'> : </td> <td>".$startDate."</td>  						
							</tr>					
							<tr class = 'bottom'> 
								 <th class='firstTr'colspan='3'> From </th> 
							</tr>						
							<tr class = 'bottom'> 					
								 <td class='firstTd'> Time</td> 
								 <td class='sep'> : </td> <td>".$startTime."</td>
							</tr>
							<tr class = 'bottom'> 
								 <th class='firstTr' colspan='3'> To </th> 
							</tr>							
							<tr class = 'bottom'> 					
								 <td class='firstTd'> Time </td> 
								 <td class='sep'> : </td> <td>".$endTime."</td>
							</tr>
						</table> 
					</div>
				</body>		
			</html>";
		return $html;
	}

	function vacationEmailContent($title, $startDay, $startTime, $endDay, $endTime){

		$html="<html>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					<style type='text/css'>	
						.title{font-size:20px;}
						.examDetails,.examDetails2 { 
										color: #404040;
										background-color: #dfe2e2;
										border-spacing: 0px;
										width:500px;
									}
						.examDetails { 
							border:15px solid #b9c9fe;
							-webkit-border-top-left-radius: 15px;
							-webkit-border-top-right-radius: 15px;
							-moz-border-radius-topleft: 15px;
							-moz-border-radius-topright: 15px;
							border-top-left-radius: 15px;
							border-top-right-radius: 15px;
						}			
						.examDetails2{ 
							border:20px solid #e8edff;
							-webkit-border-bottom-right-radius: 20px;
							-webkit-border-bottom-left-radius: 15px;
							-moz-border-radius-bottomright: 15px;
							-moz-border-radius-bottomleft: 15px;
							border-bottom-right-radius: 15px;
							border-bottom-left-radius: 15px;
						}
						.top{text-align:center;
							 background-color: #b9c9fe;
							}
						.bottom{background-color: #e8edff;
								height:30px;
								text-align:left;
								}							
						.examDetails2 tr td {border-bottom: 1px solid white;}	
						.firstTd{ padding-left:130px; }
						.firstTr{ padding-left:210px;
								  background-color: #cbd7fe;
								border-radius: 15px;						 
								 }
						.sep{width: 70px;
							text-align: center;
							border-bottom: 1px solid white;
							}
					</style>
							
				</head>
				<body>	
					<div>
						<table class = 'examDetails'>
							<tr class = 'top'>
								<th colspan='3' class='title' height='30px'> ".$title." </th>
							</tr>
							<tr class = 'top'>
								<th colspan='3'> New leave introduced in your schedule.th>
							</tr>
						</table> 
						<table class = 'examDetails2'>						
							<tr class = 'bottom'> 
								 <th class='firstTr'colspan='3'> From </th> 
							</tr>						
							<tr class = 'bottom'> 
								 <td class='firstTd' width='55px'> Date </td> 
								 <td class='sep'> : </td> <td>".$startDay."</td>  	
							</tr>
							<tr class = 'bottom'> 					
								 <td class='firstTd'> Time</td> 
								 <td class='sep'> : </td> <td>".$startTime."</td>
							</tr>
							<tr class = 'bottom'> 
								 <th class='firstTr' colspan='3'> To </th> 
							</tr>							
							<tr class = 'bottom'> 
								 <td class='firstTd' width='55px'> Date </td> 
								 <td class='sep'> : </td> <td>".$endDay."</td>  	
							</tr>
							<tr class = 'bottom'> 					
								 <td class='firstTd'> Time </td> 
								 <td class='sep'> : </td> <td>".$endTime."</td>
							</tr>
						</table> 
					</div>
				</body>		
			</html>";	
		return $html;
	}	
	
	function newExamToDocContent($code, $date, $time, $patient_name, $patient_surname, $patient_id, $ward_number, $unit_name, $building_name, $address){
		$html= "<html>
					<head>
						<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
						<style type='text/css'>	
							.examDetails,.examDetails2 { 
											color: #404040;
											background-color: #dfe2e2;
											border-spacing: 0px;
											width:500px;
										}
							.examDetails { 
								border:15px solid #b9c9fe;
								-webkit-border-top-left-radius: 15px;
								-webkit-border-top-right-radius: 15px;
								-moz-border-radius-topleft: 15px;
								-moz-border-radius-topright: 15px;
								border-top-left-radius: 15px;
								border-top-right-radius: 15px;
							}			
							.examDetails2{ 
								border:20px solid #e8edff;
								-webkit-border-bottom-right-radius: 20px;
								-webkit-border-bottom-left-radius: 15px;
								-moz-border-radius-bottomright: 15px;
								-moz-border-radius-bottomleft: 15px;
								border-bottom-right-radius: 15px;
								border-bottom-left-radius: 15px;
							}
							.top{text-align:center;
								 background-color: #b9c9fe;
								}
							.bottom{background-color: #e8edff;
									height:30px;
									text-align:left;
									}	
							.examDetails2 tr td {border-bottom: 1px solid white;}							
							.sep{width: 35px;
								text-align: center;
								border-bottom: 1px solid white;
								}
						</style>
								
					</head>
					<body>	
						<div>
							<table class = 'examDetails'>
								<tr class = 'top'>
									<th colspan='2'> New examination appointment </th>
								</tr>
								<tr class = 'top'>
									<th colspan='2'> The appointment submitted with code ".$code ."</th>
								</tr>
							</table> 
							<table class = 'examDetails2'>					
								<tr class = 'bottom'>
									<th colspan='3' > Appointment info : </th>
								</tr>			
								<tr class = 'bottom'> 
									 <td width='55px'> Date</td> 
									 <td class='sep'> : </td> <td>".$date."</td>  	
								</tr>
								<tr class = 'bottom'> 					
									 <td> Time</td> 
									 <td class='sep'> : </td> <td>".$time."</td>
								</tr>
								<tr class = 'bottom'> 
									 <td> Building</td> 
									 <td class='sep'> : </td> <td>".$building_name." (".$address.")</td> 
								</tr>
								<tr class = 'bottom'> 					
									 <td> Unit</td> 
									 <td class='sep'> : </td> <td>".$unit_name."</td> 
								</tr>
								<tr class = 'bottom'> 					
									 <td> Ward</td> 
									<td class='sep'> : </td> <td>".$ward_number."</td> 
								</tr>
								<tr class = 'bottom'> 					
									<td> Patient </td> 
									<td class='sep'> : </td> <td>".$patient_name." ".$patient_surname." (".$patient_id.")</td>
								</tr>
							</table> 
						</div>
					</body>		
				</html>";
		return $html;
	}	
	
	function newExamToPatientContent($code, $date, $time, $doctor_name, $doctor_surname, $ward_number, $unit_name, $building_name, $address){
		$html= "<html>
					<head>
						<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
						<style type='text/css'>	
							.examDetails,.examDetails2 { 
											color: #404040;
											background-color: #dfe2e2;
											border-spacing: 0px;
											width:500px;
										}
							.examDetails { 
								border:15px solid #b9c9fe;
								-webkit-border-top-left-radius: 15px;
								-webkit-border-top-right-radius: 15px;
								-moz-border-radius-topleft: 15px;
								-moz-border-radius-topright: 15px;
								border-top-left-radius: 15px;
								border-top-right-radius: 15px;
							}			
							.examDetails2{ 
								border:20px solid #e8edff;
								-webkit-border-bottom-right-radius: 20px;
								-webkit-border-bottom-left-radius: 15px;
								-moz-border-radius-bottomright: 15px;
								-moz-border-radius-bottomleft: 15px;
								border-bottom-right-radius: 15px;
								border-bottom-left-radius: 15px;
							}
							.top{text-align:center;
								 background-color: #b9c9fe;
								}
							.bottom{background-color: #e8edff;
									height:30px;
									text-align:left;
									}	
							.examDetails2 tr td {border-bottom: 1px solid white;}							
							.sep{width: 35px;
								text-align: center;
								border-bottom: 1px solid white;
								}
						</style>
								
					</head>
					<body>	
						<div>
							<table class = 'examDetails'>
								<tr class = 'top'>
									<th colspan='2'> New examination appointemnt </th>
								</tr>
								<tr class = 'top'>
									<th colspan='2'> The appointment submitted with code ".$code ."</th>
								</tr>
							</table> 
							<table class = 'examDetails2'>					
								<tr class = 'bottom'>
									<th colspan='3' > Appointment info : </th>
								</tr>			
								<tr class = 'bottom'> 
									 <td width='55px'> Date</td> 
									 <td class='sep'> : </td> <td>".$date."</td>  	
								</tr>
								<tr class = 'bottom'> 					
									 <td> Time</td> 
									 <td class='sep'> : </td> <td>".$time."</td>
								</tr>
								<tr class = 'bottom'> 
									 <td> Building</td> 
									 <td class='sep'> : </td> <td>".$building_name." (".$address.")</td> 
								</tr>
								<tr class = 'bottom'> 					
									 <td> Unit</td> 
									 <td class='sep'> : </td> <td>".$unit_name."</td> 
								</tr>
								<tr class = 'bottom'> 					
									 <td> Ward</td> 
									<td class='sep'> : </td> <td>".$ward_number."</td> 
								</tr>
								<tr class = 'bottom'> 					
									<td> Doctor</td> 
									<td class='sep'> : </td> <td>".$doctor_name." ".$doctor_surname."</td>
								</tr>
							</table> 
						</div>
					</body>		
				</html>";	
		return $html;
	}	

?>