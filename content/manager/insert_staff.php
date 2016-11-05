<?php
	if(!isset($_SESSION)){ session_start();}
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_manager();					// Checking session to prevent unauthorized access
	
	if (!check_session_timer()){exit;}	 // Checking session (if exists) to see if is expired	
?>

    <script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"></script>
	<script type="text/javascript" src="client_processes/manager_functions/doctors_registration.js"></script>
    <div id="intro">
      <h1 data-inter="newMedicalStaffTitle">New Medical Staff</h1>
      <div class="content_page">
		  <form id="myform" action="#" method="post">
			  <!-- #first_step -->
			  <div id="first_step">
				<div id="form_left" style="height:520px";>
					<div id="myform_container">
						<div class="myform">
							<input type="text" name="name" id="name" value="* Name" /> 
							<label for="name" class="err_msg"> </label> 
							<input type="text" name="surname" id="surname" value="* Surname" /> 
							<label for="surname" class="err_msg"> </label> 
							<input type="text" name="fathersName" id="fathersName" value="* Father's name" />
							<label for="fathersName"  class="err_msg"> </label> 
							<input type="text" name="mothersName" id="mothersName" value=" &nbsp Mother's name" />
							<label for="mothersName"  class="err_msg"> </label> 							
							<select id="sex" name="sex">
								<option data-inter="male">&nbsp Male</option>
								<option data-inter="female">&nbsp Female</option>
							</select> 
							<label for="sex" class="err_msg"> </label> 
							<input type="text" name="birthDate" id="birthDate" value="* Birth date" /> 
							<label for="birthDate" class="err_msg"> </label>
						</div><!-- clearfix -->
						<input class="submit_staff" type="button" name="submit_first" id="submit_first" value="Next" />
					</div>
				</div>	
				</br>
				<div id="form_right" class="right_staff1" style="height:540px">
					<div id='form_msg'>
						<h1 data-inter="firstStep"> </h1>
						<p data-inter="starFields"> </p>
					</div>
				</div>
			</div>
			
			<div id="second_step">
				<div id="form_left">
					<div id="myform_container" style="height:580px;">
						<div class="myform">
							<input type="text" name="address" id="address" value="Address" />
							<label for="address" class="err_msg"> </label>
							<input type="text" name="city" id="city" value="City" />
							<label for="city" class="err_msg"> </label>               
							<input type="text" name="postCode" id="postCode" value="Post code" />
							<label for="postCode" class="err_msg"> </label>					
							<input type="text" name="phone" id="phone" value="Home number" />
							<label for="phone" class="err_msg"> </label>
							<input type="text" name="cellPhone" id="cellPhone" value="Mobile phone" />
							<label for="cellPhone" class="err_msg"> </label>
							<input type="text" name="workPhoneStaff" id="workPhoneStaff" value="Work phone" />
							<label for="workPhoneStaff" class="err_msg"> </label>							
							<input type="text" name="email" id="email" value="E-mail" />
							<label for="email" class="err_msg"> </label>  
						</div>
						<input class="submit_staff_next" type="button" name="submit_second" id="submit_second" value="Next" />
						<input class="submit_staff_prev" type="button" name="back_second" id="back_second" value="Previous" />					
					</div>
				</div>
				</br>
				<div id="form_right" class="right_staff2">
					<div id='form_msg'>
						<h1 data-inter="secondStep"> </h1>
						<p data-inter="starFields"> </p>
					</div>
					
				</div>
			</div>
			
			<div id="third_step">
				<div id="form_left"  style="height:500px">
					<div id="myform_container">
						<div class="myform">
							<div data-inter="specialtyLabel" class='titles'> Specialty</div>
							<select class='categories_select' id='specialty'> </select>
							<div data-inter="moreInfoLabel" class='titles'> More info</div>
							<textarea name="biog" id="biog"> </textarea>
							<label for="biog" class="err_msg"> </label> </br>	
							<div data-inter="passwordLabel" class='titles'> Password</div> 							
							<input type="password" name="password" id="password" value="Password" />
							<label for="password" class="err_msg"> </label>		
							<div data-inter="cPasswordLabel" class='titles'> Confirm Password</div> 
							<input type="password" name="cpassword" id="cpassword" value="Password" />			
							<label for="cpassword"  class="err_msg"> </label>							
						</div>
						
						<input class="send_staff_submit" type="submit" name="submit_fourth" id="submit_fourth" value="Submit" />     
						<input class="submit_staff_prev2" type="button" name="back_third" id="back_third" value="Previous" />						
					</div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
				</div>
				</br>
				<div id="form_right" class="right_staff3">
					<div id='form_msg'>
						<h1 data-inter="thirdStep"></h1>
						<p data-inter="starFields"> </p>
					</div>
				</div>
			</div>				
			
			<div id="fourth_step"> </div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
			</br></br>
			<div id="progress_bar">
				<div id="progress"></div>
				<div id="progress_text" data-inter="Completed_0">0% Completed</div>
			</div>	
		</form>
   </div>
 </div>

  	<script type="text/javascript">
		changeLang(defaultLang);	
	</script>
