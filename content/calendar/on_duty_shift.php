<?php 
	session_start(); 

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions
	
	check_if_doctor();					// Checking session to prevent unauthorized access

	if(!check_and_update_session()){	// Checking if session has expired and updates timout and id
		echo json_encode('EXPIRED');	// or destoys it and prevents access
		exit;
	}
	
?>
<html>
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<title>Call duty - Work shift</title>
		
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/calendar/dhtmlxscheduler_glossy.css" charset="utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/calendar/custom.css" title" charset="utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/msgBoxLight.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/menu.css">

		<script type="text/javascript" src="../../client_processes/jquery/jquery-1.8.2.min.js"> </script>
		<script type="text/javascript" src="../../client_processes/calendar/dhtml/dhtmlxscheduler.js" charset="utf-8"></script>
		<script type="text/javascript" src="../../client_processes/calendar/dhtml/dhtmlxscheduler_serialize.js"></script>
		<script type="text/javascript" src="../../client_processes/general_functions/localization.js"> </script>			
		<script type="text/javascript" src="../../client_processes/general_functions/general_functions.js"></script>
		<script type="text/javascript" src="../../client_processes/general_functions/session_checker_calendar.js"></script>			
		<script type="text/javascript" src="../../client_processes/jquery/jquery.msgBox.js"></script>
		<script type="text/javascript" src="../../client_processes/calendar/general_calendar_actions.js"></script>

		<script type="text/javascript">
			var defaultLang= 'en';	
			changeLang(defaultLang);
				
			$(document).ready(function() 
			{
				make_list('parent',1);
				init(<?php print $_SESSION['id'] ?>);
			});
		</script>

	</head>
	<body>
		<div id="my_form" class="dhx_cal_light">
			<div class="dhx_cal_ltitle">
				<span class="dhx_mark"></span>
				<span class="dhx_title">My program</span>
			</div>
			<div id="parent_label" class="dhx_cal_lsection">Type</div>
			<div id="parent_field" class="dhx_cal_ltext" style="height:23px;">
				<select style="width:100%;" name="parent" id="parent" onchange="make(this)">
					<option> </option>
				</select>
			</div>
			<div id="child_label" class="dhx_cal_lsection" style="display:none;">Category</div>
			<div id="child_field" class="dhx_cal_ltext" style="height:23px; display:none">
				<select style="width:100%;" name="child" id="child">
					<option> </option>
				</select>
			</div>
			<div id="child2_label" class="dhx_cal_lsection" style="display:none;">Building</div>
			<div id="child2_field" class="dhx_cal_ltext" style="height:23px; display:none">
				<select style="width:100%;" name="child2" id="child2">
					<option> </option>
				</select>
			</div>	
			<div id="patient_label" class="dhx_cal_lsection" style="display:none;">Patient</div>
			<div id="patient_field" class="dhx_cal_ltext" style="height:23px; display:none;">
				<input id="patient" type="text" name="patient"  style="width:98%;">
			</div>
			<div id="more_label" class="dhx_cal_lsection">More</div>				
			<div id="more_field" class="dhx_cal_ltext" style="height:70px;">					
				<input id="more" type="text" name="more" style="width:98%; height:100%;" >
			</div>
			<div id="hidden_field" class="dhx_cal_ltext" style="height:70px; display:none;">					
				<input id="startDate" type="text" name="startDate" style="width:98%; height:100%;" >
				<input id="endDate" type="text" name="endDate" style="width:98%; height:100%;" >				
			</div>	
			<div id="Date / Time" class="dhx_cal_lsection">Date / Time</div>				
			<?php include 'time_options.php'; ?>		
			<div class="dhx_btn_set dhx_left_btn_set dhx_cancel_btn_set" value="Exit" id="close" onclick="close_form()">
				<div class="dhx_cancel_btn" dhx_button="1"></div>
				<div>Exit</div>
			</div>
						
			<div id ='clr' style="height:10px;"></div>
		</div>		
		
		<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
			<div class="dhx_cal_navline">
				<div class="dhx_cal_prev_button">&nbsp;</div>
				<div class="dhx_cal_next_button">&nbsp;</div>
				<div class="dhx_cal_today_button"></div>
				<div class="dhx_cal_date"></div>
				<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
				<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
				<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
			</div>
			<div class="dhx_cal_header">
			</div>
			<div class="dhx_cal_data">
			</div>
		</div>
	
	</body>		
	<script type="text/javascript" src="../../client_processes/calendar/doctor_duty_shifts_calendar.js"></script>		

</html>