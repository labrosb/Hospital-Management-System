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
	
	$sql = mysql_query("SELECT Home_phone, Mobile_phone, Email, Address, City, Postal_code FROM patients WHERE Id=$ID LIMIT 1");

	while($row = mysql_fetch_assoc($sql)) {
		$patientData[] = $row; 
	}	
?>

	<script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"> </script>	
	<script type="text/javascript" src="client_processes/edit_profile.js"></script>
    <div id="intro">
      <h1>Profile edit</h1>
      <div class="content_page">
		  <form id="myform" action="#" method="post">
			  <!-- #first_step -->			
			<div id="first_step">
				<div id="form_left">
					<div id="myform_container">
						<div class="myform">
							<div data-inter="Address" class='titles'> Address</div> 
							<input type="text" name="address" id="address" value="Address" />
							<label for="address" class="err_msg"> </label> </br>
							<div data-inter="cityLabel" class='titles'> City</div>
							<input type="text" name="city" id="city" value="City" />
							<label for="city" class="err_msg"> </label>   </br>     
							<div data-inter="postCode" class='titles'> Post code</div>
							<input type="text" name="postCode" id="postCode" value="Post code" />
							<label for="postCode" class="err_msg"> </label> </br> 		
							<div data-inter="phone" class='titles'> Home phone</div>
							<input type="text" name="phone" id="phone" value="Home phone" />
							<label for="phone" class="err_msg"> </label> </br>
							<div data-inter="cellPhone" class='titles'> Mobile phone</div>
							<input type="text" name="cellPhone" id="cellPhone" value="Mobile phone" />
							<label for="cellPhone" class="err_msg"> </label> 
						</div><!-- clearfix -->
						<input class="submit_change" type="button" name="submit_edit_first" id="submit_edit_first" value="Submit" />
					</div>
				</div>
				<div id="form_right">
					<div id='form_msg1'>
						<table class ='choice_buttons'>
							<tr>
								<td data-inter='contactInfo' id ='communication_upd'> Contact info </td>
								<td id='space'></td>
								<td data-inter='Email' id ='email_upd'> E-mail </td>
								<td id='space'></td>
								<td data-inter='password' id='password_upd'> Password </td>
							</tr>
						</table>
						</br></br></br></br></br></br>
						<h1 data-inter='contactInfoEdit' class='msg_p succ'>Contact information edit</h1>
						<h1 data-inter='ERROR' class='error_p' style='display:none'>ERROR</h1>
						<p data-inter='regFailed2' class='error_p' style='display:none'>An error came up. Please try again!</p>
						<div id='form_loading_p'>
							<img id='loading_img_p' src='styles/images/loading_icon.gif' height="120" width="120" />
						</div>
					</div>
				</div>
			</div>
		
			<div id="second_step">
				<div id="form_left">
					<div id="myform_container">
					</br></br>
						<div class="myform">
							<div data-inter="Email" class='titles'> E-mail</div>
							<input type="text" name="email" id="email" value="E-mail" />
							<label for="email" class="err_msg"> </label> </br>
							<div data-inter="password" class='titles'> Password</div>
							<input type="password" name="password" id="password" value="Password" />			
							<label for="password" class="err_msg"> </label>				
						</div>
						<input class="submit_change2" type="button" name="submit_edit_second" id="submit_edit_second" value="Submit" />											
					</div>    
				</div>
				<div id="form_right">
					<div id='form_msg2' class ='sec'>
						<table class ='choice_buttons dn'>
							<tr>
								<td data-inter='contactInfo' id ='communication_upd'> Contact info </td>
								<td id='space'></td>
								<td data-inter='Email' id ='email_upd'> E-mail </td>
								<td id='space'></td>
								<td data-inter='password' id='password_upd'> Password </td>
							</tr>
						</table>
							</br></br></br></br></br></br>
							<h1 data-inter='EmailEdit' class='msg'>Ε-mail edit</h1>
							<p data-inter='passForMail' class='msg_p succ2'>Your password is required to edit your e-mail.</p>
							<h1 data-inter='error' class='error_p' style='display:none'>Error</h1>
							<p data-inter='regFailed2' class='error_p' style='display:none'>An error came up. Please try again!</p>
							<div id='form_loading_p2'>
								<img id='loading_img_p2' src='styles/images/loading_icon.gif' height="120" width="120" />
							</div>
					</div>
				</div>
			</div>	
			
			<div id="third_step">
				<div id="form_left">
					<div id="myform_container">
					</br>
						<div class="myform">
							<div data-inter="oldPass" class='titles'> Old Password</div>
							<input type="password" name="oldPassword" id="oldPassword" value="Password" />
							<label for="oldPassword" class="err_msg"> </label>	</br>	
							<div data-inter="newPass" class='titles'> New Password</div>							
							<input type="password" name="newPassword" id="newPassword" value="Password" />			
							<label for="newPassword" class="err_msg"> </label>	</br>		
							<div data-inter="newPassconfirm" class='titles'> Confirm new Password</div>	
							<input type="password" name="passwordConf" id="passwordConf" value="Password" />			
							<label for="passwordConf" class="err_msg"> </label>	</br>							
							<!-- clearfix --><div class="clear"></div><!-- /clearfix -->				
						</div>
						<input class="submit_change3" type="button" name="submit_edit_third" id="submit_edit_third" value="Submit" />						
					</div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
				</div>
				<div id="form_right">
					<div id='form_msg3'>
						<table class ='choice_buttons dn'>
							<tr>
								<td data-inter='contactInfo' id ='communication_upd'> Contact info </td>
								<td id='space'></td>
								<td data-inter='Email' id ='email_upd'> E-mail </td>
								<td id='space'></td>
								<td data-inter='password' id='password_upd'> Password </td>
							</tr>
						</table>
						</br></br></br></br></br></br>
						<h1 data-inter='passEdit' class='msg_p succ3'>Password edit</h1>
						<p data-inter='passEditMsg' class='msg_p succ3'>Your previous password is required.</p>
						<h1 data-inter='ERROR' class='error_p' style='display:none'>ERROR</h1>
						<p data-inter='regFailed2' class='error_p' style='display:none'>An error came up. Please try again!</p>	
						<div id='form_loading_p3'>
							<img id='loading_img_p3' src='styles/images/loading_icon.gif' height="120" width="120" />
						</div>						
					</div>
				</div>
			</div>				
								
		</form>
   </div>
 </div>
 	<script type="text/javascript">
		changeLang(defaultLang);	
		$(document).ready(function() {
			$('#address').val('<?php echo $patientData[0]['Address']; ?>');
			$('#city').val('<?php echo $patientData[0]['City']; ?>');
			$('#postCode').val('<?php echo $patientData[0]['Postal_code']; ?>');
			$('#phone').val('<?php echo $patientData[0]['Home_phone']; ?>');
			$('#cellPhone').val('<?php echo $patientData[0]['Mobile_phone']; ?>');
			$('#email').val('<?php echo $patientData[0]['Email']; ?>');
		});
	</script>
