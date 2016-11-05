<?php
	if(!isset($_SESSION)){ session_start();} 

	// In case that someone tries to retrieve information 
	// from this particular file through the URL path

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");	// Connection to database		
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_doctor();		// Checking session to prevent unauthorized access
	
	if (!check_and_update_session()){										// If session hasn't expired update session 									
		header("Location: http://". $_SERVER['HTTP_HOST']."/hospital");		// else redirect to home page
		exit;
	}				
		
	$con = DB_Connect();	// Connecting to database	
	
	$ID = $_SESSION['id'];
	$Name = $_SESSION['name'];
	$Surname = $_SESSION['surname'];
		
	try {	
		$stmt = $con->prepare('SELECT Email FROM medical_staff WHERE Id = :id LIMIT 1');		
		$stmt->execute(array('id' => $ID));	
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{	
			$data['Email'] = $row['Email']; 		// Retrieves user's e-mail
		}
		$con=null;
	}
	catch (PDOException $e) { die("cannot connect to reasons"); }
?>	

    <script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"></script>	
	<script type="text/javascript" src="client_processes/general_functions/send_mail.js"></script>	
		<div id="intro">					
			<h1 data-inter="contactUsTitle"> </h1>					
           <div class="content_page">          
            	<div id="cont_left">
					<div  data-inter="contactUsContent" id="contact_text"> </div>
					<form id="contact_form" action="#" method="post">			
						<div id="contact_form_container">
							<div class="myform2">
								<input type="text" name="name" id="name" value="Name" />
								<label for="name" class="err_msg"> </label>
								<input type="text" name="email" id="email" value="E-mail" />
								<label for="email" class="email"> </label>							
								<textarea data-inter="message" id="message" name="text" > </textarea>
								<label for="text" class="err_msg"> </label>
								</br><!-- clearfix --><div class="clear"></div><!-- /clearfix -->
								<input type="button" class="sendMailBtn" name="send" id="sendMailBtn" value="Send" />								
							</div>    
							<div id="send_msg"> </div>
						</div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
					</form>
                </div>		
                <div id="cont_right">           
					<div id="map">	
						<h2 data-inter="Map"> </h2>    					
						<iframe alt="Map" class="map_wrapper"src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=el&amp;geocode=&amp;q=%CE%95%CE%BB%CE%B5%CF%85%CE%B8%CE%B5%CF%81%CE%AF%CE%BF%CF%85+%CE%92%CE%B5%CE%BD%CE%B9%CE%B6%CE%AD%CE%BB%CE%BF%CF%85+139,+%CE%9A%CE%B9%CE%AC%CF%84%CE%BF,+%CE%9A%CE%BF%CF%81%CE%B9%CE%BD%CE%B8%CE%AF%CE%B1,+%CE%95%CE%BB%CE%BB%CE%AC%CE%B4%CE%B1&amp;aq=0&amp;oq=%CE%95%CE%BB%CE%B5%CF%85%CE%B8%CE%B5%CF%81%CE%AF%CE%BF%CF%85+%CE%92%CE%B5%CE%BD%CE%B9%CE%B6%CE%AD%CE%BB%CE%BF%CF%85+139+%CE%BA%CE%B9%CE%B1&amp;sll=37.0625,-95.677068&amp;sspn=37.735377,86.044922&amp;ie=UTF8&amp;hq=&amp;hnear=%CE%95%CE%BB%CE%B5%CF%85%CE%B8%CE%B5%CF%81%CE%AF%CE%BF%CF%85+%CE%92%CE%B5%CE%BD%CE%B9%CE%B6%CE%AD%CE%BB%CE%BF%CF%85,+%CE%A3%CE%B9%CE%BA%CF%85%CF%89%CE%BD%CE%AF%CE%B5%CF%82,+%CE%9A%CE%BF%CF%81%CE%B9%CE%BD%CE%B8%CE%AF%CE%B1,+%CE%95%CE%BB%CE%BB%CE%AC%CE%B4%CE%B1&amp;t=m&amp;z=14&amp;ll=38.015116,22.745542&amp;output=embed"></iframe>
						</br></br>
						<strong data-inter="Address">  </strong> : ---------<br />						
						<strong data-inter="Phone"> </strong> : --------- <br />
						<strong data-inter="Email"> </strong> : <a href="mailto:mail@mail.com"> mail@mail.com </a>
					</div>                           
                </div><!--right ends-->          
            </div><!--content end-->										
		</div><!--introduction end-->	
		
	<script>	
		changeLang(defaultLang);		
		$('#name').val('<?php echo $Name.' '.$Surname ?>');
		$('#email').val('<?php echo $data["Email"] ?>');
		$('#name').attr('disabled', true);
		$('#email').attr('disabled', true);
	
	</script>	
	
	
	