<?php session_start(); ?>
<!DOCTYPE html> 
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Home</title>

		<script type="text/javascript" src="client_processes/jquery/jquery-1.8.2.min.js"> </script>
		<script type="text/javascript" src="client_processes/nivo-slider/jquery.nivo.slider.js"> </script>		
		<script type="text/javascript" src="client_processes/general.js"> </script>	
		<script type="text/javascript" src="client_processes/localization/localization.js"> </script>	
		<script>
			var defaultLang= 'en';
		</script>

		<link rel="stylesheet" href="styles/nivo-slider/themes/default/default.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="styles/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
		<link href="styles/style.css" type="text/css" rel="stylesheet" />
		<link href="styles/menu.css" type="text/css" rel="stylesheet" media="all" />
		<link href="styles/forms.css" type="text/css" rel="stylesheet" media="all" />
		<link href="styles/jquery-ui.css" type="text/css" rel="stylesheet" media="all" />
		<link href="styles/jquery.ui.timepicker.css" type="text/css" rel="stylesheet" media="all" />
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="styles/IE7styles.css" /><![endif]-->  
		<!--[if IE 8]><link rel="stylesheet" type="text/css" href="styles/IE8styles.css" /><![endif]-->  
	</head>

	<body>
		<?php
			if (isset($_SESSION['Rights']) && isset($_SESSION['id']) ){
				if ($_SESSION['Rights'] == "asth"){ ?>
						<div id="container">
								
							<div id="slideshow">
								<div id="banner">
									<div id="banner_container_patient" style="display:block">
										 <?php include 'content/patient/banner.php'; ?>
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
									<?php include 'content/patient/menu.php'; ?>
								</div>
									
								<div id="header"> </div>
								<div id="header_manager"> </div>
								<div id="header_staff"> </div>
										
										
							</div><!--slideshow ends-->	
							
												
							<div class="content">
								<?php include 'content/patient/content.php'; ?>

							</div> <!--content end-->
						<div id="footer"> 
							<div id="infoot">
								<h1 data-inter="footer"> </h1>
							</div>
						</div>													
						</div><!--container ends-->
				<?php
				} else if ($_SESSION['Rights'] == "staff"){
				?>
						<div id="container">
								
							<div id="slideshow">
								<div id="banner">
									<div id="banner_container_staff" style="display:block">
										<?php include 'content/staff/banner.php'; ?>
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
									<?php include 'content/staff/menu.php'; ?>
								</div>
									
								<div id="header"> </div>
								<div id="header_patient"> </div>
								<div id="header_manager"> </div>
										
										
							</div><!--slideshow ends-->	
																	
							<div class="content">
								<?php include 'content/staff/content.php'; ?>
							</div> <!--content end-->
							<div id="footer"> 
								<div id="infoot">
									<h1 data-inter="footer"> </h1>
								</div>
							</div>	
											
						</div><!--container ends-->
				<?php
				} else if ($_SESSION['Rights'] == "manager"){
				?>
						<div id="container">
								
							<div id="slideshow">
								<div id="banner">
									<div id="banner_container_manager" style="display:block">
										<?php include 'content/manager/banner.php'; ?>
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
									<?php include 'content/manager/menu.php'; ?>
								</div>
									
								<div id="header"> </div>
								<div id="header_patient"> </div>
								<div id="header_staff"> </div>
										
										
							</div><!--slideshow ends-->	
																	
							<div class="content">
								<?php include 'content/manager/content.php'; ?>
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
				?>
					<div id="container">
							
						<div id="slideshow">
							<div id="banner">
								<div id="banner_container" style="display:block">
									<?php include 'content/banner.php'; ?>
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
								<?php include'content/menu.php'; ?>
							</div>
								
							<div id="header_patient"> </div>
							<div id="header_staff"> </div>	
							<div id="header_manager"> </div>							
			
						</div><!--slideshow ends-->	
															
						<div class="content">
							<?php include'content/content.php'; ?>
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