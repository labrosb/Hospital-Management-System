	var speciality_id;

	function speciality_set(id)
	{
		speciality_id = id;
	}
	
	function setUpdate()		// Switch save and update buttons
	{		
		$('#save').hide();		// depending on if its an existing or
		$('#update').show();	// a new event
		return true;
	}
	function setSave()
	{
		$('#save').show();
		$('#update').hide();
		return true;
	}
	
	$('.dhx_cal_data').click(function(){setSave();}); 
	
	$('#parent').change(function()
	{ 
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
													// Sets the right default sub category
		if (typeof speciality_id !== 'undefined' && 
			($('#parent').val() == 4 || $('#parent').val() == 5 || $('#parent').val() == 6 ))
		{
			$('#child option[value='+speciality_id+']').attr("selected",true);
		}
		
	});	
	
	$('#child').change(function()
	{ 
		struct_lists("child2");
	});
	
	$('#ward').attr('disabled', true); //Change
	
	function save_event(subType, staff_id) 			// Save event function
	{
		var check = limitations();					// Checks for all limitations set
		
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
		if($("#child2 option:selected").text() != '')
		{
			ev.text += '</br>'+$("#child2 option:selected").text();		}
		
		if($("#parent option:selected").val() == 4)
		{	
			ev.text += '</br> Patient : '+$("#patient_id").val();
		}
		if($("#more").val() != '')
		{
			ev.text += '</br> Comments : '+$("#more").val();
		}		
		ev.more = html("more").value;
		
		if ($("#parent option:selected").val() == 5)		// If Call-Duty
		{
			$.msgBox(
			{										// Ask for day off to ve included
				title: "Question",
				content: "Insert also day off?",
				type: "confirm",
				buttons: [{ value: "Yes" }, { value: "No" }, { value: "Cancel"}],
				success: function (myresult) 
				{
					if (myresult == "Yes") {				// If Yes is answered
						action_Query("insert", subType, staff_id, true);
					}
					else if (myresult == "No"){				// If No is answered
						action_Query("insert", subType, staff_id, false);
					}
				}
			});

		}
		else					// If everything but a call-duty move to other checks
		{
			action_Query("insert", subType, staff_id, false);
		}

		scheduler.endLightbox(false, html("my_form"));		//Closing form
	
	}
	
	function delete_event(subType, staff_id) 							// Delete event function
	{							
		var event_id = scheduler.getState().lightbox_id;					// Gets event id
		action_execute("delete", null, event_id, subType, staff_id, null);	// Excecutes the delete action
		scheduler.endLightbox(false, html("my_form"));						// Closes form
		scheduler.deleteEvent(event_id);									// Updates interface
	}		
	
	function update_event(subType, staff_id) 							// Update event function
	{ 							
		var check = limitations();											// Checks all limitations set
		if (check === false){
			return false;
		}
		var event_id = scheduler.getState().lightbox_id;					// Gets event id
		action_execute("update", null, event_id, subType, staff_id, null);	// Excecutes the delete action
		scheduler.endLightbox(false, html("my_form"));						// Closes form
		scheduler.deleteEvent(event_id);									// Updates interface
	}
	
	$('#okButton').click(function()
	{ 
		$('#external_field_msgs').hide();	
	});	

	
	function action_execute(action, events, event_id, subType, staff_id, auto_day_off)
	{
		var start_date = $('#start_year').val()+"-"+$('#start_month').val()+"-"+$('#start_day').val()+" "+$('#start_time').val()+":00";
		var end_date = $('#end_year').val()+"-"+$('#end_month').val()+"-"+$('#end_day').val()+" "+$('#end_time').val()+":00";
		var eventType = $("#parent option:selected").val();
		var patient_id;
		var refresh_arg;
		if(subType == null)									// In this case is the doctor schedule
		{ 									
			subType =  $("#child option:selected").val()	// If the subType is null take it from the choice 
			refresh_arg = staff_id;							// The calendar to be refreshed
			patient_id = html("patient_id").value;			// Patient's id
		}
		if(staff_id == null)								// In this case is the unit schedule
		{			
			staff_id = html("doc").value;					// If the subType is null take it from the input
			refresh_arg = subType;							// The calendar to be refreshed
		}		

		$.ajax(												// Executes action (insert,update,delete) calling the execute.php
		{							
			type: "POST",
			url: "../../server_processes/schedule_events_functions/execute.php",
			dataType: "json",	
			data:{
				  action: action,
				  event: eventType,							// arguments to pass
				  subReason: subType,
				  comments: html("more").value,
				  startDate: start_date,
				  endDate: end_date,
				  patient_id: patient_id,
				  staff_id: staff_id,
				  auto_day_off: auto_day_off,
				  id: event_id,
				  events: events
				 },
			async: false,
			global: false,						
			success: function(response)
			{
						if (response == "EXPIRED"){
							calendar_session_expired();
						}						
						else if(response.result == "done")
						{
							if (response.multiDelete == true){			// If needs to delete (overwrite) multiple events 
								var cnt = response.deleted;
								var size = cnt.length;
								for(var i=0;i < size;i++){
									var ID = response.deleted[i].Id;	
									scheduler.deleteEvent(ID);	
								}
							}
							if (response.childDelete == true)			// If needs to delete connected events 
							{			
								scheduler.deleteEvent(response.childDeleted);	
								$.msgBox(
								{
									title:"Day off deleted",
									content:"The day off that was connected with the call duty, scheduled on "+response.childDate+" is also deleted !" 
								});	

								
							}
							if (auto_day_off != null)					// If auto day off is selected
							{					
								if(response.dayOffExistance == 'true')
								{
									var dayOffDate = response.dayOffDate;	
									var dayOffday = response.dayOffday;
									var dayOffdaysAfter = response.dayOffdaysAfter;	
									
									$.msgBox(
									{
										title:"Day off inserted!",
										content:"The day off is successfully inserted at "+dayOffDate+"!" 
									});		
								}
								else{								// If all 7 next working days are occupied...
									$.msgBox(
									{
										title:"Fail",
										content:"Could not find available day-off for submission up to 7 days after the call-duty."
									});	
								}
							}
							refreshScheduler(refresh_arg);								
						}
						else if(response.result == "WARD AVAILABILITY ERROR")
						{
							$.msgBox(
							{
								title:"Fail!",
								content:"There is no ward available for this examination this date/time!"
							});				
						}
						else{
							$.msgBox(
							{
								title:"FAIL!",
								content:"An error came up!"
							});							
						}

					}
		});		
	}
	
	function action_Query(action, subType, staff_id, day_off)
	{	
		var start_date = $('#start_year').val()+"-"+$('#start_month').val()+"-"+$('#start_day').val()+" "+$('#start_time').val()+":00";
		var end_date = $('#end_year').val()+"-"+$('#end_month').val()+"-"+$('#end_day').val()+" "+$('#end_time').val()+":00";
		
		$.ajax(
		{												// Validates before action execusion 
			type: "POST",
			url: "../../server_processes/schedule_events_functions/schedulerValidation.php",
			dataType: "json",	
			data:{
				  event: html("parent").value,
				  action: action,
				  staff_id: staff_id,
				  startDate: start_date,
				  endDate: end_date,
				  dayOff: day_off
				 },	
				 
			success:function(response)
				{ 
					if (response == "EXPIRED")
					{
						calendar_session_expired();
					}						
					else if (response.dayOff != null)
					{
						var dayOffArray = response.dayOff;
					}
					else{
						var dayOffArray = null;
					}
					
					var result;
					if(response == "EXPIRED")
					{
						calendar_session_expired();
					}
					else if (response.do == 'ASK')			// If finds existing events where user needs to decide upon the action
					{
						result = $.msgBox(
						{
							title: "Question",			
							content: "The doctor has other events scheduled at that time. If you continue those events will be deleted. Are you sure??",
							type: "confirm",
							buttons: [{ value: "Yes" }, { value: "No" }],
							success: function (myresult) {
									if (myresult == "No")
									{
										return false;
									}
									else 					//leave or day-off
									{ 							
										action_execute("delete&insert", response.events, null, subType, staff_id, dayOffArray);
									}
								}
						});						
					}
					else if (response.do == 'FORCE')		// If existing events are off less significance (forces action)
					{
						action_execute("delete&insert", response.events, null, subType, staff_id, dayOffArray);
					}
					else if (response.do == 'NOT')			// If existing events are important (prevents action)
					{
						$.msgBox(
						{
							title:"Fail",
							content:"The doctor has other events scheduled at that time which can't be deleted!"
						});	
					}
					else if (response.do == 'NTN')			// Register events without deleting the existing ones
					{		
						action_execute("insert", null, null, subType, staff_id, dayOffArray);
					}					
				}
		});	
	}	
	
	
	function CheckIfExists($who, $id)						// Function to check if entity (patient or doctor) exists
	{
		var result = $.ajax(
		{
			type: "POST",
			url: "../../server_processes/general_functions/existence_checker.php",
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
	{														// Sets start/end time		
		var start_time = $('#start_time').val().split(":");			
		var end_time = $('#end_time').val().split(":");
		
		var start_date_num = $('#start_year').val()+""+$('#start_month').val()+""+$('#start_day').val()+""+start_time[0]+""+start_time[1];
		var end_date_num = $('#end_year').val()+""+$('#end_month').val()+""+$('#end_day').val()+""+end_time[0]+""+end_time[1];

		var start = new Date();
		start.setFullYear($('#start_year').val(),$('#start_month').val()-1,$('#start_day').val());
		var end = new Date();
		end.setFullYear($('#end_year').val(),$('#end_month').val()-1,$('#end_day').val());	

		var start_day = start.getDay();	
		var end_day = end.getDay();	
		
		var currentDate = structCurrentDate();		// Current date
		
		if(start_date_num < currentDate)			// If start date set older than the current one
		{ 		
			$(".msgBoxContent").html("<p><span>The date that you selected is older than the current one!</span></p>");
			$("#external_field_msgs").show();				
			return false;
		}
		
		if(start_date_num > end_date_num)			// If the start date is older than the end date 
		{ 		
			$(".msgBoxContent").html("<p><span>The start date must be older than the end date!</span></p>");
			$("#external_field_msgs").show();						
			return false;
		}	
		
		if ( $("#parent option:selected").val() == 1 || 		// If Leave or Sick-leave
			 $("#parent option:selected").val() == 2 )
		{
			if(start_day == 0 || end_day == 6 || 				// If starts or ends in Weekend... error msg
			   start_day == 6 || end_day == 0 )
			{																			
				$(".msgBoxContent").html("<p><span>This event can't be inserted in weekend days!</span></p>");
				$("#external_field_msgs").show();
				return false;
			}
			
		}
		else if ( $("#parent option:selected").val() == 3 ||	// If Day-off
				  $("#parent option:selected").val() == 4 ||	// or Examination
				  $("#parent option:selected").val() == 6 ) 	// or Work-Shift
		{																			
			if(start_day == 0 || end_day == 6 || 				// If in Weekend... error msg
			   start_day == 6 || end_day == 0 )
			{																		
				$(".msgBoxContent").html("<p><span>This event can't be inserted in weekend days!</span></p>");
				$("#external_field_msgs").show();
				return false;
			}
			else if( (!isInSTRICTWorkingHours()) )				// If NOT in working hours of the same day... error msg
			{																				
				$(".msgBoxContent").html("<p><span>The event is set out of the limits of schedule!</span></p>");
				$("#external_field_msgs").show();
				return false;
				
			}
		}
		else if ( $("#parent option:selected").val() == 5)		// If Call Duty			
		{   
			if ( !((start_day == 0 && end_day == 0) ||			 
				   (start_day == 6 && end_day == 0) ||			// If NOT in Weekend
				   (start_day == 6 && end_day == 6)) )
			{	
				if (!callDutyHours())							// If does conflict with working hours... error msg
				{
					$(".msgBoxContent").html("<p><span>The event is set out of the limits of schedule!</span></p>");
					$("#external_field_msgs").show();
					return false;
				
				}
			}
		}
		if($("#parent option:selected").val() == 4)				// If Examination
		{	
			if($("#patient_id").val() == '')					// If empty code
			{					
				$(".msgBoxContent").html("<p><span>Insert the patient's code!</span></p>");
				$("#external_field_msgs").show();				
				return false;
			}
			else{
				var patientId = $("#patient_id").val();
				var thisPatient = CheckIfExists('patient', patientId);
				
				if(thisPatient == "EXPIRED")
				{
					calendar_session_expired();
				}
				else if(thisPatient == 'NOT EXISTS')			// If patient does not exist
				{		
					$(".msgBoxContent").html("<p><span>No patient found with that code!</span></p>");
					$("#external_field_msgs").show();
					return false;
				}
			}
		} 
		if( $('#doc').length > 0)
		{			
			if($("#doc").val() == '')					// If doctor field is empty (for units)
			{					
				$(".msgBoxContent").html("<p><span>Insert the doctor's code!</span></p>");
				$("#external_field_msgs").show();				
				return false;
			}
			else
			{											// If no doctor found
				var docId = $("#doc").val();		
				var thisDoctor = CheckIfExists('doctor', docId);
				if(thisDoctor == "EXPIRED")
				{
					calendar_session_expired();
				}
				else if(thisDoctor == 'NOT EXISTS')
				{
					$(".msgBoxContent").html("<p><span>No doctor found with that code!</span></p>");
					$("#external_field_msgs").show();					
					return false;
				}
			}	
		}		
	}		

	function isInWorkingHours()							// Checks if the calendar set times
	{													// correspond to working hours
		result = false;								
		if( $('#start_time').val() >= '07:00' && $('#end_time').val() < '23:00')
		{ 													
			result = true;					
		}
		return result;
	}
	
	function isInSTRICTWorkingHours()					// Checks if the calendar set times correspond to working hours
	{													// !! Strictly in the same day !!
		var start_date = $('#start_year').val()+""+$('#start_month').val()+""+$('#start_day').val();
		var end_date = $('#end_year').val()+""+$('#end_month').val()+""+$('#end_day').val();
		
		result = false;
				
		if(start_date == end_date)						// If start and end in the same day!!	
		{					
			if( ($('#start_time').val() >= '07:00' && $('#end_time').val() < '23:00') &&  $('#end_time').val() > '07:00')
			{ 													
				result = true;					
			}
		}
		return result;
	}
		
	function callDutyHours()				// Checks if the calendar set times correspond to working hours
	{			
		result = false;										
		
		if ( ($('#start_time').val() < '07:00' && $('#end_time').val() < '07:00') || 
			 ($('#start_time').val() > '23:00' && $('#end_time').val() > '23:00') ||
			 ($('#start_time').val() > '23:00' && $('#end_time').val() < '07:00') ) 
		{ 					
			result = true;			
		}		
		return result;
	}	
	