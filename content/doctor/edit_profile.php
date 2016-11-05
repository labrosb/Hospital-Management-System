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
?>

	<script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"> </script>	
	<script type="text/javascript" src="client_processes/doctor_functions/edit_staff_profile.js"></script>
    <div id="intro">
      <h1 data-inter="editProfileTitle">Profile edit</h1>
      <div class="content_page">
		  <form id="myform" class="noDefault" action="#" method="post">
			  <!-- #first_step -->			
			<div id="first_step">
				<div id="form_left">
					<div id="myform_container">
						<div class="myform">
							<div data-inter="Address" class='titles'>Address</div> 
							<input type="text" name="address" id="address" value="" />
							<label for="address" class="err_msg"> </label> </br>
							<div data-inter="cityLabel" class='titles'>City</div>
							<input type="text" name="city" id="city" value="" />
							<label for="city" class="err_msg"> </label>   </br>     
							<div data-inter="postCode" class='titles'>Post code</div>
							<input type="text" name="postCode" id="postCode" value="" />
							<label for="postCode" class="err_msg"> </label> </br> 		
							<div data-inter="phone" class='titles'>Home phone</div>
							<input type="text" name="phone" id="phone" value="" />
							<label for="phone" class="err_msg"> </label> </br>
							<div data-inter="cellPhone" class='titles'>Mobile phone</div>
							<input type="text" name="cellPhone" id="cellPhone" value="" />
							<label for="cellPhone" class="err_msg"> </label> 
							<div data-inter="Email" class='titles'>E-mail</div>
							<input type="text" name="email" id="email" value="" />
							<label for="email" class="err_msg"> </label> </br>							
						</div><!-- clearfix -->
						<input class="submit_change_doc" type="button" name="submit_edit_first" id="submit_edit_first" value="Submit" />
					</div>
				</div>
				<div id="form_right">
					<div id='form_msg1'>
						<table class ='choice_buttons'>
							<tr>
								<td data-inter="contactInfo" id ='communication_upd'> Contact info </td>
								<td id='space'></td>
								<td data-inter="moreInfo" id ='biog_upd'> More info </td>
								<td id='space'></td>
								<td data-inter="password" id='password_upd'> Password </td>
							</tr>
						</table>
						</br></br></br></br></br></br>
						<h1 data-inter="contactInfoEdit" class='msg_p succ'>Contact information edit</h1>
						<div id="step1_error">
							<h1 data-inter="ERROR" class='error_p' style='display:none'>ERROR</h1>
							<p data-inter="regFailed2" class='error_p' style='display:none'>An error came up. Please try again!</p>
						</div>
						<div id='form_loading_p_doc'>
							<img id='loading_img_p' src='styles/images/loading_icon.gif' height="120" width="120" />
						</div>
					</div>
				</div>
			</div>
		
			<div id="second_step">
				<div id="form_left">
					<div id="myform_container">
						<div class="myform">
							<div data-inter="moreInfo" class='titles'> More info </div>
							<textarea name="biog" id="biog"> </textarea>
							<label for="biog" class="err_msg"> </label> </br>		
						</div>
						<input class="submit_change_doc2" type="button" name="submit_edit_second" id="submit_edit_second" value="Submit" />											
					</div>    
				</div>
				<div id="form_right">
					<div id='form_msg2'>
						<table class ='choice_buttons dn'>
							<tr>
								<td data-inter="contactInfo" id ='communication_upd'> Contact info </td>
								<td id='space'></td>
								<td data-inter="moreInfo" id ='biog_upd'> More info </td>
								<td id='space'></td>
								<td data-inter="password" id='password_upd'> Password </td>
							</tr>
						</table>
						</br></br></br></br></br></br>
						<h1 data-inter="infoEdit" class='msg_p succ2'>Personal info</h1>
						<div id="step2_error_staff">
							<h1 data-inter="ERROR" class='error_p' style='display:none'>ERROR</h1>
							<p data-inter="regFailed2" class='error_p' style='display:none'>An error came up. Please try again!</p>
						</div>
						<div id='form_loading_p2_doc'>
							<img id='loading_img_p2' src='styles/images/loading_icon.gif' height="120" width="120" />
						</div>
					</div>
				</div>
			</div>	
			
			<div id="third_step">
				<div id="form_left">
					<div id="myform_container">
						<div class="myform">
						</br>
							<div data-inter="oldPass" class='titles'> Old Password</div>
							<input type="password" name="oldPassword" id="oldPassword" value="" />
							<label for="oldPassword" class="err_msg"> </label>	</br>	
							<div data-inter="newPass" class='titles'> New Password</div>							
							<input type="password" name="newPassword" id="newPassword" value="" />			
							<label for="newPassword" class="err_msg"> </label>	</br>		
							<div data-inter="newPassconfirm" class='titles'> Confirm new Password</div>	
							<input type="password" name="passwordConf" id="passwordConf" value="" />			
							<label for="passwordConf" class="err_msg"> </label>	</br>							
							<!-- clearfix --><div class="clear"></div><!-- /clearfix -->				
						</div>
						<input class="submit_change_doc3" type="button" name="submit_edit_third" id="submit_edit_third" value="Submit" />						
					</div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
				</div>
				<div id="form_right">
					<div id='form_msg3'>
						<table class ='choice_buttons dn'>
							<tr>
								<td data-inter="contactInfo" id ='communication_upd'> Contact info </td>
								<td id='space'></td>
								<td data-inter="moreInfo" id ='biog_upd'> More info </td>
								<td id='space'></td>
								<td data-inter="Password" id='password_upd'> Password </td>
							</tr>
						</table>
						</br></br></br></br></br></br>
						<h1 data-inter="passEdit" class='msg_p succ3'>Password edit</h1>
						<p data-inter="passEditMsg" class='msg_p succ3'>Your previous password is required.</p>
						<div id="step3_error">
							<h1 data-inter="ERROR" class='error_p' style='display:none'>ERROR</h1>
							<p data-inter="regFailed2" class='error_p' style='display:none'>An error came up. Please try again!</p>	
						</div>
						<div id='form_loading_p3_doc'>
							<img id='loading_img_p3' src='styles/images/loading_icon.gif' height="120" width="120" />
						</div>						
					</div>
				</div>
			</div>				
								
		</form>
   </div>
 </div>
<script>
	changeLang(defaultLang);
</script>	