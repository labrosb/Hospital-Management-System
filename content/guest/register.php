    <script type="text/javascript" src="client_processes/jquery/jquery-ui-1.9.1.custom.min.js"></script>	
	<script type="text/javascript" src="client_processes/patients_registration.js"></script>
    <div id="intro"> 
      <h1 data-inter="registerTitle">Patient registration</h1>
      <div class="content_page">
		  <form id="myform" class="patientRegistration" action="#" method="post">
			  <!-- #first_step -->
			  <div id="first_step">
				<div id="form_left" style="height:520px";>
					<div id="myform_container">
						<div class="myform">
							<input type="text" name="name" id="name" value="Name" /> 
							<label for="name" class="err_msg"> </label> 
							<input type="text" name="surname" id="surname" value="Surname" /> 
							<label for="surname" class="err_msg"> </label> 
							<input type="text" name="fathersName" id="fathersName" value="Father's name" />
							<label for="fathersName"  class="err_msg"> </label> 
							<select id="sex" name="sex">
								<option data-inter="male" id ="male">Male</option>
								<option data-inter="female" id = "female">Female</option>
							</select> 
							<label for="sex" class="err_msg"> </label> 
							<input type="text" name="birthDate" id="birthDate" value="Birth date" /> 
							<label for="birthDate" class="err_msg"> </label>
						</div><!-- clearfix -->
						<input class="submit" type="button" name="submit_first" id="submit_first" value="Next" />
					</div>
				</div>	
				<div id="form_right" class="right1" style="height:540px">
					<div id='form_msg'>
						<h1 data-inter="firstStep">First step</br> Personal info.</h1>
						<p data-inter="starFields"> Fields with (*) are mandatory.</p>
					</div>
				</div>
			</div>
			
			<div id="second_step" style="height:400px";>
				<div id="form_left">
					<div id="myform_container">
						<div class="myform">
							<input type="text" name="address" id="address" value="Address" />
							<label for="address" class="err_msg"> </label>
							<input type="text" name="city" id="city" value="City" />
							<label for="city" class="err_msg"> </label>               
							<input type="text" name="postCode" id="postCode" value="Post code" />
							<label for="postCode" class="err_msg"> </label>					
							<input type="text" name="phone" id="phone" value="Home phone" />
							<label for="phone" class="err_msg"> </label>
							<input type="text" name="cellPhone" id="cellPhone" value="Mobile phone" />
							<label for="cellPhone" class="err_msg"> </label>
							<input type="text" name="email" id="email" value="E-mail" />
							<label for="email" class="err_msg"> </label>  
						</div><!-- clearfix -->
						<div class="clear"></div><!-- /clearfix -->
						<input class="submit_next" type="button" name="submit_second" id="submit_second" value="Next" />
						<input class="submit_prev" type="button" name="back_second" id="back_second" value="Previous" />					
						<div class="clear">	</div><!-- /clearfix -->
					</div>
				</div>
				<div id="form_right" class="right3">
					<div id='form_msg'>
						<h1 data-inter="secondStep">Second step</br>Contact info.</h1>
						<p data-inter="starFields"> Fields with (*) are mandatory.</p>
					</div>
				</div>
			</div>
			
			<div id="third_step">
				<div id="form_left"  style="height:500px">
					<div id="myform_container">
						<div class="myform">
							<div data-inter="Password" class='titles'> Password</div> 
							<input type="password" name="password" id="password" value="Password" />
							<label for="password" class="err_msg"> </label>		
							<div data-inter="passConfirm" class='titles'> Confirm Password</div> 
							<input type="password" name="cpassword" id="cpassword" value="Password" />			
							<label for="cpassword"  class="err_msg"> </label>				
							<select id="insurer" name="insurer">
								<option data-inter="insurance" selected="selected" disabled="disabled">Insurance</option>
								<option data-inter="insuranceOption1">None</option>
								<option data-inter="insuranceOption2">ΙΚΑ</option>
								<option data-inter="insuranceOption3">ΤΕΒΕ</option>
								<option data-inter="insuranceOption4">Other</option>
							</select>				
							<input type="text" name="insuranceCode" id="insuranceCode" value="Insurance code" disabled="disabled" />
							<label for="insuranceCode" id="insurer_code_label" class="err_msg"> </label> <!-- clearfix --><div class="clear"></div><!-- /clearfix -->				
						</div>
						<input class="send_submit" type="submit" name="submit_fourth" id="submit_fourth" value="Submit" />     
						<input class="submit_prev2" type="button" name="back_third" id="back_third" value="Previous" />						
					</div>      <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
				</div>
				<div id="form_right" class="right2">
					<div id='form_msg'>
						<h1 data-inter="thirdStep">Third step</br>Password & Insurance</h1>
						<p data-inter="starFields"> Fields with (*) are mandatory.</p>
					</div>
				</div>
			</div>				
			
			<div id="fourth_step"> </div>      
			
			<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
					
			<div id="progress_bar">
				<div id="progress"></div>
				<div  data-inter="Completed_0" id="progress_text">0% Completed</div>
			</div>	
		</form>
   </div>
 </div>
 <script>
	changeLang(defaultLang);
</script>
