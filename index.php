<?php 	
	session_start(); 
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	 // Security functions	
		 
	if (isset($_SESSION['timeout']) && !check_session_timer()){	 	// Checking session (if exists) to see if is expired
		header("Refresh:0");
		exit;
	}
	update_session();	 // Updates the session expiration time
?>

<!DOCTYPE html> 
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<title>Home</title>

		<link rel="stylesheet" type="text/css" media="screen" href="styles/nivo-slider/themes/default/default.css">
		<link rel="stylesheet" type="text/css" media="screen" href="styles/nivo-slider/nivo-slider.css">
		<link rel="stylesheet" type="text/css" media="screen" href="styles/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="styles/menu.css">
		<link rel="stylesheet" type="text/css" media="screen" href="styles/forms.css">
		<link rel="stylesheet" type="text/css" media="screen" href="styles/jquery-ui.css">
		<link rel="stylesheet" type="text/css" media="screen" href="styles/jquery.ui.timepicker.css">
		<link rel="stylesheet" type="text/css" media="screen" href="styles/msgBoxLight.css"> 
		
		<script type="text/javascript" src="client_processes/jquery/jquery-1.8.2.min.js"> </script>
		<script type="text/javascript" src="client_processes/jquery/jquery.nivo.slider.js"> </script>		
		<script type="text/javascript" src="client_processes/jquery/jquery.msgBox.js"> </script>
		<script type="text/javascript" src="client_processes/general_functions/general_functions.js"> </script>	
		<script type="text/javascript" src="client_processes/general_functions/pages_handler.js"> </script>	
		<script type="text/javascript" src="client_processes/general_functions/localization.js"> </script>	
		<script>
			var defaultLang= 'en';		//variable for language
		</script>
	</head>

	<body>	
		<!-- During login and navigation the content is handled dynamicaly, mainly in  "general_functions/pages_handler.js".
			 The code below occurs in the cases of fist page load or when page is refreshed. --> 
		<?php
			if (isset($_SESSION['Rights']) && isset($_SESSION['id']) ){
				if ($_SESSION['Rights'] == "asth"){ ?>			<!--if user has logged in as patient-->
						<div id="container">								
							<div id="slideshow">
								<div id="banner">
									<div id="banner_container_patient" style="display:block">
										 <?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/patient/banner.php"; ?>
									</div>
									<div id="banner_container"></div>
									<div id="banner_container_staff"></div>
									<div id="banner_container_manager"></div>
									<div id="languages">
										<div id="greek" onclick="changeLang('gr')"> </div>
										<div id="english" onclick="changeLang('en')"> </div>
									</div>
								</div>
								<div id="header_patient" style="display:block">
									<?php  include $_SERVER['DOCUMENT_ROOT']."/hospital/content/patient/menu.php"; ?>
								</div>
									
								<div id="header"> </div>
								<div id="header_manager"> </div>
								<div id="header_staff"> </div>										
									
							</div><!--slideshow ends-->								
												
							<div class="content">
								<?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/patient/content.php"; ?>

							</div> <!--content end-->
						<div id="footer"> 
							<div id="infoot">
								<h1 data-inter="footer"> </h1>
							</div>
						</div>													
						</div><!--container ends-->
				<?php
				} else if ($_SESSION['Rights'] == "doctor"){   
				?>	 <!-- if user has logged in as doctor -->
						<div id="container">
								
							<div id="slideshow">
								<div id="banner">
									<div id="banner_container_staff" style="display:block">
										<?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/doctor/banner.php"; ?>
									</div>
									<div id="banner_container"></div>									
									<div id="banner_container_patient"></div>
									<div id="banner_container_manager"></div>
									<div id="languages">
										<div id="greek" onclick="changeLang('gr')"> </div>
										<div id="english" onclick="changeLang('en')"> </div>
									</div>

								</div>
								<div id="header_staff" style="display:block">
									<?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/doctor/menu.php"; ?>
								</div>
									
								<div id="header"> </div>
								<div id="header_patient"> </div>
								<div id="header_manager"> </div>
																				
							</div><!--slideshow ends-->	
																	
							<div class="content">
								<?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/doctor/content.php"; ?>
							</div> <!--content end-->
							<div id="footer"> 
								<div id="infoot">
									<h1 data-inter="footer"> </h1>
								</div>
							</div>	
											
						</div><!--container ends-->
				<?php
				} else if ($_SESSION['Rights'] == "manager"){
				?> <!-- if user has logged in as manager -->
						<div id="container">
								
							<div id="slideshow">
								<div id="banner">
									<div id="banner_container_manager" style="display:block">
										<?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/manager/banner.php"; ?>
									</div>
									<div id="banner_container"></div>									
									<div id="banner_container_patient"></div>
									<div id="banner_container_staff"></div>
									<div id="languages">
										<div id="greek" onclick="changeLang('gr')"> </div>
										<div id="english" onclick="changeLang('en')"> </div>
									</div>

								</div>
								<div id="header_manager" style="display:block">
									<?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/manager/menu.php"; ?>
								</div>
									
								<div id="header"> </div>
								<div id="header_patient"> </div>
								<div id="header_staff"> </div>
										
										
							</div><!--slideshow ends-->	
																	
							<div class="content">
								<?php include $_SERVER['DOCUMENT_ROOT']."/hospital/content/manager/content.php"; ?>
							</div> <!--content end-->
							<div id="footer"> 
								<div id="infoot">
									<h1 data-inter="footer"> </h1>
								</div>
							</div>	
											
						</div><!--container ends-->
				<?php			
					}
				}else{
				?>  <!--if user has not logged in -->
					<div id="container">
							
						<div id="slideshow">
							<div id="banner">
								<div id="banner_container" style="display:block">
									<?php include 'content/guest/banner.php'; ?>
								</div>
								<div id="banner_container_patient"></div>
								<div id="banner_container_staff"></div>
								<div id="banner_container_manager"></div>
								<div id="languages">
									<div id="greek" onclick="changeLang('gr')"> </div>
									<div id="english" onclick="changeLang('en')"> </div>
								</div>
							</div>

							<div id="header" style="display:block">
								<?php include'content/guest/menu.php'; ?>
							</div>
								
							<div id="header_patient"> </div>
							<div id="header_staff"> </div>	
							<div id="header_manager"> </div>							
			
						</div><!--slideshow ends-->	
															
						<div class="content">
							<?php include'content/guest/content.php'; ?>
						</div> <!--content end-->	
						
						<div id="footer"> 
							<div id="infoot">
								<h1 data-inter="footer"> </h1>
							</div>
						</div>	
						
					</div><!--container ends-->			
		<?php					
			}
		?>

	</body>
	
	<script>
		changeLang(defaultLang);
	</script>	
	
</html>