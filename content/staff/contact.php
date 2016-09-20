<?php
	session_start();
	
	include("../../server_processes/config.inc.php");
	
	$con = mysql_connect(dbServer,dbUser,dbPass) or die('Connection-ERROR');

	mysql_select_db(dbDatabase) or die('Connection-ERROR'.$dbDatabase.'.');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 	
	
	$ID = $_SESSION['id'];
	
	$sql = mysql_query("SELECT Email FROM medical_staff WHERE Id=$ID LIMIT 1");

	while($row = mysql_fetch_assoc($sql)) {
		$data['Email'] = $row['Email']; 
	}	
?>	
    <script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"></script>	
	<script type="text/javascript" src="client_processes/send_mail.js"></script>	
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
		$('#name').val('<?php echo $_SESSION['name'].' '.$_SESSION['surname'] ?>');
		$('#email').val('<?php echo $data["Email"] ?>');
		$('#name').attr('disabled', true);
		$('#email').attr('disabled', true);
	
	</script>	
	
	
	