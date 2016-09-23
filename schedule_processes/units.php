<?php 
	$id = $_GET['id'];
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title> Schedule</title>
<link rel="stylesheet" href="../styles/scheduler/dhtmlxscheduler_glossy.css" type="text/css" media="screen" title="no title" charset="utf-8">
<style type="text/css" media="screen">
	html, body {
		margin: 0px;
		padding: 0px;
		height: 100%;
		overflow: hidden;
	}

	#my_form {
		position: absolute;
		top: 100px;
		left: 200px;
		z-index: 10001;
		display: none;
		visibility: visible; 
		background-color: white;
		border: 2px outset gray;
		padding: 20px;
		font-family: Tahoma;
		font-size: 10pt;
		height: auto;
	}

	#my_form label {
		width: 200px;
	}
	.icon_edit,.icon_delete{display: none;}
		
	#external_field_msgs{		
		position: absolute;
		left:625px;
		top:200px;
		z-index: 99999;
		display:none
	}
	</style>
	
<script type="text/javascript" src="../client_processes/jquery/jquery-1.9.1.js"> </script>	
<script type="text/javascript" src="../client_processes/scheduler/dhtmlxscheduler.js" charset="utf-8"></script>
<script type="text/javascript"src="../client_processes/scheduler/dhtmlxscheduler_serialize.js"></script>
<script type="text/javascript" src="../client_processes/jquery/jquery.msgBox.js"></script>
<link rel="stylesheet" href="../styles/msgBoxLight.css" type="text/css" />
<link rel="stylesheet" href="../styles/menu.css" type="text/css" />

</head>
	<body>
	<script type="text/javascript">
		$(document).ready(function() {
			init();
		});
	</script>
		<div id ="external_field_msgs">
			<div id="extra_popup"> 
					<div class="msgBoxTitle"> 
						FAIL!
					</div>
				<div class="msgBoxContainer">
					<div class="msgBoxImage">
						<img src="../styles/images/msgBox/alert.png">
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
			<div class="dhx_btn_set dhx_left_btn_set dhx_save_btn_set" name="update" id="update" onclick="update_event()">
				<div class="dhx_save_btn" dhx_button="1"></div>
				<div>Update</div>
			</div>			
			<div class="dhx_btn_set dhx_left_btn_set dhx_save_btn_set" name="save" id="save" onclick="save_event()">
				<div class="dhx_save_btn" dhx_button="1"></div>
				<div>Save</div>
			</div>
			<div class="dhx_btn_set dhx_left_btn_set dhx_cancel_btn_set" value="cancel" id="close" onclick="close_form()">
				<div class="dhx_cancel_btn" dhx_button="1"></div>
				<div>Cancel</div>
			</div>
			<div class="dhx_btn_set dhx_right_btn_set dhx_delete_btn_set"  name="delete" id="delete" onclick="delete_event()" style="float:right;">
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
	
<script type="text/javascript">
	function setUpdate(){
		$('#save').hide();
		$('#update').show();
		return true;
	}
	function setSave(){
		$('#save').show();
		$('#update').hide();
		return true;
	}
	setSave();
	
	
	function init() {	
		scheduler.config.xml_date = "%Y-%m-%d %H:%i";	
		//scheduler.config.auto_end_date = true;		
		scheduler.config.details_on_dblclick = true;
		scheduler.config.details_on_create = true;
		scheduler.config.start_on_monday = true;
        //scheduler.config.time_step = 30;
        scheduler.config.multi_day = true;
		scheduler.init('scheduler_here', null, "week");		
		scheduler.load("units_events.php?id=<?php print $id ?>", "json");
	}
	
	
	$('.dhx_cal_data').click(function(){setSave();}); 
	
	scheduler.attachEvent("onClick",setUpdate);
	scheduler.attachEvent("onDblClick",setUpdate);

	var html = function(id) { return document.getElementById(id); };
	
	
	scheduler.showLightbox = function(id) {
	
		var ev = scheduler.getEvent(id);
		scheduler.startLightbox(id, html("my_form"));
		html("parent").value = ev.parent || "";
		html("more").value = ev.more || "";
		html("startDate").value =  ev.start_date;
		html("endDate").value =  ev.end_date;
		html("doc").value = ev.staff_id || "";
		
		if(ev.parent != 'undefined'){
			$("#parent").val($("#parent option:first").val());
		}
	
		
		var startDate = structStartDate(html("startDate").value);
		
		$('#start_time').val(startDate['startHourMinutes']);
		$('#start_day').val(startDate['startDay']);
		$('#start_month').val(startDate['startMonth']);
		$('#start_year').val(startDate['startYear']);		
		
		var endDate = structEndDate(html("startDate").value );	
		$('#end_time').val(endDate['endHourMinutes']);
		$('#end_day').val(endDate['endDay']);
		$('#end_month').val(endDate['endMonth']);
		$('#end_year').val(endDate['endYear']);
		

		
	};
	
	
	$('#parent').change(function(){ 
		var startDate = structStartDate(html("startDate").value);	
		$('#start_time').val(startDate['startHourMinutes']);
		$('#start_day').val(startDate['startDay']);
		$('#start_month').val(startDate['startMonth']);
		$('#start_year').val(startDate['startYear']);		
		
		var endDate = structEndDate(html("startDate").value);			
		$('#end_time').val(endDate['endHourMinutes']);
		$('#end_day').val(endDate['endDay']);
		$('#end_month').val(endDate['endMonth']);
		$('#end_year').val(endDate['endYear']);
	});	

	
	function structStartDate(stringStartDate)
	{
		var myDate = new Date(stringStartDate);
		var startDate = new Array();
		startDate['startDay'] = myDate.getDate();
		startDate['startMonth'] = myDate.getMonth() + 1; 
		startDate['startYear']  = myDate.getFullYear();

		if($("#parent option:selected").val() == 1 || ($("#parent option:selected").val() == 3))
		{
			startDate['startHour'] = "8";
			startDate['startMinute'] = "0";	
		}else if($("#parent option:selected").val() == 2)
		{
			startDate['startHour'] = "0";
			startDate['startMinute'] = "0";			
		}else
		{
			startDate['startHour'] = myDate.getHours();
			startDate['startMinute'] = myDate.getMinutes();
		}
		
		if (startDate['startDay'] > 0 && startDate['startDay'] < 10)
		{
			startDate['startDay'] = "0" + startDate['startDay'];
		}	
		if (startDate['startMonth'] > 0 && startDate['startMonth'] < 10 
		&&  startDate['startMonth'])
		{
			startDate['startMonth'] = "0" + startDate['startMonth'];
		}		
		if (startDate['startHour'] >= 0 && startDate['startHour'] < 10 )
		{
			startDate['startHour'] = "0"+startDate['startHour'];
		}	
		if (startDate['startMinute'] >= 0 && startDate['startMinute'] < 10 )
		{
			startDate['startMinute'] = "0"+startDate['startMinute'];
		}				
		startDate['startHourMinutes'] =  startDate['startHour']+":"+startDate['startMinute'];
		return startDate;
	}
	
	function structEndDate(stringStartDate)
	{	
		var myDate = new Date(stringStartDate);		
		if($("#parent option:selected").val() == 5 || ($("#parent option:selected").val() == 6))
		{
			myDate.setHours(myDate.getHours()+8);
		}else if ($("#parent option:selected").val() == 4 )
		{	
			myDate.setMinutes(myDate.getMinutes()+30);
		}
		
		var endDate = new Array();				
		endDate['endDay'] = myDate.getDate();
		endDate['endMonth'] = myDate.getMonth() + 1; 
		endDate['endYear']  = myDate.getFullYear();		
		
		if($("#parent option:selected").val() == 1 || $("#parent option:selected").val() == 2 || ($("#parent option:selected").val() == 3))
		{
			endDate['endHour'] = 21;
			endDate['endMinute'] = 55;				
		}else{
			endDate['endHour'] = myDate.getHours();
			endDate['endMinute'] = myDate.getMinutes();	
		}
		
		if (endDate['endDay'] > 0 && endDate['endDay'] < 10)
		{
			endDate['endDay'] = "0" + endDate['endDay'];
		}	
		if (endDate['endMonth'] > 0 && endDate['endMonth'] < 10 
		&& endDate['endMonth'])
		{
			endDate['endMonth'] = "0" + endDate['endMonth'];
		}	
		if (endDate['endHour'] >= 0 && endDate['endHour'] < 10 )
		{
			endDate['endHour'] = "0"+endDate['endHour'];
		}	
		if (endDate['endMinute'] >= 0 && endDate['endMinute'] < 10 )
		{
			endDate['endMinute'] = "0"+endDate['endMinute'];
		}				
		endDate['endHourMinutes'] =  endDate['endHour']+":"+endDate['endMinute'];
		
		return endDate;
		
	}
	

	function structCurrentDate(){
		var current = new Date();
		var now = new Array();				
		now['Day'] = current.getDate();
		now['Month'] = current.getMonth() + 1; 
		now['Year']  = current.getFullYear();
		now['Hour']  = current.getHours();		
		now['Minutes']  = current.getMinutes();	
		if(now['Day'] < 10){
			now['Day'] = "0"+now['Day'];
		}
		if(now['Month'] < 10){
			now['Month'] = "0"+now['Month'];
		}
		if(now['Hour'] < 10){
			now['Hour'] = "0"+now['Hour'];
		}
		if(now['Minutes'] < 10){
			now['Minutes'] = "0"+now['Minutes'];
		}		
		
		var currentDate = now['Year']+""+now['Month']+""+now['Day']+""+now['Hour']+""+now['Minutes'];
		
		return currentDate;
	}

	
	function action_execute(action, events, id, auto_day_off)
	{
		var start_date = $('#start_year').val()+"-"+$('#start_month').val()+"-"+$('#start_day').val()+" "+$('#start_time').val()+":00";
		var end_date = $('#end_year').val()+"-"+$('#end_month').val()+"-"+$('#end_day').val()+" "+$('#end_time').val()+":00";
		var event = $("#parent option:selected").val();
		var subReason = <?php print $id ?>;
		$.ajax({
			type: "POST",
			url: "execute.php",
			dataType: "json",	
			data:{
				  action: action,
				  event: event,
				  subReason: subReason,
				  comments: html("more").value,
				  startDate: start_date,
				  endDate: end_date,
				  staff_id: html("doc").value,
				  auto_day_off: auto_day_off,
				  id: id,
				  events: events
				 },
			async: false,
			global: false,						
			success: function(response){
						if(response.result == "done"){
							if (response.multiDelete == true){
								var cnt = response.deleted;
								var size = cnt.length;
								for(var i=0;i < size;i++){
									var ID = response.deleted[i].Id;
									scheduler.deleteEvent(ID);
								}
							}
							if (response.childDelete == true){
								scheduler.deleteEvent(response.childDeleted);	
								$.msgBox({
									title:"Day off deleted",
									content:"The day off that was connected with the call duty, scheduled on "+response.childDate+" is also deleted !" 
								});	

								
							}
							if (auto_day_off != null){
								if(response.dayOffExistance == 'true'){
									var dayOffDate = response.dayOffDate;	
									var dayOffday = response.dayOffday;
									var dayOffdaysAfter = response.dayOffdaysAfter;	
									$.msgBox({
										title:"Day off inserted!",
										content:"The day off is successfully inserted at "+dayOffDate+"!" 
									});		

								}else{
									$.msgBox({
										title:"Fail",
										content:"Could not find available day-off for submission up to 7 days after the call-duty."
									});	
								}
							}

							scheduler.load("units_events.php?id=<?php print $id ?>", "json");					
							
						}
						else if(response.result == "WARD AVAILABILITY ERROR"){
							$.msgBox({
								title:"Fail!",
								content:"There is no ward available for this examination this date/time!"
							});				
						}
						else{
							$.msgBox({
								title:"FAIL!",
								content:"An error came up!"
							});							
						}

					}
		});
			
	}
	
	function action_Query(action, day_off)
	{	
		var start_date = $('#start_year').val()+"-"+$('#start_month').val()+"-"+$('#start_day').val()+" "+$('#start_time').val()+":00";
		var end_date = $('#end_year').val()+"-"+$('#end_month').val()+"-"+$('#end_day').val()+" "+$('#end_time').val()+":00";
		
		$.ajax(
		{
			type: "POST",
			url: "schedulerValidation.php",
			dataType: "json",	
			data:{
				  event: html("parent").value,
				  action: action,
				  startDate: start_date,
				  endDate: end_date,
				  dayOff: day_off,
				  staff_id: html("doc").value,
				  docId: $("#doc").val()
				 },	
				 
			success:function(response)
				{ 
					if (response.dayOff != null){
						var dayOffArray = response.dayOff;
					}else{
						var dayOffArray = null;
					}
					var result;
					if (response.do == 'ASK')
					{
						result = $.msgBox({
						title: "Question",
						content: "The doctor has other events scheduled at that time. If you continue those events will be deleted. Are you sure??",
						type: "confirm",
						buttons: [{ value: "Yes" }, { value: "No" }],
						success: function (myresult) {
								if (myresult == "No")
								{
									return false;
								}
								else 
								{ //άδεια ή ρεπό 
									action_execute("delete&insert", response.events, null, dayOffArray);
								}
							}
						});						
					}
					else if (response.do == 'FORCE'){
						action_execute("delete&insert", response.events, null, dayOffArray);
					}
					else if (response.do == 'NOT'){
						$.msgBox({
								title:"Fail",
								content:"The doctor has other events scheduled at that time which can't be deleted!"
							});	
					}
					else if (response.do == 'NTN'){
						action_execute("insert", null, null, dayOffArray);
					}
						
				}
		});	
	}


	function CheckIfExists($who, $id)
	{
		var result = $.ajax({
			type: "POST",
			url: "existanceChecker.php",
			data:{
				  who: $who,
				  id: $id
				 },	
			 async: false,						
			success:function(response){return response;}			 
		}).responseText;	
		
		return result;
	}	
	
	function limitations()
	{
		//var start_date = $('#start_year').val()+"-"+$('#start_month').val()+"-"+$('#start_day').val()+" "+$('#start_time').val()+":00";
		//var end_date = $('#end_year').val()+"-"+$('#end_month').val()+"-"+$('#end_day').val()+" "+$('#end_time').val()+":00";
		
		var start_time = $('#start_time').val().split(":");
		var end_time = $('#end_time').val().split(":");
		
		var start_date_num = $('#start_year').val()+""+$('#start_month').val()+""+$('#start_day').val()+""+start_time[0]+""+start_time[1];
		var end_date_num = $('#end_year').val()+""+$('#end_month').val()+""+$('#end_day').val()+""+end_time[0]+""+end_time[1];

		var start = new Date();
		start.setFullYear($('#start_year').val(),$('#start_month').val()-1,$('#start_day').val());
		var end = new Date();
		end.setFullYear($('#end_year').val(),$('#end_month').val()-1,$('#end_day').val());	

		var start_day = start.getDay();	
		var end_day = start.getDay();	
		
		var currentDate = structCurrentDate();
		if(start_date_num < currentDate){ 
			$(".msgBoxContent").html("<p><span>The date that you selected is older than the current!</span></p>");
			$("#external_field_msgs").show();			
			return false;
		}
		if(start_date_num > end_date_num){ 
			$(".msgBoxContent").html("<p><span>The start date must be older than the end date!</span></p>");
			$("#external_field_msgs").show();				
			return false;
		}
		
		if($("#doc").val() == ''){
				$(".msgBoxContent").html("<p><span>Insert the doctor's code!</span></p>");
			$("#external_field_msgs").show();				
			return false;
		}else{
			var docId = $("#doc").val();
			var thisPatient = CheckIfExists('doctor', docId);
			if(thisPatient == 'NOT EXISTS'){
					$(".msgBoxContent").html("<p><span>No doctor found with that code!</span></p>");
				$("#external_field_msgs").show();					
				return false;
			}
		}
		
		if (($("#parent option:selected").val() == 3) || 
			 ($("#parent option:selected").val() == 4)||
			 ($("#parent option:selected").val() == 6))
		{ 
			if((start_day == 0) || (end_day == 6)) {
				$(".msgBoxContent").html("<p><span>This event can't be inserted at weekend!</span></p>");
				$("#external_field_msgs").show();					
				return false;
			}				
			else if(($('#start_time').val() < '07:00') ||
				($('#start_time').val() >'23:00') || 
				($('#end_time').val() < '07:00')  || 
				($('#end_time').val() > '23:00')){
				$(".msgBoxContent").html("<p><span>The event is set out of the limits of schedule!</span></p>");
				$("#external_field_msgs").show();
				return false;
			}	
				
		}

	}		
	
	
	function save_event() 
	{
		var check = limitations();
		if (check === false){
			return false;
		}
	
		var ev = scheduler.getEvent(scheduler.getState().lightbox_id);
		
		ev.text = $("#parent option:selected").text();
		limitations();
		if($("#child option:selected").text() != '' &&
		   $("#child option:selected").text() != ' ')
		{
			ev.text += ' : '+$("#child option:selected").text();
		}

		if($("#child2 option:selected").text() != ''){
			ev.text += '</br>'+$("#child2 option:selected").text();
		}
		
		if($("#more").val() != ''){
			ev.text += '</br> Comments : '+$("#more").val();
		}
		
		ev.more = html("more").value;
		
		if ($("#parent option:selected").val() == 5)
		{
			$.msgBox({
				title: "Question",
				content: "Insert also day off?",
				type: "confirm",
				buttons: [{ value: "Yes" }, { value: "No" }, { value: "Cancel"}],
				success: function (myresult) {
					if (myresult == "Yes") {
						action_Query("insert", true);
					}
					else if (myresult == "No"){
						action_Query("insert", false);
					}
				}
			});

		}
		else
		{
			action_Query("insert", false);
		}

		scheduler.endLightbox(false, html("my_form"));

		
	}
	
	
	
	function close_form() {
		scheduler.endLightbox(false, html("my_form"));
	}

	function delete_event() {
		var event_id = scheduler.getState().lightbox_id;
		action_execute("delete", null, event_id, null);	
		scheduler.endLightbox(false, html("my_form"));
		scheduler.deleteEvent(event_id);
	}		
	
	function update_event() { 
		var event_id = scheduler.getState().lightbox_id;
		action_execute("update", null, event_id, null);	
		scheduler.endLightbox(false, html("my_form"));
		scheduler.deleteEvent(event_id);
	}
	
	$('#okButton').click(function(){ 
		$('#external_field_msgs').hide();	
	});	
	
</script>		

</html>