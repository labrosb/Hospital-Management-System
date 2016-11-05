<?php
	if(!isset($_SESSION)){ session_start();}
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_manager();					// Checking session to prevent unauthorized access
	
	if (!check_session_timer()){exit;}	 // Checking session to see if is expired and update
?>
		<div id ="banner_index">	
			<div class="slider-wrapper theme-default">
				<div id="slider" class="nivoSlider manager_slider">
					<img src="styles/images/banner_pic.png" alt="" />
					<img src="styles/images/banner_pic2.png" alt="" />
					<img src="styles/images/banner_pic.png" alt="" />
					<img src="styles/images/banner_pic2.png" alt="" />
				</div>
			</div>			
			<div id ="banner_text">	
				<h1 data-inter="managerBannerTitle"> Administrative Staff Area </h1>
				<p data-inter="managerBannerText"> You are logged in as Administrative Staff .</br>
					You can now access the daily and weekly schedule of medical staff and medical units.
					You can also search, insert or delete medical staff if its required.
				</p>
			</div>
		</div>		
		<script type="text/javascript">
			$(".manager_slider").nivoSlider();
		</script>