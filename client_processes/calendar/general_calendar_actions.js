	
	function structStartDate(stringStartDate)
	{
		var myDate = new Date(stringStartDate);
		var startDate = new Array();
		startDate['startDay'] = myDate.getDate();
		startDate['startMonth'] = myDate.getMonth() + 1; 
		startDate['startYear']  = myDate.getFullYear();

		if($("#parent option:selected").val() == 1 || ($("#parent option:selected").val() == 3))
		{
			startDate['startHour'] = "7";
			startDate['startMinute'] = "0";	
		}
		else if($("#parent option:selected").val() == 2)
		{
			startDate['startHour'] = "0";
			startDate['startMinute'] = "0";			
		}
		else{
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
		
		if($("#parent option:selected").val() == 1 || $("#parent option:selected").val() == 2 || $("#parent option:selected").val() == 3)
		{
			endDate['endHour'] = 22;
			endDate['endMinute'] = 55;				
		}
		else{
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
	
	function structCurrentDate()
	{
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

	/// Menu functions ///		

	var choicelist = new Array();

	choicelist['parent'] = new Array();
	choicelist['child'] = new Array();
	choicelist['child2'] = new Array();

	function set_choicelist(x){
		choicelist = x;
	}
	
	function struct_lists(action)
	{	
		$.ajax(
		{
			type: "POST",
			url: "../../server_processes/schedule_events_functions/calendar_lists.php",
			data:{ 
				action: action,
				child_choice: $('#child :selected').val()
				},	
			dataType: "json",
			async: false,
			global: false,						
			success: function(response)
					{
						if(response == "EXPIRED")
						{
							calendar_session_expired();
						}
						else if(action == "basic_lists")
						{
							set_choicelist(response);
						}
						else if(action == "child2")
						{
							$('#child2').html("");
							for(var i=0;i<response.length;i++)
							{
								$('#child2').append($('<option>', {
									value: response.child2[i].building_id,
									text: response.child2[i].name
								}));
							}
						}
					}
		});
	}	

	function make_list(myList, listOption)
	{ 
		remove_all(myList);
		remove_all('child2');
		$('#patient_id').val("");
		$('#ward').val("");
		$('#child_label, #child_field, #child2_label, #child2_field, #patient_label, #ward_label, #ward_field, #patient_field').hide();
		
		var fragment = document.createDocumentFragment();
		var list = document.getElementById(myList);
		for(i=0; i < choicelist[myList].length; i++)
		{	
			choicelist[myList][i].parentId;
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
		
		if (myList = 'parent')
		{			
			var element = listOption-1;
			
			if (listOption != ""){
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
				if (choicelist['parent'][element].ward_details === true)   //Change
				{
					$('#ward_field, #ward_label').show();
				}		
				else
				{
					$('#ward_field, #ward_label').hide();					
				}	
			}
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
	
	function close_form() {
		scheduler.endLightbox(false, html("my_form"));
	}
	
	function calendar_session_expired()
	{
		$.msgBox(
		{								
			title: "Session Expired",
			content: "Login again to continue..",
			success: function (myresult) {
					window.close();
			}
		});
	}