<?php
	if(!isset($_SESSION)){ session_start();} 

	// In case that someone tries to retrieve information 
	// from this particular file through the URL path
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_patient();		// Checking session to prevent unauthorized access
	
	if (!check_and_update_session()){										// If session hasn't expired update session 									
		header("Location: http://". $_SERVER['HTTP_HOST']."/hospital");		// else redirect to home page
		exit;
	}				
?>

	<script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"> </script>	
	<script type="text/javascript" src="client_processes/jquery/jquery.ui.timepicker.js"></script>
	<script type="text/javascript" src="client_processes/patient_functions/exams_categories.js"></script> 
    <script type="text/javascript" src="client_processes/patient_functions/available_doctors.js"></script> 
	<script type="text/javascript" src="client_processes/patient_functions/exams_form.js"></script>
	<script type="text/javascript" src="client_processes/jquery/jquery.bpopup-0.7.0.min.js"></script>	

	<div id ="doctors_dimensions"></div>
	<div id ="exams_dimensions"></div>
	<div id ="popup_area"></div>
	<div id ="thisDoctor" style ="display:none">0</div>

	<div id="intro">

						
		<h1 data-inter="newExamTitle">New examination</h1>
		<div class="content_page">
			<form id="myform" action="#" method="post">			
				<!-- #first_step -->
				<div id="first_step">					
					<div id="form_left">
						<div id="myform_container">
							<div id="first_step">
								<div class="myform">
									<div class="exams_type_choice choice"><p data-inter="select">Select</p></div>
									<input type="text" name="exams_type" id="exams_type" value="Exam type"/><!--	onkeyup="lookup(this.value);" /> -->
									<label for="exams_type" class="err_msg choice"> </label>
									<!--  <div id="suggestions"></div>  -->
									
									<input type="text" name="date" id="date" value="Date" />
									<label for="date" class="err_msg"> </label>

									<button type="button" class="timeButton ui-datepicker-trigger2"> <img src="styles/images/forms/calendar.png" alt="calendar"></button>									
									<input type="text" name="time" id="time" value="Time" />
									<label for="time" class="err_msg"> </label>

									<div class="available_doctors_choice choice"><p data-inter="select">select</p></div>
									<input type="text" name="doctor" id="doctor" value="Doctor" />
									<label for="doctor" class="doctors label"> </label>
																
									<label data-inter="useInsuranceMsg" class="insurance_choice" for="insurance">Use your insurance:</label>	
									<input class="insurance_checkbox" id="insurance_checkbox" type="checkbox" name="insurance" value="true"/>
																
									<input class="submit_exam" type="button" name="submit_exam" id="submit_exam" value="Submit" />
									<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
								</div> 
							</div>							
						</div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
					</div>							
				<div id="form_right">
				</br>
					<div id='form_msg'>
						<h1 data-inter="bookExamMsg">Fill the form on the left to book your appointment for examinations</h1>
						
						<p data-inter="bookExamSubMsg"> If you leave the doctor field blank, the doctor will be chosen automatically by the system.</p>
					</div>
					<div id='error_msg'></div>
					<div id='form_loading'>
						<img id='loading_img' src='styles/images/loading_icon.gif' height="120" width="120" />
					</div>
				</div> <!--right ends-->
			</div>
			<div id="second_step"></div>				
			
		</form>				
 
		</div><!--content end-->
											
	</div><!--introduction end-->
	<script>
		changeLang(defaultLang);
	</script>		