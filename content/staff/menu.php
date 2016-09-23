<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
	echo'<script type="text/javascript" src="client_processes/staff_notifications.js"></script>
		<script type="text/javascript" src="client_processes/program.js"></script>
		<ul id="nav_staff">
			<li class="name"> <a href="#">'.$_SESSION['specialty'].': ' .$_SESSION['name'].' '. $_SESSION['surname'].'</a>
				<ul class="name_sub">	
					<li class="edit_profile"><a data-inter="editProfile" href="edit_profile">Edit Profile</a></li>
					<li class="logout"> <button data-inter="logout" class ="logout_button" type="button">Log out</button></li>
				</ul>
			</li>
			<li class="news"><a data-inter="news" href="news"></a></li>
			<li class="exams"><a data-inter="examinations" href="#"></a> 
			<div id="notif_all_p"><p>0</p></div>

				<ul class="exams_sub">
					<li class="result_insertion"><a data-inter="insertResults" href="result_insertion"></a>
					<div id="notif_results_p"><p>0</p></div></li>										

				</ul>
			</li>
			<li class="myProgram"><a <a data-inter="schedule" href="#">Schedule</a>
				<ul class="myProgram_sub">
					<li class="daily_program"><a data-inter="daily" class="daily_program" href="#">Daily</a></li>				
					<li class="general_program"><a data-inter="weekly" class="general_program" href="#">Weekly</a></li>
					<li class="on_duty_shifts"> <a data-inter="duties_Shifts" class="on_duty_shifts"  href="#">Call duties / Work shifts</a></li>
				</ul>
			</li>
			<li class="contact"><a data-inter="contactUs" href="contact">Contact Us</a></li>
		</ul>';

?>