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
	
	$sql = mysql_query("SELECT Address, City, Postal_code, Home_phone, Mobile_phone, Email, Biography FROM medical_staff WHERE Id=$ID LIMIT 1");

	while($row = mysql_fetch_assoc($sql)) {
		$staffData[] = $row; 
	}	
?>

	<script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"> </script>	
	<script type="text/javascript" src="client_processes/edit_staff_profile.js"></script>
    <div id="intro">
      <h1 data-inter="editProfileTitle">Profile edit</h1>
      <div class="content_page">
		  <form id="myform" action="#" method="post">
			  <!-- #first_step -->			
			<div id="first_step">
				<div id="form_left">
					<div id="myform_container">
						<div class="myform">
							<div data-inter="Address" class='titles'> Address</div> 
							<input type="text" name="address" id="address" value="Διεύθυνση" />
							<label for="address" class="err_msg"> </label> </br>
							<div data-inter="cityLabel" class='titles'> City</div>
							<input type="text" name="city" id="city" value="Πόλη" />
							<label for="city" class="err_msg"> </label>   </br>     
							<div data-inter="postCode" class='titles'> Post code</div>
							<input type="text" name="postCode" id="postCode" value="Τ.Κ" />
							<label for="postCode" class="err_msg"> </label> </br> 		
							<div data-inter="phone" class='titles'> Home phone</div>
							<input type="text" name="phone" id="phone" value="Τηλέφωνο Οικίας" />
							<label for="phone" class="err_msg"> </label> </br>
							<div data-inter="cellPhone" class='titles'>Mobile phone</div>
							<input type="text" name="cellPhone" id="cellPhone" value="Κινητό Τηλέφωνο" />
							<label for="cellPhone" class="err_msg"> </label> 
							<div data-inter="Email" class='titles'> E-mail</div>
							<input type="text" name="email" id="email" value="E-mail" />
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
						<h1 data-inter="ERROR" class='error_p' style='display:none'>ERROR</h1>
						<p data-inter="regFailed2" class='error_p' style='display:none'>An error came up. Please try again!</p>
						<div id='form_loading_p'>
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
							<textarea name="biog" id="biog"> <?php echo $staffData[0]['Biography']; ?>" </textarea>
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
						<h1 data-inter="infoEdit" class='msg'>Info edit</h1>
						<h1 data-inter="ERROR" class='error_p' style='display:none'>ERROR</h1>
						<p data-inter="regFailed2" class='error_p' style='display:none'>An error came up. Please try again!</p>
						<div id='form_loading_p2'>
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
						<h1 data-inter="ERROR" class='error_p' style='display:none'>ERROR</h1>
						<p data-inter="passEditMsg" class='msg_p succ3'>Your previous password is required.</p>
						<p data-inter="regFailed2" class='error_p' style='display:none'>An error came up. Please try again!</p>	
						<div id='form_loading_p3'>
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
	
	$(document).ready(function() {
		$('#address').val('  <?php echo $staffData[0]['Address']; ?>');
		$('#city').val('  <?php echo $staffData[0]['City']; ?>');
		$('#postCode').val('  <?php echo $staffData[0]['Postal_code']; ?>');
		$('#phone').val('  <?php echo $staffData[0]['Home_phone']; ?>');
		$('#cellPhone').val('  <?php echo $staffData[0]['Mobile_phone']; ?>');
		$('#email').val(' <?php echo $staffData[0]['Email']; ?>');
	});
</script>	