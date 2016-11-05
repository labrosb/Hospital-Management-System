<?php
	if(!isset($_SESSION)){ session_start();} 

	// In case that someone tries to retrieve information 
	// from this particular file through the URL path
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_patient();		// Checking session to prevent unauthorized access
	
	if (!check_and_update_session()){									// If session hasn't expired update session 									
		header("Location: http://". $_SERVER['HTTP_HOST']."/hospital"); // else redirects to the homepage
		exit;
	}				
?>

	<script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"> </script>	
	<script type="text/javascript" src="client_processes/jquery/jquery.ui.timepicker.js"></script>
	<script type="text/javascript" src="client_processes/patient_functions/exams_history.js"></script>

	<div id='height_specify'></div>

	<div id="intro">
			<h1 data-inter="examsHistoryLabel">Exams history</h1>
		<div class="content_page">
				<form id="myform" action="#" method="post">			
					<!-- #first_step -->
					</br></br>
						<div id='filter'>
							<div class="myform">								
								<input type="text" name="exams" id="exams" value="Exams" />		
								<div id='exams_checkboxes'></div>
								<input type="text" name="from" id="from" value="From" />
								<input type="text" name="to" id="to" value="To" />
																																
								<input class="submit_filter" type="button" name="submit_filter" id="submit_filter" value="OK" />
									<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
							</div> 
							<div id='info_area'>
								<div id='filter_loading'> 
									<img id="loading_img" src="styles/images/loading_icon.gif" height="30" width="30"/>
								</div>
								<div id='filter_error_msg'> </div>
							</div>
					    </div>
						<!-- clearfix --><div class="clear"></div><!-- /clearfix -->		
				</form>		
				
			    <div id="center_table">               
					<div id='results'></div>
					<div id='more_results' class='unfiltered'>
						<img id='loading_img' src='styles/images/loading2.gif' height='30' width='30' />
					</div>           
                </div>
			
		</div><!--content end-->
											
	</div><!--introduction end-->
	<script>
		changeLang(defaultLang);
	</script>	