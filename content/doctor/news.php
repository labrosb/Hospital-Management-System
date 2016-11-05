<?php
	if(!isset($_SESSION)){ session_start();} 

	// In case that someone tries to retrieve information 
	// from this particular file through the URL path
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_doctor();					// Checking session to prevent unauthorized access
	
	if (!check_and_update_session()){										// If session hasn't expired update session 									
		header("Location: http://". $_SERVER['HTTP_HOST']."/hospital");		// else redirect to home page
		exit;
	}				
?>

		<div id="intro">
						
			<h1> News </h1>
						
           <div class="content_page">
            
            	<div id="left">
                
					<h1 data-inter="doctorNewsTitle"> News </h1>
					<p data-inter="doctorNewsContent">
						You can now access your daily and weekly appointment, shift,
						on call, holidays and days off schedule and you can post 
						results for examinations.						
					</p>
                </div>
				
                <div id="right">
                
                <img src="styles/images/about_stock.jpg" alt="" />
                             
                            
                </div><!--right ends-->
            
            </div><!--content end-->
					
						
		</div><!--introduction end-->
		<script>
			changeLang(defaultLang);
		</script>										