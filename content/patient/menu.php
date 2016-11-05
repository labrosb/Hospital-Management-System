<?php
	if(!isset($_SESSION)){ session_start();} 
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_patient();					// Checking session to prevent unauthorized access

	if (!check_session_timer()){exit;}	 // Checking session to see if is expired and update		
?>
		<script type="text/javascript" src="client_processes/patient_functions/patients_notifications.js"></script>
		<ul id="nav_patient">
			<li class="name"> <a href="#"><span data-inter="patientLabel">Welcome</span>: <?php echo (' '.$_SESSION['name'].' '. $_SESSION['surname']) ?></a>
				<ul class="name_sub">	
					<li class="edit_profile"><a data-inter="editProfile" href="edit_profile">Edit profile</a></li>
					<li class="logout"> <button data-inter="logout" class ="logout_button" type="button">Logout</button></li>
				</ul>
			</li>
			<li class="news"><a data-inter="news" href="news">News</a></li>
			<li class="exams"><a data-inter="examinations" href="#">Examinations</a>
			<div id="notif_all_p"><p>0</p></div>
				<ul class="exams_sub">
					<li class="new_exam"> <a data-inter="newExam" href="new_exam">New exam</a></li>	
					<li class="exams_results">
						<a data-inter="newResult" href="exams_results">Results</a> <div id="notif_results_p"><p>0</p></div>
					</li>										
				</ul>
			</li>
			<li class="myHistory"><a data-inter="history" href="#">History</a>
				<ul class="myHistory_sub">
					<li class="exams_history"><a data-inter="examsHistory" href="exams_history">Examinations</a></li>
				</ul>
			</li>
			<li class="contact"><a data-inter="contactUs" href="contact">Contact Us</a></li> 
		</ul>