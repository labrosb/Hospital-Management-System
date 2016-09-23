<?php session_start(); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Call duty - Work swift</title>
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
</style>
	
<script type="text/javascript" src="../client_processes/jquery/jquery-1.9.1.min.js"> </script>
<script type="text/javascript" src="../client_processes/scheduler/dhtmlxscheduler.js" charset="utf-8"></script>
<script type="text/javascript" src="../client_processes/scheduler/dhtmlxscheduler_serialize.js"></script>


</head>
<body>
	<script type="text/javascript">
		$(document).ready(function() {
			make_list('parent',1);
			init();
		});
	</script>
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
		scheduler.config.readonly_form = true;		
		scheduler.config.details_on_dblclick = true;
		scheduler.config.details_on_create = false;
		scheduler.config.start_on_monday = true;
        //scheduler.config.time_step = 30;
        scheduler.config.multi_day = true;
		scheduler.init('scheduler_here', null, "week");		
		scheduler.load("on_duty_swift_events.php?id=<?php print $_SESSION['id'] ?>", "json");
		scheduler.config.dblclick_create = false;
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
		make_list('child', html("parent").value);
		
		if (ev.parent == 4){
			html("child").value = ev.exam_type_id;
			html("patient").value = ev.patient_name+ " "+ev.patient_surname+ " (" +ev.patient_id+")";
			//name, surname
			//ward
		}
		
		else if (ev.parent == 5){
			html("child").value = ev.unit_name;
			html("child2").value = ev.building_id;
			//name, surname
			//ward
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
			endDate['endHour'] = 23;
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
	


	function close_form() {
		scheduler.endLightbox(false, html("my_form"));
	}
	
	
	
	///////////////////////////////////////////////////////////	
	///////////////////////////////////////////////////////////	
	///////////////////////////////////////////////////////////	
	///////////////////////////////////////////////////////////	
	///////////////////////////////////////////////////////////	
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////	
	
	
		
	var choicelist = new Array();

	choicelist['parent'] = new Array();
	choicelist['child'] = new Array();
	choicelist['child2'] = new Array();

	function set_choicelist(x){
		choicelist = x;
	}
	
	function struct_lists(action){	
		$.ajax({
			type: "POST",
			url: "calendar_lists.php",
			data:{ 
				action: action,
				child_choice: $('#child :selected').val()
				},	
			dataType: "json",
			async: false,
			global: false,						
			success: function(response){
						if(action == "basic_lists"){
							set_choicelist(response);
						}else if(action == "child2"){
							$('#child2').html("");
							for(var i=0;i<response.length;i++){
								$('#child2').append($('<option>', {
									value: response.child2[i].building_id,
									text: response.child2[i].name
								}));
							}
						}
					}
		});
	}
	
	struct_lists("basic_lists");

	
	function make_list(myList, listOption)
	{ 
		remove_all(myList);
		remove_all('child2');
		$('#patient').val("");
		$('#child_label, #child_field, #child2_label, #child2_field, #patient_label, #patient_field').hide();
		
		var fragment = document.createDocumentFragment();
		var list = document.getElementById(myList);
		for(i=0; i < choicelist[myList].length; i++)
		{	choicelist[myList][i].parentId;
			if(choicelist[myList][i].parentId == 0 || choicelist[myList][i].parentId == listOption)
			{
				var new_option = document.createElement('option');
				var text = document.createTextNode(choicelist[myList][i].name);
				new_option.appendChild(text);
				new_option.setAttribute('value',choicelist[myList][i].Id);
				fragment.appendChild(new_option);
			}
		}
		list.appendChild(fragment);	
		
		for(i=0; i < choicelist[myList].length; i++)
		{
			if(choicelist['parent'][i].Id == listOption)
			{
				var element = i;
				break;
			}
		}	
		if(choicelist['parent'][element].option1 === false)
		{
			$('#child_field, #child_label').hide();
		}
		else
		{
			$('#child_field, #child_label').show();
		}
		if (choicelist['parent'][element].option2 === false)
		{ 
			$('#child2_field, #child2_label').hide();
		}
		else
		{	
			$('#child2_field, #child2_label').show();
			make2('child2', listOption);
		}
		if (choicelist['parent'][element].patient === false)
		{
			$('#patient_field, #patient_label').hide();
		}
		else
		{
			$('#patient_field, #patient_label').show();
		}	
	}

	function remove_all(list)
	{
		var list = document.getElementById(list);
		while(list.length > 0)
		{
			list.remove(0);
		}
	}

	function make(list)
	{	
		var thisOption = $(list).val();
		make_list('child', thisOption);	
	}

	function make2(myList, listOption)
	{		
		remove_all(myList);
		var fragment = document.createDocumentFragment();
		var list = document.getElementById(myList);
		for(i=0; i < choicelist[myList].length; i++)
		{
			if(choicelist[myList][i].parentId == listOption)
			{
				var new_option = document.createElement('option');
				var text = document.createTextNode(choicelist[myList][i].name);
				new_option.appendChild(text);
				new_option.setAttribute('value',choicelist[myList][i].Id);
				fragment.appendChild(new_option);
			}
		}
		list.appendChild(fragment);	
	}	

	$('#child').change(function(){ 
		struct_lists("child2");
	});
	
	$('#parent').attr('disabled', true);	
	$('#child').attr('disabled', true);	
	$('#child2').attr('disabled', true);	
	$('#more').attr('disabled', true);	
	
	$('#start_time').attr('disabled', true);	
	$('#start_day').attr('disabled', true);	
	$('#start_month').attr('disabled', true);	
	$('#start_year').attr('disabled', true);
	
	$('#end_time').attr('disabled', true);	
	$('#end_day').attr('disabled', true);	
	$('#end_month').attr('disabled', true);	
	$('#end_year').attr('disabled', true);	
	
	$('#patient').attr('disabled', true);	

	
</script>		

</html>