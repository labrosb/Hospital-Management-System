<?php 

	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/config.inc.php");			// Connection to database
	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	

	check_if_manager();					

	if(!check_and_update_session()){		// Checking session to prevent unauthorized access
		$output = json_encode('EXPIRED');	// or destoys it and prevents access
		exit($output);
	}	
	
	$id = $_GET['id'];						// Patient's id	

	$con = DB_Connect();					// Connecting to database	
			
	try {	
		$stmt = $con->prepare('SELECT Name, Surname, Specialty_id FROM medical_staff WHERE Id = :id LIMIT 1');		
		$stmt->execute(array('id' => $id));	
		
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{	
			$name = $row['Name'];			// Retrieves users name - surname
			$surname = $row['Surname'];
			$speciality = $row['Specialty_id']; 
		}
		$con=null;
	}
	catch (PDOException $e) { die("Connection Error"); }
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title> <?php echo $name." ".$surname?> - Schedule</title>
		
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/calendar/dhtmlxscheduler_glossy.css" charset="utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/calendar/custom.css" title" charset="utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/msgBoxLight.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../../styles/menu.css">

		
		<script type="text/javascript" src="../../client_processes/jquery/jquery-1.8.2.min.js"> </script>	
		<script type="text/javascript" src="../../client_processes/calendar/dhtml/dhtmlxscheduler.js" charset="utf-8"></script>
		<script type="text/javascript" src="../../client_processes/calendar/dhtml/dhtmlxscheduler_serialize.js"></script>	
		<script type="text/javascript" src="../../client_processes/jquery/jquery.msgBox.js"></script>
		<script type="text/javascript" src="../../client_processes/calendar/general_calendar_actions.js"></script>	
		<script type="text/javascript" src="../../client_processes/general_functions/general_functions.js"></script>
		<script type="text/javascript" src="../../client_processes/general_functions/session_checker_calendar.js"></script>	
		<script type="text/javascript" src="../../client_processes/general_functions/localization.js"> </script>			
		<script type="text/javascript">
			var defaultLang= 'en';			
			$(document).ready(function() 
			{					
				make_list('parent',1);			
				init(<?php print $id ?>);
				speciality_set(<?php print $speciality ?>);
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
			<div id="patient_label" class="dhx_cal_lsection" style="display:none;">Patient code</div>
			<div id="patient_field" class="dhx_cal_ltext" style="height:23px; display:none;">
				<input id="patient_id" type="text" name="patient_id"  style="width:98%;">
			</div>
			<div id="ward_label" class="dhx_cal_lsection" style="display:none;">Ward / Unit / Building</div>
			<div id="ward_field" class="dhx_cal_ltext" style="height:23px; display:none;">
				<input id="ward" type="text" name="ward"  style="width:98%;">
			</div>					
			<div id="more_label" class="dhx_cal_lsection">More</div>				
			<div id="more_field" class="dhx_cal_ltext" style="height:70px;">					
				<input id="more" type="text" name="more" style="width:98%; height:100%;" >
			</div>
			<div id="hidden_field" class="dhx_cal_ltext" style="height:70px; display:none;">					
				<input id="startDate" type="text" name="startDate" style="width:98%; height:100%;" >
				<input id="endDate" type="text" name="endDate" style="width:98%; height:100%;" >				
			</div>	
			<div id="date_label" class="dhx_cal_lsection" style="display:none;">Date/Time</div>			
			<?php include 'time_options.php'; ?>		
			<div class="dhx_btn_set dhx_left_btn_set dhx_save_btn_set" name="update" id="update" onclick="update_event(null, <?php print $id ?>)">
				<div class="dhx_save_btn" dhx_button="1"></div>
				<div>Update</div>
			</div>			
			<div class="dhx_btn_set dhx_left_btn_set dhx_save_btn_set" name="save" id="save" onclick="save_event(null, <?php print $id ?>)">
				<div class="dhx_save_btn" dhx_button="1"></div>
				<div>Save</div>
			</div>
			<div class="dhx_btn_set dhx_left_btn_set dhx_cancel_btn_set" value="cancel" id="close" onclick="close_form(null, <?php print $id ?>)">
				<div class="dhx_cancel_btn" dhx_button="1"></div>
				<div>Cancel</div>
			</div>
			<div class="dhx_btn_set dhx_right_btn_set dhx_delete_btn_set"  name="delete" id="delete" onclick="delete_event(null, <?php print $id ?>)" style="float:right;">
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
	<script type="text/javascript" src="../../client_processes/calendar/doctor_manager_calendar.js"></script>
	<script type="text/javascript">
		changeLang(defaultLang);
	</script>
</html>