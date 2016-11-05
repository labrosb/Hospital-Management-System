<?php
	if(!isset($_SESSION)){ session_start();} 
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	
		
	check_if_manager();						// Checking session to prevent unauthorized access

	if (!check_session_timer()){exit;}	 	// Checking session to see if is expired and update	
?>	

	<link rel="stylesheet" type="text/css" media="screen"="screen" href="styles/msgBoxLight.css">
	<script type="text/javascript" src="client_processes/jquery/jquery.bpopup-0.7.0.min.js"></script>
	<script type="text/javascript" src="client_processes/general_functions/general_functions.js"></script>
	<script type="text/javascript" src="client_processes/general_functions/session_checker.js"></script>
	<script type="text/javascript" src="client_processes/manager_functions/program.js"></script>
	
		<ul id="nav_manager">
			<li class="name"> 
				<a href="#"> 
					<span data-inter="managerLabel" id ="entityLabel"> Admin Staff </span>: 
					<?php echo ($_SESSION['name'].' '. $_SESSION['surname']);?> 
				</a>
				<ul class="name_sub">	
					<li class="logout"> <button class ="logout_button" type="button">Log out</button></li>
				</ul>
			</li>
			<li class="news"><a data-inter="news" href="news">News</a></li>
			<li class="staff"><a data-inter="medicalStaff" href="#">Medical Staff</a>
				<ul class="exams_sub">
					<li class="insert_staff"><a data-inter="insertStaff"  href="insert_staff">Insert new Staff</a></li>										
					<li class="search_staff"><a data-inter="searchStaff"  class="search_staff_click" href="#">Search Medical Staff</a></li>														
				</ul>
			</li>
			<li class="myProgram"><a data-inter="schedule"  href="#">Schedule</a>
				<ul class="myProgram_sub">
					<li><a data-inter="medicalStaff2" class="staff_program" href="#">Medical staff</a></li>				
					<li><a data-inter="units" class="units_program" href="#">Units</a></li>
				</ul>
			</li>
			<li class="contact"><a data-inter="contactUs" href="contact">Contact Us</a></li>
		</ul>
		
		
		
	<!-- ///// External fields ///// -->
	
	<div id ="external_field_staff">
		<div id="staff_popup">
			<div class="button bClose"><span>X</span></div> 
			<br>
			<div id="profile_content"> 
				<table id="profile_inner"> 
					<tbody>
						<tr>
							<td colspan="3" data-inter="cardTitle" style="text-align:center; font-weight:bold;"> Profile </td>
						</tr>
						<tr> 
							<td id="profile_photo_content" rowspan="16" style="vertical-align:middle;"> 
								<div id="doc_photo_content"> </div>
							</td> 
						</tr> 
						<tr> 
							<td class="staff_details" data-inter="cardName">Name: </td>
							<td class="name2"></td>	
						</tr> 
						<tr>
							<td class="staff_details" data-inter="cardSurname">Surname: </td> 
							<td class="surname"></td>
						</tr>
						<tr> 
							<td class="staff_details" data-inter="cardSpecialty">Specialty: </td>
							<td class="specialty" data-inter=""></td> 										
						</tr> 										
						<tr> 
							<td class="staff_details" data-inter="cardSex">Sex: </td> 
							<td class="sex"></td>									
						</tr>
						<tr> 
							<td class="staff_details" data-inter="cardAge">Age: </td> 
							<td class="birthDate"></td> 
						</tr> 										
						<tr> 
							<td class="staff_details" data-inter="cardFatherName">Father name: </td> 
							<td class="fathersName"></td>									
						</tr> 
						<tr> 
							<td class="staff_details" data-inter="cardMotherName">Mother name: </td> 
							<td class="mothers_name"></td>									
						</tr> 											
						<tr> 
							<td class="staff_details" data-inter="cardWorkPhone">Work number: </td> 
							<td class="Work_phone"></td>									
						</tr> 
						<tr> 
							<td class="staff_details" data-inter="cardHomePhone">Home number: </td> 
							<td class="Home_Phone"></td>									
						</tr>
						<tr> 
							<td class="staff_details" data-inter="cardMobilePhone"> Mobile number: </td> 
							 <td class="Mobile_phone"></td>
						<tr> 
							<td class="staff_details" data-inter="cardEmail"> E-mail: </td> 
							<td class="Email"></td>									
						</tr>										
						<tr> 
							<td class="staff_details" data-inter="cardAddress"> Address: </td> 
							<td class="Address"></td>									
						</tr>
						<tr> 
							<td class="staff_details" data-inter="cardCity"> City: </td> 
							 <td class="City"></td>									
						</tr>	
						<tr> 
							<td class="staff_details" data-inter="cardPostCode"> Post code: </td> 
							<td class="Postal_code"></td>									
						</tr>
						<tr> 
							<td class="staff_details" data-inter="cardHireDate"> Hire date: </td> 
							<td class="Hire_date"></td>									
						</tr>										
						<tr> 
							<td colspan="3" data-inter="cardMore" class="staff_details" style=" font-weight:bold;">More information</td>	
						</tr>
						<tr> 
							<td colspan="3" style="max-width:200px; padding:5px 10px;"><div class="biog"></div></td>	
						</tr>
						<tr> 
							<td colspan="3" class="delbutton" style="text-align:center"><button type="button" id="cardDeleteBtn" class="del_btn" value="">Delete doctor</button></td>	
						</tr> 											
					</tbody>
				</table> 
			</div>
		</div>
	</div>	

	<div id ="external_field_units">
		<div id="units_popup"> 
			<div class="msgBoxTitle" data-inter="unitsBoxTitle"> 
				CHOOSE UNIT
			</div>
			<div id="units_list">
			</div>
			<div class="msgBoxButtons" style="text-align: center; margin-top: 5px;">
				<input id="okButton" class="msgButton2" type="button" value="OK" name="OK">
				<input id="cancelButton" class="msgButton2" type="button" value="Cancel" name="Cancel">
			</div>			
		</div>
	</div>
