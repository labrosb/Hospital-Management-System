<?php
	if(!isset($_SESSION))  { session_start();} 
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_doctor();						// Checking session to prevent unauthorized access

	if (!check_session_timer()){exit;}	 	// Checking session (if exists) to see if is expired		
?>			
		<div id ="banner_index">	
			<div class="slider-wrapper theme-default">
				<div id="slider" class="nivoSlider staff_slider">
					<img src="styles/images/banner_pic.png" alt="" />
					<img src="styles/images/banner_pic2.png" alt="" />
					<img src="styles/images/banner_pic.png" alt="" />
					<img src="styles/images/banner_pic2.png" alt="" />
				</div>
			</div>			
			<div id ="banner_text">	
				<h1 data-inter="doctorBannerTitle"> Medical Staff Area </h1>
				<p data-inter="doctorBannerText">You are logged in as Doctor.</br>
						You can now access your daily and weekly appointment, shift,
						on call, holidays and days off schedule and you can post 
						results for examinations.		
				</p>
			</div>
		</div>		
		<script type="text/javascript">
			$(".staff_slider").nivoSlider();		
		</script>