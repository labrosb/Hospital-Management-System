	<script type="text/javascript" src="client_processes/jquery/jquery.bpopup-0.7.0.min.js"></script>	
	
		
		<div id='height_specify'></div>
		<div id='results_input'>
			<div id='input_results_popup'>
				<div data-inter="insertDiagnosisMsg" id='input_msg'>
					Insert your diagnosis:
				</div>
				<form id="res_form" action="#" method="post">			
					<textarea></textarea></br></br>
					<input class="submit_result" type="button" name="submit_result" id="submit_result" value="Submit" />
					<input class="cancel_result bClose" type="button" name="cancel_result" id="cancel_result" value="Cancel" />		
				</form>					
			</div>
		</div>
		
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
		
	<script type="text/javascript" src="client_processes/exams_for_results.js"></script>
	<script>
		changeLang(defaultLang);
	</script>	