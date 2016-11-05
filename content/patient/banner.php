<?php
	if(!isset($_SESSION)){ session_start();}
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_patient();					// Checking session to prevent unauthorized access
	
	if (!check_session_timer()){exit;}	 // Checking session (if exists) to see if is expired	
?>	

		<div id ="banner_index">	
			<div class="slider-wrapper theme-default">
				<div id="slider" class="nivoSlider patient_slider">
					<img src="styles/images/banner_pic.png" alt="" />
					<img src="styles/images/banner_pic2.png" alt="" />
					<img src="styles/images/banner_pic.png" alt="" />
					<img src="styles/images/banner_pic2.png" alt="" />
				</div>
			</div>	
			<div id ="banner_text">	
				<h1 data-inter="patientBannerTitle"> Patient Area </h1>
				<p data-inter="patientBannerText"> You are logged in as patient.
						Now you can submit your appointments for examinations selecting
						the doctor of your choice, see the results of your tests that 
						have been posted and access your examinations history.
				</p>
			</div>
		</div>		
		<script type="text/javascript">
			$(".patient_slider").nivoSlider();
		</script>