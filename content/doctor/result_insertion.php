<?php
	if(!isset($_SESSION)){ session_start();} 

	// In case that someone tries to retrieve information 
	// from this particular file through the URL path
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_doctor();		// Checking session to prevent unauthorized access
	
	if (!check_and_update_session()){										// If session hasn't expired update session 									
		header("Location: http://". $_SERVER['HTTP_HOST']."/hospital");		// else redirect to home page
		exit;
	}				
?>
			
		<div id='height_specify'></div>
		
		<div id="intro">
						
			<h1 data-inter="insertDiagnosisTitle">Insert diagnosis</h1>
			
           <div class="content_page">
            	<div id="center_table">               
					</br></br>
					<div id='results'></div>
					<div id='more_results'>
						<img id='loading_img' src='styles/images/loading2.gif' height='30' width='30' />
					</div>           

                </div>				
            
            </div><!--content end-->
										
		</div><!--introduction end-->
		
	<script type="text/javascript" src="client_processes/doctor_functions/exams_for_results.js"></script>
	<script>
		changeLang(defaultLang);
	</script>	