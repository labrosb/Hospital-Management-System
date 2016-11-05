<?php 
	session_start();	
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions
	
	check_if_manager();					// Checking session to prevent unauthorized access

	if(!check_and_update_session()){	// Checking if session has expired and updates timout and id
		echo json_encode('EXPIRED');	// or destoys it and prevents access
		exit;
	}
	
	$id = $_GET['id'];
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<title> Schedule </title>

		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/calendar/dhtmlxscheduler_glossy.css" charset="utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/calendar/custom.css" charset="utf-8">
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
		<script type="text/javascript" src="../../client_processes/calendar/units.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function() 
			{
				var defaultLang= 'en';	
				changeLang(defaultLang);
				init(<?php print $id ?>);
			});
	</script>
	
	</head>
	
	<body>
		<div id ="external_field_msgs">
			<div id="extra_popup"> 
					<div class="msgBoxTitle"> 
						FAIL!
					</div>
				<div class="msgBoxContainer">
					<div class="msgBoxImage">
						<img src="../../styles/images/msgBox/alert.png">
					</div>
					<div class="msgBoxContent">
						
					</div>
					<div class="msgBoxButtons" style="text-align: center; margin-top: 5px;">
						<input id="okButton" class="msgButton" type="button" value="OK" name="OK" style="float:right;">
					</div>			
				</div>
			</div>
		</div>
		<div id="my_form" class="dhx_cal_light">
			<div class="dhx_cal_ltitle">
				<span class="dhx_mark"></span>
				<span class="dhx_title">New event</span>
			</div>
			<div id="parent_label" class="dhx_cal_lsection">Type</div>
			<div id="parent_field" class="dhx_cal_ltext" style="height:23px;">
				<select style="width:100%;" name="parent" id="parent">
					<option value="6">Work shift</option>
					<option value="5">Call duty</option>
				</select>
			</div>

			<div id="doc_label" class="dhx_cal_lsection">Doctor</div>
			<div id="doc_field" class="dhx_cal_ltext" style="height:23px;">
				<input id="doc" type="text" name="doc"  style="width:98%;">
			</div>					
			<div id="more_label" class="dhx_cal_lsection">More</div>				
			<div id="more_field" class="dhx_cal_ltext" style="height:70px;">					
				<input id="more" type="text" name="more" style="width:98%; height:100%;" >
			</div>
			<div id="hidden_field" class="dhx_cal_ltext" style="height:70px; display:none;">					
				<input id="startDate" type="text" name="startDate" style="width:98%; height:100%;" >
				<input id="endDate" type="text" name="endDate" style="width:98%; height:100%;" >				
			</div>	
			<?php include 'time_options.php'; ?>		
			<div class="dhx_btn_set dhx_left_btn_set dhx_save_btn_set" name="update" id="update" onclick="update_event( <?php print $id ?>, null )">
				<div class="dhx_save_btn" dhx_button="1"></div>
				<div>Update</div>
			</div>			
			<div class="dhx_btn_set dhx_left_btn_set dhx_save_btn_set" name="save" id="save" onclick="save_event( <?php print $id ?>, null )">
				<div class="dhx_save_btn" dhx_button="1"></div>
				<div>Save</div>
			</div>
			<div class="dhx_btn_set dhx_left_btn_set dhx_cancel_btn_set" value="cancel" id="close" onclick="close_form()">
				<div class="dhx_cancel_btn" dhx_button="1"></div>
				<div>Cancel</div>
			</div>
			<div class="dhx_btn_set dhx_right_btn_set dhx_delete_btn_set"  name="delete" id="delete" onclick="delete_event(<?php print $id ?>, null)" style="float:right;">
				<div class="dhx_delete_btn" dhx_button="1"></div>
				<div>Delete</div>
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

	<script type="text/javascript" src="../../client_processes/calendar/calendar_actions.js"></script>	
	<script type="text/javascript" src="../../client_processes/calendar/unit_calendar.js"></script>
</html>