<?php
	if(!isset($_SESSION))  {  session_start(); } 
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_doctor();						// Checking session to prevent unauthorized access

	if (!check_session_timer()){exit;}	 	// Checking session (if exists) to see if is expired	
	
?>
		<script type="text/javascript" src="client_processes/jquery/jquery.bpopup-0.7.0.min.js"></script>	
		<script type="text/javascript" src="client_processes/doctor_functions/staff_notifications.js"></script>
		<script type="text/javascript" src="client_processes/doctor_functions/move_to_calendar.js"></script>
		<script>	
			var speciality;		
			$.ajax({		// Retrieves the doctor's speciality data-inter attribute, responsible for the translations
				type: "POST",
				url: "/hospital/server_processes/general_functions/exams_specialities_units.php",
				data: {speciality_data_inter_by_id: '<?php echo $_SESSION['specialty'] ?>'},	 
				async: false,
				success: function(response){
					speciality = response;
				}					
			});
		</script>	
		<ul id="nav_staff">
			<li class="name"> 
				<a href="#">
					<span id="speciality"> </span><?php echo(": ".$_SESSION['name'].' '. $_SESSION['surname']); ?>
				</a>
				<script> $('#speciality').attr('data-inter', speciality); </script>
				<ul class="name_sub">	
					<li class="edit_profile"><a data-inter="editProfile" href="edit_profile">Edit Profile</a></li>
					<li class="logout"> <button data-inter="logout" class ="logout_button" type="button">Log out</button></li>
				</ul>
			</li>
			<li class="news"><a data-inter="news" href="news"></a></li>
			<li class="exams"><a data-inter="examinations" href="#"></a> 
			<div id="notif_all_p"><p>0</p></div>

				<ul class="exams_sub">
					<li class="result_insertion"><a data-inter="insertResults" href="result_insertion"></a>
					<div id="notif_results_p"><p>0</p></div></li>										

				</ul>
			</li>
			<li class="myProgram"><a <a data-inter="schedule" href="#">Schedule</a>
				<ul class="myProgram_sub">
					<li class="daily_program"><a data-inter="daily" class="daily_program" href="#">Daily</a></li>				
					<li class="general_program"><a data-inter="weekly" class="general_program" href="#">Weekly</a></li>
					<li class="on_duty_shifts"> <a data-inter="duties_Shifts" class="on_duty_shifts"  href="#">Call duties / Work shifts</a></li>
				</ul>
			</li>
			<li class="contact"><a data-inter="contactUs" href="contact">Contact Us</a></li>
		</ul>
		
		<div id='results_input'>
			<div id='input_results_popup'>
				<div data-inter="insertDiagnosisMsg" id='input_msg'>
					Insert your diagnosis:
				</div>
				<form id="res_form" action="#" method="post">			
					<textarea></textarea></br></br>
					<button type="button" id="submit_result" class="submit_result" value="">Submit</button>
					<button type="button" id="cancel_result" class="cancel_result bClose" value="">Cancel</button>

				</form>					
			</div>
		</div>		